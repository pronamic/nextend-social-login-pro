<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$provider = $this->getProvider();
?>
<ol>
    <li><?php printf(__('Navigate to %s', 'nextend-facebook-connect'), '<a href="https://discord.com/developers/applications" target="_blank">https://discord.com/developers/applications</a>'); ?></li>
    <li><?php printf(__('If you are not logged in yet, then log in with your %s credentials and visit the link above again.', 'nextend-facebook-connect'), 'Discord'); ?></li>
    <li><?php printf(__('Select your %s App and click on the “<b>%2$s</b>” menu point under the Settings section on the left side.', 'nextend-facebook-connect'), 'Discord', 'OAuth2'); ?></li>
    <li><?php
        $loginUrls = $provider->getAllRedirectUrisForAppCreation();
        printf(__('Add the following URL in the %s field:', 'nextend-facebook-connect'), '"<b>Redirects</b>"');
        echo "<ul>";
        foreach ($loginUrls as $loginUrl) {
            echo "<li><strong>" . $loginUrl . "</strong></li>";
        }
        echo "</ul>";
        ?>
    </li>
</ol>