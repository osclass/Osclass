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

define('ABS_PATH', dirname(dirname(__FILE__)) . '/') ;

require_once ABS_PATH . 'oc-admin/oc-load.php' ;

$action = Params::getParam('action') ;
switch($action) 
{
    case 'import':          osc_renderAdminSection('tools/import.php', __('Tools')) ;
	break;
    case 'import_post':     $sql = $_POST['sql'] ;
                            $conn = getConnection() ;
                            $conn->osc_dbImportSQL($sql) ;
		
                            osc_redirectTo('tools.php') ;
	break;
    case 'images':          osc_renderAdminSection('tools/images.php', __('Tools')) ;
	break;
    case 'images_post':
        $preferences = Preference::newInstance()->toArray() ;

	    $path = ABS_PATH . 'oc-content/uploads' ;
	    $dir = opendir($path) ;
	    while($file = readdir($dir)) {

		    if(preg_match('|([0-9]+)_thumbnail\.png|i', $file, $matches)) {

                $orig_file = str_replace('_thumbnail.', '_original.', $file) ;
                $tmpName = ABS_PATH . 'oc-content/uploads/' . $orig_file ;
			    if(!file_exists($orig_file)) {
                    copy(str_replace('_original.', '.', $tmpName), $tmpName) ;
                }

                // Create thumbnail
                $thumbnailPath = ABS_PATH . 'oc-content/uploads/' . $file ;
                $size = explode('x', osc_thumbnail_dimensions()) ;
                ImageResizer::fromFile($tmpName)->resizeTo($size[0], $size[1])->saveToFile($thumbnailPath) ;

                // Create preview
                $thumbnailPath = ABS_PATH . 'oc-content/uploads/'.str_replace('_thumbnail.', '_preview.', $file) ;
                $size = explode('x', osc_preview_dimensions()) ;
                ImageResizer::fromFile($tmpName)->resizeTo($size[0], $size[1])->saveToFile($thumbnailPath) ;

                // Create normal size
                $thumbnailPath = ABS_PATH . 'oc-content/uploads/'.str_replace('_thumbnail.', '.', $file) ;
                $size = explode('x', osc_normal_dimensions()) ;
                ImageResizer::fromFile($tmpName)->resizeTo($size[0], $size[1])->saveToFile($thumbnailPath) ;
                
                if(!osc_keep_original_image()) {
                    @unlink($tmpName) ;
                }

		    }

	    }
	    closedir($dir) ;
        osc_addFlashMessage(__('Re-generation complete.')) ;
        osc_redirectTo('tools.php?action=images') ;
	break;
    case 'upgrade':         osc_renderAdminSection('tools/upgrade.php', __('Tools')) ;
	break;
    case 'backup':          osc_renderAdminSection('tools/backup.php', __('Tools')) ;
	break;
    case 'backup-sql':      if(isset($_REQUEST['bck_dir'])) {
                                if(substr(trim($_REQUEST['bck_dir']), -1, 1) == "/") {
                                	$sql_name = trim($_REQUEST['bck_dir']) . "/OSClass_mysqlbackup." . date('YmdHis') . ".sql" ;
                                } else {
                                	$sql_name = trim($_REQUEST['bck_dir']) . "OSClass_mysqlbackup." . date('YmdHis') . ".sql" ;
                                }
                            } else {
                            	$sql_name = ABS_PATH . "OSClass_mysqlbackup." . date('YmdHis') . ".sql" ;
                            }
                            osc_dbdump($sql_name) ;
                            _e('Backup made correctly') ;
	break;
    case 'backup-zip':      if(isset($_REQUEST['bck_dir'])) {
                            	$archive_name = $_REQUEST['bck_dir'] . "/OSClass_backup." . date('YmdHis') . ".zip" ;
                            } else {
                            	$archive_name = ABS_PATH . "OSClass_backup." . date('YmdHis') . ".zip" ;
                            }
                            $archive_folder = ABS_PATH ;
                            
                            if (osc_zipFolder($archive_folder, $archive_name)) _e('Archiving is sucessful!') ;
                            else _e('Error, can\'t create a zip file!') ;
	break;
    case 'backup_post':     print_r($_REQUEST);
                            osc_renderAdminSection('tools/backup.php', __('Tools')) ;
	break;
}

?>
