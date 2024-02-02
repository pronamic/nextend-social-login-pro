<?php
/*
Plugin Name: Nextend Social Login Pro Addon
Plugin URI: https://nextendweb.com/social-login/
Description: Pro Addon for Nextend Social Login.
Version: 3.1.11
Requires PHP: 7.0
Requires at least: 4.9
Author: Nextendweb
Author URI: https://nextendweb.com/social-login/
License: GPL3
*/

if (!defined('NSL_PRO_PATH_PLUGIN')) {
    define('NSL_PRO_PATH_PLUGIN', __FILE__);
}

if (!defined('NSL_PRO_PATH')) {
    define('NSL_PRO_PATH', dirname(NSL_PRO_PATH_PLUGIN));
}

class NextendSocialLoginPRO {

    public static $version = '3.1.11';

    public static $nslMinVersion = '3.1.11';

    /**
     * @var NextendSocialProvider[]
     */
    private static $enabledProvidersDB = array();

    public static function pro($isPro) {
        if (NextendSocialLogin::hasLicense()) {
            $isPro = true;
        }

        return $isPro;
    }

    public static function init() {

        require_once NSL_PRO_PATH . '/src/autoloader.php';

        add_action('plugins_loaded', 'NextendSocialLoginPRO::plugins_loaded');
        add_action('nsl_start', 'NextendSocialLoginPRO::start');

        register_activation_hook(NSL_PRO_PATH . DIRECTORY_SEPARATOR . 'nextend-social-login-pro.php', array(
            self::class,
            'register_cron_weekly'
        ));

        register_deactivation_hook(NSL_PRO_PATH . DIRECTORY_SEPARATOR . 'nextend-social-login-pro.php', array(
            self::class,
            'deregister_cron_weekly'
        ));

    }


    public static function register_cron_weekly() {
        if (!wp_next_scheduled('nslpro_weekly_cron')) {
            wp_schedule_event(time(), 'weekly', 'nslpro_weekly_cron');
        }
    }

    public static function deregister_cron_weekly() {
        wp_clear_scheduled_hook('nslpro_weekly_cron');
    }

    public static function plugins_loaded() {
        if (!defined('NSL_PATH')) {
            add_action('admin_notices', array(
                'NextendSocialLoginPRO',
                'admin_notices_nsl_not_installed'
            ));
        } else {
            $lastVersion = get_option('nslpro-version');
            if ($lastVersion != self::$version && version_compare(NextendSocialLogin::$version, self::$nslMinVersion, '>=')) {
                if (!wp_next_scheduled('nslpro_weekly_cron')) {
                    wp_schedule_event(time(), 'weekly', 'nslpro_weekly_cron');
                }

                if (!empty($lastVersion)) {
                    /**
                     * Before 3.0.26 WooCommerce email integration only worked with "Registration notification sent to" was set to "User" or "User and Admin"
                     * Those users who had it on "WordPress default" may sent custom notifications, so we shouldn't use the WooCommerce registration notification
                     * if a user upgraded from 3.0.25 or earlier and the user used the default WordPress notifications.
                     */
                    if (version_compare($lastVersion, '3.0.25', '<=') && NextendSocialLogin::$settings->get('registration_notification_notify') == '0') {
                        NextendSocialLogin::$settings->update(array(
                            'woocoommerce_registration_email_template' => 'default'
                        ));
                    }

                    /**
                     * Before 3.1.2 the Microsoft provider used the "consumers" tenant endpoint only.
                     * In 3.1.2 we introduced additional endpoints and the "common" will be used by default.
                     * For users with configured Microsoft providers we should continue using the "consumers" tenant.
                     */
                    if (version_compare($lastVersion, '3.1.1', '<=') && isset(NextendSocialLogin::$providers['microsoft']) && isset(NextendSocialLogin::$providers['microsoft']->settings) && NextendSocialLogin::$providers['microsoft']->settings->get('tested')) {
                        NextendSocialLogin::$providers['microsoft']->settings->update(array('tenant' => 'consumers'));
                    }
                }

                update_option('nslpro-version', self::$version, true);
            }
        }

    }

    public static function admin_notices_nsl_not_installed() {

        echo '<div class="error"><p>';
        printf(__('Please install and activate %1$s to use the %2$s', 'nextend-facebook-connect'), "Nextend Social Login", "Pro Addon");

        $installed_plugin = get_plugins('/nextend-facebook-connect');
        if (isset($installed_plugin['nextend-facebook-connect.php'])) {
            $file = 'nextend-facebook-connect/nextend-facebook-connect.php';

            $button_text  = __('Activate', 'nextend-facebook-connect');
            $activate_url = add_query_arg(array(
                '_wpnonce' => wp_create_nonce('activate-plugin_' . $file),
                'action'   => 'activate',
                'plugin'   => $file,
            ), network_admin_url('plugins.php'));

            if (is_network_admin()) {
                $button_text  = __('Network Activate', 'nextend-facebook-connect');
                $activate_url = add_query_arg(array('networkwide' => 1), $activate_url);
            }
            echo ' <a href="' . esc_url($activate_url) . '">' . $button_text . '</a>';
        } else {
            $slug         = 'nextend-facebook-connect';
            $activate_url = add_query_arg(array(
                '_wpnonce' => wp_create_nonce('install-plugin_' . $slug),
                'action'   => 'install-plugin',
                'plugin'   => $slug,
            ), network_admin_url('update.php'));

            echo ' <a href="' . esc_url($activate_url) . '">' . __('Install now!', 'nextend-facebook-connect') . '</a>';
        }
        echo '</p></div>';
    }

