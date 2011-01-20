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

class UserForm extends Form {

    static public function primary_input_hidden($user) {
        parent::generic_input_hidden("id", $user["pk_i_id"]) ;    
    }
    
    static public function name_text($user = null) {
        parent::generic_input_text("s_name", isset($user['s_name'])? $user['s_name'] : '', null, false);
        return true ;
    }
    
    static public function username_text($user = null) {
        parent::generic_input_text("profile_username", isset($user['s_username'])? $user['s_username'] : '', null, false);
        return true ;
    }
    
    static public function username_login_text($user = null) {
        parent::generic_input_text("userName", isset($user['s_username'])? $user['s_username'] : '', null, false);
        return true ;
    }
    
    static public function password_login_text($user = null) {
        parent::generic_password("password", '', null, false);
        return true ;
    }
    
    static public function rememberme_login_checkbox($user = null) {
        parent::generic_input_checkbox("rememberMe", '1', false);
        return true ;
    }
    
    static public function username_register_text($user = null) {
        parent::generic_input_text("s_username", isset($user['s_username'])? $user['s_username'] : '', null, false);
        return true ;
    }
    
    static public function password_text($user = null) {
        parent::generic_password("profile_password", '', null, false);
        return true ;
    }
    
    static public function check_password_text($user = null) {
        parent::generic_password("profile_password2", '', null, false);
        return true ;
    }
    
    static public function check_password_register_text($user = null) {
        parent::generic_password("s_password2", '', null, false);
        return true ;
    }
    
    static public function password_register_text($user = null) {
        parent::generic_password("s_password", '', null, false);
        return true ;
    }
    
    static public function email_text($user = null) {
        parent::generic_input_text("s_email", isset($user['s_email'])? $user['s_email'] : '', null, false);
        return true ;
    }
    
    static public function website_text($user = null) {
        parent::generic_input_text("s_website", isset($user['s_website'])? $user['s_website'] : '', null, false);
        return true ;
    }
    
    static public function mobile_text($user = null) {
        parent::generic_input_text("s_phone_mobile", isset($user['s_phone_mobile'])? $user['s_phone_mobile'] : '', null, false);
        return true ;
    }
    
    static public function phone_land_text($user = null) {
        parent::generic_input_text("s_phone_land", isset($user['s_phone_land'])? $user['s_phone_land'] : '', null, false);
        return true ;
    }
    
    static public function info_textarea($user = null) {
        parent::generic_textarea("s_info", isset($user['s_info'])? $user['s_info'] : '');
        return true ;
    }
    
    static public function country_select($countries, $user = null) {
        if( count($countries) > 1 ) {
            parent::generic_select('countryId', $countries, 'pk_c_code', 's_name', __('Select one country...'), (isset($user['fk_c_country_code'])) ? $user['fk_c_country_code'] : null) ;
            return true ;
        } else if ( count($countries) == 1 ) {
            parent::generic_input_hidden('countryId', (isset($user['fk_c_country_code'])) ? $user['fk_c_country_code'] : $countries[0]['pk_c_code']) ;
            echo '</span>' .$countries[0]['s_name'] . '</span>';
            return false ;
        } else {
            parent::generic_input_text('country', (isset($user['s_country'])) ? $user['s_country'] : null) ;
            return true ;
        }
    }

    static public function country_text($user = null) {
        parent::generic_input_text('country', (isset($user['s_country'])) ? $user['s_country'] : null) ;
        return true ;
    }

    static public function region_select($regions, $user = null) {
        if( count($regions) > 1 ) {
            parent::generic_select('regionId', $regions, 'pk_i_id', 's_name', __('Select one region...'), (isset($user['fk_i_region_id'])) ? $user['fk_i_region_id'] : null) ;
            return true ;
        } else if ( count($regions) == 1 ) {
            parent::generic_input_hidden('countryId', (isset($user['fk_i_region_id'])) ? $user['fk_i_region_id'] : $regions[0]['pk_i_id']) ;
            echo '</span>' .$regions[0]['s_name'] . '</span>';
            return false ;
        } else {
            parent::generic_input_text('region', (isset($user['s_region'])) ? $user['s_region'] : null) ;
            return true ;
        }
    }

