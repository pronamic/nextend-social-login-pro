<?php

class NextendSocialLoginPROProviderExtensionTwitter extends NextendSocialLoginPROProviderExtensionWithSyncData {

    /** @var NextendSocialProviderTwitter */
    protected $provider;

    protected function synchronizeNodeFields($user_id, $fields, $data) {

        if (!$this->provider->isV2Api()) {
            /**
             * Available only for the V1.1 Api.
             */
            if (isset($data['screen_name'])) {
                $data['profile_url'] = 'https://twitter.com/' . $data['screen_name'];
            }
        }

        parent::synchronizeNodeFields($user_id, $fields, $data);
    }

    public function providerEnabled() {

        parent::providerEnabled();

        if ($this->provider->isV2Api()) {
            /**
             * Sync fields available only for the V2 Api.
             */

            add_filter('nsl_' . $this->provider->getId() . '_sync_field_verified', array(
                $this,
                'sync_bool_field'
            ), 10, 2);

            add_filter('nsl_' . $this->provider->getId() . '_sync_field_public_metrics', array(
                $this,
                'sync_structured_field'
            ), 10, 2);


            add_filter('nsl_' . $this->provider->getId() . '_sync_field_protected', array(
                $this,
                'sync_bool_field'
            ), 10, 2);


            add_filter('nsl_' . $this->provider->getId() . '_sync_field_public_entities', array(
                $this,
                'sync_structured_field'
            ), 10, 2);
        }
    }

    public function sync_structured_field($value, $original_value) {
        if (isset($original_value) && !empty($original_value)) {
            return maybe_serialize($original_value);
        }

        return false;
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
        if ($node === 'me' || $node === 'mev2') {
            return $this->provider->getMe();
        }

        return array();
    }
}