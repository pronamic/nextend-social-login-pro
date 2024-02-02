<?php
require_once NSL_PATH . '/includes/oauth2.php';

class NextendSocialProviderPaypalClient extends NextendSocialOauth2 {

    protected $access_token_data = array(
        'access_token' => '',
        'expires_in'   => -1,
        'created'      => -1
    );


    protected $endpointAuthorization = 'https://www.paypal.com/signin/authorize';

    protected $endpointAccessToken = 'https://api-m.paypal.com/v1/oauth2/token';

    protected $endpointRestAPI = 'https://api-m.paypal.com/v1/identity/oauth2';


    protected $scopes = array(
        'openid',
        'profile'
    );

    /**
     * @param string $scope_email
     */
    public function setScopeEmail($scope_email) {
        if ($scope_email) {
            $this->scopes[] = 'email';
        }
    }

    /**
     * @param $http_args
     * Puts additional data into the http header.
     * Used for getting access to the resources with a basic token with base64(client_id:client_secret) format.
     *
     * @return mixed
     */
    protected function extendAllHttpArgs($http_args) {
        if ($this->client_id && $this->client_secret) {
            $http_args['headers'] = array(
                'Content-Type'  => 'application/x-www-form-urlencoded',
                'Authorization' => 'Basic  ' . base64_encode($this->client_id . ':' . $this->client_secret)
            );
        }

        return $http_args;
    }


}

