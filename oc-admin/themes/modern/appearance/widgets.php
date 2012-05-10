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

    //getting variables for this view
    $info = __get("info") ;
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
                    <h1 class="widgets"><?php _e('Widgets') ; ?></h1>
                </div>
                <?php osc_show_flash_message('admin') ; ?>
                <!-- widgets list -->
                <div class="widgets">
                    <h3><?php echo $info['name'] ; ?> <?php echo $info['version'] ; ?> <?php _e('by') ; ?> <a href="<?php echo $info['author_url'] ; ?>"><?php echo $info['author_name'] ; ?></a></h3>
                    <?php foreach($info['locations'] as $location) { ?>
                    <div class="widget-sections">
                        <h4><?php printf( __('Section: %s'), $location ) ; ?> &middot; <a href="<?php echo osc_admin_base_url(true); ?>?page=appearance&amp;action=add_widget&amp;location=<?php echo $location ; ?>"><?php _e('Add HTML widget') ; ?></a></h4>
                        <?php $widgets = Widget::newInstance()->findByLocation($location) ; ?>
                        <?php if( count($widgets) > 0 ) { ?>
                            <ul>
                            <?php foreach($widgets as $w) { ?>
                                <li>
                                    <?php printf('Widget #%d - <a href="%s?page=appearance&amp;action=edit_widget&amp;location=%s&amp;id=%1$d">' . __('Edit') .'</a>', $w['pk_i_id'], osc_admin_base_url(true), $location, $w['pk_i_id']) ; ?>
                                    &middot;
                                    <?php printf('<a href="%s?page=appearance&amp;action=delete_widget&amp;id=%d">' . __('Delete') .'</a>', osc_admin_base_url(true), $w['pk_i_id']) ; ?>
                                    <em><?php printf( __('Description: %s'), $w['s_description'] ) ; ?></em>
                                </li>
                            <?php } ?>
                            </ul>
                        <?php } ?>
                    </div>
                    <?php } ?>
                </div>
                <!-- /widgets list -->
            </div>
            <!-- /right container -->
        </div>
        <!-- /container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>