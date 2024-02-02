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
                <th scope="row"><label for="client_id"><?php _e('Client ID', 'nextend-facebook-connect'); ?>
                        - <em>(<?php _e('Required', 'nextend-facebook-connect'); ?>)</em></label>
                </th>
                <td>
                    <input name="client_id" type="text" id="client_id"
                           value="<?php echo esc_attr($settings->get('client_id')); ?>" class="regular-text">
                    <p class="description"
                       id="tagline-client_id"><?php printf(__('If you are not sure what is your %1$s, please head over to <a href="%2$s">Getting Started</a>', 'nextend-facebook-connect'), 'Client ID', $this->getUrl()); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label
                            for="client_secret"><?php _e('Client Secret', 'nextend-facebook-connect'); ?>
                        - <em>(<?php _e('Required', 'nextend-facebook-connect'); ?>)</em></label></th>
                <td><input name="client_secret" type="text" id="client_secret"
                           value="<?php echo esc_attr($settings->get('client_secret')); ?>" class="regular-text">
                </td>
            </tr>
            <tr>
                <th scope="row"><label
                            for="team_id"><?php _e('Slack Team ID', 'nextend-facebook-connect'); ?>
                        - <em>(<?php _e('Optional', 'nextend-facebook-connect'); ?>)</em></label></th>
                <td><input name="team_id" type="text" id="team_id"
                           value="<?php echo esc_attr($settings->get('team_id')); ?>" class="regular-text">
                    <p class="description"
                       id="tagline-team_id"><?php printf(__('The Team ID of a workspace to attempt to restrict to. %1$sLearn more.%2$s', 'nextend-facebook-connect'), '<a href="https://nextendweb.com/nextend-social-login-docs/provider-slack/#team-id" target="_blank">', '</a>'); ?></p>
                </td>
            </tr>
            </tbody>
        </table>
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
                                      value="image_24" <?php if ($settings->get('profile_image_size') == 'image_24') : ?> checked="checked" <?php endif; ?>>
                            <span>24x24</span></label><br>
                        <label><input type="radio" name="profile_image_size"
                                      value="image_32" <?php if ($settings->get('profile_image_size') == 'image_32') : ?> checked="checked" <?php endif; ?>>
                            <span>32x32</span></label><br>
                        <label><input type="radio" name="profile_image_size"
                                      value="image_48" <?php if ($settings->get('profile_image_size') == 'image_48') : ?> checked="checked" <?php endif; ?>>
                            <span>48x48</span></label><br>
                        <label><input type="radio" name="profile_image_size"
                                      value="image_72" <?php if ($settings->get('profile_image_size') == 'image_72') : ?> checked="checked" <?php endif; ?>>
                            <span><?php _e('72x72', 'nextend-facebook-connect'); ?></span></label><br>
                        <label><input type="radio" name="profile_image_size"
                                      value="image_192" <?php if ($settings->get('profile_image_size') == 'image_192') : ?> checked="checked" <?php endif; ?>>
                            <span><?php _e('192x192', 'nextend-facebook-connect'); ?></span></label><br>
                        <label><input type="radio" name="profile_image_size"
                                      value="image_512" <?php if ($settings->get('profile_image_size') == 'image_512') : ?> checked="checked" <?php endif; ?>>
                            <span><?php _e('512x512', 'nextend-facebook-connect'); ?></span></label><br>
                        <label><input type="radio" name="profile_image_size"
                                      value="image_1024" <?php if ($settings->get('profile_image_size') == 'image_1024') : ?> checked="checked" <?php endif; ?>>
                            <span><?php _e('1024x1024', 'nextend-facebook-connect'); ?></span></label><br>
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