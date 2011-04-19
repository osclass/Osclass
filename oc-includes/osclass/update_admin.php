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
error_reporting(0);

define( 'ABS_PATH', dirname(dirname(dirname(__FILE__))) . '/' );

require_once ABS_PATH . 'oc-includes/osclass/db.php';
require_once ABS_PATH . 'oc-includes/osclass/classes/DAO.php';
require_once ABS_PATH . 'oc-includes/osclass/model/Admin.php';
require_once ABS_PATH . 'oc-includes/osclass/helpers/hDatabaseInfo.php';

require_once ABS_PATH . 'config.php';

$old_passwd   = $_REQUEST['old_password'];
$id_admin     = $_REQUEST['id'];
$new_username = $_REQUEST['new_username'];
$new_passwd   = $_REQUEST['new_password'];

$mAdmin = Admin::newInstance();
$admin = $mAdmin->findByConditions( array('pk_i_id' => $id_admin, 's_password' => sha1($old_passwd) ) );

if($admin){
    $result = -1;
    if( isset( $new_username ) ){
        $result = $mAdmin->update( array('s_username' => $new_username ), array('pk_i_id' => '1') ) ;
    } elseif ( isset( $new_passwd ) ) {
        $result = $mAdmin->update( array('s_password' => sha1($new_passwd) ), array('pk_i_id' => '1') ) ;
    }
    echo $result;
} else {
    echo "-1";
}
?>
