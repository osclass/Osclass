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

/**
 * @return true if the item has uploaded a thumbnail.
 */
function osc_itemHasThumbnail($item) {
    $conn = getConnection() ;
    $resource = $conn->osc_dbFetchResult('SELECT * FROM %st_item_resource WHERE fk_i_item_id = %d', DB_TABLE_PREFIX, $item['pk_i_id']) ;
    return!is_null($resource) ;
}

/**
 * Returns the URL to the thumbnail of the item passed by paramater.
 */
function osc_itemThumbnail($item) {
    $conn = getConnection() ;
    $resource = $conn->osc_dbFetchResult('SELECT * FROM %st_item_resource WHERE fk_i_item_id = %d', DB_TABLE_PREFIX, $item['pk_i_id']) ;
    echo osc_createThumbnailURL($resource) ;
}

/**
 * Formats the price using the appropiate currency.
 */
function osc_formatPrice($item) {
    if (!isset($item['f_price']))
        return __('Consult') ;

    if ($item['f_price'] == 0)
        return __('Free') ;

    if (!empty($item['f_price']))
        return sprintf('%.02f %s', $item['f_price'], $item['fk_c_currency_code']) ;

    return __('Consult') ;
}

/**
 * Formats the date using the appropiate format.
 */
function osc_formatDate($item) {
    global $preferences ;
    $date = strtotime($item['dt_pub_date']) ;
    return date($preferences['dateFormat'], $date) ;
}

function osc_pageInfo($property, $echo = false) {
    global $preferences, $headerConf ;
    $conf = array(
        'pageTitle' => $preferences['pageTitle']
    );
    if (isset($headerConf))
        $conf = array_merge($conf, $headerConf) ;

    if (!isset($conf[$property])) {
        return '';
    }

    if($echo) {
        echo $conf[$property];
        return '';
    }

    return $conf[$property] ;
}

function osc_themeResource($fileName, $echo = false) {
    global $preferences;
    $themePath = THEMES_PATH . $preferences['theme'] . '/' . $fileName;
    $path = '';
    if (file_exists($themePath)) {
        $path = ABS_WEB_URL . 'oc-content/themes/' . $preferences['theme'] . '/' . $fileName;
    } else {
        $path =  ABS_WEB_URL . 'oc-includes/osclass/gui/' . $fileName;
    }

    if($echo) {
        echo $path;
        return '';
    }
    return $path;
}

function osc_showWidgets($location) {
    $widgets = Widget::newInstance()->findByLocation($location);
    foreach ($widgets as $w)
        echo $w['s_content'];
}

function osc_createPageURL($page, $echo = false) {
    global $preferences;
    $path = '';
    if (isset($preferences['rewriteEnabled']) && $preferences['rewriteEnabled']) {
        $sanitizedString = osc_sanitizeString($page['s_title']);
        $path = sprintf(ABS_WEB_URL . '%s-p%d', urlencode($sanitizedString), $page['pk_i_id']);
    } else {
        $path = sprintf(ABS_WEB_URL . 'page.php?id=%d', $page['pk_i_id']);
    }

    if($echo) {
        echo $path;
        return '';
    }

    return $path;
}

function osc_createLoginURL() {
    global $preferences;
    if (isset($preferences['rewriteEnabled']) && $preferences['rewriteEnabled']) {
        return WEB_PATH_URL . 'user/login';
    } else
        return WEB_PATH_URL . 'user.php?action=login';
}

function osc_createUserAccountURL() {
    global $preferences;
    if (isset($preferences['rewriteEnabled']) && $preferences['rewriteEnabled']) {
        return WEB_PATH_URL . 'user/account';
    } else
        return WEB_PATH_URL . 'user.php?action=account';
}

function osc_createUserAlertsURL() {
    global $preferences;
    if (isset($preferences['rewriteEnabled']) && $preferences['rewriteEnabled']) {
        return WEB_PATH_URL . 'user/alerts';
    } else
        return WEB_PATH_URL . 'user.php?action=alerts';
}

function osc_createLogoutURL() {
    global $preferences;
    if (isset($preferences['rewriteEnabled']) && $preferences['rewriteEnabled']) {
        return WEB_PATH_URL . 'user/logout';
    } else
        return WEB_PATH_URL . 'user.php?action=logout';
}

