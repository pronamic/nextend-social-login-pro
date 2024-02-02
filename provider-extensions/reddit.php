<?php

class NextendSocialLoginPROProviderExtensionReddit extends NextendSocialLoginPROProviderExtensionWithSyncData {

    /** @var NextendSocialPROProviderReddit */
    protected $provider;

    protected function getRemoteData($node = 'me') {
        switch ($node) {
            case 'me':
                return $this->provider->getMe();
        }

        return array();
    }
}