<?php

use NSL\Persistent\Persistent;

require_once NSL_PATH . '/includes/oauth2.php';

class NextendSocialProviderAppleClient extends NextendSocialOauth2 {

    protected $access_token_data = array(
        'access_token' => '',
        'expires_in'   => -1,
        'created'      => -1
    );

    protected $endpointAuthorization = 'https://appleid.apple.com/auth/authorize';
    protected $endpointAccessToken = 'https://appleid.apple.com/auth/token';
    protected $endpointRestAPI = '';

    protected $defaultRestParams = array(
        'format' => 'json'
    );

    protected $scopes = array(
        'name email'
    );

    public function getAppleIdToken() {
        if (isset($this->access_token_data['id_token']) && !empty($this->access_token_data['id_token'])) {
            return $this->access_token_data['id_token'];
        }

        return false;
    }

    public function getAppleUserData() {
        if (isset($this->access_token_data['user']) && !empty($this->access_token_data['user'])) {
            return $this->access_token_data['user'];
        }

        return false;
    }


    public function createAuthUrl() {

        $args = array(
            'client_id'     => urlencode($this->client_id),
            'redirect_uri'  => urlencode($this->redirect_uri),
            'response_type' => 'code',
            'response_mode' => 'form_post',
            'state'         => urlencode($this->getState())
        );

        $scopes = apply_filters('nsl_' . $this->providerID . '_scopes', $this->scopes);
        if (count($scopes)) {
            $args['scope'] = rawurlencode($this->formatScopes($scopes));
        }

        $args = apply_filters('nsl_' . $this->providerID . '_auth_url_args', $args);

        return add_query_arg($args, $this->getEndpointAuthorization());
    }

    public function authenticate() {
        if (isset($_POST['code'])) {
            if (!$this->validateState()) {
                throw  new Exception('Unable to validate CSRF state');
            }

            $http_args = array(
                'timeout'    => 15,
                'user-agent' => 'WordPress',
                'headers'    => array(
                    'Authorization' => 'Basic  ' . base64_encode($this->client_id . ':' . $this->client_secret)
                ),
                'body'       => array(
                    'grant_type'    => 'authorization_code',
                    'code'          => $_POST['code'],
                    'redirect_uri'  => $this->redirect_uri,
                    'client_id'     => $this->client_id,
                    'client_secret' => $this->client_secret
                )
            );

            $request = wp_remote_post($this->endpointAccessToken, $this->extendAllHttpArgs($http_args));

            if (is_wp_error($request)) {

                throw new Exception($request->get_error_message());
            } else if (wp_remote_retrieve_response_code($request) !== 200) {

                $this->errorFromResponse(json_decode(wp_remote_retrieve_body($request), true));
            }

            $accessTokenData = json_decode(wp_remote_retrieve_body($request), true);

            if (!is_array($accessTokenData)) {
                throw new Exception(sprintf(__('Unexpected response: %s', 'nextend-facebook-connect'), wp_remote_retrieve_body($request)));
            }

            /*
             * Apple sends the name and email in the $_POST the very first time the user authorizes the App!
             */

            if (isset($_POST['user']) && !empty($_POST['user'])) {
                $accessTokenData['user'] = json_decode(stripslashes($_POST['user']), true);
            }

            $accessTokenData['created'] = time();

            $this->access_token_data = $accessTokenData;

            return wp_json_encode($accessTokenData);
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
            throw new Exception($response['error']);
        }
    }

    /**
     * If the stored state is the same as the state we have received from the remote Provider, it is valid.
     *
     * @return bool
     */
    protected function validateState() {
        $this->state = Persistent::get($this->providerID . '_state');
        if ($this->state === false) {
            return false;
        }

        if (empty($_POST['state'])) {
            return false;
        }

        if ($_POST['state'] == $this->state) {
            return true;
        }

        return false;
    }
}