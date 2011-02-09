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

<?php defined('ABS_PATH') or die(__('Invalid OSClass request.')); ?>

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

			<?php include_once osc_current_admin_theme_path() . 'include/backoffice_menu.php'; ?>

		    <div id="right_column">
				<div id="content_header" class="content_header">
					<div style="float: left;"><img src="<?php echo  osc_current_admin_theme_url() ; ?>images/back_office/settings-icon.png" /></div>
					<div id="content_header_arrow">&raquo; <?php _e('Notifications'); ?></div>
					<div style="clear: both;"></div>
				</div>
				
				<div id="content_separator"></div>
				<?php osc_show_flash_message() ; ?>
				<!-- settings form -->
				<div id="settings_form" style="border: 1px solid #ccc; background: #eee;">
					<div style="padding: 20px;">

						<form action="settings.php" method="post">
                            <input type="hidden" name="action" value="notifications_post" />

                            <div style="float: left; width: 50%;">
                                <fieldset>
                                    <legend><?php _e('Items'); ?></legend>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" <?php (osc_notify_new_item()) ? echo 'checked="true"' : echo '' ; ?> name="notify_new_item" id="notify_new_item" />
                                    <label><?php _e('Notify new item to admin') ; ?></label>
                                    <br/>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" <?php (osc_notify_contact_item()) ? echo 'checked="true"' : echo '' ; ?> name="notify_contact_item" id="notify_contact_item" />
                                    <label><?php _e('Notify contact item to admin') ; ?></label>
                                    <br/>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" <?php (osc_notify_contact_friends()) ? echo 'checked="true"' : echo '' ; ?> name="notify_contact_friends" id="notify_contact_friends" />
                                    <label><?php _e('Notify contact friends to admin') ; ?></label>
                                    <br/>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" <?php (osc_enabled_item_validation()) ? echo 'checked="true"' : echo '' ; ?> name="enabled_item_validation" id="enabled_item_validation" />
                                    <label><?php _e('Enable item validation') ; ?></label>
                                </fieldset>
                            </div>

                            <div style="float: left; width: 50%;">
                                <fieldset>
                                    <legend><?php _e('Comments') ; ?></legend>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" <?php (osc_notify_new_comment()) ? echo 'checked="true"' : echo '' ; ?> name="notify_new_comment" id="notify_new_comment" />
                                    <label><?php _e('Notify new comment') ; ?></label>
                                </fieldset>
                            </div>

                            <div style="clear: both;"></div>

                            <input id="button_save" type="submit" value="<?php _e('Update') ; ?>" />
						</form>
                        
					</div>

				</div>
                
			</div> <!-- end of right column -->
