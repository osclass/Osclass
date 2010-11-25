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

define('OC_ADMIN', true);

require_once dirname(__FILE__).'/../common.php';
require_once APP_PATH.'/config.php';
require_once LIB_PATH.'/osclass/web.php';
require_once LIB_PATH.'/osclass/session.php';
osc_checkAdminSession();


require_once LIB_PATH.'/osclass/db.php';
require_once  LIB_PATH.'/osclass/classes/DAO.php';
require_once  LIB_PATH.'/osclass/session.php';
require_once  LIB_PATH.'/osclass/plugins.php';
require_once  LIB_PATH.'/osclass/themes.php';
require_once  LIB_PATH.'/osclass/utils.php';
require_once  LIB_PATH.'/osclass/locale.php';
require_once  LIB_PATH.'/osclass/formatting.php';
require_once  LIB_PATH.'/osclass/AdminThemes.php';
require_once  LIB_PATH.'/osclass/error.php';
require_once  LIB_PATH.'/osclass/feeds.php';
require_once  LIB_PATH.'/osclass/install.php';
require_once  LIB_PATH.'/osclass/locales.php';
require_once  LIB_PATH.'/osclass/security.php';
require_once  LIB_PATH.'/osclass/validations.php';
require_once  LIB_PATH.'/osclass/model/Admin.php';
require_once  LIB_PATH.'/osclass/model/Alerts.php';
require_once  LIB_PATH.'/osclass/model/Cron.php';
require_once  LIB_PATH.'/osclass/model/Category.php';
require_once  LIB_PATH.'/osclass/model/CategoryStats.php';
require_once  LIB_PATH.'/osclass/model/City.php';
require_once  LIB_PATH.'/osclass/model/Country.php';
require_once  LIB_PATH.'/osclass/model/Comment.php';
require_once  LIB_PATH.'/osclass/model/Currency.php';
require_once  LIB_PATH.'/osclass/model/Item.php';
require_once  LIB_PATH.'/osclass/model/ItemComment.php';
require_once  LIB_PATH.'/osclass/model/ItemResource.php';
require_once  LIB_PATH.'/osclass/model/ItemStats.php';
require_once  LIB_PATH.'/osclass/model/Locale.php';
require_once  LIB_PATH.'/osclass/model/Page.php';
require_once  LIB_PATH.'/osclass/model/Plugin.php';
require_once  LIB_PATH.'/osclass/model/PluginCategory.php';
require_once  LIB_PATH.'/osclass/model/Preference.php';
require_once  LIB_PATH.'/osclass/model/Region.php';
require_once  LIB_PATH.'/osclass/model/User.php';
require_once  LIB_PATH.'/osclass/model/ItemLocation.php';
require_once  LIB_PATH.'/osclass/model/Widget.php';
require_once  LIB_PATH.'/osclass/model/Search.php';
require_once  LIB_PATH.'/osclass/classes/Cache.php';
require_once  LIB_PATH.'/osclass/classes/DAOEntity.php';
require_once  LIB_PATH.'/osclass/classes/HTML.php';
require_once  LIB_PATH.'/osclass/classes/ImageResizer.php';
require_once  LIB_PATH.'/osclass/classes/RSSFeed.php';
require_once  LIB_PATH.'/osclass/classes/Sitemap.php';
require_once 'common.php';
require_once LIB_PATH.'/osclass/alerts.php';

require_once LIB_PATH.'/osclass/frm/Form.form.class.php' ;
require_once LIB_PATH.'/osclass/frm/Item.form.class.php' ;
require_once LIB_PATH.'/osclass/frm/Category.form.class.php' ;
require_once LIB_PATH.'/osclass/frm/Page.form.class.php' ;
require_once LIB_PATH.'/osclass/frm/Language.form.class.php' ;
require_once LIB_PATH.'/osclass/frm/Contact.form.class.php' ;
require_once LIB_PATH.'/osclass/frm/User.form.class.php' ;

require_once LIB_PATH.'/libcurlemu/libcurlemu.inc.php';


$_GET = add_slashes_extended($_GET);
$_POST = add_slashes_extended($_POST);
$_COOKIE = add_slashes_extended($_COOKIE);
$_SERVER = add_slashes_extended($_SERVER);
$_REQUEST = add_slashes_extended($_REQUEST);

?>
