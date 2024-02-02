<?php
require_once NSL_PATH . '/includes/oauth2.php';

class NextendSocialProviderMicrosoftClient extends NextendSocialOauth2 {

    protected $access_token_data = array(
        'access_token' => '',
        'expires_in'   => -1,
        'created'      => -1
    );

    protected $endpointRestAPI = 'https://graph.microsoft.com/v1.0';

    private $prompt = 'select_account';

    protected $scopes = array(
        'openid',
        'profile',
        'User.Read'
    );

    public function __construct($providerID, $tenant) {
        parent::__construct($providerID);
        $this->endpointAuthorization = 'https://login.microsoftonline.com/' . $tenant . '/oauth2/v2.0/authorize';
        $this->endpointAccessToken   = 'https://login.microsoftonline.com/' . $tenant . '/oauth2/v2.0/token';
    }

    public function createAuthUrl() {
        $args = array();
        if ($this->prompt != '') {
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

    protected function errorFromResponse($response) {
        if (isset($response['error']) && isset($response['error']['message'])) {
            throw new Exception($response['error']['code'] . ' - ' . $response['error']['message']);
        } else {
            parent::errorFromResponse($response);
        }
    }


}

