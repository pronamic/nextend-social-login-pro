<?php

use NSL\Notices;

class NextendSocialPROProviderMicrosoft extends NextendSocialProviderOAuth {

    /** @var NextendSocialProviderMicrosoftClient */
    protected $client;

    protected $color = '#2F2F2F';

    protected $svg = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11 2H2V11H11V2Z" fill="#F25022"/><path d="M11 12H2V21H11V12Z" fill="#00A4EF"/><path d="M21 2H12V11H21V2Z" fill="#7FBA00"/><path d="M21 12H12V21H21V12Z" fill="#FFB900"/></svg>';

    public function __construct() {
        $this->id    = 'microsoft';
        $this->label = 'Microsoft';

        $this->path = dirname(__FILE__);

        $this->requiredFields = array(
            'client_id'     => 'Application (client) ID',
            'client_secret' => 'Client secret',
            'tenant'        => 'Audience'
        );

        $this->authRedirectBehavior = 'rest_redirect';

        parent::__construct(array(
            'client_id'           => '',
            'client_secret'       => '',
            'tenant'              => 'common',
            'custom_tenant_value' => '',
            'prompt'              => 'select_account',
            'login_label'         => 'Continue with <b>Microsoft</b>',
            'register_label'      => 'Sign up with <b>Microsoft</b>',
            'link_label'          => 'Link account with <b>Microsoft</b>',
            'unlink_label'        => 'Unlink account from <b>Microsoft</b>'
        ));
    }

    protected function forTranslation() {
        __('Continue with <b>Microsoft</b>', 'nextend-facebook-connect');
        __('Sign up with <b>Microsoft</b>', 'nextend-facebook-connect');
        __('Link account with <b>Microsoft</b>', 'nextend-facebook-connect');
        __('Unlink account from <b>Microsoft</b>', 'nextend-facebook-connect');
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
                case 'tenant':
                    $newData[$key] = trim(sanitize_text_field($value));
                    if ($this->settings->get($key) !== $newData[$key]) {
                        $newData['tested'] = 0;
                    }

                    if (empty($newData[$key])) {
                        Notices::addError(sprintf(__('The %1$s entered did not appear to be a valid. Please enter a valid %2$s.', 'nextend-facebook-connect'), $this->requiredFields[$key], $this->requiredFields[$key]));
                    }
                    break;
                case 'custom_tenant_value':
                case 'prompt':
                    $newData[$key] = trim(sanitize_text_field($value));
                    break;
            }
        }

        return $newData;
    }

    /**
     * @return NextendSocialAuth|NextendSocialProviderMicrosoftClient
     */
    public function getClient() {
        if ($this->client === null) {

            require_once dirname(__FILE__) . '/microsoft-client.php';

            $tenant = $this->settings->get('tenant');
            if ($tenant === 'custom_tenant') {
                $tenant = $this->settings->get('custom_tenant_value');
            }

            $this->client = new NextendSocialProviderMicrosoftClient($this->id, $tenant);

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
        return $this->getClient()
                    ->get('/me');
    }

    public function getAuthUserData($key) {
        switch ($key) {
            case 'id':
                return $this->authUserData['id'];
            case 'email':
                return is_email($this->authUserData['userPrincipalName']) ? $this->authUserData['userPrincipalName'] : '';
            case 'name':
                return !empty($this->authUserData['displayName']) ? $this->authUserData['displayName'] : '';
            case 'first_name':
                return !empty($this->authUserData['givenName']) ? $this->authUserData['givenName'] : '';
            case 'last_name':
                return !empty($this->authUserData['surname']) ? $this->authUserData['surname'] : '';
        }

        return parent::getAuthUserData($key);
    }

    public function syncProfile($user_id, $provider, $data) {
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

NextendSocialLogin::addProvider(new NextendSocialPROProviderMicrosoft());