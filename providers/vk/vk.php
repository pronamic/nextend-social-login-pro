<?php

use NSL\Notices;

class NextendSocialPROProviderVK extends NextendSocialProviderOAuth {

    /** @var NextendSocialProviderVKClient */
    protected $client;

    protected $color = '#45668e';

    protected $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#fff" d="M23.8735527,17.2259056 C23.8425841,17.1639683 23.8154865,17.1123538 23.79226,17.0710623 C23.1350252,16.047997 22.3301738,15.127701 21.403801,14.3400123 L21.3805745,14.3152374 L21.3689612,14.303237 L21.3534769,14.2908495 L21.3418636,14.2908495 C20.9873671,13.9711664 20.6501073,13.6328729 20.3315106,13.2773997 C20.0828307,13.0065857 20.0134057,12.6162962 20.1534407,12.2763373 C20.4709509,11.6987094 20.8485373,11.1562045 21.2799263,10.6578369 C21.6205817,10.2211786 21.8915576,9.87110357 22.092854,9.60761176 C23.5328973,7.71310311 24.1574323,6.50222795 23.9664588,5.97498627 L23.8929082,5.85188579 C23.7871665,5.74671585 23.6523279,5.67565592 23.5057997,5.64787965 C23.2435451,5.58342057 22.9709326,5.57288482 22.7044853,5.61691097 L19.1043769,5.64168591 C19.0227567,5.62127688 18.937126,5.62341765 18.8566275,5.64787965 L18.694042,5.68659049 L18.6321046,5.71755917 L18.5817805,5.75627001 C18.5281025,5.79085919 18.4809301,5.83462468 18.4424215,5.88556422 C18.3924751,5.95238388 18.3508756,6.02505296 18.3185468,6.10195784 C17.9454411,7.06162204 17.4989448,7.99111074 16.9830227,8.88217057 C16.673336,9.39276659 16.3907468,9.83561863 16.1313842,10.2103396 C15.9393987,10.5039193 15.7231519,10.7808965 15.4849131,11.0383645 C15.3351107,11.1899653 15.1760939,11.3321755 15.0087697,11.4641838 C14.9222933,11.5502181 14.8055779,11.5989885 14.6835987,11.6000588 C14.6100481,11.5834132 14.5364975,11.5667675 14.470689,11.550509 C14.3531128,11.4742801 14.2560709,11.3703256 14.1880999,11.2477902 C14.1120211,11.0969578 14.063515,10.9337412 14.0448697,10.7658402 C14.0216432,10.572286 14.0061589,10.4054422 14.0022878,10.265309 C13.9984167,10.1251757 13.9984167,9.92775043 14.01003,9.67225887 C14.0216432,9.41676731 14.0216432,9.24411695 14.0216432,9.15314647 C14.0216432,8.84036286 14.0255143,8.50048166 14.0409987,8.13388998 C14.056483,7.76729829 14.0642252,7.47580565 14.0719673,7.26289601 C14.0797095,7.04998638 14.0835806,6.82197951 14.0835806,6.58313361 C14.0870841,6.39485123 14.072827,6.2066579 14.0409987,6.02105217 C14.0106496,5.885182 13.9665501,5.75275365 13.9093818,5.62581447 C13.8559972,5.50341341 13.7669616,5.39994325 13.6538902,5.3289023 C13.5240502,5.25198309 13.3829904,5.1958204 13.2358131,5.16244568 C12.6800827,5.05204729 12.114599,4.99822258 11.5480204,5.00179568 C9.99958667,4.98515002 9.00084692,5.08424978 8.55954331,5.29832074 C8.38082004,5.39193654 8.22075495,5.5174958 8.08727103,5.6687835 C7.93629874,5.84995025 7.91694332,5.94905001 8.02533368,5.96530856 C8.43582827,5.99319147 8.81795385,6.18467265 9.08601078,6.49680843 L9.15956138,6.64507096 C9.24144793,6.82635876 9.29998237,7.0173111 9.33376017,7.21334613 C9.39704703,7.5103228 9.43588789,7.81198678 9.4498927,8.11530877 C9.48888018,8.6294249 9.48888018,9.14575528 9.4498927,9.6598714 C9.40731077,10.0884004 9.36859993,10.4217008 9.32988909,10.6609338 C9.30378228,10.8621946 9.24630973,11.0581238 9.15956138,11.2415964 C9.11570663,11.3346385 9.06659353,11.4251101 9.01246018,11.5125723 C8.99532661,11.5376869 8.97285466,11.5587048 8.94665174,11.5741226 C8.83910635,11.6151322 8.72496648,11.6361234 8.60986741,11.6360599 C8.45527819,11.6210646 8.3085262,11.5608828 8.18791922,11.4630225 C7.99585942,11.3266745 7.82159961,11.1668497 7.66919393,10.9872662 C7.44266697,10.7231816 7.23932746,10.4400605 7.0614337,10.1410472 C6.83691081,9.77832658 6.60464575,9.35018466 6.36076744,8.8558472 L6.16334214,8.49738479 C6.03559636,8.26511973 5.86526865,7.93104516 5.64848793,7.49090288 C5.43170721,7.05076059 5.24202408,6.62378 5.07556746,6.21344506 C5.01941337,6.05299435 4.91366441,5.91457167 4.77362288,5.81820736 L4.71168553,5.77949651 C4.65095777,5.73570632 4.58453606,5.70041164 4.51426024,5.67459013 C4.42068709,5.63730833 4.32333803,5.61031019 4.22392891,5.59407158 L0.801890406,5.61884652 C0.541314889,5.58644139 0.279932492,5.67252711 0.0896108977,5.85343422 L0.039286802,5.92775904 C0.0102778093,5.98936099 -0.00302178467,6.05718892 0.000575959166,6.12518434 C0.006539652,6.24010371 0.0327492827,6.35306721 0.0779976449,6.45887181 C0.577367518,7.62019709 1.12060968,8.74048888 1.70772413,9.81974718 C2.29612894,10.8990055 2.80324098,11.7680639 3.23680242,12.4269224 C3.67036386,13.085781 4.11166747,13.7078642 4.56071325,14.292398 C5.00975902,14.8769317 5.3117036,15.2524269 5.45493372,15.4150124 C5.59816384,15.5775979 5.71429636,15.7034082 5.79946022,15.7858623 L6.11301805,16.0823873 C6.38678856,16.3419151 6.68061266,16.5794337 6.99175418,16.7927313 C7.40713278,17.0865967 7.83739492,17.3588376 8.28082525,17.6083688 C8.79807476,17.895059 9.34958543,18.1150129 9.92216498,18.2629691 C10.5150468,18.428358 11.1306596,18.4973694 11.7454457,18.4673624 L13.185489,18.4673624 C13.4318762,18.4611332 13.6674135,18.3647144 13.8474444,18.1963865 L13.8977685,18.1344491 C13.9382239,18.0625303 13.9694393,17.9857924 13.9906746,17.9060551 C14.0194125,17.7951932 14.0337246,17.6810863 14.0332565,17.5665611 C14.0226247,17.2452403 14.0499106,16.9237864 14.1145493,16.6088548 C14.1549189,16.3858249 14.2265971,16.1696172 14.3274589,15.9666419 C14.3987343,15.8331449 14.4886737,15.7104884 14.5945637,15.6023729 C14.6574002,15.5335858 14.7289794,15.4733292 14.8074734,15.4231417 C14.8398428,15.4060912 14.8734945,15.3915951 14.9081215,15.3797855 C15.1604664,15.3259935 15.4232137,15.3981768 15.6126589,15.5733398 C15.8965943,15.7784235 16.1529078,16.019254 16.3752625,16.2898775 C16.6152697,16.5744022 16.8978589,16.8933795 17.2307721,17.2475837 C17.4954993,17.5395108 17.7885585,17.8044312 18.1056372,18.0384462 L18.3572576,18.1867088 C18.5628888,18.3017587 18.781114,18.3926859 19.0075998,18.4576847 C19.2309266,18.5367547 19.470623,18.558061 19.704395,18.519622 L22.9057817,18.4700721 C23.1602464,18.4833253 23.4137656,18.4299529 23.6412877,18.3152288 C23.7854053,18.2461745 23.8976076,18.1245528 23.9548455,17.9753476 C23.9873482,17.8372416 23.9886706,17.6936288 23.9587166,17.5549478 C23.9425072,17.4424706 23.9139467,17.3321233 23.8735527,17.2259056 Z"/></svg>';

