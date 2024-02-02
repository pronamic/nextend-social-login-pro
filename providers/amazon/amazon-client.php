<?php
require_once NSL_PATH . '/includes/oauth2.php';

class NextendSocialProviderAmazonClient extends NextendSocialOauth2 {

    protected $access_token_data = array(
        'access_token' => '',
        'expires_in'   => -1,
        'created'      => -1
    );

    protected $endpointAuthorization = 'https://www.amazon.com/ap/oa';
    protected $endpointAccessToken = 'https://api.amazon.com/auth/o2/token';
    protected $endpointRestAPI = 'https://api.amazon.com/';

    protected $defaultRestParams = array(
        'format' => 'json'
    );

    protected $scopes = array(
        'profile'
    );
}