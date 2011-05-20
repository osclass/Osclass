<?php
/*
 *      OSCLass software for creating and publishing online classified
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


    // Required: num. of non-punctuation characters (international)
    function osc_validate_text ($value = '', $count = 1, $required = true) {
        if ($required || $value) {
            if ( !preg_match("/([\p{L}][^\p{L}]*){".$count."}/i", strip_tags($value)) ) {
                return false;
            }
        }
        return true;
    }


    // Required: one or more numbers (no periods)
    function osc_validate_int ($value) {
        if ( preg_match("/^[0-9]+$/", $value) ) {
            return true;
        }
        return false;
    }


    /**
    Required: one or more numbers (no periods),
    must be more than 0.
    */
    function osc_validate_nozero ($value) {
        if ( preg_match("/^[0-9]+$/", $value) && $value>0 ) {
            return true;
        }
        return false;
    }


    // Required: number (period allowed, no commas)
    function osc_validate_number ($value = null, $required = false) {
        if ($required || strlen($value) > 0) {
            if ( !is_numeric($value) ) {
                return false;
            }
        }
        return true;
    }


    // Required: num. of digits (international)
    function osc_validate_phone ($value = null, $count = 10, $required = false) {
        if ($required || strlen($value) > 0) {
            if ( !preg_match("/([\p{Nd}][^\p{Nd}]*){".$count."}/i", strip_tags($value)) ) {
                return false;
            }
        }
        return true;
    }

     
    // Check: more than minimum.
    function osc_validate_min ($value = null, $min = 6) {
        if ( strlen($value) < $min ) {
            return false;
        }
        return true;
    } 
     
     
    // Check: less than maximum.
    function osc_validate_max ($value = null, $max = 255) {
        if ( strlen($value) > $max ) {
            return false;
        }
        return true;
    }


    // Required: range min. to max.
    function osc_validate_range ($value, $min = 6, $max = 255) {
        if ( strlen($value)>=$min && strlen($value)<=$max ) {
            return true;
        }
        return false;
    }


    // Exists: Country/Region/City
    function osc_validate_location ($city, $region, $country) {
        if ( osc_validate_nozero($city) && osc_validate_nozero($region) && osc_validate_text($country,2) ) {
            $data = Country::newInstance()->findByCode($country);
            $countryId = $data['pk_c_code'];
            if ( $countryId  ) {
                $data = Region::newInstance()->findByPrimaryKey($region);
                $regionId = $data['pk_i_id'];
                if ( $data['b_active'] == 1 ) {
                    $data = City::newInstance()->findByPrimaryKey($city);
                    if ($data['b_active'] == 1 && $data['fk_i_region_id'] == $regionId && strtolower($data['fk_c_country_code']) == strtolower($countryId)) {
                        return true;
                    }
                }
            }
        }
        return false;
    }


    // Exists: Category [and is enabled]
    function osc_validate_category ($value) {
        if ( osc_validate_nozero($value) ) {
            $data = Category::newInstance()->findByPrimaryKey($value);
            if (isset($data['b_enabled']) && $data['b_enabled'] == 1) {
                if(osc_selectable_parent_categories()){
                    return true;
                } else {
                    if($data['fk_i_parent_id']!=null) {
                        return true;
                    }
                }
            }
        }
        return false;
    }


    // Exists: Website 
    function osc_validate_url ($value, $required = false) {
        if ($required || strlen($value) > 0) {
            $value = osc_sanitize_url($value);
            if ( filter_var($value, FILTER_VALIDATE_URL) ) {
                @$headers = get_headers($value); 
                if (!preg_match('/^HTTP\/\d\.\d\s+(200|301|302)/', $headers[0])) {
                    return false;
                }
            } else {
                return false;
            }
        }
        return true;
    }


    // Validate time between two items added/comments
    function osc_validate_spam_delay($type = 'item') {
        if ($type == 'item') {
            $delay = osc_item_spam_delay();
            $saved_as = 'last_submit_item';
        } else {
            $delay = osc_comment_spam_delay();
            $saved_as = 'last_submit_comment';
        }
        // check $_SESSION
        if ((Session::newInstance()->_get($saved_as)+$delay) > time() ||
            (Cookie::newInstance()->get_value($saved_as)+$delay) > time()) {
                return false;
        }
        return true;
    }



    /**
    Validate an email address
    Source: http://www.linuxjournal.com/article/9585?page=0,3
    */
    function osc_validate_email ($email, $required = true) {
        if ($required || strlen($email) > 0) {
            $atIndex = strrpos($email, "@");
            if (is_bool($atIndex) && !$atIndex) {
                return false;
            } else {
                $domain = substr($email, $atIndex+1);
                $local = substr($email, 0, $atIndex);
                $localLen = strlen($local);
                $domainLen = strlen($domain);

                if ($localLen < 1 || $localLen > 64) {
                    return false;
			    } else if ($domainLen < 1 || $domainLen > 255) {
                    return false;
			    } else if ($local[0] == '.' || $local[$localLen-1] == '.') {
                    return false;
			    } else if (preg_match('/\\.\\./', $local)) {
                    return false;
			    } else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
                    return false;
			    } else if (preg_match('/\\.\\./', $domain)) {
                    return false;
			    } else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&amp;`_=\\/$\'*+?^{}|~.-])+$/',
                            str_replace("\\\\","",$local))) {
                    if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local))) {
                        return false;
                    }
                } 

                if (!(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))) {
                    return false;
                }
            }
            return true;
        }
        return true;
    }
?>