    static public function region_text($user = null) {
        parent::generic_input_text('region', (isset($user['s_region'])) ? $user['s_region'] : null) ;
    }

    static public function city_select($cities, $user = null) {
        if( count($cities) > 1 ) {
            parent::generic_select('cityId', $cities, 'pk_i_id', 's_name', __('Select one city...'), (isset($user['fk_i_city_id'])) ? $user['fk_i_city_id'] : null) ;
            return true ;
        } else if ( count($cities) == 1 ) {
            parent::generic_input_hidden('cityId', (isset($user['fk_i_city_id'])) ? $user['fk_i_city_id'] : null) ;
            return false ;
        } else {
            parent::generic_input_text('city', (isset($user['s_city'])) ? $user['s_city'] : null) ;
            return true ;
        }
    }

    static public function city_text($user = null) {
        parent::generic_input_text('city', (isset($user['s_city'])) ? $user['s_city'] : null) ;
        return true ;
    }

    static public function city_area_text($user = null) {
        parent::generic_input_text('cityArea', (isset($user['s_city_area'])) ? $user['s_city_area'] : null) ;
        return true ;
    }

    static public function address_text($user = null) {
        parent::generic_input_text('address', (isset($user['s_address'])) ? $user['s_address'] : null) ;
        return true ;
    }







    static public function js_validation() { ?>
<script type="text/javascript">

$(document).ready(function(){
    $('#s_name').focus(function(){
        $('#s_name').css('border', '');
    });

    $('#s_email').focus(function(){
        $('#s_email').css('border', '');
    });

    $('#s_password').focus(function(){
        $('#s_password').css('border', '');
        $('#password-error').css('display', 'none');
    });

    $('#s_password2').focus(function(){
        $('#s_password2').css('border', '');
        $('#password-error').css('display', 'none');
    });
});    
    


function checkForm() {
    var num_errors = 0;
    if( $('#s_name').val() == '' ) {
        $('#s_name').css('border', '1px solid red');
        num_errors = num_errors + 1;
    }
    if( $('#s_email').val() == '' ) {
        $('#s_email').css('border', '1px solid red');
        num_errors = num_errors + 1;
    }
    if( $('#s_password').val() != $('#s_password2').val() ) {
        $('#password-error').css('display', 'block');
        num_errors = num_errors + 1;
    }
    if( $('#s_password').val() == '' ) {
        $('#s_password').css('border', '1px solid red');
        num_errors = num_errors + 1;
    }
    if( $('#s_password2').val() == '' ) {
        $('#s_password2').css('border', '1px solid red');
        num_errors = num_errors + 1;
    }
    if(num_errors > 0) {
        return false;
    }

    return true;
}
</script>
    <?php } 



    static public function js_validation_edit() { ?>
<script type="text/javascript">

$(document).ready(function(){
    $('#s_name').focus(function(){
        $('#s_name').css('border', '');
    });

    $('#s_email').focus(function(){
        $('#s_email').css('border', '');
    });

    $('#s_password').focus(function(){
        $('#s_password').css('border', '');
        $('#password-error').css('display', 'none');
    });

    $('#s_password2').focus(function(){
        $('#s_password2').css('border', '');
        $('#password-error').css('display', 'none');
    });
});

function checkForm() {
    var num_errors = 0;
    if( $('#s_name').val() == '' ) {
        $('#s_name').css('border', '1px solid red');
        num_errors = num_errors + 1;
    }
    if( $('#s_email').val() == '' ) {
        $('#s_email').css('border', '1px solid red');
        num_errors = num_errors + 1;
    }
    if( $('#s_password').val() != $('#s_password2').val() ) {
        $('#password-error').css('display', 'block');
        num_errors = num_errors + 1;
    }
    if(num_errors > 0) {
        return false;
    }

    return true;
}
</script>
    <?php } 



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
                        } else {
                            result += '<option value=""><?php echo __('No results') ?></option>';
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
                        } else {
                            result += '<option value=""><?php echo __('No results') ?></option>';
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

</script>
<?php
    }



    
   
}

?>
