<?php

use NSL\Notices;

class NextendSocialPROProviderApple extends NextendSocialProviderOAuth {

    /** @var NextendSocialProviderAppleClient */
    protected $client;

    protected $color = '#000000';

    protected $fieldNames;

    protected $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="31" height="44" viewBox="0 0 31 44"><rect width="31" height="44" fill="#000"/><path fill="#FFF" fill-rule="nonzero" d="M15.7099491,14.8846154 C16.5675461,14.8846154 17.642562,14.3048315 18.28274,13.5317864 C18.8625238,12.8312142 19.2852829,11.852829 19.2852829,10.8744437 C19.2852829,10.7415766 19.2732041,10.6087095 19.2490464,10.5 C18.2948188,10.5362365 17.1473299,11.140178 16.4588366,11.9494596 C15.9152893,12.56548 15.4200572,13.5317864 15.4200572,14.5222505 C15.4200572,14.6671964 15.4442149,14.8121424 15.4562937,14.8604577 C15.5166879,14.8725366 15.6133185,14.8846154 15.7099491,14.8846154 Z M12.6902416,29.5 C13.8618881,29.5 14.3812778,28.714876 15.8428163,28.714876 C17.3285124,28.714876 17.6546408,29.4758423 18.9591545,29.4758423 C20.2395105,29.4758423 21.0971074,28.292117 21.9063891,27.1325493 C22.8123013,25.8038779 23.1867451,24.4993643 23.2109027,24.4389701 C23.1263509,24.4148125 20.6743484,23.4122695 20.6743484,20.5979021 C20.6743484,18.1579784 22.6069612,17.0588048 22.7156707,16.974253 C21.4353147,15.1382708 19.490623,15.0899555 18.9591545,15.0899555 C17.5217737,15.0899555 16.3501271,15.9596313 15.6133185,15.9596313 C14.8161157,15.9596313 13.7652575,15.1382708 12.521138,15.1382708 C10.1536872,15.1382708 7.75,17.0950413 7.75,20.7911634 C7.75,23.0861411 8.64383344,25.513986 9.74300699,27.0842339 C10.6851558,28.4129053 11.5065162,29.5 12.6902416,29.5 Z"/></svg>';

    protected $svgLight = '<svg xmlns="http://www.w3.org/2000/svg" width="31" height="44" viewBox="0 0 31 44"><rect width="31" height="44" fill="#FFF"/><path fill="#000" fill-rule="nonzero" d="M15.7099491,14.8846154 C16.5675461,14.8846154 17.642562,14.3048315 18.28274,13.5317864 C18.8625238,12.8312142 19.2852829,11.852829 19.2852829,10.8744437 C19.2852829,10.7415766 19.2732041,10.6087095 19.2490464,10.5 C18.2948188,10.5362365 17.1473299,11.140178 16.4588366,11.9494596 C15.9152893,12.56548 15.4200572,13.5317864 15.4200572,14.5222505 C15.4200572,14.6671964 15.4442149,14.8121424 15.4562937,14.8604577 C15.5166879,14.8725366 15.6133185,14.8846154 15.7099491,14.8846154 Z M12.6902416,29.5 C13.8618881,29.5 14.3812778,28.714876 15.8428163,28.714876 C17.3285124,28.714876 17.6546408,29.4758423 18.9591545,29.4758423 C20.2395105,29.4758423 21.0971074,28.292117 21.9063891,27.1325493 C22.8123013,25.8038779 23.1867451,24.4993643 23.2109027,24.4389701 C23.1263509,24.4148125 20.6743484,23.4122695 20.6743484,20.5979021 C20.6743484,18.1579784 22.6069612,17.0588048 22.7156707,16.974253 C21.4353147,15.1382708 19.490623,15.0899555 18.9591545,15.0899555 C17.5217737,15.0899555 16.3501271,15.9596313 15.6133185,15.9596313 C14.8161157,15.9596313 13.7652575,15.1382708 12.521138,15.1382708 C10.1536872,15.1382708 7.75,17.0950413 7.75,20.7911634 C7.75,23.0861411 8.64383344,25.513986 9.74300699,27.0842339 C10.6851558,28.4129053 11.5065162,29.5 12.6902416,29.5 Z"/></svg>';

    public function __construct() {
        $this->id    = 'apple';
        $this->label = 'Apple';

        $this->path = dirname(__FILE__);


        $this->requiredFields = array(
            'client_id'     => 'Client ID',
            'client_secret' => 'Client Secret'
        );

        $this->fieldNames = array(
            'private_key_id'     => 'Private Key ID',
            'private_key'        => 'Private Key',
            'team_identifier'    => 'Team Identifier',
            'service_identifier' => 'Service Identifier',
        );


        parent::__construct(array(
            'client_id'                 => '',
            'client_secret'             => '',
            'private_key_id'            => '',
            'private_key'               => '',
            'team_identifier'           => '',
            'service_identifier'        => '',
            'expiration_timestamp'      => 0,
            'has_credentials'           => 0,
            'show_client_secret_notice' => 0,
            'skin'                      => 'dark',
            'login_label'               => 'Continue with <b>Apple</b>',
            'register_label'            => 'Sign up with <b>Apple</b>',
            'link_label'                => 'Link account with <b>Apple</b>',
            'unlink_label'              => 'Unlink account from <b>Apple</b>'
        ));

        add_action('admin_notices', array(
            $this,
            'show_admin_apple_client_secret_notice'
        ));

        add_action('nslpro_weekly_cron', array(
            $this,
            'weekly_apple_client_secret_check'
        ));
    }

