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

function changeServer(box) {
	if(box.value=='gmail') {
		document.getElementById("mailserver_host").value="smtp.gmail.com";
		document.getElementById("mailserver_host").readOnly = true;
		document.getElementById("mailserver_port").value="465";
		document.getElementById("mailserver_port").readOnly = true;
		document.getElementById("mailserver_auth").checked="true";
		document.getElementById("mailserver_auth").disabled = true;
	} else {
		document.getElementById("mailserver_host").value="";
		document.getElementById("mailserver_host").readOnly = false;
		document.getElementById("mailserver_port").value="";
		document.getElementById("mailserver_port").readOnly = false;
		document.getElementById("mailserver_auth").disabled = false;
	}
}
</script>

</script>
		<div id="content">
                    <div id="separator"></div>

			<?php include_once $absolute_path . '/include/backoffice_menu.php'; ?>

		    <div id="right_column">
				<div id="content_header" class="content_header">
					<div style="float: left;"><img src="<?php echo  $current_theme; ?>/images/back_office/settings-icon.png" /></div>
					<div id="content_header_arrow">&raquo; <?php echo __('Functionalities'); ?></div>
					<div style="clear: both;"></div>
				</div>
				
				<div id="content_separator"></div>
				<?php osc_showFlashMessages(); ?>
				<!-- settings form -->
				<div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
					<div style="padding: 20px;">

						<form id="mailserver_form" action="settings.php" method="post">
						<input type="hidden" name="action" value="mailserver_post" />

                                                <div style="float: left; width: 50%;">
							<fieldset>
								<legend><?php echo __('Configuration'); ?></legend>
										<label><?php echo __('Type of server'); ?></label>
                                                                <select name="mailserver_type" id="mailserver_type" onChange="changeServer(this)">
																	<option value="custom" <?php if(isset($preferences['mailserver_type']) && $preferences['mailserver_type']=="custom") { echo 'selected=true'; }; ?>><?php echo __('Custom Server'); ?></option>
																	<option value="gmail" <?php if(isset($preferences['mailserver_type']) && $preferences['mailserver_type']=="gmail") { echo 'selected=true'; }; ?>><?php echo __('GMail Server'); ?></option>
																</select>
                                                                <br/>
                                                                <br/>

                                                                <label><?php echo __('Host Name'); ?></label>
                                                                <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text"  name="mailserver_host" id="mailserver_host" value="<?php echo $preferences['mailserver_host']; ?>"/>
                                                                <br/>
                                                                <label><?php echo __('Server Port'); ?></label>
                                                                <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text"  name="mailserver_port" id="mailserver_port" value="<?php echo $preferences['mailserver_port']; ?>"/>
                                                                <br/>
                                                                <label><?php echo __('Username'); ?></label>
                                                                <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text"  name="mailserver_username" id="mailserver_username" value="<?php echo $preferences['mailserver_username']; ?>"/>
                                                                <br/>
                                                                <label><?php echo __('Password'); ?></label>
                                                                <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="password"  name="mailserver_password" id="mailserver_password" value="<?php echo $preferences['mailserver_password']; ?>"/>
                                                                <br/>

                                                                <?php if(isset($preferences['mailserver_auth']) && $preferences['mailserver_auth']): ?>
                                                                <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" checked="true" name="mailserver_auth" id="mailserver_auth"/>
                                                                <?php else: ?>
                                                                <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" name="mailserver_auth" id="mailserver_auth"/>
                                                                <?php endif; ?>
                                                                <label><?php echo __('Enable SMTP authentication'); ?></label>
                                                        </fieldset>
						</div>

						<div style="float: left; width: 50%;">
							<fieldset>
								<legend><?php echo __('Help'); ?></legend>
                                                               <label><?php echo __('Enter your email server configuration.'); ?></label>
                                                        </fieldset>

						</div>

						<div style="clear: both;"></div>
												
						<input id="button_save" type="submit" value="<?php echo __('Update'); ?>" />
						
						</form>

					</div>
				</div>
			</div> <!-- end of right column -->
