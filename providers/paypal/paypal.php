<?php

use NSL\Notices;

class NextendSocialPROProviderPaypal extends NextendSocialProviderOAuth {

    /** @var NextendSocialProviderPaypalClient */
    protected $client;

    protected $color = '#014ea0';

    protected $svg = '<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <path d="M20.1770645,6.0470303 C20.4674888,4.19539394 20.1751251,2.93575758 19.1736706,1.79442424 C18.0711251,0.538181818 16.0793676,0 13.5310039,0 L6.1336706,0 C5.61294333,0 5.16930696,0.378909091 5.08833727,0.893575758 L2.00785242,20.4261818 C1.94724636,20.8116364 2.24518575,21.16 2.63500393,21.16 L7.20179181,21.16 L6.8866403,23.1587879 C6.83354939,23.496 7.09391302,23.8007273 7.43524636,23.8007273 L11.2847009,23.8007273 C11.7402161,23.8007273 12.1280948,23.4693333 12.1991251,23.0191515 L12.2369433,22.8232727 L12.9620342,18.2249697 L13.0088221,17.9709091 C13.0798524,17.5209697 13.4677312,17.1890909 13.9232464,17.1890909 L14.4990039,17.1890909 C18.2282161,17.1890909 21.1482161,15.6741818 22.001307,11.2926061 C22.357913,9.46181818 22.1734282,7.93357576 21.2306403,6.85915152 C20.9457918,6.53478788 20.5908827,6.26618182 20.1770645,6.0470303" id="Shape" fill="#fff" fill-rule="nonzero" opacity="0.68"></path>
            <path d="M20.1770645,6.0470303 C20.4674888,4.19539394 20.1751251,2.93575758 19.1736706,1.79442424 C18.0711251,0.538181818 16.0793676,0 13.5310039,0 L6.1336706,0 C5.61294333,0 5.16930696,0.378909091 5.08833727,0.893575758 L2.00785242,20.4261818 C1.94724636,20.8116364 2.24518575,21.16 2.63500393,21.16 L7.20179181,21.16 L8.34894333,13.8855758 L8.31330696,14.113697 C8.39451908,13.5992727 8.83403424,13.2201212 9.35500393,13.2201212 L11.5256706,13.2201212 C15.7884585,13.2201212 19.1263979,11.4882424 20.1014282,6.47975758 C20.1305191,6.33187879 20.1550039,6.18812121 20.1770645,6.0470303" id="Shape" fill="#fff" fill-rule="nonzero" opacity="0.7"></path>
            <path d="M9.58142817,6.07151515 C9.63015545,5.76218182 9.82894333,5.50860606 10.0963373,5.38060606 C10.2177918,5.32242424 10.3537918,5.28993939 10.4960948,5.28993939 L16.2946403,5.28993939 C16.9816706,5.28993939 17.6221554,5.3350303 18.20761,5.42933333 C18.3748827,5.45624242 18.5377918,5.48727273 18.6960948,5.52242424 C18.8541554,5.55757576 19.0078524,5.59660606 19.1564585,5.64024242 C19.2308827,5.66181818 19.3038524,5.68484848 19.3758524,5.70860606 C19.6633676,5.80412121 19.9312464,5.91660606 20.1775494,6.04727273 C20.4679736,4.19563636 20.17561,2.936 19.1741554,1.79466667 C18.0711251,0.538181818 16.0793676,0 13.5310039,0 L6.1336706,0 C5.61294333,0 5.16930696,0.378909091 5.08833727,0.893575758 L2.00785242,20.4261818 C1.94724636,20.8116364 2.24518575,21.16 2.63500393,21.16 L7.20179181,21.16 L8.34894333,13.8855758 L9.58142817,6.07151515 Z" id="Shape" fill="#fff" fill-rule="nonzero"></path>
        </svg>';


    protected $sync_fields = array(
        'address'          => array(
            'label'       => 'Address',
            'node'        => 'me',
            'scope'       => 'address',
            'description' => 'Street Address, City, State, Country, Postal Code'
        ),
        'verified_account' => array(
            'label'       => 'Account verification status',
            'node'        => 'me',
            'scope'       => 'https://uri.paypal.com/services/paypalattributes',
            'description' => 'Account verification status'
        ),
        'payer_id'         => array(
            'label'       => 'PayPal account ID (payer ID)',
            'node'        => 'me',
            'scope'       => 'https://uri.paypal.com/services/paypalattributes',
            'description' => 'PayPal account ID (payer ID)'
        )

    );


    public function __construct() {
        $this->id    = 'paypal';
        $this->label = 'PayPal';

        $this->path = dirname(__FILE__);

        $this->requiredFields = array(
            'client_id'     => 'Client ID',
            'client_secret' => 'Secret'
        );

        parent::__construct(array(
            'client_id'      => '',
            'client_secret'  => '',
            'scope_email'    => 1,
            'login_label'    => 'Continue with <b>PayPal</b>',
            'register_label' => 'Sign up with <b>PayPal</b>',
            'link_label'     => 'Link account with <b>PayPal</b>',
            'unlink_label'   => 'Unlink account from <b>PayPal</b>'
        ));
    }

    protected function forTranslation() {
        __('Continue with <b>PayPal</b>', 'nextend-facebook-connect');
        __('Sign up with <b>PayPal</b>', 'nextend-facebook-connect');
        __('Link account with <b>PayPal</b>', 'nextend-facebook-connect');
        __('Unlink account from <b>PayPal</b>', 'nextend-facebook-connect');
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
                case 'scope_email':
                    $newData[$key] = $value ? 1 : 0;
                    break;
            }
        }

        return $newData;
    }

    /**
     * @return NextendSocialAuth|NextendSocialProviderPaypalClient
     */
    public function getClient() {
        if ($this->client === null) {

            require_once dirname(__FILE__) . '/paypal-client.php';

            $this->client = new NextendSocialProviderPaypalClient($this->id);

            $this->client->setClientId($this->settings->get('client_id'));
            $this->client->setClientSecret($this->settings->get('client_secret'));
            $this->client->setScopeEmail($this->settings->get('scope_email'));
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
                    ->get('/userinfo?schema=openid');
    }

    public function getMe() {
        return $this->authUserData;
    }

    public function getAuthUserData($key) {
        switch ($key) {
            case 'id':
                return $this->authUserData['user_id'];
            case 'email':
                return !empty($this->authUserData['email']) ? $this->authUserData['email'] : '';
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

    public function getSyncDataFieldDescription($fieldName) {
        if (isset($this->sync_fields[$fieldName]['description'])) {
            return sprintf(__('Required scope: %1$s', 'nextend-facebook-connect'), $this->sync_fields[$fieldName]['description']);
        }

        return parent::getSyncDataFieldDescription($fieldName);
    }

}

NextendSocialLogin::addProvider(new NextendSocialPROProviderPaypal());