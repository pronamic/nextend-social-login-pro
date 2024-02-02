<?php
defined('ABSPATH') || die();
/** @var $this NextendSocialProviderAdmin */

$provider = $this->getProvider();

$settings = $provider->settings;
?>

<div class="nsl-admin-sub-content">
    <?php
    $this->renderSettingsHeader();
    ?>

    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" novalidate="novalidate">

        <?php wp_nonce_field('nextend-social-login'); ?>
        <input type="hidden" name="action" value="nextend-social-login"/>
        <input type="hidden" name="view" value="provider-<?php echo $provider->getId(); ?>"/>
        <input type="hidden" name="subview" value="settings"/>
        <input type="hidden" name="settings_saved" value="1"/>
        <input type="hidden" name="tested" id="tested" value="<?php echo esc_attr($settings->get('tested')); ?>"/>
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="client_id"><?php _e('Application (client) ID', 'nextend-facebook-connect'); ?>
                        - <em>(<?php _e('Required', 'nextend-facebook-connect'); ?>)</em></label>
                </th>
                <td>
                    <input name="client_id" type="text" id="client_id"
                           value="<?php echo esc_attr($settings->get('client_id')); ?>" class="regular-text">
                    <p class="description"
                       id="tagline-client_id"><?php printf(__('If you are not sure what is your %1$s, please head over to <a href="%2$s">Getting Started</a>', 'nextend-facebook-connect'), 'Application (client) ID', $this->getUrl()); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label
                            for="client_secret"><?php _e('Client secret', 'nextend-facebook-connect'); ?>
                        - <em>(<?php _e('Required', 'nextend-facebook-connect'); ?>)</em></label></th>
                <td>
                    <input name="client_secret" type="text" id="client_secret"
                           value="<?php echo esc_attr($settings->get('client_secret')); ?>" class="regular-text">
                </td>
            </tr>

            <tr>
                <th scope="row"><?php _e('Audience', 'nextend-facebook-connect'); ?></th>
                <td>
                    <?php
                    $tenant              = $settings->get('tenant');
                    $custom_tenant_value = $settings->get('custom_tenant_value');
                    ?>

                    <fieldset>
                        <label><input type="radio" name="tenant" class="tenant-radio"
                                      value="organizations" <?php if ($tenant == 'organizations') : ?> checked="checked" <?php endif; ?>>
                            <span><?php _e('Accounts in any organizational directory (Any Azure AD directory - Multitenant)', 'nextend-facebook-connect'); ?></span></label><br>
                        <label><input type="radio" name="tenant" class="tenant-radio"
                                      value="common" <?php if ($tenant == 'common') : ?> checked="checked" <?php endif; ?>>
                            <span><?php _e('Accounts in any organizational directory (Any Azure AD directory - Multitenant) and personal Microsoft accounts (e.g. Skype, Xbox)', 'nextend-facebook-connect'); ?></span></label><br>
                        <label><input type="radio" name="tenant" class="tenant-radio"
                                      value="consumers" <?php if ($tenant == 'consumers') : ?> checked="checked" <?php endif; ?>>
                            <span><?php _e('Personal Microsoft accounts only', 'nextend-facebook-connect'); ?></span></label><br>
                        <label><input type="radio" name="tenant" class="tenant-radio"
                                      value="custom_tenant" <?php if ($tenant == 'custom_tenant') : ?> checked="checked" <?php endif; ?>>
                            <span><?php _e('Only users in an organizational directory from a particular Azure AD tenant:', 'nextend-facebook-connect'); ?></span>
                            <input name="custom_tenant_value" type="text" id="custom_tenant_value"
                                   value="<?php echo esc_attr($custom_tenant_value); ?>"
                                   class="regular-text">
                        </label><br>
                    </fieldset>
                    <p class="description"
                       id="tagline-tenant"><?php printf(__('The selected value will define the supported account types. %1$sLearn more.%2$s', 'nextend-facebook-connect'), '<a href="https://nextendweb.com/nextend-social-login-docs/provider-microsoft/#audience" target="_blank">', '</a>'); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row"><?php _e('Authorization Prompt', 'nextend-facebook-connect'); ?></th>
                <td>
                    <?php
                    $prompt = $settings->get('prompt');
                    ?>

                    <fieldset>
                        <label><input type="radio" name="prompt"
                                      value="select_account" <?php if ($settings->get('prompt') == 'select_account') : ?> checked="checked" <?php endif; ?>>
                            <span><?php _e('Display account select modal', 'nextend-facebook-connect'); ?></span></label><br>
                        <label><input type="radio" name="prompt"
                                      value="login" <?php if ($settings->get('prompt') == 'login') : ?> checked="checked" <?php endif; ?>>
                            <span><?php _e('Force user to enter login credentials on each login', 'nextend-facebook-connect'); ?></span></label><br>
                        <label><input type="radio" name="prompt"
                                      value="" <?php if ($settings->get('prompt') == '') : ?> checked="checked" <?php endif; ?>>
                            <span><?php _e('Display authorization and authentication dialog only when necessary', 'nextend-facebook-connect'); ?></span></label><br>
                    </fieldset>
                </td>
            </tr>
            </tbody>
        </table>
        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary"
                                 value="<?php _e('Save Changes'); ?>"></p>

        <?php
        $this->renderOtherSettings();

        $this->renderProSettings();
        ?>
    </form>
</div>