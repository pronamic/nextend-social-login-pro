<?php

use NSL\Notices;

class NextendSocialPROProviderKakao extends NextendSocialProviderOAuth {

    /** @var NextendSocialProviderKakaoClient */
    protected $client;

    protected $color = '#000000';

    protected $buttonBgColor = '#FEE500';

    protected $svg = '<svg width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g clip-path="url(#a)"><path d="M11.5 1C5.17 1 0 5.04 0 10.06 0 13.2 1.98 16 5.05 17.63l-1.28 4.76c-.13.45.32.78.7.52l5.56-3.78c.51.04 1 .06 1.47.06 6.32 0 11.5-4.04 11.5-9.13C23 5.04 17.82 1 11.5 1Z" fill="#000000"/></g><defs><clipPath id="a"><path fill="#000000" d="M0 0h24v24H0z"/></clipPath></defs></svg>';

    protected $sync_fields = array(
        'name'          => array(
            'label' => 'Name',
            'node'  => 'me',
            'scope' => 'name'
        ),
        'gender'        => array(
            'label' => 'Gender',
            'node'  => 'me',
            'scope' => 'gender'
        ),
        'age_range'     => array(
            'label' => 'Age range',
            'node'  => 'me',
            'scope' => 'age_range'
        ),
        'birthday'      => array(
            'label' => 'Birthday',
            'node'  => 'me',
            'scope' => 'birthday'
        ),
        'birthday_type' => array(
            'label' => 'Birthday type',
            'node'  => 'me',
            'scope' => 'birthday'
        ),
        'birthyear'     => array(
            'label' => 'Birthyear',
            'node'  => 'me',
            'scope' => 'birthyear'
        ),
        'phone_number'  => array(
            'label' => 'Phone number',
            'node'  => 'me',
            'scope' => 'phone_number'
        )
    );

    public function __construct() {
        $this->id    = 'kakao';
        $this->label = 'Kakao';

        $this->path = dirname(__FILE__);

        $this->requiredFields = array(
            'client_id'     => 'REST API Key',
            'client_secret' => 'Client Secret Code'
        );

        parent::__construct(array(
            'client_id'          => '',
            'client_secret'      => '',
            'prompt'             => 'select_account',
            'login_label'        => 'Continue with <b>Kakao</b>',
            'register_label'     => 'Sign up with <b>Kakao</b>',
            'link_label'         => 'Link account with <b>Kakao</b>',
            'unlink_label'       => 'Unlink account from <b>Kakao</b>',
            'profile_image_size' => 'mini',

        ));
    }

    protected function forTranslation() {
        __('Continue with <b>Kakao</b>', 'nextend-facebook-connect');
        __('Sign up with <b>Kakao</b>', 'nextend-facebook-connect');
        __('Link account with <b>Kakao</b>', 'nextend-facebook-connect');
        __('Unlink account from <b>Kakao</b>', 'nextend-facebook-connect');
    }

    public function getRawDefaultButton() {
        return '<div class="nsl-button nsl-button-default nsl-button-' . $this->id . '" style="background-color:' . $this->buttonBgColor . ';"><div class="nsl-button-svg-container">' . $this->svg . '</div><div class="nsl-button-label-container">{{label}}</div></div>';
    }

