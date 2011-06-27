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

    $fields = __get("fields");
    $last = end($fields); $last_id = $last['pk_i_id'];
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
            
            function show_iframe(class_name, id) {

                //$('.edit #settings_form').remove();

                var name = 'frame_'+ id ; 
                var id_  = 'frame_'+ id ;
                var url  = '<?php echo osc_admin_base_url(true); ?>?page=ajax&action=field_categories_iframe&id='+id;
                $.ajax({
                    url: url,
                    context: document.body,
                    success: function(res){
                        $('div.'+class_name).html(res);
                        $('div.'+class_name).fadeIn("fast");
                    }
                });
                
                return false;
            }
            
            
        </script>
        <div id="content">
            <div id="separator"></div>	
            <?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>
            <div id="right_column">
                <div id="content_header" class="content_header">
                    <div style="float: left;">
                        <img src="<?php echo osc_current_admin_theme_url('images/cat-icon.png') ; ?>" title="" alt="" />
                    </div>
                    <div id="content_header_arrow">&raquo; <?php _e('Custom Fields'); ?></div>
                    <div id="jsMessage" class="" style="float:right;display:none;"></div>
                    <div style="clear: both;"></div>
                </div>
                <div id="content_separator"></div>
                <?php osc_show_flash_message('admin') ; ?>

                <div id="jsMessage" class="FlashMessage" style="display:none;"></div>

                <div style="clear: both;"></div>
                <div id="TableFields" class="TableFields">
                    <ul>
                    <?php $even = true;
                    foreach($fields as $field) {?>
                        <li id="list_<?php echo $field['pk_i_id']; ?>" class="field_li <?php echo $even?'even':'odd';?>" >
                            <div class="field_div" field_id="<?php echo $field['pk_i_id'];?>" >
                                <div class="quick_edit" id="<?php echo "quick_edit_".$field['pk_i_id']; ?>" style="float:left;">
                                    <?php echo $field['s_name'];?> 
                                </div>
                                <div style="float:right;">
                                    <a onclick="show_iframe('content_list_<?php echo $field['pk_i_id'];?>','<?php echo $field['pk_i_id'];?>');">
                                    <?php _e('Edit'); ?>
                                    </a>
                                </div>
                                <div class="edit content_list_<?php echo $field['pk_i_id']; ?>"></div>
                                <div style="clear: both;"></div>
                                
                            </div>
                        </li>
                        <?php $even = !$even; } ?>
                    </ul>
                </div>
                <div>
                    <div style="padding: 20px;">
                        <form action="<?php echo osc_admin_base_url(true); ?>" method="post">
                            <input type="hidden" name="page" value="cfields" />
                            <input type="hidden" name="action" value="add_post" />
                            <div style="float: left; width: 100%;">
                                <fieldset>
                                    <legend><?php _e('Add new custom field'); ?></legend>
                                    <label for="auto_cron"><?php _e('Name'); ?></label>
                                    <input type="text" name="field_name" id="field_name" value="" />
                                    <br/>
                                    <label><?php _e('Type'); ?></label>
                                    <select name="field_type" id="field_type">
                                        <option value="TEXT">TEXT</option>
                                        <option value="TEXTAREA">TEXTAREA</option>
                                    </select>
                                </fieldset>
                            </div>
                            <div style="clear: both;"></div>
                            <input id="button_save" type="submit" value="<?php _e('Add') ; ?>" />
                        </form>
                    </div>
                </div>
                <div style="clear: both;"></div>
            </div> <!-- end of right column -->
            <div style="clear: both;"></div>
        </div> <!-- end of container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>