<?php

use NSL\Notices;

class NextendSocialLoginPROProviderExtension {

    /** @var NextendSocialProvider */
    protected $provider;

    private $context = array();

    /**
     * NextendSocialLoginPROProviderExtension constructor.
     *
     * @param $provider NextendSocialProvider
     */
    public function __construct($provider) {
        $this->provider = $provider;

        add_action('nsl_providers_loaded', array(
            $this,
            'loaded'
        ));
    }

    public function loaded() {

        $id = $this->provider->getId();

        add_filter('nsl_' . $id . '_is_login_allowed', array(
            $this,
            'isLoginAllowed'
        ), 10, 3);

        add_action('nsl_' . $id . '_register_new_user', array(
            $this,
            'afterRegister'
        ));

        add_action('nsl_' . $id . '_before_register', array(
            $this,
            'beforeRegister'
        ));

        add_filter('nsl_' . $id . '_auto_link_allowed', array(
            $this,
            'isAutoLinkAllowed'
        ), 10, 1);

        add_filter('nsl_update_settings_validate_' . $this->provider->getOptionKey(), array(
            $this,
            'validateSettings'
        ), 10, 2);
    }

    public function validateSettings($newData, $postedData) {

        foreach ($postedData as $key => $value) {
            switch ($key) {
                case 'disabled_roles':
                    $newData[$key] = array_filter((array)$value);
                    break;
                case 'register_roles':
                    $value = array_filter((array)$value);
                    if (empty($value)) {
                        $value[] = 'default';
                    }
                    $newData[$key] = $value;
                    break;
                case 'ask_email':
                case 'ask_user':
                case 'ask_password':
                case 'auto_link':
                    $newData[$key] = trim(sanitize_text_field($value));
                    break;
            }
        }

        if (!empty($postedData['sync_fields']) && is_array($postedData['sync_fields'])) {
            $sync_fields = $postedData['sync_fields'];

            if (isset($sync_fields['login'])) {
                $newData['sync_fields/login'] = intval($sync_fields['login']) ? 1 : 0;
            }

            if (isset($sync_fields['link'])) {
                $newData['sync_fields/link'] = intval($sync_fields['link']) ? 1 : 0;
            }

            if (!empty($sync_fields['fields']) && is_array($sync_fields['fields'])) {
                foreach ($sync_fields['fields'] as $fieldName => $fieldSettings) {
                    if (isset($fieldSettings['enabled'])) {
                        $newData['sync_fields/fields/' . $fieldName . '/enabled'] = intval($fieldSettings['enabled']) ? 1 : 0;
                    }
                    if (isset($fieldSettings['meta_key'])) {
                        $newData['sync_fields/fields/' . $fieldName . '/meta_key'] = preg_replace("/[^A-Za-z0-9\-_ ]/", '', $fieldSettings['meta_key']);
                    }

                    if (empty($newData['sync_fields/fields/' . $fieldName . '/meta_key'])) {
                        $newData['sync_fields/fields/' . $fieldName . '/enabled'] = 0;
                    }
                }
            }
        }

        return $newData;
    }

    public function isLoginAllowed($isAllowed, $provider, $user_id) {
        if ($isAllowed) {
            $disable_roles = $this->provider->settings->get('disabled_roles');
            if (is_array($disable_roles) && count($disable_roles) > 0) {
                $user_info = get_userdata($user_id);
                foreach ($user_info->roles as $user_role) {
                    if (in_array($user_role, $disable_roles)) {

                        Notices::addError(__('Social login is not allowed with this role!', 'nextend-facebook-connect'));
                        $isAllowed = false;
                    }
                }
            }
        }

        return $isAllowed;
    }

    public function afterRegister($user_id) {
        $register_roles = $this->provider->settings->get('register_roles');
        if (!is_array($register_roles) || count($register_roles) == 0 || (count($register_roles) === 1 && $register_roles[0] == 'default')) {
            //Do nothing as the user role is fine
        } else {
            $user = new WP_User($user_id);
            foreach ($register_roles as $k => $register_role) {
                if ($register_roles[$k] == 'default') {
                    $register_roles[$k] = get_option('default_role');
                }
            }

            $user->set_role($register_roles[0]);
            array_shift($register_roles);


            foreach ($register_roles as $register_role) {
                $user->add_role($register_role);
            }
        }
    }

