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

define('ABS_PATH',dirname(dirname(dirname(dirname(__FILE__)))));

require_once ABS_PATH . 'oc-load.php';

if(!isset($_POST['action']))
    return false;

switch ($_POST['action']) {
    case 'runhook':
        if(!isset($_POST['hook']))
            return false;
        switch ($_POST['hook']) {
            case 'item_form':
                if(!isset($_POST['catId']))
                    return false;
                
                osc_runHook('item_form', $_POST['catId']);
                break;
        }

        break;
    default:
        echo json_encode(array('error' => 'no action defined'));
        break;
}

?>