    public static function start() {
        if (NextendSocialLogin::checkVersion()) {
            $isAdminArea = defined('WP_ADMIN') && WP_ADMIN;

            if ((!$isAdminArea && NextendSocialLogin::hasLicense(false)) || ($isAdminArea && NextendSocialLogin::hasLicense())) {
                add_action('nsl_provider_init', 'NextendSocialLoginPRO::provider_init');
                add_action('nsl_add_providers', 'NextendSocialLoginPRO::addProviders');
            }
        }
    }


    /**
     * @param NextendSocialProvider $provider
     */
    public static function provider_init($provider) {
        require_once(dirname(__FILE__) . '/class-provider-extension.php');
        require_once(dirname(__FILE__) . '/class-provider-extension-with-syncdata.php');

        $extensionPath = dirname(__FILE__) . '/provider-extensions';
        if (file_exists($extensionPath . '/' . $provider->getId() . '.php')) {
            require_once($extensionPath . '/' . $provider->getId() . '.php');
            $className = 'NextendSocialLoginPROProviderExtension' . $provider->getId();
            new $className($provider);
        } else {
            new NextendSocialLoginPROProviderExtension($provider);
        }
    }

    public static function addProviders() {
        add_filter('nsl-pro', 'NextendSocialLoginPRO::pro');

        $providersPath = dirname(__FILE__) . '/providers/';

        $providers = array_diff(scandir($providersPath), array(
            '..',
            '.'
        ));

        foreach ($providers as $provider) {
            if (file_exists($providersPath . $provider . '/' . $provider . '.php')) {
                require_once($providersPath . $provider . '/' . $provider . '.php');
            }
        }

        add_action('nsl_providers_loaded', 'NextendSocialLoginPRO::providersLoaded');

        add_action('nsl_init', 'NextendSocialLoginPRO::nsl_init');
    }


