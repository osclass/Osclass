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
                var answer = confirm('<?php echo osc_esc_js( __('WARNING: This will also delete the information related to this field. This action cannot be undone. Are you sure you want to continue?') ) ; ?>') ;
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
    						$(".jsMessage p").html("<?php echo osc_esc_html ( __('Ajax error, try again.') ) ; ?>") ;
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
                    $.ajax({
                        url: '<?php echo osc_admin_base_url(true) ; ?>?page=ajax&action=add_field',
                        context: document.body,
                        success: function(res){
                            var ret = eval( "(" + res + ")");
                            if(ret.error==0) {
                                var html = '';
                                html += '<li id="list_'+ret.field_id+'" class="field_li even">';
                                    html += '<div class="cfield-div" field_id="'+ret.field_id+'" >';
                                        html += '<div class="name-edit-cfield" id="quick_edit_'+ret.field_id+'">';
                                            html += ret.field_name;
                                        html += '</div>';
                                        html += '<div class="actions-edit-cfield">';
                                            html += '<a onclick="show_iframe(\'content_list_'+ret.field_id+'\',\''+ret.field_id+'\');"><?php _e('Edit') ; ?></a>';
                                            html += ' &middot; ';
                                            html += '<a onclick="delete_field(\''+ret.field_id+'\');"><?php _e('Delete') ; ?></a>';
                                        html += '</div>';
                                    html += '</div>';
                                    html += '<div class="edit content_list_'+ret.field_id+'"></div>';
                                html += '</li>';
                                $("#ul_fields").append(html);
                                show_iframe('content_list_'+ret.field_id, ret.field_id);
                            } else {
                                var message = "";
                                message += '<img style="padding-right:5px;padding-top:2px;" src="<?php echo osc_current_admin_theme_url('images/cross.png');?>"/>';
                                message += '<?php _e('Custom field could not be added'); ?>'
                                $(".jsMessage").fadeIn('fast') ;
                                $(".jsMessage p").html(message) ;
                            }
                        }
                    }) ;
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
                <?php osc_show_flash_message('admin') ; ?>
                <div class="jsMessage FlashMessage ok" style="display: none;">
                    <a class="close" href="#">×</a>
                    <p></p>
                </div>
                <!-- custom fields -->
                <div class="custom-fields">
                    <!-- list fields -->
                    <div class="list-fields">
                        <ul id="ul_fields">
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
                <div class="clear"></div>
            </div>
            <!-- /right container -->
        </div>
        <!-- /container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>