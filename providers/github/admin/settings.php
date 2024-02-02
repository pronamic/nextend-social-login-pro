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
                    <label for="client_id"><?php _e('Client ID', 'nextend-facebook-connect'); ?>
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
            </tbody>
        </table>

        <?php if (has_filter('nsl_github_client_id')): ?>
            <?php
            $client_id_notice = sprintf(__('We detected that your %1$s is probably overridden over the %2$s filter!', 'nextend-facebook-connect'), 'GitHub Client ID', '<code>nsl_github_client_id</code>');
            if ($settings->get('client_id') && $settings->get('client_id') !== apply_filters('nsl_github_client_id', $settings->get('client_id'))) {
                $client_id_notice = sprintf(__('We detected that your %1$s has been overridden over the %2$s filter!', 'nextend-facebook-connect'), 'GitHub Client ID', '<code>nsl_github_client_id</code>');
            }
            ?>
            <div class="notice notice-info">
                <p><?php echo $client_id_notice; ?></p>
            </div>
        <?php endif; ?>


        <?php if (has_filter('nsl_github_client_secret')): ?>
            <?php
            $client_secret_notice = sprintf(__('We detected that your %1$s is probably overridden over the %2$s filter!', 'nextend-facebook-connect'), 'GitHub Client Secret', '<code>nsl_github_client_secret</code>');
            if ($settings->get('client_secret') && $settings->get('client_secret') !== apply_filters('nsl_github_client_secret', $settings->get('client_secret'))) {
                $client_secret_notice = sprintf(__('We detected that your %1$s has been overridden over the %2$s filter!', 'nextend-facebook-connect'), 'GitHub Client Secret', '<code>nsl_github_client_secret</code>');
            }
            ?>
            <div class="notice notice-info">
                <p><?php echo $client_secret_notice; ?></p>
            </div>
        <?php endif; ?>

        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary"
                                 value="<?php _e('Save Changes'); ?>"></p>

        <?php
        $this->renderOtherSettings();

        $this->renderProSettings();
        ?>
    </form>
</div>