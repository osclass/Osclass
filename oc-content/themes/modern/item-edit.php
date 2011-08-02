<?php
    /*
     *      OSCLass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2010 OSCLASS
     *
     *       This program is free software: you can redistribute it and/or
     *     modify it under the terms of the GNU Affero General Public License
     *     as published by the Free Software Foundation, either version 3 of
     *            the License, or (at your option) any later version.
     *
     *     This program is distributed in the hope that it will be useful, but
     *         WITHOUT ANY WARRANTY; without even the implied warranty of
     *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *             GNU Affero General Public License for more details.
     *
     *      You should have received a copy of the GNU Affero General Public
     * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
     */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
    <head>
        <?php osc_current_web_theme_path('head.php') ; ?>
        <meta name="robots" content="noindex, nofollow" />
        <meta name="googlebot" content="noindex, nofollow" />

        <!-- only item-edit.php -->
        <script type="text/javascript" src="<?php echo osc_current_web_theme_js_url('jquery.validate.min.js') ; ?>"></script>
        <?php ItemForm::location_javascript_new(); ?>
        <?php if(osc_images_enabled_at_items()) ItemForm::photos_javascript(); ?>
        <!-- end only item-edit.php -->
    </head>
    <body>
        <div class="container">
            <?php osc_current_web_theme_path('header.php') ; ?>
            <div class="content add_item">
                <h1><strong><?php _e('Update your item', 'modern'); ?></strong></h1>
                <ul id="error_list"></ul>
                    <form name="item" action="<?php echo osc_base_url(true)?>" method="post" enctype="multipart/form-data">
                    <fieldset>
                        <input type="hidden" name="action" value="item_edit_post" />
                        <input type="hidden" name="page" value="item" />
                        <input type="hidden" name="id" value="<?php echo osc_item_id() ;?>" />
                        <input type="hidden" name="secret" value="<?php echo osc_item_secret() ;?>" />
                            <div class="box general_info">
                                <h2><?php _e('General Information', 'modern'); ?></h2>
                                <div class="row">
                                    <label><?php _e('Category', 'modern'); ?> *</label>
                                    <?php ItemForm::category_select(null, null, __('Select a category', 'modern')); ?>
                                </div>
                                <div class="row">
                                    <?php ItemForm::multilanguage_title_description(osc_get_locales()); ?>
                                </div>
                                <?php if( osc_price_enabled_at_items() ) { ?>
                                <div class="row price">
                                    <label><?php _e('Price', 'modern'); ?></label>
                                    <?php ItemForm::price_input_text(); ?>
                                    <?php ItemForm::currency_select(); ?>
                                </div>
                                <?php } ?>
                            </div>
                            <?php if( osc_images_enabled_at_items() ) { ?>
                            <div class="box photos">
                                <h2><?php _e('Photos', 'modern'); ?></h2>
                                <?php ItemForm::photos(); ?>
                                <div id="photos">
                                    <?php if(osc_max_images_per_item()==0 || (osc_max_images_per_item()!=0 && osc_count_item_resources()<  osc_max_images_per_item())) { ?>
                                    <div class="row">
                                        <input type="file" name="photos[]" />
                                    </div>
                                    <?php }; ?>
                                </div>
                                <a href="#" onclick="addNewPhoto(); return false;"><?php _e('Add new photo', 'modern'); ?></a>
                            </div>
                            <?php } ?>

                            <div class="box location">
                                <h2><?php _e('Location', 'modern'); ?></h2>
                                <div class="row">
                                    <label><?php _e('Country', 'modern'); ?></label>
                                    <?php ItemForm::country_select() ; ?>
                                </div>
                                <div class="row">
                                    <label><?php _e('Region', 'modern'); ?></label>
                                    <?php ItemForm::region_text() ; ?>
                                </div>
                                <div class="row">
                                    <label><?php _e('City', 'modern'); ?></label>
                                    <?php ItemForm::city_text() ; ?>
                                </div>
                                <div class="row">
                                    <label><?php _e('City area', 'modern'); ?></label>
                                    <?php ItemForm::city_area_text() ; ?>
                                </div>
                                <div class="row">
                                    <label><?php _e('Address', 'modern'); ?></label>
                                    <?php ItemForm::address_text() ; ?>
                                </div>
                            </div>
                            <?php ItemForm::plugin_edit_item(); ?>
                            <?php if( osc_recaptcha_items_enabled() ) {?>
                            <div class="box">
                                <div class="row">
                                    <?php osc_show_recaptcha(); ?>
                                </div>
                            </div>
                            <?php }?>
                        <button class="itemFormButton" type="submit"><?php _e('Update', 'modern'); ?></button>
                        <a href="javascript:history.back(-1)" class="go_back"><?php _e('Cancel', 'modern'); ?></a>
                    </fieldset>
                </form>
            </div>
            <?php osc_current_web_theme_path('footer.php') ; ?>
        </div>
        <?php osc_show_flash_message() ; ?>
        <?php osc_run_hook('footer'); ?>
    </body>
</html>