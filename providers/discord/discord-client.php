<?php
require_once NSL_PATH . '/includes/oauth2.php';

class NextendSocialProviderDiscordClient extends NextendSocialOauth2 {

    protected $access_token_data = array(
        'access_token' => '',
        'expires_in'   => -1,
        'created'      => -1
    );

    private $prompt = 'consent';

    protected $endpointAuthorization = 'https://discord.com/api/oauth2/authorize';

    protected $endpointAccessToken = 'https://discord.com/api/oauth2/token';

    protected $endpointRestAPI = 'https://discord.com/api/v10';

    protected $defaultRestParams = array(
        'format' => 'json',
    );


    protected $scopes = array(
        'identify',
        'email'
    );

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
}

