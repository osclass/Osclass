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

    /**
     * Helper Utils
     * @package OSClass
     * @subpackage Helpers
     * @author OSClass
     */
     
    /**
     * Getting from View the $key index
     *
     * @param string $key
     * @return array
     */
     function __get($key) {
         return View::newInstance()->_get($key) ;
     }

    /**
     * Get variable from $_REQUEST[$key]
     *
     * @param string $key
     * @return mixed
     */
    function osc_get_param($key) {
        return Params::getParam($key) ;
    }

    /**
     * Generic function for view layer, return the $field of $item
     * with specific $locate
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
                    return $item[$field] ;
                }
            } else {
                if(isset($item["locale"]) && !empty($item['locale']) && isset($item["locale"][$locale]) && isset($item["locale"][$locale][$field])) {
                    return $item["locale"][$locale][$field] ;
                }else{
                    if(isset($item["locale"])){
                        foreach($item["locale"] as $locale => $data) {
                            if( isset($item["locale"][$locale][$field] ) ) {
                                return $item["locale"][$locale][$field] ;
                            }
                        }
                    }
                }
            }
        }
        return '' ;
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
            echo $w['s_content'] ;
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
            require_once osc_lib_path() . 'recaptchalib.php' ;
            switch($section) {
                case('recover_password'):
                    $time  = Session::newInstance()->_get('recover_time');
                    if((time()-$time)<=1200) {
                       echo recaptcha_get_html( osc_recaptcha_public_key() )."<br />" ;
                    }
                break;
                
                default:
                   echo recaptcha_get_html( osc_recaptcha_public_key() ) ;
                break;
            }
        }
    }

    /**
     * Formats the date using the appropiate format.
     *
     * @param string $date
     */
    function osc_format_date($date) {
        //$date = strtotime($item['dt_pub_date']) ;
        return date(osc_date_format(), strtotime($date)) ;
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
            $options[] = array('name' => __('Dashboard'), 'url' => osc_user_dashboard_url(), 'class' => 'opt_dashboard') ;
            $options[] = array('name' => __('Manage your items'), 'url' => osc_user_list_items_url(), 'class' => 'opt_items') ;
            $options[] = array('name' => __('Manage your alerts'), 'url' => osc_user_alerts_url(), 'class' => 'opt_alerts') ;
            $options[] = array('name' => __('My account'), 'url' => osc_user_profile_url(), 'class' => 'opt_account') ;
            $options[] = array('name' => __('Logout'), 'url' => osc_user_logout_url(), 'class' => 'opt_logout') ;
        }

        echo '<script type="text/javascript">' ;
            echo '$(".user_menu > :first-child").addClass("first") ;' ;
            echo '$(".user_menu > :last-child").addClass("last") ;' ;
        echo '</script>' ;
        echo '<ul class="user_menu">' ;

            $var_l = count($options) ;
            for($var_o = 0 ; $var_o < ($var_l-1) ; $var_o++) {
                echo '<li class="' . $options[$var_o]['class'] . '" ><a href="' . $options[$var_o]['url'] . '" >' . $options[$var_o]['name'] . '</a></li>' ;
            }

            osc_run_hook('user_menu') ;
            
            echo '<li class="' . $options[$var_l-1]['class'] . '" ><a href="' . $options[$var_l-1]['url'] . '" >' . $options[$var_l-1]['name'] . '</a></li>' ;

        echo '</ul>' ;
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

        if (strlen($txt) > $len) {
            $txt = mb_substr($txt, 0, $len, 'utf-8') . "..." ;
        }

        $query = osc_search_pattern() . " " . osc_search_city() ;
        $query = trim(preg_replace('/\s\s+/', ' ', $query)) ;
        $aQuery = explode(' ', $query) ;
        foreach ($aQuery as $word) {
            $txt = str_replace($word, $start_tag . $word. $end_tag, $txt) ;
        }
        return $txt ;
    }

?>
