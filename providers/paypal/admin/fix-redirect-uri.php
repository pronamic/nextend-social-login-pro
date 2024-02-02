<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$provider = $this->getProvider();
?>
<ol>
    <li><?php printf(__('Navigate to %s', 'nextend-facebook-connect'), '<a href="https://developer.paypal.com/dashboard/applications/live" target="_blank">https://developer.paypal.com/dashboard/applications/live</a>'); ?></li>
    <li><?php printf(__('Log in with your %s credentials if you are not logged in.', 'nextend-facebook-connect'), 'PayPal'); ?></li>
    <li><?php _e('There is a Sandbox/Live switch. Make sure "<b>Live</b>" is selected!', 'nextend-facebook-connect') ?></li>
    <li><?php printf(__('Click on the name of your %s App, under the REST API apps section.', 'nextend-facebook-connect'), 'PayPal'); ?></li>
    <li><?php printf(__('Scroll down to %1$s section, find the %2$s feature and and click on the %3$s link.', 'nextend-facebook-connect'), '"<b>Features</b>"', '"<b>Log in with PayPal</b>"', '"<b>Advanced Settings</b>"'); ?></li>
    <li><?php
        $loginUrls = $provider->getAllRedirectUrisForAppCreation();
        printf(__('Add the following URL to the %s field and press %2$s:', 'nextend-facebook-connect'), '"<b>Return URL</b>"', '"<b>Save</b>"');
        echo "<ul>";
        foreach ($loginUrls as $loginUrl) {
            echo "<li><strong>" . $loginUrl . "</strong></li>";
        }
        echo "</ul>";
        ?>
    </li>
    <li><?php printf(__('Wait for %1$s to approve the %2$s feature ( this might take up to 7 business days ). The %3$s row will indicate the current status. ', 'nextend-facebook-connect'), 'PayPal', '"<b>Log in with PayPal</b>"', '"<b>Approval Status</b>"'); ?></li>
</ol>