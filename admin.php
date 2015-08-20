<div class="wrap" id="hide_settings">
	<h2><?php _e('Hide Login+ Settings', 'hidelogin')?></h2>
		<div class="update-nag" style="padding: 0px 5px; margin-top: 5px;">
        <p><strong>** You must use custom permalinks. Do that from <a href="<?php echo admin_url("/options-permalink.php"); ?>">here.</a></strong></p>
        <p><strong>** Please notice making new registeration and lost password URLs public, obviously means others can have access to the new login page as well. </strong></p>
		<p><strong>** After modifying <span style="color:red">admin slug</span>, you need to log out and log in for the changes to take effect.</strong></p>
		</div>
    <form method="post" action="">
        <table class="form-table">
            <tbody>
                <tr valign="top">
           			 <th scope="row"><label for="login_slug"><?php _e('Login slug', 'hidelogin');?></label></th>
            		<td><input name="hide_login_slug" id="login_slug" value="<?php echo get_option('hide_login_slug');?>" type="text"><br />
                    <strong style="color:#777;font-size:12px;">Login URL:</strong> <span style="font-size:0.9em;color:#999999;"><?php echo trailingslashit( get_option('siteurl') ); ?><span style="background-color: #fffbcc;"><?php echo get_option('hide_login_slug');?></span></span><br />
					<small>Not more than 24 characters. Allowed characters are A-Z a-z 0-9 _ and -</small>
                    </td>
            	</tr>
                <tr valign="top">
                	<th scope="row"><label for="logout_slug"><?php _e('Logout slug', 'hidelogin');?></label></th>
                    <td><input type="text" name="hide_logout_slug" id="logout_slug" value="<?php echo get_option('hide_logout_slug');?>" /><br />
                    <strong style="color:#777;font-size:12px;">Logout URL:</strong> <span style="font-size:0.9em;color:#999999;"><?php echo trailingslashit( get_option('siteurl') ); ?><span style="background-color: #fffbcc;"><?php echo get_option('hide_logout_slug');?></span></span><br />
                    <small>Not more than 24 characters. Allowed characters are A-Z a-z 0-9 _ and -</small>
                    </td>
                </tr>
             <?php if( get_option('users_can_register') ){ ?>
                <tr valign="top">
                	<th scope="row"><label for="register_slug"><?php _e('Register slug', 'hidelogin');?></label></th>
                    <td><input type="text" name="hide_register_slug" id="register_slug" value="<?php echo get_option('hide_register_slug');?>" /><br />
                    <strong style="color:#777;font-size:12px;">Register URL:</strong> <span style="font-size:0.9em;color:#999999;"><?php echo trailingslashit( get_option('siteurl') ); ?><span style="background-color: #fffbcc;"><?php echo get_option('hide_register_slug');?></span></span><br />
                    <small>Not more than 24 characters. Allowed characters are A-Z a-z 0-9 _ and -</small>
                    </td>
                </tr>
              <?php } ?>
              <tr valign="top">
           			 <th scope="row"><label for="admin_slug"><?php _e('Admin slug', 'hidelogin');?></label></th>
            		<td><input name="hide_admin_slug" id="admin_slug" value="<?php echo get_option('hide_admin_slug');?>" type="text"><br />
                    <strong style="color:#777;font-size:12px;">Admin URL:</strong> <span style="font-size:0.9em;color:#999999;"><?php echo trailingslashit( get_option('siteurl') ); ?><span style="background-color: #fffbcc;"><?php echo get_option('hide_admin_slug');?></span></span><br />
                    <small>* You may leave this field empty if want to have wp-admin slug intact.</small><br />
                    <small>Not more than 24 characters. Allowed characters are A-Z a-z 0-9 _ and -</small>
                    </td>
            	</tr>
				<tr valign="top">
           			 <th scope="row"><label for="forgot_slug"><?php _e('Forgot password slug', 'hidelogin');?></label></th>
            		<td><input name="hide_forgot_slug" id="forgot_slug" value="<?php echo get_option('hide_forgot_slug');?>" type="text"><br />
                    <strong style="color:#777;font-size:12px;">Forgot password URL:</strong> <span style="font-size:0.9em;color:#999999;"><?php echo trailingslashit( get_option('siteurl') ); ?><span style="background-color: #fffbcc;"><?php echo get_option('hide_forgot_slug');?></span></span><br />
                    <small>Not more than 24 characters. Allowed characters are A-Z a-z 0-9 _ and -</small>
                    </td>
            	</tr>
                <tr valign="top">
                	<th scope="row"><?php _e('Hide mode', 'hidelogin'); ?></th>
                    <td><label><input type="radio" name="hide_wplogin" value="1" <?php if(get_option('hide_wplogin') ) echo 'checked="checked" ';?> /> Enable</label><br />
                    	<label><input type="radio" name="hide_wplogin" value="0" <?php if(!get_option('hide_wplogin') ) echo 'checked="checked" ';?>/> Disable</label><br />
                        <small><?php _e('Prevent users from being able to access wp-login.php directly ( enable this when you use custom login slug )','hidelogin');?></small></td>
                </tr>
				<tr valign="top">
                	<th scope="row"><?php _e('Hide wp-admin', 'hidelogin'); ?></th>
                    <td><label><input type="radio" name="hide_wpadmin" value="1" <?php if(get_option('hide_wpadmin') ) echo 'checked="checked" ';?> /> Enable</label><br />
                    	<label><input type="radio" name="hide_wpadmin" value="0" <?php if(!get_option('hide_wpadmin') ) echo 'checked="checked" ';?>/> Disable</label><br />
                        <small><?php _e('Prevent users from being able to access wp-admin directly ( enable this when you use custom admin slug )','hidelogin');?></small></td>
                </tr>
                <tr valign="top">
                <th scope="row"><?php _e('.htaccess output', 'hidelogin');?></th>
                <td style="color: navy;"><pre><?php echo ((get_option('hide_rules') != "")?get_option('hide_rules'):"<span style=\"color: red !important;\">No Output.</span>");?></pre></td>
                </tr>
				<tr valign="top">
				<th scope="row"><?php _e('Did the tricks ?', 'hidelogin');?></th>
				<td>
				        <input class="button-primary" name="Submit" style="font-weight: bold;" value="<?php _e('Save Changes','hidelogin');?>" type="submit" />
						<input name="action" value="hide_login_update" type="hidden" />
				</td>
				</tr>
        	</tbody>
     	</table>
    </form>
</div>