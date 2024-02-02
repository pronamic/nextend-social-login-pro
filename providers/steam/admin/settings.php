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
                <th scope="row"><label for="web_api_key"><?php _e('Web API Key', 'nextend-facebook-connect'); ?>
                        - <em>(<?php _e('Required', 'nextend-facebook-connect'); ?>)</em></label>
                </th>
                <td>
                    <input name="web_api_key" type="text" id="web_api_key"
                           value="<?php echo esc_attr($settings->get('web_api_key')); ?>" class="regular-text">
                    <p class="description"
                       id="tagline-web_api_key"><?php printf(__('If you are not sure what is your %1$s, please head over to <a href="%2$s">Getting Started</a>', 'nextend-facebook-connect'), 'Web API Key', $this->getUrl()); ?></p>
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
                                      value="normal" <?php if ($settings->get('profile_image_size') == 'normal') : ?> checked="checked" <?php endif; ?>>
                            <span>Normal</span></label><br>
                        <label><input type="radio" name="profile_image_size"
                                      value="medium" <?php if ($settings->get('profile_image_size') == 'medium') : ?> checked="checked" <?php endif; ?>>
                            <span>Medium</span></label><br>
                        <label><input type="radio" name="profile_image_size"
                                      value="full" <?php if ($settings->get('profile_image_size') == 'full') : ?> checked="checked" <?php endif; ?>>
                            <span>Full</span></label><br>
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