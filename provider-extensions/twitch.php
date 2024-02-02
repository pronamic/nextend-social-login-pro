<?php

class NextendSocialLoginPROProviderExtensionTwitch extends NextendSocialLoginPROProviderExtensionWithSyncData {

    /** @var NextendSocialPROProviderTwitch */
    protected $provider;

    protected function getRemoteData($node = 'me') {
        switch ($node) {
            case 'me':
                return $this->provider->getMe();
        }

        return array();
    }
}