    public function getRawIconButton() {
        return '<div class="nsl-button nsl-button-icon nsl-button-' . $this->id . '" style="background-color:' . $this->buttonBgColor . ';"><div class="nsl-button-svg-container">' . $this->svg . '</div></div>';
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
                case 'prompt':
                case 'profile_image_size':
                    $newData[$key] = trim(sanitize_text_field($value));
                    break;
            }
        }

        return $newData;
    }

    /**
     * @return NextendSocialAuth|NextendSocialProviderKakaoClient
     */
    public function getClient() {
        if ($this->client === null) {

            require_once dirname(__FILE__) . '/kakao-client.php';

            $this->client = new NextendSocialProviderKakaoClient($this->id);

            $this->client->setClientId($this->settings->get('client_id'));
            $this->client->setClientSecret($this->settings->get('client_secret'));
            $this->client->setRedirectUri($this->getRedirectUriForAuthFlow());

            $this->client->setPrompt($this->settings->get('prompt'));

        }

        return $this->client;
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getCurrentUserInfo() {

        $fields = array(
            'kakao_account.profile',
            'kakao_account.email',
            'kakao_account.name'
        );

        $extra_me_fields = apply_filters('nsl_kakao_sync_node_fields', array(), 'me');

        $fields = array_map(function ($value) {
            return '"' . $value . '"';
        }, $fields);

        $extra_me_fields = array_map(function ($value) {
            return '"kakao_account.' . $value . '"';
        }, $extra_me_fields);

        return $this->getClient()
                    ->get('/me?secure_resource=true&property_keys=[' . implode(',', array_merge($fields, $extra_me_fields)) . ']');
    }

    public function getMe() {
        return $this->authUserData['kakao_account'];
    }

    public function getAuthUserData($key) {

        switch ($key) {
            case 'id':
                return $this->authUserData['id'];
            case 'email':
                /**
                 * Kakao stated the following:
                 * - If a user's email has expired, the user's email is masked with asterisks (*). (Example: ka***@kakao.com)
                 * - If your service sends emails to the email addresses provided by users, you must check both the is_email_valid and is_email_verified fields.
                 * - User's email saved in Kakao Account can be changed if a user wants. For this reason, we recommend not to use users' emails as an ID or not to identify users by email.
                 *
                 * For this reason we should only attempt to use the email address if it is both valid and verified.
                 */

                $email = '';
                if (!empty($this->authUserData['kakao_account']['is_email_valid']) && !empty($this->authUserData['kakao_account']['is_email_verified']) && !empty($this->authUserData['kakao_account']['email'])) {
                    $email = $this->authUserData['kakao_account']['email'];
                }

                return $email;
            case 'name':
                $name = '';
                if (!empty($this->authUserData['kakao_account']['profile']['nickname'])) {
                    /**
                     * Kakao returns the "name" only for the verified apps, but generally the "nickname" is returned and can be set as with required consent type, so we should rather use this.
                     */
                    $name = $this->authUserData['kakao_account']['profile']['nickname'];
                }

                return $name;
            case 'first_name':
                $name = explode(' ', $this->getAuthUserData('name'), 2);

                return isset($name[0]) ? $name[0] : '';
            case 'last_name':
                $name = explode(' ', $this->getAuthUserData('name'), 2);

                return isset($name[1]) ? $name[1] : '';
            case 'picture':
                $profile_image_size = $this->settings->get('profile_image_size');
                $avatar_url         = '';
                switch ($profile_image_size) {
                    case 'large':
                        if (!empty($this->authUserData['kakao_account']['profile']['profile_image_url'])) {
                            $avatar_url = $this->authUserData['kakao_account']['profile']['profile_image_url'];
                        }
                        break;
                    default:
                        if (!empty($this->authUserData['kakao_account']['profile']['thumbnail_image_url'])) {
                            $avatar_url = $this->authUserData['kakao_account']['profile']['thumbnail_image_url'];
                        }
                        break;
                }


                return $avatar_url;
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

    public function getSyncDataFieldDescription($fieldName) {
        if (isset($this->sync_fields[$fieldName]['scope'])) {
            return sprintf(__('Required scope: %1$s', 'nextend-facebook-connect'), $this->sync_fields[$fieldName]['scope']);
        }

        return parent::getSyncDataFieldDescription($fieldName);
    }

    public function deleteLoginPersistentData() {
        parent::deleteLoginPersistentData();

        if ($this->client !== null) {
            $this->client->deleteLoginPersistentData();
        }
    }

}

NextendSocialLogin::addProvider(new NextendSocialPROProviderKakao());