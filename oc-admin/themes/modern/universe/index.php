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
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()) ; ?>">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
        <link href="<?php echo osc_current_admin_theme_styles_url('demo_table.css') ; ?>" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.dataTables.js') ; ?>"></script>
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('datatables.pagination.js') ; ?>"></script>
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('datatables.extend.js') ; ?>"></script>
        <script type="text/javascript">
            $(function() {
                oTable = $('#datatables_list').dataTable({
                    "bAutoWidth": false,
                    "iDisplayLength": 50,
                    "aaData": [
                        <?php foreach($plugins as $_p){
                            $p = Plugins::getInfo($_p);
                            $up = osc_check_update(@$p['plugin_update_uri'], @$p['version']);
                            ?>
                            [
                                "<input type='hidden' name='upgradable' value='<?php echo $up?'1':'0'; ?>' />" +
                                "<?php echo addcslashes($p['plugin_name'], '"'); ?>&nbsp;",
                                "<?php echo addcslashes($p['version'], '"'); ?>",
                                "<?php echo addcslashes($p['description'], '"'); ?>",
                                <?php if($up) { ?>
                                    "<td><a href=\"#\" onclick=\"upgrade('<?php echo $p['plugin_update_uri']; ?>');\" ><?php _e('Upgrade available'); ?></a></td>"
                                <?php } else { ?>
                                    "<td><?php _e('No upgrade needed'); ?></td>"
                                <?php }; ?>
                            ],
                        <?php } ?>
                        <?php foreach($themes as $_t){
                            $t = WebThemes::newInstance()->loadThemeInfo($_t);
                            $up = osc_check_update(@$t['theme_update_uri'], @$t['version']);
                            ?>
                            [
                                "<input type='hidden' name='upgradable' value='<?php echo $up?'1':'0'; ?>' />" +
                                "<?php echo addcslashes($t['name'], '"'); ?>&nbsp;",
                                "<?php echo addcslashes($t['version'], '"'); ?>",
                                "<?php echo addcslashes($t['description'], '"'); ?>",
                                <?php if($up) { ?>
                                    "<td><a href=\"#\" onclick=\"upgrade('<?php echo $t['theme_update_uri']; ?>');\" ><?php _e('Upgrade available'); ?></a></td>"
                                <?php } else { ?>
                                    "<td><?php _e('No upgrade needed'); ?></td>"
                                <?php }; ?>
                            ] <?php echo $t != end($themes) ? ',' : ''; ?>
                        <?php } ?>
                    ],
                    "aoColumns": [
                        {
                            "sTitle": "<?php echo osc_esc_html( __('Name') ) ; ?>"
                        },
                        {
                            "sTitle": "<?php echo osc_esc_html( __('Version') ) ; ?>"
                        },
                        {
                            "sTitle": "<?php echo osc_esc_html( __('Description') ) ; ?>"
                        },
                        {
                            "sTitle": "<?php echo osc_esc_html( __('Upgrade') ) ; ?>"
                        }
                    ],
                    "fnDrawCallback": function() {
                        $('input:hidden[name="upgradable"]').each(function() {
                            $(this).parent().parent().children().css('background', 'none') ;
                            if( $(this).val() == '1' ) {
                                $(this).parent().parent().css('background-color', '#FFF0DF') ;
                            } else {
                                $(this).parent().parent().css('background-color', '#EDFFDF') ;
                            }
                        }) ;
                    }
                });

                $('.filter').append( $("#add_plugin_button") ) ;
            });
        </script>
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('datatables.post_init.js') ; ?>"></script>
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
                <?php osc_show_admin_flash_messages() ; ?>
                
                
                <div>
                    <?php _e('some text explaining this process', 'universe') ; ?>
                </div>
                <div id="universe_form" style="border: 1px solid #ccc; background: #eee; ">
                    <div style="padding: 20px;">
                        
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
                <!-- datatables universe -->
                <div class="datatables">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="datatables_list"></table>
                </div>
                <!-- /datatables universe -->
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