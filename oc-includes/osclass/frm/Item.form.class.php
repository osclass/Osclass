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
        if($item==null) { $item = osc_item(); };
        parent::generic_input_hidden("id", $item["pk_i_id"]) ;
    }
    
    static public function category_select($categories = null, $item = null, $default_item = null)
    {
        // Did user select a specific category to post in?
        $catId = Params::getParam('catId');
        
        if($categories==null) { $categories = osc_get_categories(); };
        if($item==null) { $item = osc_item(); };
        echo '<select name="catId" id="catId">' ;           
        if(isset($default_item)) {
            echo '<option value="">' . $default_item . '</option>' ;
        }
        foreach($categories as $c) {
            $selected = ( (isset($item["fk_i_category_id"]) && $item["fk_i_category_id"] == $c['pk_i_id']) || (isset($catId) && $catId == $c['pk_i_id']) );
            echo '<option value="' . $c['pk_i_id'] . '"' . ($selected ? 'selected="selected"' : '' ). '>' . $c['s_name'] . '</option>' ;
            if(isset($c['categories']) && is_array($c['categories'])) {
                ItemForm::subcategory_select($c['categories'], $item, $default_item, 1);
            }
        }
        echo '</select>' ;
        return true ;
    }
    
    static public function subcategory_select($categories, $item, $default_item = null, $deep = 0)
    {
        // Did user select a specific category to post in?
        $catId = Params::getParam('catId');
        
        // How many indents to add?
        $deep_string = 0;
        if( $deep > 0 ) {
            $deep_string = (string) (15 * $deep);
        }

        foreach($categories as $c) {
            $selected = ( (isset($item["fk_i_category_id"]) && $item["fk_i_category_id"] == $c['pk_i_id']) || (isset($catId) && $catId == $c['pk_i_id']) );
            echo '<option style="padding-left: ' . $deep_string . 'px;" value="' . $c['pk_i_id'] . '"' . ($selected ? 'selected="selected"' : '') . '>' . $c['s_name'] . '</option>' ;
            if(isset($c['categories']) && is_array($c['categories'])) {
                ItemForm::subcategory_select($c['categories'], $item, $default_item, $deep+1);
            }
        }
    }

    static public function user_select($users = null, $item = null, $default_item = null)
    {
        if($users==null) { $users = User::newInstance()->listAll(); };
        if($item==null) { $item = osc_item(); };
        echo '<select name="userId" id="userId">' ;
            if(isset($default_item)) {
                echo '<option value="">' . $default_item . '</option>' ;
            }
            foreach($users as $user) {
                echo '<option value="' . $user['pk_i_id'] . '"' . ( (isset($item["fk_i_user_id"]) && $item["fk_i_user_id"] == $user['pk_i_id']) ? 'selected="selected"' : '' ) . '>' . $user['s_name'] . '</option>' ;
            }
        echo '</select>' ;
        return true ;
    }

    static public function title_input($name, $locale = 'en_US', $value = '')
    {
        parent::generic_input_text($name . '[' . $locale . ']', $value) ;
        return true ;
    }

    static public function description_textarea($name, $locale = 'en_US', $value = '')
    {
        parent::generic_textarea($name . '[' . $locale . ']', $value) ;
        return true ;
    }

    static public function multilanguage_title_description($locales = null, $item = null) {
        if($locales==null) { $locales = osc_get_locales(); }
        if($item==null) { $item = osc_item(); }
        $num_locales = count($locales);
        if($num_locales>1) { echo '<div class="tabber">'; };
        foreach($locales as $locale) {
            if($num_locales>1) { echo '<div class="tabbertab">'; };
            if($num_locales>1) { echo '<h2>' . $locale['s_name'] . '</h2>'; };
            echo '<div class="title">';
            echo '<div><label for="title">' . __('Title') . ' *</label></div>';
            self::title_input('title', $locale['pk_c_code'], (isset($item) && isset($item['locale'][$locale['pk_c_code']]) && isset($item['locale'][$locale['pk_c_code']]['s_title'])) ? $item['locale'][$locale['pk_c_code']]['s_title'] : '' );
            echo '</div>';
            echo '<div class="description">';
            echo '<div><label for="description">' . __('Description') . ' *</label></div>';
            self::description_textarea('description', $locale['pk_c_code'], (isset($item) && isset($item['locale'][$locale['pk_c_code']]) && isset($item['locale'][$locale['pk_c_code']]['s_description'])) ? $item['locale'][$locale['pk_c_code']]['s_description'] : '');
            echo '</div>';
            if($num_locales>1) { echo '</div>'; };
         }
         if($num_locales>1) { echo '</div>'; };
    }
    
    static public function price_input_text($item = null)
    {
        if($item==null) { $item = osc_item(); };
        parent::generic_input_text('price', (isset($item['f_price'])) ? $item['f_price'] : null) ;
    }

    static public function currency_select($currencies = null, $item = null) {
        if($currencies==null) { $currencies = osc_get_currencies(); };
        if($item==null) { $item = osc_item(); }
        if(count($currencies) > 1 ) {
            $default_key = null;
            $currency = Preference::newInstance()->findByConditions(array('s_section' => 'osclass', 's_name' => 'currency')) ;
            if ( isset($item['fk_c_currency_code']) ) {
                $default_key = $item['fk_c_currency_code'];
            } elseif ( is_array($currency) ) {
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


    static public function country_select($countries = null, $item = null) {
        if($countries==null) { $countries = osc_get_countries(); };
        if($item==null) { $item = osc_item(); };
        if( count($countries) > 1 ) {
            parent::generic_select('countryId', $countries, 'pk_c_code', 's_name', __('Select a country...'), (isset($item['fk_c_country_code'])) ? $item['fk_c_country_code'] : null) ;
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
        if($item==null) { $item = osc_item(); };
        parent::generic_input_text('country', (isset($item['s_country'])) ? $item['s_country'] : null) ;
        return true ;
    }

    static public function region_select($regions = null, $item = null) {
        if($regions==null) { $regions = osc_get_regions(); };
        if($item==null) { $item = osc_item(); };
        if( count($regions) > 1 ) {
            parent::generic_select('regionId', $regions, 'pk_i_id', 's_name', __('Select a region...'), (isset($item['fk_i_region_id'])) ? $item['fk_i_region_id'] : null) ;
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
        if($item==null) { $item = osc_item(); };
        parent::generic_input_text('region', (isset($item['s_region'])) ? $item['s_region'] : null) ;
    }

    static public function city_select($cities = null, $item = null) {
        if($cities==null) { $cities = osc_get_cities(); };
        if($item==null) { $item = osc_item(); };
        if( count($cities) > 1 ) {
            parent::generic_select('cityId', $cities, 'pk_i_id', 's_name', __('Select a city...'), (isset($item['fk_i_city_id'])) ? $item['fk_i_city_id'] : null) ;
            return true ;
        } else if ( count($cities) == 1 ) {
            parent::generic_input_hidden('cityId', (isset($item['fk_i_city_id'])) ? $item['fk_i_city_id'] : $cities[0]['pk_i_id']) ;
            echo '</span>' .$cities[0]['s_name'] . '</span>';
            return false ;
        } else {
            parent::generic_input_text('city', (isset($item['s_city'])) ? $item['s_city'] : null) ;
            return true ;
        }
    }

    static public function city_text($item = null) {
        if($item==null) { $item = osc_item(); };
        parent::generic_input_text('city', (isset($item['s_city'])) ? $item['s_city'] : null) ;
        return true ;
    }

    static public function city_area_text($item = null) {
        if($item==null) { $item = osc_item(); };
        parent::generic_input_text('cityArea', (isset($item['s_city_area'])) ? $item['s_city_area'] : null) ;
        return true ;
    }

    static public function address_text($item = null) {
        if($item==null) { $item = osc_item(); };
        parent::generic_input_text('address', (isset($item['s_address'])) ? $item['s_address'] : null) ;
        return true ;
    }

    static public function contact_name_text($item = null) {
        if($item==null) { $item = osc_item(); };
        parent::generic_input_text('contactName', (isset($item['s_contact_name'])) ? $item['s_contact_name'] : null) ;
        return true ;
    }

    static public function contact_email_text($item = null) {
        if($item==null) { $item = osc_item(); };
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
        if($item==null) { $item = osc_item(); };
        parent::generic_input_checkbox('showEmail', '1', (isset($item['b_show_email']) ) ? $item['b_show_email'] : false );
        return true ;
    }

    static public function location_javascript($path = "front") {
 ?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#countryId").change(function(){
            var pk_c_code = $(this).val();
            <?php if($path=="admin") { ?>
                var url = '<?php echo osc_admin_base_url(true)."?page=ajax&action=regions&countryId="; ?>' + pk_c_code;
            <?php } else { ?>
                var url = '<?php echo osc_base_url(true)."?page=ajax&action=regions&countryId="; ?>' + pk_c_code;
            <?php }; ?>
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
                            result += '<option value=""><?php _e("Select a region..."); ?></option>';
                            for(key in data) {
                                result += '<option value="' + data[key].pk_i_id + '">' + data[key].s_name + '</option>';
                            }
                            $("#region").before('<select name="regionId" id="regionId" ></select>');
                            $("#region").remove();
                        } else {
                            result += '<option value=""><?php _e('No results') ?></option>';
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
            <?php if($path=="admin") { ?>
                var url = '<?php echo osc_admin_base_url(true)."?page=ajax&action=cities&regionId="; ?>' + pk_c_code;
            <?php } else { ?>
                var url = '<?php echo osc_base_url(true)."?page=ajax&action=cities&regionId="; ?>' + pk_c_code;
            <?php }; ?>

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
                            result += '<option value=""><?php _e("Select a city..."); ?></option>';
                            for(key in data) {
                                result += '<option value="' + data[key].pk_i_id + '">' + data[key].s_name + '</option>';
                            }
                            $("#city").before('<select name="cityId" id="cityId" ></select>');
                            $("#city").remove();
                        } else {
                            result += '<option value=""><?php _e('No results') ?></option>';
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
    
        /**
         * Validate form
         */
         
        // Validate description without HTML.
        $.validator.addMethod(
            "minstriptags", 
            function(value, element) { 
                altered_input = strip_tags(value);
                if (altered_input.length < 10) {
                    return false;
                } else {
                    return true;
                }
            }, 
            "<?php _e("Description: needs to be longer"); ?>."
        );
        
        // Validate fields in each locale.
        $("form[name=item] button").click(function() {
            lang_count = $(".title input").length;
            // Title
            $(".title input").each(function(){
                lang_name   = $(this).parent().prev('h2').text().replace(/^(.+) \((.+)\)$/, '$1');
                lang_locale = $(this).attr('name').replace(/^title\[(.+)\]$/,'$1');

                str = ((lang_count > 1) ? lang_name + ' ' : '');
                $(this).rules("add", {
                    required: true,
                    minlength: 9,
                    maxlength: 80,
                    messages: {
                        required: str + "<?php _e("Title: this field is required"); ?>.",
                        minlength: str + "<?php _e("Title: enter at least 9 characters"); ?>.",
                        maxlength: str + "<?php _e("Title: no more than 80 characters"); ?>."
                    }
                });                   
            });
            // Description
            $(".description textarea").each(function(){
                lang_name   = $(this).parent().prev('h2').text().replace(/^(.+) \((.+)\)$/, '$1');
                lang_locale = $(this).attr('name').replace(/^title\[(.+)\]$/,'$1');

                str = ((lang_count > 1) ? lang_name + ' ' : '');
                $(this).rules("add", {
                    required: true,
                    minlength: 10,
                    maxlength: 5000,
                    'minstriptags': true,
                    messages: {
                        required: str + "<?php _e("Description: this field is required"); ?>.",
                        minlength: str + "<?php _e("Description: needs to be longer"); ?>.",
                        maxlength: str + "<?php _e("Description: no more than 5000 characters"); ?>."
                    }
                });                   
            });
        });
        
        // Code for form validation
        $("form[name=item]").validate({
            rules: {
                catId: {
                    required: true,
                    digits: true
                },
                <?php if(osc_price_enabled_at_items()) { ?>
                price: {
                    number: true,
                    maxlength: 15
                },
                currency: "required",
                <?php } ?>
                <?php if(osc_images_enabled_at_items()) { ?>
                "photos[]": {
                    accept: "<?php echo osc_allowed_extension(); ?>"
                },
                <?php } ?>
                <?php if($path == 'front') { ?>
                contactName: {
                    minlength: 3,
                    maxlength: 35
                },
                contactEmail: {
                    required: true,
                    email: true
                },
                <?php } ?>
                regionId: {
                    required: true,
                    digits: true
                },
                cityId: {
                    required: true,
                    digits: true
                },
                cityArea: {
                    minlength: 3,
                    maxlength: 35
                },
                address: {
                    minlength: 5,
                    maxlength: 50
                }
            },
            messages: {
                catId: "<?php _e('Choose one category'); ?>.",
                <?php if(osc_price_enabled_at_items()) { ?>
                price: {
                    number: "<?php _e('Price: enter a valid number'); ?>.",
                    maxlength: "<?php _e("Price: no more than 15 characters"); ?>."
                },
                currency: "<?php _e("Currency: make your selection"); ?>.",
                <?php } ?>
                <?php if(osc_images_enabled_at_items()) { ?>
                "photos[]": {
                    accept: "<?php printf(__("Photo: must be %s"), osc_allowed_extension()); ?>."
                },
                <?php } ?>
                <?php if($path == 'front') { ?>
                contactName: {
                    minlength: "<?php _e("Name: enter at least 3 characters"); ?>.",
                    maxlength: "<?php _e("Name: no more than 35 characters"); ?>."
                },
                contactEmail: {
                    required: "<?php _e("Email: this field is required"); ?>.",
                    email: "<?php _e("Invalid email address"); ?>."
                },
                <?php } ?>
                regionId: "<?php _e("Select a region"); ?>.",
                cityId: "<?php _e("Select a city"); ?>.",
                cityArea: {
                    minlength: "<?php _e("City area: enter at least 3 characters"); ?>.",
                    maxlength: "<?php _e("City area: no more than 35 characters"); ?>."
                },
                address: {
                    minlength: "<?php _e("Address: enter at least 5 characters"); ?>.",
                    maxlength: "<?php _e("Address: no more than 50 characters"); ?>."
                }
            },
            errorLabelContainer: "#error_list",
            wrapper: "li",
            invalidHandler: function(form, validator) {
                $('html,body').animate({ scrollTop: $('h1').offset().top }, { duration: 250, easing: 'swing'});
            }
        });
    });

    /**
     * Strip HTML tags to count number of visible characters.
     */
    function strip_tags(html) {
        if (arguments.length < 3) {
            html=html.replace(/<\/?(?!\!)[^>]*>/gi, '');
        } else {
            var allowed = arguments[1];
            var specified = eval("["+arguments[2]+"]");
            if (allowed){
                var regex='</?(?!(' + specified.join('|') + '))\b[^>]*>';
                html=html.replace(new RegExp(regex, 'gi'), '');
            } else{
                var regex='</?(' + specified.join('|') + ')\b[^>]*>';
                html=html.replace(new RegExp(regex, 'gi'), '');
            }
        }
        return html;
    }
</script>
<?php
    }

    static public function photos($resources = null) {
        if($resources==null) { $resources = osc_get_item_resources(); };
        if($resources!=null && is_array($resources) && count($resources)>0) {
            foreach($resources as $_r) { ?>
                <div id="<?php echo $_r['pk_i_id'];?>" fkid="<?php echo $_r['fk_i_item_id'];?>" name="<?php echo $_r['s_name'];?>">
                    <img src="<?php echo osc_base_url() . $_r['s_path'] . $_r['pk_i_id'] . '_thumbnail.' . $_r['s_extension']; ?>" /><a onclick="javascript:return confirm('<?php _e('This action can\\\'t be undone. Are you sure you want to continue?'); ?>')" href="<?php echo osc_item_resource_delete_url($_r['pk_i_id'], $_r['fk_i_item_id'], $_r['s_name'], Params::getParam('secret') );?>" class="delete"><?php _e('Delete'); ?></a>
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
    // Listener: automatically add new file field when the visible ones are full.
    setInterval("add_file_field()", 250);
    /**
     * Timed: if there are no empty file fields, add new file field.
     */
    function add_file_field() {
        var count = 0;
        $("input[name='photos[]']").each(function(index) {
            if ( $(this).val() == '' ) {
                count++;
            }
        });
        if (count == 0) {
            addNewPhoto();
        }
    }
</script>
<?php
    }

    static public function plugin_post_item($case = 'form') {
?>
<script type="text/javascript">
    $("#catId").change(function(){
        var cat_id = $(this).val();
        var url = '<?php echo osc_base_url(true); ?>';
        var result = '';

        if(cat_id != '') {
            $.ajax({
                type: "POST",
                url: url,
                data: 'page=ajax&action=runhook&hook=item_<?php echo $case;?>&catId=' + cat_id,
                dataType: 'text/html',
                success: function(data){
                    $("#plugin-hook").html(data);
                }
            });
        }
    });
    $(document).ready(function(){
        var cat_id = $("#catId").val();
        var url = '<?php echo osc_base_url(true); ?>';
        var result = '';

        if(cat_id != '') {
            $.ajax({
                type: "POST",
                url: url,
                data: 'page=ajax&action=runhook&hook=item_<?php echo $case;?>&catId=' + cat_id,
                dataType: 'text/html',
                success: function(data){
                    $("#plugin-hook").html(data);
                }
            });
        }
    });
</script>
<div id="plugin-hook">
</div>
<?php
    }
    
    static public function plugin_edit_item() {
        ItemForm::plugin_post_item('edit&itemId='.osc_item_id());
    }
    
}

?>
