<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$provider = $this->getProvider();
?>
<ol>
    <li><?php printf(__('Log in with your %s credentials if you are not logged in.', 'nextend-facebook-connect'), 'Slack'); ?></li>
    <li><?php printf(__('Navigate to %s', 'nextend-facebook-connect'), '<a href="https://api.slack.com/apps" target="_blank">https://api.slack.com/apps</a>'); ?></li>
    <li><?php printf(__('Select your %s App.', 'nextend-facebook-connect'), 'Slack'); ?></li>
    <li><?php _e('Under the "<b>Add features and functionality</b>" panel click on the "<b>Permissions</b>" option.', 'nextend-facebook-connect') ?></li>
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
    <li><?php _e('Click on the "<b>Add</b>" and "<b>Save URLs</b>" button.', 'nextend-facebook-connect') ?></li>
</ol>