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
            $(document).ready(function(){
                $("#universe_info").hide();
                $("#universe_error").hide();
                $("#check_btn").click(function(){
                    var code = $("#s_code").attr("value");
                    $("#s_code").attr("readonly", "readonly");
                    $.getJSON(
                        "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=check_universe",
                        {"code" : code},
                        function(data){
                            if(data.error==0) {
                                $("#universe_info").show();
                                $("#name").text(data.s_name);
                                $("#version").text(data.s_version);
                                $("#description").text(data.s_description);
                                $("#url").text(data.s_source_file);
                            } else {
                                $("#universe_error").show();
                                $("#error_msg").text(data.error_msg);
                            }
                        }
                    );
                });

                $("#download_btn").click(function(){
                    var code = $("#s_code").attr("value");
                    $("#s_code").attr("readonly", "readonly");
                    $.getJSON(
                        "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=universe",
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
                });

                $("#cancel_btn").click(function(){
                    $("#universe_info").hide();
                    $("#s_code").removeAttr("readonly");
                });
            });
        </script>
        
        <div id="content">
            <div id="separator"></div>
            <?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>
            <div id="right_column">
                <div id="content_header" class="content_header">
                    <div style="float: left;">
                        <img src="<?php echo osc_current_admin_theme_url('images/tools-icon.png') ; ?>" title="" alt=""/>
                    </div>
                    <div id="content_header_arrow">&raquo; <?php _e('Universe'); ?></div>
                    <div style="clear: both;"></div>
                </div>
                <div id="content_separator"></div>
                <?php osc_show_flash_message('admin') ; ?>
                <!-- add new item form -->
                <div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
                    <div style="padding: 20px;">
                        <?php _e('some text explaining this process', 'universe') ; ?>
                        <form action="<?php echo osc_admin_base_url(true) ; ?>" method="post" id="universeform" name="universeform" >
                            <p>
                                <label for="data"><?php _e('Universe code'); ?></label>
                                <input type="text" id="s_code" name="s_code" value="" />
                            </p>
                            <p>
                                <button class="formButton" type="button" id="check_btn" ><?php _e('Get Info - CHANGE TEXT OF THIS BUTTON - ') ; ?></button>
                            </p>
                        </form>
                    </div>
                </div>
                <div id="universe_error" style="border: 1px solid #ccc; background: #eee; ">
                    <div style="padding: 10px;" id="error_msg">
                    </div>
                </div>
                <div id="universe_info" style="border: 1px solid #ccc; background: #eee; ">
                    <div style="padding: 10px;">
                        <?php _e('Information, MAYBE EXPLAIN and WARN THE USER THAT IT IS TRYING TO DOWNLOAD AN EXTERNAL FILE', 'universe') ; ?>
                        <form action="<?php echo osc_admin_base_url(true) ; ?>" method="post" >
                            <p>
                                <label><?php _e('Name'); ?></label>
                                <span id="name"></span>
                            </p>
                            <p>
                                <label><?php _e('Description'); ?></label>
                                <span id="description"></span>
                            </p>
                            <p>
                                <label><?php _e('URL'); ?></label>
                                <span id="url"></span>
                            </p>
                            <p>
                                <label><?php _e('Version'); ?></label>
                                <span id="version"></span>
                            </p>
                            <p>
                                <button class="formButton" type="button" id="cancel_btn" ><?php _e('Cancel') ; ?></button>
                                <button class="formButton" type="button" id="download_btn" ><?php _e('Download') ; ?></button>
                                <div id="steps_zip"></div>
                            </p>
                        </form>
                    </div>
                </div>
            </div> <!-- end of right column -->
        </div>
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>
