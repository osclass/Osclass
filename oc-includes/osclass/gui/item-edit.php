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
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <?php osc_current_web_theme_path('head.php') ; ?>
    </head>
    <body>
        <div class="container">
            <?php osc_current_web_theme_path('header.php') ; ?>
            <?php ItemForm::location_javascript(); ?>
            <div class="content add_item">
                <h1><strong><?php _e('Update your item', 'gui'); ?></strong></h1>
                    <form action="<?php echo osc_base_url(true)?>" method="post" enctype="multipart/form-data">
                    <fieldset>
                        <input type="hidden" name="action" value="item_edit_post" />
                        <input type="hidden" name="page" value="item" />
                        <input type="hidden" name="id" value="<?php echo osc_item_id() ;?>" />
                        <input type="hidden" name="secret" value="<?php echo osc_item_secret() ;?>" />
                        <div class="left_column">
                            <div class="box general_info">
                                <h2><?php _e('General Information', 'gui'); ?></h2>
                                <div class="row">
                                    <label><?php _e('Category', 'gui'); ?></label>
                                    <?php ItemForm::category_select(); ?>
                                </div>
                                <div class="row">
                                    <?php ItemForm::multilanguage_title_description(osc_get_locales(), osc_item()); ?>
                                </div>
                                <div class="row price">
                                    <label><?php _e('Price', 'gui'); ?></label>
                                    <?php ItemForm::price_input_text(); ?>
                                    <?php ItemForm::currency_select(); ?>
                                </div>
                            </div>

                            <div class="box photos">
                                <?php ItemForm::photos_javascript(); ?>
                                <h2><?php _e('Photos', 'gui'); ?></h2>
                                <?php ItemForm::photos(); ?>
                                <div id="photos">
                                    <div class="row">
                                        <input type="file" name="photos[]" /> (<?php _e('optional', 'gui'); ?>)
                                    </div>
                                </div>
                                <a href="#" onclick="addNewPhoto(); return false;"><?php _e('Add new photo', 'gui'); ?></a>
                            </div>
                        </div>

                        <div class="right_column">
                            <div class="box location">
                                <h2><?php _e('Location', 'gui'); ?></h2>
                                <div class="row">
                                    <label><?php _e('Country', 'gui'); ?></label>
                                    <?php ItemForm::country_select() ; ?>
                                </div>
                                <div class="row">
                                    <label><?php _e('Region', 'gui'); ?></label>
                                    <?php ItemForm::region_select() ; ?>
                                </div>
                                <div class="row">
                                    <label><?php _e('City', 'gui'); ?></label>
                                    <?php ItemForm::city_select() ; ?>
                                </div>
                                <div class="row">
                                    <label><?php _e('City area', 'gui'); ?></label>
                                    <?php ItemForm::city_area_text() ; ?>
                                </div>
                                <div class="row">
                                    <label><?php _e('Address', 'gui'); ?></label>
                                    <?php ItemForm::address_text() ; ?>
                                </div>
                            </div>
                            <?php osc_run_hook('item_edit', osc_item() ) ;?>
                        </div>
                        <button class="itemFormButton" type="submit"><?php _e('Update', 'gui'); ?></button>
                        <a href="javascript:history.back(-1)" class="go_back"><?php _e('Cancel', 'gui'); ?></a>
                    </fieldset>
                </form>
            </div>
            <?php osc_current_web_theme_path('footer.php') ; ?>
        </div>
        <?php osc_show_flash_message() ; ?>
    </body>
</html>