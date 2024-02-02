<?php

class NextendSocialLoginPROProviderExtensionFacebook extends NextendSocialLoginPROProviderExtensionWithSyncData {

    /** @var NextendSocialProviderFacebook */
    protected $provider;

    public function providerEnabled() {

        parent::providerEnabled();

        add_filter('nsl_' . $this->provider->getId() . '_sync_field_age_range', array(
            $this,
            'sync_field_age_range'
        ), 10, 2);

        add_filter('nsl_' . $this->provider->getId() . '_sync_field_hometown', array(
            $this,
            'sync_field_hometown'
        ), 10, 2);

        add_filter('nsl_' . $this->provider->getId() . '_sync_field_location', array(
            $this,
            'sync_field_location'
        ), 10, 2);

        add_filter('nsl_' . $this->provider->getId() . '_sync_field_currency', array(
            $this,
            'sync_field_currency'
        ), 10, 2);

        //Sync data warning
        add_filter('nsl_' . $this->provider->getId() . '_sync_warning', array(
            $this,
            'facebook_sync_warning'
        ), 10);
    }

    public function sync_field_age_range($value, $original_value) {
        $range = array(
            isset($original_value['min']) ? $original_value['min'] : '',
            isset($original_value['max']) ? $original_value['max'] : ''
        );


        return implode('-', $range);
    }

    public function sync_field_hometown($value, $original_value) {
        if (isset($original_value['name'])) {
            return $original_value['name'];
        }

        return false;
    }

    public function sync_field_location($value, $original_value) {
        if (isset($original_value['name'])) {
            return $original_value['name'];
        }

        return false;
    }

    public function sync_field_currency($value, $original_value) {
        if (isset($original_value['user_currency'])) {
            return $original_value['user_currency'];
        }

        return false;
    }

    public function facebook_sync_warning() {
        $sync_warning_message = sprintf(__('The Facebook Sync data needs an approved %1$s and your App must use the latest %2$s version!', 'nextend-facebook-connect'), '<a href="https://developers.facebook.com/docs/apps/review/" target="_blank">Facebook App Review</a>', '<a href="https://nextendweb.com/nextend-social-login-docs/facebook-upgrade-api-call/#api-call" target="_blank">API Call</a>');

        return $sync_warning_message;
    }

    protected function getRemoteData($node = 'me') {
        if ($node === 'me') {
            return $this->provider->getMe();
        }

        return array();
    }
}