    protected function forTranslation() {
        __('Continue with <b>Apple</b>', 'nextend-facebook-connect');
        __('Sign up with <b>Apple</b>', 'nextend-facebook-connect');
        __('Link account with <b>Apple</b>', 'nextend-facebook-connect');
        __('Unlink account from <b>Apple</b>', 'nextend-facebook-connect');
    }

    public function getRawDefaultButton() {
        $skin = $this->settings->get('skin');
        switch ($skin) {
            case 'light':
                $color = '#fff';
                $svg   = $this->svgLight;
                break;
            default:
                $color = $this->color;
                $svg   = $this->svg;
        }

        return '<div class="nsl-button nsl-button-default nsl-button-' . $this->id . '" data-skin="' . $skin . '" style="background-color:' . $color . ';"><div class="nsl-button-svg-container">' . $svg . '</div><div class="nsl-button-label-container">{{label}}</div></div>';
    }

    public function getRawIconButton() {
        $skin = $this->settings->get('skin');
        switch ($skin) {
            case 'light':
                $color = '#fff';
                $svg   = $this->svgLight;
                break;
            default:
                $color = $this->color;
                $svg   = $this->svg;
        }

        return '<div class="nsl-button nsl-button-icon nsl-button-' . $this->id . '" data-skin="' . $skin . '" style="background-color:' . $color . ';"><div class="nsl-button-svg-container">' . $svg . '</div></div>';
    }


    public function validateSettings($newData, $postedData) {
        $newData = parent::validateSettings($newData, $postedData);

        foreach ($postedData as $key => $value) {

            switch ($key) {
                case 'private_key_id':
                case 'team_identifier':
                case 'service_identifier':
                    $sanitizedValue = trim(sanitize_text_field($value));
                    if (empty($sanitizedValue)) {
                        Notices::addError(sprintf(__('The %1$s entered did not appear to be a valid. Please enter a valid %2$s.', 'nextend-facebook-connect'), $this->fieldNames[$key], $this->fieldNames[$key]));
                    } else {
                        $newData[$key] = $sanitizedValue;
                    }
                    break;
                case 'private_key':
                    if (empty($value)) {
                        Notices::addError(sprintf(__('The %1$s entered did not appear to be a valid. Please enter a valid %2$s.', 'nextend-facebook-connect'), $this->fieldNames[$key], $this->fieldNames[$key]));
                    } else {
                        $newData[$key] = $value;
                    }
                    break;
                case 'has_credentials':
                    if ($value == 0) {
                        $newData[$key]                        = 0;
                        $newData['client_id']                 = '';
                        $newData['client_secret']             = '';
                        $newData['tested']                    = 0;
                        $newData['expiration_timestamp']      = 0;
                        $newData['show_client_secret_notice'] = 0;
                    }
                    break;
                case 'tested':
                    if ($postedData[$key] == '1' && (!isset($newData['tested']) || $newData['tested'] != '0')) {
                        $newData['tested'] = 1;
                    } else {
                        $newData['tested'] = 0;
                    }
                    break;
                case 'skin':
                    $newData[$key] = trim(sanitize_text_field($value));
                    break;
            }
        }

        if (isset($newData['private_key_id']) && isset($newData['private_key']) && isset($newData['team_identifier']) && isset($newData['service_identifier'])) {
            try {
                $time                = new DateTime("now", new DateTimeZone("UTC"));
                $expirationTimeStamp = $time->getTimestamp() + MONTH_IN_SECONDS * 6;

                $newData['client_id']     = $newData['service_identifier'];
                $newData['client_secret'] = $this->generateClientSecret($newData['private_key_id'], $newData['private_key'], $newData['team_identifier'], $newData['service_identifier'], $expirationTimeStamp);
                if (!empty($newData['client_id']) && !empty($newData['client_secret'])) {
                    $newData['has_credentials']           = 1;
                    $newData['expiration_timestamp']      = $expirationTimeStamp;
                    $newData['show_client_secret_notice'] = 0;
                }

            } catch (Exception $e) {
                Notices::addError(sprintf(__('An error occurred when storing of the expiration timestamp : %1$s', 'nextend-facebook-connect'), $e->getMessage()));
            }

            if (empty($newData['client_id']) || ($newData['client_secret'] !== false && empty($newData['client_secret']))) {
                Notices::addError(sprintf(__('Token generation failed: %1$s', 'nextend-facebook-connect'), __('Please check your credentials!', 'nextend-facebook-connect')));
            }
        }

        return $newData;
    }

