<?php

class NextendSocialLoginPROProviderExtensionKakao extends NextendSocialLoginPROProviderExtensionWithSyncData {

    /** @var NextendSocialPROProviderKakao */
    protected $provider;

    public function providerEnabled() {

        parent::providerEnabled();

        add_filter('nsl_' . $this->provider->getId() . '_sync_field_birthday', array(
            $this,
            'sync_field_birthday'
        ), 10, 2);

        add_filter('nsl_' . $this->provider->getId() . '_sync_field_birthday_type', array(
            $this,
            'sync_field_birthdayType'
        ), 10, 2);

        add_filter('nsl_' . $this->provider->getId() . '_sync_warning', array(
            $this,
            'kakao_sync_warning'
        ), 10);
    }

    public function sync_field_birthday($value, $original_value) {

        if (isset($original_value)) {
            $month = substr($original_value, 0, 2);
            $day   = substr($original_value, 2, 2);

            return ($month . '/' . $day);
        }

        return false;
    }

    public function sync_field_birthdayType($value, $original_value) {
        if (isset($original_value)) {
            return ucfirst(strtolower($original_value));
        }

        return false;
    }

    public function kakao_sync_warning() {
        $sync_warning_message = sprintf(__('Most of these information can only be retrieved, when the field is filled on the user\'s %s page!', 'nextend-facebook-connect'), '<a href="https://accounts.kakao.com/weblogin/account/profile" target="_blank">MyInfo</a>');

        return $sync_warning_message;
    }

    protected function getRemoteData($node = 'me') {
        switch ($node) {
            case 'me':
                return $this->provider->getMe();
        }

        return array();
    }
}