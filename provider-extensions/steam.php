<?php

class NextendSocialLoginPROProviderExtensionSteam extends NextendSocialLoginPROProviderExtensionWithSyncData {

    /** @var NextendSocialPROProviderSteam */
    protected $provider;

    public function providerEnabled() {

        parent::providerEnabled();

        add_filter('nsl_' . $this->provider->getId() . '_sync_field_communityvisibilitystate', array(
            $this,
            'sync_numeric_as_string'
        ), 10, 2);

        add_filter('nsl_' . $this->provider->getId() . '_sync_field_profilestate', array(
            $this,
            'sync_numeric_as_string'
        ), 10, 2);

        add_filter('nsl_' . $this->provider->getId() . '_sync_field_personastate', array(
            $this,
            'sync_numeric_as_string'
        ), 10, 2);
    }

    public function sync_numeric_as_string($value, $original_value) {
        if (($original_value && is_numeric($original_value)) || $original_value === 0) {
            return strval($original_value);
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