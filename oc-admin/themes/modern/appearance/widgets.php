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
                        <img src="<?php echo  osc_current_admin_theme_url('images/widget-icon.png') ; ?>" title="" alt=""/>
                    </div>
                    <div id="content_header_arrow">&raquo; <?php _e('Widgets') ; ?></div>
                    <div style="clear: both;"></div>
                </div>
                <div id="content_separator"></div>
                <?php osc_show_flash_message('admin') ; ?>
                <div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
                    <div style="padding: 20px;">
                        <strong><?php echo $info['name']; ?> <?php echo $info['version']; ?> by <a href="<?php echo $info['author_url']; ?>"><?php echo $info['author_name']; ?></a></strong><br />
                        <p><?php echo $info['description']; ?></p>
                        <?php foreach($info['locations'] as $location) { ?>
                            <div>
                                <div style="font-weight: bold; background-color: white; padding: 5px;">
                                    <?php echo $location; ?> <a href="<?php echo osc_admin_base_url(true); ?>?page=appearance&action=add_widget&amp;location=<?php echo $location; ?>"><?php _e('Add HTML widget'); ?></a>
                                    <br />
                                    <?php
                                        $widgets = Widget::newInstance()->findByLocation($location);
                                        foreach($widgets as $w) {
                                            $aux_id = $w['pk_i_id'] ;
                                            printf('<div>Widget #%d - <a href="%s?page=appearance&action=edit_widget&location=%s&amp;id=%1$d">' . __("Edit") .'</a>', $aux_id, osc_admin_base_url(true), $location,$aux_id) ;
                                            printf(' - <a href="%s?page=appearance&action=delete_widget&amp;id=%d">' . __("Delete") .'</a>', osc_admin_base_url(true), $aux_id) ;
                                            printf('<div style="border: 1px dashed gray;">%s</div></div>', $w['s_description']);
                                        }
                                    ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div style="clear: both;"></div>
            </div> <!-- end of right column -->
            <div style="clear: both;"></div>
        </div> <!-- end of container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>