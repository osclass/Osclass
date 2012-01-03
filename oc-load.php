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

define('OSCLASS_VERSION', '2.3.4') ;

if( !defined('ABS_PATH') ) {
    define( 'ABS_PATH', dirname(__FILE__) . '/' );
}

define('LIB_PATH', ABS_PATH . 'oc-includes/') ;
define('CONTENT_PATH', ABS_PATH . 'oc-content/') ;
define('THEMES_PATH', CONTENT_PATH . 'themes/') ;
define('PLUGINS_PATH', CONTENT_PATH . 'plugins/') ;
define('TRANSLATIONS_PATH', CONTENT_PATH . 'languages/') ;

if( !file_exists(ABS_PATH . 'config.php') ) {
    require_once LIB_PATH . 'osclass/helpers/hErrors.php' ;

    $title   = 'OSClass &raquo; Error' ;
    $message = 'There doesn\'t seem to be a <code>config.php</code> file. OSClass isn\'t installed. <a href="http://forums.osclass.org/">Need more help?</a></p>' ;
    $message .= '<p><a class="button" href="' . osc_get_absolute_url() .'oc-includes/osclass/install.php">Install</a></p>' ;

    osc_die($title, $message) ;
}

// load database configuration
require_once ABS_PATH . 'config.php' ;
require_once LIB_PATH . 'osclass/default-constants.php' ;

// Sets PHP error handling
if( OSC_DEBUG ) {
    ini_set( 'display_errors', 1 ) ;
    error_reporting( E_ALL | E_STRICT ) ;

    if( OSC_DEBUG_LOG ) {
        ini_set( 'display_errors', 0 ) ;
        ini_set( 'log_errors', 1 ) ;
        ini_set( 'error_log', CONTENT_PATH . 'debug.log' ) ;
    }
} else {
    error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING ) ;
}

require_once LIB_PATH . 'osclass/db.php';
require_once LIB_PATH . 'osclass/Logger/LogDatabase.php' ;
require_once LIB_PATH . 'osclass/classes/database/DBConnectionClass.php';
require_once LIB_PATH . 'osclass/classes/database/DBCommandClass.php';
require_once LIB_PATH . 'osclass/classes/database/DBRecordsetClass.php';
require_once LIB_PATH . 'osclass/classes/database/DAO.php';
require_once LIB_PATH . 'osclass/helpers/hDatabaseInfo.php';
require_once LIB_PATH . 'osclass/model/Preference.php';
require_once LIB_PATH . 'osclass/helpers/hPreference.php';

// check if OSClass is installed
if( !getBoolPreference('osclass_installed') ) {
    require_once LIB_PATH . 'osclass/helpers/hErrors.php' ;

    $title    = 'OSClass &raquo; Error' ;
    $message  = 'OSClass isn\'t installed. <a href="http://forums.osclass.org/">Need more help?</a></p>' ;
    $message .= '<p><a class="button" href="' . osc_get_absolute_url() .'oc-includes/osclass/install.php">Install</a></p>' ;

    osc_die($title, $message) ;
}

require_once LIB_PATH . 'osclass/helpers/hDefines.php';
require_once LIB_PATH . 'osclass/helpers/hLocale.php';
require_once LIB_PATH . 'osclass/helpers/hMessages.php';
require_once LIB_PATH . 'osclass/helpers/hUsers.php';
require_once LIB_PATH . 'osclass/helpers/hItems.php';
require_once LIB_PATH . 'osclass/helpers/hSearch.php';
require_once LIB_PATH . 'osclass/helpers/hUtils.php';
require_once LIB_PATH . 'osclass/helpers/hCategories.php';
require_once LIB_PATH . 'osclass/helpers/hTranslations.php';
require_once LIB_PATH . 'osclass/helpers/hSecurity.php';
require_once LIB_PATH . 'osclass/helpers/hSanitize.php';
require_once LIB_PATH . 'osclass/helpers/hValidate.php';
require_once LIB_PATH . 'osclass/helpers/hPage.php';
require_once LIB_PATH . 'osclass/helpers/hPagination.php';
require_once LIB_PATH . 'osclass/helpers/hPremium.php';
require_once LIB_PATH . 'osclass/helpers/hTheme.php';
require_once LIB_PATH . 'osclass/core/Params.php';
require_once LIB_PATH . 'osclass/core/Cookie.php';
require_once LIB_PATH . 'osclass/core/Session.php';
require_once LIB_PATH . 'osclass/core/View.php';
require_once LIB_PATH . 'osclass/core/BaseModel.php';
require_once LIB_PATH . 'osclass/core/SecBaseModel.php';
require_once LIB_PATH . 'osclass/core/WebSecBaseModel.php';
require_once LIB_PATH . 'osclass/core/AdminSecBaseModel.php';
require_once LIB_PATH . 'osclass/core/Translation.php';

