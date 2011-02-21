<?php

/*
 *      OSCLass – software for creating and publishing online classified
 *                           advertising platforms
 *
 *                        Copyright (C) 2010 OSCLASS
 *
 *       This program is free software: you can redistribute it and/or
 *     modify it under the terms of the GNU Affero General Public License
 *     as published by the Free Software Foundation, either version 3 of
 *            the License, or (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful, but
 *         WITHOUT ANY WARRANTY; without even the implied warranty of
 *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *             GNU Affero General Public License for more details.
 *
 *      You should have received a copy of the GNU Affero General Public
 * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

?>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title><?php _e('OSClass Admin Panel'); ?></title>

        <link href="<?php echo osc_current_admin_theme_styles_url('backoffice.css') ; ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo osc_current_admin_theme_styles_url('jquery-ui.css') ; ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo osc_current_admin_theme_styles_url('demo_table.css') ; ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo osc_current_admin_theme_styles_url('new_item_layout.css') ; ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo osc_current_admin_theme_styles_url('item_list_layout.css') ; ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo osc_current_admin_theme_styles_url('tabs.css') ; ?>" rel="stylesheet" type="text/css" />


        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.js') ; ?>"></script>
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery-ui.js') ; ?>"></script>
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.cookie.js') ; ?>"></script>
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.json.js') ; ?>"></script>
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.dataTables.min.js') ; ?>"></script>
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('tabber-minimized.js') ; ?>"></script>

        <script>
            $(function() {
                $("#menu").accordion({
                    active: false,
                    collapsible: true,
                    navigation: true,
                    autoHeight: false,
                    icons: { 'header': 'ui-icon-plus', 'headerSelected': 'ui-icon-minus' }
                });

                if (jQuery.browser.msie && jQuery.browser.version.substr(0,1)<7) {
                    jQuery('#accordion *').css('zoom', '1');
                }

                if($('.Header')) $('.Header').hide(); //XXX: remove it.
                if($('.FlashMessage')) $('.FlashMessage').animate({opacity: 1.0}, 5000).fadeOut();
            });
        </script>
        
        <?php
            $lastCheck = (int)osc_last_version_check() ;
            $hourInSecs = 24 * 3600 ;
        ?>
        <?php if ( (time() - $lastCheck) > $hourInSecs ) { ?>
            <script>
                $(function() {
                    var version = <?php echo osc_version() ; ?> ;

                    $.getJSON("http://www.osclass.org/latest_version.php?callback=?", function(data) {
                        var update = document.getElementById('update_version') ;
                        if(data.version > version) {
                            //var text = 'OSClass ' + data.s_name + ' is available! <a href="tools.php?action=upgrade">Please upgrade now</a>' ;
                            var text = '<?php printf(__('OSClass %s is available!'), '\' + data.s_name + \'') ; ?> <a href="tools.php?action=upgrade"><?php _e('Please upgrade now') ; ?></a>' ;
                            update.innerHTML = text ;
                            update.setAttribute('style', '') ;
                        }
                    });
                });
            </script>
        <?php } ?>
                
        <!-- <script src="<?php echo  osc_current_admin_theme_url() ; ?>js/jquery.cookie.js"></script> -->
        <!-- <script src="<?php echo  osc_current_admin_theme_url() ; ?>js/jquery.json.js"></script> -->
        <!-- <link type="text/css" href="<?php echo osc_current_admin_theme_styles_url() ; ?>backoffice.css" media="screen" rel="stylesheet" /> -->
        <!-- <link href="<?php echo osc_base_url() ; ?>oc-includes/css/jquery-ui.css" rel="stylesheet" type="text/css" /> -->

        

        <?php
            // XXX: must be another way to include page specific stylesheets.
            if(strstr($_SERVER["SCRIPT_NAME"], "items.php")) {
            ?>
                    <!-- <script type="text/javascript" src="<?php echo osc_base_url() ; ?>oc-includes/js/tabber-minimized.js"></script> -->
                    <!-- <link type="text/css" href="<?php echo osc_current_admin_theme_styles_url() ; ?>tabs.css" media="screen" rel="stylesheet" /> -->
                    <script type="text/javascript">
                    	document.write('<style type="text/css">.tabber{display:none;}<\/style>') ;
                    </script>
                    
                    <!-- <script src="<?php echo osc_base_url() ; ?>oc-includes/js/jquery.dataTables.min.js"></script> -->
                    <!-- <link type="text/css" href="<?php echo osc_current_admin_theme_styles_url() ; ?>item_list_layout.css" media="screen" rel="stylesheet" /> -->
            <?php
            }
            if(strstr($_SERVER["SCRIPT_NAME"], "new_item.php")) {
            ?>
                    <!-- <script src="<?php echo osc_base_url() ; ?>oc-includes/js/jquery.dataTables.min.js"></script> -->
                    <!-- <link type="text/css" href="<?php echo osc_current_admin_theme_styles_url() ; ?>new_item_layout.css" media="screen" rel="stylesheet" /> -->
            <?php
            }
            if(strstr($_SERVER["SCRIPT_NAME"], "categories.php")) {
            ?>
                    <script type="text/javascript" src="<?php echo osc_base_url() ; ?>oc-includes/js/tabber-minimized.js"></script>
                    <link type="text/css" href="<?php echo osc_current_admin_theme_styles_url() ; ?>tabs.css" media="screen" rel="stylesheet" />
                    <script type="text/javascript">
                    	document.write('<style type="text/css">.tabber{display:none;}<\/style>') ;
                    </script>
                    
                    <script src="<?php echo osc_base_url() ; ?>oc-includes/js/jquery.dataTables.min.js"></script>
                    <link type="text/css" href="<?php echo osc_current_admin_theme_styles_url() ; ?>cat_list_layout.css" media="screen" rel="stylesheet" />
            <?php
            }
            if(strstr($_SERVER["SCRIPT_NAME"], "media.php")) {
            ?>
                    <!-- <script src="<?php echo osc_base_url() ; ?>oc-includes/js/jquery.dataTables.min.js"></script> -->
                    <!-- <link type="text/css" href="<?php echo osc_current_admin_theme_styles_url() ; ?>media_layout.css" media="screen" rel="stylesheet" /> -->
            <?php
            }

            if(strstr($_SERVER["SCRIPT_NAME"], "users.php")) {
                    ?>
                    <script src="<?php echo osc_base_url() ; ?>oc-includes/js/jquery.dataTables.min.js"></script>
                    <link type="text/css" href="<?php echo osc_current_admin_theme_styles_url() ; ?>users_list_layout.css" media="screen" rel="stylesheet" />
                    <?php
            }
            if(strstr($_SERVER["SCRIPT_NAME"], "admins.php")) {
            ?>
                    <script src="<?php echo osc_base_url() ; ?>oc-includes/js/jquery.dataTables.min.js"></script>
                    <link type="text/css" href="<?php echo osc_current_admin_theme_styles_url() ; ?>admins_list_layout.css" media="screen" rel="stylesheet" />
            <?php
            }
            if(strstr($_SERVER["SCRIPT_NAME"], "comments.php")) {
            ?>
                    <!-- <script src="<?php echo osc_base_url() ; ?>oc-includes/js/jquery.dataTables.min.js"></script> -->
                    <!-- <link type="text/css" href="<?php echo osc_current_admin_theme_styles_url() ; ?>item_list_layout.css" media="screen" rel="stylesheet" /> -->
            <?php
            }

            if(strstr($_SERVER["SCRIPT_NAME"], "appearance.php")) {
            ?>
                    <link type="text/css" href="<?php echo osc_current_admin_theme_styles_url() ; ?>appearance_layout.css" media="screen" rel="stylesheet" />
            <?php
            }
            if(strstr($_SERVER["SCRIPT_NAME"], "plugins.php")) {
            ?>
                    <script type="text/javascript" src="<?php echo osc_base_url() ; ?>oc-includes/js/tabber-minimized.js"></script>
                    <link type="text/css" href="<?php echo osc_current_admin_theme_styles_url() ; ?>tabs.css" media="screen" rel="stylesheet" />
                    <script type="text/javascript">
                    	document.write('<style type="text/css">.tabber{display:none;}<\/style>') ;
                    </script>
                    

                    <script type="text/javascript" src="<?php echo osc_base_url() ; ?>oc-includes/js/jquery.dataTables.min.js"></script>
                    <link type="text/css" href="<?php echo osc_current_admin_theme_styles_url() ; ?>plugins_layout.css" media="screen" rel="stylesheet" />
            <?php
            }
            if(strstr($_SERVER["SCRIPT_NAME"],"pages.php")) {
                ?>
                <script type="text/javascript" src="<?php echo osc_base_url() ; ?>oc-includes/js/tabber-minimized.js"></script>
                <link type="text/css" href="<?php echo osc_current_admin_theme_styles_url() ; ?>tabs.css" media="screen" rel="stylesheet" />
                <script type="text/javascript">
                    document.write('<style type="text/css">.tabber{display:none;}</style>') ;
                </script>

                <script src="<?php echo osc_base_url() ; ?>oc-includes/js/jquery.dataTables.min.js"></script>
                <link type="text/css" href="<?php echo osc_current_admin_theme_styles_url() ; ?>pages_layout.css" media="screen" rel="stylesheet" />
                <?php
            }
            if(strstr($_SERVER["SCRIPT_NAME"], "languages.php")) {
            ?>
                <script src="<?php echo osc_base_url() ; ?>oc-includes/js/jquery.dataTables.min.js"></script>
                <link type="text/css" href="<?php echo osc_current_admin_theme_styles_url() ; ?>languages_layout.css" media="screen" rel="stylesheet" />
            <?php
            }

            if(strstr($_SERVER["SCRIPT_NAME"], "settings.php")) {
            ?>
                <script src="<?php echo osc_base_url() ; ?>oc-includes/js/jquery.dataTables.min.js"></script>
                <link type="text/css" href="<?php echo osc_current_admin_theme_styles_url() ; ?>settings_layout.css" media="screen" rel="stylesheet" />
            <?php
            }
            if(strstr($_SERVER["SCRIPT_NAME"], "tools.php")) {
            ?>
                <!-- styles goes here -->
                <link type="text/css" href="<?php echo osc_current_admin_theme_styles_url() ; ?>tools_layout.css" media="screen" rel="stylesheet" />
            <?php
            }

            /*if(!strstr($_SERVER["SCRIPT_NAME"], "main.php")) { // XXX: Dirty workaround, in theory it must be included only when we load Datatables.
            
                <!-- must be changed to different file -->
                <style type="text/css" title="currentStyle">
                    @import "<?php echo osc_current_admin_theme_styles_url() ; ?>demo_table.css";
                </style>
            
            }*/
            ?>