<?php

use NSL\Notices;

class NextendSocialPROProviderTiktok extends NextendSocialProviderOAuth {

    /** @var NextendSocialProviderTiktokClient */
    protected $client;

    protected $color = '#121212';

    protected $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path fill="#fff" fill-rule="evenodd" d="M13.7143 17.1176c-.0662 1.6975-1.5226 3.0599-3.308 3.0599a3.4086 3.4086 0 0 1 .0001 0c1.7855 0 3.2419-1.3624 3.3082-3.0597l.0063-15.158h2.8866c.2782 1.441 1.1699 2.6776 2.4052 3.4501l.0012.0015c.86.5376 1.8853.8512 2.986.8512v.842L22 7.1047v2.9396c-2.0446 0-3.9391-.6274-5.4856-1.6922v7.6863c0 3.8387-3.2555 6.9616-7.2573 6.9616-1.5463 0-2.9803-.4678-4.159-1.2617-.0006-.0007-.0012-.0014-.002-.002C3.2257 20.4758 2 18.3921 2 16.0376 2 12.1991 5.2556 9.076 9.2574 9.076c.332 0 .6574.0262.9779.068v.896c-3.6508.0817-6.6439 2.7605-7.0412 6.1816.3977-3.4207 3.3906-6.0991 7.041-6.1809v2.9652c-.3095-.0929-.6368-.147-.978-.147-1.8275 0-3.3143 1.4264-3.3143 3.1795 0 1.2208.722 2.2815 1.777 2.814v.0001c.46.2322.9824.3652 1.5372.3652 1.7854 0 3.2418-1.3623 3.3081-3.0597L12.5714 1h3.9429c0 .3279.0329.6483.093.9599h-2.8866l-.0064 15.1577Z" clip-rule="evenodd"/></svg>';

    protected $svgLight = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path fill="#161823" fill-rule="evenodd" d="M13.7143 17.1176c-.0662 1.6975-1.5226 3.0599-3.308 3.0599a3.4086 3.4086 0 0 1 .0001 0c1.7855 0 3.2419-1.3624 3.3082-3.0597l.0063-15.158h2.8866c.2782 1.441 1.1699 2.6776 2.4052 3.4501l.0012.0015c.86.5376 1.8853.8512 2.986.8512v.842L22 7.1047v2.9396c-2.0446 0-3.9391-.6274-5.4856-1.6922v7.6863c0 3.8387-3.2555 6.9616-7.2573 6.9616-1.5463 0-2.9803-.4678-4.159-1.2617-.0006-.0007-.0012-.0014-.002-.002C3.2257 20.4758 2 18.3921 2 16.0376 2 12.1991 5.2556 9.076 9.2574 9.076c.332 0 .6574.0262.9779.068v.896c-3.6508.0817-6.6439 2.7605-7.0412 6.1816.3977-3.4207 3.3906-6.0991 7.041-6.1809v2.9652c-.3095-.0929-.6368-.147-.978-.147-1.8275 0-3.3143 1.4264-3.3143 3.1795 0 1.2208.722 2.2815 1.777 2.814v.0001c.46.2322.9824.3652 1.5372.3652 1.7854 0 3.2418-1.3623 3.3081-3.0597L12.5714 1h3.9429c0 .3279.0329.6483.093.9599h-2.8866l-.0064 15.1577Z" clip-rule="evenodd"/></svg>';
    protected $colorLight = '#ffffff';

    protected $sync_fields = array(
        'bio_description'   => array(
            'label' => 'Bio description',
            'node'  => 'me',
            'scope' => 'user.info.profile'
        ),
        'profile_deep_link' => array(
            'label' => 'Profile page link',
            'node'  => 'me',
            'scope' => 'user.info.profile'
        ),
        'is_verified'       => array(
            'label' => 'Verified',
            'node'  => 'me',
            'scope' => 'user.info.profile'
        ),
        'follower_count'    => array(
            'label' => 'Followers',
            'node'  => 'me',
            'scope' => 'user.info.stats'
        ),
        'following_count'   => array(
            'label' => 'Following',
            'node'  => 'me',
            'scope' => 'user.info.stats'
        ),
        'likes_count'       => array(
            'label' => 'Likes',
            'node'  => 'me',
            'scope' => 'user.info.stats'
        ),
        'video_count'       => array(
            'label' => 'Videos',
            'node'  => 'me',
            'scope' => 'user.info.stats'
        )
    );

    public function __construct() {
        $this->id    = 'tiktok';
        $this->label = 'TikTok';

        $this->path = dirname(__FILE__);

        $this->requiredFields = array(
            'client_key'    => 'Client Key',
            'client_secret' => 'Client Secret'
        );

        $this->authRedirectBehavior = 'rest_redirect';

        parent::__construct(array(
            'client_key'         => '',
            'client_secret'      => '',
            'skin'               => 'dark',
            'login_label'        => 'Continue with <b>TikTok</b>',
            'register_label'     => 'Sign up with <b>TikTok</b>',
            'link_label'         => 'Link account with <b>TikTok</b>',
            'unlink_label'       => 'Unlink account from <b>TikTok</b>',
            'profile_image_size' => 'mini'
        ));
    }

