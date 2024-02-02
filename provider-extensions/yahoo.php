<?php

class NextendSocialLoginPROProviderExtensionYahoo extends NextendSocialLoginPROProviderExtensionWithSyncData {

    /** @var NextendSocialPROProviderYahoo */
    protected $provider;

    public function providerEnabled() {

        parent::providerEnabled();

        add_filter('nsl_' . $this->provider->getId() . '_sync_field_phones', array(
            $this,
            'sync_field_phones'
        ), 10, 2);
    }

    public function sync_field_phones($value, $original_value) {
        if (isset($original_value) && !empty($original_value)) {
            return maybe_serialize($original_value);
        }

        return false;
    }

    protected function getRemoteData($node = 'me') {
        if ($node === 'me') {
            return $this->provider->getMe();
        }

        return array();
    }
}