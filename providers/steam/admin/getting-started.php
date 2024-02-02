<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$lastUpdated = '2022-06-13';

$provider = $this->getProvider();
?>
<div class="nsl-admin-sub-content">

    <?php if (substr($provider->getLoginUrl(), 0, 8) !== 'https://'): ?>
        <div class="error">
            <p><?php printf(__('%1$s allows HTTPS OAuth Redirects only. You must move your site to HTTPS in order to allow login with %1$s.', 'nextend-facebook-connect'), 'Steam'); ?></p>
            <p>
                <a href="https://nextendweb.com/nextend-social-login-docs/facebook-api-changes/#how-to-add-ssl-to-wordpress"><?php _e("How to get SSL for my WordPress site?", 'nextend-facebook-connect'); ?></a>
            </p>
        </div>
    <?php else: ?>
        <div class="nsl-admin-getting-started">
            <h2 class="title"><?php _e('Getting Started', 'nextend-facebook-connect'); ?></h2>

            <p><?php printf(__('To allow your visitors to log in with their %1$s account, first you must create a %1$s %2$s. The following guide will help you through the %1$s %2$s creation process. After you have created your %1$s %2$s, head over to "Settings" and configure the given "%2$s" field according to your %1$s %2$s.', 'nextend-facebook-connect'), "Steam", "Web API Key"); ?></p>

            <p><?php do_action('nsl_getting_started_warnings', $provider, $lastUpdated); ?></p>

            <h2 class="title"><?php printf(_x('Create %s', 'App creation', 'nextend-facebook-connect'), 'Steam Web API key'); ?></h2>

            <ol>
                <li><?php printf(__('To be able to create a %1$s, you need to spend at least $5 in the %2$s.', 'nextend-facebook-connect'), 'Steam Web API key', 'Steam Store'); ?></li>
                <li><?php printf(__('Navigate to %s', 'nextend-facebook-connect'), '<strong><a href="https://steamcommunity.com/dev/apikey" target="_blank">https://steamcommunity.com/dev/apikey</a></strong>'); ?></li>
                <li><?php printf(__('Log in with your %s credentials if you are not logged in.', 'nextend-facebook-connect'), 'Steam'); ?></li>
                <li><?php printf(__('Enter your domain name to the %1$s field, probably: %2$s', 'nextend-facebook-connect'), '<strong>"Domain Name"</strong>', '<strong>' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . '</strong>'); ?></li>
                <li><?php printf(__('Check the %1$s option.', 'nextend-facebook-connect'), '<strong>"I agree to the Steam Web API Terms of Use"</strong>'); ?></li>
                <li><?php printf(__('Press the %1$s button.', 'nextend-facebook-connect'), '<strong>"Register"</strong>'); ?></li>
                <li><?php printf(__('Copy the necessary %1$s value under the %2$s section, you will need this on our %3$s tab.', 'nextend-facebook-connect'), '<strong>"Key"</strong>', '<strong>"Your Steam Web API Key"</strong>', __('Settings', 'nextend-facebook-connect')); ?></li>
            </ol>

            <p><?php printf(__('<b>WARNING:</b> The %1$s API can not return any email address! %2$sLearn more%3$s.', 'nextend-facebook-connect'), 'Steam', '<a href="https://nextendweb.com/nextend-social-login-docs/provider-steam/#empty_email" target="_blank">', '</a>'); ?></p>


            <a href="<?php echo $this->getUrl('settings'); ?>"
               class="button button-primary"><?php printf(__('I am done setting up my %s', 'nextend-facebook-connect'), 'Steam Web API key'); ?></a>
        </div>
    <?php endif; ?>

</div>