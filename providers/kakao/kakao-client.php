<?php
require_once NSL_PATH . '/includes/oauth2.php';

class NextendSocialProviderKakaoClient extends NextendSocialOauth2 {

    protected $access_token_data = array(
        'access_token' => '',
        'expires_in'   => -1,
        'created'      => -1
    );

    private $prompt = 'select_account';

    protected $endpointAuthorization = 'https://kauth.kakao.com/oauth/authorize';
    protected $endpointAccessToken = 'https://kauth.kakao.com/oauth/token';
    protected $endpointRestAPI = 'https://kapi.kakao.com/v2/user';

    protected $scopes = array(
        'profile_nickname',
        'account_email',
        'profile_image'
    );

    public function createAuthUrl() {

        if ($this->prompt != '') {
            /**
             * login, create or select_account
             * The "none" option - used for Auto-login - shouldn't be offered here, is it returns an error - which breaks the OAuth flow - when a user interaction is required.
             * E.g. when the user has to authenticate to Kakao.
             *
             */
            $args['prompt'] = urlencode($this->prompt);
        }

        return add_query_arg($args, parent::createAuthUrl());
    }

    /**
     * @param string $prompt
     */
    public function setPrompt($prompt) {
        $this->prompt = $prompt;
    }

    protected function extendAuthenticateHttpArgs($http_args) {
        $http_args['headers'] = [
            'Content-Type' => 'application/x-www-form-urlencoded;charset=utf-8'
        ];

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