    protected function forTranslation() {
        __('Continue with <b>TikTok</b>', 'nextend-facebook-connect');
        __('Sign up with <b>TikTok</b>', 'nextend-facebook-connect');
        __('Link account with <b>TikTok</b>', 'nextend-facebook-connect');
        __('Unlink account from <b>TikTok</b>', 'nextend-facebook-connect');
    }

    public function getRawDefaultButton() {
        $skin = $this->settings->get('skin');
        switch ($skin) {
            case 'light':
                $color = $this->colorLight;
                $svg   = $this->svgLight;
                break;
            default:
                $color = $this->color;
                $svg   = $this->svg;
        }

        return '<div class="nsl-button nsl-button-default nsl-button-' . $this->id . '" data-skin="' . $skin . '" style="background-color:' . $color . ';"><div class="nsl-button-svg-container">' . $svg . '</div><div class="nsl-button-label-container">{{label}}</div></div>';
    }

    public function getRawIconButton() {
        $skin = $this->settings->get('skin');
        switch ($skin) {
            case 'light':
                $color = $this->colorLight;
                $svg   = $this->svgLight;
                break;
            default:
                $color = $this->color;
                $svg   = $this->svg;
        }

        return '<div class="nsl-button nsl-button-icon nsl-button-' . $this->id . '" data-skin="' . $skin . '" style="background-color:' . $color . ';"><div class="nsl-button-svg-container">' . $svg . '</div></div>';
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
                case 'client_key':
                case 'client_secret':
                    $newData[$key] = trim(sanitize_text_field($value));
                    if ($this->settings->get($key) !== $newData[$key]) {
                        $newData['tested'] = 0;
                    }

                    if (empty($newData[$key])) {
                        Notices::addError(sprintf(__('The %1$s entered did not appear to be a valid. Please enter a valid %2$s.', 'nextend-facebook-connect'), $this->requiredFields[$key], $this->requiredFields[$key]));
                    }
                    break;
                case 'profile_image_size':
                    $newData[$key] = trim(sanitize_text_field($value));
                    break;
                case 'skin':
                    $newData[$key] = trim(sanitize_text_field($value));
                    break;
            }
        }

        return $newData;
    }

    /**
     * @return NextendSocialAuth|NextendSocialProviderTiktokClient
     */
    public function getClient() {
        if ($this->client === null) {

            require_once dirname(__FILE__) . '/tiktok-client.php';

            $this->client = new NextendSocialProviderTiktokClient($this->id);
            $this->client->setClientId($this->settings->get('client_key'));
            $this->client->setClientSecret($this->settings->get('client_secret'));
            $this->client->setRedirectUri($this->getRedirectUriForAuthFlow());
        }

        return $this->client;
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getCurrentUserInfo() {
        $fields       = array(
            'open_id',
            'union_id',
            'avatar_url',
            'avatar_large_url',
            'display_name',
        );
        $extra_fields = apply_filters('nsl_tiktok_sync_node_fields', array(), 'me');

        $user = $this->getClient()
                     ->get("user/info/?fields=" . implode(',', array_merge($fields, $extra_fields)));

        if (isset($user['data']) && isset($user['data']['user'])) {
            return $user['data']['user'];
        }

        /**
         * TikTok made entire API modifications in the past, that could cause unexpected results. If we get to this point, we should throw an error!
         */
        throw new Exception(sprintf(__('Unexpected response: %s', 'nextend-facebook-connect'), json_encode($user)));

    }


    public function getMe() {
        return $this->authUserData;
    }

    public function getAuthUserData($key) {
        switch ($key) {
            case 'id':
                return $this->authUserData['union_id'];
            case 'email':
                return '';
            case 'name':
                return $this->authUserData['display_name'];
            case 'first_name':
                $name = explode(' ', $this->getAuthUserData('name'), 2);

                return isset($name[0]) ? $name[0] : '';
            case 'last_name':
                $name = explode(' ', $this->getAuthUserData('name'), 2);

                return isset($name[1]) ? $name[1] : '';
            case 'picture':
                $profile_image_size = $this->settings->get('profile_image_size');
                if ($profile_image_size === 'large') {
                    return isset($this->authUserData['avatar_large_url']) ? $this->authUserData['avatar_large_url'] : '';
                }

                return isset($this->authUserData['avatar_url']) ? $this->authUserData['avatar_url'] : '';
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

    public function getSyncDataFieldDescription($fieldName) {
        if (isset($this->sync_fields[$fieldName]['scope'])) {
            return sprintf(__('Required scope: %1$s', 'nextend-facebook-connect'), $this->sync_fields[$fieldName]['scope']);
        }

        return parent::getSyncDataFieldDescription($fieldName);
    }

}

NextendSocialLogin::addProvider(new NextendSocialPROProviderTiktok());