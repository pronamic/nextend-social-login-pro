<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$lastUpdated = '2023-06-15';

$provider = $this->getProvider();
?>
<div class="nsl-admin-sub-content">

    <?php if (substr($provider->getLoginUrl(), 0, 8) !== 'https://'): ?>
        <div class="error">
            <p><?php printf(__('%1$s allows HTTPS OAuth Redirects only. You must move your site to HTTPS in order to allow login with %1$s.', 'nextend-facebook-connect'), 'TikTok'); ?></p>
            <p>
                <a href="https://nextendweb.com/nextend-social-login-docs/facebook-api-changes/#how-to-add-ssl-to-wordpress"><?php _e("How to get SSL for my WordPress site?", 'nextend-facebook-connect'); ?></a>
            </p>
        </div>
    <?php else: ?>
        <div class="nsl-admin-getting-started">

            <h2 class="title"><?php _e('Getting Started', 'nextend-facebook-connect'); ?></h2>

            <p><?php printf(__('To allow your visitors to log in with their %1$s account, first you must create an %1$s App. The following guide will help you through the %1$s App creation process. After you have created your %1$s App, head over to "Settings" and configure the given "%2$s" and "%3$s" according to your %1$s App.', 'nextend-facebook-connect'), "TikTok", "Client Key", "Client Secret"); ?></p>

            <p><?php do_action('nsl_getting_started_warnings', $provider, $lastUpdated); ?></p>

            <h2 class="title"><?php printf(_x('Create %s', 'App creation', 'nextend-facebook-connect'), 'TikTok App'); ?></h2>

            <ol>
                <li><?php printf(__('Navigate to %s', 'nextend-facebook-connect'), '<a href="https://developers.tiktok.com/" target="_blank">https://developers.tiktok.com/</a>'); ?></li>
                <li><?php printf(__('Log in to your %s developer account or register one if you don\'t have any!', 'nextend-facebook-connect'), 'TikTok'); ?></li>
                <li><?php printf(__('On the top right corner click on %1$s then click on the %2$s option.', 'nextend-facebook-connect'), '<strong>Manage apps</strong>', '<strong>Connect an app</strong>'); ?></li>
                <li><?php printf(__('Find the %1$s section and upload an image as %2$s, then enter an %3$s, a %4$s and choose a value for %5$s.', 'nextend-facebook-connect'), '<strong>Configuration</strong>', '<strong>App icon</strong>', '<strong>App name</strong>', '<strong>Description</strong>', '<strong>Category</strong>'); ?></li>
                <li><?php printf(__('For %1$s you should enable the %2$s option then enter the URL of your website into the %3$s field, probably: %4$s', 'nextend-facebook-connect'), '<strong>Platform</strong>', '<strong>Configure for Web</strong>', '<strong>Website URL</strong>', '<strong>' . site_url() . '</strong>'); ?></li>
                <li><?php printf(__('Click on the %s button.', 'nextend-facebook-connect'), '<strong>Save changes</strong>'); ?></li>
                <li><?php printf(__('Click on %1$s on the left hand menu then add the %2$s product, and press the %3$s button.', 'nextend-facebook-connect'), '<strong>+Add products</strong>', '<strong>Login Kit</strong>', '<strong>Done</strong>'); ?></li>
                <li><?php printf(__('At the left hand menu click on the %1$s option under %2$s.', 'nextend-facebook-connect'), '<strong>Login Kit</strong>', '<strong>Products</strong>'); ?></li>
                <li><?php printf(__('Enter your %1$s and %2$s URLs.', 'nextend-facebook-connect'), '<strong>Terms of Service</strong>', '<strong>Privacy Policy</strong>'); ?></li>
                <li><?php
                    $loginUrls = $provider->getAllRedirectUrisForAppCreation();
                    printf(__('Add the following URL to the %s field: ', 'nextend-facebook-connect'), '"<b>Redirect URI</b>"');
                    echo "<ul>";
                    foreach ($loginUrls as $loginUrl) {
                        echo "<li><strong>" . $loginUrl . "</strong></li>";
                    }
                    echo "</ul>";
                    ?>
                </li>
               <li><?php printf(__('Scroll up and click on the %s button.', 'nextend-facebook-connect'), '<strong>Save changes</strong>'); ?></li>
                <li><?php printf(__('Currently your App is in %1$s status, meaning that you don\'t have access to the credentials which are necessary for the %2$s integration. In order to gain access, click on the %3$s on the top right corner.', 'nextend-facebook-connect'), '<strong>Staging</strong>', 'TikTok', '<strong>Submit for review</strong>'); ?></li>
                <li><?php printf(__('A modal will appear where you should enter a text, that describes what you are going to do with the App. In this particular case, you will use it to offer %1$s login option for your visitors.', 'nextend-facebook-connect'), 'TikTok'); ?></li>
                <li><?php printf(__('Press the %s button.', 'nextend-facebook-connect'), '<strong>Submit</strong>'); ?></li>
                <li><?php printf(__('Wait until your App gets approved. This can take a couple of days. If you scroll up to the top of the page you will be able to inspect the %1$s below the %2$s section. If you want to learn more about the App review process, you can find more information in the %3$sofficial documentation%4$s.', 'nextend-facebook-connect'), '<strong>Status</strong>', '<strong>App details</strong>', '<a href="https://developers.tiktok.com/doc/getting-started-app-review-process" target="_blank">', '</a>'); ?></li>
                <li><?php printf(__('Once the %1$s says %2$s, you will be able to reveal the %3$s and %4$s by clicking on the eye icon next to these fields. You will need these credentials for the provider configuration.', 'nextend-facebook-connect'), '<strong>Status</strong>', '<strong>Live in production</strong>', '<strong>Client Key</strong>', '<strong>Client Secret</strong>'); ?></li>
            </ol>
            <p><?php printf(__('<b>WARNING:</b> The %1$s API can not return any email address or phone number! %2$sLearn more%3$s.', 'nextend-facebook-connect'), 'TikTok', '<a href="https://nextendweb.com/nextend-social-login-docs/provider-tiktok/#empty_email" target="_blank">', '</a>'); ?></p>

            <a href="<?php echo $this->getUrl('settings'); ?>"
               class="button button-primary"><?php printf(__('I am done setting up my %s', 'nextend-facebook-connect'), 'TikTok App'); ?></a>
        </div>
    <?php endif; ?>

</div>