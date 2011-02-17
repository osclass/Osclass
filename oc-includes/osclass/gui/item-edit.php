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
<?php
    //getting variables for this view
    $categories = $this->_get("categories");
    $currencies = $this->_get("currencies");
    $countries  = $this->_get("countries");
    $locales    = $this->_get("locales") ;
    $regions    = $this->_get("regions");
    $cities     = $this->_get("cities");
    $user       = $this->_get("user") ;
    $item       = $this->_get("item") ;

    $this->add_css('tabs.css');
    $this->add_global_js('tabber-minimized.js')
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <?php $this->osc_print_head() ; ?>
    </head>
    <body>
        <div class="container">
            <?php $this->osc_print_header() ; ?>
            <?php ItemForm::location_javascript(); ?>

            <div class="content add_item">
                <h1><strong><?php _e('Update your item'); ?></strong></h1>

                    <form action="<?php echo osc_base_url(true)?>" method="post" enctype="multipart/form-data">
                    <fieldset>
                        <input type="hidden" name="action" value="item_edit_post" />
                        <input type="hidden" name="page" value="item" />
                        <input type="hidden" name="id" value="<?php echo $item['pk_i_id'];?>" />
                        <input type="hidden" name="secret" value="<?php echo $item['s_secret'];?>" />
                        <input type="hidden" name="fk_location_id" value="<?php echo $item['fk_i_user_location_id'];?>" />

                        <div class="left_column">
                            <div class="box general_info">
                                <h2><?php _e('General Information'); ?></h2>
                                <div class="row">
                                    <label><?php _e('Category'); ?></label>
                                    <?php ItemForm::category_select($categories, $item); ?>
                                </div>
                                <div class="row">
                                    <?php ItemForm::multilanguage_title_description($locales, $item); ?>
                                </div>
                                <div class="row price">
                                    <label><?php _e('Price'); ?></label>
                                    <?php ItemForm::price_input_text($item); ?>
                                    <?php ItemForm::currency_select($currencies,$item); ?>
                                </div>
                            </div>

                            <div class="box photos">
                                <?php ItemForm::photos_javascript($item); ?>
                                <h2><?php _e('Photos'); ?></h2>
                                <?php ItemForm::photos($resources); ?>
                                <div id="photos">
                                    <div class="row">
                                        <input type="file" name="photos[]" /> (<?php _e('optional'); ?>)
                                    </div>
                                </div>
                                <a href="#" onclick="addNewPhoto(); return false;"><?php _e('Add new photo'); ?></a>
                            </div>
                        </div>

                            <div class="right_column">
                        <div class="box location">
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

                            <?php osc_run_hook('item_edit', $item) ;?>
                            </div>

                    <button class="itemFormButton" type="submit"><?php _e('Update'); ?></button>
                    <a href="javascript:history.back(-1)" class="go_back"><?php _e('Cancel'); ?></a>
                    </fieldset>
                    </form>
            </div>
                    </div>
        <?php $this->osc_print_footer() ; ?>

    </body>

</html>