    public static function providersLoaded() {

        if (count(NextendSocialLogin::$enabledProviders)) {

            if (!is_user_logged_in()) {

                // Stack Overflow and WordPress.com button labels do not fit in the default 320px wide form.
                if (NextendSocialLogin::isProviderEnabled('stackoverflow') || NextendSocialLogin::isProviderEnabled('wordpress')) {
                    add_action('login_enqueue_scripts', function() {
                        wp_add_inline_style('login', '#login {width: 100%; max-width: 400px;}');
                    });
                }

                if (!defined('NSL_DISABLE_CUSTOM_ACTIONS')) {
                    $customActions = NextendSocialLogin::$settings->get('custom_actions');
                    if (!empty($customActions)) {
                        $customActionsArray = preg_split('/\r\n|\r|\n|,/', $customActions);
                        foreach ($customActionsArray as $customAction) {
                            add_action($customAction, 'NextendSocialLoginPRO::custom_actions_button_show');
                        }
                    }
                }

                if (NextendSocialLogin::$settings->get('comment_login_button') == 'show') {
                    add_action('comment_form_must_log_in_after', 'NextendSocialLoginPRO::comment_form_must_log_in_after');

                    //Jetpack can override the default WordPress comment form, so we need to hook our buttons later.
                    if (class_exists('Jetpack', false) && Jetpack::is_module_active('comments')) {
                        add_action('comment_form_after', 'NextendSocialLoginPRO::comment_form_must_log_in_after');
                    }
                }

                $buddypress_register_button = NextendSocialLogin::$settings->get('buddypress_register_button');
                if (!empty($buddypress_register_button)) {
                    $action = 'bp_before_account_details_fields';
                    if ($buddypress_register_button == 'bp_before_register_page') {
                        $action = 'bp_before_register_page';
                    } else if ($buddypress_register_button == 'bp_after_register_page') {
                        $action = 'bp_after_register_page';
                    }
                    add_action($action, 'NextendSocialLoginPRO::bp_register_form');
                }

                //BuddyPress Login Widget
                switch (NextendSocialLogin::$settings->get('buddypress_login')) {
                    case 'show':
                        add_action('bp_login_widget_form', 'NextendSocialLoginPRO::buddypress_login');
                        break;
                }


                switch (NextendSocialLogin::$settings->get('woocommerce_login')) {
                    case 'before':
                        add_action('woocommerce_login_form_start', 'NextendSocialLoginPRO::woocommerce_login_form_start');
                        break;
                    case 'after':
                        add_action('woocommerce_login_form_end', 'NextendSocialLoginPRO::woocommerce_login_form_end');
                        break;
                }

                switch (NextendSocialLogin::$settings->get('woocommerce_register')) {
                    case 'before':
                        add_action('woocommerce_register_form_start', 'NextendSocialLoginPRO::woocommerce_register_form_start');
                        break;
                    case 'after':
                        add_action('woocommerce_register_form_end', 'NextendSocialLoginPRO::woocommerce_register_form_end');
                        break;
                }

                switch (NextendSocialLogin::$settings->get('woocommerce_billing')) {
                    case 'before':
                        add_action('woocommerce_before_checkout_billing_form', 'NextendSocialLoginPRO::woocommerce_before_checkout_billing_form');
                        break;
                    case 'after':
                        add_action('woocommerce_after_checkout_billing_form', 'NextendSocialLoginPRO::woocommerce_after_checkout_billing_form');
                        break;
                    case 'before-checkout-registration':
                        add_action('woocommerce_before_checkout_registration_form', 'NextendSocialLoginPRO::woocommerce_before_checkout_billing_form');
                        break;
                    case 'after-checkout-registration':
                        add_action('woocommerce_after_checkout_registration_form', 'NextendSocialLoginPRO::woocommerce_after_checkout_billing_form');
                        break;
                }

                /**
                 * Integration for "Checkout for WooCommerce" plugin:
                 * When there is a shipping method enabled in WooCommerce, then the WooCommerce actions will be triggered at different positions.
                 * We need to render the social buttons in the Customer information form.
                 */
                if (defined('CFW_MAIN_FILE')) {
                    switch (NextendSocialLogin::$settings->get('woocommerce_cfw')) {
                        case 'show':
                            add_action('cfw_checkout_after_login', 'NextendSocialLoginPRO::woocommerce_cfw_form');
                            break;
                    }
                }


                switch (NextendSocialLogin::$settings->get('memberpress_login')) {
                    case 'before':
                        add_action('mepr-login-form-before-submit', 'NextendSocialLoginPRO::memberpress_login');
                        break;
                }

                switch (NextendSocialLogin::$settings->get('memberpress_signup')) {
                    case 'before':
                        add_action('mepr-checkout-before-submit', 'NextendSocialLoginPRO::memberpress_signup');
                        break;
                }

                add_action('userpro_inside_form_register', 'NextendSocialLoginPRO::userpro_mark_register');
                add_action('userpro_social_connect_buttons', 'NextendSocialLoginPRO::userpro_login_or_register');

                //Ultimate Member
                switch (NextendSocialLogin::$settings->get('ultimatemember_login')) {
                    case 'after':
                        add_action('um_after_login_fields', 'NextendSocialLoginPRO::ultimatemember_login', 2000);
                        break;
                }
                switch (NextendSocialLogin::$settings->get('ultimatemember_register')) {
                    case 'after':
                        add_action('um_after_register_fields', 'NextendSocialLoginPRO::ultimatemember_register', 2000);
                        break;
                }

                //Easy Digital Downloads
                switch (NextendSocialLogin::$settings->get('edd_login')) {
                    case 'before':
                        add_action('edd_login_fields_before', 'NextendSocialLoginPRO::edd_login');
                        break;
                    case 'after':
                        add_action('edd_login_fields_after', 'NextendSocialLoginPRO::edd_login');
                        break;
                }
                switch (NextendSocialLogin::$settings->get('edd_register')) {
                    case 'top':
                        add_action('edd_register_form_fields_top', 'NextendSocialLoginPRO::edd_register');
                        break;
                    case 'before':
                        add_action('edd_register_form_fields_before', 'NextendSocialLoginPRO::edd_register');
                        break;
                    case 'before_submit':
                        add_action('edd_register_form_fields_before_submit', 'NextendSocialLoginPRO::edd_register');
                        break;
                    case 'after':
                        add_action('edd_register_form_fields_after', 'NextendSocialLoginPRO::edd_register');
                        break;
                }
                switch (NextendSocialLogin::$settings->get('edd_checkout')) {
                    case 'items_before':
                        add_action('edd_cart_items_before', 'NextendSocialLoginPRO::edd_checkout');
                        break;
                    case 'before_purchase_form':
                        add_action('edd_before_purchase_form', 'NextendSocialLoginPRO::edd_checkout');
                        break;
                    case 'form_top':
                        add_action('edd_checkout_form_top', 'NextendSocialLoginPRO::edd_checkout');
                        break;
                    case 'before_email':
                        add_action('edd_purchase_form_before_email', 'NextendSocialLoginPRO::edd_checkout');
                        break;
                    case 'before_submit':
                        add_action('edd_purchase_form_before_submit', 'NextendSocialLoginPRO::edd_checkout');
                        break;
                    case 'form_after':
                        add_action('edd_purchase_form_after_submit', 'NextendSocialLoginPRO::edd_checkout');
                        break;
                }

            } else {
                switch (NextendSocialLogin::$settings->get('woocommerce_account_details')) {
                    case 'before':
                        add_action('woocommerce_edit_account_form_start', 'NextendSocialLoginPRO::woocommerce_edit_account_form_start');
                        break;
                    case 'after':
                        add_action('woocommerce_edit_account_form_end', 'NextendSocialLoginPRO::woocommerce_edit_account_form_end');
                        break;
                }
            }


            switch (NextendSocialLogin::$settings->get('memberpress_account_details')) {
                case 'after':
                    add_action('mepr_account_home', 'NextendSocialLoginPRO::memberpress_account_home');
                    break;
            }

            switch (NextendSocialLogin::$settings->get('ultimatemember_account_details')) {
                case 'after':
                    add_action('um_after_account_general_button', 'NextendSocialLoginPRO::ultimatemember_account_home');
                    break;
            }
        }

        if (NextendSocialLogin::$settings->get('registration_notification_notify') != '0') {
            add_action('nsl_pre_register_new_user', 'NextendSocialLoginPRO::pre_register_new_user');
        } else {
            add_action('nsl_pre_register_new_user', 'NextendSocialLoginPRO::pre_register_new_user_default');
        }

        add_filter('nsl_update_settings_validate_nextend_social_login', 'NextendSocialLoginPRO::validateSettings', 10, 2);
    }

