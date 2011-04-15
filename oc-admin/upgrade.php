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

define('ABS_PATH', dirname(dirname(__FILE__)) . '/');

require_once ABS_PATH . 'oc-load.php';

$action = Params::getParam('action');
$message = "";
switch($action) {
	case 'check-update':
		$message = __("Checking for update files");
		break;

	case 'download-file':
		if(Params::getParam('file')!='') {

			$tmp = explode("/", Params::getParam('file'));
			$filename = end($tmp);

			osc_downloadFile(Params::getParam('file'), $filename);

			$message = __('File downloaded correctly');
		} else {
			$message = __('Missing filename');
		}
		break;

	case 'empty-temp':
		$message = __("Removing temp-directory");
		$path = ABS_PATH . 'oc-temp';
		$dir = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::CHILD_FIRST);
		for ($dir->rewind(); $dir->valid(); $dir->next()) {
			if ($dir->isDir()) {
                if($dir->getFilename()!='.' && $dir->getFilename()!='..') {
    				rmdir($dir->getPathname());
                }
			} else {
				unlink($dir->getPathname());
			}
		}
		rmdir($path);
		
		break;

	case 'db-backup':
		osc_dbdump();
		break;

	case 'zip-osclass':
		$archive_name = ABS_PATH . "OSClass_backup.".date('YmdHis').".zip";
		$archive_folder = ABS_PATH;

		if (osc_zip_folder($archive_folder, $archive_name)) {
			$message = __('Archiving is sucessful!');
		} else {
		    $message = __('Error, can\'t create a zip file!');
		}
		break;

	case 'unzip-file':
		if(Params::getParam('file')!='') {
            @mkdir(ABS_PATH.'oc-temp', 0777);
            $res = osc_unzip_file(osc_content_path() . 'downloads/' . Params::getParam('file'), ABS_PATH.'oc-temp/');
            if($res==1) {
				$message = __('OK');
			} else {
				$message = __('Unzip failed');
			}
						
		} else {
			$message = __('Filename incorrect');
			
		}
		break;

	case 'remove-files':
		if(file_exists(ABS_PATH.'oc-temp/remove.list')) {
			$lines = file(ABS_PATH.'oc-temp/remove.list', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			$message = "";
			foreach ($lines as $line_num => $r_file) {
				$unlink = @unlink(ABS_PATH.$r_file);
				if(!$unlink) { $message .= __('Error removing file: ').$r_file."<br/>"; }
			}
			if($message=="") {
				$message = __('Files removed');
			}
		} else {
			$message = __('No files to remove');
		}
		
		break;

	case 'copy-files':
		$fail = -1;
		if ($handle = opendir(ABS_PATH.'oc-temp')) {

			$fail = 0;
			while (false !== ($_file = readdir($handle))) {
				if($_file!='.' && $_file!='..' && $_file!='remove.list' && $_file!='upgrade.sql' && $_file!='customs.actions') {
					$data = osc_copy(ABS_PATH."oc-temp/".$_file, ABS_PATH.$_file);
					if($data==false) {
					    $fail = 1;
					};
				}
			}
			closedir($handle);

			if($fail==-1) {
				$message = __('Nothing to copy');
			} else if($fail==0) {
				$message = __('Files copied');
			} else {
				$message = __('There were problems copying files. Maybe the file permissions are not set correctly');
			}

		} else {
			$message = __('Nothing to copy');
		}
		break;

	case 'execute-sql':
        if(file_exists(osc_lib_path() . 'osclass/installer/struct.sql')) {
            $sql = file_get_contents(osc_lib_path() . 'osclass/installer/struct.sql');
    		$conn = getConnection();
            $queries = $conn->osc_updateDB(str_replace('/*TABLE_PREFIX*/', DB_TABLE_PREFIX, $sql));
			$message = __('Tables updated correctly') ;
		} else {
			$message = __('No tables update to execute') ;
		}
		break ;

	case 'execute-actions':
		if(file_exists(osc_lib_path() . 'osclass/upgrade-funcs.php')) {
			require_once osc_lib_path() . 'osclass/upgrade-funcs.php';
			$message = __('Custom actions executed') ;
		} else {
			$message = __('No action to execute') ;
		}
		
		break ;

	default:
		osc_renderAdminSection('tools/upgrade.php', __('Update')) ;
		break ;

    /************************
     *** COMPLETE PROCESS ***
     ************************/
	case 'complete': // AT THIS POINT WE KNOW IF THERE'S AN UPDATE OR NOT
	
	    $error = 0;
        $remove_error_msg = "";
        $sql_error_msg = "";
        $rm_errors = 0;

        /***********************
         **** DOWNLOAD FILE ****
         ***********************/
		if(Params::getParam('file')!='') {

			$tmp = explode("/", Params::getParam('file'));
			$filename = end($tmp);
			$result = osc_downloadFile(Params::getParam('file'), $filename);

            if($result) { // Everything is OK, continue
                /**********************
                 ***** UNZIP FILE *****
                 **********************/
                @mkdir(ABS_PATH.'oc-temp', 0777);
                $res = osc_unzip_file(osc_content_path() . 'downloads/' . $filename, ABS_PATH.'oc-temp/');
                if($res==1) { // Everything is OK, continue
                    /**********************
                     ***** COPY FILES *****
                     **********************/
		            $fail = -1;
		            if ($handle = opendir(ABS_PATH.'oc-temp')) {
			            $fail = 0;
			            while (false !== ($_file = readdir($handle))) {
				            if($_file!='.' && $_file!='..' && $_file!='remove.list' && $_file!='upgrade.sql' && $_file!='customs.actions') {
					            $data = osc_copy(ABS_PATH."oc-temp/".$_file, ABS_PATH.$_file);
					            if($data==false) {
					                $fail = 1;
					            };
				            }
			            }
			            closedir($handle);
			            
                        if($fail==0) { // Everything is OK, continue
                            /**********************
                             **** REMOVE FILES ****
                             **********************/
                            if(file_exists(ABS_PATH.'oc-temp/remove.list')) {
			                    $lines = file(ABS_PATH.'oc-temp/remove.list', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			                    foreach ($lines as $line_num => $r_file) {
				                    $unlink = @unlink(ABS_PATH.$r_file);
				                    if(!$unlink) { $remove_error_msg .= __('Error removing file: ').$r_file."<br/>"; }
			                    }
		                    }
		                    // Removing files is not important for the rest of the proccess
		                    // We will inform the user of the problems but the upgrade could continue
                            /************************
                             *** UPGRADE DATABASE ***
                             ************************/
		                    $error_queries = array();
                            if(file_exists(osc_lib_path() . 'osclass/installer/struct.sql')) {
                                $sql = file_get_contents(osc_lib_path() . 'osclass/installer/struct.sql');
                        		$conn = getConnection();
                                $error_queries = $conn->osc_updateDB(str_replace('/*TABLE_PREFIX*/', DB_TABLE_PREFIX, $sql));
		                    }
		                    if($error_queries[0]) { // Everything is OK, continue
                                /**********************************
                                 ** EXECUTING ADDITIONAL ACTIONS **
                                 **********************************/
		                        if(file_exists(osc_lib_path() . 'osclass/upgrade-funcs.php')) {
			                        // There should be no errors here
			                        require_once osc_lib_path() . 'osclass/upgrade-funcs.php';
		                        }
        		                // Additional actions is not important for the rest of the proccess
        		                // We will inform the user of the problems but the upgrade could continue
                                /****************************
                                 ** REMOVE TEMPORARY FILES **
                                 ****************************/
		                        $path = ABS_PATH . 'oc-temp';
		                        $rm_errors = 0;
		                        $dir = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::CHILD_FIRST);
		                        for ($dir->rewind(); $dir->valid(); $dir->next()) {
			                        if ($dir->isDir()) {
                                        if($dir->getFilename()!='.' && $dir->getFilename()!='..') {
                            				if(!rmdir($dir->getPathname())) {
                            				    $rm_errors++;
                            				}
                                        }
			                        } else {
				                        if(!unlink($dir->getPathname())) {
				                            $rm_errors++;
				                        }
			                        }
		                        }
		                        if(!rmdir($path)) {
		                            $rm_errors++;
		                        }
		                        if($rm_errors==0) {
		                            $message = __('Everything was OK! Your OSClass installation is updated');
		                        } else {
                                    $message = __('Almost everything was OK! Your OSClass installation is updated, but there were some errors removing temporary files. Please, remove manually the "oc-temp" folder', 'admin');
		                            $error = 6; // Some errors removing files
		                        }
		                    } else {
                                $sql_error_msg = $error_queries[2];
                                $message = __('Problems upgrading the database', 'admin');
                                $error = 5; // Problems upgrading the database		                
		                    }
			            } else {
                            $message = __('Problems copying files. Maybe permissions are not correct', 'admin');
				            $error = 4; // Problems copying files. Maybe permissions are not correct
			            }
		            } else {
                        $message = __('Nothing to copy', 'admin');
                        $error = 99; // Nothing to copy. THIS SHOULD NEVER HAPPENS, means we dont update any file!
		            }
			    } else {
        		    $message = __('Unzip failed', 'admin');
				    $error = 3; // Unzip failed
			    }
            } else {
    		    $message = __('Download failed', 'admin');
                $error = 2; // Download failed
            }
		} else {
		    $message = __('Missing download URL', 'admin');
			$error = 1; // Missing download URL
		}
		
		if($remove_error_msg!='') {
		    if($error==0) {
    		    $message .= "<br /><br />" . __('We had some errors removing files, those are not super-sensitive errors, so we continued upgrading your installation. Please remove the following files (you already have OSClass upgraded, but to ensure maximun performance)', 'admin');
            }
		}
		
        if($error==5) {
            $message .= "<br /><br />" . __('We had some errors upgrading your database. The follwing queries failed', 'admin') . implode("<br />", $sql_error_msg);
		}
		
		break;


}

echo $message;

