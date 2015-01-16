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

    class CategoryForm extends Form
    {
        static public function primary_input_hidden($category)
        {
            parent::generic_input_hidden("id", $category["pk_i_id"]);
        }

        static public function category_select($categories, $category, $default_item = null, $name = "sCategory")
        {
            echo '<select name="' . $name . '" id="' . $name . '">';
            if(isset($default_item)) {
                echo '<option value="">' . $default_item . '</option>';
            }
            foreach($categories as $c) {
                echo '<option value="' . $c['pk_i_id'] . '"' . ( ($category['pk_i_id'] == $c['pk_i_id']) ? 'selected="selected"' : '' ) . '>' . $c['s_name'] . '</option>';
                if(isset($c['categories']) && is_array($c['categories'])) {
                    CategoryForm::subcategory_select($c['categories'], $category, $default_item, 1);
                }
            }
            echo '</select>';
        }

        static public function subcategory_select($categories, $category, $default_item = null, $deep = 0)
        {
            $deep_string = "";
            for($var = 0;$var<$deep;$var++) {
                $deep_string .= '&nbsp;&nbsp;';
            }
            $deep++;
            foreach($categories as $c) {
                echo '<option value="' . $c['pk_i_id'] . '"' . ( ($category['pk_i_id'] == $c['pk_i_id']) ? 'selected="selected"' : '' ) . '>' . $deep_string.$c['s_name'] . '</option>';
                if(isset($c['categories']) && is_array($c['categories'])) {
                    CategoryForm::subcategory_select($c['categories'], $category, $default_item, $deep);
                }
            }
        }

        static public function categories_tree($categories = null, $selected = null, $depth = 0)
        {
            if( ( $categories != null ) && is_array($categories) ) {
                echo '<ul id="cat' . $categories[0]['fk_i_parent_id'] . '">';

                $d_string = '';
                for($var_d = 0; $var_d < $depth; $var_d++) {
                    $d_string .= "&nbsp;&nbsp;&nbsp;&nbsp;";
                }

                foreach($categories as $c) {
                    echo '<li>';
                    echo $d_string . '<input type="checkbox" name="categories[]" value="' . $c['pk_i_id'] . '" onclick="javascript:checkCat(\'' . $c['pk_i_id'] . '\', this.checked);" ' . ( in_array($c['pk_i_id'], $selected) ? 'checked="checked"' : '' ) . ' />' . ( ( $depth == 0 ) ? '<span>' : '' ) . $c['s_name'] . ( ( $depth == 0 ) ? '</span>' : '' );
                    CategoryForm::categories_tree($c['categories'], $selected, $depth + 1);
                    echo '</li>';
                }
                echo '</ul>';
            }
        }

        static public function expiration_days_input_text($category = null)
        {
            parent::generic_input_text("i_expiration_days", (isset($category) && isset($category['i_expiration_days'])) ? $category["i_expiration_days"] : "", 3);
        }

        static public function position_input_text($category = null)
        {
            parent::generic_input_text("i_position", (isset($category) && isset($category['i_position'])) ? $category["i_position"] : "", 3);
        }

        static public function enabled_input_checkbox($category = null)
        {
            parent::generic_input_checkbox("b_enabled", "1", (isset($category) && isset($category['b_enabled']) && $category["b_enabled"] == 1) ? true : false);
        }

        static public function apply_changes_to_subcategories($category = null)
        {
            if($category['fk_i_parent_id']==NULL) {
                parent::generic_input_checkbox("apply_changes_to_subcategories", "1", true);
            }
        }

        static public function price_enabled_for_category($category = null)
        {
			parent::generic_input_checkbox("b_price_enabled", "1", (isset($category) && isset($category['b_price_enabled']) && $category["b_price_enabled"] == 1) ? true : false);
        }

        static public function multilanguage_name_description($locales, $category = null)
        {
            $tabs = array();
            $content = array();
            foreach($locales as $locale) {
                    $value = (isset($category['locale'][$locale['pk_c_code']])) ? $category['locale'][$locale['pk_c_code']]['s_name'] : "";
                    $name = $locale['pk_c_code'] . '#s_name';
                    $nameTextarea = $locale['pk_c_code'] . '#s_description';
                    $valueTextarea = (isset($category['locale'][$locale['pk_c_code']])) ? $category['locale'][$locale['pk_c_code']]['s_description'] : "";

                    $contentTemp  = '<div id="'.$category['pk_i_id'].'-'.$locale['pk_c_code'].'" class="category-details-form">';
                    $contentTemp .= '<div class="FormElement"><label>' . __('Name') . '</label><input id="' . $name .'" type="text" name="' . $name .'" value="' . osc_esc_html(htmlentities($value, ENT_COMPAT, "UTF-8")) . '"/></div>';
                    $contentTemp .= '<div class="FormElement"><label>' . __('Description') . '</label>';
                    $contentTemp .= '<textarea id="' . $nameTextarea . '" name="' . $nameTextarea . '" rows="10">' . $valueTextarea . '</textarea>';
                    $contentTemp .= '</div></div>';
                    $tabs[] = '<li><a href="#'.$category['pk_i_id'].'-'.$locale['pk_c_code'].'">' . $locale['s_name'] . '</a></li>';
                    $content[] = $contentTemp;
             }
             echo '<div class="ui-osc-tabs osc-tab">';
             echo '<ul>'.join('',$tabs).'</ul>';
             echo join('',$content);
             echo '</div>';
        }
    }

    /* file end: ./oc-includes/osclass/frm/Category.form.class.php */
?>
