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

    define('IS_AJAX', 1);
    define('ABS_PATH', dirname(dirname(dirname(dirname(__FILE__)))) . '/');

    require_once  ABS_PATH . 'config.php';
    require_once  ABS_PATH . 'common.php';
    require_once  LIB_PATH . 'osclass/web.php';
    require_once  LIB_PATH . 'osclass/db.php';
    require_once  LIB_PATH . 'osclass/classes/DAO.php';
    require_once  LIB_PATH . 'osclass/model/Region.php';

    $regions = Region::newInstance()->listWhere("fk_c_country_code = '%s'", $_REQUEST['countryId']);
    
    echo json_encode($regions);
