<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$provider = $this->getProvider();
?>
<ol>
    <li><?php printf(__('Navigate to %s', 'nextend-facebook-connect'), '<a href="https://www.linkedin.com/developer/apps" target="_blank">https://www.linkedin.com/developer/apps</a>'); ?></li>
    <li><?php printf(__('Log in with your %s credentials if you are not logged in', 'nextend-facebook-connect'), 'LinkedIn'); ?></li>
    <li><?php printf(__('Click on your App and go to the %s tab.', 'nextend-facebook-connect'), '"<b>Auth</b>"'); ?></li>
    <li><?php
        $loginUrls = $provider->getAllRedirectUrisForAppCreation();
        printf(__('Add the following URL at %s:', 'nextend-facebook-connect'), '"<b>Authorized redirect URLs for your app</b>"');
        echo "<ul>";
        foreach ($loginUrls as $loginUrl) {
            echo "<li><strong>" . $loginUrl . "</strong></li>";
        }
        echo "</ul>";
        ?>
    </li>
    <li><?php printf(__('Click on %s to save the changes', 'nextend-facebook-connect'), '"<b>Update</b>"'); ?></li>
</ol>