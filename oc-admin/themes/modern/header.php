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

    $adminId = osc_paramSession('adminId', 0);
    $admin = Admin::newInstance()->findByPrimaryKey($adminId);
    $preferences = Preference::newInstance()->toArray();

    $admin_theme = new AdminThemes();
    $admin_theme->setCurrentTheme($adminTheme); // variable from common.php

    $current_theme = $admin_theme->getCurrentThemePath();
    $current_styles = $admin_theme->getCurrentThemeStyles();
    $absolute_path = $admin_theme->getCurrentThemeAbsolutePath();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US"> 
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title><?php _e('OSClass Admin Panel'); ?></title>
        <script type="text/javascript" src="<?php echo WEB_PATH; ?>/oc-includes/js/jquery-1.4.2.js"></script>
        <script type="text/javascript" src="<?php echo WEB_PATH; ?>/oc-includes/js/jquery-ui-1.8.5.js"></script>
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

                var version = <?php echo $preferences['version']; ?>;

                $.getJSON("http://www.osclass.org/latest_version.php?callback=?", function(data) {
                    var update = document.getElementById('update_version');
                    if(data.version > version) {
                        var text = 'OSClass ' + data.s_name + ' is available! <a href="tools.php?action=upgrade">Please upgrade now</a>';
                        update.innerHTML = text;
                        update.setAttribute('style', '');
                    }
                });
            });
        </script>

        <!-- style -->
        <script src="<?php echo  $current_theme; ?>/js/jquery.cookie.js"></script>
        <script src="<?php echo  $current_theme; ?>/js/jquery.json.js"></script>
        <link type="text/css" href="<?php echo  $current_styles; ?>/backoffice.css" media="screen" rel="stylesheet" />
        <link href="<?php echo WEB_PATH; ?>/oc-includes/css/jquery-ui.css" rel="stylesheet" type="text/css" />
            <?php
            // XXX: must be another way to include page specific stylesheets.
            if(strstr($_SERVER["SCRIPT_NAME"],"items.php")) {
                    ?>
                    <script type="text/javascript" src="<?php echo WEB_PATH;?>/oc-includes/js/tabber-minimized.js"></script>
                    <link type="text/css" href="<?php echo $current_styles;?>/tabs.css" media="screen" rel="stylesheet" />
                    <script type="text/javascript">
                    	document.write('<style type="text/css">.tabber{display:none;}<\/style>');
                    </script>
                    
                    <script src="<?php echo WEB_PATH;?>/oc-includes/js/jquery.dataTables.min.js"></script>
                    <link type="text/css" href="<?php echo $current_styles;?>/item_list_layout.css" media="screen" rel="stylesheet" />
                    <?php
            }
            if(strstr($_SERVER["SCRIPT_NAME"],"new_item.php")) {
                    ?>
                    <script src="<?php echo WEB_PATH;?>/oc-includes/js/jquery.dataTables.min.js"></script>
                    <link type="text/css" href="<?php echo $current_styles;?>/new_item_layout.css" media="screen" rel="stylesheet" />
                    <?php
            }
            if(strstr($_SERVER["SCRIPT_NAME"],"categories.php")) {
                    ?>
                    <script type="text/javascript" src="<?php echo WEB_PATH;?>/oc-includes/js/tabber-minimized.js"></script>
                    <link type="text/css" href="<?php echo $current_styles;?>/tabs.css" media="screen" rel="stylesheet" />
                    <script type="text/javascript">
                    	document.write('<style type="text/css">.tabber{display:none;}<\/style>');
                    </script>
                    
                    <script src="<?php echo WEB_PATH;?>/oc-includes/js/jquery.dataTables.min.js"></script>
                    <link type="text/css" href="<?php echo $current_styles;?>/cat_list_layout.css" media="screen" rel="stylesheet" />
                    <?php
            }
            if(strstr($_SERVER["SCRIPT_NAME"],"media.php")) {
                    ?>
                    <script src="<?php echo WEB_PATH;?>/oc-includes/js/jquery.dataTables.min.js"></script>
                    <link type="text/css" href="<?php echo $current_styles;?>/media_layout.css" media="screen" rel="stylesheet" />
                    <?php
            }

            if(strstr($_SERVER["SCRIPT_NAME"],"users.php")) {
                    ?>
                    <script src="<?php echo WEB_PATH;?>/oc-includes/js/jquery.dataTables.min.js"></script>
                    <link type="text/css" href="<?php echo $current_styles;?>/users_list_layout.css" media="screen" rel="stylesheet" />
                    <?php
            }
            if(strstr($_SERVER["SCRIPT_NAME"],"admins.php")) {
                    ?>
                    <script src="<?php echo WEB_PATH;?>/oc-includes/js/jquery.dataTables.min.js"></script>
                    <link type="text/css" href="<?php echo $current_styles;?>/admins_list_layout.css" media="screen" rel="stylesheet" />
                    <?php
            }
            if(strstr($_SERVER["SCRIPT_NAME"],"comments.php")) {
                    ?>
                    <script src="<?php echo WEB_PATH;?>/oc-includes/js/jquery.dataTables.min.js"></script>
                    <link type="text/css" href="<?php echo $current_styles;?>/item_list_layout.css" media="screen" rel="stylesheet" />
                    <?php
            }

            if(strstr($_SERVER["SCRIPT_NAME"],"appearance.php")) {
                    ?>
                    <link type="text/css" href="<?php echo $current_styles;?>/appearance_layout.css" media="screen" rel="stylesheet" />
                    <?php
            }
            if(strstr($_SERVER["SCRIPT_NAME"],"plugins.php")) {
                    ?>
                    <script type="text/javascript" src="<?php echo WEB_PATH;?>/oc-includes/js/tabber-minimized.js"></script>
                    <link type="text/css" href="<?php echo $current_styles;?>/tabs.css" media="screen" rel="stylesheet" />
                    <script type="text/javascript">
                    	document.write('<style type="text/css">.tabber{display:none;}<\/style>');
                    </script>
                    

                    <script type="text/javascript" src="<?php echo WEB_PATH;?>/oc-includes/js/jquery.dataTables.min.js"></script>
                    <link type="text/css" href="<?php echo $current_styles;?>/plugins_layout.css" media="screen" rel="stylesheet" />
                    <?php
            }
            if(strstr($_SERVER["SCRIPT_NAME"],"pages.php")) {
                    ?>
                    <script type="text/javascript" src="<?php echo WEB_PATH;?>/oc-includes/js/tabber-minimized.js"></script>
                    <link type="text/css" href="<?php echo $current_styles;?>/tabs.css" media="screen" rel="stylesheet" />
                    <script type="text/javascript">
                    	document.write('<style type="text/css">.tabber{display:none;}</style>');
                    </script>

                    <script src="<?php echo WEB_PATH;?>/oc-includes/js/jquery.dataTables.min.js"></script>
                    <link type="text/css" href="<?php echo $current_styles;?>/pages_layout.css" media="screen" rel="stylesheet" />
                    <?php
            }
            if(strstr($_SERVER["SCRIPT_NAME"],"languages.php")) {
                    ?>
                    <script src="<?php echo WEB_PATH;?>/oc-includes/js/jquery.dataTables.min.js"></script>
                    <link type="text/css" href="<?php echo $current_styles;?>/languages_layout.css" media="screen" rel="stylesheet" />
                    <?php
            }

            if(strstr($_SERVER["SCRIPT_NAME"],"settings.php")) {
                    ?>
                    <script src="<?php echo WEB_PATH;?>/oc-includes/js/jquery.dataTables.min.js"></script>
                    <link type="text/css" href="<?php echo $current_styles;?>/settings_layout.css" media="screen" rel="stylesheet" />
                    <?php
            }
            if(strstr($_SERVER["SCRIPT_NAME"],"tools.php")) {
                    ?>
                    <!-- styles goes here -->
                    <link type="text/css" href="<?php echo $current_styles;?>/tools_layout.css" media="screen" rel="stylesheet" />
                    <?php
            }

            if(!strstr($_SERVER["SCRIPT_NAME"],"main.php")) { // XXX: Dirty workaround, in theory it must be included only when we load Datatables.
            ?>
            <!-- must be changed to different file -->
            <style type="text/css" title="currentStyle">
                    @import "<?php echo $current_styles;?>/demo_table.css";
            </style>
            <?php
            }
            ?>
    </head>
    <body>

    <!-- let's go! -->
    <div id="header">
        <div id="logo"><?php _e('OSClass'); ?></div>
        <div id="arrow">&raquo;</div>
        <?php if(isset($preferences)): ?>
        <div id="hostname"><?php echo $preferences['pageTitle']; ?></div>
        <?php endif; ?>
        <em id="visit_site"><a title="<?php _e('Visit website'); ?>" href="<?php echo WEB_PATH; ?>" target="_blank"><?php echo osc_lowerCase( __('Visit website') ); ?></a><!-- &crarr; --></em>
        <div id="user_links"><?php _e('Howdy'); ?>, <a title="<?php _e('Your profile'); ?>" href="admins.php?action=edit"><?php echo $admin['s_name']; ?>!</a> | <a title="<?php _e('Log Out'); ?>" href="index.php?action=logout"><?php _e('Log Out'); ?></a></div>
	
		<?php osc_runHook('admin_header'); ?>

    </div>
    <div id="update_version" style="display:none;">
    </div>
