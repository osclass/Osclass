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

require_once 'oc-load.php';


$action = osc_readAction();
switch($action) 
{
    case 'import':          osc_renderAdminSection('tools/import.php', __('Tools'));
	break;
    case 'import_post':     $sql = $_POST['sql'];
                            $conn = getConnection() ;
                            $conn->osc_dbImportSQL($sql) ;
		
                            osc_redirectTo('tools.php') ;
	break;
    case 'upgrade':         osc_renderAdminSection('tools/upgrade.php', __('Tools'));
	break;
    case 'backup':          osc_renderAdminSection('tools/backup.php', __('Tools'));
	break;
    case 'backup-sql':      if(isset($_REQUEST['bck_dir'])) {
                            	$sql_name = $_REQUEST['bck_dir']."/OSClass_mysqlbackup.".date('YmdHis').".sql";
                            } else {
                            	$sql_name = APP_PATH."/OSClass_mysqlbackup.".date('YmdHis').".sql";
                            }
                            osc_dbdump($sql_name);
                            echo __('Backup made correctly') ;
	break;
    case 'backup-zip':      if(isset($_REQUEST['bck_dir'])) {
                            	$archive_name = $_REQUEST['bck_dir'] . "/OSClass_backup." . date('YmdHis') . ".zip" ;
                            } else {
                            	$archive_name = APP_PATH . "/OSClass_backup." . date('YmdHis') . ".zip" ;
                            }
                            $archive_folder = APP_PATH ;
                            
                            if (osc_zipFolder($archive_folder, $archive_name)) echo __('Archiving is sucessful!') ;
                            else echo __('Error, can\'t create a zip file!') ;
	break;
    case 'backup_post':     print_r($_REQUEST);
                            osc_renderAdminSection('tools/backup.php', __('Tools'));
	break;
}

?>