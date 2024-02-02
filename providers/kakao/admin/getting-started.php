<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$lastUpdated = '2023-08-30';

$provider = $this->getProvider();
?>
<div class="nsl-admin-sub-content">
    <div class="nsl-admin-getting-started">
        <h2 class="title"><?php _e('Getting Started', 'nextend-facebook-connect'); ?></h2>

        <p><?php printf(__('To allow your visitors to log in with their %1$s account, first you must create a %1$s App. The following guide will help you through the %1$s App creation process. After you have created your %1$s App, head over to "Settings" and configure the given "%2$s" and "%3$s" according to your %1$s App.', 'nextend-facebook-connect'), "Kakao", "REST API Key", "Client Secret Code"); ?></p>

        <p><?php do_action('nsl_getting_started_warnings', $provider, $lastUpdated); ?></p>

        <h2 class="title"><?php printf(_x('Create %s', 'App creation', 'nextend-facebook-connect'), 'Kakao App'); ?></h2>

        <ol>
            <li><?php printf(__('Navigate to %s', 'nextend-facebook-connect'), '<a href="https://developers.kakao.com/console" target="_blank">https://developers.kakao.com/console</a>'); ?></li>
            <li>
                <?php printf(__('Log in with your %s credentials if you are not logged in.', 'nextend-facebook-connect'), 'Kakao'); ?>
            </li>
            <li><?php printf(__('Click on the blue "%s" button!', 'nextend-facebook-connect'), '<b>Add an application</b>'); ?></li>
            <li><?php printf(__('Select an image for your App, fill in the "%1$s" and "%2$s" fields, comply with the "%3$s", then click on the "%4$s" button!', 'nextend-facebook-connect'), '<b>App Name</b>', '<b>Company name</b>', '<b>Operating Policy</b>', '<b>Save</b>'); ?></li>
            <li><?php printf(__('Once you have a project, click on it, and you will enter the %1$s.', 'nextend-facebook-connect'), '<b>Summary</b>'); ?></li>
            <li><?php printf(__('Copy and save the "%1$s" value. This is what you will use later on the Settings tab for the "%1$s" field.', 'nextend-facebook-connect'), '<b>REST API Key</b>'); ?></li>
            <li>
                <?php printf(__('Then in the side bar on the left side, find the "%1$s" menu and click on the "%2$s" menu point. Click on the "%3$s" button, then on "%4$s".', 'nextend-facebook-connect'), '<b>Product Settings</b>', '<b>Security</b>', '<b>Generate code</b>', '<b>Generate</b>'); ?>
                <ul>
                    <li>
                        <?php printf(__('Copy and save this code, this is what you will use for the "%1$s" on the Settings tab.', 'nextend-facebook-connect'), '<b>Client Secret Code</b>'); ?>
                        <?php printf(__('Also, make sure to set the "%1$s" to "%2$s" with the "%3$s" button.', 'nextend-facebook-connect'), '<b>Activation state</b>', '<b>Enabled</b>', '<b>Set</b>'); ?>
                    </li>
                </ul>
            </li>
            <li><?php printf(__('Next, go to the "%1$s" in the "%2$s" menu on the left.', 'nextend-facebook-connect'), '<b>Platform</b>', '<b>App Settings</b>'); ?></li>
            <li><?php printf(__('Click on the blue "%1$s" button, then enter your domain name into the "%2$s" field, probably: %3$s', 'nextend-facebook-connect'), '<b>Register Web platform</b>', '<b>Site domain</b>', '<b>' . set_url_scheme('http://' . $_SERVER['HTTP_HOST']) . '</b>'); ?></li>
            <li><?php printf(__('Press the %s button.', 'nextend-facebook-connect'), '"<b>Save</b>"'); ?></li>
            <li><?php printf(__('Below the "%1$s" menu on the left, click on the "%2$s" menu point, then set the "%3$s" ON-OFF switch to ON. A popup will appear, where you should click on the "%4$s" button.', 'nextend-facebook-connect'), '<b>Product Settings</b>', '<b>Kakao Login</b>', '<b>Kakao Login Activation State</b>', '<b>Activate</b>'); ?></li>
            <li><?php
                $loginUrls = $provider->getAllRedirectUrisForAppCreation();
                printf(__('Scroll down to the "%1$s" section, then click on the "%2$s" button, and enter the following URL: ', 'nextend-facebook-connect'), '<b>Redirect URI</b>', '<b>Register Redirect URI</b>');

                echo "<ul>";
                foreach ($loginUrls as $loginUrl) {
                    echo "<li><strong>" . $loginUrl . "</strong></li>";
                }
                echo "</ul>";
                ?>
            </li>
            <li><?php printf(__('Press the %s button.', 'nextend-facebook-connect'), '"<b>Save</b>"'); ?></li>
            <li><?php printf(__('After this, click on the "%1$s" menu point on the left.', 'nextend-facebook-connect'), '<b>Consent Items</b>'); ?>
            </li>
            <li>
                <?php printf(__('Click on the "%1$s" button for the Scopes with the "%2$s" and for any other scope that you will need:', 'nextend-facebook-connect'), '<b>Set</b>', '<b>Scope ID</b>'); ?>
                <ol>
                    <li><?php printf(__('%s - Purpose: Used for the username generation and for setting the first name and last name.', 'nextend-facebook-connect'), '<b>profile_nickname</b>'); ?></li>
                    <li><?php printf(__('%s - Purpose: Accounts will be registered or linked based on this email address.', 'nextend-facebook-connect'), '<b>account_email</b>'); ?></li>
                    <li><?php printf(__('%s - Purpose: This image will be used as the avatar of the account.', 'nextend-facebook-connect'), '<b>profile_image</b>'); ?></li>
                </ol>
                <ul>
                    <li><?php printf(__('In the popup you should set the "%1$s" to either "%2$s" or "%3$s" depending on your needs.', 'nextend-facebook-connect'), '<b>Consent Type</b>', '<b>Optional consent</b>', '<b>Required consent</b>'); ?></li>
                    <li><?php printf(__('Finally fill the "%1$s" field. It is important to clearly state what you want to use the consent item for.', 'nextend-facebook-connect'), '<b>Purpose of consent</b>'); ?></li>
                    <li><?php printf(__('%1$s The "%2$s" type itself and certain scopes (e.g. "%3$s", "%4$s" - used when you turn on the corresponding setting on our %5$s tab), will require you to register business information, or if you are a single developer verify your identity (both can be done under the "%6$s" menu point), in order to be able to request consent to these items.', 'nextend-facebook-connect'), '<b>' . __('WARNING:', 'nextend-facebook-connect') . '</b>', '<b>Require consent</b>', '<b>Birth Year</b>', '<b>Phone Number</b>', __('Sync data', 'nextend-facebook-connect'), '<b>Business</b>'); ?></li>
                </ul>
            </li>
            <li><?php printf(__('Find the "%1$s" and "%2$s" values you copied earlier. You will need these for the fields with the same name on the "%3$s" tab.', 'nextend-facebook-connect'), '<b>REST API Key</b>', '<b>Client Secret Code</b>', __('Settings', 'nextend-facebook-connect')); ?></li>
            <a href="<?php echo $this->getUrl('settings'); ?>"
               class="button button-primary"><?php printf(__('I am done setting up my %s', 'nextend-facebook-connect'), 'Kakao App'); ?></a>
    </div>
</div>