    public static function nsl_init() {

        if (NextendSocialLogin::$settings->get('allow_unlink') == 0) {
            add_filter('nsl_allow_unlink', '__return_false');
        }

        if (!empty(NextendSocialLogin::$settings->get('admin_bar_roles')) && is_user_logged_in()) {
            add_action('after_setup_theme', 'NextendSocialLoginPRO::disable_adminbar_roles');
        }

        if (NextendSocialLogin::$settings->get('show_linked_providers') == '1') {
            add_filter('manage_users_columns', array(
                'NextendSocialLoginPRO',
                'linked_providers_column'
            ));
            add_filter('manage_users_custom_column', array(
                'NextendSocialLoginPRO',
                'linked_providers_row'
            ), 10, 3);
        }

        if (NextendSocialLogin::$settings->get('buddypress_registration_integration') == '1') {
            add_filter('nsl_register_external_insert_user', array(
                'NextendSocialLoginPRO',
                'integrationBuddyPressLoginRestriction'
            ), 10, 3);
        }

    }

    public static function validateSettings($newData, $postedData) {

        foreach ($postedData as $key => $value) {
            switch ($key) {
                case 'target':
                case 'login_form_button_style':
                case 'login_form_layout':
                case 'embedded_login_form_button_style':
                case 'embedded_login_form_layout':
                case 'comment_login_button':
                case 'comment_button_align':
                case 'comment_button_style':
                case 'buddypress_register_button':
                case 'buddypress_register_button_align':
                case 'buddypress_register_button_style':
                case 'buddypress_register_form_layout':
                case 'buddypress_login':
                case 'buddypress_login_form_layout':
                case 'buddypress_login_button_style':
                case 'buddypress_sidebar_login':
                case 'buddypress_social_accounts_tab':
                case 'custom_actions_button_style':
                case 'custom_actions_button_layout':
                case 'custom_actions_button_align':
                case 'woocommerce_login':
                case 'woocommerce_login_form_layout':
                case 'woocommerce_register':
                case 'woocommerce_register_form_layout':
                case 'woocommerce_billing':
                case 'woocommerce_cfw':
                case 'woocommerce_cfw_layout':
                case 'woocommerce_billing_form_layout':
                case 'woocommerce_account_details':
                case 'woocoommerce_form_button_style':
                case 'woocoommerce_form_button_align':
                case 'woocoommerce_registration_email_template':
                case 'registration_notification_notify':
                case 'memberpress_login':
                case 'memberpress_form_button_align':
                case 'memberpress_login_form_button_style':
                case 'memberpress_login_form_layout':
                case 'memberpress_signup':
                case 'memberpress_signup_form_button_style':
                case 'memberpress_signup_form_layout':
                case 'memberpress_account_details':
                case 'userpro_show_login_form':
                case 'userpro_show_register_form':
                case 'userpro_login_form_button_style':
                case 'userpro_login_form_layout':
                case 'userpro_register_form_button_style':
                case 'userpro_register_form_layout':
                case 'userpro_form_button_align':
                case 'ultimatemember_login':
                case 'ultimatemember_login_form_button_style':
                case 'ultimatemember_login_form_layout':
                case 'ultimatemember_register':
                case 'ultimatemember_register_form_button_style':
                case 'ultimatemember_register_form_layout':
                case 'ultimatemember_form_button_align':
                case 'ultimatemember_account_details':
                case 'edd_login':
                case 'edd_login_form_button_style':
                case 'edd_login_form_layout':
                case 'edd_register':
                case 'edd_register_form_button_style':
                case 'edd_register_form_layout':
                case 'edd_checkout':
                case 'edd_checkout_form_button_style':
                case 'edd_checkout_form_layout':
                case 'edd_form_button_align':
                    $newData[$key] = sanitize_text_field($value);
                    break;
                case 'custom_actions':
                    $newData[$key] = sanitize_textarea_field($postedData[$key]);
                    break;
                case 'buddypress_registration_integration':
                case 'allow_unlink':
                case 'show_linked_providers':
                    if ($value == '0') {
                        $newData[$key] = 0;
                    } else {
                        $newData[$key] = 1;
                    }
                    break;
                case 'admin_bar_roles':
                    $newData[$key] = array_filter((array)$value);
                    break;
            }
        }

        return $newData;
    }

    public static function custom_actions_button_show() {
        if (did_action('init')) {
            $index = NextendSocialLogin::$counter++;

            $containerID = 'nsl-custom-login-form-' . $index;

            echo '<div id="' . $containerID . '">' . NextendSocialLogin::renderButtonsWithContainer(NextendSocialLogin::$settings->get('custom_actions_button_style'), false, false, false, NextendSocialLogin::$settings->get('custom_actions_button_align')) . '</div>';

            $template = NextendSocialLogin::get_template_part('custom-actions/' . sanitize_file_name(NextendSocialLogin::$settings->get('custom_actions_button_layout')) . '.php');
            if (!empty($template) && file_exists($template)) {
                include($template);
            }
        }
    }

    public static function comment_form_must_log_in_after() {
        $template = NextendSocialLogin::get_template_part('comment/default.php');
        if (!empty($template) && file_exists($template)) {

            $buttons = NextendSocialLogin::renderButtonsWithContainer(NextendSocialLogin::$settings->get('comment_button_style'), false, false, false, NextendSocialLogin::$settings->get('comment_button_align'));

            include($template);
        }
    }

