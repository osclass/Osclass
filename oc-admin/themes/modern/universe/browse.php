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
$url = View::newInstance()->_get('url');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
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
                        <img src="<?php echo osc_current_admin_theme_url('images/tools-icon.png') ; ?>" title="" alt=""/>
                    </div>
                    <div id="content_header_arrow">&raquo; <?php _e('Browse Universe'); ?></div>
                    <div style="clear: both;"></div>
                </div>
                <div id="content_separator"></div>
                <?php osc_show_flash_message('admin') ; ?>
                <!-- add new item form -->
                <div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
                    <div style="padding: 20px;">
                        <?php _e('some text explaining this process', 'universe') ; ?>
                        <p>
                            <label for="data"><?php _e('Universe code'); ?></label>
                            <input type="text" id="s_code" name="s_code" value="" />
                        </p>
                        <p>
                            <button class="formButton" type="button" id="check_btn" ><?php _e('Get Info - CHANGE TEXT OF THIS BUTTON - ') ; ?></button>
                        </p>
                    </div>
                </div>
            </div> <!-- end of right column -->
        </div>
        <script type="text/javascript">
            $(document).ready(function(){
            });
            
            function getData(url) {
                $.getJSON(
                    url,
                    {"code" : code},
                    function(data){
                        $("#universe_error").show();
                        if(data.error==0) {
                            $("#error_msg").text(data.message);
                        } else {
                            $("#error_msg").text(data.message + " ERROR: " + data.error);
                        }
                    }
                );
            }
            
            getData('<?php echo $url; ?>');
        </script>
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>
