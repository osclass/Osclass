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

    $code = View::newInstance()->_get('code');
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
            <?php osc_current_admin_theme_path( 'include/backoffice_menu.php' ) ; ?>
            <!-- right container -->
            <div class="right">
                <div class="header_title">
                    <h1 class="universe"><?php _e('Universe') ; ?></h1>
                </div>
                <?php osc_show_flash_message('admin') ; ?>
                
                
                <div>
                    <?php _e('Browse the official repository of OSClass and discover new plugins and themes for your website', 'universe') ; ?>
                </div>
                <div id="universe_form" style="border: 1px solid #ccc; background: #eee; ">
                    <div style="padding: 20px;">
                        
                        <p>
                            <label for="data"><?php _e('Universe code'); ?></label>
                            <input type="text" id="s_code" name="s_code" value="" />
                        </p>
                        <p>
                            <button class="formButton" type="button" id="check_btn" ><?php _e('Download package') ; ?></button>
                        </p>
                    </div>
                </div>
                <div id="universe_error" style="border: 1px solid #ccc; background: #eee; ">
                    <div style="padding: 10px;" id="error_msg">
                    </div>
                </div>
                <div id="universe_info" style="border: 1px solid #ccc; background: #eee; ">
                    <div style="padding: 10px;">
                        <?php _e('Information, you are going to download and install the following package:') ; ?>
                        <form>
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
                                <button class="formButton" type="button" id="download_btn" ><?php _e('I understand the risk, continue') ; ?></button>
                                <div id="steps_zip"></div>
                            </p>
                        </form>
                    </div>
                </div>     
                <br/>
                <!-- boxes -->
                    <div class="sortable_div">
                        <div class="float50per">
                            <div class="latest-items ui-widget-content ui-corner-all">
                                <h3 class="ui-state-default"><?php _e('Plugins') ; ?></h3>
                                <div class="ui-state-body">
                                    <ul id="plugins_ul"></ul>
                                </div>
                            </div>
                        </div>
                        <div class="float50per">
                            <div class="dashboard-statistics ui-widget-content ui-corner-all">
                                <h3 class="ui-state-default"><?php _e('Themes'); ?></h3>
                                <div class="ui-state-body">
                                    <ul id="themes_ul"></ul>
                                </div>
                            </div>
                        </div>
                        <div style="clear"></div>
                    </div>
                <!-- /boxes -->
            </div>
            <!-- /right container -->
        </div>
        <!-- /container -->
        
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
                                $("#name").text(data.s_title);
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
            
            function upgrade(code) {
                $("#s_code").attr("value", code);
                $("#s_code").attr("readonly", "readonly");
                $.getJSON(
                    "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=check_universe",
                    {"code" : code},
                    function(data){
                        if(data.error==0) {
                            $("#universe_info").show();
                            $("#name").text(data.s_title);
                            $("#version").text(data.s_version);
                            $("#description").text(data.s_description);
                            $("#url").text(data.s_source_file);
                        } else {
                            $("#universe_error").show();
                            $("#error_msg").text(data.error_msg);
                        }
                    }
                );
            }
            
            function addItem(item, list) {
                $(list).append("<li><h3>" + item.s_title + " - " + item.s_version + "</h3><p>&nbsp;&nbsp;&nbsp;&nbsp;" + item.s_description + " <a href=\"<?php echo osc_admin_base_url(true);?>?page=universe&code=" + item.s_slug + "\"><?php _e('Install'); ?></a></p></li>");
                
            }
            
            $.getJSON(
                "<?php echo osc_market_url(); ?>",
                {"section" : 'all'},
                function(data){
                    var l = data.plugins.length;
                    for(var k = 0;k<l;k++) {
                        addItem(data.plugins[k], "#plugins_ul");
                    }
                    l = data.themes.length;
                    for(var k = 0;k<l;k++) {
                        addItem(data.themes[k], "#themes_ul");
                    }
                }
            );

            
            
            <?php if($code!='') { ?>
                upgrade('<?php echo $code; ?>');
            <?php }; ?>
            
        </script>        
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>