<?php

use NSL\Notices;

class NextendSocialPROProviderWordpress extends NextendSocialProviderOAuth {

    /** @var NextendSocialProviderWordpressClient */
    protected $client;

    protected $color = '#3499cd';
    protected $svg = '
    <svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
        <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
            <path d="M0,12 C0,18.627417 5.372583,24 12,24 C18.627417,24 24,18.627417 24,12 C24,5.372583 18.627417,0 12,0 C8.81740212,0 5.76515517,1.26428208 3.51471863,3.51471863 C1.26428208,5.76515517 0,8.81740212 0,12 Z M0.924444444,12 C0.924281908,10.4467302 1.25138636,8.91085364 1.88444444,7.49244444 L7.16888889,21.9666667 C3.34879091,20.1163293 0.92304481,16.2446313 0.924444444,12 Z M12,23.0751111 C10.9408878,23.0756795 9.88717824,22.9242087 8.87111111,22.6253333 L12.1955556,12.9702222 L15.6,22.296 C15.6217759,22.3478607 15.6470181,22.3981967 15.6755556,22.4466667 C14.495125,22.8640348 13.2520424,23.0765763 12,23.0751111 Z M20.9511111,11.7924444 C21.4881851,10.5378184 21.7750367,9.19037073 21.7955556,7.82577778 C21.7951773,7.44468398 21.7684469,7.06407246 21.7155556,6.68666667 C24.5995157,11.9518522 22.7597058,18.5562373 17.5688889,21.572 L20.9511111,11.7924444 Z M18.5644444,8.38666667 C19.1443887,9.30105863 19.459749,10.3582079 19.4755556,11.4408889 C19.4118457,12.6751323 19.1273441,13.8879525 18.6355556,15.0217778 L17.5288889,18.7106667 L13.5244444,6.80711111 C14.1955556,6.77066667 14.7911111,6.70133333 14.7911111,6.70133333 C15.0433211,6.68292379 15.2328538,6.46354334 15.2144443,6.21133334 C15.1960349,5.95912334 14.9766544,5.76959056 14.7244444,5.788 C14.7244444,5.788 12.9288889,5.92977778 11.7688889,5.92977778 C10.68,5.92977778 8.85333333,5.788 8.85333333,5.788 C8.60548706,5.77555175 8.39277121,5.96266641 8.37350815,6.21007633 C8.3542451,6.45748625 8.535436,6.67527005 8.78222222,6.70133333 C8.78222222,6.70133333 9.35111111,6.77066667 9.94666667,6.80711111 L11.6711111,11.5346667 L9.24888889,18.8017778 L5.21333333,6.80711111 C5.88,6.77066667 6.48,6.70133333 6.48,6.70133333 C6.73220999,6.68292379 6.92174272,6.46354334 6.90333324,6.21133334 C6.88492375,5.95912334 6.66554333,5.76959056 6.41333333,5.788 C6.41333333,5.788 4.61777778,5.92977778 3.45777778,5.92977778 C3.25333333,5.92977778 3.00888889,5.924 2.74666667,5.91688889 C4.5285531,3.20412401 7.41107846,1.40990453 10.6318416,1.00878479 C13.8526048,0.607665036 17.0871609,1.64005198 19.48,3.83288889 C19.4311111,3.82933333 19.3822222,3.82355556 19.3377778,3.82355556 C18.2814474,3.85262278 17.4481858,4.73162426 17.4755556,5.788 C17.4755556,6.70266667 18,7.47466667 18.5644444,8.38666667 Z" id="Forma_1" fill="#ffffff"></path>
        </g>
    </svg>
    ';


    protected $sync_fields = array(
        'primary_blog'     => array(
            'label' => 'Primary blog ID',
            'node'  => 'me'
        ),
        'primary_blog_url' => array(
            'label' => 'Primary blog URL',
            'node'  => 'me'
        ),
        'language'         => array(
            'label' => 'Language',
            'node'  => 'me'
        ),
        'avatar_URL'       => array(
            'label' => 'Gravatar Image URL',
            'node'  => 'me'
        ),
        'email_verified'   => array(
            'label' => 'Is Email verified account?',
            'node'  => 'me'
        ),
        'date'             => array(
            'label' => 'WordPress.com join date',
            'node'  => 'me'
        ),
        'phone_account'    => array(
            'label' => 'Is Phone account?',
            'node'  => 'me'
        )
    );


    public function __construct() {
        $this->id    = 'wordpress';
        $this->label = 'WordPress.com';

        $this->path = dirname(__FILE__);

        $this->requiredFields = array(
            'client_id'     => 'Client ID',
            'client_secret' => 'Secret'
        );

        parent::__construct(array(
            'client_id'      => '',
            'client_secret'  => '',
            'login_label'    => 'Continue with <b>WordPress.com</b>',
            'register_label' => 'Sign up with <b>WordPress.com</b>',
            'link_label'     => 'Link account with <b>WordPress.com</b>',
            'unlink_label'   => 'Unlink account from <b>WordPress.com</b>'
        ));
    }

    protected function forTranslation() {
        __('Continue with <b>WordPress.com</b>', 'nextend-facebook-connect');
        __('Sign up with <b>WordPress.com</b>', 'nextend-facebook-connect');
        __('Link account with <b>WordPress.com</b>', 'nextend-facebook-connect');
        __('Unlink account from <b>WordPress.com</b>', 'nextend-facebook-connect');
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
     * @return NextendSocialAuth|NextendSocialProviderWordpressClient
     */
    public function getClient() {
        if ($this->client === null) {

            require_once dirname(__FILE__) . '/wordpress-client.php';

            $this->client = new NextendSocialProviderWordpressClient($this->id);

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
        $fields       = array(
            'ID',
            'display_name',
            'email',
            'avatar_URL'
        );
        $extra_fields = apply_filters('nsl_wordpress_sync_node_fields', array(), 'me');

        return $this->getClient()
                    ->get('/me?fields=' . implode(',', array_merge($fields, $extra_fields)));
    }

    public function getMe() {
        return $this->authUserData;
    }

    public function getAuthUserData($key) {
        switch ($key) {
            case 'id':
                return $this->authUserData['ID'];
            case 'email':
                return $this->authUserData['email'];
            case 'name':
                return $this->authUserData['display_name'];
            case 'first_name':
                $name = explode(' ', $this->getAuthUserData('name'), 2);

                return isset($name[0]) ? $name[0] : '';
            case 'last_name':
                $name = explode(' ', $this->getAuthUserData('name'), 2);

                return isset($name[1]) ? $name[1] : '';
            case 'picture':
                return !empty($this->authUserData['avatar_URL']) ? $this->authUserData['avatar_URL'] : '';
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

NextendSocialLogin::addProvider(new NextendSocialPROProviderWordpress());