<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$lastUpdated = '2021-09-22';

$provider = $this->getProvider();
?>
<div class="nsl-admin-sub-content">
    <div class="nsl-admin-getting-started">
        <h2 class="title"><?php _e('Getting Started', 'nextend-facebook-connect'); ?></h2>

        <p><?php printf(__('To allow your visitors to log in with their %1$s account, first you must create a %1$s App. The following guide will help you through the %1$s App creation process. After you have created your %1$s App, head over to "Settings" and configure the given "%2$s" and "%3$s" according to your %1$s App.', 'nextend-facebook-connect'), "Slack", "Client ID", "Client Secret"); ?></p>

        <p><?php do_action('nsl_getting_started_warnings', $provider, $lastUpdated); ?></p>

        <h2 class="title"><?php printf(_x('Create %s', 'App creation', 'nextend-facebook-connect'), 'Slack App'); ?></h2>

        <ol>
            <li><?php printf(__('Visit the %1$s page and log in with your %2$s credentials if you are not logged in, yet.', 'nextend-facebook-connect'), '<a href="https://slack.com/signin#/signin" target="_blank">Slack Sign In</a>', 'Slack'); ?></li>
            <li><?php printf(__('Navigate to %s', 'nextend-facebook-connect'), '<a href="https://api.slack.com/apps" target="_blank">https://api.slack.com/apps</a>'); ?></li>
            <li><?php _e('Click the "<b>Create New App</b>" button. (If you can not see this button, you might need to create a workspace first!)', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Choose the "<b>From scratch</b>" option.', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Fill the "<b>App Name</b>" field, select your workspace and click the "<b>Create App</b>" button.', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Under the "<b>Add features and functionality</b>" panel click on the "<b>Permissions</b>" option.', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Scroll down to the "<b>Redirect URLs</b>" section.', 'nextend-facebook-connect') ?></li>
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
            <li><?php _e('Click on the "<b>Save URLs</b>" button.', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Scroll down to the "<b>Scopes</b>" section.', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Add the following Scopes to the "<b>User Token Scopes</b>" field: "<b>openid</b>", "<b>profile</b>", "<b>email</b>"', 'nextend-facebook-connect') ?></li>
            <li><?php _e('On the top left side click on the "<b>Basic Information</b>" option in the "<b>Settings</b>".', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Under the "<b>Install your app</b>" panel click on the "<b>Install to Workspace</b>" button.', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Click on the "<b>Allow</b>" button.', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Under the "<b>Manage distribution</b>" panel click on the "<b>Distribute App</b>" button.', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Scroll down to the "<b>Remove Hard Coded Information</b>" section.', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Click on the "<b>I’ve reviewed and removed any hard-coded information.</b>". ', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Click on the "<b>Activate Public Distribution</b>" button.', 'nextend-facebook-connect') ?></li>
            <li><?php _e('On the top left side click on the "<b>Basic Information</b>" option, that you find under "<b>Settings</b>".', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Scroll down to the "<b>App Credentials</b>" section, find the necessary "<b>Client ID</b>" and "<b>Client Secret</b>" values and fill these fields in the plugin settings!', 'nextend-facebook-connect') ?></li>
        </ol>

        <a href="<?php echo $this->getUrl('settings'); ?>"
           class="button button-primary"><?php printf(__('I am done setting up my %s', 'nextend-facebook-connect'), 'Slack App'); ?></a>
    </div>
</div>