    public function beforeRegister() {

        add_filter('nsl_registration_require_extra_input', array(
            $this,
            'require_extra_input_username'
        ), 0, 2);

        add_filter('nsl_registration_require_extra_input', array(
            $this,
            'require_extra_input_email'
        ), 0, 2);

        add_filter('nsl_registration_require_extra_input', array(
            $this,
            'require_extra_input_password'
        ), 0, 2);
    }

    public function require_extra_input_username($askExtraData, $userData) {

        $askUsername = false;
        switch ($this->provider->settings->get('ask_user')) {
            case 'always':
                $askUsername = true;
                break;
            case 'when-empty':
                if (empty($userData['username'])) {
                    $askUsername = true;
                }
                break;
        }

        if ($askUsername) {
            add_filter('nsl_registration_validate_extra_input', array(
                $this,
                'validate_extra_input_username'
            ), 10, 2);

            add_action('nsl_registration_form_start', array(
                $this,
                'registration_form_username'
            ), -30);
        }

        return $askExtraData || $askUsername;
    }

    /**
     * @param array    $userData
     * @param WP_Error $errors
     *
     * @return array
     */
    public function validate_extra_input_username($userData, $errors) {

        $isPost = isset($_POST['submit']);
        if ($isPost) {
            if (isset($_POST['user_login']) && is_string($_POST['user_login'])) {
                $hasError = false;

                $user_login           = $_POST['user_login'];
                $sanitized_user_login = sanitize_user($user_login);

                if ($sanitized_user_login == '') {
                    $errors->add('empty_username', '<strong>' . __('Error') . '</strong>: ' . __('Please enter a username.'));
                    $hasError = true;
                } else if (!validate_username($user_login)) {
                    $errors->add('invalid_username', '<strong>' . __('Error') . '</strong>: ' . __('This username is invalid because it uses illegal characters. Please enter a valid username.'));
                    $sanitized_user_login = '';
                    $hasError             = true;
                } else if (!apply_filters('nsl_validate_username', true, $sanitized_user_login, $errors)) {
                    $hasError = true;
                } else if (username_exists($sanitized_user_login)) {
                    $errors->add('username_exists', __('<strong>Error</strong>: This username is already registered. Please choose another one.'));
                    $hasError = true;

                } else {
                    /** This filter is documented in wp-includes/user.php */
                    $illegal_user_logins = array_map('strtolower', (array)apply_filters('illegal_user_logins', array()));
                    if (in_array(strtolower($sanitized_user_login), $illegal_user_logins)) {
                        $errors->add('invalid_username', __('<strong>Error</strong>: Sorry, that username is not allowed.'));
                        $hasError = true;
                    }
                }

                $hasError = apply_filters('nsl_validate_extra_input_username_errors', $hasError, $this->provider, $errors);
                if (!$hasError) {
                    $userData['username']   = $sanitized_user_login;
                    $userData['user_login'] = $sanitized_user_login;
                }
            }
        }

        return $userData;
    }

    public function registration_form_username($userData) {
        $template = NextendSocialLogin::get_template_part('register-flow/register-username-field.php');
        if (!empty($template) && file_exists($template)) {
            include($template);
        }
    }

    public function require_extra_input_email($askExtraData, $userData) {


        $askEmail = false;
        switch ($this->provider->settings->get('ask_email')) {
            case 'always':
                $askEmail = true;
                break;
            case 'when-empty':
                if (empty($userData['email'])) {
                    $askEmail = true;
                }
                break;
        }

        if ($askEmail) {
            add_filter('nsl_registration_validate_extra_input', array(
                $this,
                'validate_extra_input_email'
            ), 10, 2);

            add_action('nsl_registration_form_start', array(
                $this,
                'registration_form_email'
            ), -20);
        }

        return $askExtraData || $askEmail;
    }

