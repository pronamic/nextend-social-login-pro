<?php
require_once NSL_PATH . '/includes/oauth2.php';

class NextendSocialProviderTwitchClient extends NextendSocialOauth2 {

    private $prompt = '';

    protected $access_token_data = array(
        'access_token' => '',
        'expires_in'   => -1,
        'created'      => -1
    );

    protected $endpointAuthorization = 'https://id.twitch.tv/oauth2/authorize';

    protected $endpointAccessToken = 'https://id.twitch.tv/oauth2/token';

    protected $endpointRestAPI = 'https://api.twitch.tv/helix';


    protected function formatScopes($scopes) {
        return implode(' ', $scopes);
    }

    protected $scopes = array(
        'user:read:email'
    );

    public function createAuthUrl() {

        $args = [];

        if ($this->prompt != '') {
            $args['force_verify'] = "true";
        }


        return add_query_arg($args, parent::createAuthUrl());
    }

    protected function extendHttpArgs($http_args) {
        $http_args                         = parent::extendHttpArgs($http_args);
        $http_args['headers']['Client-Id'] = $this->client_id;

        return $http_args;
    }

    /**
     * @param $response
     *
     * @throws Exception
     */
    protected function errorFromResponse($response) {
        if (isset($response['error']) && isset($response['message'])) {
            throw new Exception($response['error'] . ' - ' . $response['message']);
        } else if (isset($response['status']) && isset($response['message'])) {
            throw new Exception($response['status'] . ' - ' . $response['message']);
        } else {
            parent::errorFromResponse($response);
        }
    }

    /**
     * @param string $prompt
     */
    public function setPrompt($prompt) {
        $this->prompt = $prompt;
    }
}

