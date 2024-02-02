<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$provider = $this->getProvider();
?>
<ol>
    <li><?php printf(__('Navigate to %s', 'nextend-facebook-connect'), '<a href="https://portal.azure.com/" target="_blank">https://portal.azure.com/</a>'); ?></li>
    <li><?php printf(__('Log in with your %s credentials if you are not logged in yet.', 'nextend-facebook-connect'), 'Microsoft Azure'); ?></li>
    <li><?php _e('Click on the Search bar and search for "<b>App registrations</b>".', 'nextend-facebook-connect') ?></li>
    <li><?php printf(__('Click on the App with Application (client) ID: <b>%s</b>', 'nextend-facebook-connect'), $provider->settings->get('client_id')); ?></li>
    <li><?php _e('Click on the link next to the Redirect URIs label.', 'nextend-facebook-connect') ?></li>
    <li><?php
        $loginUrls = $provider->getAllRedirectUrisForAppCreation();
        printf(__('Add the following URL to the %s field:', 'nextend-facebook-connect'), '"<b>Redirect URIs</b>"');
        echo "<ul>";
        foreach ($loginUrls as $loginUrl) {
            echo "<li><strong>" . $loginUrl . "</strong></li>";
        }
        echo "</ul>";
        ?>
    </li>
    <li><?php _e('Click on "<b>Save</b>"', 'nextend-facebook-connect'); ?></li>
</ol>