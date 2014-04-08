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

    osc_enqueue_script('jquery-treeview');

    $fields     = __get('fields');
    $categories = __get('categories');
    $selected   = __get('default_selected');

    function addHelp() {
        echo '<p>' . __('Create new fields for users to fill out when they publish a listing. You can require extra  information such as the number of bedrooms in real estate listings or fuel type in car listings, for example.') . '</p>';
    }
    osc_add_hook('help_box','addHelp');

    function customPageHeader(){ ?>
        <h1><?php _e('Listing'); ?>
            <a href="#" class="btn ico ico-32 ico-help float-right"></a>
            <a href="#" class="btn btn-green ico ico-32 ico-add-white float-right" id="add-button"><?php _e('Add custom field'); ?></a>
        </h1>
<?php
}
osc_add_hook('admin_page_header','customPageHeader');
//customize Head
function customHead() {
    $csrf_token = osc_csrf_token_url(); ?>
<script type="text/javascript">
    function show_iframe(class_name, id) {
        if($('.content_list_'+id+' .custom-field-frame').length == 0){
            $('.custom-field-frame').remove();
            var name = 'frame_'+ id;
            var id_  = 'frame_'+ id;
            var url  = '<?php echo osc_admin_base_url(true); ?>?page=ajax&action=field_categories_iframe&<?php echo $csrf_token; ?>&id=' + id;
            $.ajax({
                url: url,
                context: document.body,
                success: function(res){
                    $('div.'+class_name).html(res);
                    $('div.'+class_name).fadeIn("fast");
                }
            });
        } else {
            $('.custom-field-frame').remove();
        }
        return false;
    }

    function delete_field(id) {
        $("#dialog-delete-field").attr('data-field-id', id);
        $("#dialog-delete-field").dialog('open');
        return false;
    }

     // check all the categories
    function checkAll(id, check) {
        aa = $('#' + id + ' input[type=checkbox]').each(function() {
            $(this).prop('checked', check);
        });
    }
    function checkCat(id, check) {
        aa = $('#cat' + id + ' input[type=checkbox]').each(function() {
            $(this).prop('checked', check);
        });
    }

    $(document).ready(function() {
        $('.cfield-div').on('mouseenter',function(){
            $(this).addClass('cfield-hover');
        }).on('mouseleave',function(){
            $(this).removeClass('cfield-hover');
        });

        // dialog delete
        $("#dialog-delete-field").dialog({
            autoOpen: false,
            modal: true
        });
        $("#field-delete-submit").click(function() {
            var id  = $("#dialog-delete-field").attr('data-field-id');
            var url = '<?php echo osc_admin_base_url(true); ?>?page=ajax&action=delete_field&<?php echo $csrf_token; ?>&id=' + id;
            $.ajax({
                url: url,
                context: document.body,
                success: function(res){
                    var ret = eval( "(" + res + ")");
                    var message = "";
                    if(ret.error) {
                        message += ret.error;
                    }
                    if(ret.ok){
                        message += ret.ok;

                        $('#list_'+id).fadeOut("slow");
                        $('#list_'+id).remove();
                    }

                    $(".jsMessage").css('display', 'block');
                    $(".jsMessage p").html(message);
                },
                error: function(){
                    $(".jsMessage").css('display', 'block');
                    $(".jsMessage p").html('<?php echo osc_esc_js( __("Ajax error, try again.") ); ?>');
                }
            });
            $('#dialog-delete-field').dialog('close');
            return false;
        });

        $("#add-button, .add-button").bind('click', function() {
            $.ajax({
                url: '<?php echo osc_admin_base_url(true); ?>?page=ajax&action=add_field&<?php echo $csrf_token; ?>',
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
                                    html += '<a href="javascript:void(0);"  onclick="show_iframe(\'content_list_'+ret.field_id+'\',\''+ret.field_id+'\');"><?php echo osc_esc_js(__('Edit')); ?></a>';
                                    html += ' &middot; ';
                                    html += '<a href="javascript:void(0);"  onclick="delete_field(\''+ret.field_id+'\');"><?php echo osc_esc_js(__('Delete')); ?></a>';
                                html += '</div>';
                                html += '<div class="edit content_list_'+ret.field_id+'"></div>';
                            html += '</div>';
                        html += '</li>';
                        $("#fields-empty").remove();
                        $("#ul_fields").append(html);
                        show_iframe('content_list_'+ret.field_id, ret.field_id);
                    } else {
                        var message = "";
                        message += '<?php echo osc_esc_js(__('Custom field could not be added')); ?>'
                        $(".jsMessage").fadeIn('fast');
                        $(".jsMessage p").html(message);
                    }
                }
            });
            return false;
        });

        $("#new_cat_tree").treeview({
            animated: "fast",
            collapsed: true
        });

        $("select[name='field_type_new']").bind('change', function() {
            if( $(this).attr('value') == 'DROPDOWN' || $(this).attr('value') == 'RADIO' ) {
                $('#div_field_options').show();
            } else {
                $('#div_field_options').hide();
            }
        });

        var field_type_new_value = $("select[name='field_type_new']").attr('value');
        if( field_type_new_value == 'TEXT' || field_type_new_value == 'TEXTAREA' || field_type_new_value == 'CHECKBOX' || field_type_new_value == 'URL') {
            $('#div_field_options').hide();
        }
    });
