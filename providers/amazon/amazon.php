<?php

use NSL\Notices;

class NextendSocialPROProviderAmazon extends NextendSocialProviderOAuth {

    /** @var NextendSocialProviderAmazonClient */
    protected $client;

    protected $color = '#2f292b';

    protected $svg = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <path d="M17.7096723,9.22641895 C17.7096723,10.0425825 17.7389874,10.8538397 17.6999006,11.6700033 C17.6608137,12.5985621 17.9442753,13.3951413 18.5307012,14.0988687 C18.5991033,14.1819488 18.6626808,14.2650288 18.7212702,14.3530152 C18.9314644,14.6706983 18.8923367,14.8905825 18.6040096,15.1349573 C17.9881868,15.653022 17.3773111,16.1759113 16.7615701,16.693976 C16.3950283,17.0018466 16.1653316,16.9969811 15.7939244,16.693976 C15.295444,16.2883474 14.9044525,15.789867 14.5574951,15.2571242 C14.474415,15.1300919 14.4304219,15.1349573 14.3277983,15.2326745 C13.7119755,15.8337784 13.062013,16.39093 12.2361186,16.6695263 C10.9605615,17.1044701 9.66546104,17.172913 8.38503855,16.7183849 C7.15347467,16.2785348 6.41552577,15.3695195 6.14183583,14.103775 C5.83883071,12.691373 5.99521913,11.3474138 6.86510669,10.1500714 C7.52488182,9.24596239 8.43876257,8.71326052 9.4894883,8.38584655 C10.5744355,8.05352628 11.6935633,7.92645302 12.8175973,7.81405779 C13.1352805,7.77983633 13.4480573,7.75052117 13.7656995,7.72120601 C13.8438733,7.7162997 13.9123162,7.70652798 13.9074099,7.60390447 C13.8976791,7.08588062 13.9514031,6.55804417 13.8292362,6.04979204 C13.6728478,5.39982951 13.2476348,5.01860975 12.5976723,4.87685848 C12.1334133,4.77423497 11.6740198,4.80355013 11.224398,4.95507313 C10.540214,5.18963531 10.1150011,5.66370686 9.94397552,6.36743428 C9.85112373,6.75351946 9.67028557,6.89036444 9.29397211,6.85123667 C8.49248659,6.7681566 7.69100107,6.68507653 6.89442186,6.60199647 C6.566967,6.567775 6.39594145,6.3185348 6.46434349,6.00085166 C6.87974383,3.97270856 8.20906581,2.83399647 10.1248137,2.31106631 C11.595846,1.91034399 13.0864219,1.88589425 14.5623196,2.30129459 C15.7498903,2.63361486 16.746892,3.22008164 17.3040028,4.3832026 C17.6216859,5.04297773 17.6949943,5.75647687 17.704766,6.47001691 C17.7145377,7.38397943 17.704766,8.3076319 17.7096723,9.22641895 C17.704766,9.22641895 17.7096723,9.22641895 17.7096723,9.22641895 Z M13.9074508,10.7512162 C13.9074508,10.5068414 13.89772,10.2625075 13.9123571,10.0181328 C13.9221288,9.86660975 13.8683639,9.81284485 13.7168818,9.82752287 C13.3454338,9.86170345 12.9691612,9.86660975 12.5977132,9.91546836 C11.9477506,9.99854842 11.3123844,10.1598431 10.7845888,10.5703372 C10.0172839,11.171482 9.85603003,11.9925109 9.9830624,12.9161634 C10.1345854,14.0010697 11.1022311,14.6022145 12.1383196,14.2405791 C12.8420471,13.9962043 13.2720845,13.4782213 13.5702242,12.828177 C13.8830011,12.1684837 13.9074508,11.45985 13.9074508,10.7512162 Z" id="amazon" fill="#FFFFFF" fill-rule="nonzero"/>
                        <path d="M12.0600643,21.8339744 C8.33119206,21.8095246 4.9834987,20.8516916 1.9583539,18.8332793 C1.34257196,18.4227444 0.756105176,17.9731226 0.198994444,17.4795485 C0.150135841,17.4355553 0.101236352,17.391603 0.0621494695,17.3377972 C0.00838456321,17.2644888 -0.0307023192,17.1765433 0.0328343077,17.088557 C0.0963709346,16.9907989 0.203900747,16.9810272 0.296752536,17.0250203 C0.511771275,17.1276439 0.71705918,17.2400391 0.927171616,17.3475689 C2.85273209,18.3640732 4.88087519,19.0971566 7.00677639,19.5614156 C8.93229598,19.9817222 10.877359,20.1772384 12.8517781,20.1234326 C15.6716759,20.0452588 18.3889093,19.4783355 21.0035191,18.4275689 C21.1305924,18.3738449 21.262572,18.32008 21.3945106,18.2907239 C21.5802551,18.2565025 21.7659587,18.2809522 21.8490387,18.4813338 C21.9272125,18.6670374 21.824589,18.8136541 21.687744,18.9309556 C21.5899859,19.0139948 21.4775907,19.0824377 21.3651955,19.1557461 C19.6791444,20.250465 17.8317985,20.9541924 15.8769638,21.3891771 C14.5867287,21.6824922 13.276991,21.8241617 12.0600643,21.8339744 Z" id="amazon" fill="#FFFFFF" fill-rule="nonzero"/>
                        <path d="M20.9710406,17.9223611 C20.4090236,17.9174548 19.8519128,17.9907631 19.2947612,18.0493935 C19.1872314,18.0591652 19.0650644,18.0884803 19.0162058,17.9712197 C18.9575755,17.8392401 19.0699707,17.7610664 19.1628225,17.6975297 C19.8568191,17.2381771 20.6338549,17.0573389 21.4500184,17.0133458 C22.0120355,16.9791652 22.5691462,17.0036149 23.1165261,17.1697342 C23.3657663,17.2430425 23.4879333,17.3896592 23.4830678,17.6438057 C23.4537527,19.002402 22.9992246,20.1851073 22.0169418,21.1478875 C21.9827203,21.182109 21.9436334,21.2114241 21.9045466,21.2407393 C21.8166011,21.3043168 21.7090713,21.3629062 21.6161786,21.2847325 C21.5282331,21.2114241 21.5770917,21.1038943 21.6161786,21.0159488 C21.9045466,20.2975434 22.1977799,19.5840442 22.3394494,18.8167393 C22.3590338,18.7189812 22.3638992,18.621264 22.3688055,18.5283713 C22.3736709,18.2106882 22.2612757,18.0738841 21.9484989,17.9957103 C21.621044,17.9076831 21.2935891,17.9223611 20.9710406,17.9223611 Z" id="amazon" fill="#FFFFFF" fill-rule="nonzero"/>
                      </svg>';

