<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$lastUpdated = '2021-09-09';

$provider = $this->getProvider();
?>
<div class="nsl-admin-sub-content">
    <div class="nsl-admin-getting-started">
        <h2 class="title"><?php _e('Getting Started', 'nextend-facebook-connect'); ?></h2>

        <p><?php printf(__('To allow your visitors to log in with their %1$s account, first you must create a %1$s App. The following guide will help you through the %1$s App creation process. After you have created your %1$s App, head over to "Settings" and configure the given "%2$s" and "%3$s" according to your %1$s App.', 'nextend-facebook-connect'), "VKontakte", "Application ID", "Secure key"); ?></p>

        <p><?php do_action('nsl_getting_started_warnings', $provider, $lastUpdated); ?></p>

        <h2 class="title"><?php printf(_x('Create %s', 'App creation', 'nextend-facebook-connect'), 'VKontakte App'); ?></h2>

        <ol>
            <li><?php printf(__('Navigate to %s', 'nextend-facebook-connect'), '<a href="https://vk.com/apps?act=manage" target="_blank">https://vk.com/apps?act=manage</a>'); ?></li>
            <li><?php printf(__('Log in with your %s credentials if you are not logged in.', 'nextend-facebook-connect'), 'VK'); ?></li>
            <li><?php _e('Locate the blue "<b>Create app</b>" button and click on it.', 'nextend-facebook-connect'); ?></li>
            <li><?php _e('Enter the <b>Title</b> for your App and select "<b>Website</b>" as platform.', 'nextend-facebook-connect') ?></li>
            <li><?php printf(__('Fill "<b>Website address</b>" with the url of your homepage, probably: <b>%s</b>', 'nextend-facebook-connect'), site_url()); ?></li>
            <li><?php printf(__('Fill the "<b>Base domain</b>" field with your domain, probably: <b>%s</b>', 'nextend-facebook-connect'), parse_url(site_url(), PHP_URL_HOST)); ?></li>
            <li><?php _e('When all fields are filled, click the "<b>Upload app</b>" button.', 'nextend-facebook-connect') ?></li>
            <li><?php _e('<b>Fill the information form</b> of your app, <b>upload an app icon</b> then click <b>Save</b>.', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Pick <b>Settings</b> at the left-hand menu ', 'nextend-facebook-connect') ?></li>
            <li><?php
                $loginUrls = $provider->getAllRedirectUrisForAppCreation();
                printf(__('Add the following URL to the %s field: ', 'nextend-facebook-connect'), '"<b>Authorized redirect URI</b>"');
                echo "<ul>";
                foreach ($loginUrls as $loginUrl) {
                    echo "<li><strong>" . $loginUrl . "</strong></li>";
                }
                echo "</ul>";
                ?>
            </li>
            <li><?php _e('<b>Save</b> your app', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Find the necessary "<b>App ID</b>" and "<b>Secure key</b>" at the top of the Settings page where you just hit the save button.', 'nextend-facebook-connect') ?></li>
        </ol>

        <a href="<?php echo $this->getUrl('settings'); ?>"
           class="button button-primary"><?php printf(__('I am done setting up my %s', 'nextend-facebook-connect'), 'VKontakte App'); ?></a>
    </div>
</div>