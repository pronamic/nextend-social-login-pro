<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$lastUpdated = '2021-09-09';

$provider = $this->getProvider();
?>
<div class="nsl-admin-sub-content">
    <div class="nsl-admin-getting-started">
        <h2 class="title"><?php _e('Getting Started', 'nextend-facebook-connect'); ?></h2>

        <p><?php printf(__('To allow your visitors to log in with their %1$s account, first you must create an %1$s App. The following guide will help you through the %1$s App creation process. After you have created your %1$s App, head over to "Settings" and configure the given "%2$s" and "%3$s" according to your %1$s App.', 'nextend-facebook-connect'), "WordPress.com", "Client ID", "Client Secret"); ?></p>

        <p><?php do_action('nsl_getting_started_warnings', $provider, $lastUpdated); ?></p>

        <h2 class="title"><?php printf(_x('Create %s', 'App creation', 'nextend-facebook-connect'), 'WordPress.com App'); ?></h2>

        <ol>
            <li><?php printf(__('Navigate to %s', 'nextend-facebook-connect'), '<a href="https://developer.wordpress.com/apps/" target="_blank">https://developer.wordpress.com/apps/</a>'); ?></li>
            <li><?php printf(__('Log in with your %s credentials if you are not logged in.', 'nextend-facebook-connect'), 'WordPress.com'); ?></li>
            <li><?php _e('Click on the "<b>Create New Application</b>" button.', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Enter a "<b>Name</b>" and "<b>Description</b>" for your App.', 'nextend-facebook-connect') ?></li>
            <li><?php printf(__('Fill "<b>Website URL</b>" with the url of your homepage, probably: <b>%s</b>', 'nextend-facebook-connect'), site_url()); ?></li>
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
            <li><?php _e('You can leave the "Javascript Origins" field blank!', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Complete the human verification test.', 'nextend-facebook-connect'); ?></li>
            <li><?php _e('At the "<b>Type</b>" make sure "<b>Web</b>" is selected!', 'nextend-facebook-connect'); ?></li>
            <li><?php _e('Click the "<b>Create</b>" button!', 'nextend-facebook-connect'); ?></li>
            <li><?php _e('<b>Click the name of your App</b> either in the Breadcrumb navigation or next to Editing!', 'nextend-facebook-connect'); ?></li>
            <li><?php _e('Here you can see your "<b>Client ID</b>" and "<b>Client Secret</b>". These will be needed in the plugin\'s settings.', 'nextend-facebook-connect'); ?></li>
        </ol>

        <a href="<?php echo $this->getUrl('settings'); ?>"
           class="button button-primary"><?php printf(__('I am done setting up my %s', 'nextend-facebook-connect'), 'WordPress.com App'); ?></a>
    </div>
</div>