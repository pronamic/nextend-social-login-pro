<div class="user-pass1-wrap">
    <p>
        <label for="pass1"><?php _e('Password'); ?></label>
    </p>

    <div class="wp-pwd">
        <input type="password" name="pass1" id="pass1" class="input password-input" size="24" value="" autocomplete="new-password" spellcheck="false" data-reveal="1" data-pw="<?php echo esc_attr(wp_generate_password(16)); ?>" aria-describedby="pass-strength-result"/>

        <button type="button" class="button button-secondary wp-hide-pw hide-if-no-js" data-toggle="0" aria-label="<?php esc_attr_e('Hide password'); ?>">
            <span class="dashicons dashicons-hidden" aria-hidden="true"></span>
        </button>
        <div id="pass-strength-result" class="hide-if-no-js" aria-live="polite"><?php _e('Strength indicator'); ?></div>
    </div>
    <div class="pw-weak">
        <input type="checkbox" name="pw_weak" id="pw-weak" class="pw-checkbox"/>
        <label for="pw-weak"><?php _e('Confirm use of weak password'); ?></label>
    </div>
</div>

<p class="user-pass2-wrap">
    <label for="pass2"><?php _e('Confirm password'); ?></label>
    <input type="password" name="pass2" id="pass2" class="input" size="20" value="" autocomplete="off" spellcheck="false"/>
</p>

<p class="description indicator-hint"><?php echo wp_get_password_hint(); ?></p>
<br class="clear"/>