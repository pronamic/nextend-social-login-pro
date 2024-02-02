<p>
    <label for="user_email"><?php _e('Email') ?><br/>
        <input type="email" name="user_email" id="user_email" class="input"
               value="<?php echo esc_attr(wp_unslash($userData['email'])); ?>" size="25"/></label>
</p>
<?php if ($this->provider->settings->get('ask_password') != 'always'): ?>
    <p id="reg_passmail"><?php _e('Registration confirmation will be emailed to you.'); ?></p>
<?php endif; ?>