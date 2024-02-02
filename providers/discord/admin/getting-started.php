<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$lastUpdated = '2021-09-15';

$provider = $this->getProvider();
?>
<div class="nsl-admin-sub-content">
    <div class="nsl-admin-getting-started">
        <h2 class="title"><?php _e('Getting Started', 'nextend-facebook-connect'); ?></h2>

        <p><?php printf(__('To allow your visitors to log in with their %1$s account, first you must create a %1$s App. The following guide will help you through the %1$s App creation process. After you have created your %1$s App, head over to "Settings" and configure the given "%2$s" and "%3$s" according to your %1$s App.', 'nextend-facebook-connect'), "Discord", "Client ID", "Client Secret"); ?></p>

        <p><?php do_action('nsl_getting_started_warnings', $provider, $lastUpdated); ?></p>

        <h2 class="title"><?php printf(_x('Create %s', 'App creation', 'nextend-facebook-connect'), 'Line App'); ?></h2>

        <ol>
            <li><?php printf(__('Navigate to %s', 'nextend-facebook-connect'), '<a href="https://discord.com/developers/applications" target="_blank">https://discord.com/developers/applications</a>'); ?></li>
            <li><?php printf(__('If you are not logged in yet, then log in with your %s credentials and visit the link above again.', 'nextend-facebook-connect'), 'Discord'); ?></li>
            <li><?php _e('Click the "<b>New Application</b>" button.', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Fill the "<b>Name</b>" field and click the "<b>Create</b>" button.', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Optional: Select an "<b>App Icon</b>" and add a "<b>Terms of Service URL</b>" and "<b>Privacy Policy URL</b>".', 'nextend-facebook-connect') ?></li>
            <li><?php printf(__('On the left side, click on the “<b>%1$s</b>” menu point in the Settings.', 'nextend-facebook-connect'), 'OAuth2') ?></li>
            <li><?php
                $loginUrls = $provider->getAllRedirectUrisForAppCreation();
                printf(__('Add the following URL to the %s field:', 'nextend-facebook-connect'), '"<b>Redirects</b>"');
                echo "<ul>";
                foreach ($loginUrls as $loginUrl) {
                    echo "<li><strong>" . $loginUrl . "</strong></li>";
                }
                echo "</ul>";
                ?>
            </li>
            <li><?php _e('Click the "<b>Save Changes</b>" button.', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Copy the necessary "<b>Client ID</b>" and "<b>Client Secret</b>" values and fill these fields in the plugin settings!', 'nextend-facebook-connect') ?></li>
        </ol>

        <a href="<?php echo $this->getUrl('settings'); ?>"
           class="button button-primary"><?php printf(__('I am done setting up my %s', 'nextend-facebook-connect'), 'Discord App'); ?></a>
    </div>
</div>