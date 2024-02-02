<?php

use NSL\Notices;

class NextendSocialPROProviderYahoo extends NextendSocialProviderOAuth {

    /** @var NextendSocialProviderYahooClient */
    protected $client;

    protected $color = '#720e9e';

    protected $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#fff" d="M21,0 C20.9994216,0.000579108655 20.998554,0.000868662983 20.9976864,0.00144777164 L20.9991324,0 C19.8599653,0.258861569 18.8324302,0.267258644 17.811836,0 C16.913285,1.67651956 13.5975829,7.10131989 11.4872751,10.5751032 C9.34717952,7.02603576 6.81347986,2.92855247 5.16271424,4.11481448e-15 C3.8537855,0.279419926 3.30632592,0.297372294 2,0 C4.59211848,3.91072075 8.74592834,11.3595058 10.1589698,13.8047921 L9.96925325,24 C9.96925325,24 10.8808183,23.8474049 11.4901671,23.8474049 C12.165454,23.8474049 13.0050078,24 13.0050078,24 L12.8155804,13.8045026 C15.452236,9.17337065 19.8050169,1.61687137 21,0 Z"/></svg>
';

    protected $sync_fields = array(
        'locale'         => array(
            'label' => 'Locale',
            'node'  => 'me',
        ),
        'email_verified' => array(
            'label'       => 'Email verification status',
            'node'        => 'me',
            'description' => 'Read/Write Public and Private'
        ),
        'birthdate'      => array(
            'label'       => 'Birthdate',
            'node'        => 'me',
            'description' => 'Read/Write Public and Private'
        ),
        'nickname'       => array(
            'label'       => 'Nickname',
            'node'        => 'me',
            'description' => 'Read/Write Public and Private'
        ),
        'gender'         => array(
            'label'       => 'Gender',
            'node'        => 'me',
            'description' => 'Profile'
        )
    );


    public function __construct() {
        $this->id    = 'yahoo';
        $this->label = 'Yahoo';

        $this->path = dirname(__FILE__);

        $this->requiredFields = array(
            'client_id'     => 'Client ID',
            'client_secret' => 'Client Secret'
        );

        parent::__construct(array(
            'client_id'      => '',
            'client_secret'  => '',
            'login_label'    => 'Continue with <b>Yahoo</b>',
            'register_label' => 'Sign up with <b>Yahoo</b>',
            'link_label'     => 'Link account with <b>Yahoo</b>',
            'unlink_label'   => 'Unlink account from <b>Yahoo</b>'
        ));
    }

    protected function forTranslation() {
        __('Continue with <b>Yahoo</b>', 'nextend-facebook-connect');
        __('Sign up with <b>Yahoo</b>', 'nextend-facebook-connect');
        __('Link account with <b>Yahoo</b>', 'nextend-facebook-connect');
        __('Unlink account from <b>Yahoo</b>', 'nextend-facebook-connect');
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
            }
        }

        return $newData;
    }

    /**
     * @return NextendSocialAuth|NextendSocialProviderYahooClient
     */
    public function getClient() {
        if ($this->client === null) {

            require_once dirname(__FILE__) . '/yahoo-client.php';

            $this->client = new NextendSocialProviderYahooClient($this->id);

            $this->client->setClientId($this->settings->get('client_id'));
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

        $user = $this->getClient()
                     ->get('userinfo');

        return $user;
    }

    public function getMe() {
        return $this->authUserData;
    }

    public function getAuthUserData($key) {
        switch ($key) {
            case 'id':
                return $this->authUserData['sub'];
            case 'email':
                return !empty($this->authUserData['email']) ? $this->authUserData['email'] : '';
            case 'name':
                return !empty($this->authUserData['name']) ? $this->authUserData['name'] : '';
            case 'first_name':
                return !empty($this->authUserData['given_name']) ? $this->authUserData['given_name'] : '';
            case 'last_name':
                return !empty($this->authUserData['family_name']) ? $this->authUserData['family_name'] : '';
            case 'picture':
                return !empty($this->authUserData['picture']) ? $this->authUserData['picture'] : '';
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

    public function getAvatar($user_id) {

        if (!$this->isUserConnected($user_id)) {
            return false;
        }

        $picture = $this->getUserData($user_id, 'profile_picture');
        if (!$picture || $picture == '') {
            return false;
        }

        return $picture;
    }

    public function getSyncDataFieldDescription($fieldName) {
        if (isset($this->sync_fields[$fieldName]['description'])) {
            return sprintf(__('Required permission: %1$s', 'nextend-facebook-connect'), $this->sync_fields[$fieldName]['description']);
        }

        return parent::getSyncDataFieldDescription($fieldName);
    }

}

NextendSocialLogin::addProvider(new NextendSocialPROProviderYahoo());