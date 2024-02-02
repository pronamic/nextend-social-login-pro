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

    <?php if ($settings->get('has_credentials')): ?>
        <!-- Client ID + Secret -->
        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" novalidate="novalidate">

            <?php wp_nonce_field('nextend-social-login'); ?>
            <input type="hidden" name="action" value="nextend-social-login"/>
            <input type="hidden" name="view" value="provider-<?php echo $provider->getId(); ?>"/>
            <input type="hidden" name="subview" value="settings"/>
            <input type="hidden" name="has_credentials" value="0"/>
            <input type="hidden" name="tested" id="tested" value="<?php echo esc_attr($settings->get('tested')); ?>"/>
            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row"><label for="client_id"><?php _e('Client ID', 'nextend-facebook-connect'); ?></label>
                    </th>
                    <td>
                        <input name="client_id" type="text" id="client_id"
                               value="<?php echo esc_attr($settings->get('client_id')); ?>" class="regular-text" disabled>
                        <p class="description"
                           id="tagline-client_id"><?php printf(__('If you are not sure what is your %1$s, please head over to <a href="%2$s">Getting Started</a>', 'nextend-facebook-connect'), 'Client ID', $this->getUrl()); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label
                                for="client_secret"><?php _e('Client Secret', 'nextend-facebook-connect'); ?></label>
                    </th>
                    <td><input name="client_secret" type="text" id="client_secret"
                               value="<?php echo esc_attr($settings->get('client_secret')); ?>" class="regular-text" disabled>
                    </td>
                </tr>
                </tbody>
            </table>
            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary"
                                     value="<?php _e('Delete credentials'); ?>"></p>
        </form>

        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" novalidate="novalidate">
            <?php wp_nonce_field('nextend-social-login'); ?>
            <input type="hidden" name="action" value="nextend-social-login"/>
            <input type="hidden" name="view" value="provider-<?php echo $provider->getId(); ?>"/>
            <input type="hidden" name="subview" value="settings"/>
            <input type="hidden" name="settings_saved" value="1"/>
            <input type="hidden" name="tested" id="tested" value="<?php echo esc_attr($settings->get('tested')); ?>"/>
            <?php
            $this->renderOtherSettings();

            $this->renderProSettings();
            ?>
        </form>
    <?php else: ?>
        <!-- Generating token -->
        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
            <?php wp_nonce_field('nextend-social-login'); ?>
            <input type="hidden" name="action" value="nextend-social-login"/>
            <input type="hidden" name="view" value="provider-<?php echo $provider->getId(); ?>"/>
            <input type="hidden" name="subview" value="settings"/>
            <input type="hidden" name="has_credentials" value="<?php echo esc_attr($settings->get('has_credentials')); ?>"/>
            <input type="hidden" name="tested" id="tested" value="<?php echo esc_attr($settings->get('tested')); ?>"/>

            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row">
                        <label for="private_key_id"><?php _e('Private Key ID', 'nextend-facebook-connect'); ?>
                            - <em>(<?php _e('Required', 'nextend-facebook-connect'); ?>)</em></label>
                    </th>
                    <td>
                        <input name="private_key_id" type="text" id="private_key_id"
                               value="<?php echo esc_attr($settings->get('private_key_id')); ?>" class="regular-text" required>
                        <p class="description"
                           id="tagline-client_id"><?php printf(__('If you are not sure what is your %1$s, please head over to <a href="%2$s">Getting Started</a>', 'nextend-facebook-connect'), 'Private Key ID', $this->getUrl()); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="private_key"><?php _e('Private Key', 'nextend-facebook-connect'); ?>
                            - <em>(<?php _e('Required', 'nextend-facebook-connect'); ?>)</em></label>
                    </th>
                    <td>
                        <textarea name="private_key" type="text" id="private_key" class="regular-text" rows="10" required><?php echo esc_attr($settings->get('private_key')); ?></textarea>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label
                                for="team_identifier"><?php _e('Team Identifier', 'nextend-facebook-connect'); ?>
                            - <em>(<?php _e('Required', 'nextend-facebook-connect'); ?>)</em></label></th>
                    <td><input name="team_identifier" type="text" id="team_identifier"
                               value="<?php echo esc_attr($settings->get('team_identifier')); ?>" class="regular-text" required>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label
                                for="service_identifier"><?php _e('Service Identifier', 'nextend-facebook-connect'); ?>
                            - <em>(<?php _e('Required', 'nextend-facebook-connect'); ?>)</em></label></th>
                    <td><input name="service_identifier" type="text" id="service_identifier"
                               value="<?php echo esc_attr($settings->get('service_identifier')); ?>" class="regular-text" required>
                    </td>
                </tr>
                </tbody>
            </table>

            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary"
                                     value="<?php _e('Generate Token'); ?>"></p>
        </form>
    <?php endif; ?>
</div>