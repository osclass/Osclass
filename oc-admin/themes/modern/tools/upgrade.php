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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <div id="update_version" style="display:none;"></div>
        <script type="text/javascript">
            $(document).ready(function() {
                $("#steps_div").hide();
            });
        <?php
        $perms = osc_save_permissions();
        $ok = osc_change_permissions();
        foreach($perms as $k => $v) {
            @chmod($k, $v);
        }
        if($ok) {
        ?>
            $(function() {
                var steps_div = document.getElementById('steps_div');
                steps_div.style.display = "";
                var steps = document.getElementById('steps');
                var version = <?php echo osc_version() ; ?> ;
                var fileToUnzip = '';
                steps.innerHTML += "<?php _e('Checking for updates', 'admin'); ?>" + " (Current version " + version + "): " ;

                $.getJSON("http://www.osclass.org/latest_version.php?callback=?", function(data) {
                    if(data.version <= version) {
                        steps.innerHTML += "<?php _e('Congratulations! Your OSClass installation is up to date!', 'admin'); ?>";
                    } else {
                        steps.innerHTML += "<?php _e('New version to update:', 'admin'); ?> " + data.version + "<br/>" ;
                        <?php if(Params::getParam('confirm')=='true') {?>
                            steps.innerHTML += "<img id=\"loading_image\" src=\"<?php echo osc_current_admin_theme_url('images/loading.gif') ; ?>\" title=\"\" alt=\"\" /><?php _e('Upgrading your OSClass installation (this could take a while): ', 'admin') ; ?>" ;

                            var tempAr = data.url.split('/') ;
                            fileToUnzip = tempAr.pop() ;
                            $.get('<?php echo osc_admin_base_url(true) ; ?>?page=ajax&action=upgrade' , function(data) {
                                var loading_image = document.getElementById('loading_image');
                                loading_image.style.display = "none";
                                steps.innerHTML += data+"<br/>";
                            });
                        <?php } else { ?>
                            steps.innerHTML += "<a href=\"<?php echo osc_admin_base_url(true); ?>?page=tools&action=upgrade&confirm=true\" ><button><?php _e('Upgrade', 'admin') ; ?></button></a>" ;
                        <?php }; ?>
                    }
                });
            });
        <?php }; ?>
        </script>
        <div id="content">
            <div id="separator"></div>
            <?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>
            <div id="right_column">
                <div id="content_header" class="content_header">
                    <div style="float: left;">
                        <img src="<?php echo osc_current_admin_theme_url('images/tools-icon.png') ; ?>" title="" alt="" />
                    </div>
                    <div id="content_header_arrow">&raquo; <?php _e('Upgrade OSClass', 'admin'); ?></div>
                    <div style="clear: both;"></div>
                </div>
                <div id="content_separator"></div>
                <?php osc_show_flash_message('admin') ; ?>
                <div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
                    <div style="padding: 5px;">
                        <div style="width: 100%;">
                            <fieldset>
                                <legend><?php _e('Upgrade', 'admin'); ?></legend>
                                <?php if($ok) {?>
                                    <label><?php echo sprintf(__('Your OSClass installation can be auto-upgraded. Please, backup your database and the folder oc-content before attempting to upgrade your OSClass installation. You can also upgrade OSClass manaully, more information in the %s', '<a href="http://wiki.osclas.org/" >Wiki</a>'), 'admin'); ?></label>
                                <?php } else { ?>
                                    <label><?php _e('Your OSClass installation can not be auto-upgraded. Files and folders need to be writable. You could apply 644 permissions via SSH with the command "chmod -R 644 *" (without quotes) or via a FTP client, it depends on the program so we can not provide more information. You could also upgrade OSClass downloading the upgrade package, unzip it and replace the files on your server with the ones on the package.', 'admin'); ?></label>
                                <?php }; ?>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <br />
                <div style="clear: both;"></div>
                <div id="steps_div" style="border: 1px solid #ccc; background: #eee; ">
                    <div style="padding: 20px;">
                        <div id="steps">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>