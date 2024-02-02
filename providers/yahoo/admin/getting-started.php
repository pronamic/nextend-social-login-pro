<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$lastUpdated = '2022-04-14';

$provider = $this->getProvider();
?>
<div class="nsl-admin-sub-content">
    <div class="nsl-admin-getting-started">
        <h2 class="title"><?php _e('Getting Started', 'nextend-facebook-connect'); ?></h2>

        <p><?php printf(__('To allow your visitors to log in with their %1$s account, first you must create an %1$s App. The following guide will help you through the %1$s App creation process. After you have created your %1$s App, head over to "Settings" and configure the given "%2$s" and "%3$s" according to your %1$s App.', 'nextend-facebook-connect'), "Yahoo", "Client ID", "Client Secret"); ?></p>

        <p><?php do_action('nsl_getting_started_warnings', $provider, $lastUpdated); ?></p>

        <h2 class="title"><?php printf(_x('Create %s', 'App creation', 'nextend-facebook-connect'), 'Yahoo App'); ?></h2>

        <ol>
            <li><?php printf(__('Navigate to %s', 'nextend-facebook-connect'), '<a href="https://developer.yahoo.com/apps/" target="_blank">https://developer.yahoo.com/apps/</a>'); ?></li>
            <li><?php printf(__('Log in with your %s credentials if you are not logged in.', 'nextend-facebook-connect'), 'Yahoo'); ?></li>
            <li><?php _e('Click on the "<b>Create an App</b>" button on the top right corner.', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Fill the "<b>Application Name</b>" field.', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Enter a "<b>Description</b>" for your app!', 'nextend-facebook-connect') ?></li>
            <li><?php printf(__('Enter the URL of your site to the "<b>Home Page URL</b>" field: <b>%s</b>', 'nextend-facebook-connect'), site_url()); ?></li>
            <li><?php
                $loginUrls = $provider->getAllRedirectUrisForAppCreation();
                printf(__('Add the following URL to the %s field:', 'nextend-facebook-connect'), '"<b>Redirect URI(s)</b>"');
                echo "<ul>";
                foreach ($loginUrls as $loginUrl) {
                    echo "<li><strong>" . $loginUrl . "</strong></li>";
                }
                echo "</ul>";
                ?>
            </li>
            <li><?php printf(__('At %1$s choose the %2$s option.', 'nextend-facebook-connect'), '"<b>OAuth Client Type</b>"', '"<b>Confidential Client</b>"'); ?></li>
            <li><?php _e('Under the "<b>API Permissions</b>" you should select "<b>OpenID Connect Permissions</b>" with both "<b>Email</b>" and "<b>Profile</b>" enabled.', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Click "<b>Create App</b>".', 'nextend-facebook-connect') ?></li>
            <li><?php _e('On the top of the page, you will find the necessary "<b>Client ID</b>" and "<b>Client Secret</b>"! These will be needed in the plugin\'s settings.', 'nextend-facebook-connect') ?></li>
        </ol>

        <a href="<?php echo $this->getUrl('settings'); ?>"
           class="button button-primary"><?php printf(__('I am done setting up my %s', 'nextend-facebook-connect'), 'Yahoo App'); ?></a>
    </div>
</div>