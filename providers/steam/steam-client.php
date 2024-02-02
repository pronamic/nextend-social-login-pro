<?php

require_once NSL_PRO_PATH . '/includes/openid.php';

class NextendSocialProviderSteamClient extends NextendSocialOpenID {

    protected $endpointOpenIdIdentity = 'https://steamcommunity.com/openid';

    protected $endpointRestAPI = 'https://api.steampowered.com';

}