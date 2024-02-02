<?php
require_once NSL_PATH . '/includes/oauth2.php';

class NextendSocialProviderRedditClient extends NextendSocialOauth2 {

    protected $access_token_data = array(
        'access_token' => '',
        'expires_in'   => -1,
        'created'      => -1
    );

    protected $endpointAuthorization = 'https://www.reddit.com/api/v1/authorize';

    protected $endpointAccessToken = 'https://www.reddit.com/api/v1/access_token';

    protected $endpointRestAPI = 'https://oauth.reddit.com/api/v1';

    protected $scopes = array(
        'identity'
    );

    protected function extendAuthenticateHttpArgs($http_args) {
        $http_args['headers'] = [
            'Authorization' => 'Basic ' . base64_encode($this->client_id . ':' . $this->client_secret)
        ];

        if (isset($http_args['body']['client_id'])) {
            unset($http_args['body']['client_id']);
        }

        if (isset($http_args['body']['client_secret'])) {
            unset($http_args['body']['client_secret']);
        }

        return $http_args;
    }

    public function createAuthUrl() {
        $args = array();

        $args['duration'] = 'temporary';

        return add_query_arg($args, parent::createAuthUrl());
    }

    /**
     * @param $response
     *
     * @throws Exception
     */
    protected function errorFromResponse($response) {
        if (isset($response['message']) && isset($response['error'])) {
            throw new Exception($response['message'] . ' - ' . $response['error']);
        } else {
            parent::errorFromResponse($response);
        }
    }
}

