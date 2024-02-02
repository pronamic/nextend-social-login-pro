<?php

class NextendSocialLoginPROProviderExtensionWordpress extends NextendSocialLoginPROProviderExtensionWithSyncData {

    /** @var NextendSocialPROProviderWordpress */
    protected $provider;

    public function providerEnabled() {

        parent::providerEnabled();

        add_filter('nsl_' . $this->provider->getId() . '_sync_field_email_verified', array(
            $this,
            'sync_bool_field'
        ), 10, 2);

        add_filter('nsl_' . $this->provider->getId() . '_sync_field_phone_account', array(
            $this,
            'sync_bool_field'
        ), 10, 2);
    }

    public function sync_bool_field($value, $original_value) {
        if (isset($original_value) && !empty($original_value)) {
            return 'Yes';
        } else if ($original_value === false) {
            return 'No';
        }

        return false;
    }

    protected function getRemoteData($node = 'me') {
        switch ($node) {
            case 'me':
                return $this->provider->getMe();
        }

        return array();
    }
}