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
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()) ; ?>">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <!-- container -->
        <div id="content">
            <?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>
            <!-- right container -->
            <div class="right">
                <div class="header_title">
                    <h1 class="tools"><?php _e('Import') ; ?></h1>
                </div>
                <?php osc_show_flash_message('admin') ; ?>
                <!-- tools import -->
                <div class="tools import">
                    <p class="text">
                        <?php _e("You can modify your database to add, remove or modify its data here. It's usually used to import countries, regions and cities."); ?>
                    </p>
                    <form action="<?php echo osc_admin_base_url(true) ; ?>" enctype="multipart/form-data" method="post">
                        <input type="hidden" name="action" value="import_post" />
                        <input type="hidden" name="page" value="tools" />
                        <fieldset>
                            <div class="actions-nomargin">
                                <p class="text">
                                    <?php _e('File (.sql)') ; ?>
                                    <input type="file" name="sql" id="sql" />
                                </p>
                                <input type="submit" value="<?php echo osc_esc_html( __('Import data') ) ; ?>" />
                            </div>
                        </fieldset>
                    </form>
                </div>
                <!-- /tools import -->
            </div>
            <!-- /right container -->
        </div>
        <!-- /container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>