<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$provider = $this->getProvider();
?>
<ol>
    <li><?php printf(__('Navigate to %s', 'nextend-facebook-connect'), '<a href="https://dev.twitch.tv/console/apps" target="_blank">https://dev.twitch.tv/console/apps</a>'); ?></li>
    <li><?php printf(__('Log in with your %s credentials if you are not logged in.', 'nextend-facebook-connect'), 'Twitch'); ?></li>
    <li><?php printf(__('On the %1$s tab, find your app and click %2$s.', 'nextend-facebook-connect'), '"<strong>Applications</strong>"', '"<strong>Manage</strong>"'); ?></li>
    <li><?php
        $loginUrls = $provider->getAllRedirectUrisForAppCreation();
        printf(__('Add the following URL to the %s field:', 'nextend-facebook-connect'), '"<strong>OAuth Redirect URLs</strong>"');
        echo "<ul>";
        foreach ($loginUrls as $loginUrl) {
            echo "<li><strong>" . $loginUrl . "</strong></li>";
        }
        echo "</ul>";
        ?>
    </li>
    <li><?php _e('Complete the human verification test.', 'nextend-facebook-connect') ?></li>
    <li><?php printf(__('Click on %s', 'nextend-facebook-connect'), '"<strong>Save</strong>"'); ?></li>
</ol>