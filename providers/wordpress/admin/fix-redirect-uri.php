<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$provider = $this->getProvider();
?>
<ol>
    <li><?php printf(__('Navigate to %s', 'nextend-facebook-connect'), '<a href="https://developer.wordpress.com/apps/" target="_blank">https://developer.wordpress.com/apps/</a>'); ?></li>
    <li><?php printf(__('Log in with your %s credentials if you are not logged in.', 'nextend-facebook-connect'), 'WordPress.com'); ?></li>
    <li><?php printf(__('Click on the name of your %s App.', 'nextend-facebook-connect'), 'WordPress.com'); ?></li>
    <li><?php _e('Click "<b>Manage Settings</b>" under the Tools section!', 'nextend-facebook-connect') ?></li>
    <li><?php
        $loginUrls = $provider->getAllRedirectUrisForAppCreation();
        printf(__('Add the following URL to the %s field:', 'nextend-facebook-connect'), '"<b>Redirect URLs</b>"');
        echo "<ul>";
        foreach ($loginUrls as $loginUrl) {
            echo "<li><strong>" . $loginUrl . "</strong></li>";
        }
        echo "</ul>";
        ?>
    </li>
    <li><?php _e('Click on "<b>Update</b>" to save the changes', 'nextend-facebook-connect'); ?></li>
</ol>