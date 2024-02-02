<?php
require_once NSL_PATH . '/includes/oauth2.php';

class NextendSocialProviderSlackClient extends NextendSocialOauth2 {

    protected $access_token_data = array(
        'access_token' => '',
        'expires_in'   => -1,
        'created'      => -1
    );

    private $teamID = '';

    protected $endpointAuthorization = 'https://slack.com/openid/connect/authorize';

    protected $endpointAccessToken = 'https://slack.com/api/openid.connect.token';

    protected $endpointRestAPI = 'https://slack.com/api';

    protected $defaultRestParams = array(
        'format' => 'json',
    );

    protected $scopes = array(
        'openid',
        'profile',
        'email'
    );

    public function createAuthUrl() {
        $args = array();

        if ($this->teamID != '') {
            $args['team'] = urlencode($this->teamID);
        }

        return add_query_arg($args, parent::createAuthUrl());
    }

    /**
     * @param string $teamID
     */
    public function setTeamID($teamID) {
        $this->teamID = $teamID;
    }
}

