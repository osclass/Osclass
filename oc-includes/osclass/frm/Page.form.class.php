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

    class PageForm extends Form {

        static public function primary_input_hidden($page = null) {
            if(isset($page['pk_i_id'])) {
                parent::generic_input_hidden("id", $page["pk_i_id"]);
            }
        }

        static public function internal_name_input_text($page = null) {
            $internal_name = '';
            if( is_array($page) && isset($page['s_internal_name']) ) {
                $internal_name = $page['s_internal_name'];
            }
            if( Session::newInstance()->_getForm('s_internal_name') != '' ) {
                $internal_name = Session::newInstance()->_getForm('s_internal_name');
            }
            parent::generic_input_text('s_internal_name', $internal_name, null, (isset($page['b_indelible']) && $page['b_indelible'] == 1) ? true : false);
        }
        
        static public function link_checkbox($page = null) {
            $checked = true;
            if( is_array($page) && isset($page['b_link']) && $page['b_link']==0 ) {
                $checked = false;
            }

            parent::generic_input_checkbox('b_link', "1", $checked);
        }

        static public function multilanguage_name_description($locales, $page = null) {
            $num_locales = count($locales);
            if($num_locales > 1) echo '<div class="tabber">';
            $aFieldsDescription = Session::newInstance()->_getForm("aFieldsDescription");
            foreach($locales as $locale) {
                if($num_locales > 1) {
                    echo '<div class="tabbertab">';
                    echo '<h2>' . $locale['s_name'] . '</h2>';
                }
                echo '<div class="FormElement">';
                echo '<div class="FormElementName">' . __('Title') . '</div>';
                echo '<div class="FormElementInput">';
                $title = '';
                if(isset($page['locale'][$locale['pk_c_code']])) {
                    $title = $page['locale'][$locale['pk_c_code']]['s_title'];
                }
                if( isset($aFieldsDescription[$locale['pk_c_code']]) && isset($aFieldsDescription[$locale['pk_c_code']]['s_title']) &&$aFieldsDescription[$locale['pk_c_code']]['s_title'] != '' ) {
                    $title = $aFieldsDescription[$locale['pk_c_code']]['s_title'];
                }
                parent::generic_input_text($locale['pk_c_code'] . '#s_title', $title);
                echo '</div>';
                echo '</div>';
                echo '<div class="FormElement">';
                echo '<div class="FormElementName">' . __('Body') . '</div>';
                echo '<div class="FormElementInput">';
                $description = '';
                if(isset($page['locale'][$locale['pk_c_code']])) {
                    $description = $page['locale'][$locale['pk_c_code']]['s_text'];
                }
                if( isset($aFieldsDescription[$locale['pk_c_code']]) && isset($aFieldsDescription[$locale['pk_c_code']]['s_text']) &&$aFieldsDescription[$locale['pk_c_code']]['s_text'] != '' ) {
                    $description = $aFieldsDescription[$locale['pk_c_code']]['s_text'];
                }
                parent::generic_textarea($locale['pk_c_code'] . '#s_text', $description);
                echo '</div>';
                echo '</div>';
                if($num_locales > 1) {
                    echo '</div>';
                }
             }
             if($num_locales > 1) {
                 echo '</div>';
             }
        }
    }

?>
