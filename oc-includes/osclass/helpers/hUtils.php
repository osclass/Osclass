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
 * Helper Utils
 * @package Osclass
 * @subpackage Helpers
 * @author Osclass
 */

/**
 * Getting from View the $key index
 *
 * @param string $key
 * @return array
 */
function __get($key) {
    return View::newInstance()->_get($key);
}

/**
 * Get variable from $_GET or $_POST
 *
 * @param string $key
 * @return mixed
 */
function osc_get_param($key) {
    return Params::getParam($key);
}

/**
 * Generic function for view layer, return the $field of $item
 * with specific $locale
 *
 * @param array $item
 * @param string $field
 * @param string $locale
 * @return string
 */
function osc_field($item, $field, $locale) {
    if(!is_null($item)) {
        if($locale == "") {
            if(isset($item[$field])) {
                return $item[$field];
            }
        } else {
            if(isset($item["locale"]) && !empty($item['locale']) && isset($item["locale"][$locale]) && isset($item["locale"][$locale][$field])) {
                return $item["locale"][$locale][$field];
            }else{
                if(isset($item["locale"])){
                    foreach($item["locale"] as $locale => $data) {
                        if( isset($item["locale"][$locale][$field] ) ) {
                            return $item["locale"][$locale][$field];
                        }
                    }
                }
            }
        }
    }
    return '';
}

/**
 * Print all widgets belonging to $location
 *
 * @param string $location
 * @return void
 */
function osc_show_widgets($location) {
    $widgets = Widget::newInstance()->findByLocation($location);
    foreach ($widgets as $w)
        echo $w['s_content'];
}

/**
 * Print all widgets named $description
 *
 * @param string $description
 * @return void
 */
function osc_show_widgets_by_description($description) {
    $widgets = Widget::newInstance()->findByDescription($description);
    foreach ($widgets as $w)
        echo $w['s_content'];
}

/**
 * Print recaptcha html, if $section = "recover_password"
 * set 'recover_time' at session.
 *
 * @param  string $section
 * @return void
 */
function osc_show_recaptcha($section = '') {
    if( osc_recaptcha_public_key() ) {
        if(osc_recaptcha_version()=="2") {
            switch($section) {
                case('recover_password'):
                    Session::newInstance()->_set('recover_captcha_not_set',0);
                    $time  = Session::newInstance()->_get('recover_time');
                    if((time()-$time)<=1200) {
                        echo _osc_recaptcha_get_html(osc_recaptcha_public_key(), substr(osc_language(), 0, 2))."<br />";
                    }
                    else{
                        Session::newInstance()->_set('recover_captcha_not_set',1);
                    }
                    break;

                default:
                    echo _osc_recaptcha_get_html(osc_recaptcha_public_key(), substr(osc_language(), 0, 2))."<br />";
                    break;
            }
        } else {
            require_once osc_lib_path() . 'recaptchalib.php';
            switch($section) {
                case('recover_password'):
                    $time  = Session::newInstance()->_get('recover_time');
                    if((time()-$time)<=1200) {
                        echo recaptcha_get_html( osc_recaptcha_public_key(), null, osc_is_ssl() )."<br />";
                    }
                    break;

                default:
                    echo recaptcha_get_html( osc_recaptcha_public_key(), null, osc_is_ssl() );
                    break;
            }
        }
    }
}

function _osc_recaptcha_get_html($siteKey, $lang) {
    echo '<div class="g-recaptcha" data-sitekey="' . $siteKey . '"></div>';
    echo '<script type="text/javascript" src="https://www.google.com/recaptcha/api.js?hl=' . $lang . '"></script>';
}

/**
 * Formats the date using the appropiate format.
 *
 * @param string $date
 * @return string
 */
function osc_format_date($date, $dateformat = null) {
    if($dateformat==null) {
        $dateformat = osc_date_format();
    }

    $month = array('', __('January'), __('February'), __('March'), __('April'), __('May'), __('June'), __('July'), __('August'), __('September'), __('October'), __('November'), __('December'));
    $month_short = array('', __('Jan'), __('Feb'), __('Mar'), __('Apr'), __('May'), __('Jun'), __('Jul'), __('Aug'), __('Sep'), __('Oct'), __('Nov'), __('Dec'));
    $day = array('', __('Monday'), __('Tuesday'), __('Wednesday'), __('Thursday'), __('Friday'), __('Saturday'), __('Sunday'));
    $day_short = array('', __('Mon'), __('Tue'), __('Wed'), __('Thu'), __('Fri'), __('Sat'), __('Sun'));
    $ampm = array('AM' => __('AM'), 'PM' => __('PM'), 'am' => __('am'), 'pm' => __('pm'));


    $time = strtotime($date);
    $dateformat = preg_replace('|(?<!\\\)F|', osc_escape_string($month[date('n', $time)]), $dateformat);
    $dateformat = preg_replace('|(?<!\\\)M|', osc_escape_string($month_short[date('n', $time)]), $dateformat);
    $dateformat = preg_replace('|(?<!\\\)l|', osc_escape_string($day[date('N', $time)]), $dateformat);
    $dateformat = preg_replace('|(?<!\\\)D|', osc_escape_string($day_short[date('N', $time)]), $dateformat);
    $dateformat = preg_replace('|(?<!\\\)A|', osc_escape_string($ampm[date('A', $time)]), $dateformat);
    $dateformat = preg_replace('|(?<!\\\)a|', osc_escape_string($ampm[date('a', $time)]), $dateformat);
    return date($dateformat, $time);
}


/**
 * Escapes letters and numbers of a string
 *
 * @since 2.4
 * @param string $string
 * @return string
 */
