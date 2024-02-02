<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$lastUpdated = '2021-09-09';

$provider = $this->getProvider();
?>
<div class="nsl-admin-sub-content">

    <?php if (substr($provider->getLoginUrl(), 0, 8) !== 'https://'): ?>
        <div class="error">
            <p><?php printf(__('%1$s allows HTTPS OAuth Redirects only. You must move your site to HTTPS in order to allow login with %1$s.', 'nextend-facebook-connect'), 'Amazon'); ?></p>
            <p>
                <a href="https://nextendweb.com/nextend-social-login-docs/facebook-api-changes/#how-to-add-ssl-to-wordpress"><?php _e("How to get SSL for my WordPress site?", 'nextend-facebook-connect'); ?></a>
            </p>
        </div>
    <?php else: ?>
        <div class="nsl-admin-getting-started">
            <h2 class="title"><?php _e('Getting Started', 'nextend-facebook-connect'); ?></h2>

            <p><?php printf(__('To allow your visitors to log in with their %1$s account, first you must create an %1$s App. The following guide will help you through the %1$s App creation process. After you have created your %1$s App, head over to "Settings" and configure the given "%2$s" and "%3$s" according to your %1$s App.', 'nextend-facebook-connect'), "Amazon", "Client ID", "Client secret"); ?></p>

            <p><?php do_action('nsl_getting_started_warnings', $provider, $lastUpdated); ?></p>

            <h2 class="title"><?php printf(_x('Create %s', 'App creation', 'nextend-facebook-connect'), 'Amazon App'); ?></h2>

            <ol>
                <li><?php printf(__('Navigate to %s', 'nextend-facebook-connect'), '<a href="https://www.amazon.com/" target="_blank">https://www.amazon.com/</a>'); ?></li>
                <li><?php printf(__('Log in with your %s credentials if you are not logged in.', 'nextend-facebook-connect'), 'Amazon'); ?></li>
                <li><?php printf(__('Visit %s', 'nextend-facebook-connect'), '<a href="https://developer.amazon.com/lwa/sp/overview.html" target="_blank">https://developer.amazon.com/lwa/sp/overview.html</a>'); ?></li>
                <li><?php _e('If you don\'t have a Security Profile yet, you\'ll need to create one. You can do this by clicking on the orange "<b>Create a New Security Profile</b>" button on the left side.', 'nextend-facebook-connect'); ?></li>
                <li><?php _e('Fill "<b>Security Profile Name</b>", "<b>Security Profile Description</b>" and "<b>Consent Privacy Notice URL</b>".', 'nextend-facebook-connect') ?></li>
                <li><?php _e('Once you filled all the required fields, click "<b>Save</b>".', 'nextend-facebook-connect') ?></li>
                <li><?php _e('On the right side, under "<b>Manage</b>", hover over the gear icon and select "<b>Web Settings</b>" option.', 'nextend-facebook-connect') ?></li>
                <li><?php _e('Click "<b>Edit</b>".', 'nextend-facebook-connect') ?></li>
                <li><?php printf(__('Fill "<b>Allowed Origins</b>" with the url of your homepage, probably: <b>%s</b>', 'nextend-facebook-connect'), str_replace(parse_url(site_url(), PHP_URL_PATH), "", site_url())); ?></li>
                <li><?php
                    $loginUrls = $provider->getAllRedirectUrisForAppCreation();
                    printf(__('Add the following URL to the %s field: ', 'nextend-facebook-connect'), '"<b>Allowed Return URLs</b>"');
                    echo "<ul>";
                    foreach ($loginUrls as $loginUrl) {
                        echo "<li><strong>" . $loginUrl . "</strong></li>";
                    }
                    echo "</ul>";
                    ?>
                </li>
                <li><?php _e('When all fields are filled, click "<b>Save</b>".', 'nextend-facebook-connect') ?></li>
                <li><?php _e('Find the necessary "<b>Client ID</b>" and "<b>Client Secret</b>" at the middle of the page, under the "<b>Web Settings</b>" tab.', 'nextend-facebook-connect') ?></li>
            </ol>

            <a href="<?php echo $this->getUrl('settings'); ?>"
               class="button button-primary"><?php printf(__('I am done setting up my %s', 'nextend-facebook-connect'), 'Amazon App'); ?></a>
        </div>
    <?php endif; ?>

</div>