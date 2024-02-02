<?php

use NSL\Notices;

class NextendSocialPROProviderGitHub extends NextendSocialProviderOAuth {

    /** @var NextendSocialProviderGitHubClient */
    protected $client;

    protected $color = '#24292e';

    protected $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#FFF" d="M12 1.032c-6.63 0-12 5.28-12 11.791 0 5.211 3.438 9.63 8.205 11.188.6.111.82-.254.82-.567 0-.28-.01-1.022-.015-2.005-3.338.711-4.042-1.582-4.042-1.582-.546-1.36-1.335-1.725-1.335-1.725-1.087-.73.084-.716.084-.716 1.205.082 1.838 1.215 1.838 1.215 1.07 1.803 2.809 1.282 3.495.981.108-.763.417-1.282.76-1.577-2.665-.295-5.466-1.309-5.466-5.827 0-1.287.465-2.339 1.235-3.164-.135-.298-.54-1.497.105-3.12 0 0 1.005-.316 3.3 1.209.96-.262 1.98-.392 3-.398 1.02.006 2.04.136 3 .398 2.28-1.525 3.285-1.21 3.285-1.21.645 1.624.24 2.823.12 3.121.765.825 1.23 1.877 1.23 3.164 0 4.53-2.805 5.527-5.475 5.817.42.354.81 1.077.81 2.182 0 1.578-.015 2.846-.015 3.23 0 .308.21.677.825.56C20.565 22.447 24 18.026 24 12.822c0-6.511-5.373-11.791-12-11.791z"/></svg>';


    protected $sync_fields = array(
        'login'               => array(
            'label' => 'GitHub username',
            'node'  => 'me'
        ),
        'type'                => array(
            'label' => 'GitHub account type',
            'node'  => 'me'
        ),
        'company'             => array(
            'label' => 'Company name',
            'node'  => 'me'
        ),
        'blog'                => array(
            'label' => 'Blog URL',
            'node'  => 'me'
        ),
        'location'            => array(
            'label' => 'Location',
            'node'  => 'me'
        ),
        'hireable'            => array(
            'label' => 'Hireable',
            'node'  => 'me'
        ),
        'bio'                 => array(
            'label' => 'Bio',
            'node'  => 'me'
        ),
        'twitter_username'    => array(
            'label' => 'Twitter Username',
            'node'  => 'me'
        ),
        'public_repos'        => array(
            'label' => 'Public repo count',
            'node'  => 'me'
        ),
        'public_gists'        => array(
            'label' => 'Public gist count',
            'node'  => 'me'
        ),
        'followers'           => array(
            'label' => 'Follower count',
            'node'  => 'me'
        ),
        'following'           => array(
            'label' => 'Following count',
            'node'  => 'me'
        ),
        'created_at'          => array(
            'label' => 'Registration date',
            'node'  => 'me'
        ),
        'updated_at'          => array(
            'label' => 'Profile update date',
            'node'  => 'me'
        ),
        'url'                 => array(
            'label' => 'JSON Profile URL',
            'node'  => 'me'
        ),
        'html_url'            => array(
            'label' => 'HTML Profile URL',
            'node'  => 'me'
        ),
        'followers_url'       => array(
            'label' => 'JSON Followers URL',
            'node'  => 'me'
        ),
        'subscriptions_url'   => array(
            'label' => 'JSON Subscriptions URL',
            'node'  => 'me'
        ),
        'organizations_url'   => array(
            'label' => 'JSON Organizations URL',
            'node'  => 'me'
        ),
        'repos_url'           => array(
            'label' => 'JSON Repositories URL',
            'node'  => 'me'
        ),
        'received_events_url' => array(
            'label' => 'JSON Received Events URL',
            'node'  => 'me'
        )

    );

    public function __construct() {
        $this->id    = 'github';
        $this->label = 'GitHub';

        $this->path = dirname(__FILE__);

        $this->requiredFields = array(
            'client_id'     => 'Client ID',
            'client_secret' => 'Client Secret'
        );

        parent::__construct(array(
            'client_id'      => '',
            'client_secret'  => '',
            'login_label'    => 'Continue with <b>GitHub</b>',
            'register_label' => 'Sign up with <b>GitHub</b>',
            'link_label'     => 'Link account with <b>GitHub</b>',
            'unlink_label'   => 'Unlink account from <b>GitHub</b>'
        ));
    }

    protected function forTranslation() {
        __('Continue with <b>GitHub</b>', 'nextend-facebook-connect');
        __('Sign up with <b>GitHub</b>', 'nextend-facebook-connect');
        __('Link account with <b>GitHub</b>', 'nextend-facebook-connect');
        __('Unlink account from <b>GitHub</b>', 'nextend-facebook-connect');
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
     * @return NextendSocialProviderGitHubClient
     */
    public function getClient() {
        if ($this->client === null) {

            require_once dirname(__FILE__) . '/github-client.php';

            $this->client = new NextendSocialProviderGitHubClient($this->id);

            $this->client->setClientId(apply_filters('nsl_github_client_id', $this->settings->get('client_id')));
            $this->client->setClientSecret(apply_filters('nsl_github_client_secret', $this->settings->get('client_secret')));
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
                    ->get('');
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
                return !empty($this->authUserData['name']) ? $this->authUserData['name'] : '';
            case 'first_name':
                $name = explode(' ', $this->getAuthUserData('name'), 2);

                return isset($name[0]) ? $name[0] : '';
            case 'last_name':
                $name = explode(' ', $this->getAuthUserData('name'), 2);

                return isset($name[1]) ? $name[1] : '';
            case 'picture':
                return !empty($this->authUserData['avatar_url']) ? $this->authUserData['avatar_url'] : '';
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

    public function getIcon() {
        return plugins_url('/providers/' . $this->id . '/' . $this->id . '.png', NSL_PRO_PATH_PLUGIN);
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
}

NextendSocialLogin::addProvider(new NextendSocialPROProviderGitHub());