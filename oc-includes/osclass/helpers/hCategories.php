<?php
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


    /**
    * Helper Categories
    * @package Osclass
    * @subpackage Helpers
    * @author Osclass
    */

    /**
     * Gets current category
     *
     * @return array
     */
    function osc_category() {
        if (View::newInstance()->_exists('subcategories')) {
            $category = View::newInstance()->_current('subcategories');
        } elseif (View::newInstance()->_exists('categories')) {
            $category = View::newInstance()->_current('categories');
        } elseif (View::newInstance()->_exists('category')) {
            $category = View::newInstance()->_get('category');
        } else {
            $category = null;
        }
        return($category);
    }

    /**
     * Low level function: Gets the list of categories as a tree
     *
     * <code>
     * <?php
     *  $c = osc_get_categories();
     * ?>
     * </code>
     *
     * @return <array>
     */
    function osc_get_categories() {
       if ( !View::newInstance()->_exists('categories') ) {
           osc_export_categories(Category::newInstance()->toTree());
        }
        return  View::newInstance()->_get('categories');
    }

    /**
     * Low level function: Gets the value of the category attribute
     *
     * @return <array>
     */
    function osc_category_field($field, $locale = '') {
        return osc_field(osc_category(), $field, "");
    }

    /**
     * Gets the number of categories
     *
     * @return int
     */
    function osc_priv_count_categories() {
        return View::newInstance()->_count('categories');
    }

    /**
     * Gets the number of subcategories
     *
     * @return int
     */
    function osc_priv_count_subcategories() {
        return View::newInstance()->_count('subcategories');
    }

    /**
     * Gets the total of categories. If categories are not loaded, this function will load them.
     *
     * @return int
     */
    function osc_count_categories() {
        if ( !View::newInstance()->_exists('categories') ) {
            View::newInstance()->_exportVariableToView('categories', Category::newInstance()->toTree() );
        }
        return osc_priv_count_categories();
    }

    /**
     * Let you know if there are more categories in the list. If categories are not loaded, this function will load them.
     *
     * @return boolean
     */
    function osc_has_categories() {
        if ( !View::newInstance()->_exists('categories') ) {
            View::newInstance()->_exportVariableToView('categories', Category::newInstance()->toTree() );
        }
        return View::newInstance()->_next('categories');
    }

    /**
     * Gets the total of subcategories for the current category. If subcategories are not loaded, this function will load them and
     * it will prepare the the pointer to the first element
     *
     * @return int
     */
    function osc_count_subcategories() {
        $category = View::newInstance()->_current('categories');
        if ( $category == '' ) return -1;
        if ( !isset($category['categories']) ) return 0;
        if ( !is_array($category['categories']) ) return 0;
        if ( count($category['categories']) == 0 ) return 0;
        if ( !View::newInstance()->_exists('subcategories') ) {
            View::newInstance()->_exportVariableToView('subcategories', $category['categories']);
        }
        return osc_priv_count_subcategories();
    }

    /**
     * Let you know if there are more subcategories for the current category in the list. If subcategories are not loaded, this
     * function will load them and it will prepare the pointer to the first element
     *
     * @return boolean
     */
    function osc_has_subcategories() {
        $category = View::newInstance()->_current('categories');
        if ( $category == '' ) return -1;
        if ( !isset($category['categories']) ) return false;

        if ( !View::newInstance()->_exists('subcategories') ) {
            View::newInstance()->_exportVariableToView('subcategories', $category['categories']);
        }
        $ret = View::newInstance()->_next('subcategories');
        //we have to delete for next iteration
        if (!$ret) View::newInstance()->_erase('subcategories');
        return $ret;
    }

    /**
     * Gets the name of the current category
     *
     * @param string $locale
     * @return string
     */
    function osc_category_name($locale = "") {
        if ($locale == "") $locale = osc_current_user_locale();
        return osc_category_field("s_name", $locale);
    }

    /**
     * Gets the description of the current category
     *
     * @param string $locale
     * @return string
     */
    function osc_category_description($locale = "") {
        if ($locale == "") $locale = osc_current_user_locale();
        return osc_category_field("s_description", $locale);
    }

    /**
     * Gets the id of the current category
     *
     * @param string $locale
     * @return string
     */
    function osc_category_id($locale = "") {
        if ($locale == "") $locale = osc_current_user_locale();
        return osc_category_field("pk_i_id", $locale);
    }

    /**
     * Gets the slug of the current category. WARNING: This slug could NOT be used as a valid W3C HTML tag attribute as it could have other characters besides [A-Za-z0-9-_] We only did a urlencode to the variable
     *
     * @param string $locale
     * @return string
     */
    function osc_category_slug($locale = "") {
        if ($locale == "") $locale = osc_current_user_locale();
        return osc_category_field("s_slug", $locale);
    }

    /**
     * Returns if the category has the prices enabled or not
     *
     * @return boolean
     */
    function osc_category_price_enabled() {
        return (boolean)osc_category_field("b_price_enabled");
    }

    /**
     * Returns category's parent id
     *
     * @return int
     */
    function osc_category_parent_id() {
        return osc_category_field("fk_i_parent_id");
    }

    /**
     * Gets the total items related with the current category
     *
     * @return int
     */
    function osc_category_total_items() {
        return osc_category_field("i_num_items", "");
        //$category = osc_category();
        //return CategoryStats::newInstance()->getNumItems($category);
    }

    /**
     * Reset the pointer of the array to the first category
     *
     * @return void
     */
    function osc_goto_first_category() {
        View::newInstance()->_reset('categories');
    }

    /**
     * Gets list of non-empty categories
     *
     * @return void
     */
    function osc_get_non_empty_categories() {
        $aCategories = Category::newInstance()->toTree(false);
        View::newInstance()->_exportVariableToView('categories', $aCategories );
        return  View::newInstance()->_get('categories');
    }

    /**
     * Prints category select
     *
     * @return void
     */
    function osc_categories_select($name = 'sCategory', $category = null, $default_str = null) {
        if($default_str == null) $default_str = __('Select a category');
        CategoryForm::category_select(Category::newInstance()->toTree(), $category, $default_str, $name);
    }

    /**
     * Get th category by id or slug
     *
     * @since 3.0
     * @param $by two possibilities: slug or id
     * @param $what the id or slug category we're looking for
     * @return array
     */
    function osc_get_category($by, $what) {
        if( !in_array($by, array('slug', 'id')) ) {
            return false;
        }

        switch ($by) {
            case 'slug':
                return Category::newInstance()->findBySlug($what);
            break;
            case 'id':
                return Category::newInstance()->findByPrimaryKey($what);
            break;
        }
    }

    function osc_category_move_to_children() {
        $category = View::newInstance()->_current('categories');
        if ( $category == '' ) return -1;
        if ( !isset($category['categories']) ) return false;

        if(View::newInstance()->_exists('categoryTrail')) {
            $catTrail = View::newInstance()->_get('categoryTrail');
        } else {
            $catTrail = array();
        }
        $catTrail[] = View::newInstance()->_key('categories');
        View::newInstance()->_exportVariableToView('categoryTrail', $catTrail);
        View::newInstance()->_exportVariableToView('categories', $category['categories']);
        View::newInstance()->_reset('categories');
    }

    function osc_category_move_to_parent() {
        $category = View::newInstance()->_get('categories');
        $category = end($category);

        if ( $category == '' ) return -1;
        if ( !isset($category['fk_i_parent_id']) ) return false;

        $keys = View::newInstance()->_get('categoryTrail');
        $position = array_pop($keys);
        View::newInstance()->_exportVariableToView('categoryTrail', $keys);
        if(!View::newInstance()->_exists('categories_tree')) {
            View::newInstance()->_exportVariableToView('categories_tree', Category::newInstance()->toTree());
        }
        $scats['categories'] = Category::newInstance()->toTree();
        if(count($keys)>0) {
            foreach($keys as $k) {
                $scats = $scats['categories'][$k];
            }
        }

        $scats = $scats['categories'];

        View::newInstance()->_erase('categories');
        View::newInstance()->_erase('subcategories');
        View::newInstance()->_exportVariableToView('categories', $scats);
        View::newInstance()->_seek('categories', $position);
    }

    /**
     * Gets the total of subcategories for the current category. If subcategories are not loaded, this function will load them and
     * it will prepare the the pointer to the first element
     *
     * @return int
     */
    function osc_count_subcategories2() {
        $category = View::newInstance()->_current('categories');
        if ( $category == '' ) return -1;
        if ( !isset($category['categories']) ) return 0;
        if ( !is_array($category['categories']) ) return 0;
        return count($category['categories']);
    }

    function osc_export_categories($categories = null) {
        if($categories==null) {
            $categories = Category::newInstance()->toTree();
        }
        View::newInstance()->_exportVariableToView('categories', $categories);
        View::newInstance()->_exportVariableToView('categories_tree', $categories);
    }


?>