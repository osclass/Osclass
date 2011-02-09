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

     class WebThemes {

        private static $instance ;
        private $theme ;
        private $theme_url ;
        private $theme_path ;

        public static function newInstance() {
            if(!self::$instance instanceof self) {
                self::$instance = new self ;
            }
            return self::$instance ;
        }

        public function __construct() {
            $this->theme = null ;
            $this->theme_url = null ;
            $this->theme_path = null ;
        }

        /* PRIVATE */
        private function setCurrentThemeUrl() {
            if(is_null($this->theme)) return false ;
            $this->theme_url = osc_base_url() . 'oc-content/themes/' . $this->theme . '/' ;
        }

        private function setCurrentThemePath() {
            if(is_null($this->theme)) return false ;
            $this->theme_path = osc_base_path() . 'oc-content/themes/' . $this->theme . '/' ; //XXX: must take data from defined global var.
        }

        /* PUBLIC */
        public function setCurrentTheme($theme) {
            $this->theme = $theme ;
            $this->setCurrentThemeUrl() ;
            $this->setCurrentThemePath() ;
        }

        public function getCurrentTheme() {
            return $this->theme ;
        }

        public function getCurrentThemeUrl() {
            return $this->theme_url ;
        }

        public function getCurrentThemePath() {
            return $this->theme_path ;
        }

        public function getCurrentThemeStyles() {
            return $this->theme_url . 'styles/' ;
        }

        public function getCurrentThemeJs() {
            return $this->theme_url . 'js/' ;
        }
    }





/**
 * @return true if the item has uploaded a thumbnail.
 */
/*
function osc_item_has_thumbnail($item) {
    $conn = getConnection() ;
    $resource = $conn->osc_dbFetchResult('SELECT * FROM %st_item_resource WHERE fk_i_item_id = %d', DB_TABLE_PREFIX, $item['pk_i_id']) ;
    return!is_null($resource) ;
}*/

/**
 * Returns the URL to the thumbnail of the item passed by paramater.
 */
/*function osc_create_item_thumbnail_url($item) {
    $conn = getConnection() ;
    $resource = $conn->osc_dbFetchResult('SELECT * FROM %st_item_resource WHERE fk_i_item_id = %d', DB_TABLE_PREFIX, $item['pk_i_id']) ;
    echo osc_createThumbnailURL($resource) ;
}*/

/**
 * Formats the price using the appropiate currency.
 */
/*function osc_format_price($item) {
    if (!isset($item['f_price']))
        return __('Consult') ;

    if ($item['f_price'] == 0)
        return __('Free') ;

    if (!empty($item['f_price']))
        return sprintf('%.02f %s', $item['f_price'], $item['fk_c_currency_code']) ;

    return __('Consult') ;
}*/

/**
 * Formats the date using the appropiate format.
 */
/*function osc_formatDate($item) {
    $date = strtotime($item['dt_pub_date']) ;
    return date(osc_date_format(), $date) ;
}*/

/*function osc_page_info($property, $echo = false) {
    global $headerConf ;
    $conf = array(
        'pageTitle' => osc_page_title()
    );
    if (isset($headerConf))
        $conf = array_merge($conf, $headerConf) ;

    if (!isset($conf[$property])) {
        return '' ;
    }

    if($echo) {
        echo $conf[$property] ;
        return '' ;
    }

    return $conf[$property] ;
}*/

/*function osc_theme_resource($fileName, $echo = false) {
    $themePath = THEMES_PATH . osc_theme() . '/' . $fileName ;
    $path = '' ;
    if (file_exists($themePath)) {
        $path = osc_base_url() . 'oc-content/themes/' . osc_theme() . '/' . $fileName ;
    } else {
        $path =  osc_base_url() . 'oc-includes/osclass/gui/' . $fileName ;
    }

    if($echo) {
        echo $path ;
        return '' ;
    }
    return $path ;
}*/

/*function osc_show_widgets($location) {
    $widgets = Widget::newInstance()->findByLocation($location);
    foreach ($widgets as $w)
        echo $w['s_content'] ;
}*/

/**
 * Create automatically the url of a page
 *
 * @param array $page An array with the page information
 * @param bool $echo If you want to echo or not the path automatically
 * @return string If $echo is false, it returns the path, if not it return blank string
 */
