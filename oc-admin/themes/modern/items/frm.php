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

    $new_item = __get("new_item");
    $users = __get("users");
    $categories = __get("categories");
    $countries = __get("countries");
    $regions = __get("regions");
    $cities = __get("cities");
    $currencies = __get("currencies");
    $locales = __get("locales");
    $item = __get("item");
    $resources = __get("resources");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <div id="update_version" style="display:none;"></div>
        <div class="Header"><?php _e("Items");?></div>

        <script type="text/javascript">
            document.write('<style type="text/css">.tabber{display:none;}<\/style>');
            $(document).ready(function(){
                $("#userId").change(function(){
                    if($(this).val()=='') {
                        $("#contact_info").show();
                    } else {
                        $("#contact_info").hide();
                    }
                });
                if($($("#userId")).val()=='') {
                    $("#contact_info").show();
                } else {
                    $("#contact_info").hide();
                }
                $('textarea, button,select, input:file').uniform();
            });
        </script>
        <?php ItemForm::location_javascript('admin'); ?>
        <?php if(osc_images_enabled_at_items()) ItemForm::photos_javascript(); ?>
        <div id="content">
            <div id="separator"></div>

            <?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>

            <div id="right_column">
                <div class="content_header" id="content_header">
                    <div style="float: left;">
                        <img alt="" title="" src="http://localhost/osclass/oc-admin/themes/modern/images/new-folder-icon.png">
                    </div>
                    <div id="content_header_arrow">» <?php if($new_item) { _e('New item'); } else { _e('Edit item'); } ?></div>
                    <div style="clear: both;"></div>
                </div>

                <div id="add_item_form" class="item-form">
                    <ul id="error_list"></ul>
                    <form name="item" action="<?php echo osc_admin_base_url(true); ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="page" value="items" />
                        <?php if($new_item) { ?>
                            <input type="hidden" name="action" value="post_item" />
                        <?php } else { ?>
                            <input type="hidden" name="action" value="item_edit_post" />
                            <input type="hidden" name="id" value="<?php echo $item['pk_i_id']; ?>" />
                            <input type="hidden" name="secret" value="<?php echo $item['s_secret']; ?>" />
                        <?php }; ?>

                        <div class="user-post">
                            <h2><?php _e('User'); ?></h2>
                            <label><?php _e('Item posted by'); ?></label>
                            <?php ItemForm::user_select($users, $item, __('Non-registered user')); ?>
                            <div  id="contact_info">
                                <label for="contactName"><?php _e('Name'); ?></label>
                                <?php ItemForm::contact_name_text($item) ; ?><br/>
                                <label for="contactEmail"><?php _e('E-Mail'); ?></label>
                                <?php ItemForm::contact_email_text($item); ?>
                            </div>
                        </div>

                        <h2>
                            <?php _e('General information'); ?>
                        </h2>
                        <label for="catId">
                            <?php _e('Category') ?>:
                        </label>
                        <?php ItemForm::category_select($categories, $item); ?>

                        <?php ItemForm::multilanguage_title_description($locales, $item); ?>

                        <?php if(osc_price_enabled_at_items()) { ?>
                            <div class="_200 auto">
                                <h2><?php _e('Price'); ?></h2>
                                <?php ItemForm::price_input_text($item); ?>
                                <?php ItemForm::currency_select($currencies, $item); ?>
                            </div>
                        <?php } ?>

                        <?php if(osc_images_enabled_at_items()) { ?>
                            <div>
                                <h2><?php _e('Photos') ; ?></h2>
                                <div id="photos">
                                    <?php foreach($resources as $_r) {?>
                                        <div id="<?php echo $_r['pk_i_id'];?>" fkid="<?php echo $_r['fk_i_item_id'];?>" name="<?php echo $_r['s_name'];?>">
                                            <img src="<?php echo osc_base_url().$_r['s_path'].$_r['pk_i_id']."_thumbnail.".$_r['s_extension']; ?>" /><a onclick="deleteResource(<?php echo $_r['pk_i_id'];?>)" style="cursor:pointer;" class="delete"><?php _e('Delete'); ?></a>
                                        </div>
                                    <?php } ?>
                                    <div>
                                        <input type="file" name="photos[]" /> (<?php _e('optional'); ?>)
                                    </div>
                                </div>
                                <p><a style="font-size: small;" href="#" onclick="addNewPhoto(); return false;"><?php _e('Add new photo') ; ?></a></p>
                            </div>
                        <?php } ?>

                        <div class="location-post _200 clear">
                            <!-- location info -->
                            <h2><?php _e('Location'); ?></h2>
                            <div class="row">
                                <label><?php _e('Country'); ?></label>
                                <?php ItemForm::country_select($countries, $item) ; ?>
                            </div>
                            <div class="row">
                                <label><?php _e('Region'); ?></label>
                                <?php ItemForm::region_select($regions, $item) ; ?>
                            </div>
                            <div class="row">
                                <label><?php _e('City'); ?></label>
                                <?php ItemForm::city_select($cities, $item) ; ?>
                            </div>
                            <div class="row">
                                <label><?php _e('City area'); ?></label>
                                <?php ItemForm::city_area_text($item) ; ?>
                            </div>
                            <div class="row">
                                <label><?php _e('Address'); ?></label>
                                <?php ItemForm::address_text($item) ; ?>
                            </div>
                        </div>

                        <?php if($new_item) {
                                ItemForm::plugin_post_item();
                            } else {
                                ItemForm::plugin_edit_item();
                            };
                        ?>
                        <div class="clear"></div>
                        <div align="center" style="margin-top: 30px; padding: 20px; ">
                            <button type="submit"><?php if($new_item) { _e('Add item'); } else { _e('Update'); } ?></button>
                            <button type="button" onclick="window.location='<?php echo osc_admin_base_url(true);?>?page=items';" ><?php _e('Cancel'); ?></button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>
