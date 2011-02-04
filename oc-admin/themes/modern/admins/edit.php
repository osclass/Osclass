<?php
/*
 *      OSCLass – software for creating and publishing online classified
 *                           advertising platforms
 *
 *                        Copyright (C) 2010 OSCLASS
 *
 *       This program is free software: you can redistribute it and/or
 *     modify it under the terms of the GNU Affero General Public License
 *     as published by the Free Software Foundation, either version 3 of
 *            the License, or (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful, but
 *         WITHOUT ANY WARRANTY; without even the implied warranty of
 *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *             GNU Affero General Public License for more details.
 *
 *      You should have received a copy of the GNU Affero General Public
 * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
?>

<?php defined('ABS_PATH') or die( __('Invalid OSClass request.') ); ?>
<?php
    $fields = array(
        array('name' => 's_name', 'error_msg' => __('You have to write a name.')),
        array('name' => 's_email', 'error_msg' => __('You have to write an e-mail.')),
        array('name' => 's_username', 'error_msg' => __('You have to write an username.'))
    );
    osc_check_form_js($fields);
?>

<script>
	$(function() {
		// Here we include specific jQuery, jQuery UI and Datatables functions.
	});
</script>
		<div id="content">
			<div id="separator"></div>	
			
			<?php include_once $absolute_path . '/include/backoffice_menu.php'; ?>

		    <div id="right_column">
				<div id="content_header" class="content_header">
					<div style="float: left;"><img src="<?php echo  $current_theme ?>/images/back_office/admin-icon.png" /></div>
					<div id="content_header_arrow">&raquo; <?php if($adminEdit['pk_i_id']==$_SESSION['adminId']) { echo __('Edit your profile'); } else { echo __('Edit administrator'); } ?></div>
					<div style="clear: both;"></div>
				</div>
				
				<div id="content_separator"></div>
				<?php osc_show_flash_messages() ; ?>
				
				<div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
					<div style="padding: 20px;">				
								
						<form action="admins.php" method="post" onSubmit="return checkForm()">
						<input type="hidden" name="action" value="edit_post" />
						<input type="hidden" name="id" value="<?php echo $adminEdit['pk_i_id']; ?>" />
						
						<div style="float: left; width: 50%;">
						<fieldset>	
							<legend><?php echo __('Real name'); ?> (<?php echo __('required'); ?>)</legend>	
							<input type="text" name="s_name" id="s_name" value="<?php echo $adminEdit['s_name']; ?>" />
						</fieldset>
						</div>
											
						<div style="float: left; width: 50%;">
						<fieldset>	
							<legend><?php echo __('E-mail'); ?></legend>	
							<input type="text" name="s_email" id="s_email" value="<?php echo $adminEdit['s_email']; ?>" />
						</fieldset>
						</div>
						<div style="clear: both;"></div>
						
						<div style="float: left; width: 50%;">
						<fieldset>	
							<legend><?php echo __('User name'); ?> (<?php echo __('required'); ?>)</legend>	
							<input type="text" name="s_username" id="s_username" value="<?php echo $adminEdit['s_username']; ?>" />
						</fieldset>
						</div>
						
						<div style="float: left; width: 50%;">
						<fieldset>	
							<legend><?php echo __('Old password'); ?></legend>	
							<input type="password" name="old_password" id="old_password" value="" />
							<legend><?php echo __('New password'); ?></legend>	
							<input type="password" name="s_password" id="s_password" value="" />
							<legend><?php echo __('Re-type new password'); ?></legend>	
							<input type="password" name="s_password2" id="password2" value="" />
						</fieldset>
						<div style="margin-left: 10px; width: 95%; padding: 4px; background: #FFD2CF;"><?php echo __('Leave it blank if you do not want to change your password now'); ?>.</div>
						</div>
						<div style="clear: both;"></div>
						
						<input id="button_save" type="submit" value="<?php echo __('Update administrator'); ?>" /> 						
						</form>
					</div>
				</div>
			</div>
		</div>
