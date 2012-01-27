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
$plugins = View::newInstance()->_get('plugins');
$themes = View::newInstance()->_get('themes');
$code = View::newInstance()->_get('code');
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
                    <div id="content_header_arrow">&raquo; <?php _e('Universe'); ?></div>
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
                <div id="universe_error" style="border: 1px solid #ccc; background: #eee; ">
                    <div style="padding: 10px;" id="error_msg">
                    </div>
                </div>
                <div id="universe_info" style="border: 1px solid #ccc; background: #eee; ">
                    <div style="padding: 10px;">
                        <?php _e('Information, MAYBE EXPLAIN and WARN THE USER THAT IT IS TRYING TO DOWNLOAD AN EXTERNAL FILE', 'universe') ; ?>
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
                                <button class="formButton" type="button" id="download_btn" ><?php _e('Download') ; ?></button>
                                <div id="steps_zip"></div>
                            </p>
                        </form>
                    </div>
                </div>
                <div id="universe_list" style="border: 1px solid #ccc; background: #eee; ">
                    <div style="padding: 10px;">
                        <?php _e('LIST OF ALL THE STUFF YOU HAVE INSTALLED', 'universe') ; ?>
                        <a href="<?php echo osc_admin_base_url();?>?page=universe&action=check"><?php _e('Check all for updates'); ?></a>
                        <table>
                            <thead>
                                <tr>
                                    <th><?php _e('Name'); ?></th>
                                    <th><?php _e('Version'); ?></th>
                                    <?php if(Params::getParam('action')=='check') { ?>
                                        <th><?php _e('Upgrade'); ?></th>
                                    <?php }; ?>
                                    <th><?php _e('Action'); ?></th>
                                    <th><?php _e('Description'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td><?php _e('xxx PLUGINS NICE HEADER xxx'); ?></td></tr>
                                <?php foreach($plugins as $_p) { 
                                    $p = Plugins::getInfo($_p); ?>
                                <tr>
                                    <td><?php echo $p['plugin_name']; ?></td>
                                    <td><?php echo @$p['version']; ?></td>
                                    <?php if(Params::getParam('action')=='check') {
                                        if(osc_check_update(@$p['plugin_update_uri'], @$p['version'])) { ?>
                                            <td><a href="#" onclick="upgrade('<?php echo $p['plugin_update_uri']; ?>');" ><?php _e('Upgrade available'); ?></a></td>
                                    <?php } else { ?>
                                            <td><?php _e('Upgrade not available'); ?></td>
                                    <?php }; }; ?>
                                    <td>ACTION</td>
                                    <td><?php echo $p['description']; ?></td>
                                </tr>
                                <?php }; ?>
                                <tr><td><?php _e('xxx THEMES NICE HEADER xxx'); ?></td></tr>
                                <?php foreach($themes as $_t) { 
                                    $t = WebThemes::newInstance()->loadThemeInfo($_t); ?>
                                <tr>
                                    <td><?php echo $t['name']; ?></td>
                                    <td><?php echo @$t['version']; ?></td>
                                    <?php if(Params::getParam('action')=='check') {
                                        if(osc_check_update(@$t['theme_update_uri'], @$t['version'])) { ?>
                                            <td><a href="#" onclick="upgrade('<?php echo $t['theme_update_uri']; ?>');" ><?php _e('Upgrade available'); ?></a></td>
                                    <?php } else { ?>
                                            <td><?php _e('Upgrade not available'); ?></td>
                                    <?php }; }; ?>
                                    <td>ACTION</td>
                                    <td><?php echo $t['description']; ?></td>
                                </tr>
                                <?php }; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> <!-- end of right column -->
        </div>
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
            
            function upgrade(code) {
                $("#s_code").attr("value", code);
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
            }
            
            <?php if($code!='') { ?>
                upgrade('<?php echo $code; ?>');
            <?php }; ?>
            
        </script>
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>