    public function __construct() {
        $this->id    = 'vk';
        $this->label = 'VKontakte';

        $this->path = dirname(__FILE__);

        $this->requiredFields = array(
            'application_id' => 'Application ID',
            'secure_key'     => 'Secure key'
        );

        $this->authRedirectBehavior = 'default_redirect_but_app_has_restriction';

        parent::__construct(array(
            'application_id' => '',
            'secure_key'     => '',
            'login_label'    => 'Continue with <b>VK</b>',
            'register_label' => 'Sign up with <b>VK</b>',
            'link_label'     => 'Link account with <b>VK</b>',
            'unlink_label'   => 'Unlink account from <b>VK</b>'
        ));
    }

    protected function forTranslation() {
        __('Continue with <b>VK</b>', 'nextend-facebook-connect');
        __('Sign up with <b>VK</b>', 'nextend-facebook-connect');
        __('Link account with <b>VK</b>', 'nextend-facebook-connect');
        __('Unlink account from <b>VK</b>', 'nextend-facebook-connect');
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
                case 'application_id':
                case 'secure_key':
                    $newData[$key] = trim(sanitize_text_field($value));
                    if ($this->settings->get($key) !== $newData[$key]) {
                        $newData['tested'] = 0;
                    }

                    if (empty($newData[$key])) {
                        Notices::addError(sprintf(__('The %1$s entered did not appear to be a valid. Please enter a valid %2$s.', 'nextend-facebook-connect'), $this->requiredFields[$key], $this->requiredFields[$key]));
                    }
                    break;
                case 'redirect':
                case 'redirect_reg':
                case 'load_style':
                    $newData[$key] = trim(sanitize_text_field($value));
                    break;
            }
        }

        return $newData;
    }

    /**
     * @return NextendSocialProviderVKClient
     */
    public function getClient() {
        if ($this->client === null) {

            require_once dirname(__FILE__) . '/vk-client.php';

            $this->client = new NextendSocialProviderVKClient($this->id);

            $this->client->setClientId($this->settings->get('application_id'));
            $this->client->setClientSecret($this->settings->get('secure_key'));
            $this->client->setRedirectUri($this->getRedirectUriForAuthFlow());
        }

        return $this->client;
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getCurrentUserInfo() {

        $users = $this->getClient()
                      ->get('users.get?fields=photo_max_orig,verified,screen_name');

        if (isset($users['response']) && isset($users['response'][0])) {

            return $users['response'][0];
        }

        return $users;
    }

    public function getAuthUserData($key) {
        switch ($key) {
            case 'id':
                return $this->authUserData['id'];
            case 'email':
                return $this->getClient()
                            ->getEmail();
            case 'name':
                return (!empty($this->authUserData['first_name']) && !empty($this->authUserData['last_name'])) ? $this->authUserData['first_name'] . ' ' . $this->authUserData['last_name'] : '';
            case 'first_name':
                return !empty($this->authUserData['first_name']) ? $this->authUserData['first_name'] : '';
            case 'last_name':
                return !empty($this->authUserData['last_name']) ? $this->authUserData['last_name'] : '';
            case 'picture':
                if (!in_array($this->authUserData['photo_max_orig'], array(
                    'https://vk.com/images/camera_a.gif',
                    'https://vk.com/images/camera_b.gif',
                    'https://vk.com/images/camera_c.gif',
                    'https://vk.com/images/camera_50.png',
                    'https://vk.com/images/camera_200.png',
                    'https://vk.com/images/camera_400.png'
                ))) {
                    return $this->authUserData['photo_max_orig'];
                } else {
                    return '';
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

NextendSocialLogin::addProvider(new NextendSocialPROProviderVK());