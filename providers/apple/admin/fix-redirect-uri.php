<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

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
<ol>
    <li><?php printf(__('Navigate to %s', 'nextend-facebook-connect'), '<a href="https://developer.apple.com/account/resources/identifiers/list/serviceId" target="_blank">https://developer.apple.com/account/resources/identifiers/list/serviceId</a>'); ?></li>
    <li><?php printf(__('Log in with your %s credentials if you are not logged in', 'nextend-facebook-connect'), 'Apple'); ?></li>
    <li><?php _e('Click on the name of your service.', 'nextend-facebook-connect') ?></li>
    <li><?php _e('Click the "<b>Configure</b>" button next to "<b>Sign In with Apple</b>".', 'nextend-facebook-connect') ?></li>
    <li><?php printf(__('Click the <b>blue + icon</b> next to %1$s heading.', 'nextend-facebook-connect'), 'Website URLs'); ?></li>
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
    <li><?php _e('Finally press "<b>Next</b>" then "<b>Done</b>" and finally click on the "<b>Continue</b>" and the "<b>Save</b>" button!', 'nextend-facebook-connect') ?></li>
</ol>