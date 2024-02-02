<?php

use NSLPro\LightOpenID\LightOpenID;
use NSL\Persistent\Persistent;

require_once NSL_PATH . '/includes/auth.php';


abstract class NextendSocialOpenID extends NextendSocialAuth {

    /**
     * Instance of LightOpenID
     *
     * @var object|null
     */
    protected $openIdClient = null;

    /*
     * The Identity endpoint used for discovering the authorization url.
     */
    protected $endpointOpenIdIdentity;

    /**
     * Normally the data comes back with the Open ID request. But some OpenID providers might have a REST API as well,
     * where some additional data can be retrieved from.
     *
     * @var string
     */
    protected $endpointRestAPI;

    protected $defaultRestParams = array();


    public function __construct($providerID, $redirect_uri) {

        parent::__construct($providerID);

        $port   = parse_url($redirect_uri, PHP_URL_PORT);
        $domain = parse_url($redirect_uri, PHP_URL_HOST);

        if ($port) {
            $domain .= ':' . $port;
        }

        $this->openIdClient = new LightOpenID($domain);
        $this->openIdClient->__set('identity', $this->endpointOpenIdIdentity);
        $this->openIdClient->returnUrl = $redirect_uri;
        $this->openIdClient->required  = array(
            'namePerson/friendly',
            'contact/email',
            'namePerson',
            'birthDate',
            'person/gender',
            'contact/postalCode/home',
            'contact/country/home',
            'pref/language',
            'pref/timezone'
        );
    }

    public function checkError() {
        if (isset($_REQUEST['error']) && isset($_REQUEST['error_description'])) {
            throw new Exception($_REQUEST['error'] . ': ' . htmlspecialchars_decode($_REQUEST['error_description']));
        }
    }

    public function getTestUrl() {
        return $this->endpointOpenIdIdentity;
    }

    public function hasAuthenticateData() {
        if (isset($_REQUEST['openid_mode']) && $_REQUEST['openid_mode'] === 'id_res' && isset($_REQUEST['openid_response_nonce']) && isset($_REQUEST['openid_sig'])) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function createAuthUrl() {
        $args = apply_filters('nsl_' . $this->providerID . '_auth_url_args', array());
        try {
            $authUrl = $this->openIdClient->authUrl();
            /**
             * We can only get the server after the discovery has happened in the authUrl() function.
             */
            $server = $this->openIdClient->getServer();
            if ($server) {
                /**
                 * We need to store the server that the initial discovery request returned.
                 * So later on we can use this value to check if the verification was done by the same server or not.
                 */
                Persistent::set($this->providerID . '_openid_server', $server);

                return add_query_arg($args, $authUrl);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        throw new Exception(__('Unexpected response: The provider didn\'t return the Authorization URL!', 'nextend-facebook-connect'));
    }

    /**
     * @return bool|false|string
     *
     * If the authentication data has been verified, then we could return the data
     *
     * @throws Exception
     */
    public function authenticate() {

        if ($this->openIdClient->mode) {
            switch ($this->openIdClient->mode) {
                case 'cancel':
                    throw new Exception(__('Unexpected response: Authentication has been cancelled!', 'nextend-facebook-connect'));
                    break;
                case 'setup_needed':
                    throw new Exception(__('Unexpected response: Immediate request failed - Setup Needed!', 'nextend-facebook-connect'));
                    break;
            }

            if ($this->openIdClient->validate()) {
                /**
                 * To prevent request forgery, we need to check if the server that validated the response is the same server that we discovered when we created the Authorization URL!
                 */
                if ($this->openIdClient->getServer() === Persistent::get($this->providerID . '_openid_server')) {
                    $openIdClaimedId = $this->openIdClient->__get('identity');
                    if ($openIdClaimedId) {
                        /**
                         * The Open ID claims have been verified and we confirmed that the verification was made by the same server that we discovered at the time we generated the authorization url.
                         * This means we can use the Claimed Identifier freely as a unique identifier for the user later on.
                         * This data comes from $_GET parameter, so we should sanitize it.
                         */

                        return sanitize_text_field($openIdClaimedId);
                    }
                } else {
                    throw new Exception(__('Unexpected response: The targeted OpenID server is not authorized to verify this request!', 'nextend-facebook-connect'));
                }

            } else {
                throw new Exception(__('Unexpected response: OpenID Verification failed!', 'nextend-facebook-connect'));
            }
        }

        return false;
    }

    /**
     * @param $response
     *
     * @throws Exception
     */
    protected function errorFromResponse($response) {
        if (isset($response['error'])) {
            throw new Exception($response['error'] . ': ' . $response['error_description']);
        }
    }

    /**
     * Can be used for getting additional data from a REST API if the Open ID provider has one.
     *
     * @param       $path
     * @param array $data
     * @param       $endpoint
     *
     * @return array
     * @throws Exception
     */
    public function get($path, $data = array(), $endpoint = false) {
        $http_args = array(
            'timeout'    => 15,
            'user-agent' => 'WordPress',
            'body'       => array_merge($this->defaultRestParams, $data)
        );

        if (!$endpoint) {
            $endpoint = $this->endpointRestAPI;
        }

        $request = wp_remote_get($endpoint . $path, $this->extendAllHttpArgs($http_args));

        if (is_wp_error($request)) {

            throw new Exception($request->get_error_message());
        } else if (wp_remote_retrieve_response_code($request) !== 200) {

            $this->errorFromResponse(json_decode(wp_remote_retrieve_body($request), true));
        }

        $result = json_decode(wp_remote_retrieve_body($request), true);

        if (!is_array($result)) {
            throw new Exception(sprintf(__('Unexpected response: %s', 'nextend-facebook-connect'), wp_remote_retrieve_body($request)));
        }

        return $result;
    }

    protected function extendAllHttpArgs($http_args) {

        return $http_args;
    }

}