require_once LIB_PATH . 'osclass/AdminThemes.php';
require_once LIB_PATH . 'osclass/WebThemes.php';
require_once LIB_PATH . 'osclass/compatibility.php';
require_once LIB_PATH . 'osclass/utils.php';
require_once LIB_PATH . 'osclass/formatting.php';
require_once LIB_PATH . 'osclass/feeds.php';
require_once LIB_PATH . 'osclass/locales.php';
require_once LIB_PATH . 'osclass/plugins.php';
require_once LIB_PATH . 'osclass/helpers/hPlugins.php';
require_once LIB_PATH . 'osclass/ItemActions.php';
require_once LIB_PATH . 'osclass/emails.php';
require_once LIB_PATH . 'osclass/model/Admin.php';
require_once LIB_PATH . 'osclass/model/Alerts.php';
require_once LIB_PATH . 'osclass/model/Cron.php';
require_once LIB_PATH . 'osclass/model/Category.php';
require_once LIB_PATH . 'osclass/model/CategoryStats.php';
require_once LIB_PATH . 'osclass/model/City.php';
require_once LIB_PATH . 'osclass/model/Country.php';
require_once LIB_PATH . 'osclass/model/Currency.php';
require_once LIB_PATH . 'osclass/model/OSCLocale.php';
require_once LIB_PATH . 'osclass/model/Item.php';
require_once LIB_PATH . 'osclass/model/ItemComment.php';
require_once LIB_PATH . 'osclass/model/ItemResource.php';
require_once LIB_PATH . 'osclass/model/ItemStats.php';
require_once LIB_PATH . 'osclass/model/Page.php';
require_once LIB_PATH . 'osclass/model/PluginCategory.php';
require_once LIB_PATH . 'osclass/model/Region.php';
require_once LIB_PATH . 'osclass/model/User.php';
require_once LIB_PATH . 'osclass/model/UserEmailTmp.php';
require_once LIB_PATH . 'osclass/model/ItemLocation.php';
require_once LIB_PATH . 'osclass/model/Widget.php';
require_once LIB_PATH . 'osclass/model/Search.php';
require_once LIB_PATH . 'osclass/model/LatestSearches.php';
require_once LIB_PATH . 'osclass/model/SiteInfo.php';
require_once LIB_PATH . 'osclass/model/Field.php';
require_once LIB_PATH . 'osclass/model/Log.php';
require_once LIB_PATH . 'osclass/classes/Cache.php';
require_once LIB_PATH . 'osclass/classes/ImageResizer.php';
require_once LIB_PATH . 'osclass/classes/RSSFeed.php';
require_once LIB_PATH . 'osclass/classes/Sitemap.php';
require_once LIB_PATH . 'osclass/classes/Pagination.php';
require_once LIB_PATH . 'osclass/classes/Watermark.php';
require_once LIB_PATH . 'osclass/classes/Rewrite.php';
require_once LIB_PATH . 'osclass/classes/Stats.php';
require_once LIB_PATH . 'osclass/alerts.php';

require_once LIB_PATH . 'osclass/frm/Form.form.class.php';
require_once LIB_PATH . 'osclass/frm/Page.form.class.php';
require_once LIB_PATH . 'osclass/frm/Category.form.class.php';
require_once LIB_PATH . 'osclass/frm/Item.form.class.php';
require_once LIB_PATH . 'osclass/frm/Contact.form.class.php';
require_once LIB_PATH . 'osclass/frm/Comment.form.class.php';
require_once LIB_PATH . 'osclass/frm/User.form.class.php';
require_once LIB_PATH . 'osclass/frm/Language.form.class.php';
require_once LIB_PATH . 'osclass/frm/SendFriend.form.class.php';
require_once LIB_PATH . 'osclass/frm/Alert.form.class.php';
require_once LIB_PATH . 'osclass/frm/Field.form.class.php';

require_once LIB_PATH . 'osclass/functions.php';

define('__OSC_LOADED__', true);

Plugins::init() ;

Rewrite::newInstance()->init();
// Moved from BaseModel, since we need some session magic on index.php ;)
Session::newInstance()->session_start() ;

if(osc_timezone() != '') {
    date_default_timezone_set(osc_timezone());
}

function osc_show_maintenance() {
    if(defined('__OSC_MAINTENANCE__')) { ?>
        <div id="maintenance" name="maintenance">
             <?php _e("The website is currently under maintenance mode"); ?>
        </div>
    <?php }
}

function osc_meta_generator() {
    echo '<meta name="generator" content="OSClass ' . OSCLASS_VERSION . '" />';
}

osc_add_hook("header", "osc_show_maintenance");
osc_add_hook("header", "osc_meta_generator");

?>