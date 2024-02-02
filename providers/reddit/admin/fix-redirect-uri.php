<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$provider = $this->getProvider();
?>
<ol>
    <li><?php printf(__('Navigate to %s', 'nextend-facebook-connect'), '<a href="https://www.reddit.com/prefs/apps/" target="_blank">https://www.reddit.com/prefs/apps/</a>'); ?></li>
    <li><?php printf(__('Log in with your %s credentials if you are not logged in.', 'nextend-facebook-connect'), 'Reddit'); ?></li>
    <li><?php printf(__('Click on %1$s in the bottom left corner of your %2$s App.', 'nextend-facebook-connect'), '"<b>edit</b>"', 'Reddit'); ?></li>
    <li><?php
        $loginUrls = $provider->getAllRedirectUrisForAppCreation();
        printf(__('Replace your existing %s with this URL:', 'nextend-facebook-connect'), '"<b>redirect uri</b>"');
        echo "<ul>";
        foreach ($loginUrls as $loginUrl) {
            echo "<li><strong>" . $loginUrl . "</strong></li>";
        }
        echo "</ul>";
        ?>
    </li>
    <li><?php printf(__('Click on the %s button', 'nextend-facebook-connect'), '"<b>update app</b>"'); ?></li>
</ol>