<?php
/**
 * OSClass – software for creating and publishing online classified advertising platforms
 *
 * Copyright (C) 2010 OSCLASS
 *
 * This program is free software: you can redistribute it and/or modify it under the terms
 * of the GNU Affero General Public License as published by the Free Software Foundation,
 * either version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

class ItemForm extends Form {

    static public function primary_input_hidden($item)
    {
        parent::generic_input_hidden("id", $item["pk_i_id"]) ;
    }
    
    static public function category_select($categories, $item, $default_item = null)
    {
        echo '<select name="catId" id="catId">' ;
            if(isset($default_item)) {
                echo '<option value="">' . $default_item . '</option>' ;
            }
            foreach($categories as $c) {
                echo '<option value="' . $c['pk_i_id'] . '"' . ( ($item["fk_i_category_id"] == $c['pk_i_id']) ? 'selected="selected"' : '' ) . '>' . $c['s_name'] . '</option>' ;
                if(isset($c['categories']) && is_array($c['categories'])) {
                    ItemForm::subcategory_select($c['categories'], $item, $default_item, 1);
                }
            }
        echo '</select>' ;
        return true ;
    }
    
    static public function subcategory_select($categories, $item, $default_item = null, $deep = 0)
    {
        $deep_string = "";
        for($var = 0;$var<$deep;$var++) {
            $deep_string .= '&nbsp;&nbsp;';
        }
        $deep++;
        foreach($categories as $c) {
            echo '<option value="' . $c['pk_i_id'] . '"' . ( ($item['fk_i_category_id'] == $c['pk_i_id']) ? 'selected="selected"' : '' ) . '>' . $deep_string.$c['s_name'] . '</option>' ;
            if(isset($c['categories']) && is_array($c['categories'])) {
                ItemForm::subcategory_select($c['categories'], $item, $default_item, $deep+1);
            }
        }
    }

    static public function user_select($users, $item, $default_item = null)
    {
        echo '<select name="userId" id="userId">' ;
            if(isset($default_item)) {
                echo '<option value="">' . $default_item . '</option>' ;
            }
            foreach($users as $user) {
                echo '<option value="' . $user['pk_i_id'] . '"' . ( ($item["fk_i_user_id"] == $user['pk_i_id']) ? 'selected="selected"' : '' ) . '>' . $user['s_name'] . '</option>' ;
            }
        echo '</select>' ;
        return true ;
    }

    static public function title_input($name, $locale = 'en_US', $value = '')
    {
        parent::generic_input_text($locale . "#" . $name, $value) ;
        return true ;
    }

    static public function description_textarea($name, $locale = 'en_US', $value = '')
    {
        parent::generic_textarea($locale . "#" . $name, $value) ;
        return true ;
    }

    static public function multilanguage_title_description($locales, $item = null) {
        $num_locales = count($locales);
        if($num_locales>1) { echo '<div class="tabber">'; };
        foreach($locales as $locale) {
            if($num_locales>1) { echo '<div class="tabbertab">'; };
            if($num_locales>1) { echo '<h2>' . $locale['s_name'] . '</h2>'; };
            echo '<div class="title">';
            echo '<div><label for="title">' . __('Title') . '</label></div>';
            self::title_input('s_title', $locale['pk_c_code'], (isset($item) && isset($item['locale'][$locale['pk_c_code']]) && isset($item['locale'][$locale['pk_c_code']]['s_title'])) ? $item['locale'][$locale['pk_c_code']]['s_title'] : '' );
            echo '</div>';
            echo '<div class="description">';
            echo '<div><label for="description">' . __('Description') . '</label></div>';
            self::description_textarea('s_description', $locale['pk_c_code'], (isset($item) && isset($item['locale'][$locale['pk_c_code']]) && isset($item['locale'][$locale['pk_c_code']]['s_description'])) ? $item['locale'][$locale['pk_c_code']]['s_description'] : '');
            echo '</div>';
            if($num_locales>1) { echo '</div>'; };
         }
         if($num_locales>1) { echo '</div>'; };
    }
    
    static public function price_input_text($item = null)
    {
        parent::generic_input_text('price', (isset($item['f_price'])) ? $item['f_price'] : null) ;
    }

    static public function currency_select($currencies, $item = null) {
        if(count($currencies) > 1 ) {
            $default_key = null;
            $mPreference = new Preference();
            $currency = $mPreference->findByConditions(array('s_section' => 'osclass', 's_name' => 'currency'));
            if ( isset($item['fk_c_currency_code']) ) {
                $default_key = $item['fk_c_currency_code'];
            } else if ( is_array($currency) ) {
                if ( isset($currency['s_value']) ) {
                    $default_key = $currency['s_value'];
                }
            }
            
            parent::generic_select('currency', $currencies, 'pk_c_code', 's_description', null, $default_key) ;
        } else if (count($currencies) == 1) {
            parent::generic_input_hidden("currency", $currencies[0]["pk_c_code"]) ;
            echo $currencies[0]['s_description'];
        }
    }


    static public function country_select($countries, $item = null) {
        if( count($countries) > 1 ) {
            parent::generic_select('countryId', $countries, 'pk_c_code', 's_name', __('Select one country...'), (isset($item['fk_c_country_code'])) ? $item['fk_c_country_code'] : null) ;
            return true ;
        } else if ( count($countries) == 1 ) {
            parent::generic_input_hidden('countryId', (isset($item['fk_c_country_code'])) ? $item['fk_c_country_code'] : $countries[0]['pk_c_code']) ;
            echo '</span>' .$countries[0]['s_name'] . '</span>';
            return false ;
        } else {
            parent::generic_input_text('country', (isset($item['s_country'])) ? $item['s_country'] : null) ;
            return true ;
        }
    }

    static public function country_text($item = null) {
        parent::generic_input_text('country', (isset($item['s_country'])) ? $item['s_country'] : null) ;
        return true ;
    }

    static public function region_select($regions, $item = null) {
        if( count($regions) > 1 ) {
            parent::generic_select('regionId', $regions, 'pk_i_id', 's_name', __('Select one region...'), (isset($item['fk_i_region_id'])) ? $item['fk_i_region_id'] : null) ;
            return true ;
        } else if ( count($regions) == 1 ) {
            parent::generic_input_hidden('regionId', (isset($item['fk_i_region_id'])) ? $item['fk_i_region_id'] : $regions[0]['pk_i_id']) ;
            echo '</span>' .$regions[0]['s_name'] . '</span>';
            return false ;
        } else {
            parent::generic_input_text('region', (isset($item['s_region'])) ? $item['s_region'] : null) ;
            return true ;
        }
    }

    static public function region_text($item = null) {
        parent::generic_input_text('region', (isset($item['s_region'])) ? $item['s_region'] : null) ;
    }

    static public function city_select($cities, $item = null) {
        if( count($cities) > 1 ) {
            parent::generic_select('cityId', $cities, 'pk_i_id', 's_name', __('Select one city...'), (isset($item['fk_i_city_id'])) ? $item['fk_i_city_id'] : null) ;
            return true ;
        } else if ( count($cities) == 1 ) {
            parent::generic_input_hidden('cityId', (isset($item['fk_i_city_id'])) ? $item['fk_i_city_id'] : null) ;
            return false ;
        } else {
            parent::generic_input_text('city', (isset($item['s_city'])) ? $item['s_city'] : null) ;
            return true ;
        }
    }

    static public function city_text($item = null) {
        parent::generic_input_text('city', (isset($item['s_city'])) ? $item['s_city'] : null) ;
        return true ;
    }

    static public function city_area_text($item = null) {
        parent::generic_input_text('cityArea', (isset($item['s_city_area'])) ? $item['s_city_area'] : null) ;
        return true ;
    }

    static public function address_text($item = null) {
        parent::generic_input_text('address', (isset($item['s_address'])) ? $item['s_address'] : null) ;
        return true ;
    }

    static public function contact_name_text($item = null) {
        parent::generic_input_text('contactName', (isset($item['s_contact_name'])) ? $item['s_contact_name'] : null) ;
        return true ;
    }

    static public function contact_email_text($item = null) {
        parent::generic_input_text('contactEmail', (isset($item['s_contact_email'])) ? $item['s_contact_email'] : null) ;
        return true ;
    }

    static public function user_data_hidden() {
        if(isset($_SESSION['userId']) && $_SESSION['userId']!=null) {
            $user = User::newInstance()->findByPrimaryKey($_SESSION['userId']);
            parent::generic_input_hidden('contactName', $user['s_name']);
            parent::generic_input_hidden('contactEmail', $user['s_email']);
            return true;
        } else {
            return false;
        }
    }

    static public function show_email_checkbox($item = null) {
        echo '<label for="showEmail">';
        parent::generic_input_checkbox('showEmail', '1', (isset($item['b_show_email']) ) ? $item['b_show_email'] : false );
        echo __('Show email publically within the item page') . '</label>';
        return true ;
    }

    static public function location_javascript() {
 ?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#countryId").change(function(){
            var pk_c_code = $(this).val();
            var url = '<?php echo WEB_PATH . "/oc-includes/osclass/ajax/region.php?countryId="; ?>' + pk_c_code;
            var result = '';

            if(pk_c_code != '') {
                $("#regionId").attr('disabled',false);
                $("#cityId").attr('disabled',true);
                $.ajax({
                    type: "POST",
                    url: url,
                    dataType: 'json',
                    success: function(data){
                        var length = data.length;
                        if(length > 0) {
                            result += '<option value=""><?php echo __("Select a region..."); ?></option>';
                            for(key in data) {
                                result += '<option value="' + data[key].pk_i_id + '">' + data[key].s_name + '</option>';
                            }
                            $("#region").before('<select name="regionId" id="regionId" ></select>');
                            $("#region").remove();
                        } else {
                            result += '<option value=""><?php echo __('No results') ?></option>';
                            $("#regionId").before('<input type="text" name="region" id="region" />');
                            $("#regionId").remove();
                        }
                        $("#regionId").html(result);
                    }
                 });
             } else {
                $("#regionId").attr('disabled',true);
                $("#cityId").attr('disabled',true);
             }
        });


        $("#regionId").change(function(){
            var pk_c_code = $(this).val();
            var url = '<?php echo WEB_PATH . "/oc-includes/osclass/ajax/city.php?regionId="; ?>' + pk_c_code;
            var result = '';

            if(pk_c_code != '') {
                $("#cityId").attr('disabled',false);
                $.ajax({
                    type: "POST",
                    url: url,
                    dataType: 'json',
                    success: function(data){
                        var length = data.length;
                        if(length > 0) {
                            result += '<option value=""><?php echo __("Select a city..."); ?></option>';
                            for(key in data) {
                                result += '<option value="' + data[key].pk_i_id + '">' + data[key].s_name + '</option>';
                            }
                            $("#city").before('<select name="cityId" id="cityId" ></select>');
                            $("#city").remove();
                        } else {
                            result += '<option value=""><?php echo __('No results') ?></option>';
                            $("#cityId").before('<input type="text" name="city" id="city" />');
                            $("#cityId").remove();
                        }
                        $("#cityId").html(result);
                    }
                 });
             } else {
                $("#cityId").attr('disabled',true);
             }
        });


        if( $("#regionId").attr('value') == "")  {
            $("#cityId").attr('disabled',true);
        }
        
        if( $("#countryId").attr('type').match(/select-one/) ) {
            if( $("#countryId").attr('value') == "")  {
                $("#regionId").attr('disabled',true);
            }
        }
        
    });

    function checkForm() {
        if(document.getElementById('regionId').value == "") {
            alert("<?php  _e('You have to select a region.');?>");
            return false;
        }

        if(document.getElementById('cityId').value == "") {
            alert("<?php  _e('You have to select a city.');?>");
            return false;
        }

        if(document.getElementById('city').value == "") {
            alert("<?php  _e('You have to write a city.');?>");
            return false;
        }

        if(typeof(document.getElementById('contactName'))!='undefined') {
            if(document.getElementById('contactName').value == "") {
                alert("<?php  _e('You have to write a name.');?>");
                return false;
            }
        }
        
        if(typeof(document.getElementById('contactEmail'))!='undefined') {
            if(document.getElementById('contactEmail').value == "") {
                alert("<?php  _e('You have to write an e-mail.');?>");
                return false;
            }
        }
        

        return true;
    }
</script>
<?php
    }

    static public function photos($resources = null) {

        if($resources!=null && is_array($resources) && count($resources)>0) {
            foreach($resources as $_r) { ?>
                <div id="<?php echo $_r['pk_i_id'];?>" fkid="<?php echo $_r['fk_i_item_id'];?>" name="<?php echo $_r['s_name'];?>">
                    <img src="<?php echo $_r['s_path'];?>" /><a onclick=\"javascript:return confirm('<?php echo __('This action can not be undone. Are you sure you want to continue?'); ?>')\" href="user.php?action=deleteResource&id=<?php echo $_r['pk_i_id'];?>&fkid=<?php echo $_r['fk_i_item_id'];?>&name=<?php echo $_r['s_name'];?>" class="delete"><?php echo __('Delete'); ?></a>
                </div>						
            <?php }
        }
    }

    static public function photos_javascript() {
?>
<script type="text/javascript">
    var photoIndex = 0;
    function gebi(id) { return document.getElementById(id); }
    function ce(name) { return document.createElement(name); }
    function re(id) {
        var e = gebi(id);
        e.parentNode.removeChild(e);
    }
    function addNewPhoto() {
        var id = 'p-' + photoIndex++;

        var i = ce('input');
        i.setAttribute('type', 'file');
        i.setAttribute('name', 'photos[]');

        var a = ce('a');
        a.style.fontSize = 'x-small';
        a.setAttribute('href', '#');
        a.setAttribute('divid', id);
        a.onclick = function() { re(this.getAttribute('divid')); return false; }
        a.appendChild(document.createTextNode('<?php _e('Remove'); ?>'));

        var d = ce('div');
        d.setAttribute('id', id);
        d.setAttribute('style','padding: 4px 0;')

        d.appendChild(i);
        d.appendChild(a);

        gebi('photos').appendChild(d);
    }
</script>
<?php
    }

    static public function plugin_post_item($categories) {
?>
<script type="text/javascript">
    $("#catId").change(function(){
        var cat_id = $(this).val();
        var url = '<?php echo WEB_PATH . "/oc-includes/osclass/ajax/plugins.php"; ?>';
        var result = '';

        if(cat_id != '') {
            $.ajax({
                type: "POST",
                url: url,
                data: 'action=runhook&hook=item_form&catId=' + cat_id,
                dataType: 'text/html',
                success: function(data){
                    $("#plugin-hook").html(data);
                }
            });
        }
    });
</script>
<div id="plugin-hook">
<?php
    if (isset($_GET['catId'])) {
        osc_runHook('item_form', $_GET['catId']);
    } else {
        if(is_array($categories)) {
            osc_runHook('item_form', $categories[0]['pk_i_id']);
        } else {
            osc_runHook('item_form', $categories);
        }
    }
?>
</div>
<?php
    }
}

?>