    public static function bp_register_form() {

        $index = NextendSocialLogin::$counter++;

        $containerID = 'nsl-custom-login-form-' . $index;

        echo '<div id="' . $containerID . '">' . NextendSocialLogin::renderButtonsWithContainer(NextendSocialLogin::$settings->get('buddypress_register_button_style'), false, false, false, NextendSocialLogin::$settings->get('buddypress_register_button_align'), 'register') . '</div>';

        $template = NextendSocialLogin::get_template_part('buddypress/register/' . sanitize_file_name(NextendSocialLogin::$settings->get('buddypress_register_form_layout')) . '.php');
        if (!empty($template) && file_exists($template)) {
            include($template);
        }
    }

    /**
     * @param string $action
     * @param string $labelType
     */
    public static function woocommerceApplySocialButtonLayout($action, $labelType = 'login') {
        switch ($action) {
            case 'login':
                $template = NextendSocialLogin::get_template_part('woocommerce/login/' . sanitize_file_name(NextendSocialLogin::$settings->get('woocommerce_login_form_layout')) . '.php');
                break;
            case 'register':
                $template = NextendSocialLogin::get_template_part('woocommerce/register/' . sanitize_file_name(NextendSocialLogin::$settings->get('woocommerce_register_form_layout')) . '.php');
                break;
            case 'billing':
                $layout = NextendSocialLogin::$settings->get('woocommerce_billing_form_layout');
                if ($layout === 'default-separator') {
                    $position = NextendSocialLogin::$settings->get('woocommerce_billing');
                    if ($position === 'before' || $position === 'before-checkout-registration') {
                        $template = NextendSocialLogin::get_template_part('woocommerce/billing/' . sanitize_file_name($layout . '-before') . '.php');
                    } else if ($position === 'after' || $position === 'after-checkout-registration') {
                        $template = NextendSocialLogin::get_template_part('woocommerce/billing/' . sanitize_file_name($layout . '-after') . '.php');
                    }
                } else {
                    $template = NextendSocialLogin::get_template_part('woocommerce/billing/' . sanitize_file_name($layout) . '.php');
                }
                break;
        }
        if (!empty($template) && file_exists($template)) {
            $index = NextendSocialLogin::$counter++;

            $containerID = 'nsl-custom-login-form-' . $index;

            echo '<div id="' . $containerID . '">' . NextendSocialLogin::renderButtonsWithContainer(NextendSocialLogin::$settings->get('woocoommerce_form_button_style'), false, false, false, NextendSocialLogin::$settings->get('woocoommerce_form_button_align'), $labelType) . '</div>';

            include($template);
        }
    }

    private static function woocommerceLogin($action, $position) {

        if (NextendSocialLogin::$settings->get('woocommerce_login_form_layout') == 'default') {
            $template = NextendSocialLogin::get_template_part('woocommerce/' . $action . '-' . $position . '.php');
            if (!empty($template) && file_exists($template)) {

                $buttons = NextendSocialLogin::renderButtonsWithContainer(NextendSocialLogin::$settings->get('woocoommerce_form_button_style'), false, false, false, NextendSocialLogin::$settings->get('woocoommerce_form_button_align'));

                include($template);
            }
        } else {
            self::woocommerceApplySocialButtonLayout($action);
        }
    }

    private static function woocommerceRegister($action, $position) {

        if (NextendSocialLogin::$settings->get('woocommerce_register_form_layout') == 'default') {
            $template = NextendSocialLogin::get_template_part('woocommerce/' . $action . '-' . $position . '.php');
            if (!empty($template) && file_exists($template)) {

                $buttons = NextendSocialLogin::renderButtonsWithContainer(NextendSocialLogin::$settings->get('woocoommerce_form_button_style'), false, false, false, NextendSocialLogin::$settings->get('woocoommerce_form_button_align'), 'register');

                include($template);
            }
        } else {
            self::woocommerceApplySocialButtonLayout($action, 'register');
        }
    }

    private static function woocommerceBilling($action, $position) {

        if (NextendSocialLogin::$settings->get('woocommerce_billing_form_layout') == 'default') {
            $template = NextendSocialLogin::get_template_part('woocommerce/' . $action . '-' . $position . '.php');
            if (!empty($template) && file_exists($template)) {

                $buttons = NextendSocialLogin::renderButtonsWithContainer(NextendSocialLogin::$settings->get('woocoommerce_form_button_style'), false, false, false, NextendSocialLogin::$settings->get('woocoommerce_form_button_align'), 'register');

                include($template);
            }
        } else {
            self::woocommerceApplySocialButtonLayout($action, 'register');
        }
    }

    public static function woocommerce_login_form_start() {
        //woocommerce/login-start.php
        self::woocommerceLogin('login', 'start');
    }

    public static function woocommerce_login_form_end() {
        //woocommerce/login-end.php
        self::woocommerceLogin('login', 'end');

    }

    public static function woocommerce_register_form_start() {
        //woocommerce/register-start.php
        self::woocommerceRegister('register', 'start');

    }

    public static function woocommerce_register_form_end() {
        //woocommerce/register-end.php
        self::woocommerceRegister('register', 'end');

    }

    public static function woocommerce_before_checkout_billing_form() {
        //woocommerce/billing-before.php;
        self::woocommerceBilling('billing', 'before');
    }

    public static function woocommerce_after_checkout_billing_form() {
        //woocommerce/billing-after.php;
        self::woocommerceBilling('billing', 'after');

    }

    public static function woocommerce_edit_account_form_start() {

        $template = NextendSocialLogin::get_template_part('woocommerce/edit-account-before.php');
        if (!empty($template) && file_exists($template)) {

            $buttons = NextendSocialLogin::renderLinkAndUnlinkButtons(false, true, true, NextendSocialLogin::$settings->get('woocoommerce_form_button_align'), false, NextendSocialLogin::$settings->get('woocoommerce_form_button_style'));

            include($template);
        }
    }

