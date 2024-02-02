<?php

use NSL\Notices;

class NextendSocialPROProviderSlack extends NextendSocialProviderOAuth {

    /** @var NextendSocialProviderSlackClient */
    protected $client;

    protected $color = '#4A154B';
    protected $colorLight = '#FFFFFF';

    protected $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path fill="#E01E5A" d="M5 15.5A2.5 2.5 0 1 1 2.5 13H5v2.5ZM6 15.44A2.47 2.47 0 0 1 8.5 13c1.38 0 2.5 1.1 2.5 2.44v6.12A2.47 2.47 0 0 1 8.5 24C7.12 24 6 22.9 6 21.56v-6.12Z"/><path fill="#36C5F0" d="M8.5 5A2.5 2.5 0 1 1 11 2.5V5H8.5ZM8.56 6A2.47 2.47 0 0 1 11 8.5C11 9.88 9.9 11 8.56 11H2.44A2.47 2.47 0 0 1 0 8.5C0 7.12 1.1 6 2.44 6h6.12Z"/><path fill="#2EB67D" d="M19 8.5a2.5 2.5 0 1 1 2.5 2.5H19V8.5ZM18 8.56A2.47 2.47 0 0 1 15.5 11C14.12 11 13 9.9 13 8.56V2.44A2.47 2.47 0 0 1 15.5 0C16.88 0 18 1.1 18 2.44v6.12Z"/><path fill="#ECB22E" d="M15.5 19a2.5 2.5 0 1 1-2.5 2.5V19h2.5ZM15.44 18A2.47 2.47 0 0 1 13 15.5c0-1.38 1.1-2.5 2.44-2.5h6.12A2.47 2.47 0 0 1 24 15.5c0 1.38-1.1 2.5-2.44 2.5h-6.12Z"/></svg>';

    protected $sync_fields = array(

        'team_id'            => array(
            'label' => 'Team ID',
            'node'  => 'me'
        ),
        'locale'             => array(
            'label' => 'Locale',
            'node'  => 'me'
        ),
        'team_name'          => array(
            'label' => 'Team Name',
            'node'  => 'me'
        ),
        'team_domain'        => array(
            'label' => 'Team Domain',
            'node'  => 'me'
        ),
        'team_image_230'     => array(
            'label' => 'Team Avatar URL (230px)',
            'node'  => 'me'
        ),
        'team_image_default' => array(
            'label' => 'Is team avatar default?',
            'node'  => 'me'
        )

    );

    public function __construct() {
        $this->id    = 'slack';
        $this->label = 'Slack';

        $this->path = dirname(__FILE__);

        $this->requiredFields = array(
            'client_id'     => 'Client ID',
            'client_secret' => 'Client Secret'
        );

        parent::__construct(array(
            'client_id'          => '',
            'client_secret'      => '',
            'team_id'            => '',
            'profile_image_size' => 'image_48',
            'skin'               => 'dark',
            'login_label'        => 'Sign in with <b>Slack</b>',
            'register_label'     => 'Sign up with <b>Slack</b>',
            'link_label'         => 'Link account with <b>Slack</b>',
            'unlink_label'       => 'Unlink account from <b>Slack</b>'
        ));
    }

    protected function forTranslation() {
        __('Sign in with <b>Slack</b>', 'nextend-facebook-connect');
        __('Sign up with <b>Slack</b>', 'nextend-facebook-connect');
        __('Link account with <b>Slack</b>', 'nextend-facebook-connect');
        __('Unlink account from <b>Slack</b>', 'nextend-facebook-connect');
    }


    public function getRawDefaultButton() {
        $skin = $this->settings->get('skin');
        $svg  = $this->svg;
        switch ($skin) {
            case 'light':
                $color = $this->colorLight;
                break;
            default:
                $color = $this->color;
        }

        return '<div class="nsl-button nsl-button-default nsl-button-' . $this->id . '" data-skin="' . $skin . '" style="background-color:' . $color . ';"><div class="nsl-button-svg-container">' . $svg . '</div><div class="nsl-button-label-container">{{label}}</div></div>';
    }

    public function getRawIconButton() {
        $skin = $this->settings->get('skin');
        $svg  = $this->svg;
        switch ($skin) {
            case 'light':
                $color = $this->colorLight;
                break;
            default:
                $color = $this->color;
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
                case 'profile_image_size':
                case 'team_id':
                case 'skin':
                    $newData[$key] = trim(sanitize_text_field($value));
                    break;
            }
        }

        return $newData;
    }

    /**
     * @return NextendSocialAuth|NextendSocialProviderSlackClient
     */
    public function getClient() {
        if ($this->client === null) {

            require_once dirname(__FILE__) . '/slack-client.php';

            $this->client = new NextendSocialProviderSlackClient($this->id);

            $this->client->setClientId($this->settings->get('client_id'));
            $this->client->setClientSecret($this->settings->get('client_secret'));
            $this->client->setRedirectUri($this->getRedirectUriForAuthFlow());

            $teamID = $this->settings->get('team_id');
            if ($teamID) {
                $this->client->setTeamID($teamID);
            }
        }

        return $this->client;
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getCurrentUserInfo() {
        $profile_raw = $this->getClient()
                            ->get('/openid.connect.userInfo');

        $profile       = array();
        $sensitiveKeys = array(
            'https://slack.com/user_id',
            'https://slack.com/team_id'
        );
        foreach ($profile_raw as $key => $value) {
            if (!in_array($key, $sensitiveKeys)) {
                /**
                 * some array keys have url format like: https://slack.com/team_name
                 * we need to clean these up because of the sync data feature,
                 * but we shouldn't touch the keys containing the User ID and Team ID.
                 */
                $key = str_replace('https://slack.com/', '', $key);
            }


            $profile[$key] = $value;
        }

        return $profile;
    }

    public function getMe() {
        return $this->authUserData;
    }

    public function getAuthUserData($key) {
        switch ($key) {
            case 'id':
                /**
                 * Different workspaces return different User IDs for the same user. The problem is that, these User IDs are not unique at all.
                 * This means, two different users can receive the same user ID in different workspaces and this could cause a security problem.
                 *
                 * To avoid this problem, we need to generate a unique ID for the user, by combining the team_id with the user_id
                 * as the team_id identifies the workspace, that the user connected with and this is unique per workspace.
                 *
                 */

                return $this->authUserData['https://slack.com/team_id'] . '__' . $this->authUserData['https://slack.com/user_id'];
            case 'email':
                return !empty($this->authUserData['email']) ? $this->authUserData['email'] : '';
            case 'name':
                return !empty($this->authUserData['name']) ? $this->authUserData['name'] : '';
            case 'first_name':
                return !empty($this->authUserData['given_name']) ? $this->authUserData['given_name'] : '';
            case 'last_name':
                return !empty($this->authUserData['family_name']) ? $this->authUserData['family_name'] : '';
            case 'picture':
                $profile_image_size     = $this->settings->get('profile_image_size');
                $profile_image_size_key = 'user_' . $profile_image_size;
                if (isset($this->authUserData[$profile_image_size_key]) && !empty($this->authUserData[$profile_image_size_key])) {
                    return $this->authUserData[$profile_image_size_key];
                } else if (isset($this->authUserData['picture']) && !empty($this->authUserData['picture'])) {
                    //picture key may still return an avatar if the selected size contains no results
                    return $this->authUserData['picture'];
                }

                return '';
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

NextendSocialLogin::addProvider(new NextendSocialPROProviderSlack());