<?php

use NSL\Notices;

class NextendSocialPROProviderDisqus extends NextendSocialProviderOAuth {

    /** @var NextendSocialProviderDisqusClient */
    protected $client;

    protected $color = '#2e9fff';
    protected $svg = '
    <svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
        <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
            <path d="M12.4376978,23.3093525 C9.58566906,23.3093525 6.97795683,22.2671655 4.96178417,20.5431367 L0,21.2210072 L1.91689209,16.4898993 C1.24903597,15.0162302 0.874359712,13.380259 0.874359712,11.6546763 C0.874359712,5.21835971 6.05145324,0 12.4376978,0 C18.8234245,0 24,5.21835971 24,11.6546763 C24,18.0922014 18.8235971,23.3093525 12.4376978,23.3093525 Z M18.7514245,11.6213525 L18.7514245,11.5888921 C18.7514245,8.22561151 16.379741,5.82768345 12.2904173,5.82768345 L7.87372662,5.82768345 L7.87372662,17.4823597 L12.2249784,17.4823597 C16.3460719,17.4825324 18.7514245,14.9842878 18.7514245,11.6213525 Z M12.3391079,14.6189353 L11.0472518,14.6189353 L11.0472518,8.69197122 L12.3391079,8.69197122 C14.2364892,8.69197122 15.4958849,9.77369784 15.4958849,11.6393094 L15.4958849,11.6717698 C15.4958849,13.5530935 14.2364892,14.6189353 12.3391079,14.6189353 Z" fill="#ffffff" fill-rule="nonzero"></path>
        </g>
    </svg>
    ';


    protected $sync_fields = array(
        'about'        => array(
            'label' => 'About',
            'node'  => 'me'
        ),
        'location'     => array(
            'label' => 'Location',
            'node'  => 'me'
        ),
        'joinedAt'     => array(
            'label' => 'Disqus join date',
            'node'  => 'me'
        ),
        'profileUrl'   => array(
            'label' => 'Profile link',
            'node'  => 'me'
        ),
        'numFollowers' => array(
            'label' => 'Followers',
            'node'  => 'me'
        ),
        'numFollowing' => array(
            'label' => 'Following',
            'node'  => 'me'
        ),

        'reputationLabel'    => array(
            'label' => 'Reputation label',
            'node'  => 'me'
        ),
        'reputation'         => array(
            'label' => 'Reputation',
            'node'  => 'me'
        ),
        'numPosts'           => array(
            'label' => 'Number of posts',
            'node'  => 'me'
        ),
        'numForumsFollowing' => array(
            'label' => 'Number of followed forums',
            'node'  => 'me'
        )
    );


    public function __construct() {
        $this->id    = 'disqus';
        $this->label = 'Disqus';

        $this->path = dirname(__FILE__);

        $this->requiredFields = array(
            'api_key'    => 'API Key',
            'api_secret' => 'API Secret'
        );

        parent::__construct(array(
            'api_key'        => '',
            'api_secret'     => '',
            'login_label'    => 'Continue with <b>Disqus</b>',
            'register_label' => 'Sign up with <b>Disqus</b>',
            'link_label'     => 'Link account with <b>Disqus</b>',
            'unlink_label'   => 'Unlink account from <b>Disqus</b>'
        ));
    }

    protected function forTranslation() {
        __('Continue with <b>Disqus</b>', 'nextend-facebook-connect');
        __('Sign up with <b>Disqus</b>', 'nextend-facebook-connect');
        __('Link account with <b>Disqus</b>', 'nextend-facebook-connect');
        __('Unlink account from <b>Disqus</b>', 'nextend-facebook-connect');
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
                case 'api_key':
                case 'api_secret':
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
     * @return NextendSocialAuth|NextendSocialProviderDisqusClient
     */
    public function getClient() {
        if ($this->client === null) {

            require_once dirname(__FILE__) . '/disqus-client.php';

            $this->client = new NextendSocialProviderDisqusClient($this->id);

            $this->client->setClientId(apply_filters('nsl_disqus_api_key', $this->settings->get('api_key')));
            $this->client->setClientSecret(apply_filters('nsl_disqus_api_secret', $this->settings->get('api_secret')));
            $this->client->setRedirectUri($this->getRedirectUriForAuthFlow());
        }

        return $this->client;
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getCurrentUserInfo() {

        return $this->getClient()
                    ->get('/users/details.json');
    }

    public function getMe() {
        return $this->authUserData;
    }

    public function getAuthUserData($key) {
        switch ($key) {
            case 'id':
                return $this->authUserData['id'];
            case 'email':
                return $this->authUserData['email'];
            case 'name':
                return $this->authUserData['name'];
            case 'first_name':
                $name = explode(' ', $this->getAuthUserData('name'), 2);

                return isset($name[0]) ? $name[0] : '';
            case 'last_name':
                $name = explode(' ', $this->getAuthUserData('name'), 2);

                return isset($name[1]) ? $name[1] : '';
            case 'picture':
                return !empty($this->authUserData['avatar']['permalink']) ? $this->authUserData['avatar']['permalink'] : '';
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

NextendSocialLogin::addProvider(new NextendSocialPROProviderDisqus());