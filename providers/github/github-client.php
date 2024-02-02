<?php
require_once NSL_PATH . '/includes/oauth2.php';

class NextendSocialProviderGitHubClient extends NextendSocialOauth2 {

    protected $access_token_data = array(
        'access_token' => '',
        'expires_in'   => -1,
        'created'      => -1
    );

    protected $endpointAuthorization = 'https://github.com/login/oauth/authorize';
    protected $endpointAccessToken = 'https://github.com/login/oauth/access_token';
    protected $endpointRestAPI = 'https://api.github.com/user';

    /**
     * @param $http_args
     * Puts additional data into the http header.
     * Used for getting the access token in JSON format.
     *
     * @return mixed
     */
    protected function extendAllHttpArgs($http_args) {
        if ($this->client_id && $this->client_secret) {
            $http_args['headers'] = array(
                'Accept' => 'application/json'
            );
        }

        return $http_args;
    }

    /**
     * @param $response
     *
     * @throws Exception
     */
    protected function errorFromResponse($response) {
        if (isset($response['message'])) {
            throw new Exception($response['message']);
        } else {
            parent::errorFromResponse($response);
        }
    }

    /**
     * @return bool|false|string
     * If the code that was sent by the selected provider and the state is valid,
     * we can make a request for an accessToken with wp_remote_post().
     * The result contains HTTP headers and content.
     *
     * Returns the accessToken with which we can make certain requests for their user profile data.
     * @throws Exception
     */
    public function authenticate() {

        if (isset($_GET['code'])) {
            if (!$this->validateState()) {
                throw  new Exception('Unable to validate CSRF state');
            }

            $http_args = array(
                'timeout'    => 15,
                'user-agent' => 'WordPress',
                'body'       => array(
                    'grant_type'    => 'authorization_code',
                    'code'          => $_GET['code'],
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
            } else if (isset($accessTokenData['error'])) {
                /**
                 * GitHub returns response code 200 when Client secret is invalid.
                 */
                $this->errorFromResponse($accessTokenData);
            }

            $accessTokenData['created'] = time();

            $this->access_token_data = $accessTokenData;

            return wp_json_encode($accessTokenData);
        }

        return false;
    }
}