    /**
     * @return NextendSocialAuth|NextendSocialProviderAppleClient
     */
    public function getClient() {
        if ($this->client === null) {

            require_once dirname(__FILE__) . '/apple-client.php';

            $this->client = new NextendSocialProviderAppleClient($this->id);

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
        /**
         * ID token contains the user id ( sub ) and email
         */
        $appleIdToken = $this->getClient()
                             ->getAppleIdToken();
        $token_parts  = array();
        if ($appleIdToken) {
            /**
             * $token_parts[0] -> Header
             * $token_parts[1] -> Payload
             * $token_parts[2] -> Signature
             */
            $token_parts = explode('.', $appleIdToken);
        }

        $result = json_decode(base64_decode($token_parts[1]), true);

        $name = $this->getClient()
                     ->getAppleUserData();
        if ($name) {
            $result = array_merge($result, $name);
        }

        return $result;
    }

    public function getAuthUserData($key) {
        switch ($key) {
            case 'id':
                return $this->authUserData['sub'];
            case 'email':
                return isset($this->authUserData['email']) ? $this->authUserData['email'] : '';
            case 'name':
                return (!empty($this->authUserData['name']['firstName']) && !empty($this->authUserData['name']['lastName'])) ? $this->authUserData['name']['firstName'] . ' ' . $this->authUserData['name']['lastName'] : '';
            case 'first_name':
                return !empty($this->authUserData['name']['firstName']) ? $this->authUserData['name']['firstName'] : '';
            case 'last_name':
                return !empty($this->authUserData['name']['lastName']) ? $this->authUserData['name']['lastName'] : '';
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

    /**
     * @param string $kid private key identifier
     * @param string $key private key
     * @param string $iss team identifier
     * @param string $sub service id
     * @param string $exp expiration timestamp
     *
     * @return bool|string
     */
    public function generateClientSecret($kid, $key, $iss, $sub, $exp) {

        if (!openssl_pkey_get_private($key)) {

            Notices::addError(sprintf(__('Token generation failed: %1$s', 'nextend-facebook-connect'), __('Private key format is not valid!', 'nextend-facebook-connect')));

            return false;
        }

        try {
            $payload = array(
                "iss" => $iss,
                "aud" => "https://appleid.apple.com",
                "exp" => $exp,
                "sub" => $sub
            );

            return NSLPro\JWT\JWT::encode($payload, $key, 'ES256', $kid);
        } catch (Exception $e) {
            Notices::addError(sprintf(__('Token generation failed: %1$s', 'nextend-facebook-connect'), $e->getMessage()));
        }

        return false;
    }

    public function show_admin_apple_client_secret_notice() {
        if (current_user_can(NextendSocialLogin::getRequiredCapability())) {
            if (!empty($this->settings->get('client_id')) && !empty($this->settings->get('client_secret'))) {
                if ($this->settings->get('expiration_timestamp') === 0 || $this->settings->get('show_client_secret_notice')) {
                    echo '<div class="error">
                        <p>' . sprintf(__('%s detected that your Apple credentials have expired. Please delete the current credentials and generate new one!', 'nextend-facebook-connect'), '<b>Nextend Social Login</b>') . '</p>
                        <p class="submit"><a href="' . $this->getAdmin()
                                                            ->getUrl('settings') . '" class="button button-primary">' . __('Fix Error', 'nextend-facebook-connect') . ' - ' . __('Apple Credentials', 'nextend-facebook-connect') . '</a></p>
                    </div>';
                }
            }
        }
    }

    /**
     * Apple client secret validation for weekly cron.
     */
    public function weekly_apple_client_secret_check() {

        if (!empty($this->settings->get('client_id')) && !empty($this->settings->get('client_secret'))) {
            try {
                $time                = new DateTime("now", new DateTimeZone("UTC"));
                $currentTimeStamp    = $time->getTimestamp();
                $expirationTimeStamp = $this->settings->get('expiration_timestamp');

                if ($expirationTimeStamp > 0 && $expirationTimeStamp - MONTH_IN_SECONDS < $currentTimeStamp) {
                    $newExpirationTimeStamp = $currentTimeStamp + MONTH_IN_SECONDS * 6;
                    $newSecret              = $this->generateClientSecret($this->settings->get('private_key_id'), $this->settings->get('private_key'), $this->settings->get('team_identifier'), $this->settings->get('service_identifier'), $newExpirationTimeStamp);
                    if ($newSecret) {
                        $this->settings->set('expiration_timestamp', $newExpirationTimeStamp);
                        $this->settings->set('client_secret', $newSecret);
                        $this->settings->set('show_client_secret_notice', 0);
                    } else {
                        //display admin notice if we couldn't generate a new secret
                        $this->settings->set('show_client_secret_notice', 1);
                    }
                }
            } catch (Exception $e) {
                Notices::addError(sprintf(__('Token generation failed: %1$s', 'nextend-facebook-connect'), $e->getMessage()));
            }
        }
    }

}

NextendSocialLogin::addProvider(new NextendSocialPROProviderApple());