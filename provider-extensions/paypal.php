<?php

class NextendSocialLoginPROProviderExtensionPaypal extends NextendSocialLoginPROProviderExtensionWithSyncData {

    /** @var NextendSocialPROProviderPaypal */
    protected $provider;

    public function providerEnabled() {

        parent::providerEnabled();

        add_filter('nsl_' . $this->provider->getId() . '_sync_field_address', array(
            $this,
            'sync_field_address'
        ), 10, 2);
    }

    public function sync_field_address($value, $original_value) {
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