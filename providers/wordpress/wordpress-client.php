<?php
require_once NSL_PATH . '/includes/oauth2.php';

class NextendSocialProviderWordpressClient extends NextendSocialOauth2 {

    protected $access_token_data = array(
        'access_token' => '',
        'expires_in'   => -1,
        'created'      => -1
    );

    protected $endpointAuthorization = 'https://public-api.wordpress.com/oauth2/authenticate';

    protected $endpointAccessToken = 'https://public-api.wordpress.com/oauth2/token';

    protected $endpointRestAPI = 'https://public-api.wordpress.com/rest/v1.1';

    protected $defaultRestParams = array(
        'format' => 'json',
    );

    protected $scopes = array('auth');

    /**
     * @param $response
     *
     * @throws Exception
     */
    protected function errorFromResponse($response) {
        if (isset($response['error']) && isset($response['message'])) {
            throw new Exception($response['error'] . ' - ' . $response['message']);
        } else {
            parent::errorFromResponse($response);
        }
    }
}

