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
					<div style="float: left;"><img src="<?php echo  $current_theme; ?>/images/back_office/user-group-icon.png" /></div>
					<div id="content_header_arrow">&raquo; <?php echo __('Add a new user'); ?></div> 
					<div style="clear: both;"></div>
				</div>
				
				<div id="content_separator"></div>
				<?php osc_showFlashMessages(); ?>
				
				<!-- add new item form -->
				<div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
					<div style="padding: 20px;">

						<form action="users.php" method="post">
						<input type="hidden" name="action" value="create_post" />
						
						<div style="float: left; width: 50%;">
							<fieldset>	
								<legend><?php echo __('User name'); ?> (<?php echo __('required'); ?>)</legend>						
								<input type="text" name="s_username" id="s_username" />
							</fieldset>
						</div>
						
						<div style="float: left; width: 50%;">
							<fieldset>	
								<legend><?php echo __('Password'); ?> (<?php echo __('required'); ?>)</legend>						
								<input type="password" name="s_password" id="s_password" />
							</fieldset>
						</div>
						<div style="clear: both;"></div>
						
						
						<div style="float: left; width: 50%;">
							<fieldset>	
								<legend><?php echo __('E-mail'); ?></legend>						
								<input type="text" name="s_email" id="s_email" />
							</fieldset>
						</div>
						
						<div style="float: left; width: 50%;">
							<fieldset>	
								<legend><?php echo __('Web site'); ?></legend>	
								<input type="text" name="s_website" id="s_website" />
							</fieldset>
						</div>
						<div style="clear: both;"></div>
						
						<div style="float: left; width: 50%;">
							<fieldset>	
								<legend><?php echo __('Real name'); ?> (<?php echo __('required'); ?>)</legend>	
								<input type="text" name="s_name" id="s_name" />
							</fieldset>
							
							<fieldset>
								<legend><?php echo __('Mobile phone'); ?></legend>
								<input type="text" name="s_phone_mobile" id="s_phone_mobile" />
							</fieldset>
							
							<fieldset>
								<legend><?php echo __('Land phone'); ?></legend>
								<input type="text" name="s_phone_land" id="s_phone_land" />
							</fieldset>
						</div>

						<div style="float: left; width: 50%;">
							<fieldset style="min-height: 166px;">
								<legend><?php echo __('Additional information'); ?></legend>
								<textarea style="height: 147px; width: 100%; border: 1px solid #ccc;" name="s_info" id="s_info" ></textarea>								
							</fieldset>
						</div>
						<div style="clear: both;"></div>
						
						<input id="button_save" type="submit" value="<?php echo __('Create user'); ?>" />
						</form>
						
					</div>
				</div>
			</div>
		</div>
						
