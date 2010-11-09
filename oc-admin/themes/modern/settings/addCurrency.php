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

<?php defined('APP_PATH') or die(__('Invalid OSClass request.')); ?>


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
					<div style="float: left;"><img src="<?php echo  $current_theme; ?>/images/back_office/settings-icon.png" /></div>
					<div id="content_header_arrow">&raquo; <?php echo __('Currencies'); ?></div> 
					<div style="clear: both;"></div>
				</div>
				
				<div id="content_separator"></div>
				<?php osc_showFlashMessages(); ?>
				
				<div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
					<div style="padding: 20px;">

						<form action="settings.php" method="post">

						<fieldset>
						<legend><?php echo __('Create'); ?></legend>

							<input type="hidden" name="action" value="addCurrency_post" />
							
							<p>
							<label for="code"><?php echo __('Code'); ?></label><br />
							<input type="text" name="pk_c_code" id="code" />
							</p>
							
							<p>
							<label for="name"><?php echo __('Name'); ?></label><br />
							<input type="text" name="s_name" id="name" />
							</p>
							
							<p>
							<label for="description"><?php echo __('Description'); ?></label><br />
							<input type="text" name="s_description" id="description" />
							</p>
							
						</fieldset>

						<input id="button_save" onclick="javascript:history.back();" value="<?php echo __('Cancel'); ?>" />
						<input id="button_save" type="submit" value="<?php echo __('Create currency'); ?>" />
						</form>
					
					</div>
				</div>
			</div>
		</div>