    /**
     * @param array    $userData
     * @param WP_Error $errors
     *
     * @return array
     */
    public function validate_extra_input_email($userData, $errors) {

        $isPost = isset($_POST['submit']);
        if ($isPost) {
            if (isset($_POST['user_email']) && is_string($_POST['user_email'])) {
                $hasError = false;

                $email = $_POST['user_email'];

                if ($email == '') {
                    $errors->add('empty_email', __('<strong>Error</strong>: Please enter an email address.'), array('form-field' => 'email'));
                    $hasError = true;
                } else if (!is_email($email)) {
                    $errors->add('invalid_email', __('<strong>Error</strong>: The email address isn&#8217;t correct.'), array('form-field' => 'email'));
                    $email    = '';
                    $hasError = true;
                } else if (email_exists($email)) {
                    $errors->add('email_exists', __('<strong>Error</strong>: This email is already registered. Please choose another one.'), array('form-field' => 'email'));
                    $hasError = true;
                }

                $hasError = apply_filters('nsl_validate_extra_input_email_errors', $hasError, $this->provider, $errors);
                if (!$hasError) {
                    $userData['email'] = $email;
                }
            }
        }

        return $userData;
    }

    public function registration_form_email($userData) {
        $template = NextendSocialLogin::get_template_part('register-flow/register-email-field.php');
        if (!empty($template) && file_exists($template)) {
            include($template);
        }
    }

    public function require_extra_input_password($askExtraData, $userData) {

        $askPassword = false;
        switch ($this->provider->settings->get('ask_password')) {
            case 'always':

                wp_enqueue_script('utils');
                wp_enqueue_script('user-profile');

                $askPassword = true;
                break;
        }

        if ($askPassword) {
            add_filter('nsl_registration_validate_extra_input', array(
                $this,
                'validate_extra_input_password'
            ), 10, 2);

            add_action('nsl_registration_form_start', array(
                $this,
                'registration_form_password'
            ), -10);


            add_action('wp_enqueue_scripts', array(
                $this,
                'dashicons_frontend_load'
            ));


        }

        return $askExtraData || $askPassword;
    }

    /**
     * @param array    $userData
     * @param WP_Error $errors
     *
     * @return array
     */
    public function validate_extra_input_password($userData, $errors) {

        $isPost = isset($_POST['submit']);
        if ($isPost) {
            if (isset($_POST['pass1']) && is_string($_POST['pass1'])) {
                $hasError = false;

                $pass1 = $_POST['pass1'];
                $pass2 = $_POST['pass2'];

                // Check for blank password when adding a user.
                if (empty($pass1)) {
                    $errors->add('pass', __('<strong>Error</strong>: Please enter a password.'), array('form-field' => 'pass1'));
                    $hasError = true;
                }

                // Check for "\" in password.
                if (false !== strpos(wp_unslash($pass1), "\\")) {
                    $errors->add('pass', __('<strong>Error</strong>: Passwords may not contain the character "\\".'), array('form-field' => 'pass1'));
                    $hasError = true;
                }

                // Checking the password has been typed twice the same.
                if (!empty($pass1) && $pass1 != $pass2) {
                    $errors->add('pass', __('<strong>Error</strong>: Passwords don&#8217;t match. Please enter the same password in both password fields.'), array('form-field' => 'pass1'));
                    $hasError = true;
                }

                $hasError = apply_filters('nsl_validate_extra_input_password_errors', $hasError, $this->provider, $errors);
                if (!$hasError) {
                    $userData['password'] = $pass1;
                }
            }
        }

        return $userData;
    }

    public function registration_form_password($userData) {

        $template = NextendSocialLogin::get_template_part('register-flow/register-password-field.php');
        if (!empty($template) && file_exists($template)) {
            include($template);
        }
    }

    public function isAutoLinkAllowed($isAllowed) {
        if ($this->provider->settings->get('auto_link') == 'disabled') {

            Notices::addError(sprintf(__('This email is already registered, please login in to your account to link with %1$s.', 'nextend-facebook-connect'), $this->provider->getLabel()));

            return false;
        }

        return $isAllowed;
    }

    public function dashicons_frontend_load() {
        wp_enqueue_style('dashicons');
    }
}