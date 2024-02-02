<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$lastUpdated = '2021-09-09';

$provider = $this->getProvider();
?>
<div class="nsl-admin-sub-content">
    <div class="nsl-admin-getting-started">
        <h2 class="title"><?php _e('Getting Started', 'nextend-facebook-connect'); ?></h2>

        <p><?php printf(__('To allow your visitors to log in with their %1$s account, first you must create a %1$s App. The following guide will help you through the %1$s App creation process. After you have created your %1$s App, head over to "Settings" and configure the given "%2$s" and "%3$s" according to your %1$s App.', 'nextend-facebook-connect'), "Line", "Channel ID", "Channel secret"); ?></p>

        <p><?php do_action('nsl_getting_started_warnings', $provider, $lastUpdated); ?></p>

        <h2 class="title"><?php printf(_x('Create %s', 'App creation', 'nextend-facebook-connect'), 'Line App'); ?></h2>

        <ol>
            <li><?php printf(__('Editing Live Apps are only possible with a %s. So please make sure you own one!', 'nextend-facebook-connect'), '<a href="https://developers.line.biz/console/" target="_blank">Line Business Account</a>'); ?></li>
            <li><?php printf(__('Log in with your %s credentials if you are not logged in.', 'nextend-facebook-connect'), 'Line business'); ?></li>
            <li><?php _e('Click the "<b>Create a new provider</b>" button.', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Fill the "<b>Provider name</b>" field and click the "<b>Create</b>" button.', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Under the "<b>Channels</b>" panel select the "<b>Create a LINE Login channel</b>" option.', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Make sure "<b>LINE Login</b>" is selected as "<b>Channel type</b>".', 'nextend-facebook-connect') ?></li>
            <li><?php _e('For "<b>Provider</b>" choose the provider from the list, that you just created.', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Select your "<b>Region</b>".', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Add your "<b>Channel icon</b>", "<b>Channel name</b>" and "<b>Channel description</b>". These will appear on your Consent Screen!', 'nextend-facebook-connect') ?></li>
            <li><?php _e('At the "<b>App types</b>" select the "<b>Web app</b>" option.', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Read and consent to the "<b>LINE Developers Agreement</b>", then click the "<b>Create</b>" button.', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Scroll down to "<b>OpenID Connect</b>", click the "<b>Apply</b>" button near the "<b>Email address permission</b>" label.', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Fill out the form, then click the "<b>Submit</b>" button.', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Scroll up to the top of the page and choose the "<b>LINE Login</b>" section.', 'nextend-facebook-connect') ?></li>
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
            <li><?php _e('Under your App name click the "<b>Developing</b>" button and publish your Channel!', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Go to the "<b>Basic settings</b>" tab and find the necessary "<b>Channel ID</b>" and "<b>Channel secret</b>" values and fill these fields in the plugin settings!', 'nextend-facebook-connect') ?></li>
        </ol>

        <a href="<?php echo $this->getUrl('settings'); ?>"
           class="button button-primary"><?php printf(__('I am done setting up my %s', 'nextend-facebook-connect'), 'Line App'); ?></a>
    </div>
</div>