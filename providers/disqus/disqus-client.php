<?php
require_once NSL_PATH . '/includes/oauth2.php';

class NextendSocialProviderDisqusClient extends NextendSocialOauth2 {

    protected $access_token_data = array(
        'access_token' => '',
        'expires_in'   => -1,
        'created'      => -1
    );

    protected $endpointAuthorization = 'https://disqus.com/api/oauth/2.0/authorize/';

    protected $endpointAccessToken = 'https://disqus.com/api/oauth/2.0/access_token/';

    protected $endpointRestAPI = 'https://disqus.com/api/3.0';

    protected $defaultRestParams = array();

    protected function formatScopes($scopes) {
        return implode(',', $scopes);
    }

    protected $scopes = array(
        'read',
        'email'
    );

    public function get($path, $data = array(), $endpoint = false) {
        $data = parent::get($path, $data, $endpoint);

        return $data['response'];
    }


    protected function extendAllHttpArgs($http_args) {
        $http_args['body']['api_key'] = $this->client_id;

        return $http_args;
    }

    /**
     * @param $response
     *
     * @throws Exception
     */
    protected function errorFromResponse($response) {
        if (isset($response['code']) && isset($response['response'])) {
            throw new Exception($response['code'] . ' - ' . $response['response']);
        } else {
            parent::errorFromResponse($response);
        }
    }
}

