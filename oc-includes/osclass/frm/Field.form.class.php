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
            parent::generic_input_text("s_name", (isset($field) && isset($field["s_name"])) ? $field["s_name"] : "", null, false) ;
            return true ;
        }

        static public function options_input_text($field = null) {
            parent::generic_input_text("s_options", (isset($field) && isset($field["s_options"])) ? $field["s_options"] : "", null, false) ;
            return true ;
        }

        static public function required_checkbox($field = null) {
            parent::generic_input_checkbox('field_required', 1, ($field!=null && isset($field['b_required']) && $field['b_required']==1)?true:false);
        }
        
        static public function type_select($field = null) {
            ?>
            <select name="field_type" id="field_type">
                <option value="TEXT" <?php if($field['e_type']=="TEXT") { echo 'selected="selected"';};?>>TEXT</option>
                <option value="TEXTAREA" <?php if($field['e_type']=="TEXTAREA") { echo 'selected="selected"';};?>>TEXTAREA</option>
                <option value="DROPDOWN" <?php if($field['e_type']=="DROPDOWN") { echo 'selected="selected"';};?>>DROPDOWN</option>
                <option value="RADIO" <?php if($field['e_type']=="RADIO") { echo 'selected="selected"';};?>>RADIO</option>
                <option value="CHECKBOX" <?php if($field['e_type']=="CHECKBOX") { echo 'selected="selected"';};?>>CHECKBOX</option>
                <option value="URL" <?php if($field['e_type']=="URL") { echo 'selected="selected"';};?>>URL</option>
            </select>
            <?php
            return true;
        }
        
        static public function meta($field = null) {
            if($field!=null) {
                if(Session::newInstance()->_getForm('meta_'.$field['pk_i_id']) != ""){
                    $field['s_value'] = Session::newInstance()->_getForm('meta_'.$field['pk_i_id']);
                }

                if($field['e_type']=="TEXTAREA") {
                    echo '<label for="meta_'.$field['s_slug'].'">'.$field['s_name'].': </label>';
                    echo '<textarea id="meta_' . $field['s_slug'] . '" name="meta['.$field['pk_i_id'].']" rows="10">' . ((isset($field) && isset($field["s_value"])) ? $field["s_value"] : "") . '</textarea>' ;
                } else if($field['e_type']=="DROPDOWN") {
                    echo '<label for="meta_'.$field['s_slug'].'">'.$field['s_name'].': </label>';
                    if(isset($field) && isset($field['s_options'])) {
                        $options = explode(",", $field['s_options']);
                        if(count($options)>0) {
                            echo '<select name="meta['.$field['pk_i_id'].']" id="meta_' . $field['s_slug'] . '">';
                            foreach($options as $option) {
                                echo '<option value="'.osc_esc_html($option).'" '.($field['s_value']==$option?'selected="selected"':'').'>'.$option.'</option>';
                            }
                            echo '</select>';
                        }
                    }
                } else if($field['e_type']=="RADIO") {
                    echo '<label for="meta_'.$field['s_slug'].'">'.$field['s_name'].': </label>';
                    if(isset($field) && isset($field['s_options'])) {
                        $options = explode(",", $field['s_options']);
                        if(count($options)>0) {
                            echo '<ul>';
                            foreach($options as $key => $option) {
                                echo '<li><input type="radio" name="meta['.$field['pk_i_id'].']" id="meta_' . $field['s_slug'] . '_'.$key.'" value="'.osc_esc_html($option).'" '.($field['s_value']==$option?'checked="checked"':'').' /><label for="meta_' . $field['s_slug'] . '_'.$key.'">'.$option.'</label></li>';
                            }
                            echo '</ul>';
                        }
                    }
                } else if($field['e_type']=="CHECKBOX") {
                    echo '<input type="checkbox" name="meta['.$field['pk_i_id'].']" id="meta_' . $field['s_slug'] .'" value="1" ' . ((isset($field) && isset($field["s_value"]) && $field["s_value"]==1) ?'checked="checked"':'') . ' />';
                    echo '<label for="meta_'.$field['s_slug'].'">'.$field['s_name'].': </label>';
                } else {
                    echo '<label for="meta_'.$field['s_slug'].'">'.$field['s_name'].': </label>';
                    echo '<input id="meta_'.$field['s_slug'].'" type="text" name="meta['.$field['pk_i_id'].']" value="' . osc_esc_html((isset($field) && isset($field["s_value"])) ? $field["s_value"] : "") . '" ' ;
                    echo '/>' ;
                }
            }
        }

        static public function meta_fields_input($catId = null, $itemId = null) {
            $fields = Field::newInstance()->findByCategoryItem($catId, $itemId);
            if(count($fields)>0) {
                echo '<div class="meta_list">';
                foreach($fields as $field) {
                    echo '<div class="meta">';
                        FieldForm::meta($field);
                    echo '</div>';
                }
                echo '</div>';
            }
        }
        
    }

?>