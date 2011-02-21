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

define('OSCLASS_VERSION', '1.2 Delta') ;

if( !defined('ABS_PATH') ) {
    define( 'ABS_PATH', dirname(__FILE__) . '/' );
}

define('LIB_PATH', ABS_PATH . 'oc-includes/') ;
define('THEMES_PATH', ABS_PATH . 'oc-content/themes/') ;
define('PLUGINS_PATH', ABS_PATH . 'oc-content/plugins/') ;
define('TRANSLATIONS_PATH', ABS_PATH . 'oc-includes/translations/') ;

if( !file_exists(ABS_PATH . 'config.php') ) {
    require_once ABS_PATH . 'oc-includes/osclass/helpers/hErrors.php' ;

    $title = 'OSClass &raquo; Error' ;
    $message = 'There doesn\'t exist config.php file. OSClass seems that is not installed.</p>' ;
    $message .= '<p><a class="button" href="oc-includes/osclass/install.php">Install</a></p>' ;

    osc_die($title, $message) ;
}

require_once ABS_PATH . 'config.php';
require_once ABS_PATH . 'oc-includes/osclass/db.php';
require_once ABS_PATH . 'oc-includes/osclass/classes/DAO.php';
require_once ABS_PATH . 'oc-includes/osclass/model/Preference.php';
require_once ABS_PATH . 'oc-includes/osclass/helpers/hPreference.php';
require_once ABS_PATH . 'oc-includes/osclass/helpers/hDefines.php';
require_once ABS_PATH . 'oc-includes/osclass/helpers/hLocale.php';
require_once ABS_PATH . 'oc-includes/osclass/helpers/hMessages.php';
require_once ABS_PATH . 'oc-includes/osclass/helpers/hUsers.php';
require_once ABS_PATH . 'oc-includes/osclass/helpers/hItems.php';
require_once ABS_PATH . 'oc-includes/osclass/helpers/hUtils.php';
require_once ABS_PATH . 'oc-includes/osclass/helpers/hCategories.php';
require_once ABS_PATH . 'oc-includes/osclass/helpers/hTranslations.php';
require_once ABS_PATH . 'oc-includes/osclass/helpers/hSecurity.php';
require_once ABS_PATH . 'oc-includes/osclass/core/Params.php';
require_once ABS_PATH . 'oc-includes/osclass/core/Session.php';
require_once ABS_PATH . 'oc-includes/osclass/core/View.php';
require_once ABS_PATH . 'oc-includes/osclass/core/BaseModel.php';
require_once ABS_PATH . 'oc-includes/osclass/core/SecBaseModel.php';
require_once ABS_PATH . 'oc-includes/osclass/core/WebSecBaseModel.php';
require_once ABS_PATH . 'oc-includes/osclass/core/AdminSecBaseModel.php';
require_once ABS_PATH . 'oc-includes/osclass/core/Translation.php';

require_once LIB_PATH . 'osclass/AdminThemes.php';
require_once LIB_PATH . 'osclass/WebThemes.php';
require_once LIB_PATH . 'osclass/translations.php';
require_once LIB_PATH . 'osclass/utils.php';
require_once LIB_PATH . 'osclass/formatting.php';
require_once LIB_PATH . 'osclass/feeds.php';
require_once LIB_PATH . 'osclass/locales.php';
require_once LIB_PATH . 'osclass/plugins.php';
require_once ABS_PATH . 'oc-includes/osclass/helpers/hPlugins.php';
//require_once LIB_PATH . 'osclass/validations.php'; xxx: to be deleted
require_once LIB_PATH . 'osclass/ItemActions.php';
require_once LIB_PATH . 'osclass/model/Admin.php';
require_once LIB_PATH . 'osclass/model/Alerts.php';
require_once LIB_PATH . 'osclass/model/Cron.php';
require_once LIB_PATH . 'osclass/model/Category.php';
require_once LIB_PATH . 'osclass/model/CategoryStats.php';
require_once LIB_PATH . 'osclass/model/City.php';
require_once LIB_PATH . 'osclass/model/Country.php';
require_once LIB_PATH . 'osclass/model/Comment.php';
require_once LIB_PATH . 'osclass/model/Currency.php';
require_once LIB_PATH . 'osclass/model/Locale.php';
require_once LIB_PATH . 'osclass/model/Test.php';
require_once LIB_PATH . 'osclass/model/Item.php';
require_once LIB_PATH . 'osclass/model/ItemComment.php';
require_once LIB_PATH . 'osclass/model/ItemResource.php';
require_once LIB_PATH . 'osclass/model/ItemStats.php';
require_once LIB_PATH . 'osclass/model/Page.php';
require_once LIB_PATH . 'osclass/model/PluginCategory.php';
require_once LIB_PATH . 'osclass/model/Region.php';
require_once LIB_PATH . 'osclass/model/Rewrite.php';
require_once LIB_PATH . 'osclass/model/User.php';
require_once LIB_PATH . 'osclass/model/UserEmailTmp.php';
require_once LIB_PATH . 'osclass/model/ItemLocation.php';
require_once LIB_PATH . 'osclass/model/Widget.php';
require_once LIB_PATH . 'osclass/model/Search.php';
require_once LIB_PATH . 'osclass/classes/Cache.php';
require_once LIB_PATH . 'osclass/classes/HTML.php';
require_once LIB_PATH . 'osclass/classes/ImageResizer.php';
require_once LIB_PATH . 'osclass/classes/RSSFeed.php';
require_once LIB_PATH . 'osclass/classes/Sitemap.php';
require_once LIB_PATH . 'osclass/alerts.php';

require_once LIB_PATH . 'osclass/frm/Form.form.class.php';
require_once LIB_PATH . 'osclass/frm/Page.form.class.php';
require_once LIB_PATH . 'osclass/frm/Category.form.class.php';
require_once LIB_PATH . 'osclass/frm/Item.form.class.php';
require_once LIB_PATH . 'osclass/frm/Contact.form.class.php';
require_once LIB_PATH . 'osclass/frm/User.form.class.php';
require_once LIB_PATH . 'osclass/frm/Language.form.class.php'; // CARLOS

define('__OSC_LOADED__', true);
if(!defined('__FROM_CRON__')) {
    if(osc_auto_cron()) {
        osc_doRequest(osc_base_url() . 'oc-includes/osclass/cron.php', array()) ;
    }
}

Plugins::init() ;

//Rewrite::newInstance()->init();

?>
