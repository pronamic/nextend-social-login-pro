<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$provider = $this->getProvider();
?>
<ol>
    <li><?php printf(__('Navigate to <b>%s</b>', 'nextend-facebook-connect'), '<a href="https://developer.yahoo.com/apps/" target="_blank">https://developer.yahoo.com/apps/</a>'); ?></li>
    <li><?php printf(__('Log in with your %s credentials if you are not logged in', 'nextend-facebook-connect'), 'Yahoo'); ?></li>
    <li><?php _e('Click on the App which has its credentials associated with the plugin.', 'nextend-facebook-connect'); ?></li>
    <li><?php
        $loginUrls = $provider->getAllRedirectUrisForAppCreation();
        printf(__('Add the following URL to the %s field:', 'nextend-facebook-connect'), '"<b>Redirect URI(s)</b>"');
        echo "<ul>";
        foreach ($loginUrls as $loginUrl) {
            echo "<li><strong>" . $loginUrl . "</strong></li>";
        }
        echo "</ul>";
        ?>
    </li>
    <li><?php _e('Click on "<b>Update</b>" to save the changes', 'nextend-facebook-connect'); ?></li>
</ol>