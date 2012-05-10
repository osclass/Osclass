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
        <script type="text/javascript">
            $(document).ready(function(){
                if (typeof $.uniform != 'undefined') {
                    $('textarea, button,select, input:file').uniform();
                }

                <?php if(Params::getParam('confirm')=='true') {?>
                    $('#output').show();
                    $('#tohide').hide();

                    $.get('<?php echo osc_admin_base_url(true) ; ?>?page=upgrade&action=upgrade-funcs' , function(data) {
                        $('#loading_immage').hide();
                        $('#result').append(data+"<br/>");
                    });
                <?php } ?>
            });
        </script>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <!-- container -->
        <div id="content">
            <?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>
            <!-- right container -->
            <div class="right">
                <div class="header_title">
                    <h1 class="tools"><?php _e('Upgrade') ; ?></h1>
                </div>
                <?php osc_show_flash_message('admin') ; ?>
                <div id="result">
                    <div id="output" style="display:none">
                        <img id="loading_immage" src="<?php echo osc_current_admin_theme_url('images/loading.gif') ; ?>" title="" alt="" />
                        <?php _e('Upgrading your OSClass installation (this could take a while): ', 'admin') ; ?>
                    </div>
                    <div id="tohide">
                        <p>
                            <?php _e('You have uploaded a new version of OSClass, you need to upgrade OSClass for a correct functioning'); ?>
                        </p>
                        <a class="button" href="<?php echo osc_admin_base_url(true); ?>?page=upgrade&confirm=true"><?php _e('Upgrade now'); ?></a>
                    </div>
                </div>
            </div>
            <!-- /right container -->
        </div>
        <!-- /container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>