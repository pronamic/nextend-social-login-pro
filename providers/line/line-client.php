<?php
require_once NSL_PATH . '/includes/oauth2.php';

class NextendSocialProviderLineClient extends NextendSocialOauth2 {

    protected $access_token_data = array(
        'access_token' => '',
        'expires_in'   => -1,
        'created'      => -1
    );

    private $prompt = '';
    private $bot_prompt = '';
    private $initial_amr_display = '';
    private $switch_amr = '';
    private $allow_auto_login = false;

    protected $endpointAuthorization = 'https://access.line.me/oauth2/v2.1/authorize';

    protected $endpointAccessToken = 'https://api.line.me/oauth2/v2.1/token';

    protected $endpointRestAPI = 'https://api.line.me/v2';

    protected $defaultRestParams = array(
        'format' => 'json',
    );

    protected $scopes = array(
        'profile',
        'openid',
        'email'
    );

    public function createAuthUrl() {
        $args = array();

        if ($this->prompt != '') {
            $args['prompt'] = urlencode($this->prompt);
        }
        if ($this->bot_prompt != '') {
            $args['bot_prompt'] = urlencode($this->bot_prompt);
        }

        if ($this->initial_amr_display !== '') {
            $args['initial_amr_display'] = $this->initial_amr_display;
        }

        if ($this->switch_amr != '') {
            $args['switch_amr'] = $this->switch_amr;
        }

        if (!$this->allow_auto_login) {
            $args['disable_auto_login']     = 'true';
            $args['disable_ios_auto_login'] = 'true';
        }


        return add_query_arg($args, parent::createAuthUrl());
    }

    /**
     * @param string $prompt
     */
    public function setPrompt($prompt) {
        $this->prompt = $prompt;
    }

    /**
     * @param string $bot_prompt
     */
    public function setBotPrompt($bot_prompt) {
        $this->bot_prompt = $bot_prompt;
    }

    /**
     * @param string $initial_amr_display
     */
    public function setInitialLoginMethod($initial_amr_display) {
        $this->initial_amr_display = $initial_amr_display;
    }

    /**
     * @param string $switch_amr
     */
    public function setForceInitialLoginMethod($switch_amr) {
        $this->switch_amr = $switch_amr;
    }

    public function setAllowAutoLogin($allow_auto_login) {
        $this->allow_auto_login = $allow_auto_login;
    }

    public function getLineIdToken() {
        if (isset($this->access_token_data['id_token']) && !empty($this->access_token_data['id_token'])) {
            return $this->access_token_data['id_token'];
        }

        return false;
    }
}

