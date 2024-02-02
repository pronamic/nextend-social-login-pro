<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$provider = $this->getProvider();
?>
<ol>
    <li><?php printf(__('Navigate to %s', 'nextend-facebook-connect'), '<a href="https://www.amazon.com/" target="_blank">https://www.amazon.com/</a>'); ?></li>
    <li><?php printf(__('Log in with your %s credentials if you are not logged in', 'nextend-facebook-connect'), 'Amazon'); ?></li>
    <li><?php printf(__('Visit %s', 'nextend-facebook-connect'), '<a href="https://developer.amazon.com/lwa/sp/overview.html" target="_blank">https://developer.amazon.com/lwa/sp/overview.html</a>'); ?></li>
    <li><?php _e('On the right side, under "<b>Manage</b>", hover over the gear icon and select "<b>Web Settings</b>" option.', 'nextend-facebook-connect') ?></li>
    <li><?php _e('Click "<b>Edit</b>".', 'nextend-facebook-connect') ?></li>
    <li><?php
        $loginUrls = $provider->getAllRedirectUrisForAppCreation();
        printf(__('Add the following URL to the %s field: ', 'nextend-facebook-connect'), '"<b>Allowed Return URLs</b>"');
        echo "<ul>";
        foreach ($loginUrls as $loginUrl) {
            echo "<li><strong>" . $loginUrl . "</strong></li>";
        }
        echo "</ul>";
        ?>
    </li>
    <li><?php _e('Click on "<b>Save</b>"', 'nextend-facebook-connect'); ?></li>
</ol>