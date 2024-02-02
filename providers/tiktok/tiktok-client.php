<?php
require_once NSL_PATH . '/includes/oauth2.php';

class NextendSocialProviderTiktokClient extends NextendSocialOauth2 {

    protected $access_token_data = array(
        'access_token' => '',
        'expires_in'   => -1,
        'created'      => -1
    );

    protected $redirect_uri = '';

    protected $endpointAuthorization = 'https://www.tiktok.com/v2/auth/authorize/';
    protected $endpointAccessToken = 'https://open.tiktokapis.com/v2/oauth/token/';
    protected $endpointRestAPI = 'https://open.tiktokapis.com/v2/';

    protected $scopes = array(
        'user.info.basic'
    );

    protected function formatScopes($scopes) {
        return implode(',', $scopes);
    }

    public function createAuthUrl() {

        $args = array(
            'client_key'    => urlencode($this->client_id),
            'redirect_uri'  => urlencode($this->redirect_uri),
            'state'         => urlencode($this->getState()),
            'response_type' => 'code'
        );

        $scopes = apply_filters('nsl_' . $this->providerID . '_scopes', $this->scopes);
        if (count($scopes)) {
            $args['scope'] = urlencode($this->formatScopes($scopes));
        }

        $args = apply_filters('nsl_' . $this->providerID . '_auth_url_args', $args);

        return add_query_arg($args, $this->getEndpointAuthorization());
    }

    protected function extendAuthenticateHttpArgs($http_args) {
        $http_args['headers'] = [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];

        $http_args['body'] = [
            'client_key'    => $this->client_id,
            'client_secret' => $this->client_secret,
            'code'          => $_GET['code'],
            'grant_type'    => 'authorization_code',
            'redirect_uri'  => $this->redirect_uri
        ];

        return $http_args;
    }

    /**
     * @param $access_token_data
     *
     * @return array
     * @throws Exception
     */
    protected function extendAccessTokenData($access_token_data) {
        if (!empty($access_token_data['access_token']) && !empty($access_token_data['open_id'])) {
            return $access_token_data;
        }

        /**
         * TikTok returns status code 200 even if there is an error.
         * If there is no access_token set in the response then we need to throw an error.
         */
        throw new Exception(json_encode($access_token_data));
    }

    /**
     * @param $response
     *
     * @throws Exception
     */
    protected function errorFromResponse($response) {
        if (isset($response['message'])) {
            if (isset($response['data']) && isset($response['data']['description'])) {
                throw new Exception($response['data']['description']);
            } else {
                throw new Exception($response['message']);
            }
        } else if (isset($response['error']) && isset($response['error']['message'])) {
            throw new Exception($response['error']['message']);
        } else {
            parent::errorFromResponse($response);
        }
    }
}