    public static function woocommerce_edit_account_form_end() {

        $template = NextendSocialLogin::get_template_part('woocommerce/edit-account-after.php');
        if (!empty($template) && file_exists($template)) {

            $buttons = NextendSocialLogin::renderLinkAndUnlinkButtons(false, true, true, NextendSocialLogin::$settings->get('woocoommerce_form_button_align'), false, NextendSocialLogin::$settings->get('woocoommerce_form_button_style'));

            include($template);
        }
    }

    public static function woocommerce_cfw_form() {
        $template = NextendSocialLogin::get_template_part('woocommerce/checkout-for-woocommerce/' . sanitize_file_name(NextendSocialLogin::$settings->get('woocommerce_cfw_layout')) . '.php');

        if (!empty($template) && file_exists($template)) {
            $index = NextendSocialLogin::$counter++;

            $containerID = 'nsl-custom-login-form-' . $index;

            echo '<div id="' . $containerID . '">' . NextendSocialLogin::renderButtonsWithContainer(NextendSocialLogin::$settings->get('woocoommerce_form_button_style'), false, false, false, NextendSocialLogin::$settings->get('woocoommerce_form_button_align')) . '</div>';

            include($template);
        }
    }

    public static function pre_register_new_user() {
        remove_action('register_new_user', 'wp_send_new_user_notifications');


        switch (NextendSocialLogin::$settings->get('registration_notification_notify')) {
            case 'both':
                add_action('register_new_user', 'NextendSocialLoginPRO::wp_send_new_user_notifications_both');
                break;
            case 'user':
                add_action('register_new_user', 'NextendSocialLoginPRO::wp_send_new_user_notifications_user');
                break;
            case 'admin':
                add_action('register_new_user', 'NextendSocialLoginPRO::wp_send_new_user_notifications_admin');
                break;
            case 'nobody':
                break;
        }
    }

    public static function pre_register_new_user_default() {
        if (class_exists('WooCommerce', false) && NextendSocialLogin::$settings->get('woocoommerce_registration_email_template') == 'woocommerce') {
            remove_action('register_new_user', 'wp_send_new_user_notifications');
            add_action('register_new_user', 'NextendSocialLoginPRO::woocommerce_send_new_user_notification');
        }
    }

    public static function wp_send_new_user_notifications_both($user_id) {

        self::wp_send_new_user_notifications_user($user_id);
        self::wp_send_new_user_notifications_admin($user_id);
    }

    public static function wp_send_new_user_notifications_user($user_id) {
        if (class_exists('WooCommerce', false) && NextendSocialLogin::$settings->get('woocoommerce_registration_email_template') == 'woocommerce') {
            self::woocommerce_send_new_user_notification($user_id);
        } else {
            wp_new_user_notification($user_id, null, 'user');
        }
    }

    public static function woocommerce_send_new_user_notification($user_id) {
        if (class_exists('WooCommerce', false)) {
            WooCommerce::instance()
                       ->mailer();

            if ('yes' === get_option('woocommerce_registration_generate_password')) {
                $generatedPassword = array_merge(array(
                    'user_pass' => wp_generate_password()
                ), apply_filters('woocommerce_new_customer_data', array()));

                wp_set_password($generatedPassword['user_pass'], $user_id);
                do_action('woocommerce_created_customer_notification', $user_id, $generatedPassword, true);
            } else {
                do_action('woocommerce_created_customer_notification', $user_id);
            }
        }
    }

    public static function wp_send_new_user_notifications_admin($user_id) {
        wp_new_user_notification($user_id, null, 'admin');
    }

    public static function memberpress_login() {

        $index = NextendSocialLogin::$counter++;

        $containerID = 'nsl-custom-login-form-' . $index;

        echo '<div id="' . $containerID . '">' . NextendSocialLogin::renderButtonsWithContainer(NextendSocialLogin::$settings->get('memberpress_login_form_button_style'), false, false, false, NextendSocialLogin::$settings->get('memberpress_form_button_align')) . '</div>';

        $template = NextendSocialLogin::get_template_part('memberpress/login/' . sanitize_file_name(NextendSocialLogin::$settings->get('memberpress_login_form_layout')) . '.php');
        if (!empty($template) && file_exists($template)) {
            include($template);
        }

    }

    public static function memberpress_signup() {

        $index = NextendSocialLogin::$counter++;

        $containerID = 'nsl-custom-login-form-' . $index;

        echo '<div id="' . $containerID . '">' . NextendSocialLogin::renderButtonsWithContainer(NextendSocialLogin::$settings->get('memberpress_signup_form_button_style'), false, false, false, NextendSocialLogin::$settings->get('memberpress_form_button_align'), 'register') . '</div>';

        $template = NextendSocialLogin::get_template_part('memberpress/sign-up/' . sanitize_file_name(NextendSocialLogin::$settings->get('memberpress_signup_form_layout')) . '.php');
        if (!empty($template) && file_exists($template)) {
            include($template);
        }
    }

    public static function memberpress_account_home() {

        $template = NextendSocialLogin::get_template_part('memberpress/account-home.php');
        if (!empty($template) && file_exists($template)) {

            $buttons = NextendSocialLogin::renderLinkAndUnlinkButtons(false, true, true, NextendSocialLogin::$settings->get('memberpress_form_button_align'), false, NextendSocialLogin::$settings->get('memberpress_login_form_button_style'));

            include($template);
        }
    }

