<?php if ( ! defined('OC_ADMIN')) exit('Direct access is not allowed.');
/*
 * Copyright 2014 Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

    $field      = __get('field');
    $categories = __get('categories');
    $selected   = __get('selected');
?>
<!-- custom field frame -->
<div id="edit-custom-field-frame" class="custom-field-frame">
    <div class="form-horizontal">
        <form id="nedit_field_form" action="<?php echo osc_admin_base_url(true); ?>" method="post">
        <input type="hidden" name="page" value="ajax" />
        <input type="hidden" name="action" value="field_categories_post" />
        <?php FieldForm::primary_input_hidden($field); ?>
        <fieldset>
            <h3><?php _e('Edit custom field'); ?></h3>
            <div class="form-row">
                <div class="form-label"><?php _e('Name'); ?></div>
                <div class="form-controls"><?php FieldForm::name_input_text($field); ?></div>
            </div>
            <div class="form-row">
                <div class="form-label"><?php _e('Type'); ?></div>
                <div class="form-controls"><?php FieldForm::type_select($field); ?></div>
            </div>
            <div class="form-row" id="div_field_options">
                <div class="form-label"><?php _e('Options'); ?></div>
                <div class="form-controls">
                    <?php FieldForm::options_input_text($field); ?>
                    <p class="help-inline"><?php _e('Separate options with commas'); ?></p>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label"></div>
                <div class="form-controls"><label><?php FieldForm::required_checkbox($field); ?> <span><?php _e('This field is required'); ?></span></label></div>
            </div>
            <div class="form-row">
                <div><?php _e('Select the categories where you want to apply this attribute:'); ?></div>
                <div class="separate-top">
                <div class="form-label">
                    <a href="javascript:void(0);" onclick="checkAll('cat_tree', true); return false;"><?php _e('Check all'); ?></a> &middot;
                    <a href="javascript:void(0);" onclick="checkAll('cat_tree', false); return false;"><?php _e('Uncheck all'); ?></a>
                </div>
                <div class="form-controls">
                    <ul id="cat_tree">
                        <?php CategoryForm::categories_tree($categories, $selected); ?>
                    </ul>
                </div>
                </div>
            </div>

            <div id="advanced_fields_iframe" class="custom-field-shrink">
                <div class="icon-more"></div><?php _e('Advanced options'); ?>
            </div>
            <div id="more-options_iframe" class="input-line">
                <div class="form-row" id="div_field_options">
                    <div class="form-label"><?php _e('Identifier name'); ?></div>
                    <div class="form-controls">
                        <input type="text" class="medium" name="field_slug" value="<?php echo $field['s_slug']; ?>" />
                        <p class="help-inline"><?php _e('Only alphanumeric characters are allowed [a-z0-9_-]'); ?></p>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label"></div>
                    <div class="form-controls"><label><?php FieldForm::searchable_checkbox($field); ?> <?php _e('Tick to allow searches by this field'); ?></label></div>
                </div>
            </div>
            <div class="form-actions">
                <input type="submit" id="cfield_save" value="<?php echo osc_esc_html( __('Save changes') ); ?>" class="btn btn-submit" />
                <input type="button" value="<?php echo osc_esc_html( __('Cancel') ); ?>" class="btn btn-red" onclick="$('#edit-custom-field-frame').remove();" />
            </div>
        </fieldset>
    </form>
    </div>
</div>
<!-- /custom field frame -->
<script type="text/javascript">
    $(document).ready(function(){
        $("#cat_tree").treeview({
            animated: "fast",
            collapsed: true
        });

        $('select[name="field_type"]').change(function() {
            if( $(this).prop('value') == 'DROPDOWN' || $(this).prop('value') == 'RADIO' ) {
                $('#div_field_options').show();
            } else {
                $('#div_field_options').hide();
            }
        });

        $('select[name="field_type"]').change();

        $('#edit-custom-field-frame form').submit(function() {
            if( ($('select[name="field_type"]').prop('value') == 'DROPDOWN' || $('select[name="field_type"]').prop('value') == 'RADIO') && $("#s_options").prop("value")=="") {
                $(".jsMessage").fadeIn('fast');
                $(".jsMessage p").html('<?php echo osc_esc_js(__('At least one option is required.')); ?>');
                return false;
            }

            $.ajax({
                type: 'POST',
                url: '<?php echo osc_admin_base_url(true); ?>',
                data: $(this).serialize(),
                // Mostramos un mensaje con la respuesta de PHP
                success: function(data) {
                    var ret = eval( "(" + data + ")");

                    var message = "";
                    if(ret.error) {
                        message += ret.error;
                    }
                    if(ret.ok){
                        $('#settings_form').fadeOut('fast', function(){
                            $('#settings_form').remove();
                        });
                        message += ret.ok;
                        $('#quick_edit_'+ret.field_id).html(ret.text);
                    }

                    $(".jsMessage").fadeIn('fast');
                    $(".jsMessage p").html(message);
                    $('div.content_list_<?php echo $field['pk_i_id']; ?>').html('');
                },
                error: function(){
                    $(".jsMessage").fadeIn('fast');
                    $(".jsMessage p").html('<?php echo osc_esc_js(__('Ajax error, try again.')); ?>');
                }

            })
            return false;
        });

        $('#advanced_fields_iframe').bind('click',function() {
            $('#more-options_iframe').toggle();
            if( $(this).hasClass('custom-field-shrink')) {
                $(this).removeClass('custom-field-shrink');
                $(this).addClass('custom-field-expanded');
            } else {
                $(this).addClass('custom-field-shrink');
                $(this).removeClass('custom-field-expanded');
            }
        });
        $('#more-options_iframe').hide();
    });
</script>