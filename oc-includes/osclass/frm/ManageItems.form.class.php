<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

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

    class ManageItemsForm extends Form {

        // OK
        static public function category_select($categories = null, $item = null, $default_item = null, $parent_selectable = false)
        {
            // Did user select a specific category to post in?
            $catId = Params::getParam('catId');

            if($categories == null) {
                if(View::newInstance()->_exists('categories')) {
                    $categories = View::newInstance()->_get('categories');
                } else {
                    $categories = osc_get_categories();
                }
            }

            echo '<select name="catId" id="catId">';
            if(isset($default_item)) {
                echo '<option value="">' . $default_item . '</option>';
            } else {
                echo '<option value="">' . __('Select a category') . '</option>';
            }

            if(count($categories)==1) { $parent_selectable = 1; };

            foreach($categories as $c) {
                if ( !osc_selectable_parent_categories() && !$parent_selectable ) {
                    echo '<optgroup label="' . $c['s_name'] . '">';
                    if(isset($c['categories']) && is_array($c['categories'])) {
                        ManageItemsForm::subcategory_select($c['categories'], $item, $default_item, 1);
                    }
                } else {
                    $selected = ( (isset($item["fk_i_category_id"]) && $item["fk_i_category_id"] == $c['pk_i_id']) || (isset($catId) && $catId == $c['pk_i_id']) );
                    echo '<option value="' . $c['pk_i_id'] . '"' . ($selected ? 'selected="selected"' : '' ). '>' . $c['s_name'] . '</option>';
                    if(isset($c['categories']) && is_array($c['categories'])) {
                        ManageItemsForm::subcategory_select($c['categories'], $item, $default_item, 1);
                    }
                }
            }
            echo '</select>';
            return true;
        }

        // OK
        static public function subcategory_select($categories, $item, $default_item = null, $deep = 0)
        {
            // Did user select a specific category to post in?
            $catId = Params::getParam('catId');
            // How many indents to add?
            $deep_string = "";
            for($var = 0;$var<$deep;$var++) {
                $deep_string .= '&nbsp;&nbsp;';
            }
            $deep++;

            foreach($categories as $c) {
                $selected = ( (isset($item["fk_i_category_id"]) && $item["fk_i_category_id"] == $c['pk_i_id']) || (isset($catId) && $catId == $c['pk_i_id']) );

                echo '<option value="' . $c['pk_i_id'] . '"' . ($selected ? 'selected="selected'.$item["fk_i_category_id"].'"' : '') . '>' . $deep_string . $c['s_name'] . '</option>';
                if(isset($c['categories']) && is_array($c['categories'])) {
                    ManageItemsForm::subcategory_select($c['categories'], $item, $default_item, $deep);
                }
            }
        }

        static public function country_text()
        {
            // get params GET (only manageItems)
            if(Params::getParam('countryName') != '') {
                $item['s_country'] = Params::getParam('countryName');
                $item['fk_c_country_code'] = Params::getParam('countryId');
            }

            parent::generic_input_text('countryName', (isset($item['s_country'])) ? $item['s_country'] : null, false, false);
            parent::generic_input_hidden('countryId', (isset($item['fk_c_country_code']) && $item['fk_c_country_code']!=null)?$item['fk_c_country_code']:'');
            return true;
        }

        static public function region_text()
        {
            // get params GET (only manageItems)
            if(Params::getParam('region') != '') {
                $item['s_region'] = Params::getParam('region');
                $item['fk_i_region_id'] = Params::getParam('regionId');
            }
            parent::generic_input_text('region', (isset($item['s_region'])) ? $item['s_region'] : null, false, false);
            parent::generic_input_hidden('regionId', (isset($item['fk_i_region_id']) && $item['fk_i_region_id']!=null)?$item['fk_i_region_id']:'');
            return true;
        }

        static public function city_text()
        {
            // get params GET (only manageItems)
            if(Params::getParam('city') != '') {
                $item['s_city'] = Params::getParam('city');
                $item['fk_i_city_id'] = Params::getParam('cityId');
            }
            parent::generic_input_text('city', (isset($item['s_city'])) ? $item['s_city'] : null, false, false);
            parent::generic_input_hidden('cityId', (isset($item['fk_i_city_id']) && $item['fk_i_city_id']!=null)?$item['fk_i_city_id']:'');
            return true;
        }
    }
?>