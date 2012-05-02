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

     $aCurrency = View::newInstance()->_get('aCurrency') ;
     $typeForm  = View::newInstance()->_get('typeForm') ;

     if( $typeForm == 'add_post' ) {
         $title  = __('Add Currency') ;
         $submit = osc_esc_html( __('Add new currency') ) ;
     } else {
         $title  = __('Edit Currency') ;
         $submit = osc_esc_html( __('Update') ) ;
     }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()) ; ?>">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.validate.min.js') ; ?>"></script>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <script type="text/javascript">
            $(document).ready(function(){
                // Code for form validation
                $("form[name=currency_form]").validate({
                    rules: {
                        pk_c_code: {
                            required: true,
                            minlength: 3,
                            maxlength: 3
                        },
                        s_name: {
                            required: true,
                            minlength: 1
                        }
                    },
                    messages: {
                        pk_c_code: {
                            required: "<?php _e("Currency code: this field is required"); ?>.",
                            minlength: "<?php _e("Currency code: this field is required"); ?>.",
                            maxlength: "<?php _e("Currency code: this field is required"); ?>."
                        },
                        s_name: {
                            required: "<?php _e("Name: this field is required"); ?>.",
                            minlength: "<?php _e("Name: this field is required"); ?>."
                        }
                    },
                    wrapper: "li",
                    errorLabelContainer: "#error_list",
                    invalidHandler: function(form, validator) {
                        $('html,body').animate({ scrollTop: $('h1').offset().top }, { duration: 250, easing: 'swing'});
                    }
                });
            });
        </script>
        <!-- container -->
        <div id="content">
            <?php osc_current_admin_theme_path( 'include/backoffice_menu.php' ) ; ?>
            <!-- right container -->
		    <div class="right">
                <div class="header_title">
                    <h1 class="currencies"><?php echo $title ; ?></h1>
                </div>
                <?php osc_show_flash_message('admin') ; ?>
                <!-- currency-form form -->
                <div class="settings currency-form">
                    <ul id="error_list" style="display: none;"></ul>
                    <form name="currency_form" action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
                        <input type="hidden" name="page" value="settings" />
                        <input type="hidden" name="action" value="currencies" />
                        <input type="hidden" name="type" value="<?php echo $typeForm ; ?>" />
                        <?php if( $typeForm == 'edit_post' ) { ?>
                        <input type="hidden" name="pk_c_code" value="<?php echo osc_esc_html($aCurrency['pk_c_code']) ; ?>" />
                        <?php } ?>
                        <fieldset>
                            <table class="table-backoffice-form">
                                <tr>
                                    <td class="labeled"><?php _e('Currency Code') ; ?></td>
                                    <td>
                                        <input type="text" class="medium" name="pk_c_code" value="<?php echo osc_esc_html($aCurrency['pk_c_code']) ; ?>" <?php if( $typeForm == 'edit_post' ) echo 'disabled' ; ?>/>
                                        <span class="help-box"><?php _e('It should be a three-character code') ; ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php _e('Name') ; ?></td>
                                    <td>
                                        <input type="text" class="medium" name="s_name" value="<?php echo osc_esc_html($aCurrency['s_name']) ; ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php _e('Description') ; ?></td>
                                    <td>
                                        <input type="text" class="xlarge" name="s_description" value="<?php echo osc_esc_html($aCurrency['s_description']) ; ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php _e('Description') ; ?></td>
                                    <td>
                                        <input type="submit" value="<?php echo $submit ; ?>" />
                                        <input type="button" onclick="location.href='<?php echo osc_admin_base_url(true) . '?page=settings&action=currencies' ; ?>'" value="<?php echo osc_esc_html( __('Cancel') ) ; ?>" />
                                    </td>
                                </tr>
                            </table>
                        </fieldset>
                    </form>
                </div>
                <!-- /currency-form form -->
            </div>
            <!-- /right container -->
        </div>
        <!-- /container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>
