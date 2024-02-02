<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$lastUpdated = '2023-08-10';

$provider = $this->getProvider();
?>
<div class="nsl-admin-sub-content">
    <div class="nsl-admin-getting-started">
        <h2 class="title"><?php _e('Getting Started', 'nextend-facebook-connect'); ?></h2>

        <p><?php printf(__('To allow your visitors to log in with their %1$s account, first you must create a %1$s App. The following guide will help you through the %1$s App creation process. After you have created your %1$s App, head over to "Settings" and configure the given "%2$s" and "%3$s" according to your %1$s App.', 'nextend-facebook-connect'), "LinkedIn", "Client  ID", "Client  secret"); ?></p>

        <p><?php do_action('nsl_getting_started_warnings', $provider, $lastUpdated); ?></p>

        <h2 class="title"><?php printf(_x('Create %s', 'App creation', 'nextend-facebook-connect'), 'LinkedIn App'); ?></h2>

        <ol>
            <li><?php printf(__('Navigate to %s', 'nextend-facebook-connect'), '<a href="https://www.linkedin.com/developer/apps" target="_blank">https://www.linkedin.com/developer/apps</a>'); ?></li>
            <li><?php printf(__('Log in with your %s credentials if you are not logged in', 'nextend-facebook-connect'), 'LinkedIn'); ?></li>
            <li><?php printf(__('Locate the %s button and click on it.', 'nextend-facebook-connect'), '"<b>Create app</b>"'); ?></li>
            <li><?php printf(__('Enter the name of your App to the %s field.', 'nextend-facebook-connect'), '"<b>App name</b>"'); ?></li>
            <li><?php printf(__('Find your page in the %1$s field. If you don\'t have one yet, create new one at: %2$s', 'nextend-facebook-connect'), '"<b>LinkedIn Page</b>"', '<a href="https://www.linkedin.com/company/setup/new/" target="_blank">https://www.linkedin.com/company/setup/new/</a>'); ?></li>
            <li><?php printf(__('Enter your %1$s and upload an %2$s', 'nextend-facebook-connect'), '"<b>Privacy policy URL</b>"', '"<b>App logo</b>"'); ?></li>
            <li><?php printf(__('Read and agree the %1$s then click the %2$s button!', 'nextend-facebook-connect'), '"<b>API Terms of Use</b>"', '"<b>Create App</b>"'); ?></li>
            <li><?php printf(__('You will end up in the products area. If you aren\'t already there click on the %s tab.', 'nextend-facebook-connect'), '"<b>Products</b>"'); ?></li>
            <li><?php printf(__('Find %1$s and click %2$s.', 'nextend-facebook-connect'), '"<b>Sign In with LinkedIn using OpenID Connect</b>"', '"<b>Request access</b>"'); ?></li>
            <li><?php printf(__('A modal will appear where you need to tick the %1$s checkbox and finally press the %2$s button.', 'nextend-facebook-connect'), '"<b>I have read and agree to these terms</b>"', '"<b>Request access</b>"'); ?></li>
            <li><?php printf(__('Click on the %1$s tab.', 'nextend-facebook-connect'), '"<b>Auth</b>"'); ?></li>
            <li><?php
                $loginUrls = $provider->getAllRedirectUrisForAppCreation();
                printf(__('Find the %1$s section and add the following URL at %2$s:', 'nextend-facebook-connect'), '"<b>OAuth 2.0 settings</b>"', '"<b>Authorized redirect URLs for your app</b>"');
                echo "<ul>";
                foreach ($loginUrls as $loginUrl) {
                    echo "<li><strong>" . $loginUrl . "</strong></li>";
                }
                echo "</ul>";
                ?>
            </li>
            <li><?php printf(__('Click on %s to save the changes', 'nextend-facebook-connect'), '"<b>Update</b>"'); ?></li>
            <li><?php printf(__('Find the necessary %1$s and %2$s under the %3$s section, on the %4$s tab.', 'nextend-facebook-connect'), '"<b>Client ID</b>"', '"<b>Client Secret</b>"', '"<b>Authentication keys</b>"', '"<b>Auth</b>"'); ?></li>
        </ol>

        <a href="<?php echo $this->getUrl('settings'); ?>"
           class="button button-primary"><?php printf(__('I am done setting up my %s', 'nextend-facebook-connect'), 'LinkedIn App'); ?></a>
    </div>
</div>