</script>
    <?php
}
    osc_add_hook('admin_header','customHead', 10);

    function customPageTitle($string) {
        return sprintf(__('Custom fields &raquo; %s'), $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');

    osc_current_admin_theme_path('parts/header.php');
?>
<div class="header_title">
    <h2 class="render-title"><?php _e('Custom fields'); ?> <a href="javascript:void(0);" class="btn btn-mini add-button"><?php _e('Add new'); ?></a></h2>
</div>
<!-- custom fields -->
<div class="custom-fields">
    <!-- list fields -->
    <div class="list-fields">
        <ul id="ul_fields">
        <?php $even = true;
        if( count($fields) == 0 ) { ?>
            <span id="fields-empty"><?php _e("You don't have any custom fields yet"); ?></span>
        <?php } else {
            foreach($fields as $field) { ?>
                <li id="list_<?php echo $field['pk_i_id']; ?>" class="field_li <?php echo ( $even ? 'even' : 'odd' ); ?>">
                    <div class="cfield-div" field_id="<?php echo $field['pk_i_id']; ?>" >
                        <div class="name-edit-cfield" id="<?php echo "quick_edit_" . $field['pk_i_id']; ?>">
                            <?php echo $field['s_name']; ?>
                        </div>
                        <div class="actions-edit-cfield">
                            <a href="javascript:void(0);" onclick="javascript:show_iframe('content_list_<?php echo $field['pk_i_id']; ?>','<?php echo $field['pk_i_id']; ?>');"><?php _e('Edit'); ?></a>
                             &middot;
                            <a href="javascript:void(0);" onclick="javascript:delete_field('<?php echo $field['pk_i_id']; ?>');"><?php _e('Delete'); ?></a>
                        </div>
                        <div class="edit content_list_<?php echo $field['pk_i_id']; ?>"></div>
                    </div>
                </li>
                <?php $even = !$even; }
        } ?>
        </ul>
    </div>
    <!-- /list fields -->
</div>
<!-- /custom fields -->
<div class="clear"></div>
<div id="dialog-delete-field" title="<?php echo osc_esc_html(__('Delete custom field')); ?>" class="has-form-actions hide" data-field-id="">
    <div class="form-horizontal">
        <div class="form-row">
            <?php _e('Are you sure you want to delete this custom field?'); ?>
        </div>
        <div class="form-actions">
            <div class="wrapper">
                <a class="btn" href="javascript:void(0);" onclick="$('#dialog-delete-field').dialog('close');"><?php _e('Cancel'); ?></a>
                <a id="field-delete-submit" href="javascript:void(0);" class="btn btn-red" ><?php echo osc_esc_html( __('Delete') ); ?></a>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>
<?php osc_current_admin_theme_path('parts/footer.php'); ?>