    private static $userProIsRegister = false;

    public static function userpro_mark_register() {
        self::$userProIsRegister = true;
    }

    public static function userpro_login_or_register() {

        if (self::$userProIsRegister) {
            if (NextendSocialLogin::$settings->get('userpro_show_register_form') == 'show') {
                NextendSocialLoginPRO::userpro_register();
            }
            self::$userProIsRegister = false;
        } else {
            if (NextendSocialLogin::$settings->get('userpro_show_login_form') == 'show') {
                NextendSocialLoginPRO::userpro_login();
            }
        }
    }

    public static function userpro_login() {
        $index = NextendSocialLogin::$counter++;

        $containerID = 'nsl-custom-login-form-' . $index;

        echo '<div id="' . $containerID . '">' . NextendSocialLogin::renderButtonsWithContainer(NextendSocialLogin::$settings->get('userpro_login_form_button_style'), false, false, false, NextendSocialLogin::$settings->get('userpro_form_button_align')) . '</div>';

        $template = NextendSocialLogin::get_template_part('userpro/login/' . sanitize_file_name(NextendSocialLogin::$settings->get('userpro_login_form_layout')) . '.php');
        if (!empty($template) && file_exists($template)) {
            include($template);
        }

    }

    public static function userpro_register() {
        $index = NextendSocialLogin::$counter++;

        $containerID = 'nsl-custom-login-form-' . $index;

        echo '<div id="' . $containerID . '">' . NextendSocialLogin::renderButtonsWithContainer(NextendSocialLogin::$settings->get('userpro_register_form_button_style'), false, false, false, NextendSocialLogin::$settings->get('userpro_form_button_align'), 'register') . '</div>';

        $template = NextendSocialLogin::get_template_part('userpro/register/' . sanitize_file_name(NextendSocialLogin::$settings->get('userpro_register_form_layout')) . '.php');
        if (!empty($template) && file_exists($template)) {
            include($template);
        }
    }

    public static function ultimatemember_login() {

        $index = NextendSocialLogin::$counter++;

        $containerID = 'nsl-custom-login-form-' . $index;

        echo '<div id="' . $containerID . '">' . NextendSocialLogin::renderButtonsWithContainer(NextendSocialLogin::$settings->get('ultimatemember_login_form_button_style'), false, false, false, NextendSocialLogin::$settings->get('ultimatemember_form_button_align')) . '</div>';

        $template = NextendSocialLogin::get_template_part('ultimate-member/login/' . sanitize_file_name(NextendSocialLogin::$settings->get('ultimatemember_login_form_layout')) . '.php');
        if (!empty($template) && file_exists($template)) {
            include($template);
        }

    }

    public static function ultimatemember_register() {

        $index = NextendSocialLogin::$counter++;

        $containerID = 'nsl-custom-login-form-' . $index;

        echo '<div id="' . $containerID . '">' . NextendSocialLogin::renderButtonsWithContainer(NextendSocialLogin::$settings->get('ultimatemember_register_form_button_style'), false, false, false, NextendSocialLogin::$settings->get('ultimatemember_form_button_align'), 'register') . '</div>';

        $template = NextendSocialLogin::get_template_part('ultimate-member/register/' . sanitize_file_name(NextendSocialLogin::$settings->get('ultimatemember_register_form_layout')) . '.php');
        if (!empty($template) && file_exists($template)) {
            include($template);
        }
    }

    public static function ultimatemember_account_home() {

        $template = NextendSocialLogin::get_template_part('ultimate-member/account-home.php');
        if (!empty($template) && file_exists($template)) {

            $buttons = NextendSocialLogin::renderLinkAndUnlinkButtons(false, true, true, NextendSocialLogin::$settings->get('ultimatemember_form_button_align'), false, NextendSocialLogin::$settings->get('ultimatemember_login_form_button_style'));

            include($template);
        }
    }

    public static function edd_login() {

        $index = NextendSocialLogin::$counter++;

        $containerID = 'nsl-custom-login-form-' . $index;

        echo '<div id="' . $containerID . '">' . NextendSocialLogin::renderButtonsWithContainer(NextendSocialLogin::$settings->get('edd_login_form_button_style'), false, false, false, NextendSocialLogin::$settings->get('edd_form_button_align')) . '</div>';

        $template = NextendSocialLogin::get_template_part('easy-digital-downloads/login/' . sanitize_file_name(NextendSocialLogin::$settings->get('edd_login_form_layout')) . '.php');
        if (!empty($template) && file_exists($template)) {
            include($template);
        }

    }

    public static function edd_register() {

        $index = NextendSocialLogin::$counter++;

        $containerID = 'nsl-custom-login-form-' . $index;

        echo '<div id="' . $containerID . '">' . NextendSocialLogin::renderButtonsWithContainer(NextendSocialLogin::$settings->get('edd_register_form_button_style'), false, false, false, NextendSocialLogin::$settings->get('edd_form_button_align'), 'register') . '</div>';

        $template = NextendSocialLogin::get_template_part('easy-digital-downloads/register/' . sanitize_file_name(NextendSocialLogin::$settings->get('edd_register_form_layout')) . '.php');
        if (!empty($template) && file_exists($template)) {
            include($template);
        }
    }

