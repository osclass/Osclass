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
        <script type="text/javascript">
            var base_url    = '<?php echo osc_base_url() ; ?>';
            var s_close     = '<?php _e('Close'); ?>';
            var s_view_more = '<?php _e('View more'); ?>';
        </script>
        <?php osc_current_admin_theme_path('head.php') ; ?>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <div id="update_version" style="display:none;"></div>
        <div id="content">
            <div id="separator"></div>
            <?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>
            <div id="right_column">
                <div id="content_header" class="content_header">
                    <div style="float: left;">
                        <img src="<?php echo osc_current_admin_theme_url('images/settings-icon.png') ; ?>" alt="" title=""/>
                    </div>
                    <div id="content_header_arrow">&raquo; <?php _e('Spam and bots') ; ?></div>
                    <div style="clear: both;"></div>
                </div>
                <div id="content_separator"></div>
                <?php osc_show_flash_message('admin') ; ?>
                <div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
                    <div style="padding: 20px;">

                        <form action="<?php echo osc_admin_base_url(true); ?>" method="post">
                            <input type="hidden" name="page" value="settings" />
                            <input type="hidden" name="action" value="spamNbots_post" />
                            
                            <fieldset>
                                <legend><?php _e('Akismet'); ?></legend>
                                <p>
                                    <label for="akismetKey"><?php _e('Akismet key (same as Wordpress.com)'); ?></label><br />
                                    <input type="text" name="akismetKey" id="akismetKey" value="<?php echo (osc_akismet_key() ? osc_akismet_key() : ''); ?>" /><br />
                                    <span class="Explanation"><?php _e('If the field is empty it\'s because the Akismet service is disabled'); ?>. <?php _e('Get your free key at'); ?> <a href="http://akismet.com">http://akismet.com</a></span>.
                                </p>
                            </fieldset>

                            <fieldset>
                                <legend><?php _e('ReCAPTCHA') ; ?></legend>
                                <p>
                                    <?php _e('If the field is empty it\'s because the reCAPTCHA service is disabled'); ?>. <?php _e('Get your free keys at') ; ?> <a href="http://recaptcha.net" target="_blank">http://recaptcha.net</a>.
                                </p>
                                <p>
                                    <label for="recaptchaPubKey"><?php _e('reCAPTCHA public key'); ?></label><br />
                                    <input type="text" name="recaptchaPubKey" id="recaptchaPubKey" value="<?php echo (osc_recaptcha_public_key() ? osc_recaptcha_public_key() : ''); ?>" />
                                </p>
                                <p>
                                    <label for="recaptchaPrivKey"><?php _e('reCAPTCHA private key'); ?></label><br />
                                    <input type="text" name="recaptchaPrivKey" id="recaptchaPrivKey" value="<?php echo (osc_recaptcha_private_key() ? osc_recaptcha_private_key() : ''); ?>" />
                                </p>
                            </fieldset>

                            <input id="button_save" type="submit" value="<?php _e('Update'); ?>" />
                        </form>
                    </div>
                </div>
            </div><!-- end of right column -->
        </div><!-- end of container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>