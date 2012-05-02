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
    
    $field      = __get('field') ;
    $categories = __get('categories') ;
    $selected   = __get('selected') ;
?>
<!-- custom field frame -->
<div id="edit-custom-field-frame" class="custom-field-frame">
    <form id="nedit_field_form" action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
        <input type="hidden" name="page" value="ajax" />
        <input type="hidden" name="action" value="field_categories_post" />
        <?php FieldForm::primary_input_hidden($field) ; ?>
        <fieldset>
            <h3><?php _e('Edit custom field') ; ?></h3>
            <div class="input-line">
                <label><?php _e('Name') ; ?></label>
                <div class="input medium">
                    <?php FieldForm::name_input_text($field) ; ?>
                </div>
            </div>
            <div class="input-line">
                <label><?php _e('Type') ; ?></label>
                <div class="input">
                    <?php FieldForm::type_select($field) ; ?>
                </div>
            </div>
            <div id="div_field_options" class="input-line">
                <label><?php _e('Options') ; ?></label>
                <div class="input xlarge">
                    <?php FieldForm::options_input_text($field) ; ?>
                    <p class="help-inline"><?php _e('Separate the options by commas') ; ?></p>
                </div>
            </div>
            <div class="input-line">
                <label></label>
                <div class="input">
                    <label class="checkbox">
                        <?php FieldForm::required_checkbox($field); ?>
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
                            <a href="javascript:void() ;" onclick="checkAll('cat_tree', true) ; return false ;"><?php _e('Check all') ; ?></a> &middot;
                            <a href="javascript:void() ;" onclick="checkAll('cat_tree', false) ; return false ;"><?php _e('Uncheck all') ; ?></a>
                        </td>
                        <td>
                            <ul id="cat_tree">
                                <?php CategoryForm::categories_tree($categories, $selected) ; ?>
                            </ul>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="advanced_fields_iframe" class="custom-field-shrink">
                <p><?php _e('Advanced options') ; ?></p>
            </div>
            <div id="more-options_iframe" class="input-line">
                <label><?php _e('Identifier name') ; ?></label>
                <div class="input medium">
                    <input type="text" class="medium" name="field_slug" value="<?php echo $field['s_slug'] ; ?>" />
                    <p class="help-inline"><?php _e('Only alphanumeric characters are allowed [a-z0-9_-]') ; ?></p>
                </div>
            </div>
            <div class="actions-cfield">
                <input type="submit" value="<?php echo osc_esc_html( __('Save changes') ) ; ?>">
                <input type="button" value="<?php echo osc_esc_html( __('Cancel') ) ; ?>" onclick="$('#edit-custom-field-frame').remove() ;">
            </div>
        </fieldset>
    </form>
</div>
<!-- /custom field frame -->
<script type="text/javascript">
    $(document).ready(function(){
        $("#cat_tree").treeview({
            animated: "fast",
            collapsed: true
        }) ;

        $('select[name="field_type"]').change(function() {
            if( $(this).attr('value') == 'DROPDOWN' || $(this).attr('value') == 'RADIO' ) {
                $('#div_field_options').show() ;
            } else {
                $('#div_field_options').hide() ;
            }
        }) ;

        if( $("select[name='field_type']").attr('value') == 'TEXT' || $("select[name='field_type']").attr('value') == 'TEXTAREA' || $("select[name='field_type']").attr('value') == 'CHECKBOX' || $("select[name='field_type_new']").attr('value') == 'URL' ) {
            $('#div_field_options').hide() ;
        }

        $('#edit-custom-field-frame form').submit(function() {
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
                        message += ret.ok ;
                        $('#quick_edit_'+ret.field_id).html(ret.text);
                    }

                    $(".jsMessage").fadeIn('fast') ;
                    $(".jsMessage p").html(message) ;
                    $('div.content_list_<?php echo $field['pk_i_id'] ; ?>').html('') ;
                },
                error: function(){
                    $(".jsMessage").fadeIn('fast') ;
                    $(".jsMessage p").html("<?php _e('Ajax error, try again.') ; ?>") ;
                }
                
            })        
            return false ;
        }) ;

        $('#advanced_fields_iframe').bind('click',function() {
            $('#more-options_iframe').toggle() ;
            if( $(this).attr('class') == 'custom-field-shrink' ) {
                $(this).removeClass('custom-field-shrink');
                $(this).addClass('custom-field-expanded');
            } else {
                $(this).addClass('custom-field-shrink');
                $(this).removeClass('custom-field-expanded');
            }
        }) ;
        $('#more-options_iframe').hide() ;
    }) ;
</script>