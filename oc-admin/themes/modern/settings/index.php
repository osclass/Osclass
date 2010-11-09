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
			<div id="content_header_arrow">&raquo; <?php echo __('General settings'); ?></div> 
			<div style="clear: both;"></div>
		</div>
		
		<div id="content_separator"></div>
		<?php osc_showFlashMessages(); ?>
		
		<!-- settings form -->
		<div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
			<div style="padding: 20px;">

				<form action="settings.php" method="post">
				<input type="hidden" name="action" value="update" />
				
				<div style="float: left; width: 50%;">
					<fieldset>
						<legend><?php echo __('Page title'); ?></legend>
						<input style="width: 95%; height: 20px; padding-left: 4px;" type="text" name="pageTitle" id="pageTitle" value="<?php echo $preferences['pageTitle']; ?>" />
					</fieldset>
				</div>

				<div style="float: left; width: 50%;">						
					<fieldset>
						<legend><?php echo __('Page description'); ?></legend>
						<input style="width: 95%; height: 20px; padding-left: 4px;" type="text" name="pageDesc" id="pageDesc" value="<?php echo $preferences['pageDesc']; ?>" />
					</fieldset>
				</div>

                                        <div style="clear: both;"></div>

				<div style="float: left; width: 50%;">
					<fieldset>
						<legend><?php echo __('Administrator E-mail'); ?></legend>
						<input style="width: 95%; height: 20px; padding-left: 4px;" type="text" name="contactEmail" id="contactEmail" value="<?php echo $preferences['contactEmail']; ?>" />
					</fieldset>
				</div>
                                        																	
				<div style="float: left; width: 50%;">
					<fieldset>
						<legend><?php echo __('Default language'); ?></legend>
						<select name="language" id="language">
						<?php foreach($languages as $lang) { ?>
							<?php if($preferences['language'] == $lang['pk_c_code']) { ?>
								<option value="<?php echo $lang['pk_c_code']; ?>" selected="selected"><?php echo $lang['s_name']; ?></option>
							<?php } else { ?>
								<option value="<?php echo $lang['pk_c_code']; ?>"><?php echo $lang['s_name']; ?></option>
							<?php } ?>
						<?php } ?>
						</select>
					</fieldset>
				</div>

                <div style="clear: both;"></div>

				<div style="float: left; width: 50%;">
                    <fieldset>
                            <legend><?php echo __('Date format'); ?></legend>
                            <div style="font-size: small; margin: 0px;">
                            <?php $checked = false; foreach($dateFormats as $df): ?>
                                    <?php if($df == $preferences['dateFormat']): $checked = true; ?>
                                            <input type="radio" name="df" id="<?php echo $df; ?>" value="<?php echo $df; ?>" checked="checked" onclick="document.getElementById('dateFormat').value = '<?php echo $df; ?>';"/>
                                    <?php else: ?>
                                            <input type="radio" name="df" id="<?php echo $df; ?>" value="<?php echo $df; ?>" onclick="document.getElementById('dateFormat').value = '<?php echo $df; ?>';"/>
                                    <?php endif; ?>

                                    <label for="<?php echo $df; ?>"><?php echo date($df); ?></label><br />
                            <?php endforeach; ?>

                            <?php if(!$checked): ?>
                            <input type="radio" name="df" id="df_custom" value="-" checked="checked" />
                            <?php else: ?>
                            <input type="radio" name="df" id="df_custom" value="-" />
                            <?php endif; ?>
                            <label for="df_custom"><?php echo __('Custom'); ?>:</label> <input type="text" name="dateFormat" id="dateFormat" value="<?php echo $preferences['dateFormat']; ?>" />
                            </div>
                    </fieldset>
				</div>

                <div style="float: left; width: 50%;">
					<fieldset>
						<legend><?php echo __('Week starts on'); ?></legend>
						<select name="weekStart" id="weekStart">
                                                        <option value="0" selected="selected"><?php echo __('Sunday'); ?></option>
						<option value="1" <?php if(isset($preferences["weekStart"]) && $preferences["weekStart"] == '1') { ?>selected="selected"<?php } ?>><?php echo __('Monday'); ?></option>
						<option value="2" <?php if(isset($preferences["weekStart"]) && $preferences["weekStart"] == '2') { ?>selected="selected"<?php } ?>><?php echo __('Tuesday'); ?></option>
						<option value="3" <?php if(isset($preferences["weekStart"]) && $preferences["weekStart"] == '3') { ?>selected="selected"<?php } ?>><?php echo __('Wednesday'); ?></option>
						<option value="4" <?php if(isset($preferences["weekStart"]) && $preferences["weekStart"] == '4') { ?>selected="selected"<?php } ?>><?php echo __('Thursday'); ?></option>
						<option value="5" <?php if(isset($preferences["weekStart"]) && $preferences["weekStart"] == '5') { ?>selected="selected"<?php } ?>><?php echo __('Friday'); ?></option>
						<option value="6" <?php if(isset($preferences["weekStart"]) && $preferences["weekStart"] == '6') { ?>selected="selected"<?php } ?>><?php echo __('Saturday'); ?></option>
						</select>
					</fieldset>

					<fieldset>
						<legend><?php echo __('Number of items in the RSS'); ?></legend>
                        <select name="num_rss_items" id="num_rss_items">
                            <option value="10" selected="selected">10</option>
                            <option value="25" <?php if(isset($preferences["num_rss_items"]) && $preferences["num_rss_items"] == '25') { ?>selected="selected"<?php } ?>>25</option>
                            <option value="50" <?php if(isset($preferences["num_rss_items"]) && $preferences["num_rss_items"] == '50') { ?>selected="selected"<?php } ?>>50</option>
                            <option value="75" <?php if(isset($preferences["num_rss_items"]) && $preferences["num_rss_items"] == '75') { ?>selected="selected"<?php } ?>>75</option>
                            <option value="100" <?php if(isset($preferences["num_rss_items"]) && $preferences["num_rss_items"] == '100') { ?>selected="selected"<?php } ?>>100</option>
                            <option value="150" <?php if(isset($preferences["num_rss_items"]) && $preferences["num_rss_items"] == '150') { ?>selected="selected"<?php } ?>>150</option>
                            <option value="200" <?php if(isset($preferences["num_rss_items"]) && $preferences["num_rss_items"] == '200') { ?>selected="selected"<?php } ?>>200</option>
						</select>
					</fieldset>
                </div>

                <div style="clear: both;"></div>
				
				<div style="float: left; width: 50%;">
                    <fieldset>
                            <legend><?php echo __('Time format'); ?></legend>
                            <label for="timeFormat"><?php echo __('Time format'); ?></label><br />
                            <div style="font-size: small; margin: 0px;">
                            <?php $checked = false; foreach($timeFormats as $tf): ?>
                                    <?php if($tf == $preferences['timeFormat']): $checked = true; ?>
                                            <input type="radio" name="tf" id="<?php echo $tf; ?>" value="<?php echo $tf; ?>" checked="checked" onclick="document.getElementById('timeFormat').value = '<?php echo $tf; ?>';" />
                                    <?php else: ?>
                                            <input type="radio" name="tf" id="<?php echo $tf; ?>" value="<?php echo $tf; ?>" onclick="document.getElementById('timeFormat').value = '<?php echo $tf; ?>';" />
                                    <?php endif; ?>
                                    <label for="<?php echo $tf; ?>"><?php echo date($tf); ?></label><br />
                            <?php endforeach; ?>
                            <?php if(!$checked): ?>
                            <input type="radio" name="tf" id="tf_custom" value="-" checked="checked" />
                            <?php else: ?>
                            <input type="radio" name="tf" id="tf_custom" value="-" />
                            <?php endif; ?>
                            <label for="tf_custom"><?php echo __('Custom'); ?>:</label> <input type="text" name="timeFormat" id="timeFormat" value="<?php echo $preferences['timeFormat']; ?>" />
                            </div>
                    </fieldset>
				</div>

				<div style="clear: both;"></div>
										
				<input id="button_save" type="submit" value="<?php echo __('Update'); ?>" />
				
				</form>

			</div>
		</div>
	</div> <!-- end of right column -->
