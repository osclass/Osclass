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

    osc_enqueue_script('jquery-validate');
    osc_enqueue_script('admin-location');

    $aCountries = __get('aCountries');
    //customize Head
    function customHead(){
        ?>
       <script type="text/javascript">
            $(document).ready(function(){
                // dialog delete
                $("#dialog-location-delete").dialog({
                    autoOpen: false,
                    modal: true,
                    title: '<?php echo osc_esc_js( __('Delete location') ); ?>'
                });

                $(".trc").on("mouseenter", function() {
                    $(this).find(".checkboxc").css({ 'visibility': ''});
                });

                $(".trc").on("mouseleave", function() {
                    if (!$(this).find(".checkboxc input").is(':checked')) {
                        $(this).find(".checkboxc").css({ 'visibility': 'hidden'});
                    };
                    if($(".checkboxc input:checked").size()>0) {
                        $("#b_remove_country").show();
                    } else {
                        $("#b_remove_country").hide();
                    };
                });

                $("#b_remove_country").on("click", function() {
                    $("#dialog-location-delete input[name='id[]']").remove();
                    $(".checkboxc input:checked").each(function() {
                        $("#dialog-location-delete").append('<input type="hidden" name="id[]" value="'+$(this).attr("value")+'" />');
                    });
                    $("#dialog-location-delete input[name='type']").attr('value', 'delete_country');

                    $("#dialog-location-delete").dialog('open');
                    return false;
                });

                $("#b_remove_region").on("click", function() {
                    $("#dialog-location-delete input[name='id[]']").remove();
                    $(".checkboxr input:checked").each(function() {
                        $("#dialog-location-delete").append('<input type="hidden" name="id[]" value="'+$(this).attr("value")+'" />');
                    });
                    $("#dialog-location-delete input[name='type']").attr('value', 'delete_region');

                    $("#dialog-location-delete").dialog('open');
                    return false;
                });

                $("#b_remove_city").on("click", function() {
                    $("#dialog-location-delete input[name='id[]']").remove();
                    $(".checkboxct input:checked").each(function() {
                        $("#dialog-location-delete").append('<input type="hidden" name="id[]" value="'+$(this).attr("value")+'" />');
                    });
                    $("#dialog-location-delete input[name='type']").attr('value', 'delete_city');
                    $("#dialog-location-delete").dialog('open');
                    return false;
                });

                $("#e_country_slug").on("keyup", function() {
                    $("#e_country_slug").css('border', 'solid 0px white');
                    $.getJSON(
                        "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=country_slug",
                        {"slug" : $("#e_country_slug").attr("value")},
                        function(data){
                            if(data.error==1) {
                                $("#e_country_slug").css('border', 'solid 3px red');
                            }
                        }
                    );
                    return false;
                });

                $("#e_region_slug").on("keyup", function() {
                    $("#e_region_slug").css('border', 'solid 0px white');
                    $.getJSON(
                        "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=region_slug",
                        {"slug" : $("#e_region_slug").attr("value")},
                        function(data){
                            if(data.error==1) {
                                $("#e_region_slug").css('border', 'solid 3px red');
                            }
                        }
                    );
                    return false;
                });

                $("#e_city_slug").on("keyup", function() {
                    $("#e_city_slug").css('border', 'solid 0px white');
                    $.getJSON(
                        "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=city_slug",
                        {"slug" : $("#e_city_slug").attr("value")},
                        function(data){
                            if(data.error==1) {
                                $("#e_city_slug").css('border', 'solid 3px red');
                            }
                        }
                    );
                    return false;
                });

            });

            var base_url           = '<?php echo osc_admin_base_url(); ?>';
            var s_close            = '<?php echo osc_esc_js(__('Close')); ?>';
            var s_view_more        = '<?php echo osc_esc_js(__('View more')); ?>';
            var addText            = '<?php echo osc_esc_js(__('Add')); ?>';
            var cancelText         = '<?php echo osc_esc_js(__('Cancel')); ?>';
            var editText           = '<?php echo osc_esc_js(__('Edit')); ?>';
            var editNewCountryText = '<?php echo osc_esc_js(__('Edit country')); ?>';
            var addNewCountryText  = '<?php echo osc_esc_js(__('Add new country')); ?>';
            var editNewRegionText  = '<?php echo osc_esc_js(__('Edit region')); ?>';
            var addNewRegionText   = '<?php echo osc_esc_js(__('Add new region')); ?>';
            var editNewCityText    = '<?php echo osc_esc_js(__('Edit city')); ?>';
            var addNewCityText     = '<?php echo osc_esc_js(__('Add new city')); ?>';

            // dialog delete function
            function delete_dialog(item_id, item_type) {
                $("#dialog-location-delete input[name='type']").attr('value', item_type);
                $("#dialog-location-delete input[name='id[]']").attr('value', item_id);
                $("#dialog-location-delete").dialog('open');
                return false;
            }
        </script>
        <?php
    }
    osc_add_hook('admin_header','customHead', 10);

    function addHelp() {
        echo '<p>' . __("Add, edit or delete the countries, regions and cities installed on your Osclass. <strong>Be careful</strong>: modifying locations can cause your statistics to be incorrect until they're recalculated. Modify only if you're sure what you're doing!") . '</p>';
    }
    osc_add_hook('help_box','addHelp');

    osc_add_hook('admin_page_header','customPageHeader');
    function customPageHeader(){ ?>
        <h1><?php _e('Settings'); ?>
            <a href="#" class="btn ico ico-32 ico-help float-right"></a>
        </h1>
<?php
    }

    function customPageTitle($string) {
        return sprintf(__('Locations &raquo; %s'), $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');
    osc_current_admin_theme_path('parts/header.php'); ?>
<!-- container -->
<h1 class="render-title"><?php _e('Locations'); ?></h1>
<?php osc_show_flash_message('admin'); ?>
        </div>
    </div>
<!-- grid close -->
<!-- /settings form -->
<div id="d_add_country" class="lightbox_country location has-form-actions hide">
        <form action="<?php echo osc_admin_base_url(true); ?>" method="post" accept-charset="utf-8" id="d_add_country_form">
            <div><small id="c_code_error" class="hide"><?php _e('Country code should have two characters'); ?></small></div>
            <input type="hidden" name="page" value="settings" />
            <input type="hidden" name="action" value="locations" />
            <input type="hidden" name="type" value="add_country" />
            <input type="hidden" name="c_manual" value="1" />
            <p>
                <label><?php _e('Country'); ?>: </label><br />
                <input type="text" id="country" name="country" value="" />
            </p>
            <p>
                <label><?php _e('Country code'); ?>: </label><br />
                <input type="text" id="c_country" name="c_country" value="" /><br />
            </p>
            <div class="form-actions">
                <div class="wrapper">
                    <button class="btn btn-red close-dialog" ><?php _e('Cancel'); ?></button>
                    <button type="submit" class="btn btn-submit" ><?php _e('Add country'); ?></button>
                </div>
            </div>
        </form>
</div>
<!-- End form add country -->
<!-- Form edit country -->
<div id="d_edit_country" class="lightbox_country location has-form-actions hide">
        <form action="<?php echo osc_admin_base_url(true); ?>" method="post" accept-charset="utf-8" id="d_edit_country_form">
            <input type="hidden" name="page" value="settings" />
            <input type="hidden" name="action" value="locations" />
            <input type="hidden" name="type" value="edit_country" />
            <input type="hidden" name="country_code" value="" />
            <p>
                <label><?php _e('Country'); ?>: </label><br />
                <input type="text" id="e_country" name="e_country" value="" />
            </p>
            <p>
                <label><?php _e('Slug'); ?>: </label><br />
                <input type="text" id="e_country_slug" name="e_country_slug" value="" /><br />
                <div class="help-box">
                    <?php _e('The slug has to be a unique string, could be left blank'); ?>
                </div>
            </p>
            <div class="form-actions">
                <div class="wrapper">
                    <button class="btn btn-red close-dialog" ><?php _e('Cancel'); ?></button>
                    <button type="submit" class="btn btn-submit" ><?php _e('Edit country'); ?></button>
                </div>
            </div>
        </form>
</div>
<!-- End form edit country -->
<!-- Form add region -->
<div id="d_add_region" class="lightbox_country location has-form-actions hide">
    <div style="padding: 14px;">
        <form action="<?php echo osc_admin_base_url(true); ?>" method="post" accept-charset="utf-8" id="d_add_region_form">
            <input type="hidden" name="page" value="settings" />
            <input type="hidden" name="action" value="locations" />
            <input type="hidden" name="type" value="add_region" />
            <input type="hidden" name="country_c_parent" value="" />
            <input type="hidden" name="country_parent" value="" />
            <input type="hidden" name="r_manual" value="1" />
            <input type="hidden" name="region_id" id="region_id" value="" />
            <table>
                <tr>
                    <td><?php _e('Region'); ?>: </td>
                    <td><input type="text" id="region" name="region" value="" /></td>
                </tr>
            </table>
            <div class="form-actions">
                <div class="wrapper">
                    <button class="btn btn-red close-dialog" ><?php _e('Cancel'); ?></button>
                    <button type="submit" class="btn btn-submit" ><?php _e('Add region'); ?></button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- End form add region -->
<!-- Form edit region -->
<div id="d_edit_region" class="lightbox_country location has-form-actions hide">
    <div style="padding: 14px;">
        <form action="<?php echo osc_admin_base_url(true); ?>" method="post" accept-charset="utf-8" id="d_edit_region_form">
            <input type="hidden" name="page" value="settings" />
            <input type="hidden" name="action" value="locations" />
            <input type="hidden" name="type" value="edit_region" />
            <input type="hidden" name="region_id" value="" />
            <p>
                <label><?php _e('Region'); ?>: </label><br />
                <input type="text" id="e_region" name="e_region" value="" />
            </p>
            <p>
                <label><?php _e('Slug'); ?>: </label><br />
                <input type="text" id="e_region_slug" name="e_region_slug" value="" /><br />
                <div class="help-box">
                    <?php _e('The slug has to be a unique string, could be left blank'); ?>
                </div>
            </p>
            <div class="form-actions">
                <div class="wrapper">
                    <button class="btn btn-red close-dialog" ><?php _e('Cancel'); ?></button>
                    <button type="submit" class="btn btn-submit" ><?php _e('Edit region'); ?></button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- End form edit region -->
<!-- Form edit city -->
<div id="d_add_city" class="lightbox_country location has-form-actions hide">
    <div style="padding: 14px;">
        <form action="<?php echo osc_admin_base_url(true); ?>" method="post" accept-charset="utf-8" id="d_add_city_form">
            <input type="hidden" name="page" value="settings" />
            <input type="hidden" name="action" value="locations" />
            <input type="hidden" name="type" value="add_city" />
            <input type="hidden" name="country_c_parent" value="" />
            <input type="hidden" name="country_parent" value="" />
            <input type="hidden" name="region_parent" value="" />
            <input type="hidden" name="ci_manual" value="1" />
            <input type="hidden" name="city_id" id="city_id" value="" />
            <table>
                <tr>
                    <td><?php _e('City'); ?>: </td>
                    <td><input type="text" id="city" name="city" value="" /></td>
                </tr>
            </table>
            <div class="form-actions">
                <div class="wrapper">
                    <button class="btn btn-red close-dialog" ><?php _e('Cancel'); ?></button>
                    <button type="submit" class="btn btn-submit" ><?php _e('Add city'); ?></button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- End form add city -->
<!-- Form edit city -->
<div id="d_edit_city" class="lightbox_country location has-form-actions hide">
    <div style="padding: 14px;">
        <form action="<?php echo osc_admin_base_url(true); ?>" method="post" accept-charset="utf-8" id="d_edit_city_form">
            <input type="hidden" name="page" value="settings" />
            <input type="hidden" name="action" value="locations" />
            <input type="hidden" name="type" value="edit_city" />
            <input type="hidden" name="city_id" value="" />
            <p>
                <label><?php _e('City'); ?>: </label><br />
                <input type="text" id="e_city" name="e_city" value="" />
            </p>
            <p>
                <label><?php _e('Slug'); ?>: </label><br />
                <input type="text" id="e_city_slug" name="e_city_slug" value="" /><br />
                <div class="help-box">
                    <?php _e('The slug has to be a unique string, could be left blank'); ?>
                </div>
            </p>
            <div class="form-actions">
                <div class="wrapper">
                    <button class="btn btn-red close-dialog" ><?php _e('Cancel'); ?></button>
                    <button type="submit" class="btn btn-submit" ><?php _e('Edit city'); ?></button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- settings form -->
<div id="settings_form" class="locations">
<div class="grid-system">
    <div class="grid-row grid-first-row grid-33">
        <div class="row-wrapper">
            <div class="widget-box">
                <div class="widget-box-title"><h3><?php _e('Countries'); ?> <a id="b_new_country" class="btn float-right" href="javascript:void(0);"><?php _e('Add new'); ?></a> <a id="b_remove_country" style="display:none;" class="btn float-right" href="javascript:void(0);"><?php _e('Remove selected'); ?></a></h3></div>
                <div class="widget-box-content">
                    <div id="l_countries">
                        <?php foreach( $aCountries as $country ) { ?>
                        <div>
                            <div class="float-left">
                                <div class="trc">
                                    <span class="checkboxc" style="visibility:hidden;">
                                        <input type="checkbox" name="country[]" value="<?php echo $country['pk_c_code']; ?>" >
                                    </span>
                                    <a class="close" onclick="return delete_dialog('<?php echo $country['pk_c_code']; ?>', 'delete_country');" href="<?php echo osc_admin_base_url(true); ?>?page=settings&action=locations&type=delete_country&id[]=<?php echo $country['pk_c_code']; ?>">
                                        <img src="<?php echo osc_admin_base_url(); ?>images/close.png" alt="<?php echo osc_esc_html(__('Close')); ?>" title="<?php echo osc_esc_html(__('Close')); ?>" />
                                    </a>
                                    <a class="edit" href="javascript:void(0);" style="padding-right: 15px;" onclick="edit_countries($(this));" data="<?php echo osc_esc_html($country['s_name']);?>" code="<?php echo $country['pk_c_code'];?>" slug="<?php echo $country['s_slug'];?>"><?php echo $country['s_name']; ?></a>
                                </div>
                            </div>
                            <div class="float-right">
                                <a class="view-more" href="javascript:void(0)" onclick="show_region('<?php echo osc_esc_js($country['pk_c_code']); ?>', '<?php echo osc_esc_js($country['s_name']); ?>')"><?php _e('View more'); ?> &raquo;</a>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="grid-row grid-first-row grid-33">
        <div class="row-wrapper">
            <div class="widget-box">
                <div class="widget-box-title"><h3><?php _e('Regions'); ?><a id="b_new_region" href="javascript:void(0);" class="btn float-right hide"><?php _e('Add new'); ?></a> <a id="b_remove_region" style="display:none;" class="btn float-right" href="javascript:void(0);"><?php _e('Remove selected'); ?></a></h3></div>
                <div class="widget-box-content">
                    <div id="i_regions"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="grid-row grid-first-row grid-33">
        <div class="row-wrapper">
            <div class="widget-box">
                <div class="widget-box-title"><h3><?php _e('Cities'); ?><a id="b_new_city" href="javascript:void(0);" class="btn float-right hide"><?php _e('Add new'); ?></a> <a id="b_remove_city" style="display:none;" class="btn float-right" href="javascript:void(0);"><?php _e('Remove selected'); ?></a></h3></div>
                <div class="widget-box-content"><div id="i_cities"></div></div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
    <form id="dialog-location-delete" method="get" action="<?php echo osc_admin_base_url(true); ?>" class="has-form-actions hide">
        <input type="hidden" name="page" value="settings" />
        <input type="hidden" name="action" value="locations" />
        <input type="hidden" name="type" value="" />
        <input type="hidden" name="id[]" value="" />
        <div class="form-horizontal">
            <div class="form-row">
                <?php _e("This action can't be undone. Items associated to this location will be deleted. Users from this location will be unlinked, but not deleted. Are you sure you want to continue?");?>
            </div>
            <div class="form-actions">
                <div class="wrapper">
                <a class="btn" href="javascript:void(0);" onclick="$('#dialog-location-delete').dialog('close');"><?php _e('Cancel'); ?></a>
                <input id="location-delete-submit" type="submit" value="<?php echo osc_esc_html( __('Delete') ); ?>" class="btn btn-red" />
                </div>
            </div>
        </div>
    </form>
    <script type="text/javascript">
    <?php if(Params::getParam('country')!='' && Params::getParam('country_code')!='') { ?>
        show_region('<?php echo osc_esc_js(Params::getParam('country_code')); ?>', '<?php echo osc_esc_js(Params::getParam('country')); ?>');
        function hook_load_cities() {
            <?php if(Params::getParam('region')!='') { ?>
            show_city(<?php echo osc_esc_js(Params::getParam('region')); ?>);
            hook_load_cities = function() { };
            <?php }; ?>
        };
    <?php } else {
        echo 'function hook_load_cities() { };';
    }; ?>
    </script>
<?php osc_current_admin_theme_path('parts/footer.php'); ?>