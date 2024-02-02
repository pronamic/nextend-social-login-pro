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
                <th scope="row"><label for="client_id"><?php _e('REST API Key', 'nextend-facebook-connect'); ?>
                        - <em>(<?php _e('Required', 'nextend-facebook-connect'); ?>)</em></label>
                </th>
                <td>
                    <input name="client_id" type="text" id="client_id"
                           value="<?php echo esc_attr($settings->get('client_id')); ?>" class="regular-text">
                    <p class="description"
                       id="tagline-client_id"><?php printf(__('If you are not sure what is your %1$s, please head over to <a href="%2$s">Getting Started</a>', 'nextend-facebook-connect'), 'REST API Key', $this->getUrl()); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label
                            for="client_secret"><?php _e('Client Secret Code', 'nextend-facebook-connect'); ?>
                        - <em>(<?php _e('Required', 'nextend-facebook-connect'); ?>)</em></label></th>
                <td><input name="client_secret" type="text" id="client_secret"
                           value="<?php echo esc_attr($settings->get('client_secret')); ?>" class="regular-text">
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
                                      value="create" <?php if ($settings->get('prompt') == 'create') : ?> checked="checked" <?php endif; ?>>
                            <span><?php _e('Start the authentication with the Kakao Account sign-up page.', 'nextend-facebook-connect'); ?></span></label><br>

                    </fieldset>
                </td>
            </tr>
            </tbody>
        </table>

        <?php if ($settings->get('client_id')): ?>
            <div class="error">
                <p><?php printf(__('By replacing your existing %1$s App, users with linked %1$s accounts will no longer be able to login with %1$s.', 'nextend-facebook-connect'), 'Kakao'); ?></p>
                <p>
                    <a href="https://nextendweb.com/nextend-social-login-docs/provider-kakao/#app_scoped_user_id" target="_blank"><?php _e('Find out why?', 'nextend-facebook-connect'); ?></a>
                </p>
            </div>
        <?php endif; ?>

        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary"
                                 value="<?php _e('Save Changes'); ?>"></p>

        <?php
        $this->renderOtherSettings();
        ?>

        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row"><?php _e('Profile image size', 'nextend-facebook-connect'); ?></th>
                <td>
                    <fieldset>
                        <label><input type="radio" name="profile_image_size"
                                      value="mini" <?php if ($settings->get('profile_image_size') == 'mini') : ?> checked="checked" <?php endif; ?>>
                            <span><?php _e('Mini', 'nextend-facebook-connect'); ?></span></label><br>
                        <label><input type="radio" name="profile_image_size"
                                      value="large" <?php if ($settings->get('profile_image_size') == 'large') : ?> checked="checked" <?php endif; ?>>
                            <span><?php _e('Large', 'nextend-facebook-connect'); ?></span></label><br>
                    </fieldset>
                </td>
            </tr>
            </tbody>
        </table>

        <?php
        $this->renderProSettings();
        ?>
    </form>
</div>