function osc_escape_string($string) {
    $string = preg_replace('/^([0-9])/', '\\\\\\\\\1', $string);
    $string = preg_replace('/([a-z])/i', '\\\\\1', $string);
    return $string;
}

/**
 * Prints the user's account menu
 *
 * @param array $options array with options of the form array('name' => 'display name', 'url' => 'url of link')
 * @return void
 */
function osc_private_user_menu($options = null)
{
    if($options == null) {
        $options = array();
        $options[] = array('name' => __('Public Profile'), 'url' => osc_user_public_profile_url(osc_logged_user_id()), 'class' => 'opt_publicprofile');
        $options[] = array('name' => __('Dashboard'), 'url' => osc_user_dashboard_url(), 'class' => 'opt_dashboard');
        $options[] = array('name' => __('Manage your listings'), 'url' => osc_user_list_items_url(), 'class' => 'opt_items');
        $options[] = array('name' => __('Manage your alerts'), 'url' => osc_user_alerts_url(), 'class' => 'opt_alerts');
        $options[] = array('name' => __('My profile'), 'url' => osc_user_profile_url(), 'class' => 'opt_account');
        $options[] = array('name' => __('Logout'), 'url' => osc_user_logout_url(), 'class' => 'opt_logout');
    }

    $options = osc_apply_filter('user_menu_filter', $options);

    echo '<script type="text/javascript">';
    echo '$(".user_menu > :first-child").addClass("first");';
    echo '$(".user_menu > :last-child").addClass("last");';
    echo '</script>';
    echo '<ul class="user_menu">';

    $var_l = count($options);
    for($var_o = 0; $var_o < ($var_l-1); $var_o++) {
        echo '<li class="' . $options[$var_o]['class'] . '" ><a href="' . $options[$var_o]['url'] . '" >' . $options[$var_o]['name'] . '</a></li>';
    }

    osc_run_hook('user_menu');

    echo '<li class="' . $options[$var_l-1]['class'] . '" ><a href="' . $options[$var_l-1]['url'] . '" >' . $options[$var_l-1]['name'] . '</a></li>';

    echo '</ul>';
}

/**
 * Gets prepared text, with:
 * - higlight search pattern and search city
 * - maxim length of text
 *
 * @param string $txt
 * @param int  $len
 * @param string $start_tag
 * @param string $end_tag
 * @return string
 */
function osc_highlight($txt, $len = 300, $start_tag = '<strong>', $end_tag = '</strong>') {
    $txt = strip_tags($txt);
    $txt = str_replace(array("\n\r","\r\n","\n","\r","\t"), ' ', $txt);
    $txt = trim($txt);
    $txt = preg_replace('/\s+/', ' ', $txt);
    if( mb_strlen($txt, 'UTF-8') > $len ) {
        $txt = mb_substr($txt, 0, $len, 'UTF-8') . "...";
    }
    $query = osc_search_pattern();
    $query = str_replace(array('(',')','+','-','~','>','<'), array('','','','','','',''), $query);

    $query = str_replace(
        array('\\', '^', '$', '.', '[', '|', '?', '*', '{', '}', '/', ']'),
        array('\\\\', '\\^', '\\$', '\\.', '\\[', '\\|', '\\?', '\\*', '\\{', '\\}', '\\/', '\\]'),
        $query);

    $query = preg_replace('/\s+/', ' ', $query);

    $words = array();
    if(preg_match_all('/"([^"]*)"/', $query, $matches)) {
        $l = count($matches[1]);
        for($k=0;$k<$l;$k++) {
            $words[] = $matches[1][$k];
        }
    }

    $query = trim(preg_replace('/\s+/', ' ', preg_replace('/"([^"]*)"/', '', $query)));
    $words = array_merge($words, explode(" ", $query));

    foreach($words as $word) {
        if($word!='') {
            $txt = preg_replace("/(\PL|\s+|^)($word)(\PL|\s+|$)/i", "$01" . $start_tag . "$02". $end_tag . "$03", $txt);
        }
    }
    return $txt;
}


/**
 *
 */
function osc_get_http_referer() {
    $ref = Rewrite::newInstance()->get_http_referer();
    if($ref!='') {
        return $ref;
    } else if(Session::newInstance()->_getReferer()!='') {
        return Session::newInstance()->_getReferer();
    } else if(Params::existServerParam('HTTP_REFERER')){
        if(filter_var(Params::getServerParam('HTTP_REFERER', false, false), FILTER_VALIDATE_URL)) {
            return Params::getServerParam('HTTP_REFERER', false, false);
        }
    }
    return '';
}

function osc_add_route($id, $regexp, $url, $file, $user_menu = false, $location = "custom", $section = "custom", $title = "Custom") {
    Rewrite::newInstance()->addRoute($id, $regexp, $url, $file, $user_menu, $location, $section, $title);
}

/**
 *
 */
function osc_get_subdomain_params() {
    $options = array();
    if(osc_subdomain_name()!='') {
        if(Params::getParam('sCountry')!='') {
            $options['sCountry'] = Params::getParam('sCountry');
        }
        if(Params::getParam('sRegion')!='') {
            $options['sRegion'] = Params::getParam('sRegion');
        }
        if(Params::getParam('sCity')!='') {
            $options['sCity'] = Params::getParam('sCity');
        }
        if(Params::getParam('sCategory')!='') {
            $options['sCategory'] = Params::getParam('sCategory');
        }
        if(Params::getParam('sUser')!='') {
            $options['sUser'] = Params::getParam('sUser');
        }
    }
    return $options;
}


?>
