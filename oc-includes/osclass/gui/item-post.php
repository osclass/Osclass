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
                <h1><strong><?php _e('Post your item'); ?></strong></h1>
                <form action="<?php echo osc_base_url(true);?>" method="post" enctype="multipart/form-data" onSubmit="return checkForm()">
                    <fieldset>
                    <input type="hidden" name="action" value="post_item" />
                    <input type="hidden" name="page" value="item" />

                    <!-- left -->
                    <div class="left_column">
                        <div class="box general_info">
                            <h2><?php _e('General Information'); ?></h2>
                            <div class="row">
                                <label for="catId"><?php _e('Category'); ?></label>
                                <?php ItemForm::category_select($categories, $item['fk_i_category_id'] = Params::getParam('catId') ); ?>
                            </div>

                            <div class="box">
                                <?php ItemForm::multilanguage_title_description($locales); ?>
                            </div>

                            <div class="row price">
                                <label for="price"><?php _e('Price'); ?></label>
                                <?php ItemForm::price_input_text(); ?>
                                <?php ItemForm::currency_select($currencies); ?>
                            </div>
                        </div>

                        <div class="box photos">
                            <?php ItemForm::photos_javascript(); ?>
                            <h2><?php _e('Photos'); ?></h2>
                            <div id="photos">
                                <div class="row">
                                    <input type="file" name="photos[]" /> (<?php _e('optional'); ?>)
                                </div>
                            </div>
                            <a href="#" onclick="addNewPhoto(); return false;"><?php _e('Add new photo'); ?></a>
                        </div>


                    </div>

                        


                    <!-- right -->
                    <div class="right_column">

                        <div class="box location">
                            <h2><?php _e('Item Location'); ?></h2>

                            <div class="row">
                                <label for="countryId"><?php _e('Country'); ?></label>
                                <?php ItemForm::country_select($countries, $user) ; ?>
                            </div>
                            <div class="row">
                                <label for="regionId"><?php _e('Region'); ?></label>
                                <?php ItemForm::region_select($regions, $user) ; ?>
                            </div>
                            <div class="row">
                                <label for="city"><?php _e('City'); ?></label>
                                <?php ItemForm::city_select($cities, $user) ; ?>
                            </div>
                            <div class="row">
                                <label for="city"><?php _e('City Area'); ?></label>
                                <?php ItemForm::city_area_text($user) ; ?>
                            </div>
                            <div class="row">
                                <label for="address"><?php _e('Address'); ?></label>
                                <?php ItemForm::address_text($user) ; ?>
                            </div>
                        </div>
                        <!-- seller info -->
                        <?php if(!osc_is_web_user_logged_in() ) { ?>
                        <div class="box seller_info">
                            <h2><?php _e('Seller information'); ?></h2>
                            <div class="row">
                                <label for="contactName"><?php _e('Name'); ?></label>
                                <?php ItemForm::contact_name_text() ; ?>
                            </div>
                            <div class="row">
                                <label for="contactEmail"><?php _e('E-mail'); ?></label>
                                <?php ItemForm::contact_email_text() ; ?>
                            </div>
                            <div class="row">
                                <div style="width: 120px;text-align: right;float:left;">
                                    <?php ItemForm::show_email_checkbox() ; ?>
                                </div>
                                <label for="showEmail" style="width: 250px;float:right;"><?php _e('Show email publically within the item page'); ?></label>
                            </div>
                        </div>
                        <?php }; ?>
                        <?php ItemForm::plugin_post_item($categories); ?>
                    </div>
                    <div class="clear"></div>
                    <button  type="submit"><?php _e('Publish'); ?></button>
                    </fieldset>
             </form>
            </div>
        </div>
        <?php $this->osc_print_footer() ; ?>

    </body>

</html>