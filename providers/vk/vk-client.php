<?php
require_once NSL_PATH . '/includes/oauth2.php';

class NextendSocialProviderVKClient extends NextendSocialOauth2 {

    protected $access_token_data = array(
        'access_token' => '',
        'expires_in'   => -1,
        'created'      => -1
    );

    protected $endpointAuthorization = 'https://oauth.vk.com/authorize?v=5.131';
    protected $endpointAccessToken = 'https://oauth.vk.com/access_token';
    protected $endpointRestAPI = 'https://api.vk.com/method/';

    protected $defaultRestParams = array(
        'format' => 'json',
        'v'      => '5.131'
    );

    protected $scopes = array(
        'wall',
        'email'
    );

    protected function extendHttpArgs($http_args) {
        $http_args['body']['access_token'] = $this->access_token_data['access_token'];

        return $http_args;
    }

    public function getEmail() {
        if (!empty($this->access_token_data['email'])) {
            return $this->access_token_data['email'];
        }

        return '';
    }
}