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

    $category   = __get("category");
    $action_frm = "edit_category_post";
    
?>

<div id="settings_form">
    <form action="<?php echo osc_admin_base_url(true); ?>?page=ajax" method="post">
            <input type="hidden" name="action" value="<?php echo $action_frm; ?>" />
            
            <?php CategoryForm::primary_input_hidden($category) ; ?>
            
            <div class="FormElement">
                <div class="FormElementName"><?php _e('Expirations days'); ?> <?php _e('(0 = no expiration)'); ?></div>
                <div class="FormElementInput">
                   <?php CategoryForm::expiration_days_input_text($category); ?>
                </div>
            </div>

            <div class="clear20"></div>

            <?php 
                $locales = OSCLocale::newInstance()->listAllEnabled();
                CategoryForm::multilanguage_name_description($locales, $category) ; 
            ?>

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
                    if(ret.error==0 || ret.error==4) {
                        $('#settings_form').fadeOut('fast', function(){
                            $('#settings_form').remove();
                        });
                        message += '<img style="padding-right:5px;padding-top:2px;" src="<?php echo osc_current_admin_theme_url('images/tick.png');?>"/>';
                        message += ret.msg;
                        $('div#settings_form').parent().parent().find('.quick_edit').html(ret.text);
                    } else {
                        message += '<img style="padding-right:5px;padding-top:2px;" src="<?php echo osc_current_admin_theme_url('images/cross.png');?>"/>';
                        message += ret.msg; 

                    }

                    $("#jsMessage").fadeIn("fast");
                    $("#jsMessage").html(message);
                    setTimeout(function(){
                        $("#jsMessage").fadeOut("slow", function () {
                            $("#jsMessage").html("");
                        });
                    }, 3000);
                    $('div.content_list_<?php echo osc_category_id();?>').html('');
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
    tabberAutomatic();
</script>