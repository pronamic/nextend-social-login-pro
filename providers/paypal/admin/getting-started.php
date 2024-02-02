<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$lastUpdated = '2022-06-01';

$provider = $this->getProvider();
?>
<div class="nsl-admin-sub-content">
    <div class="nsl-admin-getting-started">
        <h2 class="title"><?php _e('Getting Started', 'nextend-facebook-connect'); ?></h2>

        <p><?php printf(__('To allow your visitors to log in with their %1$s account, first you must create an %1$s App. The following guide will help you through the %1$s App creation process. After you have created your %1$s App, head over to "Settings" and configure the given "%2$s" and "%3$s" according to your %1$s App.', 'nextend-facebook-connect'), "PayPal", "Client ID", "Secret"); ?></p>

        <p><?php do_action('nsl_getting_started_warnings', $provider, $lastUpdated); ?></p>

        <h2 class="title"><?php printf(_x('Create %s', 'App creation', 'nextend-facebook-connect'), 'PayPal App'); ?></h2>

        <ol>
            <li><?php printf(__('Editing Live Apps are only possible with a %s. If you own one, go to the 4. step, if not click on the link!', 'nextend-facebook-connect'), '<a href="https://www.paypal.com/" target="_blank">PayPal Business Account</a>'); ?></li>
            <li><?php _e('Click on Registration and create a Business account.', 'nextend-facebook-connect') ?></li>
            <li><?php _e('If you are done, follow the guide from the 5. step.', 'nextend-facebook-connect') ?></li>
            <li><?php printf(__('Log in with your %s credentials.', 'nextend-facebook-connect'), 'PayPal'); ?></li>
            <li><?php printf(__('Navigate to %s', 'nextend-facebook-connect'), '<a href="https://developer.paypal.com/dashboard/applications/live" target="_blank">https://developer.paypal.com/dashboard/applications/live</a>'); ?></li>
            <li><?php _e('There is a Sandbox/Live switch. Make sure "<b>Live</b>" is selected!', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Click the "<b>Create App</b>" button under the REST API apps section.', 'nextend-facebook-connect') ?></li>
            <li><?php _e('Fill the "<b>App Name</b>" field and click "<b>Create App</b>" button.', 'nextend-facebook-connect') ?></li>
            <li><?php printf(__('Scroll down to %1$s section, and turn off all features. ( If you are trying to set up social login in an existing App, then create a new App, as this App should be used only for social login because some of the non used features might cause scope specific problems. )', 'nextend-facebook-connect'), '"<b>Features</b>"'); ?></li>
            <li><?php printf(__('Find the %1$s feature and check the checkbox.', 'nextend-facebook-connect'), '"<b>Log in with PayPal</b>"'); ?></li>
            <li><?php printf(__('Click on the %1$s link that you can find below the %2$s feature.', 'nextend-facebook-connect'), '"<b>Advanced Settings</b>"', '"<b>Log in with PayPal</b>"'); ?></li>

            <li><?php
                $loginUrls = $provider->getAllRedirectUrisForAppCreation();
                printf(__('Add the following URL to the %s field and press %2$s:', 'nextend-facebook-connect'), '"<b>Return URL</b>"', '"<b>Save</b>"');
                echo "<ul>";
                foreach ($loginUrls as $loginUrl) {
                    echo "<li><strong>" . $loginUrl . "</strong></li>";
                }
                echo "</ul>";
                ?>
            </li>
            <li><?php printf(__('Scroll down to %1$s section, and tick the %2$s field.', 'nextend-facebook-connect'), '"<b>Information requested from customers</b>"', '"<b>Full Name</b>"'); ?></li>
            <li><?php printf(__('If you want to get the email address as well, then don\'t forget to tick %1$s option. In this case you should also enable the %2$s setting in our %3$s %4$s tab. ( If you enabled extra fields on our Sync data tab, don\'t forget to tick the other necessary fields either! )', 'nextend-facebook-connect'), '<b>Email address</b>', '<b>' . __('Email scope', 'nextend-facebook-connect') . '</b>', 'PayPal', __('Settings', 'nextend-facebook-connect')); ?></li>

            <li><?php printf(__('Fill the %1$s and %2$s fields.', 'nextend-facebook-connect'), '"<b>Privacy policy URL</b>"', '"<b>User agreement URL</b>"'); ?></li>
            <li><?php printf(__('Optional: If you want the unverified %1$s accounts to be able to login with your App, then check the %2$s field, too.', 'nextend-facebook-connect'), 'PayPal', '"<b>Enable customers who have not yet confirmed their email with PayPal to log in to your app.</b>"'); ?></li>
            <li><?php printf(__('When all fields are filled, click %1$s in the modal.', 'nextend-facebook-connect'), '"<b>Save</b>"'); ?></li>
            <li><?php printf(__('Wait for %1$s to approve the %2$s feature ( this might take up to 7 business days ). The %3$s row will indicate the current status. ', 'nextend-facebook-connect'), 'PayPal', '"<b>Log in with PayPal</b>"', '"<b>Approval Status</b>"'); ?></li>
            <li><?php printf(__('Once the %1$s feature is approved, scroll up to the %2$s section and find the necessary %3$s and %4$s values, you will need these on the %5$s tab of the plugin!', 'nextend-facebook-connect'), '"<b>Log in with PayPal</b>"', '"<b>API credentials</b>"', '"<b>Client ID</b>"', '"<b>Secret key 1</b>"', '"<b>' . __('Settings', 'nextend-facebook-connect') . '</b>"'); ?></li>
        </ol>

        <a href="<?php echo $this->getUrl('settings'); ?>"
           class="button button-primary"><?php printf(__('I am done setting up my %s', 'nextend-facebook-connect'), 'PayPal App'); ?></a>
    </div>
</div>