<p>
    <label for="user_login"><?php _e('Username') ?><br/>
        <input type="text" name="user_login" id="user_login" class="input"
               value="<?php echo esc_attr(wp_unslash($userData['username'])); ?>" size="20"/></label>
</p>