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
<script type="text/javascript">
    function changeServer( box ) {
	if( box.value == 'gmail' ) {
            document.getElementById("mailserver_host").value = 'smtp.gmail.com';
            document.getElementById("mailserver_host").readOnly = true;
            document.getElementById("mailserver_port").readOnly = true;
            document.getElementById("mailserver_port").value = '465';
            document.getElementById("mailserver_auth").checked = 'true';
            document.getElementById("mailserver_ssl").value = 'ssl';
	}
    }
</script>
<div id="content">
    <div id="separator"></div>

    <?php include_once osc_current_admin_theme_path() . '/include/backoffice_menu.php'; ?>

    <div id="right_column">
        <div id="content_header" class="content_header">
            <div style="float: left;"><img src="<?php echo  osc_current_admin_theme_url() ; ?>/images/back_office/settings-icon.png" /></div>
            <div id="content_header_arrow">&raquo; <?php _e('Functionalities'); ?></div>
            <div style="clear: both;"></div>
        </div>

        <div id="content_separator"></div>
        <?php osc_show_flash_messages(); ?>
        <!-- settings form -->
        <div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
            <div style="padding: 20px;">

                <form id="mailserver_form" action="settings.php" method="post">
                    <input type="hidden" name="action" value="mailserver_post" />

                    <div style="float: left; width: 50%;">
                        <fieldset>
                            <legend><?php _e('Configuration'); ?></legend>
                            <label><?php _e('Type of server'); ?></label>
                            <select name="mailserver_type" id="mailserver_type" onChange="changeServer(this)">
                                <option value="custom" <?php (osc_mailserver_type() == 'custom') ? echo 'selected="true"' : '' ; ?>><?php _e('Custom Server') ; ?></option>
                                <option value="gmail" <?php (osc_mailserver_type() == 'gmail') ? echo 'selected="true"' : '' ; ?>><?php _e('GMail Server'); ?></option>
                            </select>
                            <br/>
                            <br/>

                            <label><?php _e('Host Name'); ?></label>
                            <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text"  name="mailserver_host" id="mailserver_host" value="<?php echo osc_mailserver_host() ; ?>" />
                            <br/>
                            <label><?php _e('Server Port'); ?></label>
                            <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text"  name="mailserver_port" id="mailserver_port" value="<?php echo osc_mailserver_port() ; ?>" />
                            <br/>
                            <label><?php _e('Username'); ?></label>
                            <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text"  name="mailserver_username" id="mailserver_username" value="<?php echo osc_mailserver_username() ; ?>" />
                            <br/>
                            <label><?php _e('Password'); ?></label>
                            <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="password"  name="mailserver_password" id="mailserver_password" value="<?php echo osc_mailserver_password() ; ?>" />
                            <br/>
                            <label><?php _e('Encryption'); ?></label>
                            <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text"  name="mailserver_ssl" id="mailserver_ssl" value="<?php echo osc_mailserver_ssl() ; ?>" />
                            <br/>
                            <small><?php _e('leave it empty if there isn\'t encryption, ssl or tls'); ?></small>
                            <br/>
                            <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" <?php (osc_mailserver_auth()) ? echo 'checked="true"' : echo '' ; ?> name="mailserver_auth" id="mailserver_auth" />
                            <label><?php _e('Enable SMTP authentication'); ?></label>
                        </fieldset>
                    </div>

                    <div style="float: left; width: 50%;">
                        <fieldset>
                            <legend><?php _e('Help') ; ?></legend>
                            <label><?php _e('Enter your email server configuration.') ; ?></label>
                        </fieldset>
                    </div>

                    <div style="clear: both;"></div>

                    <input id="button_save" type="submit" value="<?php _e('Update') ; ?>" />
                </form>

            </div>
        </div>
    </div> <!-- end of right column -->
</div>