function osc_createSearchURL($pattern) {
    global $preferences;
    if (isset($preferences['rewriteEnabled']) && $preferences['rewriteEnabled']) {
        return WEB_PATH_URL . 'search/' . $pattern;
    } else
        return WEB_PATH_URL . 'search.php?pattern=' . $pattern;
}

function osc_createProfileURL() {
    global $preferences;
    if (isset($preferences['rewriteEnabled']) && $preferences['rewriteEnabled']) {
        return WEB_PATH_URL . 'user/profile';
    } else
        return WEB_PATH_URL . 'user.php?action=profile';
}

function osc_createRegisterURL() {
    global $preferences;
    if (isset($preferences['rewriteEnabled']) && $preferences['rewriteEnabled']) {
        return WEB_PATH_URL . 'user/register';
    } else
        return WEB_PATH_URL . 'user.php?action=register';
}

function osc_createUserItemsURL() {
    global $preferences;
    if (isset($preferences['rewriteEnabled']) && $preferences['rewriteEnabled']) {
        return WEB_PATH_URL . 'user/items';
    } else
        return WEB_PATH_URL . 'user.php?action=items';
}

function osc_createUserOptionsURL($option = null) {
    global $preferences;

    if (isset($preferences['rewriteEnabled']) && $preferences['rewriteEnabled']) {
        if($option!=null) {
            return WEB_PATH_URL . 'user/options/'.$option;
        } else {
            return WEB_PATH_URL . 'user/options';
        }
    } else {
        if($option!=null) {
            return WEB_PATH_URL . 'user.php?action=options&option='.$option;
        } else {
            return WEB_PATH_URL . 'user.php?action=options';
        }
    }
}

function osc_createUserOptionsPostURL($option = null) {
    global $preferences;

    if (isset($preferences['rewriteEnabled']) && $preferences['rewriteEnabled']) {
        if($option!=null) {
            return WEB_PATH_URL . 'user/options_post/'.$option;
        } else {
            return WEB_PATH_URL . 'user/options_post';
        }
    } else {
        if($option!=null) {
            return WEB_PATH_URL . 'user.php?action=options_post&option='.$option;
        } else {
            return WEB_PATH_URL . 'user.php?action=options_post';
        }
    }
}

function osc_createURL($params = null) {
    global $preferences;
    if(is_array($params)) {
        if (count($params) > 0 && isset($params['file']) && $params['file'] != "") {
            $params_string = "";
            foreach ($params as $k => $v) {
                if ($k != 'file') {
                    $params_string .= $k . '=' . $v . '&';
                }
            }
            if (isset($preferences['rewriteEnabled']) && $preferences['rewriteEnabled']) {
                if(count($params)==2 && isset($params['action'])) {
                    return WEB_PATH_URL . $params['file'] . "/" . $params['action'];
                } else {
                    return WEB_PATH_URL . $params['file'] . "?" . $params_string;
                }
            } else {
                return WEB_PATH_URL . $params['file'] . ".php?" . $params_string;
            }
        }
    } else if(is_string($params)) {
        if (isset($preferences['rewriteEnabled']) && $preferences['rewriteEnabled']) {
            return WEB_PATH_URL . $params;
        } else {
            return WEB_PATH_URL . $params.".php";
        }
    }
    return '';
}

function osc_createThumbnailURL($resource) {
    if(isset($resource['pk_i_id'])) {
        return sprintf(WEB_PATH . 'oc-content/uploads/%d_thumbnail.png', $resource['pk_i_id']);
    } else {
        return osc_themeResource('images/no-image.png');
    }
}

function osc_createResourceURL($resource) {
    return sprintf(WEB_PATH . 'oc-content/uploads/%d.png', $resource['pk_i_id']);
}

function osc_createItemPostURL($cat = null) {
    if (is_null($cat)) {
        if (isset($preferences['rewriteEnabled']) && $preferences['rewriteEnabled']) {
            return WEB_PATH_URL . 'item/new';//sprintf(WEB_PATH_URL . 'item.php?action=post');
        } else {
            return sprintf(WEB_PATH_URL . 'item.php?action=post');
        }
    } else {
        if (isset($preferences['rewriteEnabled']) && $preferences['rewriteEnabled']) {
            return WEB_PATH_URL . 'item/new/' . $cat['pk_i_id'];//sprintf(WEB_PATH_URL . 'item.php?action=post&catId=%d', $cat['pk_i_id']);
        } else {
            return sprintf(WEB_PATH_URL . 'item.php?action=post&catId=%d', $cat['pk_i_id']);
        }
    }
}

