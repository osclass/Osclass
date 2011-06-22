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

    //$categories   = __get("categories");
    $field        = __get("field");
    $categories = __get("categories");
    $selected = __get("selected");
    $plugin_data = __get("plugin_data");
    $numCols = 1;
    $catsPerCol = round(count($categories)/$numCols);
?>

<div id="settings_form">
    <form action="<?php echo osc_admin_base_url(true); ?>?page=ajax" method="post">
            <input type="hidden" name="action" value="field_categories_post" />
           <?php FieldForm::primary_input_hidden($field); ?>

            <div class="FormElement">
                <div class="FormElementName"><?php _e('Field\'s name'); ?></div>
                <div class="FormElementInput">
                    <?php FieldForm::name_input_text($field); ?>
                    <select name="field_type" id="field_type">
                        <option value="TEXT">TEXT</option>
                        <option value="TEXTAREA">TEXTAREA</option>
                    </select>
                </div>
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
                                <a style="font-size: x-small; color: gray;" href="#" onclick="checkAll('frm3', true); return false;"><?php _e("Check all");?></a> - <a style="font-size: x-small; color: gray;" href="#" onclick="checkAll('frm3', false); return false;"><?php _e("Uncheck all");?></a>
                            </td>
                            <td>
                                <?php CategoryForm::plugin_categories($categories, $selected); ?>
                            </td>
                        </tr>
                    </table>
                </p>
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