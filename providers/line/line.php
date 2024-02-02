<?php

use NSL\Notices;

class NextendSocialPROProviderLine extends NextendSocialProviderOAuth {

    /** @var NextendSocialProviderLineClient */
    protected $client;

    protected $color = '#06C755';

    protected $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path fill="#fff" fill-rule="evenodd" d="M23.9917 11.1626c-.0125.1941-.0398.4415-.0949.7355-.1476.9364-.4616 1.8307-.917 2.6636-.2146.3924-1.2669 1.8861-1.5896 2.2799-1.7735 2.1644-4.7435 4.6627-9.7102 7.0911-.4634.2267-.9922-.1503-.9354-.6684l.2503-2.2824c.0399-.3638-.2193-.6925-.5787-.7306C4.5376 19.6278 0 15.5746 0 10.6672 0 5.32816 5.37099 1 11.9966 1c6.4387 0 11.6927 4.08755 11.9838 9.2181.0084.1488.032.6243.0113.9445zm-9.8475-2.60911v2.86741s-2.4507-3.23845-2.4881-3.28101c-.1171-.13313-.2896-.21503-.4811-.20767-.3338.01283-.5898.30838-.5898.64678v4.6682c0 .3433.2747.6217.6136.6217.3389 0 .6137-.2784.6137-.6217v-2.8501s2.488 3.2667 2.5245 3.3018c.1085.1041.2543.1685.4152.17.3408.0031.6194-.3023.6194-.6475V8.55349c0-.34335-.2748-.62171-.6137-.62171-.3389.00008-.6137.27836-.6137.62171zm-8.74426.00003v4.07198h1.84092c.33891 0 .61366.2784.61366.6218 0 .3433-.27475.6217-.61366.6217H4.78627c-.33891 0-.61366-.2784-.61366-.6217V8.55352c0-.34335.27475-.62171.61366-.62171s.61367.27836.61367.62171zM8.91697 13.869h.26828c.26483 0 .47953-.2175.47953-.4858V8.41763c0-.26831-.2147-.48582-.47953-.48582h-.26828c-.26483 0-.47953.21751-.47953.48582v4.96557c0 .2683.2147.4858.47953.4858zM19.3601 7.93181c.339 0 .6137.27836.6137.62171 0 .34336-.2747.62172-.6137.62157h-1.8409v1.10351h1.8409c.339 0 .6137.2784.6137.6217 0 .3434-.2747.6217-.6137.6217h-1.8409v1.1035h1.8409c.339 0 .6137.2784.6137.6218 0 .3433-.2747.6217-.6137.6217h-2.4545c-.3389 0-.6137-.2784-.6137-.6217V8.55352c0-.34335.2748-.62171.6137-.62171h2.4545z" clip-rule="evenodd"/></svg>';

    public function __construct() {
        $this->id    = 'line';
        $this->label = 'Line';

        $this->path = dirname(__FILE__);

        $this->requiredFields = array(
            'client_id'     => 'Channel ID',
            'client_secret' => 'Channel Secret'
        );

        parent::__construct(array(
            'client_id'           => '',
            'client_secret'       => '',
            'force_reauth'        => 0,
            'bot_prompt'          => '',
            'initial_amr_display' => '',
            'switch_amr'          => 0,
            'allow_auto_login'    => 0,
            'login_label'         => 'Continue with <b>Line</b>',
            'register_label'      => 'Sign up with <b>Line</b>',
            'link_label'          => 'Link account with <b>Line</b>',
            'unlink_label'        => 'Unlink account from <b>Line</b>'
        ));
    }

    protected function forTranslation() {
        __('Continue with <b>Line</b>', 'nextend-facebook-connect');
        __('Sign up with <b>Line</b>', 'nextend-facebook-connect');
        __('Link account with <b>Line</b>', 'nextend-facebook-connect');
        __('Unlink account from <b>Line</b>', 'nextend-facebook-connect');
    }

