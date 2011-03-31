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
        <div class="Header"><?php _e("Upgrade");?></div>
        <script type="text/javascript">
            $(document).ready(function(){
                $.ajaxSetup({
                    error: function(x,e){
                        if(x.status==0){
                            alert("<?php _e('You\'re offline! Please check your connection'); ?>");
                        } else if(x.status==404){
                            alert("<?php _e('Requested URL not found'); ?>");
                        } else if(x.status==500){
                            alert("<?php _e('Internal server error'); ?>");
                        } else if(e=='parsererror'){
                            alert("<?php _e('Error. Parsing JSON request failed'); ?>");
                        } else if(e=='timeout'){
                            alert("<?php _e('Request timeout'); ?>");
                        } else {
                            alert("<?php _e('Unknown error'); ?>" + x.responseText);
                        }
                    }
                });
            });

            $(function() {
                var steps = document.getElementById('steps');
                var version = <?php echo osc_version() ; ?> ;
                var fileToUnzip = '';
                steps.innerHTML += "<?php _e('Checking for updates (installed version: '); ?>" + version + "): " ;

                $.getJSON("http://www.osclass.org/latest_version.php?callback=?", function(data) {
                    if(data.version <= version) {
                        steps.innerHTML += "<?php _e('Congratulations! Your OSClass installation is up to date! (current version: '); ?>" + data.version + ")" ;
                    } else {
                        steps.innerHTML += "<?php _e('current version: '); ?>" + data.version + "<br/>" ;
                        steps.innerHTML += "<?php _e('Downloading update file: ') ; ?>" ;

                        var tempAr = data.url.split('/') ;
                        fileToUnzip = tempAr.pop() ;
                        $.get('<?php echo osc_admin_base_url() ; ?>upgrade.php?action=download-file&file=' + data.url, function(data) {
                            steps.innerHTML += data+"<br/>";
                            steps.innerHTML += "<?php _e('Unzipping file: '); ?>";
                            $.get('<?php echo osc_admin_base_url() ; ?>upgrade.php?action=unzip-file&file=' + fileToUnzip, function(data) {
                                steps.innerHTML += data+"<br/>";
                                steps.innerHTML += "<?php _e('Copying new files: '); ?>";
                                $.get('<?php echo osc_admin_base_url() ; ?>upgrade.php?action=copy-files', function(data) {
                                    steps.innerHTML += data+"<br/>";
                                    steps.innerHTML += "<?php _e('Removing old files: '); ?>";
                                    $.get('<?php echo osc_admin_base_url() ; ?>upgrade.php?action=remove-files', function(data) {
                                        steps.innerHTML += data+"<br/>";
                                        steps.innerHTML += "<?php _e('Executing SQL: '); ?>";
                                        $.get('<?php echo osc_admin_base_url() ; ?>upgrade.php?action=execute-sql', function(data) {
                                            steps.innerHTML += data+"<br/>";
                                            steps.innerHTML += "<?php _e('Executing additional actions: '); ?>";
                                            $.get('<?php echo osc_admin_base_url() ; ?>upgrade.php?action=execute-actions', function(data) {
                                                steps.innerHTML += data+"<br/>";
                                                steps.innerHTML += "<?php _e('Cleaning all the mess: '); ?>";
                                                $.get('<?php echo osc_admin_base_url() ; ?>upgrade.php?action=empty-temp', function(data) {
                                                    steps.innerHTML += data+"<br/>";
                                                    steps.innerHTML += "<?php _e('Awesome and easy auto-upgrade: done!'); ?><br/><br/>" ;
                                                });
                                            });
                                        });
                                    });
                                });
                            });
                        });
                    }
                });
            });
        </script>
        <div id="content">
            <div id="separator"></div>
            <?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>
            <div id="right_column">
                <div id="content_header" class="content_header">
                    <div style="float: left;">
                        <img src="<?php echo osc_current_admin_theme_url() ; ?>images/tools-icon.png" title="" alt="" />
                    </div>
                    <div id="content_header_arrow">&raquo; <?php _e('Upgrade OSClass'); ?></div>
                    <div style="clear: both;"></div>
                </div>
                <div id="content_separator"></div>
                <?php osc_show_flash_message('admin') ; ?>
                <!-- add new item form -->
                <div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
                    <div style="padding: 20px;">
                        <div id="steps">
                        </div>
                    </div>
                </div>
            </div> <!-- end of right column -->
        </div>
        <?php osc_current_admin_theme_url('footer.php') ; ?>
    </body>
</html>
