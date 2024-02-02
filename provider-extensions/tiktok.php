<?php

class NextendSocialLoginPROProviderExtensionTikTok extends NextendSocialLoginPROProviderExtensionWithSyncData {

    /** @var NextendSocialPROProviderTiktok */
    protected $provider;

    public function providerEnabled() {

        parent::providerEnabled();

        add_filter('nsl_' . $this->provider->getId() . '_sync_field_is_verified', array(
            $this,
            'sync_bool_field'
        ), 10, 2);

        //Sync data warning
        add_filter('nsl_' . $this->provider->getId() . '_sync_warning', array(
            $this,
            'tiktok_sync_warning'
        ), 10);
    }

    public function sync_bool_field($value, $original_value) {
        if (isset($original_value) && !empty($original_value)) {
            return 'Yes';
        } else if ($original_value === false) {
            return 'No';
        }

        return false;
    }


    public function tiktok_sync_warning() {
        $sync_warning_message = sprintf(__('The TikTok Sync data requires the %1$s with the necessary scopes enabled!', 'nextend-facebook-connect'), '<a href="https://developers.tiktok.com/doc/scopes-overview/" target="_blank">TikTok API</a>');

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