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

    $fields     = __get("fields");
    $last       = end($fields); $last_id = $last['pk_i_id'];
    $categories = __get("categories");
    $selected   = __get("default_selected");
    $numCols    = 1;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <link href="<?php echo osc_current_admin_theme_styles_url('jquery.treeview.css') ; ?>" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.treeview.js') ; ?>"></script>
        <div id="update_version" style="display:none;"></div>
        <script type="text/javascript">
            
            function show_iframe(class_name, id) {
                $('div[class^="edit content_list_"]').each(function(){
                    $(this).html('');
                });

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
            
            function show_add() {
                $('#addframe').fadeIn('fast');
            }
            $(document).ready(function(){
                $("#addframe").hide();
            });
                
            function delete_field(id){
                var answer = confirm('<?php _e('WARNING: This will also delete the information related to this field. This action cann not be undone. Are you sure you want to continue?'); ?>');
                if(answer){
                    var url  = '<?php echo osc_admin_base_url(true); ?>?page=ajax&action=delete_field&id='+id;
                    $.ajax({
                        url: url,
                        context: document.body,
                        success: function(res){
                            var ret = eval( "(" + res + ")");
                            var message = "";
                            if(ret.error) { 
                                message += '<img style="padding-right:5px;padding-top:2px;" src="<?php echo osc_current_admin_theme_url('images/cross.png'); ?>"/>';
                                message += ret.error; 
                            }
                            if(ret.ok){
                                message += '<img style="padding-right:5px;padding-top:2px;" src="<?php echo osc_current_admin_theme_url('images/tick.png'); ?>"/>';
                                message += ret.ok;
                                
                                $('#list_'+id).fadeOut("slow");
                                $('#list_'+id).remove();
                            }
                            
                            $("#jsMessage").fadeIn("fast");
                            $("#jsMessage").html(message);
                            setTimeout(function(){
                                $("#jsMessage").fadeOut("slow", function () {
                                    $("#jsMessage").html("");
                                });
                            }, 3000);

                        },
                        error: function(){
                            $("#jsMessage").fadeIn("fast");
                            $("#jsMessage").html("<?php _e('Ajax error, try again.');?>");

                            setTimeout(function(){
                                $("#jsMessage").fadeOut("slow", function () {
                                    $("#jsMessage").html("");
                                });
                            }, 3000);
                        }
                    });
                }
                return false;
            }
            
            $(document).ready(function(){
                $("#new_cat_tree").treeview({
                    animated: "fast",
                    collapsed: true
                });
                
                
                $("select[name='field_type_new']").change(function() {
                    if($(this).attr('value')=='DROPDOWN' || $(this).attr('value')=='RADIO') {
                        $('#div_field_options').show();
                    } else {
                        $('#div_field_options').hide();
                    }
                });
                if($("select[name='field_type_new']").attr('value')=='TEXT' || $("select[name='field_type_new']").attr('value')=='TEXTAREA') {
                    $('#div_field_options').hide();
                }

                
            });

            function checkAll (id, check) {
                aa = $('#'+id+' input[type=checkbox]').each(
                    function(){
                        $(this).attr('checked', check);
                    }
                );
            }

            function checkCat(id, check) {
                aa = $('#cat'+id+' input[type=checkbox]').each(
                    function(){
                        $(this).attr('checked', check);
                    }
                );
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
                    <div style="float:right;"><button id="button_add" onclick="show_add();" ><?php _e('Add new field') ; ?></button></div>
                    <div id="jsMessage" class="" style="float:right;display:none;"></div>
                    <div style="clear: both;"></div>
                </div>
                <div id="content_separator"></div>
                <?php osc_show_flash_message('admin') ; ?>

                <div id="jsMessage" class="FlashMessage" style="display:none;"></div>

                <div style="clear: both;"></div>
                <div id="addframe">
                    <div style="padding: 20px;">
                        <form id="new_field_form" action="<?php echo osc_admin_base_url(true); ?>" method="post">
                            <input type="hidden" name="page" value="cfields" />
                            <input type="hidden" name="action" value="add_post" />
                            <div style="float: left; width: 100%;">
                                <fieldset>
                                    <legend><?php _e('Add new custom field'); ?></legend>
                                    <div class="FormElement">
                                        <label for="name"><?php _e('Name'); ?></label>
                                        <input type="text" name="field_name" id="field_name" value="" />
                                    </div>
                                    <br/>
                                    <div class="FormElement">
                                        <label><?php _e('Type'); ?></label>
                                        <select name="field_type_new" id="field_type">
                                            <option value="TEXT">TEXT</option>
                                            <option value="TEXTAREA">TEXTAREA</option>
                                            <option value="DROPDOWN">DROPDOWN</option>
                                            <option value="RADIO">RADIO</option>
                                        </select>
                                    </div>
                                    <div class="FormElement" id="div_field_options">
                                        <label for="name"><?php _e('Options (separeted by commas)'); ?></label>
                                        <input type="text" name="field_options" id="field_options" value="" />
                                    </div>
                                    <div class="FormElement">
                                        <input type="checkbox" id="field_required" name="field_required" value="1"/>
                                        <label><?php _e('This field is required'); ?></label>
                                    </div>
                                    <div class="FormElement">
                                        <p>
                                            <?php _e('Select the categories where you want to apply these attribute'); ?>:
                                        </p>
                                        <p>
                                            <table>
                                                <tr style="vertical-align: top;">
                                                    <td style="font-weight: bold;" colspan="<?php echo $numCols; ?>">
                                                        <label for="categories"><?php _e("Preset categories");?></label><br />
                                                        <a style="font-size: x-small; color: gray;" href="#" onclick="checkAll('cat_tree', true); return false;"><?php _e("Check all");?></a> - <a style="font-size: x-small; color: gray;" href="#" onclick="checkAll('cat_tree', false); return false;"><?php _e("Uncheck all");?></a>
                                                    </td>
                                                    <td>
                                                        <ul id="new_cat_tree">
                                                            <?php CategoryForm::categories_tree($categories, $selected); ?>
                                                        </ul>
                                                    </td>
                                                </tr>
                                            </table>
                                        </p>
                                    </div>
                                    <div id="advanced_fields" class="shrink">
                                        <div class="text">
                                            <span><?php _e('Advanced options'); ?></span>
                                        </div>
                                    </div>
                                    <hr></hr>
                                    <script type="text/javascript">
                                        $(document).ready(function() {
                                            $('#advanced_fields').click(function() {
                                                $('#more-options').toggle();
                                                if( $('#advanced_fields').attr('class') == 'shrink' ) {
                                                    $('#advanced_fields').removeClass('shrink');
                                                    $('#advanced_fields').addClass('expanded');
                                                } else {
                                                    $('#advanced_fields').addClass('shrink');
                                                    $('#advanced_fields').removeClass('expanded');
                                                }
                                            });
                                            $('#more-options').hide();
                                        });
                                    </script>
                                    <div id="more-options" class="FormElement">
                                        <label for="slug"><?php _e('Identifier name (only alphanumeric characters are allowed (a-z0-9_-)'); ?></label>
                                        <input type="text" name="field_slug" id="field_slug" value="" />
                                    </div>
                                    <span style="float:right;"><input id="button_save" type="submit" value="<?php _e('Add') ; ?>" /></span>
                                </fieldset>
                            </div>
                            <div style="clear: both;"></div>
                        </form>
                    </div>
                </div>
                
                <div id="TableFields" class="TableFields">
                    <ul>
                    <?php $even = true;
                    if(count($fields)==0) { ?>
                        <?php _e("You don't have any custom fields yet"); ?> <button id="button_add" onclick="show_add();" ><?php _e('Add new field') ; ?></button>
                    <?php } else {
                        foreach($fields as $field) {?>
                            <li id="list_<?php echo $field['pk_i_id']; ?>" class="field_li <?php echo $even?'even':'odd';?>" >
                                <div class="field_div" field_id="<?php echo $field['pk_i_id'];?>" >
                                    <div class="quick_edit" id="<?php echo "quick_edit_".$field['pk_i_id']; ?>" style="float:left;">
                                        <?php echo $field['s_name'];?> 
                                    </div>
                                    <div style="float:right;">
                                        <a onclick="show_iframe('content_list_<?php echo $field['pk_i_id'];?>','<?php echo $field['pk_i_id'];?>');"><?php _e('Edit'); ?></a>
                                        <span> | </span>
                                        <a onclick="delete_field('<?php echo $field['pk_i_id'];?>');"><?php _e('Delete'); ?></a>
                                    </div>
                                    <div class="edit content_list_<?php echo $field['pk_i_id']; ?>"></div>
                                    <div style="clear: both;"></div>

                                </div>
                            </li>
                            <?php $even = !$even; }
                    };?>
                    </ul>
                </div>
                <div style="clear: both;"></div>
            </div> <!-- end of right column -->
            <div style="clear: both;"></div>
        </div> <!-- end of container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>