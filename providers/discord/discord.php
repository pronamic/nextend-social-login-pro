<?php

use NSL\Notices;

class NextendSocialPROProviderDiscord extends NextendSocialProviderOAuth {

    /** @var NextendSocialProviderDiscordClient */
    protected $client;

    protected $color = '#5865F2';

    protected $svg = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20.317 4.575C18.7873 3.846 17.147 3.30891 15.4319 3.00129C15.4007 2.99535 15.3695 3.01019 15.3534 3.03986C15.1424 3.42957 14.9087 3.93798 14.7451 4.33759C12.9004 4.05075 11.0652 4.05075 9.25832 4.33759C9.09465 3.9291 8.85248 3.42957 8.64057 3.03986C8.62448 3.01118 8.59328 2.99634 8.56205 3.00129C6.84791 3.30792 5.20756 3.84502 3.67694 4.575C3.66368 4.58093 3.65233 4.59083 3.64479 4.60368C0.533392 9.43162 -0.31895 14.1409 0.0991801 18.7918C0.101072 18.8145 0.11337 18.8363 0.130398 18.8501C2.18321 20.4159 4.17171 21.3665 6.12328 21.9965C6.15451 22.0064 6.18761 21.9946 6.20748 21.9679C6.66913 21.3131 7.08064 20.6227 7.43348 19.8966C7.4543 19.8541 7.43442 19.8036 7.39186 19.7868C6.73913 19.5297 6.1176 19.2161 5.51973 18.86C5.47244 18.8313 5.46865 18.7611 5.51216 18.7275C5.63797 18.6295 5.76382 18.5277 5.88395 18.4248C5.90569 18.406 5.93598 18.402 5.96153 18.4139C9.88928 20.2765 14.1415 20.2765 18.023 18.4139C18.0485 18.4011 18.0788 18.405 18.1015 18.4238C18.2216 18.5267 18.3475 18.6295 18.4742 18.7275C18.5177 18.7611 18.5149 18.8313 18.4676 18.86C17.8697 19.223 17.2482 19.5297 16.5945 19.7858C16.552 19.8027 16.533 19.8541 16.5538 19.8966C16.9143 20.6216 17.3258 21.3121 17.7789 21.9669C17.7978 21.9946 17.8319 22.0064 17.8631 21.9965C19.8241 21.3665 21.8126 20.4159 23.8654 18.8501C23.8834 18.8363 23.8948 18.8155 23.8967 18.7928C24.3971 13.4158 23.0585 8.74517 20.3482 4.60466C20.3416 4.59083 20.3303 4.58093 20.317 4.575ZM8.02002 15.9599C6.8375 15.9599 5.86313 14.8323 5.86313 13.4475C5.86313 12.0627 6.8186 10.9351 8.02002 10.9351C9.23087 10.9351 10.1958 12.0726 10.1769 13.4475C10.1769 14.8323 9.22141 15.9599 8.02002 15.9599ZM15.9947 15.9599C14.8123 15.9599 13.8379 14.8323 13.8379 13.4475C13.8379 12.0627 14.7933 10.9351 15.9947 10.9351C17.2056 10.9351 18.1705 12.0726 18.1516 13.4475C18.1516 14.8323 17.2056 15.9599 15.9947 15.9599Z" fill="white"/></svg>';

    protected $sync_fields = array(
        'username'      => array(
            'label' => 'Username ( not unique )',
            'node'  => 'me'
        ),
        'avatar'        => array(
            'label' => 'Avatar hash',
            'node'  => 'me'
        ),
        'discriminator' => array(
            'label' => '4-digit Discord-tag',
            'node'  => 'me'
        ),
        'public_flags'  => array(
            'label' => 'Public flags',
            'node'  => 'me'
        ),
        'flags'         => array(
            'label' => 'Flags',
            'node'  => 'me'
        ),
        'banner'        => array(
            'label' => 'Banner hash',
            'node'  => 'me'
        ),
        'banner_color'  => array(
            'label' => 'Banner color',
            'node'  => 'me'
        ),
        'accent_color'  => array(
            'label' => 'Accent color',
            'node'  => 'me'
        ),
        'locale'        => array(
            'label' => 'Locale',
            'node'  => 'me'
        ),
        'mfa_enabled'   => array(
            'label' => 'Is 2FA enabled',
            'node'  => 'me'
        ),
        'premium_type'  => array(
            'label' => 'Nitro subscription type',
            'node'  => 'me'
        ),
        'email'         => array(
            'label' => 'Email',
            'node'  => 'me'
        ),
        'verified'      => array(
            'label' => 'Is email verified',
            'node'  => 'me'
        )
    );


    public function __construct() {
        $this->id    = 'discord';
        $this->label = 'Discord';

        $this->path = dirname(__FILE__);

        $this->requiredFields = array(
            'client_id'     => 'Client ID',
            'client_secret' => 'Client Secret'
        );

        parent::__construct(array(
            'client_id'      => '',
            'client_secret'  => '',
            'force_reauth'   => 0,
            'login_label'    => 'Continue with <b>Discord</b>',
            'register_label' => 'Sign up with <b>Discord</b>',
            'link_label'     => 'Link account with <b>Discord</b>',
            'unlink_label'   => 'Unlink account from <b>Discord</b>'
        ));
    }

    protected function forTranslation() {
        __('Continue with <b>Discord</b>', 'nextend-facebook-connect');
        __('Sign up with <b>Discord</b>', 'nextend-facebook-connect');
        __('Link account with <b>Discord</b>', 'nextend-facebook-connect');
        __('Unlink account from <b>Discord</b>', 'nextend-facebook-connect');
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
                    $newData[$key] = $value ? 1 : 0;
                    break;
            }
        }

        return $newData;
    }

    /**
     * @return NextendSocialAuth|NextendSocialProviderDiscordClient
     */
    public function getClient() {
        if ($this->client === null) {

            require_once dirname(__FILE__) . '/discord-client.php';

            $this->client = new NextendSocialProviderDiscordClient($this->id);

            $this->client->setClientId($this->settings->get('client_id'));
            $this->client->setClientSecret($this->settings->get('client_secret'));
            $this->client->setRedirectUri($this->getRedirectUriForAuthFlow());

            if (!$this->settings->get('force_reauth')) {
                $this->client->setPrompt('none');
            }
        }

        return $this->client;
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getCurrentUserInfo() {
        return $this->getClient()
                    ->get('/users/@me');
    }


    public function getMe() {
        return $this->authUserData;
    }

    public function getPicture($user_id, $user_avatar) {
        return "https://cdn.discordapp.com/avatars/" . $user_id . "/" . $user_avatar . ".jpg";
    }

    public function getAuthUserData($key) {
        switch ($key) {
            case 'id':
                return $this->authUserData['id'];
            case 'email':
                return !empty($this->authUserData['email']) ? $this->authUserData['email'] : '';
            case 'name':
                return !empty($this->authUserData['username']) ? $this->authUserData['username'] : '';
            case 'first_name':
                $name = explode(' ', $this->authUserData['username'], 2);

                return isset($name[0]) ? $name[0] : '';
            case 'last_name':
                $name = explode(' ', $this->authUserData['username'], 2);

                return isset($name[1]) ? $name[1] : '';
            case 'picture':
                return !empty($this->authUserData['avatar']) ? $this->getPicture($this->authUserData['id'], $this->authUserData['avatar']) : '';
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

NextendSocialLogin::addProvider(new NextendSocialPROProviderDiscord());