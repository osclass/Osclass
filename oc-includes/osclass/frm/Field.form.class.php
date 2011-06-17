<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

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

    class FieldForm extends Form {

        static public function primary_input_hidden($field = null) {
            if(isset($field['pk_i_id'])) {
                parent::generic_input_hidden("id", $field["pk_i_id"]) ;
            }
        }

        static public function name_input_text($field = null) {
            parent::generic_input_text("s_name", (isset($field) && isset($field["s_internal_name"])) ? $field["s_internal_name"] : "", null, (isset($field['b_indelible']) && $field['b_indelible'] == 1) ? true : false) ;
            return true ;
        }

        static public function multilanguage_name_description($locales, $category = null) {
            $num_locales = count($locales);
            if($num_locales>1) { echo '<div class="tabber">'; };
            foreach($locales as $locale) {
               if($num_locales>1) {  echo '<div class="tabbertab">'; };
                    if($num_locales>1) { echo '<h2>' . $locale['s_name'] . '</h2>'; };
                    echo '<div class="FormElement">';
                        echo '<div class="FormElementName">' . __('Title') . '</div>';
                        echo '<div class="FormElementInput">' ;
                            parent::generic_input_text($locale['pk_c_code'] . '#s_title', (isset($category['locale'][$locale['pk_c_code']])) ? $category['locale'][$locale['pk_c_code']]['s_title'] : "") ;
                        echo '</div>' ;
                    echo '</div>';
                    echo '<div class="FormElement">';
                        echo '<div class="FormElementName">' . __('Body') . '</div>';
                        echo '<div class="FormElementInput">' ;
                            parent::generic_textarea($locale['pk_c_code'] . '#s_text', (isset($category['locale'][$locale['pk_c_code']])) ? ($category['locale'][$locale['pk_c_code']]['s_text']) : "") ;
                        echo '</div>' ;
                    echo '</div>';
                if($num_locales>1) { echo '</div>'; };
             }
             if($num_locales>1) { echo '</div>'; };
        }
    }

?>