function osc_createCategoryURL($cat, $absolute = false, $echo = false) {
    $prefix = $absolute ? ABS_WEB_URL : REL_WEB_URL;
    global $preferences;
    $path = '';
    if (isset($preferences['rewriteEnabled']) && $preferences['rewriteEnabled']) {
        $cat = Category::newInstance()->hierarchy($cat['pk_i_id']);
        $sanitized_category = "";
        for ($i = (count($cat)); $i > 0; $i--) {
            $sanitized_category .= $cat[$i - 1]['s_slug'] . '/';
        }
        $path = sprintf($prefix . '%s', $sanitized_category);
    } else {
        $path = sprintf(WEB_PATH_URL . 'search.php?catId=%d', $cat['pk_i_id']);
    }

    if($echo) {
        echo $path;
        return '';
    }

    return $path;
}

function osc_createItemURL($item, $absolute = false) {
    $prefix = $absolute ? ABS_WEB_URL : REL_WEB_URL;
    global $preferences;
    if (isset($preferences['rewriteEnabled']) && $preferences['rewriteEnabled']) {
        $sanitized_title = osc_sanitizeString($item['s_title']);
        $sanitized_category = '';
        $cat = Category::newInstance()->hierarchy($item['fk_i_category_id']);
        for ($i = (count($cat)); $i > 0; $i--) {
            $sanitized_category .= $cat[$i - 1]['s_slug'] . '/';
        }
        return sprintf($prefix . '%s%s_%d', $sanitized_category, $sanitized_title, $item['pk_i_id']);
    } else
        return sprintf($prefix . 'item.php?id=%d', $item['pk_i_id']);
}

/**
 * Prints the user's account menu
 *
 * @param array with options of the form array('name' => 'display name', 'url' => 'url of link')
 * 
 * @return void
 */
function nav_user_menu($options = null) {

    if($options==null) {
        $options = array();
        $options[] = array('name' => __('Dashboard'), 'url' => osc_createUserAccountURL());
        $options[] = array('name' => __('Manage your items'), 'url' => osc_createUserItemsURL());
        $options[] = array('name' => __('Manage your alerts'), 'url' => osc_createUserAlertsURL());
        $options[] = array('name' => __('My account'), 'url' => osc_createProfileURL());
        $options[] = array('name' => __('Logout'), 'url' => osc_createLogoutURL());
    }
    
    
    ?>
    
    <script type="text/javascript">
        $(".user_menu > :first-child").addClass("first");
        $(".user_menu > :last-child").addClass("last");
    </script>
    <ul class="user_menu" >
    
    <?php
    
    $var_l = count($options);
    for($var_o=0;$var_o<$var_l;$var_o++) {
        echo '<li><a href="'.$options[$var_o]['url'].'" >'.$options[$var_o]['name'].'</a></li> ';
    }
    
    osc_runHook('user_menu');
    echo '</ul>';

} 


/**
 * Prints the aditional options to the menu
 *
 * @param array with options of the form array('name' => 'display name', 'url' => 'url of link')
 * 
 * @return void
 */
function add_option_menu($option = null) {

    if($option!=null) {
        echo '<li><a href="'.$option['url'].'" >'.$option['name'].'</a></li> ';
    }
    
}


/**
 * This function returns an array of themes (those copied in the oc-content/themes folder)
 */
function osc_listThemes() {
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
}

/**
 * This function renders the page header
 */
function osc_renderHeader($headerConf = array()) {
    global $preferences;
    global $categories;
    $themePath = THEMES_PATH . $preferences['theme'] . '/header.php';
    if (file_exists($themePath))
        require_once $themePath;
}

/**
 * This function renders the page footer
 */
function osc_renderFooter() {
    global $preferences;
    $themePath = THEMES_PATH . $preferences['theme'] . '/footer.php';
    if (file_exists($themePath))
        require_once $themePath;
}

/**
 * This functions tries to render a view from the current theme, or using the generic UI if the former does not exist.
 */
function osc_renderView($name) {
    extract($GLOBALS);
    $themePath = THEMES_PATH . $preferences['theme'] . '/' . $name;
    if (file_exists($themePath)) {
        require_once $themePath;
    } else {
        $defaultPath = LIB_PATH . 'osclass/gui/' . $name;
        if (file_exists($defaultPath))
            require_once $defaultPath;
        else
            trigger_error("The view '$name' was not found.");
    }
}
