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
        </script>
        <?php if(isset($action) && $action === "add_widget") { ?>
            <script type="text/javascript">
                $(function() {
                    // change title of the page
                    $(this).attr("title", "<?php _e('Dashboard - Add New Widget'); ?>") ;

                    // remove stuff that we don't need
                    $('#button_open').remove();
                    $('#datatables_list_wrapper').remove();
                    $('#pages_desc').remove();

                    // change style of the div
                    $('#main_div').css('margin-top', '21px');
                    $('#main_div').css('border', '1px solid #ccc');
                    $('#main_div input').css('width', '99%');
                    $('#main_div').show();
                });
            </script>
        <?php
        }
        ?>
        <div id="content">
            <div id="separator"></div>

            <?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>

            <div id="right_column">

                <div id="content_header" class="content_header">
                    <div style="float: left;">
                        <img src="<?php echo osc_current_admin_theme_url('images/plugins-icon.png'); ?>" title="" alt="" />
                    </div>
                    <div id="content_header_arrow">&raquo; <?php echo sprintf(__('Create widget in %s'), Params::getParam('location')); ?></div>
                    <div style="clear: both;"></div>
                </div>

                <div id="content_separator"></div>
                
                <?php osc_show_flash_message('admin') ; ?>
                <!-- add new theme form -->
                <div id="main_div" style="border: 1px solid #ccc; background: #eee; display:block;">
                    <div style="padding: 20px; padding-top: 10px;">

                        <form action="<?php echo osc_admin_base_url(true); ?>" method="post">
                            <input type="hidden" name="action" value="<?php echo (Params::getParam('action') == 'edit_widget') ? 'edit_widget_post' : 'add_widget_post';?>" />
                            <input type="hidden" name="page" value="appearance" />
                            <?php if (Params::getParam('action') == 'edit_widget') echo '<input type="hidden" name="id" value="'.Params::getParam('id').'" />'; ?>
                            <input type="hidden" name="location" value="<?php echo $_GET['location'] ; ?>" />

                            <fieldset>
                                <legend><?php _e('Description (only for internal purposes)'); ?></legend>
                                <input type="text" name="description" id="description" value="<?php if(isset($widget['s_description'])) echo htmlentities($widget['s_description'],null, "UTF-8");?>"/>
                            </fieldset>

                            <fieldset>
                                <legend><?php _e('HTML Code for the Widget'); ?></legend>
                                <textarea name="content" id="body" style="width: 100%; height: 300px;"></textarea>
                            </fieldset>

                            <input id="button_save" type="submit" value="<?php _e('Publish widget') ; ?>" />
                        </form>
                    </div>
                </div>
               
            </div>
        </div>
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
    <script type="text/javascript" >
        $(window).load(function() {
            <?php $str = htmlentities(str_replace(array("\r", "\n"), array("\\r", "\\n"), $widget['s_content']) , null, 'UTF-8');?>
            var aux = ('<?php echo (addcslashes($str, '\'') );?>');
            var str = $("<div/>").html(aux).text();
            tinyMCE.activeEditor.setContent( str.replace("\\n", '\n' ) );
        });
     </script>
</html>