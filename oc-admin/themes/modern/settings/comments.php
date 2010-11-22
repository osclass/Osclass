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

<?php
$dateFormats = array('F j, Y', 'Y/m/d', 'm/d/Y', 'd/m/Y');
$timeFormats = array('g:i a', 'g:i A', 'H:i');
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
					<div style="float: left;"><img src="<?php echo  $current_theme; ?>/images/back_office/settings-icon.png" /></div>
					<div id="content_header_arrow">&raquo; <?php echo __('Notifications'); ?></div>
					<div style="clear: both;"></div>
				</div>
				
				<div id="content_separator"></div>
				<?php osc_showFlashMessages(); ?>
				<!-- settings form -->
				<div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
					<div style="padding: 20px;">

						<form action="settings.php" method="post">
						<input type="hidden" name="action" value="comments_post" />
						
						<div style="float: left; width: 50%;">
							<fieldset>
								<legend><?php echo __('Settings'); ?></legend>
                                                                <?php if(isset($preferences['enabled_comments']) && $preferences['enabled_comments']): ?>
                                                                <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" checked="true" name="enabled_comments" id="enabled_comments"/>
                                                                <?php else: ?>
                                                                <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" name="enabled_comments" id="enabled_comments"/>
                                                                <?php endif; ?>
                                                                <label><?php echo __('Comments enabled'); ?></label>

																<br/>

                                                                <?php if(isset($preferences['moderate_comments']) && $preferences['moderate_comments']): ?>
                                                                <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" checked="true" name="moderate_comments" id="moderate_comments"/>
                                                                <?php else: ?>
                                                                <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" name="moderate_comments" id="moderate_comments"/>
                                                                <?php endif; ?>
                                                                <label><?php echo __('Moderate comments'); ?></label>
                                                        </fieldset>
						</div>
						<div style="float: left; width: 50%;">
							<fieldset>
								<legend><?php echo __('Notifications'); ?></legend>
                                                                <?php if(isset($preferences['notify_new_comment']) && $preferences['notify_new_comment']): ?>
                                                                <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" checked="true" name="notify_new_comment" id="notify_new_comment"/>
                                                                <?php else: ?>
                                                                <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" name="notify_new_comment" id="notify_new_comment"/>
                                                                <?php endif; ?>
                                                                <label><?php echo __('Notify new comment'); ?></label>
							</fieldset>
						</div>

						<div style="clear: both;"></div>
												
						<input id="button_save" type="submit" value="<?php echo __('Update'); ?>" />
						
						</form>

					</div>
				</div>
			</div> <!-- end of right column -->
