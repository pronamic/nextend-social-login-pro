<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$provider = $this->getProvider();
?>
<ol>
    <li><?php printf(__('Navigate to %s', 'nextend-facebook-connect'), '<a href="https://developers.kakao.com/console/app" target="_blank">https://developers.kakao.com/console/app</a>'); ?></li>
    <li><?php printf(__('Log in with your %s credentials if you are not logged in.', 'nextend-facebook-connect'), 'Kakao'); ?></li>
    <li><?php printf(__('Click on the name of your %s App.', 'nextend-facebook-connect'), 'Kakao'); ?></li>
    <li><?php printf(__('Select the %1$s tab on the left and scroll down to the %2$s section!', 'nextend-facebook-connect'), '"<b>Kakao Login</b>"', '"<b>Redirect URI</b>"'); ?></li>
    <li><?php printf(__('Click on %s.', 'nextend-facebook-connect'), '"<b>Modify</b>"'); ?></li>
    <li><?php
        $loginUrls = $provider->getAllRedirectUrisForAppCreation();
        _e('Add the following URL:', 'nextend-facebook-connect');
        echo "<ul>";
        foreach ($loginUrls as $loginUrl) {
            echo "<li><strong>" . $loginUrl . "</strong></li>";
        }
        echo "</ul>";
        ?>
    </li>
    <li><?php printf(__('Click on the %s button.', 'nextend-facebook-connect'), '"<b>Save</b>"'); ?></li>
</ol>