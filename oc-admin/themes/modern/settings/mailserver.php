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
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()) ; ?>">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
        <script type="text/javascript">
            jQuery(document).ready(function(){
                $('select[name="mailserver_type"]').bind('change', function(){
                    if( $(this).val() == 'gmail' ) {
                        $('input[name="mailserver_host"]').val('smtp.gmail.com') ;
                        $('input[name="mailserver_host"]').attr('readonly', true) ;
                        $('input[name="mailserver_port"]').val('465') ;
                        $('input[name="mailserver_port"]').attr('readonly', true) ;
                        $('input[name="mailserver_username"]').val('') ;
                        $('input[name="mailserver_password"]').val('') ;
                        $('input[name="mailserver_ssl"]').val('ssl') ;
                        $('input[name="mailserver_auth"]').attr('checked', true) ;
                        $('input[name="mailserver_pop"]').attr('checked', false) ;
                    } else {
                        $('input[name="mailserver_host"]').attr('readonly', false) ;
                        $('input[name="mailserver_port"]').attr('readonly', false) ;
                    }
                }) ;

                $('#testMail').bind('click', function() {
                    $.ajax({
                        "url": "<?php echo osc_admin_base_url(true)?>?page=ajax&action=test_mail",
                        "dataType": 'json',
                        success: function(data) {
                            $('#testMail_message p').html(data.html) ;
                            $('#testMail_message').css('display', 'block') ;
                            if( data.status == 1 ) {
                                $('#testMail_message').addClass('alert-ok');
                            } else {
                                $('#testMail_message').addClass('alert-error');
                            }
                        }
                    }) ;
                }) ;
            }) ;
        </script>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <!-- container -->
        <div id="content">
            <?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>
            <!-- right container -->
            <div class="right">
                <div class="header_title">
                    <h1 class="settings"><?php _e('Mail Settings') ; ?></h1>
                </div>
                <?php osc_show_admin_flash_messages() ; ?>
                <!-- mail-server form -->
                <div class="settings mail-server">
                    <h2><?php _e('Configuration') ; ?></h2>
                    <!-- configuration -->
                    <form action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
                        <input type="hidden" name="page" value="settings" />
                        <input type="hidden" name="action" value="mailserver_post" />
                        <div class="input-line">
                            <label><?php _e('Server type') ; ?></label>
                            <div class="input">
                                <select name="mailserver_type">
                                    <option value="custom" <?php echo (osc_mailserver_type() == 'custom') ? 'selected="true"' : '' ; ?>><?php _e('Custom Server') ; ?></option>
                                    <option value="gmail" <?php echo (osc_mailserver_type() == 'gmail') ? 'selected="true"' : '' ; ?>><?php _e('GMail Server') ; ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="input-line">
                            <label><?php _e('Hostname') ; ?></label>
                            <div class="input">
                                <input type="text" class="medium" name="mailserver_host" value="<?php echo osc_esc_html( osc_mailserver_host() ) ; ?>" />
                            </div>
                        </div>
                        <div class="input-line">
                            <label><?php _e('Server port') ; ?></label>
                            <div class="input">
                                <input type="text" class="medium" name="mailserver_port" value="<?php echo osc_esc_html( osc_mailserver_port() ) ; ?>" />
                            </div>
                        </div>
                        <div class="input-line">
                            <label><?php _e('Username') ; ?></label>
                            <div class="input">
                                <input type="text" class="medium" name="mailserver_username" value="<?php echo osc_esc_html( osc_mailserver_username() ) ; ?>" />
                            </div>
                        </div>
                        <div class="input-line">
                            <label><?php _e('Password') ; ?></label>
                            <div class="input">
                                <input type="text" class="medium" name="mailserver_password" value="<?php echo osc_esc_html( osc_mailserver_password() ) ; ?>" />
                            </div>
                        </div>
                        <div class="input-line">
                            <label><?php _e('Encryption') ; ?></label>
                            <div class="input">
                                <input type="text" class="medium" name="mailserver_ssl" value="<?php echo osc_esc_html( osc_mailserver_ssl() ) ; ?>" />
                                <p class="help-inline"><?php _e('Options: blank, ssl or tls') ; ?></p>
                                <?php if( !@apache_mod_loaded('mod_ssl') ) { ?>
                                <div class="alert alert-inline alert-warning">
                                    <p><?php _e("Apache Module <b>mod_ssl</b> is not loaded") ; ?></p>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="input-line">
                            <label><?php _e('SMTP') ; ?></label>
                            <div class="input">
                                <label>
                                    <input type="checkbox" <?php echo ( osc_mailserver_auth() ? 'checked="true"' : '' ) ; ?> name="mailserver_auth" value="1" />
                                    <p class="inline"><?php _e('SMTP authentication enabled') ; ?></p>
                                </label>
                            </div>
                        </div>
                        <div class="input-line">
                            <label><?php _e('POP') ; ?></label>
                            <div class="input">
                                <label>
                                    <input type="checkbox" <?php echo ( osc_mailserver_pop() ? 'checked="true"' : '' ) ; ?> name="mailserver_pop" value="1" />
                                    <p class="inline"><?php _e('Use POP before SMTP') ; ?></p>
                                </label>
                            </div>
                        </div>
                        <div class="actions">
                            <input type="submit" value="<?php echo osc_esc_html( __('Save changes') ) ; ?>" />
                        </div>
                    </form>
                    <!-- /configuration -->
                    <h2><?php _e('Help') ; ?></h2>
                    <p class="text"><?php printf( __('Send an e-mail to </code>%s</code> to test mail server configuration'), osc_contact_email() ) ; ?> <input id="testMail" type="button" value="<?php echo osc_esc_html( __('Send e-mail') ) ; ?>" /></p>
                    <!-- test email -->
                    <div id="testMail_message" class="alert" style="display:none;">
                        <a class="close" href="#">×</a>
                        <p></p>
                    </div>
                    <!-- /test email -->
                </div>
                <!-- /mail-server form -->
            </div>
            <!-- /right container -->
        </div>
        <!-- /container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>