    public function __construct() {
        $this->id    = 'amazon';
        $this->label = 'Amazon';

        $this->path = dirname(__FILE__);

        $this->requiredFields = array(
            'client_id'     => 'Client ID',
            'client_secret' => 'Client Secret'
        );

        parent::__construct(array(
            'client_id'      => '',
            'client_secret'  => '',
            'login_label'    => 'Continue with <b>Amazon</b>',
            'register_label' => 'Sign up with <b>Amazon</b>',
            'link_label'     => 'Link account with <b>Amazon</b>',
            'unlink_label'   => 'Unlink account from <b>Amazon</b>'
        ));
    }

    protected function forTranslation() {
        __('Continue with <b>Amazon</b>', 'nextend-facebook-connect');
        __('Sign up with <b>Amazon</b>', 'nextend-facebook-connect');
        __('Link account with <b>Amazon</b>', 'nextend-facebook-connect');
        __('Unlink account from <b>Amazon</b>', 'nextend-facebook-connect');
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
     * @return NextendSocialAuth|NextendSocialProviderAmazonClient
     */
    public function getClient() {
        if ($this->client === null) {

            require_once dirname(__FILE__) . '/amazon-client.php';

            $this->client = new NextendSocialProviderAmazonClient($this->id);

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

        return $this->getClient()
                    ->get("user/profile");
    }

    public function getAuthUserData($key) {
        switch ($key) {
            case 'id':
                return $this->authUserData['user_id'];
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

NextendSocialLogin::addProvider(new NextendSocialPROProviderAmazon());