<?php

class NextendSocialLoginPROProviderExtensionGitHub extends NextendSocialLoginPROProviderExtensionWithSyncData {

    /** @var NextendSocialPROProviderGitHub */
    protected $provider;

    protected function getRemoteData($node = 'me') {
        switch ($node) {
            case 'me':
                return $this->provider->getMe();
        }

        return array();
    }
}