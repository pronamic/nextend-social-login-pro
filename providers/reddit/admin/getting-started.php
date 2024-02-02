<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$lastUpdated = '2023-09-22';

$provider = $this->getProvider();
?>
<div class="nsl-admin-sub-content">
    <div class="nsl-admin-getting-started">
        <h2 class="title"><?php _e('Getting Started', 'nextend-facebook-connect'); ?></h2>

        <p><?php printf(__('To allow your visitors to log in with their %1$s account, first you must create an %1$s App. The following guide will help you through the %1$s App creation process. After you have created your %1$s App, head over to "Settings" and configure the given "%2$s" and "%3$s" according to your %1$s App.', 'nextend-facebook-connect'), "Reddit", "Client ID", "Client Secret"); ?></p>

        <p><?php do_action('nsl_getting_started_warnings', $provider, $lastUpdated); ?></p>

        <h2 class="title"><?php printf(_x('Create %s', 'App creation', 'nextend-facebook-connect'), 'Reddit App'); ?></h2>

        <ol>
            <li><?php printf(__('Navigate to %s', 'nextend-facebook-connect'), '<a href="https://www.reddit.com/prefs/apps/" target="_blank">https://www.reddit.com/prefs/apps/</a>'); ?></li>
            <li><?php printf(__('Log in with your %s credentials if you are not logged in.', 'nextend-facebook-connect'), 'Reddit'); ?></li>
            <li><?php printf(__('Click on the %s button.', 'nextend-facebook-connect'), '"<b>are you a developer? create an app...</b>"'); ?></li>
            <li><?php printf(__('Make sure you have the %s radio option selected!', 'nextend-facebook-connect'), '"<b>web app</b>"'); ?></li>
            <li><?php printf(__('Enter a %s for your App.', 'nextend-facebook-connect'), '<b>name</b>'); ?></li>
            <li><?php printf(__('Optional: Enter a %1$s and set an %2$s for your App.', 'nextend-facebook-connect'), '"<b>description</b>"', '"<b>about url</b>"'); ?></li>
            <li><?php
                $loginUrls = $provider->getAllRedirectUrisForAppCreation();
                printf(__('Add the following URL to the %s field:', 'nextend-facebook-connect'), '"<b>redirect uri</b>"');
                echo "<ul>";
                foreach ($loginUrls as $loginUrl) {
                    echo "<li><strong>" . $loginUrl . "</strong></li>";
                }
                echo "</ul>";
                ?>
            </li>
            <li><?php printf(__('Complete the Human test and click the %s button.', 'nextend-facebook-connect'), '"<b>create app</b>"'); ?></li>
            <li><?php printf(__('Finally find your %1$s, which is the value just below the name and type of your app, and the %2$s, which is the value next to %3$s. These will be needed in the plugin\'s settings.', 'nextend-facebook-connect'), '"<b>Client ID</b>"', '"<b>Client Secret</b>"', '"<b>secret</b>"'); ?></li>
        </ol>

        <a href="<?php echo $this->getUrl('settings'); ?>"
           class="button button-primary"><?php printf(__('I am done setting up my %s', 'nextend-facebook-connect'), 'Reddit App'); ?></a>
    </div>
</div>