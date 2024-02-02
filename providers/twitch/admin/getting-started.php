<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$lastUpdated = '2022-11-04';

$provider = $this->getProvider();
?>
<div class="nsl-admin-sub-content">
    <div class="nsl-admin-getting-started">
        <h2 class="title"><?php _e('Getting Started', 'nextend-facebook-connect'); ?></h2>

        <p><?php printf(__('To allow your visitors to log in with their %1$s account, first you must create an %1$s App. The following guide will help you through the %1$s App creation process. After you have created your %1$s App, head over to "Settings" and configure the given "%2$s" and "%3$s" according to your %1$s App.', 'nextend-facebook-connect'), "Twitch", "Client ID", "Client Secret"); ?></p>

        <p><?php do_action('nsl_getting_started_warnings', $provider, $lastUpdated); ?></p>

        <h2 class="title"><?php printf(_x('Create %s', 'App creation', 'nextend-facebook-connect'), 'Twitch App'); ?></h2>

        <ol>
            <li><?php printf(__('Open the %1$s and login with your %2$s account.', 'nextend-facebook-connect'), '<a href="https://dev.twitch.tv/console" target="_blank">Twitch Developer Console</a>', 'Twitch'); ?></li>
            <li><?php printf(__('To be able to create a %s your account both needs:', 'nextend-facebook-connect'), 'Twitch App'); ?>
                <ol>
                    <li><?php printf(__('to be %s', 'nextend-facebook-connect'), '<strong>verified</strong>'); ?></li>
                    <li><?php printf(__('and to have the %1$s enabled. If it is not enabled already, you can enable it at your %2$s page on the %3$s tab', 'nextend-facebook-connect'), '<strong>Two Factor Authentication</strong>', 'Settings', '<a href="https://www.twitch.tv/settings/security" target="_blank">Security and Privacy</a>'); ?></li>
                </ol>
            </li>
            <li><?php printf(__('Navigate to the %1$s tab.', 'nextend-facebook-connect'), '<a href="https://dev.twitch.tv/console/apps" target="_blank">Applications</a>'); ?></li>
            <li><?php printf(__('Click on the %s button on the right side.', 'nextend-facebook-connect'), '"<strong>+ Register Your Application</strong>"'); ?></li>
            <li><?php printf(__('Enter a %s for your App.', 'nextend-facebook-connect'), '"<strong>Name</strong>"'); ?></li>
            <li><?php
                $loginUrls = $provider->getAllRedirectUrisForAppCreation();
                printf(__('Add the following URL to the %s field:', 'nextend-facebook-connect'), '"<strong>OAuth Redirect URLs</strong>"');
                echo "<ul>";
                foreach ($loginUrls as $loginUrl) {
                    echo "<li><strong>" . $loginUrl . "</strong></li>";
                }
                echo "</ul>";
                ?>
            </li>
            <li><?php printf(__('Select the %1$s option in the %2$s list.', 'nextend-facebook-connect'), '"<strong>Website Integration</strong>"', '"<strong>Category</strong>"'); ?></li>
            <li><?php _e('Complete the human verification test.', 'nextend-facebook-connect') ?></li>
            <li><?php printf(__('Press the %s button.', 'nextend-facebook-connect'), '"<strong>Create</strong>"'); ?></li>
            <li><?php printf(__('On the %1$s tab, find your app and click %2$s.', 'nextend-facebook-connect'), '"<strong>Applications</strong>"', '"<strong>Manage</strong>"'); ?></li>
            <li><?php printf(__('To generate a %1$s, click on the %2$s button.', 'nextend-facebook-connect'), '"<strong>Client Secret</strong>"', '"<strong>New Secret</strong>"'); ?></li>
            <li><?php printf(__('Copy the %1$s and the %2$s values. These will be needed in the plugin\'s settings.', 'nextend-facebook-connect'), '"<strong>Client ID</strong>"', '"<strong>Client Secret</strong>"'); ?></li>
        </ol>

        <a href="<?php echo $this->getUrl('settings'); ?>"
           class="button button-primary"><?php printf(__('I am done setting up my %s', 'nextend-facebook-connect'), 'Twitch App'); ?></a>
    </div>
</div>