/*function osc_createPageURL($page, $echo = false) {
    $path = '' ;

    if (osc_rewrite_enabled()) {
        $sanitizedString = osc_sanitizeString($page['s_title']);
        $path = sprintf(osc_base_url() . '%s-p%d', urlencode($sanitizedString), $page['pk_i_id']);
    } else {
        $path = sprintf(osc_base_url() . 'page.php?id=%d', $page['pk_i_id']);
    }

    if($echo) {
        echo $path;
        return '';
    }

    return $path;
}

function osc_createLoginURL() {
    if (osc_rewrite_enabled()) {
        return osc_base_url() . 'user/login';
    } else
        return osc_base_url() . 'user.php?action=login';
}

function osc_indexURL() {
    return osc_base_url() ;
}

function osc_createUserAccountURL() {
    if (osc_rewrite_enabled()) {
        return osc_base_url() . 'user/account';
    } else
        return osc_base_url() . 'user.php?action=account';
}

function osc_createUserAlertsURL() {
    if (osc_rewrite_enabled()) {
        return osc_base_url() . 'user/alerts';
    } else
        return osc_base_url() . 'user.php?action=alerts';
}

function osc_createLogoutURL() {
    if (osc_rewrite_enabled()) {
        return osc_base_url() . 'user/logout';
    } else
        return osc_base_url() . 'user.php?action=logout';
}

function osc_createSearchURL($pattern) {
    if (osc_rewrite_enabled()) {
        return osc_base_url() . 'search/' . $pattern;
    } else
        return osc_base_url() . 'search.php?sPattern=' . $pattern;
}

function osc_createProfileURL() {
    if (osc_rewrite_enabled()) {
        return osc_base_url() . 'user/profile';
    } else
        return osc_base_url() . 'user.php?action=profile';
}

function osc_createRegisterURL() {
    if (osc_rewrite_enabled()) {
        return osc_base_url() . 'user/register';
    } else
        return osc_base_url() . 'user.php?action=register';
}

function osc_createUserItemsURL() {
    if (osc_rewrite_enabled()) {
        return osc_base_url() . 'user/items';
    } else
        return osc_base_url() . 'user.php?action=items';
}

function osc_createUserOptionsURL($option = null) {
    if (osc_rewrite_enabled()) {
        if($option != null) {
            return osc_base_url() . 'user/options/'.$option;
        } else {
            return osc_base_url() . 'user/options';
        }
    } else {
        if($option != null) {
            return osc_base_url() . 'user.php?action=options&option='.$option;
        } else {
            return osc_base_url() . 'user.php?action=options';
        }
    }
}

function osc_createUserOptionsPostURL($option = null) {
    if (osc_rewrite_enabled()) {
        if($option != null) {
            return osc_base_url() . 'user/options_post/' . $option ;
        } else {
            return osc_base_url() . 'user/options_post';
        }
    } else {
        if($option != null) {
            return osc_base_url() . 'user.php?action=options_post&option=' . $option ;
        } else {
            return osc_base_url() . 'user.php?action=options_post' ;
        }
    }
}

function osc_create_url($params = null, $echo = false) {
    $path = '';
    if(!is_array($params)) {
        return '';
    }

    if(count($params) == 0) {
        return '';
    }

    if(!isset($params['file'])) {
        return '';
    }

    if (osc_rewrite_enabled()) {
        if($params['file'] == 'index') {
            $params['file'] = '';
            $path = osc_base_url() . $params['action'];
        } else {
            if(count($params) == 2 && isset($params['action'])) {
                $path = osc_base_url() . $params['file'] . "/" . $params['action'];
            } else {
                $path = osc_base_url() . $params['file'] . "?" . $params_string;
            }
        }
    } else {
        $params_string = "";
        foreach ($params as $k => $v) {
            if ($k != 'file') {
                $params_string .= $k . '=' . $v . '&';
            }
        }
        $params_string = preg_replace('/\&$/','',$params_string);
        $path = osc_base_url() . $params['file'] . ".php?" . $params_string;
    }

    if($echo) {
        echo $path;
        return '';
    }

    return $path;
}

function osc_createThumbnailURL($resource) {
    if(isset($resource['pk_i_id'])) {
        return sprintf(osc_base_url() . 'oc-content/uploads/%d_thumbnail.png', $resource['pk_i_id']) ;
    } else {
        return osc_theme_resource('images/no-image.png') ;
    }
}

function osc_createResourceURL($resource) {
    return sprintf(osc_base_url() . 'oc-content/uploads/%d.png', $resource['pk_i_id']) ;
}

function osc_createItemPostURL($cat = null) {
    if (!isset($cat) || !isset($cat['pk_i_id'])) {
        if (osc_rewrite_enabled()) {
            return osc_base_url() . 'item/new' ;
        } else {
            return sprintf(osc_base_url() . 'item.php?action=post') ;
        }
    } else {
        if (osc_rewrite_enabled()) {
            return osc_base_url() . 'item/new/' . $cat['pk_i_id'] ;
        } else {
            return sprintf(osc_base_url() . 'item.php?action=post&catId=%d', $cat['pk_i_id']) ;
        }
    }
}*/

/**
 * Create automatically the url of a category
 *
 * @param array $cat An array with the category information
 * @param bool $echo If you want to echo or not the path automatically
 * @return string If $echo is false, it returns the path, if not it return blank string
 */
/*function osc_createCategoryURL($cat, $echo = false) {
    $path = '';

    if (osc_rewrite_enabled()) {
        $cat = Category::newInstance()->hierarchy($cat['pk_i_id']) ;
        $sanitized_category = "" ;
        for ($i = count($cat); $i > 0; $i--) {
            $sanitized_category .= $cat[$i - 1]['s_slug'] . '/' ;
        }
        $path = osc_base_url() . $sanitized_category ;
    } else {
        $path = sprintf(osc_base_url() . 'search.php?sCategory=%d', $cat['pk_i_id']) ;
    }

    if($echo) {
        echo $path ;
        return '';
    }

    return $path ;
}*/

