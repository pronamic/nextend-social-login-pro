<?php
require_once NSL_PATH . '/includes/oauth2.php';

class NextendSocialProviderLinkedInClient extends NextendSocialOauth2 {

    protected $access_token_data = array(
        'access_token' => '',
        'expires_in'   => -1,
        'created'      => -1
    );

    protected $endpointAuthorization = 'https://www.linkedin.com/oauth/v2/authorization';
    protected $endpointAccessToken = 'https://www.linkedin.com/oauth/v2/accessToken';
    protected $endpointRestAPI = 'https://api.linkedin.com/v2';

    protected $scopes = array(
        'openid',
        'profile',
        'email'
    );

    /**
     * @param string $access_token_data
     */
    public function setAccessTokenData($access_token_data) {
        $this->access_token_data = json_decode($access_token_data, true);
    }

    /**
     * @param $response
     *
     * @throws Exception
     */
    protected function errorFromResponse($response) {
        if (isset($response['serviceErrorCode']) && isset($response['message'])) {
            throw new Exception($response['serviceErrorCode'] . ' - ' . $response['message']);
        } else {
            parent::errorFromResponse($response);
        }
    }

}