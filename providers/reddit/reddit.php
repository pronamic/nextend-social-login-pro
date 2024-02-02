<?php

use NSL\Notices;

class NextendSocialPROProviderReddit extends NextendSocialProviderOAuth {

    /** @var NextendSocialProviderRedditClient */
    protected $client;

    protected $color = '#FF4500';

    protected $svg = '<svg width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#a)"><path d="M24 11.7a2.5 2.5 0 0 0-1.6-2.4 2.7 2.7 0 0 0-2.8.5 13 13 0 0 0-7-2.2l1.1-5.5 4 .8c0 .5.1.9.5 1.2a1.9 1.9 0 0 0 3.1-.9c.1-.4 0-.9-.2-1.3s-.5-.7-1-.8a2 2 0 0 0-2.3 1l-4.3-1H13l-.2.4-1.3 6.1c-2.6 0-5 .8-7.2 2.2a2.6 2.6 0 0 0-3.1-.3A2.6 2.6 0 0 0 0 11.3a2.5 2.5 0 0 0 .6 2c.3.4.6.6 1 .7l-.1.8c0 4 4.7 7.2 10.5 7.2s10.5-3.2 10.5-7.2V14a2.6 2.6 0 0 0 1.5-2.3ZM6 13.5a1.8 1.8 0 0 1 1.2-1.7 2 2 0 0 1 2 .4 1.8 1.8 0 0 1 .4 2A1.8 1.8 0 0 1 8 15.4c-.5 0-1-.2-1.3-.6-.4-.3-.6-.8-.6-1.3Zm10.5 4.9c-1.3 1.2-3.8 1.3-4.5 1.3-.7 0-3.2 0-4.4-1.3a.5.5 0 0 1-.2-.5.5.5 0 0 1 .3-.3.5.5 0 0 1 .5.1c.8.8 2.6 1 3.8 1 1.2 0 3-.2 3.8-1a.5.5 0 0 1 .7 0 .5.5 0 0 1 0 .5v.2Zm-.4-3a1.9 1.9 0 0 1-1.7-1.2 1.8 1.8 0 0 1 .4-2 1.9 1.9 0 0 1 3.2 1.3c0 .5-.2 1-.5 1.3-.4.4-.9.6-1.4.6Z" fill="#fff"/></g><defs><clipPath id="a"><path fill="#fff" d="M0 0h24v24H0z"/></clipPath></defs></svg>';

    protected $popupWidth = 850;

    protected $popupHeight = 630;

    protected $sync_fields = array(
        'comment_karma' => array(
            'label' => 'Comment Karma',
            'node'  => 'me'
        ),
        'awarder_karma' => array(
            'label' => 'Awarder Karma',
            'node'  => 'me'
        ),
        'awardee_karma' => array(
            'label' => 'Awardee Karma',
            'node'  => 'me'
        ),
        'link_karma'    => array(
            'label' => 'Link Karma',
            'node'  => 'me'
        ),
        'total_karma'   => array(
            'label' => 'Total Karma',
            'node'  => 'me'
        ),
    );

    public function __construct() {
        $this->id    = 'reddit';
        $this->label = 'Reddit';

        $this->path = dirname(__FILE__);

        $this->requiredFields = array(
            'client_id'     => 'Client ID',
            'client_secret' => 'Client Secret'
        );

        parent::__construct(array(
            'client_id'      => '',
            'client_secret'  => '',
            'login_label'    => 'Continue with <b>Reddit</b>',
            'register_label' => 'Sign up with <b>Reddit</b>',
            'link_label'     => 'Link account with <b>Reddit</b>',
            'unlink_label'   => 'Unlink account from <b>Reddit</b>'
        ));
    }

    protected function forTranslation() {
        __('Continue with <b>Reddit</b>', 'nextend-facebook-connect');
        __('Sign up with <b>Reddit</b>', 'nextend-facebook-connect');
        __('Link account with <b>Reddit</b>', 'nextend-facebook-connect');
        __('Unlink account from <b>Reddit</b>', 'nextend-facebook-connect');
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
     * @return NextendSocialAuth|NextendSocialProviderRedditClient
     */
    public function getClient() {
        if ($this->client === null) {

            require_once dirname(__FILE__) . '/reddit-client.php';

            $this->client = new NextendSocialProviderRedditClient($this->id);

            $this->client->setClientId(apply_filters('nsl_reddit_client_id', $this->settings->get('client_id')));
            $this->client->setClientSecret(apply_filters('nsl_reddit_client_secret', $this->settings->get('client_secret')));
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
                    ->get('/me');
    }

    public function getMe() {
        return $this->authUserData;
    }

    public function getAuthUserData($key) {
        switch ($key) {
            case 'id':
                return $this->authUserData['id'];
            case 'email':
                /**
                 * Reddit does not return the email address of the user
                 */ return '';
            case 'name':
                if (!empty($this->authUserData['subreddit']['title'])) {
                    return $this->authUserData['subreddit']['title'];
                }

                return $this->authUserData['name'];
            case 'first_name':
                $name = explode(' ', $this->getAuthUserData('name'), 2);

                return isset($name[0]) ? $name[0] : '';
            case 'last_name':
                $name = explode(' ', $this->getAuthUserData('name'), 2);

                return isset($name[1]) ? $name[1] : '';
            case 'picture':
                $avatar_url = '';
                if (!empty($this->authUserData['subreddit']['icon_img'])) {
                    $parsed_avatar_url = parse_url($this->authUserData['subreddit']['icon_img']);

                    if ($parsed_avatar_url !== false) {
                        $avatar_url = $parsed_avatar_url['scheme'] . '://' . $parsed_avatar_url['host'] . $parsed_avatar_url['path'];
                    }

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

    public function deleteLoginPersistentData() {
        parent::deleteLoginPersistentData();

        if ($this->client !== null) {
            $this->client->deleteLoginPersistentData();
        }
    }

}

NextendSocialLogin::addProvider(new NextendSocialPROProviderReddit());