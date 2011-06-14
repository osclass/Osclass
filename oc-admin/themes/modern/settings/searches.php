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
                        <img src="<?php echo osc_current_admin_theme_url('images/settings-icon.png') ; ?>" alt="" title=""/>
                    </div>
                    <div id="content_header_arrow">&raquo; <?php _e('Last Searches') ; ?></div>
                    <div style="clear: both;"></div>
                </div>
                <div id="content_separator"></div>
                <?php osc_show_flash_message('admin'); ?>
                <!-- settings form -->
                <div id="settings_form" style="border: 1px solid #ccc; background: #eee;">
                    <div style="padding: 20px;">
                        <form action="<?php echo osc_admin_base_url(true); ?>" method="post">
                            <input type="hidden" name="page" value="settings" />
                            <input type="hidden" name="action" value="latestsearches_post" />
                            <div style="float: left; width: 50%;">
                                <fieldset>
                                    <legend><?php _e('Save latest searches') ; ?></legend>
                                    <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" <?php echo ( osc_save_latest_searches() ) ? 'checked="true"' : '' ; ?> name="save_latest_searches" id="save_latest_searches" />
                                    <label for="last_searches"><?php _e('Save latest searches'); ?></label>
                                    <br/>
                                    <label><?php _e('OSClass could save the latest searches users do on your site. You could have an idea of what your users are looking for or show them on the site as "hot topics".'); ?></label>
                                </fieldset>
                            </div>

                            <div style="float: left; width: 50%;">
                                <fieldset>
                                    <legend><?php _e('Keep last searches') ; ?></legend>
                                    <div style="font-size: small; margin: 0px;">
                                        <input type="radio" name="purge_searches" id="purge_searches" value="hour" <?php echo ((osc_purge_latest_searches()=='hour') ? 'checked="checked"' : ''); ?> onclick="javascript:document.getElementById('customPurge').value = 'hour' ;" />
                                        <label for=""><?php echo _e('One hour') ; ?></label>
                                        <br />
                                        <input type="radio" name="purge_searches" id="purge_searches" value="day" <?php echo ((osc_purge_latest_searches()=='day') ? 'checked="checked"' : ''); ?> onclick="javascript:document.getElementById('customPurge').value = 'day' ;" />
                                        <label for=""><?php echo _e('One day') ; ?></label>
                                        <br />
                                        <input type="radio" name="purge_searches" id="purge_searches" value="week" <?php echo ((osc_purge_latest_searches()=='week') ? 'checked="checked"' : ''); ?> onclick="javascript:document.getElementById('customPurge').value = 'week' ;" />
                                        <label for=""><?php echo _e('One week') ; ?></label>
                                        <br />
                                        <input type="radio" name="purge_searches" id="purge_searches" value="forever" <?php echo ((osc_purge_latest_searches()=='forever') ? 'checked="checked"' : ''); ?> onclick="javascript:document.getElementById('customPurge').value = 'forever' ;" />
                                        <label for=""><?php echo _e('Forever (never delete)') ; ?></label>
                                        <br />
                                        <input type="radio" name="purge_searches" id="purge_searches" value="1000" <?php echo ((osc_purge_latest_searches()=='1000') ? 'checked="checked"' : ''); ?> onclick="javascript:document.getElementById('customPurge').value = '1000' ;" />
                                        <label for=""><?php echo _e('Keep last 1000 searches') ; ?></label>
                                        <br />
                                        
                                        <?php if(osc_purge_latest_searches()!='hour' && osc_purge_latest_searches()!='day' && osc_purge_latest_searches()!='week' && osc_purge_latest_searches()!='forever' && osc_purge_latest_searches()!='1000') { $custom_checked = true; } else { $custom_checked = false; }; ?>
                                        <input type="radio" name="purge_searches" id="purge_searches" value="custom" <?php echo (($custom_checked) ? 'checked="checked"' : ''); ?> />
                                        <label for="tf_custom"><?php _e('Number of files to keep') ; ?>:</label> <input type="text" <?php echo (($custom_checked) ? 'value="' . osc_purge_latest_searches() . '"' : ''); ?> onkeyup="javascript:document.getElementById('customPurge').value = this.value;"/>
                                        <input type="hidden" name="customPurge" id="customPurge" value="<?php echo osc_purge_latest_searches(); ?>" />
                                        <br/>
                                        <label><?php _e('Latest searches functionality could generate a lot of data. It\'s recomended you purge that data. You could delete one hour old, one day old or one week old, you could also specify the number of searches to keep or to never delete them.'); ?></label>
                                    </div>
                                </fieldset>
                            </div>
                            
                            <div style="clear: both;"></div>
                            <input id="button_save" type="submit" value="<?php _e('Update') ; ?>" />
                        </form>

                    </div>
                </div>
            </div> <!-- end of right column -->
        </div><!-- end of container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>