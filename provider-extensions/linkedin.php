<?php

class NextendSocialLoginPROProviderExtensionLinkedIn extends NextendSocialLoginPROProviderExtensionWithSyncData {

    /** @var NextendSocialPROProviderLinkedIn */
    protected $provider;

    protected function getRemoteData($node = 'me') {
        if ($node === 'me') {
            return $this->provider->getMe();
        }

        return array();
    }
}