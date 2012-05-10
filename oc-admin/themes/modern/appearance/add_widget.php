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

    // getting variables for this view
    $info   = __get("info") ;
    $widget = __get("widget") ;

    if( Params::getParam('action') == 'edit_widget' ) {
        $title  = __('Edit widget') ;
        $edit   = true ;
        $button = osc_esc_html( __('Save changes') ) ;
    } else {
        $title  = __('Add widget') ;
        $edit   = false ;
        $button = osc_esc_html( __('Add widget') ) ;
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()) ; ?>">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('tiny_mce/tiny_mce.js') ; ?>"></script>
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.validate.min.js') ; ?>"></script>
        <script type="text/javascript">
            tinyMCE.init({
                mode : "textareas",
                theme : "advanced",
                theme_advanced_buttons1 : "bold,italic,underline,code",
                theme_advanced_buttons2 : "",
                theme_advanced_buttons3 : "",
                theme_advanced_toolbar_location : "top",
                theme_advanced_toolbar_align : "left",
                theme_advanced_statusbar_location : "bottom",
                extended_valid_elements : "script[type|src|charset|defer]"
            });

            <?php if( $edit ) { ?>
            $(window).load(function() {
                <?php $str = htmlentities(str_replace(array("\r", "\n"), array("\\r", "\\n"), $widget['s_content']) , null, 'UTF-8') ; ?>
                var aux = <?php echo "'" . addcslashes($str, "'") . "'" ; ?> ;
                var str = $("<div/>").html(aux).text() ;
                tinyMCE.activeEditor.setContent( str.replace("\\n", '\n' ) ) ;
            }) ;
            <?php } ?>
        </script>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <script type="text/javascript">
            $(document).ready(function(){
                // Code for form validation
                $("form[name=widget_form]").validate({
                    rules: {
                        description: {
                            required: true
                        }
                    },
                    messages: {
                        description: {
                            required:  "<?php _e("Description: this field is required"); ?>."
                        }
                    },
                    errorLabelContainer: "#error_list",
                    wrapper: "li",
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
                    <h1 class="widgets"><?php echo $title ; ?></h1>
                </div>
                <?php osc_show_flash_message('admin') ; ?>
                <!-- add widgets form -->
                <div class="widgets">
                    <ul id="error_list" style="display: none;"></ul>
                    <form name="widget_form" action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
                        <input type="hidden" name="action" value="<?php echo ( $edit ? 'edit_widget_post' : 'add_widget_post' ) ; ?>" />
                        <input type="hidden" name="page" value="appearance" />
                        <?php if( $edit) { ?>
                        <input type="hidden" name="id" value="<?php echo Params::getParam('id') ; ?>" />
                        <?php } ?>
                        <input type="hidden" name="location" value="<?php echo Params::getParam('location') ; ?>" />
                        <fieldset>
                            <div class="input-line">
                                <label><?php _e('Description (only for internal purposes)') ; ?></label>
                                <div class="input">
                                    <input type="text" class="large" name="description" value="<?php if( $edit ) { echo osc_esc_html($widget['s_description']) ; } ?>"/>
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('HTML Code for the Widget') ; ?></label>
                                <div class="input">
                                    <textarea name="content" id="body" style="width: 90%; height: 300px;"></textarea>
                                </div>
                            </div>
                            <div class="actions">
                                <input type="submit" value="<?php echo $button ; ?>" />
                            </div>
                        </fieldset>
                    </form>
                </div>
                <!-- /add widgets form -->
            </div>
            <!-- /right container -->
        </div>
        <!-- /container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>