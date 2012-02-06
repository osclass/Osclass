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
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <!-- container -->
        <div id="content">
            <?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>
            <!-- right container -->
            <div class="right">
                <div class="header_title">
                    <h1 class="settings"><?php _e('Spam and bots Settings') ; ?></h1>
                </div>
                <?php osc_show_admin_flash_messages() ; ?>
				<!-- settings form -->
                <div class="settings spambots">
                    <!-- akismet -->
                    <form action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
                        <input type="hidden" name="page" value="settings" />
                        <input type="hidden" name="action" value="akismet_post" />
                        <fieldset>
                            <h3><?php _e('Akismet') ; ?></h3>
                            <p class="text">
                                Akismet is a hosted web service that saves you time by automatically detecting comment and trackback spam. It's hosted on our servers, but we give you access to it through plugins and our API.
                            </p>
                            <div class="input-line">
                                <label><?php _e('Akismet API Key') ; ?></label>
                                <div class="input">
                                    <input type="text" class="medium" name="akismetKey" value="<?php echo ( osc_akismet_key() ? osc_akismet_key() : '' ) ; ?>" />
                                        <?php
                                            $akismet_status = View::newInstance()->_get('akismet_status') ;
                                            $alert_msg      = '' ;
                                            $alert_type     = 'error' ;
                                            switch($akismet_status) {
                                                case 1:
                                                    $alert_type = 'ok' ;
                                                    $alert_msg  = __('This key is valid') ;
                                                break;
                                                case 2:
                                                    $alert_type = 'error' ;
                                                    $alert_msg  = __('The key you entered is invalid. Please double-check it') ;
                                                break;
                                                case 3:
                                                    $alert_type = 'warning' ;
                                                    $alert_msg  = sprintf(__('Akismet is disabled, please enter an API key. <a href="%s" target="_blank">(Get your key)</a>'), 'http://akismet.com/get/') ; ;
                                                break;
                                            }
                                        ?>
                                    <div class="alert alert-inline alert-<?php echo $alert_type ; ?>">
                                        <p><?php echo $alert_msg ; ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="actions">
                                <input type="submit" value="<?php osc_esc_html( _e('Save changes') ) ; ?>" />
                            </div>
                        </fieldset>
                    </form>
                    <!-- /akismet -->
                    <!-- recaptcha -->
                    <form action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
                        <input type="hidden" name="page" value="settings" />
                        <input type="hidden" name="action" value="recaptcha_post" />
                        <fieldset>
                            <h3><?php _e('reCAPTCHA') ; ?></h3>
                            <p class="text">
                                <?php printf(__('reCAPTCHA helps prevent automated abuse of your site by using a CAPTCHA to ensure that only humans perform certain actions. <a href="%s" target="_blank">Get your key</a>'), 'http://www.google.com/recaptcha/whyrecaptcha') ; ?>
                            </p>
                            <div class="input-line">
                                <label><?php _e('reCAPTCHA Public key') ; ?></label>
                                <div class="input">
                                    <input type="text" class="xxlarge" name="recaptchaPubKey" value="<?php echo (osc_recaptcha_public_key() ? osc_recaptcha_public_key() : ''); ?>" />
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('reCAPTCHA Private key') ; ?></label>
                                <div class="input">
                                    <input type="text" class="xxlarge" name="recaptchaPrivKey" value="<?php echo (osc_recaptcha_private_key() ? osc_recaptcha_private_key() : ''); ?>" />
                                </div>
                            </div>
                            <div class="actions">
                                <input type="submit" value="<?php osc_esc_html( _e('Save changes') ) ; ?>" />
                            </div>
                            <?php if( osc_recaptcha_public_key() != '' ) { ?>
                            <p class="text">
                                If you see the reCAPTCHA form below this text it means that you have entered correctly the public key
                            </p>
                            <div class="recaptcha">
                            <?php
                                    require_once(osc_lib_path() . 'recaptchalib.php');
                                    $publickey = osc_recaptcha_public_key() ;
                                    echo recaptcha_get_html($publickey, false);
                                }
                            ?>
                            </div>
                        </fieldset>
                    </form>
                    <!-- /recaptcha -->
                </div>
                <!-- /settings form -->
            </div>
            <!-- /right container -->
        </div>
        <!-- /container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>