/**
 * Create automatically the url of an item
 *
 * @param array $item An array with the item information
 * @param bool $echo If you want to echo or not the path automatically
 * @return string If $echo is false, it returns the path, if not it return blank string
 */
/*function osc_create_item_url($item, $echo = false) {
    $path = '' ;
    
    if (osc_rewrite_enabled()) {
        $sanitized_title = osc_sanitizeString($item['s_title']) ;
        $sanitized_category = '' ;
        $cat = Category::newInstance()->hierarchy($item['fk_i_category_id']) ;
        for ($i = (count($cat)); $i > 0; $i--) {
            $sanitized_category .= $cat[$i - 1]['s_slug'] . '/' ;
        }
        $path = sprintf(osc_base_url() . '%s%s_%d', $sanitized_category, $sanitized_title, $item['pk_i_id']) ;
    } else {
        $path = sprintf(osc_base_url() . 'item.php?id=%d', $item['pk_i_id']) ;
    }

    if($echo) {
        echo $path ;
        return '' ;
    }

    return $path ;
}

function osc_createUserPublicDashboard($user = null) {
    if ($user != null || !isset($user['pk_i_id'])) {
        if (osc_rewrite_enabled()) {
            return osc_base_url() . 'user/'.$user['pk_i_id'] ;
        } else {
            return osc_base_url() . 'user.php?action=public&user='.$user['pk_i_id'] ;
        }
    }
}*/

/**
 * Prints the user's account menu
 *
 * @param array with options of the form array('name' => 'display name', 'url' => 'url of link')
 * 
 * @return void
 */
/* function nav_user_menu($options = null) {
    if($options == null) {
        $options = array();
        $options[] = array('name' => __('Dashboard'), 'url' => osc_createUserAccountURL()) ;
        $options[] = array('name' => __('Manage your items'), 'url' => osc_createUserItemsURL()) ;
        $options[] = array('name' => __('Manage your alerts'), 'url' => osc_createUserAlertsURL()) ;
        $options[] = array('name' => __('My account'), 'url' => osc_createProfileURL()) ;
        $options[] = array('name' => __('Logout'), 'url' => osc_createLogoutURL()) ;
    } 

    <script type="text/javascript">
        $(".user_menu > :first-child").addClass("first");
        $(".user_menu > :last-child").addClass("last");
    </script>
    <ul class="user_menu">
        
            $var_l = count($options) ;
            for($var_o=0;$var_o<$var_l;$var_o++) {
                echo '<li><a href="' . $options[$var_o]['url'] . '" >' . $options[$var_o]['name'] . '</a></li>' ;
            }

            osc_run_hook('user_menu');
       
    </ul>
} */


/**
 * Prints the aditional options to the menu
 *
 * @param array with options of the form array('name' => 'display name', 'url' => 'url of link')
 * 
 * @return void
 */
/*function add_option_menu($option = null) {
    if($option!=null) {
        echo '<li><a href="' . $option['url'] . '" >' . $option['name'] . '</a></li>' ;
    }
    
}*/


/**
 * This function returns an array of themes (those copied in the oc-content/themes folder)
 */
/*function osc_listThemes() {
    $themes = array();
    $dir = opendir(ABS_PATH . 'oc-content/themes');
    while ($file = readdir($dir)) {
        if (preg_match('/^[a-z0-9_]+$/i', $file))
            $themes[] = $file;
    }
    closedir($dir);
    return $themes;
}

function osc_loadThemeInfo($theme) {
    $path = ABS_PATH . 'oc-content/themes/' . $theme . '/index.php';
    if (!file_exists($path))
        return false;
    require_once $path;

    $fxName = $theme . '_theme_info';
    if (!function_exists($fxName))
        return false;
    $result = call_user_func($fxName);

    $result['int_name'] = $theme;

    return $result;
}*/

/**
 * This function renders the page header
 */
/*function osc_renderHeader($headerConf = array()) {
    global $categories ;
    $themePath = THEMES_PATH . osc_theme() . '/header.php' ;
    if (file_exists($themePath)) {
        require_once $themePath ;
    }
}*/

/**
 * This function renders the page footer
 */
/*function osc_renderFooter() {
    $themePath = THEMES_PATH . osc_theme() . '/footer.php' ;
    if (file_exists($themePath)) {
        require_once $themePath ;
    }
}*/

/**
 * This functions tries to render a view from the current theme, or using the generic UI if the former does not exist.
 */
/*function osc_renderView($name) {
    extract($GLOBALS);
    $themePath = THEMES_PATH . osc_theme() . '/' . $name ;
    if (file_exists($themePath)) {
        require_once $themePath ;
    } else {
        $defaultPath = LIB_PATH . 'osclass/gui/' . $name ;
        if (file_exists($defaultPath)) {
            require_once $defaultPath ;
        } else {
            trigger_error("The view '$name' was not found.") ;
        }
    }
}*/

?>