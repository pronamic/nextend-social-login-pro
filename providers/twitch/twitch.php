<?php

use NSL\Notices;

class NextendSocialPROProviderTwitch extends NextendSocialProviderOAuth {

    /** @var NextendSocialProviderTwitchClient */
    protected $client;

    protected $color = '#9146FF';
    protected $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><g clip-path="url(#a)"><path fill="#fff" d="M20.33 11.14 17 14.57h-3.33l-2.92 3v-3H7V1.71h13.33v9.43Z"/><path fill="#000" d="M6.17 0 2 4.29V19.7h5V24l4.17-4.29h3.33L22 12V0H6.17Zm14.16 11.14L17 14.57h-3.33l-2.92 3v-3H7V1.71h13.33v9.43Z"/><path fill="#000" d="M17.83 4.71h-1.66v5.15h1.66V4.7ZM13.25 4.71h-1.67v5.15h1.67V4.7Z"/></g><defs><clipPath id="a"><path fill="#fff" d="M0 0h20v24H0z" transform="translate(2)"/></clipPath></defs></svg>';


    protected $sync_fields = array(
        'login'            => array(
            'label' => 'User login name',
            'node'  => 'me'
        ),
        'type'             => array(
            'label' => 'Type',
            'node'  => 'me'
        ),
        'broadcaster_type' => array(
            'label' => 'Broadcaster type',
            'node'  => 'me'
        ),
        'description'      => array(
            'label' => 'Description',
            'node'  => 'me'
        ),
        'created_at'       => array(
            'label' => 'Creation Date',
            'node'  => 'me'
        )
    );


    public function __construct() {
        $this->id    = 'twitch';
        $this->label = 'Twitch';

        $this->path = dirname(__FILE__);

        $this->requiredFields = array(
            'client_id'     => 'Client ID',
            'client_secret' => 'Client Secret'
        );

        parent::__construct(array(
            'client_id'      => '',
            'client_secret'  => '',
            'force_verify'   => 0,
            'login_label'    => 'Continue with <b>Twitch</b>',
            'register_label' => 'Sign up with <b>Twitch</b>',
            'link_label'     => 'Link account with <b>Twitch</b>',
            'unlink_label'   => 'Unlink account from <b>Twitch</b>'
        ));
    }

    protected function forTranslation() {
        __('Continue with <b>Twitch</b>', 'nextend-facebook-connect');
        __('Sign up with <b>Twitch</b>', 'nextend-facebook-connect');
        __('Link account with <b>Twitch</b>', 'nextend-facebook-connect');
        __('Unlink account from <b>Twitch</b>', 'nextend-facebook-connect');
    }

    public function validateSettings($newData, $postedData) {
        $newData = parent::validateSettings($newData, $postedData);

        foreach ($postedData AS $key => $value) {

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
                case 'force_verify':
                    $newData[$key] = $value ? 1 : 0;
                    break;
            }
        }

        return $newData;
    }

    /**
     * @return NextendSocialAuth|NextendSocialProviderTwitchClient
     */
    public function getClient() {
        if ($this->client === null) {

            require_once dirname(__FILE__) . '/twitch-client.php';

            $this->client = new NextendSocialProviderTwitchClient($this->id);

            $this->client->setClientId($this->settings->get('client_id'));
            $this->client->setClientSecret($this->settings->get('client_secret'));
            $this->client->setRedirectUri($this->getRedirectUriForAuthFlow());

            if ($this->settings->get('force_verify')) {
                $this->client->setPrompt(true);
            }
        }

        return $this->client;
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getCurrentUserInfo() {
        $response = $this->getClient()
                         ->get('/users');
        if (isset($response['data']) && !empty($response['data'][0])) {
            return $response['data'][0];
        }

        throw new Exception(sprintf(__('Unexpected response: %s', 'nextend-facebook-connect'), json_encode($response)));
    }

    public function getMe() {
        return $this->authUserData;
    }

    public function getAuthUserData($key) {
        switch ($key) {
            case 'id':
                return $this->authUserData['id'];
            case 'email':
                return !empty($this->authUserData['email']) ? $this->authUserData['email'] : '';
            case 'name':
                return $this->authUserData['display_name'];
            case 'first_name':
                $name = explode(' ', $this->getAuthUserData('name'), 2);

                return isset($name[0]) ? $name[0] : '';
            case 'last_name':
                $name = explode(' ', $this->getAuthUserData('name'), 2);

                return isset($name[1]) ? $name[1] : '';
            case 'picture':
                return !empty($this->authUserData['profile_image_url']) ? $this->authUserData['profile_image_url'] : '';
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

NextendSocialLogin::addProvider(new NextendSocialPROProviderTwitch());