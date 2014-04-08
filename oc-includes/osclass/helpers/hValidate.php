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
    * Helper Validation
    * @package Osclass
    * @subpackage Helpers
    * @author Osclass
    */

    /**
     * Validate the text with a minimum of non-punctuation characters (international)
     *
     * @param string $value
     * @param integer $count
     * @param boolean $required
     * @return boolean
     */
    function osc_validate_text ($value = '', $count = 1, $required = true) {
        if ($required || $value) {
            if ( !preg_match("/([\p{L}\p{N}]){".$count."}/iu", strip_tags($value)) ) {
                return false;
            }
        }
        return true;
    }

    /**
     * Validate one or more numbers (no periods)
     *
     * @param string $value
     * @return boolean
     */
    function osc_validate_int ($value) {
        if ( preg_match("/^[0-9]+$/", $value) ) {
            return true;
        }
        return false;
    }

    /**
     * Validate one or more numbers (no periods), must be more than 0.
     *
     * @param string $value
     * @return boolean
     */
    function osc_validate_nozero ($value) {
        if ( preg_match("/^[0-9]+$/", $value) && $value>0 ) {
            return true;
        }
        return false;
    }

    /**
     * Validate $value is a number or a numeric string
     *
     * @param string $value
     * @param boolean $required
     * @return boolean
     */
    function osc_validate_number ($value = null, $required = false) {
        if ($required || strlen($value) > 0) {
            if ( !is_numeric($value) ) {
                return false;
            }
        }
        return true;
    }

    /**
     * Validate $value is a number phone,
     * with $count lenght
     *
     * @param string $value
     * @param int $count
     * @param boolean $required
     * @return boolean
     */
    function osc_validate_phone ($value = null, $count = 10, $required = false) {
        if ($required || mb_strlen($value, 'UTF-8') > 0) {
            if ( !preg_match("/([\p{Nd}][^\p{Nd}]*){".$count."}/i", strip_tags($value)) ) {
                return false;
            }
        }
        return true;
    }

    /**
     * Validate if $value is more than $min
     *
     * @param string $value
     * @param int $min
     * @return boolean
     */
    function osc_validate_min ($value = null, $min = 6) {
        if ( mb_strlen($value, 'UTF-8') < $min ) {
            return false;
        }
        return true;
    }

    /**
     * Validate if $value is less than $max
     * @param string $value
     * @param int $max
     * @return boolean
     */
    function osc_validate_max ($value = null, $max = 255) {
        if ( mb_strlen($value, 'UTF-8') > $max ) {
            return false;
        }
        return true;
    }

    /**
     * Validate if $value belongs at range between min to max
     * @param string $value
     * @param int $min
     * @param int $max
     * @return boolean
     */
    function osc_validate_range ($value, $min = 6, $max = 255) {
        if ( mb_strlen($value, 'UTF-8')>=$min && mb_strlen($value, 'UTF-8')<=$max ) {
            return true;
        }
        return false;
    }

    /**
     * Validate if exist $city, $region, $country in db
     *
     * @param string $city
     * @param string $region
     * @param string $country
     * @return boolean
     */
    function osc_validate_location ($city,$sCity,$region,$sRegion,$country,$sCountry) {
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
        } else if(osc_validate_nozero($region) && osc_validate_text($country,2) && $sCity != "" ) {
            return true;
        } else if($sRegion != "" && osc_validate_text($country,2) && $sCity != "" ) {
            return true;
        } else if($sRegion != "" && $sCountry != "" && $sCity != "" ){
            return true;
        }
        return false;
    }

    /**
     * Validate if exist category $value and is enabled in db
     *
     * @param string $value
     * @return boolean
     */
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

    /**
     * Validate if $value url is a valid url.
     * Check header response to validate.
     *
     * @param string $value
     * @param boolean $required
     * @return boolean
     */
    function osc_validate_url ($value, $required = false) {
        if ($required || mb_strlen($value, 'UTF-8') > 0) {
            $value = osc_sanitize_url($value);
            if(!function_exists('filter_var')) {
                $success = preg_match('|^(http\:\/\/[a-zA-Z0-9_\-]+(?:\.[a-zA-Z0-9_\-]+)*\.[a-zA-Z]{2,4}(?:\/[a-zA-Z0-9_]+)*(?:\/[a-zA-Z0-9_]+\.[a-zA-Z]{2,4}(?:\?[a-zA-Z0-9_]+\=[a-zA-Z0-9_]+)?)?(?:\&[a-zA-Z0-9_]+\=[a-zA-Z0-9_]+)*)$|', $value, $m);
            } else {
                $success = filter_var($value, FILTER_VALIDATE_URL);
            }
            if ($success) {
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

    /**
     * Validate time between two items added/comments
     *
     * @param string $type
     * @return boolean
     */
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
     * Validate an email address
     * Source: http://www.linuxjournal.com/article/9585?page=0,3
     *
     * @param string $email
     * @param boolean $required
     * @return boolean
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
                } else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&amp;`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local))) {
                    if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local))) {
                        return false;
                    }
                }
                return true;
            }
        }
        return true;
    }

    /**
     * validate username, accept letters plus underline, without separators
     *
     * @param $value
     * @param $min
     */
    function osc_validate_username( $value, $min = 1 ) {
        if(mb_strlen($value, 'UTF-8') >= $min && preg_match('/^[A-Za-z0-9_]+$/',$value) ){
            return true;
        } else {
            return false;
        }
    }


?>
