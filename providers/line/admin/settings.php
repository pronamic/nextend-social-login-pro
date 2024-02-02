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
                <th scope="row"><label for="client_id"><?php _e('Channel ID', 'nextend-facebook-connect'); ?>
                        - <em>(<?php _e('Required', 'nextend-facebook-connect'); ?>)</em></label>
                </th>
                <td>
                    <input name="client_id" type="text" id="client_id"
                           value="<?php echo esc_attr($settings->get('client_id')); ?>" class="regular-text">
                    <p class="description"
                       id="tagline-client_id"><?php printf(__('If you are not sure what is your %1$s, please head over to <a href="%2$s">Getting Started</a>', 'nextend-facebook-connect'), 'Channel ID', $this->getUrl()); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label
                            for="client_secret"><?php _e('Channel Secret', 'nextend-facebook-connect'); ?>
                        - <em>(<?php _e('Required', 'nextend-facebook-connect'); ?>)</em></label></th>
                <td><input name="client_secret" type="text" id="client_secret"
                           value="<?php echo esc_attr($settings->get('client_secret')); ?>" class="regular-text">
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Force reauthorization on each login', 'nextend-facebook-connect'); ?></th>
                <td>
                    <label for="force_reauth">
                        <input type="hidden" name="force_reauth" value="0">
                        <input type="checkbox" name="force_reauth" id="force_reauth" value="1" <?php if ($settings->get('force_reauth') == 1) : ?> checked="checked" <?php endif; ?>>
                        <?php _e('Enabled', 'nextend-facebook-connect'); ?>
                    </label>
                    <p class="description" id="tagline-force_reauth">
                        <?php _e('Enable, when you want to see the consent screen on each login.', 'nextend-facebook-connect'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Add LINE Official Account as a friend', 'nextend-facebook-connect'); ?></th>
                <td>
                    <?php
                    $bot_prompt = $settings->get('bot_prompt');
                    ?>

                    <fieldset>
                        <label><input type="radio" name="bot_prompt"
                                      value="" <?php if ($bot_prompt == '') : ?> checked="checked" <?php endif; ?>>
                            <span><?php _e('Don\'t display', 'nextend-facebook-connect'); ?></span>
                        </label><br>
                        <label><input type="radio" name="bot_prompt"
                                      value="normal" <?php if ($bot_prompt == 'normal') : ?> checked="checked" <?php endif; ?>>
                            <span><?php _e('Display the add friend option in the consent screen', 'nextend-facebook-connect'); ?></span>
                        </label><br>
                        <label><input type="radio" name="bot_prompt"
                                      value="aggressive" <?php if ($bot_prompt == 'aggressive') : ?> checked="checked" <?php endif; ?>>
                            <span><?php _e('Opens a new screen with the add friend option after the consent screen', 'nextend-facebook-connect'); ?></span>
                        </label>
                        <p class="description"
                           id="tagline-bot_prompt"><?php printf(__('If you have a LINE Official Account, people can add it as a friend when they authorize your App. %1$sLearn more%2$s.', 'nextend-facebook-connect'), '<a href="https://nextendweb.com/nextend-social-login-docs/provider-line/#how-to-add-friend" target="_blank">', '</a>'); ?></p>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Initial Login method', 'nextend-facebook-connect'); ?></th>
                <td>
                    <?php
                    $initial_amr_display = $settings->get('initial_amr_display');
                    ?>

                    <fieldset>
                        <label><input type="radio" name="initial_amr_display"
                                      value="" <?php if ($initial_amr_display == '') : ?> checked="checked" <?php endif; ?>>
                            <span><?php _e('Email and Password', 'nextend-facebook-connect'); ?></span>
                        </label><br>
                        <label><input type="radio" name="initial_amr_display"
                                      value="lineqr" <?php if ($initial_amr_display == 'lineqr') : ?> checked="checked" <?php endif; ?>>
                            <span><?php _e('QR code', 'nextend-facebook-connect'); ?></span>
                        </label>
                        <p class="description"
                           id="tagline-initial_amr_display"><?php _e('The selected value defines whether the Email and Password or the QR code login option will be presented in the LINE authentication screen by default.', 'nextend-facebook-connect'); ?></p>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Force initial login method', 'nextend-facebook-connect'); ?></th>
                <td>
                    <label for="switch_amr">
                        <input type="hidden" name="switch_amr" value="0">
                        <input type="checkbox" name="switch_amr" id="switch_amr" value="1" <?php if ($settings->get('switch_amr') == 1) : ?> checked="checked" <?php endif; ?>>
                        <?php _e('Enabled', 'nextend-facebook-connect'); ?>
                    </label>
                    <p class="description" id="tagline-switch_amr">
                        <?php _e('Enable, if you want to offer only the selected Initial Login method in the LINE authentication screen.', 'nextend-facebook-connect'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Allow Auto login', 'nextend-facebook-connect'); ?></th>
                <td>
                    <label for="allow_auto_login">
                        <input type="hidden" name="allow_auto_login" value="0">
                        <input type="checkbox" name="allow_auto_login" id="allow_auto_login" value="1" <?php if ($settings->get('allow_auto_login') == 1) : ?> checked="checked" <?php endif; ?>>
                        <?php _e('Enabled', 'nextend-facebook-connect'); ?>
                    </label>
                    <p class="description"
                       id="tagline-allow_auto_login"><?php printf(__('Having the Auto login enabled could make the login fail in certain cases. %1$sLearn more%2$s.', 'nextend-facebook-connect'), '<a href="https://nextendweb.com/nextend-social-login-docs/provider-line/#allow-auto-login" target="_blank">', '</a>'); ?></p>
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