    public function validateSettings($newData, $postedData) {
        $newData = parent::validateSettings($newData, $postedData);

        foreach ($postedData as $key => $value) {

            switch ($key) {
                case 'tested':
                    if ($postedData[$key] == '1' && (!isset($newData['tested']) || $newData['tested'] != '0')) {
                        $newData['tested'] = 1;
                    } else {
                        $newData['tested'] = 0;
                    }
                    break;
                case 'client_id':
                case 'client_secret':
                    $newData[$key] = trim(sanitize_text_field($value));
                    if ($this->settings->get($key) !== $newData[$key]) {
                        $newData['tested'] = 0;
                    }

                    if (empty($newData[$key])) {
                        Notices::addError(sprintf(__('The %1$s entered did not appear to be a valid. Please enter a valid %2$s.', 'nextend-facebook-connect'), $this->requiredFields[$key], $this->requiredFields[$key]));
                    }
                    break;
                case 'force_reauth':
                case 'switch_amr':
                case 'allow_auto_login':
                    $newData[$key] = $value ? 1 : 0;
                    break;
                case 'bot_prompt':
                case 'initial_amr_display':
                    $newData[$key] = trim(sanitize_text_field($value));
                    break;
            }
        }

        return $newData;
    }

    /**
     * @return NextendSocialAuth|NextendSocialProviderLineClient
     */
    public function getClient() {
        if ($this->client === null) {

            require_once dirname(__FILE__) . '/line-client.php';

            $this->client = new NextendSocialProviderLineClient($this->id);

            $this->client->setClientId($this->settings->get('client_id'));
            $this->client->setClientSecret($this->settings->get('client_secret'));
            $this->client->setRedirectUri($this->getRedirectUriForAuthFlow());

            if ($this->settings->get('force_reauth')) {
                $this->client->setPrompt('consent');
            }

            $botPrompt = $this->settings->get('bot_prompt');
            if ($botPrompt) {
                $this->client->setBotPrompt($botPrompt);
            }

            $initialLoginMethod = $this->settings->get('initial_amr_display');
            if ($initialLoginMethod) {
                $this->client->setInitialLoginMethod($initialLoginMethod);
            }

            if ($this->settings->get('switch_amr')) {
                $this->client->setForceInitialLoginMethod('false');
            }

            if ($this->settings->get('allow_auto_login')) {
                $this->client->setAllowAutoLogin(true);
            }
        }

        return $this->client;
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getCurrentUserInfo() {

        $decodedUserData = array();

        $idToken = $this->client->getLineIdToken();
        if ($idToken) {
            $http_args = array(
                'timeout'    => 15,
                'user-agent' => 'WordPress',
                'body'       => array(
                    'id_token'  => $idToken,
                    'client_id' => $this->settings->get('client_id')
                )
            );
            $request   = wp_remote_post('https://api.line.me/oauth2/v2.1/verify', $http_args);
            if (!is_wp_error($request) && wp_remote_retrieve_response_code($request) === 200) {
                $decodedIdToken = json_decode(wp_remote_retrieve_body($request), true);
                if (isset($decodedIdToken['email']) && $decodedIdToken['email'] !== '') {
                    $decodedUserData['email'] = $decodedIdToken['email'];
                }
            }
        }

        $profileData       = $this->getClient()
                                  ->get('/profile');
        $mergedProfileData = array_merge($decodedUserData, $profileData);

        return $mergedProfileData;
    }

    public function getAuthUserData($key) {
        switch ($key) {
            case 'id':
                return $this->authUserData['userId'];
            case 'email':
                return !empty($this->authUserData['email']) ? $this->authUserData['email'] : '';
            case 'name':
                return !empty($this->authUserData['displayName']) ? $this->authUserData['displayName'] : '';
            case 'first_name':
                $firstProfileName = explode(" ", $this->authUserData['displayName']);

                return !empty($this->authUserData['displayName']) ? $firstProfileName[0] : '';
            case 'last_name':
                $firstProfileName = explode(" ", $this->authUserData['displayName']);

                return !empty($this->authUserData['displayName']) ? $firstProfileName[1] : '';
            case 'picture':
                return !empty($this->authUserData['pictureUrl']) ? $this->authUserData['pictureUrl'] : '';
        }

        return parent::getAuthUserData($key);
    }

    public function syncProfile($user_id, $provider, $data) {
        if ($this->needUpdateAvatar($user_id)) {

            if ($this->getAuthUserData('picture')) {
                $this->updateAvatar($user_id, $this->getAuthUserData('picture'));
            }
        }

        if (!empty($data['access_token_data'])) {
            $this->storeAccessToken($user_id, $data['access_token_data']);
        }
    }

    public function deleteLoginPersistentData() {
        parent::deleteLoginPersistentData();

        if ($this->client !== null) {
            $this->client->deleteLoginPersistentData();
        }
    }

}

NextendSocialLogin::addProvider(new NextendSocialPROProviderLine());