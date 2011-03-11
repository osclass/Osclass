<?php


/*
 *      OSCLass - software for creating and publishing online classified
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

require_once LIB_PATH . 'osclass/classes/data/DAO.php';

   require_once LIB_PATH . 'osclass/model/dao/DAO_osclass_t_admin.php' ;
   require_once LIB_PATH . 'osclass/model/dao/DAO_osclass_t_alerts.php' ;
   require_once LIB_PATH . 'osclass/model/dao/DAO_osclass_t_category.php' ;
   require_once LIB_PATH . 'osclass/model/dao/DAO_osclass_t_category_description.php' ;
   require_once LIB_PATH . 'osclass/model/dao/DAO_osclass_t_category_stats.php' ;
   require_once LIB_PATH . 'osclass/model/dao/DAO_osclass_t_city.php' ;
   require_once LIB_PATH . 'osclass/model/dao/DAO_osclass_t_city_area.php' ;
   require_once LIB_PATH . 'osclass/model/dao/DAO_osclass_t_country.php' ;
   require_once LIB_PATH . 'osclass/model/dao/DAO_osclass_t_cron.php' ;
   require_once LIB_PATH . 'osclass/model/dao/DAO_osclass_t_currency.php' ;
   require_once LIB_PATH . 'osclass/model/dao/DAO_osclass_t_item.php' ;
   require_once LIB_PATH . 'osclass/model/dao/DAO_osclass_t_item_comment.php' ;
   require_once LIB_PATH . 'osclass/model/dao/DAO_osclass_t_item_description.php' ;
   require_once LIB_PATH . 'osclass/model/dao/DAO_osclass_t_item_job_attr.php' ;
   require_once LIB_PATH . 'osclass/model/dao/DAO_osclass_t_item_job_description_attr.php' ;
   require_once LIB_PATH . 'osclass/model/dao/DAO_osclass_t_item_location.php' ;
   require_once LIB_PATH . 'osclass/model/dao/DAO_osclass_t_item_resource.php' ;
   require_once LIB_PATH . 'osclass/model/dao/DAO_osclass_t_item_stats.php' ;
   require_once LIB_PATH . 'osclass/model/dao/DAO_osclass_t_keywords.php' ;
   require_once LIB_PATH . 'osclass/model/dao/DAO_osclass_t_locale.php' ;
   require_once LIB_PATH . 'osclass/model/dao/DAO_osclass_t_pages.php' ;
   require_once LIB_PATH . 'osclass/model/dao/DAO_osclass_t_pages_description.php' ;
   require_once LIB_PATH . 'osclass/model/dao/DAO_osclass_t_plugin_category.php' ;
   require_once LIB_PATH . 'osclass/model/dao/DAO_osclass_t_preference.php' ;
   require_once LIB_PATH . 'osclass/model/dao/DAO_osclass_t_region.php' ;
   require_once LIB_PATH . 'osclass/model/dao/DAO_osclass_t_user.php' ;
   require_once LIB_PATH . 'osclass/model/dao/DAO_osclass_t_user_description.php' ;
   require_once LIB_PATH . 'osclass/model/dao/DAO_osclass_t_user_email_tmp.php' ;
   require_once LIB_PATH . 'osclass/model/dao/DAO_osclass_t_widget.php' ;

?>