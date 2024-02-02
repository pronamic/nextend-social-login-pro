<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$lastUpdated = '2021-09-09';

$provider = $this->getProvider();

function nslGetReversedDomain($domain) {
    return implode('.', array_reverse(explode('.', (str_replace('www.', '', trim($domain))))));
}

$protocol        = parse_url(site_url(), PHP_URL_SCHEME);
$domain          = parse_url(site_url(), PHP_URL_HOST);
$clean_domain    = str_replace('www.', '', $domain);
$reversed_domain = nslGetReversedDomain($clean_domain);
$url_domain      = $protocol . '://' . $clean_domain;


?>
<div class="nsl-admin-sub-content">
    <?php if (substr($provider->getLoginUrl(), 0, 8) !== 'https://'): ?>
        <div class="error">
            <p><?php printf(__('%1$s allows HTTPS OAuth Redirects only. You must move your site to HTTPS in order to allow login with %1$s.', 'nextend-facebook-connect'), 'Apple'); ?></p>
            <p>
                <a href="https://nextendweb.com/nextend-social-login-docs/facebook-api-changes/#how-to-add-ssl-to-wordpress"><?php _e("How to get SSL for my WordPress site?", 'nextend-facebook-connect'); ?></a>
            </p>
        </div>
    <?php else: ?>
        <div class="nsl-admin-getting-started">
            <h2 class="title"><?php _e('Getting Started', 'nextend-facebook-connect'); ?></h2>

            <p><?php printf(__('To allow your visitors to log in with their %1$s account, first you must create an %1$s App. The following guide will help you through the %1$s App creation process. After you have created your %1$s App, head over to "Settings" and configure the given "%2$s", "%3$s", "%4$s" and "%5$s" according to your %1$s App.', 'nextend-facebook-connect'), "Apple", "Private Key ID", "Private Key", "Team Identifier", "Service Identifier"); ?></p>

            <p><?php do_action('nsl_getting_started_warnings', $provider, $lastUpdated); ?></p>

            <h2 class="title"><?php printf(_x('Create %s', 'App creation', 'nextend-facebook-connect'), 'Apple App'); ?></h2>

            <ol>
                <li><?php _e('Make sure you have an <strong>active subscription for the <a href="https://developer.apple.com/programs/" target="_blank">Apple Developer Program</a></strong>, as that is necessary for both creating and maintaining an Apple App!', 'nextend-facebook-connect'); ?></li>
                <li><?php _e('Make sure your site have <b>SSL</b>, since <b>Apple only allows HTTPS urls</b>!', 'nextend-facebook-connect'); ?></li>
                <li><?php printf(__('Navigate to %s', 'nextend-facebook-connect'), '<a href="https://developer.apple.com/account/resources/identifiers/list" target="_blank">https://developer.apple.com/account/resources/identifiers/list</a>'); ?></li>
                <li><?php printf(__('Log in with your %s credentials if you are not logged in.', 'nextend-facebook-connect'), 'Apple Developer'); ?></li>
            </ol>

            <h3><?php _e('1.) Create the associated App:', 'nextend-facebook-connect'); ?></h3>
            <ol>
                <li><?php printf(__('Click the <b>blue + icon</b> next to %1$s, then select the <b>%2$s</b> option and click the "Continue" button.', 'nextend-facebook-connect'), 'Identifiers', 'App IDs'); ?></li>
                <li><?php printf(__('Choose the "<strong>%1$s</strong>" option as type and press the "<b>Continue</b>" button.', 'nextend-facebook-connect'), 'App'); ?></li>
                <li><?php _e('Enter a "<b>Description</b>"', 'nextend-facebook-connect'); ?></li>
                <li><?php printf(__('At the "<b>Bundle ID</b>" field select the "<b>Explicit</b>" option and enter your domain name in reverse-domain name style, with the name of the app at its end: <b>%s.nslapp</b>', 'nextend-facebook-connect'), $reversed_domain); ?></li>
                <li><?php _e('Under the "<b>Capabilities</b>" section, tick the "<b>Sign In with Apple</b>" option.', 'nextend-facebook-connect'); ?></li>
                <li><?php _e('Scroll up and press the "<b>Continue</b>" button and then the "<b>Register</b>" button.', 'nextend-facebook-connect'); ?></li>
            </ol>

            <h3><?php _e('2.) Create the Key:', 'nextend-facebook-connect'); ?></h3>
            <ol>
                <li><?php printf(__('On the left hand side, click on the "<b>%s</b>" tab.', 'nextend-facebook-connect'), '<a href="https://developer.apple.com/account/resources/authkeys/list" target="_blank">Keys</a>'); ?></li>
                <li><?php printf(__('Click the <b>blue + icon</b> next to %1$s heading.', 'nextend-facebook-connect'), 'Keys'); ?></li>
                <li><?php _e('Enter a name in the <b>Key Name</b> field.', 'nextend-facebook-connect'); ?></li>
                <li><?php _e('Tick the "<b>Sign In with Apple</b>" option, then click on "<b>Configure</b>".', 'nextend-facebook-connect'); ?></li>
                <li><?php _e('If you have multiple Apps, then at the "<b>Choose a Primary App ID</b>" field <b>select the App what you just created</b>, then click "<b>Save</b>".', 'nextend-facebook-connect'); ?></li>
                <li><?php _e('Finally press the "<b>Continue</b>" button and then the "<b>Register</b>" button.', 'nextend-facebook-connect'); ?></li>
                <li><?php _e('<b>Don\'t download the key yet!</b>', 'nextend-facebook-connect'); ?></li>
            </ol>

            <h3><?php _e('3.) Create the Service:', 'nextend-facebook-connect'); ?></h3>
            <ol>
                <li><?php printf(__('Go to the "<b>%1$s</b>" section, what you will find within the "%2$s" tab.', 'nextend-facebook-connect'), '<a href="https://developer.apple.com/account/resources/identifiers/list/serviceId" target="_blank">Services IDs</a>', "Identifiers"); ?></li>
                <li><?php printf(__('Click the <b>blue + icon</b> next to %1$s, then select the "<b>%2$s</b>" option and click the "<b>Continue</b>" button.', 'nextend-facebook-connect'), 'Identifiers', 'Services IDs'); ?></li>
                <li><?php _e('Enter a "<b>Description</b>".', 'nextend-facebook-connect'); ?></li>
                <li><?php printf(__('At the "<b>Identifier</b>" field enter your domain name in reverse-domain name style, with the name of the client at its end: <b>%s.nslclient</b>', 'nextend-facebook-connect'), $reversed_domain); ?>
                    <ul>

                        <li><?php _e('<b>Note:</b> This will also be used as Service Identifier later!', 'nextend-facebook-connect'); ?></li>
                    </ul>
                </li>
                <li><?php _e('Press the "<b>Continue</b>" button and then the "<b>Register</b>" button.', 'nextend-facebook-connect'); ?></li>
                <li><?php printf(__('In the "<b>%1$s</b>" section, click the service you just created.', 'nextend-facebook-connect'), '<a href="https://developer.apple.com/account/resources/identifiers/list/serviceId" target="_blank">Services IDs</a>'); ?></li>


                <li><?php _e('Tick the "<b>Sign In with Apple</b>" option and click the "<b>Configure</b>" button next to it.', 'nextend-facebook-connect'); ?>
                    <ol>
                        <li><?php _e('If you have multiple Apps, then at the "Primary App ID" field select the App what you just created.', 'nextend-facebook-connect'); ?></li>
                        <li><?php printf(__('Fill the "<b>Domains and Subdomains</b>" field with your domain name probably: <b>%s</b>', 'nextend-facebook-connect'), $clean_domain); ?></li>
                        <li><?php
                            $loginUrls = $provider->getAllRedirectUrisForAppCreation();
                            printf(__('Add the following URL to the %s field:', 'nextend-facebook-connect'), '"<b>Return URLs</b>"');
                            echo "<ul>";
                            foreach ($loginUrls as $loginUrl) {
                                echo "<li><strong>" . $loginUrl . "</strong></li>";
                            }
                            echo "</ul>";
                            ?>
                        </li>
                    </ol>
                </li>
                <li><?php _e('Click the "<b>Next</b>" button then press the "<b>Done</b>" button.', 'nextend-facebook-connect'); ?></li>
                <li><?php _e('Finally press the "<b>Continue</b>" button and then the "<b>Save</b>" button.', 'nextend-facebook-connect'); ?></li>
            </ol>

            <h3><?php _e('4.) Configure Nextend Social Login with your credentials:', 'nextend-facebook-connect') ?></h3>
            <ol>
                <li><?php _e('Go to Nextend Social Login > Providers > Apple > Settings tab.', 'nextend-facebook-connect') ?></li>
                <li><?php _e('<strong><u>Private Key ID:</u></strong>', 'nextend-facebook-connect') ?>
                    <ol>
                        <li><?php printf(__('Navigate to: <b>%s</b>', 'nextend-facebook-connect'), '<a href="https://developer.apple.com/account/resources/authkeys/list" target="_blank">https://developer.apple.com/account/resources/authkeys/list</a>'); ?></li>
                        <li><?php _e('Click on the <b>name of your Key</b>.', 'nextend-facebook-connect') ?></li>
                        <li><?php _e('You will find your "<b>Private Key ID</b>" under "<b>Key ID</b>".', 'nextend-facebook-connect') ?></li>
                    </ol>
                </li>
                <li><?php _e('<strong><u>Private Key:</u></strong>', 'nextend-facebook-connect') ?>
                    <ol>
                        <li><?php _e('Click the "<b>Download</b>" button to download the key file. <u>Once this file is downloaded, it will no longer be available, so <b>make sure you keep this file safe</b>!</u> ', 'nextend-facebook-connect') ?></li>
                        <li><?php _e('<b>Open the downloaded file</b> with a text editor, like Notepad, <b><u>copy all of its contents</u></b> and <b>paste it</b> into the "<b>Private Key</b>" field of Nextend Social Login.', 'nextend-facebook-connect') ?></li>
                    </ol>
                </li>
                <li><?php _e('<strong><u>Team Identifier:</u></strong>', 'nextend-facebook-connect') ?>
                    <ol>
                        <li><?php _e('A 10 character long identifier, what you can find on the <b>top-right corner, just under your name</b>.', 'nextend-facebook-connect') ?></li>
                    </ol>
                </li>
                <li><?php _e('<strong><u>Service Identifier:</u></strong>', 'nextend-facebook-connect') ?>
                    <ol>
                        <li><?php printf(__('Navigate to: <b>%s</b>', 'nextend-facebook-connect'), '<a href="https://developer.apple.com/account/resources/identifiers/list/serviceId" target="_blank">https://developer.apple.com/account/resources/identifiers/list/serviceId</a>'); ?></li>
                        <li><?php printf(__('You will find it under the "<b>IDENTIFIER</b>" column. If you configured the service according to the suggestions, it will probably end to .nslclient e.g.: <b>%s.nslclient</b>', 'nextend-facebook-connect'), $reversed_domain); ?></li>
                    </ol>
                </li>
                <li><?php _e('Once you filled up all the fields, click on the "<b>Generate Token</b>" button.', 'nextend-facebook-connect') ?></li>
                <li><?php _e('Finally <b>verify the settings</b> and <b>enable the provider</b>!', 'nextend-facebook-connect') ?></li>
                <li><?php _e('When you need to change your credentials for some reason, then you must delete the token, copy the new credentials and generate a new token!', 'nextend-facebook-connect') ?></li>
            </ol>

            <a href="<?php echo $this->getUrl('settings'); ?>"
               class="button button-primary"><?php printf(__('I am done setting up my %s', 'nextend-facebook-connect'), 'Apple App'); ?></a>
        </div>

    <?php endif; ?>

</div>