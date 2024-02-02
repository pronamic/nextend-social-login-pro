<?php
require_once NSL_PATH . '/includes/oauth2.php';

class NextendSocialProviderYahooClient extends NextendSocialOauth2 {

    protected $access_token_data = array(
        'access_token' => '',
        'expires_in'   => -1,
        'created'      => -1
    );

    protected $endpointAuthorization = 'https://api.login.yahoo.com/oauth2/request_auth';

    protected $endpointAccessToken = 'https://api.login.yahoo.com/oauth2/get_token';

    protected $endpointRestAPI = 'https://api.login.yahoo.com/openid/v1/';

    protected $defaultRestParams = array(
        'format' => 'json',
    );

    protected $scopes = array('profile email');
}

