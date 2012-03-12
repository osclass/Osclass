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

    $fields     = __get('fields') ;
    $last       = end($fields) ; $last_id = $last['pk_i_id'] ;
    $categories = __get('categories') ;
    $selected   = __get('default_selected') ;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
        <link href="<?php echo osc_current_admin_theme_styles_url('jquery.treeview.css') ; ?>" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.treeview.js') ; ?>"></script>
        <script type="text/javascript">
            function show_iframe(class_name, id) {
                $('div[class^="edit content_list_"]').each(function(){
                    $(this).html('');
                });

                var name = 'frame_'+ id ; 
                var id_  = 'frame_'+ id ;
                var url  = '<?php echo osc_admin_base_url(true) ; ?>?page=ajax&action=field_categories_iframe&id=' + id ;
                $.ajax({
                    url: url,
                    context: document.body,
                    success: function(res){
                        $('div.'+class_name).html(res) ;
                        $('div.'+class_name).fadeIn("fast") ;
                    }
                }) ;

                return false ;
            }

            function delete_field(id){
                var answer = confirm('<?php echo osc_esc_js( __('WARNING: This will also delete the information related to this field. This action cann not be undone. Are you sure you want to continue?') ) ; ?>') ;
                if( answer ) {
                    var url  = '<?php echo osc_admin_base_url(true) ; ?>?page=ajax&action=delete_field&id=' + id ;
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
                            
                            $(".jsMessage").css('display', 'block') ;
                            $(".jsMessage p").html(message) ;
                        },
                        error: function(){
                            $(".jsMessage").css('display', 'block') ;
                            $(".jsMessage p").html('<?php echo osc_esc_js( __("Ajax error, try again.") ) ; ?>') ;
                        }
                    }) ;
                }
                return false ;
            }

            function checkAll (id, check) {
                aa = $('#' + id + ' input[type=checkbox]').each(function() {
                    $(this).attr('checked', check) ;
                }) ;
            }

            function checkCat(id, check) {
                aa = $('#cat' + id + ' input[type=checkbox]').each(function() {
                    $(this).attr('checked', check) ;
                }) ;
            }

            $(document).ready(function() {
                $("#add-button").bind('click', function() {
                    $('#add-custom-field-frame').fadeIn('fast') ;
                }) ;

                $("#new_cat_tree").treeview({
                    animated: "fast",
                    collapsed: true
                }) ;

                $("select[name='field_type_new']").bind('change', function() {
                    if( $(this).attr('value') == 'DROPDOWN' || $(this).attr('value') == 'RADIO' ) {
                        $('#div_field_options').show() ;
                    } else {
                        $('#div_field_options').hide() ;
                    }
                }) ;

                var field_type_new_value = $("select[name='field_type_new']").attr('value') ;
                if( field_type_new_value == 'TEXT' || field_type_new_value == 'TEXTAREA' || field_type_new_value == 'CHECKBOX' || field_type_new_value == 'URL') {
                    $('#div_field_options').hide() ;
                }
            }) ;
        </script>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <!-- container -->
        <div id="content">
            <?php osc_current_admin_theme_path( 'include/backoffice_menu.php' ) ; ?>
            <!-- right container -->
            <div class="right">
                <div class="header_title">
                    <input id="add-button" type="button" value="<?php echo osc_esc_html( __('Add new field') ) ; ?>" />
                    <h1 class="categories"><?php _e('Custom fields') ; ?></h1>
                </div>
                <?php osc_show_admin_flash_messages() ; ?>
                <div class="jsMessage alert alert-ok" style="display: none;">
                    <a class="close" href="#">×</a>
                    <p></p>
                </div>
                <!-- custom fields -->
                <div class="custom-fields">
                    <!-- custom field frame -->
                    <div id="add-custom-field-frame" class="custom-field-frame" style="display: none;">
                        <form id="new_field_form" action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
                            <input type="hidden" name="page" value="cfields" />
                            <input type="hidden" name="action" value="add_post" />
                            <fieldset>
                                <h3><?php _e('New custom field') ; ?></h3>
                                <div class="input-line">
                                    <label><?php _e('Name') ; ?></label>
                                    <div class="input medium">
                                        <input type="text" class="medium" name="field_name" value="" />
                                    </div>
                                </div>
                                <div class="input-line">
                                    <label><?php _e('Type') ; ?></label>
                                    <div class="input">
                                        <select name="field_type_new">
                                            <option value="TEXT">TEXT</option>
                                            <option value="TEXTAREA">TEXTAREA</option>
                                            <option value="DROPDOWN">DROPDOWN</option>
                                            <option value="RADIO">RADIO</option>
                                            <option value="CHECKBOX">CHECKBOX</option>
                                            <option value="URL">URL</option>
                                        </select>
                                    </div>
                                </div>
                                <div id="div_field_options" class="input-line">
                                    <label><?php _e('Options') ; ?></label>
                                    <div class="input medium">
                                        <input class="xlarge" type="text" name="field_options" value="" />
                                        <p class="help-inline"><?php _e('Separate the options by commas') ; ?></p>
                                    </div>
                                </div>
                                <div class="input-line">
                                    <label></label>
                                    <div class="input">
                                        <label class="checkbox">
                                            <input type="checkbox" name="field_required" value="1"/>
                                            <p class="inline"><?php _e('This field is required') ; ?></p>
                                        </label>
                                    </div>
                                </div>
                                <div class="categories-tree">
                                    <p>
                                        <?php _e('Select the categories where you want to apply these attribute:') ; ?>
                                    </p>
                                    <table class="preset-categories">
                                        <tr>
                                            <td>
                                                <a href="javascript:void() ;" onclick="checkAll('new_cat_tree', true) ; return false ;"><?php _e('Check all') ; ?></a> &middot;
                                                <a href="javascript:void() ;" onclick="checkAll('new_cat_tree', false) ; return false ;"><?php _e('Uncheck all') ; ?></a>
                                            </td>
                                            <td>
                                                <ul id="new_cat_tree">
                                                    <?php CategoryForm::categories_tree($categories, $selected) ; ?>
                                                </ul>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div id="advanced_fields" class="custom-field-shrink">
                                    <p><?php _e('Advanced options') ; ?></p>
                                </div>
                                <div id="more-options" class="input-line">
                                    <label><?php _e('Identifier name') ; ?></label>
                                    <div class="input medium">
                                        <input type="text" class="medium" name="field_slug" value="" />
                                        <p class="help-inline"><?php _e('Only alphanumeric characters are allowed [a-z0-9_-]') ; ?></p>
                                    </div>
                                </div>
                                <div class="actions-cfield">
                                    <input type="submit" value="<?php echo osc_esc_html( __('Add custom field') ) ; ?>">
                                    <input type="button" value="<?php echo osc_esc_html( __('Cancel') ) ; ?>" onclick="$('#add-custom-field-frame').fadeOut('fast') ;">
                                </div>
                            </fieldset>
                        </form>
                        <script type="text/javascript">
                            $(document).ready(function() {
                                $('#advanced_fields').bind('click',function() {
                                    $('#more-options').toggle() ;
                                    if( $('#advanced_fields').attr('class') == 'custom-field-shrink' ) {
                                        $('#advanced_fields').removeClass('custom-field-shrink');
                                        $('#advanced_fields').addClass('custom-field-expanded');
                                    } else {
                                        $('#advanced_fields').addClass('custom-field-shrink');
                                        $('#advanced_fields').removeClass('custom-field-expanded');
                                    }
                                }) ;
                                $('#more-options').hide() ;
                            }) ;
                        </script>
                    </div>
                    <!-- /custom field frame -->
                    <!-- list fields -->
                    <div class="list-fields">
                        <ul>
                        <?php $even = true ;
                        if( count($fields) == 0 ) { ?>
                            <?php _e("You don't have any custom fields yet") ; ?>
                        <?php } else {
                            foreach($fields as $field) { ?>
                                <li id="list_<?php echo $field['pk_i_id'] ; ?>" class="field_li <?php echo ( $even ? 'even' : 'odd' ) ; ?>">
                                    <div class="cfield-div" field_id="<?php echo $field['pk_i_id'] ; ?>" >
                                        <div class="name-edit-cfield" id="<?php echo "quick_edit_" . $field['pk_i_id'] ; ?>">
                                            <?php echo $field['s_name'] ; ?>
                                        </div>
                                        <div class="actions-edit-cfield">
                                            <a onclick="show_iframe('content_list_<?php echo $field['pk_i_id'] ; ?>','<?php echo $field['pk_i_id'] ; ?>');"><?php _e('Edit') ; ?></a>
                                             &middot;
                                            <a onclick="delete_field('<?php echo $field['pk_i_id'] ; ?>');"><?php _e('Delete') ; ?></a>
                                        </div>
                                    </div>
                                    <div class="edit content_list_<?php echo $field['pk_i_id'] ; ?>"></div>
                                </li>
                                <?php $even = !$even ; }
                        } ?>
                        </ul>
                    </div>
                    <!-- /list fields -->
                </div>
                <!-- /custom fields -->
            </div>
            <!-- /right container -->
        </div>
        <!-- /container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>