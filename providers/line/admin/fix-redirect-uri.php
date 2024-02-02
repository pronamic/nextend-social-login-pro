<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$provider = $this->getProvider();
?>
<ol>
    <li><?php printf(__('Navigate to your %s', 'nextend-facebook-connect'), '<a href="https://developers.line.biz/console/" target="_blank">LINE Business Account</a>'); ?></li>
    <li><?php printf(__('Log in with your %s credentials if you are not logged in.', 'nextend-facebook-connect'), 'Line business'); ?></li>
    <li><?php printf(__('Select your %s App and click on the LINE Login section.', 'nextend-facebook-connect'), 'Line'); ?></li>
    <li><?php
        $loginUrls = $provider->getAllRedirectUrisForAppCreation();
        printf(__('Change your URL in the %s field:', 'nextend-facebook-connect'), '"<b>Callback URL</b>"');
        echo "<ul>";
        foreach ($loginUrls as $loginUrl) {
            echo "<li><strong>" . $loginUrl . "</strong></li>";
        }
        echo "</ul>";
        ?>
    </li>
</ol>