<?php
    /**
     * OSClass – software for creating and publishing online classified advertising platforms
     *
     * Copyright (C) 2010 OSCLASS
     *
     * This program is free software: you can redistribute it and/or modify it under the terms
     * of the GNU Affero General Public License as published by the Free Software Foundation,
     * either version 3 of the License, or (at your option) any later version.
     *
     * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
     * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
     * See the GNU Affero General Public License for more details.
     *
     * You should have received a copy of the GNU Affero General Public
     * License along with this program. If not, see <http://www.gnu.org/licenses/>.
     */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <div id="update_version" style="display:none;"></div>
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
            function sendEmail() {
                var mail = $('#mail_delivery').val();
                $.ajax({
                    "url": "<?php echo osc_admin_base_url(true)?>?page=ajax&action=test_mail",
                    "dataType": 'json',
                    success: function( result ) {
                        if(result.status == 1){
                            $("fieldset.test_email div#flash_message").html(result.html);
                            $("fieldset.test_email div#flash_message").css('background-color','green');
                            $("fieldset.test_email div#flash_message").show();
                        } else {
                            $("fieldset.test_email div#flash_message").html(result.html);
                            $("fieldset.test_email div#flash_message").css('background-color','pink');
                            $("fieldset.test_email div#flash_message").show();
                        }
                    }
                });
            }
        </script>
        <div id="content">
            <div id="separator"></div>
            <?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>
            <div id="right_column">
                <div id="content_header" class="content_header">
                    <div style="float: left;">
                        <img src="<?php echo osc_current_admin_theme_url('images/settings-icon.png') ; ?>" alt="" title=""/>
                    </div>
                    <div id="content_header_arrow">&raquo; <?php _e('Mail server'); ?></div>
                    <div style="clear: both;"></div>
                </div>
                <div id="content_separator"></div>
                <?php osc_show_flash_message('admin'); ?>
                <!-- settings form -->
                <div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
                    <div style="padding: 20px;">
                        <form id="mailserver_form" action="<?php echo osc_admin_base_url(true); ?>" method="post">
                            <input type="hidden" name="page" value="settings" />
                            <input type="hidden" name="action" value="mailserver_post" />
                            <div style="float: left; width: 50%;">
                                <fieldset>
                                    <legend><?php _e('Configuration'); ?></legend>
                                    <label><?php _e('Server type'); ?></label>
                                    <select name="mailserver_type" id="mailserver_type" onChange="changeServer(this)">
                                        <option value="custom" <?php echo (osc_mailserver_type() == 'custom') ? 'selected="true"' : '' ; ?>><?php _e('Custom Server') ; ?></option>
                                        <option value="gmail" <?php echo (osc_mailserver_type() == 'gmail') ? 'selected="true"' : '' ; ?>><?php _e('GMail Server'); ?></option>
                                    </select>
                                    <p>
                                        <label><?php _e('Hostname'); ?></label>
                                        <br/>
                                        <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text"  name="mailserver_host" id="mailserver_host" value="<?php echo osc_esc_html(osc_mailserver_host()) ; ?>" />
                                    </p>
                                    <p>
                                        <label><?php _e('Server port'); ?></label>
                                        <br/>
                                        <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text"  name="mailserver_port" id="mailserver_port" value="<?php echo osc_esc_html(osc_mailserver_port()) ; ?>" />
                                    </p>
                                    <p>
                                        <label><?php _e('Username'); ?></label>
                                        <br/>
                                        <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text"  name="mailserver_username" id="mailserver_username" value="<?php echo osc_esc_html(osc_mailserver_username()) ; ?>" />
                                    </p>
                                    <p>
                                        <label><?php _e('Password'); ?></label>
                                        <br/>
                                        <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="password"  name="mailserver_password" id="mailserver_password" value="<?php echo osc_esc_html(osc_mailserver_password()) ; ?>" />
                                    </p>
                                    <p>
                                        <label><?php _e('Encryption'); ?></label>
                                        <br/>
                                        <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="text"  name="mailserver_ssl" id="mailserver_ssl" value="<?php echo osc_esc_html(osc_mailserver_ssl()) ; ?>" />
                                        <small><?php _e('Options: blank, ssl or tls'); ?></small>
                                    </p>
                                    <p>
                                        <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" <?php echo (osc_mailserver_auth() ? 'checked="true"' : ''); ?> name="mailserver_auth" id="mailserver_auth" />
                                        <label for="mailserver_auth"><?php _e('Enable SMTP authentication'); ?></label>
                                    </p>
                                    <p>
                                        <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" <?php echo (osc_mailserver_pop() ? 'checked="true"' : ''); ?> name="mailserver_pop" id="mailserver_pop" />
                                        <label for="mailserver_pop"><?php _e('Use a POP before SMTP'); ?></label>
                                    </p>
                                </fieldset>
                            </div>

                            <div style="float: left; width: 50%;">
                                <fieldset>
                                    <legend><?php _e('Help'); ?></legend>
                                    <label><?php _e('Enter your e-mail server configuration'); ?></label>

                                    <?php if( function_exists('@apache_get_modules') ) { ?>
                                    <?php
                                        $aModules = @apache_get_modules();
                                        $ssl = false;
                                        foreach( $aModules as  $mod ){
                                            if($mod == 'mod_ssl') { $ssl = true; }
                                        }
                                    ?>
                                    <?php if(!$ssl){?>
                                    <div id="flash_message">
                                        <p>mod_ssl <?php _e('not found');?></p>
                                    </div>
                                    <?php }?>
                                    <?php }?>
                                    
                                </fieldset>
                            </div>

                            <div style="float: left; width: 50%;">
                                <fieldset class="test_email">
                                    <legend><?php _e('Test configuration'); ?></legend>
                                    <p><?php _e('The email will be sent to contact email');?>: <?php echo osc_contact_email();?></p>
                                    <p>
                                        <button onclick="sendEmail();return false;"><?php _e('Send email');?></button>
                                    </p>
                                    <div id="flash_message" style="display: none;">
                                    </div>
                                    
                                </fieldset>
                            </div>
                            <div style="clear: both;"></div>
                            <input id="button_save" type="submit" value="<?php osc_esc_html(_e('Update')) ; ?>" />
                        </form>
                    </div>
                </div>
            </div> <!-- end of right column -->
        </div><!-- end of container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>