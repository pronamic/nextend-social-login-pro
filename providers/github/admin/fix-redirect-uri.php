<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$provider = $this->getProvider();
?>
<ol>
    <li><?php printf(__('Navigate to %s', 'nextend-facebook-connect'), '<a href="https://github.com/settings/developers/" target="_blank">https://github.com/settings/developers/</a>'); ?></li>
    <li><?php printf(__('Log in with your %s credentials if you are not logged in', 'nextend-facebook-connect'), 'GitHub'); ?></li>
    <li><?php printf(__('Click on the name of the App you configured %s with.', 'nextend-facebook-connect'), 'Nextend Social Login'); ?></li>
    <li><?php printf(__('Make sure the <b>Homepage URL</b> matches with: <b>%s</b>', 'nextend-facebook-connect'), str_replace(parse_url(site_url(), PHP_URL_PATH), "", site_url())); ?></li>
    <li><?php
        $loginUrls = $provider->getAllRedirectUrisForAppCreation();
        printf(__('Add the following URL to the %s field:', 'nextend-facebook-connect'), '<b>Authorization callback URL</b>');
        echo "<ul>";
        foreach ($loginUrls as $loginUrl) {
            echo "<li><strong>" . $loginUrl . "</strong></li>";
        }
        echo "</ul>";
        ?>
    </li>
    <li><?php _e('Press the <b>Update application</b> button to save the changes.', 'nextend-facebook-connect'); ?></li>
</ol>