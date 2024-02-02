<?php

use NSL\Persistent\Persistent;
use NSL\Notices;

class NextendSocialPROProviderSteam extends NextendSocialProviderOpenId {

    /** @var NextendSocialProviderSteamClient */
    protected $client;

    protected $color = '#201D1D';

    protected $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path fill="#fff" d="M11.9792 0C5.6644 0 .4912 4.8608 0 11.0374l6.4428 2.6593a3.3824 3.3824 0 0 1 1.915-.5904c.064 0 .1271.0017.1894.0051l2.8655-4.1463V8.907c0-2.495 2.0335-4.5254 4.5331-4.5254s4.5331 2.0304 4.5331 4.5254c0 2.4949-2.0335 4.5262-4.5331 4.5262-.0346 0-.0683-.0009-.1029-.0018l-4.0868 2.91a3.35 3.35 0 0 1 .0043.1608c0 1.8738-1.5266 3.3977-3.4026 3.3977-1.6468 0-3.0247-1.1739-3.336-2.7277l-4.6084-1.902C1.8406 20.3072 6.4766 24 11.9792 24 18.6184 24 24 18.6267 24 12c0-6.6276-5.3816-12-12.0208-12ZM7.533 18.208l-1.4764-.6092c.2612.5442.7144.9994 1.3155 1.2492 1.2991.5407 2.7972-.0744 3.3386-1.3724.2621-.628.2638-1.3211.0044-1.9508-.2595-.6297-.75-1.1209-1.3787-1.3827-.6254-.2592-1.2948-.2498-1.883-.0282l1.5258.6297c.9583.3987 1.4115 1.4973 1.012 2.4539-.3988.9566-1.4999 1.4092-2.4582 1.0105Zm11.4335-9.301c0-1.6624-1.3554-3.016-3.0204-3.016-1.6658 0-3.0211 1.3536-3.0211 3.016 0 1.6625 1.3553 3.0152 3.0211 3.0152 1.665 0 3.0204-1.3527 3.0204-3.0152Zm-5.2847-.0051c0-1.251 1.0163-2.2648 2.2687-2.2648 1.2532 0 2.2695 1.0139 2.2695 2.2648 0 1.2509-1.0163 2.2648-2.2695 2.2648-1.2524 0-2.2687-1.0139-2.2687-2.2648Z"/></svg>';

    protected $sync_fields = array(
        'communityvisibilitystate' => array(
            'label' => 'Community Visibility State',
            'node'  => 'me'
        ),
        'profilestate'             => array(
            'label' => 'Has community profile',
            'node'  => 'me'
        ),
        'profileurl'               => array(
            'label' => 'Steam Community profile url',
            'node'  => 'me'
        ),
        'personastate'             => array(
            'label' => 'Current status',
            'node'  => 'me'
        ),
        'timecreated'              => array(
            'label' => 'Account creation timestamp',
            'node'  => 'me'
        ),
        'loccountrycode'           => array(
            'label' => 'ISO country code',
            'node'  => 'me'
        )
    );

    public function __construct() {
        $this->id    = 'steam';
        $this->label = 'Steam';

        $this->path = dirname(__FILE__);

        $this->requiredFields = array(
            'web_api_key' => 'Web API Key'
        );

        parent::__construct(array(
            'web_api_key'        => '',
            'login_label'        => 'Continue with <b>Steam</b>',
            'register_label'     => 'Sign up with <b>Steam</b>',
            'link_label'         => 'Link account with <b>Steam</b>',
            'unlink_label'       => 'Unlink account from <b>Steam</b>',
            'profile_image_size' => 'normal'
        ));
    }

    protected function forTranslation() {
        __('Continue with <b>Steam</b>', 'nextend-facebook-connect');
        __('Sign up with <b>Steam</b>', 'nextend-facebook-connect');
        __('Link account with <b>Steam</b>', 'nextend-facebook-connect');
        __('Unlink account from <b>Steam</b>', 'nextend-facebook-connect');
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
                case 'web_api_key':
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
            }
        }

        return $newData;
    }

    /**
     * @return NextendSocialAuth|NextendSocialProviderSteamClient
     */
    public function getClient() {
        if ($this->client === null) {

            require_once dirname(__FILE__) . '/steam-client.php';

            $this->client = new NextendSocialProviderSteamClient($this->id, $this->getRedirectUriForAuthFlow());
        }

        return $this->client;
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getCurrentUserInfo() {
        $openIdClaimedId = Persistent::get($this->id . '_openid_claimed_id');

        if (!$openIdClaimedId) {
            /**
             * We can end up here, when developers try to get data outside of our normal flow, via the getAuthUserDataByAuthOptions() function.
             */
            $openIdClaimedId = $this->getOpenIdClaimedId();
        }
        if ($openIdClaimedId) {
            $steamID64 = false;

            /*
             * $openIdClaimedId contains the user's 64-bit SteamID. The Claimed ID format is: https://steamcommunity.com/openid/id/<steamid>
             */
            preg_match("#^https://steamcommunity.com/openid/id/(.*)#", $openIdClaimedId, $matches);
            if (!empty($matches[1])) {
                $steamID64 = $matches[1];
            }

            if ($steamID64) {
                $profile = array(
                    'claimedId' => $steamID64
                );

                $steamUserData = $this->getClient()
                                      ->get("/ISteamUser/GetPlayerSummaries/v2/?key=" . $this->settings->get('web_api_key') . "&steamids=" . $steamID64 . "&format=json");


                if (!empty($steamUserData['response']['players'][0])) {
                    $profile = array_merge($steamUserData['response']['players'][0], $profile);
                }

                return $profile;
            }
        }

        throw new Exception(__('Error: The 64-bit SteamID can not be retrieved for this user!', 'nextend-facebook-connect'));
    }

    public function getMe() {
        return $this->authUserData;
    }

    public function getAuthUserData($key) {
        switch ($key) {
            case 'id':
                return $this->authUserData['claimedId'];
            case 'email':
                return '';
            case 'name':
                return !empty($this->authUserData['personaname']) ? $this->authUserData['personaname'] : '';
            case 'first_name':
                if (!empty($this->authUserData['realname'])) {
                    $name = explode(' ', $this->authUserData['realname'], 2);

                    return isset($name[0]) ? $name[0] : '';
                } else {
                    return '';
                }
            case 'last_name':
                if (!empty($this->authUserData['realname'])) {
                    $name = explode(' ', $this->authUserData['realname'], 2);

                    return isset($name[1]) ? $name[1] : '';
                } else {
                    return '';
                }
            case 'picture':
                $profile_image_size = $this->settings->get('profile_image_size');
                switch ($profile_image_size) {
                    case 'medium':
                        return !empty($this->authUserData['avatarmedium']) ? $this->authUserData['avatarmedium'] : '';
                    case 'full':
                        return !empty($this->authUserData['avatarfull']) ? $this->authUserData['avatarfull'] : '';
                    default:
                        return !empty($this->authUserData['avatar']) ? $this->authUserData['avatar'] : '';
                }
        }

        return parent::getAuthUserData($key);
    }

    public function syncProfile($user_id, $provider, $data) {
        if ($this->needUpdateAvatar($user_id)) {
            if ($this->getAuthUserData('picture')) {
                $this->updateAvatar($user_id, $this->getAuthUserData('picture'));
            }
        }
    }

    public function deleteLoginPersistentData() {
        parent::deleteLoginPersistentData();
        Persistent::delete($this->id . '_openid_server');
    }

}

NextendSocialLogin::addProvider(new NextendSocialPROProviderSteam());