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
    
    $field          = __get("field");
    $categories     = __get("categories");
    $selected       = __get("selected");
    $numCols        = 1;
?>

<link href="<?php echo osc_current_admin_theme_styles_url('jquery.treeview.css') ; ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.treeview.js') ; ?>"></script>
<div id="settings_form">
    <form action="<?php echo osc_admin_base_url(true); ?>?page=ajax" method="post" id="field_form">
            <input type="hidden" name="action" value="field_categories_post" />
           <?php FieldForm::primary_input_hidden($field); ?>

            <div class="FormElement">
                <div class="FormElementName"><?php _e('Field\'s name'); ?></div>
                <div class="FormElementInput">
                    <?php FieldForm::name_input_text($field); ?>
                </div>
                <div style="clear:both;" ></div>
                <div class="FormElementName"><?php _e('Field\'s type'); ?></div>
                <div class="FormElementInput">
                    <?php FieldForm::type_select($field); ?>
                </div>
                <div style="clear:both;" ></div>
                <div id="div_field_options_iframe">
                    <div class="FormElementName"><?php _e('Field\'s options (separated by commas)'); ?></div>
                    <div class="FormElementInput">
                        <?php FieldForm::options_input_text($field); ?>
                    </div>
                </div>
                <div class="FormElement">
                    <input type="checkbox" id="field_required" name="field_required" value="1" <?php if($field['b_required']==1) { echo 'checked="checked"'; } ?>/>
                    <label><?php _e('This field is required'); ?></label>
                </div>
                <div style="clear:both;" ></div>
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
                                <ul id="cat_tree">
                                    <?php CategoryForm::categories_tree($categories, $selected); ?>
                                </ul>
                            </td>
                        </tr>
                    </table>
                </p>
            </div>
            <div id="advanced_fields_iframe" class="shrink">
                <div class="text">
                    <span><?php _e('Advanced options'); ?></span>
                </div>
            </div>
            <hr></hr>
            <script type="text/javascript">
                $(document).ready(function() {
                    $('#advanced_fields_iframe').click(function() {
                        $('#more-options_iframe').toggle();
                        if( $('#advanced_fields_iframe').attr('class') == 'shrink' ) {
                            $('#advanced_fields_iframe').removeClass('shrink');
                            $('#advanced_fields_iframe').addClass('expanded');
                        } else {
                            $('#advanced_fields_iframe').addClass('shrink');
                            $('#advanced_fields_iframe').removeClass('expanded');
                        }
                    });
                    $('#more-options_iframe').hide();
                });
            </script>
            <div id="more-options_iframe" class="FormElement">
                <label for="slug"><?php _e('Identifier name (only alphanumeric characters are allowed (a-z0-9_-)'); ?></label>
                <input type="text" name="field_slug" id="field_slug" value="<?php echo $field['s_slug'];?>" />
            </div>
            <div class="clear20"></div>

            <div class="FormElement">
                <div class="FormElementName"></div>
                <div class="FormElementInput">
                    <button class="formButton" type="button" onclick="$('#settings_form').remove();" ><?php _e('Cancel'); ?></button>
                    <button class="formButton" type="submit" ><?php _e('Save'); ?></button>
                </div>
            </div>

    </form>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $("#cat_tree").treeview({
            animated: "fast",
            collapsed: true
        });
        
        $("select[name='field_type']").change(function() {
            if($(this).attr('value')=='DROPDOWN' || $(this).attr('value')=='RADIO') {
                $('#div_field_options_iframe').show();
            } else {
                $('#div_field_options_iframe').hide();
            }
        });
        if($("select[name='field_type']").attr('value')=='TEXT' || $("select[name='field_type']").attr('value')=='TEXTAREA') {
            $('#div_field_options_iframe').hide();
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
    
    $(document).ready(function() {
        $('#settings_form form').submit(function() {
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: $(this).serialize(),
                // Mostramos un mensaje con la respuesta de PHP
                success: function(data) {
                    var ret = eval( "(" + data + ")");
                  
                    var message = "";
                    if(ret.error) {
                        message += '<img style="padding-right:5px;padding-top:2px;" src="<?php echo osc_current_admin_theme_url('images/cross.png');?>"/>';
                        message += ret.error; 

                    }
                    if(ret.ok){ 
                        $('#settings_form').fadeOut('fast', function(){
                            $('#settings_form').remove();
                        });
                        message += '<img style="padding-right:5px;padding-top:2px;" src="<?php echo osc_current_admin_theme_url('images/tick.png');?>"/>';
                        message += ret.ok;
                        $('div#settings_form').parent().parent().find('.quick_edit').html(ret.text);
                    }

                    $("#jsMessage").fadeIn("fast");
                    $("#jsMessage").html(message);
                    setTimeout(function(){
                        $("#jsMessage").fadeOut("slow", function () {
                            $("#jsMessage").html("");
                        });
                    }, 3000);
                    $('div.content_list_<?php echo $field['pk_i_id'];?>').html('');
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
                
            })        
            return false;
        });
        
    });     
</script>