<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$lastUpdated = '2021-09-09';

$provider = $this->getProvider();
?>
<div class="nsl-admin-sub-content">
    <div class="nsl-admin-getting-started">
        <h2 class="title"><?php _e('Getting Started', 'nextend-facebook-connect'); ?></h2>

        <p><?php printf(__('To allow your visitors to log in with their %1$s account, first you must create an %1$s App. The following guide will help you through the %1$s App creation process. After you have created your %1$s App, head over to "Settings" and configure the given "%2$s" and "%3$s" according to your %1$s App.', 'nextend-facebook-connect'), "Disqus", "API Key", "API Secret"); ?></p>

        <p><?php do_action('nsl_getting_started_warnings', $provider, $lastUpdated); ?></p>

        <h2 class="title"><?php printf(_x('Create %s', 'App creation', 'nextend-facebook-connect'), 'Disqus App'); ?></h2>

        <ol>
            <li><?php printf(__('Navigate to %s', 'nextend-facebook-connect'), '<a href="https://disqus.com/api/applications/" target="_blank">https://disqus.com/api/applications/</a>'); ?></li>
            <li><?php printf(__('Log in with your %s credentials if you are not logged in.', 'nextend-facebook-connect'), 'Disqus'); ?></li>
            <li><?php _e('Click on the "<b>Registering new application</b>" button under the Applications tab.', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Enter a "<b>Label</b>" and "<b>Description</b>" for your App.', 'nextend-facebook-connect') ?></li>
            <li><?php printf(__('Fill "<b>Website</b>" with the url of your homepage, probably: <b>%s</b>', 'nextend-facebook-connect'), site_url()); ?></li>
            <li><?php _e('Complete the Human test and click the "<b>Register my application</b>" button.', 'nextend-facebook-connect') ?></li>
            <li><?php printf(__('Fill the "<b>Domains</b>" field with your domain name like: <b>%s</b>', 'nextend-facebook-connect'), $_SERVER['HTTP_HOST']); ?></li>
            <li><?php _e('Select "<b>Read only</b>" as Default Access under the Authentication section.', 'nextend-facebook-connect') ?></li>
            <li><?php
                $loginUrls = $provider->getAllRedirectUrisForAppCreation();
                printf(__('Add the following URL to the %s field:', 'nextend-facebook-connect'), '"<b>Callback URL</b>"');
                echo "<ul>";
                foreach ($loginUrls as $loginUrl) {
                    echo "<li><strong>" . $loginUrl . "</strong></li>";
                }
                echo "</ul>";
                ?>
            </li>
            <li><?php _e('Click on the "<b>Save Changes</b>" button!', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Navigate to the "<b>Details</b>" tab of your Application!', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Here you can see your "<b>API Key</b>" and "<b>API Secret</b>". These will be needed in the plugin\'s settings.', 'nextend-facebook-connect'); ?></li>
        </ol>

        <a href="<?php echo $this->getUrl('settings'); ?>"
           class="button button-primary"><?php printf(__('I am done setting up my %s', 'nextend-facebook-connect'), 'Disqus App'); ?></a>
    </div>
</div>