    public static function edd_checkout() {

        $index = NextendSocialLogin::$counter++;

        $containerID = 'nsl-custom-login-form-' . $index;

        echo '<div id="' . $containerID . '">' . NextendSocialLogin::renderButtonsWithContainer(NextendSocialLogin::$settings->get('edd_checkout_form_button_style'), false, false, false, NextendSocialLogin::$settings->get('edd_form_button_align'), 'register') . '</div>';

        $template = NextendSocialLogin::get_template_part('easy-digital-downloads/checkout/' . sanitize_file_name(NextendSocialLogin::$settings->get('edd_checkout_form_layout')) . '.php');
        if (!empty($template) && file_exists($template)) {
            include($template);
        }
    }

    public static function buddypress_login() {

        $index = NextendSocialLogin::$counter++;

        $containerID = 'nsl-custom-login-form-' . $index;

        echo '<div id="' . $containerID . '">' . NextendSocialLogin::renderButtonsWithContainer(NextendSocialLogin::$settings->get('buddypress_login_button_style'), false, false, false, NextendSocialLogin::$settings->get('buddypress_register_button_align')) . '</div>';

        $template = NextendSocialLogin::get_template_part('buddypress/login/' . sanitize_file_name(NextendSocialLogin::$settings->get('buddypress_login_form_layout')) . '.php');
        if (!empty($template) && file_exists($template)) {
            include($template);
        }

    }

    public static function disable_adminbar_roles() {
        $user_info               = wp_get_current_user();
        $adminbar_disabled_roles = NextendSocialLogin::$settings->get('admin_bar_roles');

        if (is_array($adminbar_disabled_roles) && count($adminbar_disabled_roles) > 0) {
            $role_match = array_intersect($user_info->roles, $adminbar_disabled_roles);
            if ($role_match) {
                show_admin_bar(false);
            }
        }
    }


    /*
     * Users table - column name
     */
    public static function linked_providers_column($column) {
        $column['linked_providers'] = __('Social Providers', 'nextend-facebook-connect');

        foreach (NextendSocialLogin::$enabledProviders as $enabledProvider) {
            self::$enabledProvidersDB[$enabledProvider->getDbId()] = $enabledProvider;
        }

        return $column;
    }

    /*
     * Users table - field value of record
     */
    public static function linked_providers_row($val, $column_name, $user_id) {
        switch ($column_name) {
            case 'linked_providers' :
                $providers    = self::getLinkedProvidersByUserID($user_id);
                $providerList = '';
                foreach ($providers as $provider) {
                    /** @var NextendSocialProvider $provider */
                    $providerList .= $provider->getLabel() . '<br>';
                }

                return $providerList;
                break;
        }

        return $val;
    }

    /**
     * Returns an array of provider objects from the providers, that are linked to the account.
     *
     * @param int $user_id
     *
     * @return array
     */
    private static function getLinkedProvidersByUserID($user_id) {
        /** @var $wpdb WPDB */ global $wpdb;

        $linkedProviders = array();

        $providerIdList = $wpdb->get_results($wpdb->prepare('SELECT type FROM `' . $wpdb->prefix . 'social_users` WHERE ID = %s', $user_id));

        foreach ($providerIdList as $providerId) {
            if (isset(self::$enabledProvidersDB[$providerId->type])) {
                $provider = self::$enabledProvidersDB[$providerId->type];

                $linkedProviders[$provider->getId()] = $provider;
            }
        }

        return $linkedProviders;

    }

    /**
     * BuddyPress - Support login restrictions
     * If our login restriction is enabled, then users with email address should be able to login with social login
     * only, if their account has been activated already.
     *
     * @param [isExternalInsertUser=>bool, error=>bool]     $externalInsertUserStatus
     * @param NextendSocialUser $socialUser
     * @param array             $user_data
     * @param bool|WP_Error     $error
     *
     * @return bool
     */
    public static function integrationBuddyPressLoginRestriction($externalInsertUserStatus, $socialUser, $user_data) {
        if (class_exists('BuddyPress', false) && function_exists('bp_core_signup_user')) {
            //we should only allow the login restriction if the email address is available and stored
            if (is_email($user_data['user_email'])) {
                /**
                 * The support of the BuddyPress login restriction might trigger our doAutoLogin() after BuddyPress registered the new account in our flow.
                 * We need to unhook the doAutoLogin function, otherwise it will prevent BuddyPress from finishing its registration.
                 */
                $autoLoginPriority = apply_filters('nsl_autologin_priority', 40);
                remove_action('user_register', array(
                    $socialUser,
                    'doAutoLogin'
                ), $autoLoginPriority);

                /**
                 * The registration needs to be handled by BuddyPress.
                 *
                 * Limitation: To exclude the inactive users from certain features, BuddyPress removes the roles that we set and after the activation they set their default role.
                 * This means that these accounts are  always registered with the BuddyPress default role.
                 */
                $user_id = bp_core_signup_user($user_data['user_login'], $user_data['user_pass'], $user_data['user_email'], array());
                if (!is_wp_error($user_id)) {
                    /**
                     * If auto login is disabled, then we might continue our register flow.
                     * so we shouldn't run wp_insert_user() again.
                     */
                    $externalInsertUserStatus['isExternalInsertUser'] = true;

                    //If the registration was successful, attempt to log the user in.
                    $socialUser->doAutoLogin($user_id);
                } else {
                    $externalInsertUserStatus['error'] = $user_id;
                }
            }
        }

        return $externalInsertUserStatus;
    }
}

if (version_compare(PHP_VERSION, '7.0', '>=') && version_compare(get_bloginfo('version'), '4.6', '>=')) {
    NextendSocialLoginPRO::init();
}