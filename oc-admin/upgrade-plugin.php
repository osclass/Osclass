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
		$message = __("Checking for update files.");
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
				rmdir($dir->getPathname());
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

		if (osc_zipFolder($archive_folder, $archive_name)) {
			$message = __('Archiving is sucessful!');
		} else {
		    $message = __('Error, can\'t create a zip file!');
		}
		break;

	case 'unzip-file':
		if(Params::getParam('file')!='') {
			$zip = new ZipArchive;
			$res = $zip->open(ABS_PATH.'oc-content/downloads/'.Params::getParam('file'));
			if ($res === TRUE) {
				@mkdir(ABS_PATH.'oc-temp', 0777);
				$zip->extractTo(ABS_PATH.'oc-temp/');
				$zip->close();
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
					$fail += osc_copy(ABS_PATH."/oc-temp/".$_file, ABS_PATH.'oc-content/plugins/'.$_file);
				}
			}

			closedir($handle);

			switch($fail) {
			    case(0): $message = __('There were problems copying files') ;
                break;
			    case(1): $message = __('Nothing to copy') ;
			    break;
			    default: $message = __('Files copied') ;
			}

		} else {
			$message = __('Nothing to copy') ;
		}
		
		break;

	case 'execute-sql':
		if(file_exists(ABS_PATH.'oc-temp/upgrade.sql')) {
			$sql = file_get_contents(ABS_PATH.'oc-temp/upgrade.sql') ;
			$conn = getConnection() ;
	        $conn->osc_dbImportSQL($sql) ;
			$message = __('upgrade.sql executed') ;
		} else {
			$message = __('No SQL to execute') ;
		}
		
		break;

	case 'execute-actions':
		if(file_exists(ABS_PATH.'oc-temp/custom.actions')) {
			require_once ABS_PATH . 'oc-temp/custom.actions' ;
			$message = __('Custom actions executed') ;
		} else {
			$message = __('No action to execute') ;
		}
		
		break;

	default:
		
		$info = osc_getPluginInfo($_REQUEST['plugin']);
		osc_renderAdminSection('tools/upgrade-plugins.php', __('Update'));
		break;

}

echo $message;

