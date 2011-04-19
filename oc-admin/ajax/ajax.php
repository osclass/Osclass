<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

    /**
     * OSClass – software for creating and publishing online classified advertising platforms
     *
     * Copyright (C) 2010 OSCLASS
     *
     * This program is free software: you can redistribute it and/or modify it under the terms
     * of the GNU Affero General Public License as published by the Free Software Foundation,
     * either version 3 of the License, or (at your option) any later version.
     *
     * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
     * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
     * See the GNU Affero General Public License for more details.
     *
     * You should have received a copy of the GNU Affero General Public
     * License along with this program. If not, see <http://www.gnu.org/licenses/>.
     */

    class CAdminAjax extends AdminSecBaseModel
    {
        function __construct() {
            parent::__construct() ;
        }

        //Business Layer...
        function doModel() {
            //specific things for this class
            switch ($this->action)
            {
                case 'bulk_actions':
                break;
                
                case 'regions': //Return regions given a countryId
                    $regions = Region::newInstance()->getByCountry(Params::getParam("countryId"));
                    echo json_encode($regions);
                    break;
                
                case 'cities': //Returns cities given a regionId
                    $cities = City::newInstance()->getByRegion(Params::getParam("regionId"));
                    echo json_encode($cities);
                    break;
                
                case 'location': // This is the autocomplete AJAX
                    $cities = City::newInstance()->ajax(Params::getParam("term"));
                    echo json_encode($cities);
                    break;
                    
                case 'alerts': // Allow to register to an alert given (not sure it's used on admin)
                    $alert = Params::getParam("alert");
                    $email = Params::getParam("email");
                    $userid = Params::getParam("userid");
                    if($alert!='' && $email!='') {
                            Alerts::newInstance()->insert(array( 'fk_i_user_id' => $userid, 's_email' => $email, 's_search' => $alert, 'e_type' => 'DAILY'));
                        echo "1";
                        return true;
                    }
                    echo '0';
                    return false;
                    break;
                    
                case 'runhook': //Run hooks
                    $hook = Params::getParam("hook");
                    switch ($hook) {

                        case 'item_form':
                            $catId = Params::getParam("catId");
                            if($catId!='') {
                                osc_run_hook("item_form", $catId);
                            } else {
                                osc_run_hook("item_form");
                            }
                            break;
                            
                        case 'item_edit':
                            $catId = Params::getParam("catId");
                            $itemId = Params::getParam("itemId");
                            osc_run_hook("item_edit", $catId, $itemId);
                            break;
                            
                        default:
                            if($hook=='') { return false; } else { osc_run_hook($hook); }
                            break;
                    }
                    break;
                    
                case 'items': // Return items (use external file oc-admin/ajax/item_processing.php)
                    require_once osc_admin_base_path() . 'ajax/items_processing.php';
                    $items_processing = new items_processing_ajax(Params::getParamsAsArray("get"));
                    break;

                case 'categories_order': // Save the order of the categories
                    $ids = explode(",", Params::getParam('order'));
                    $var_l = count($ids)-1;
                    $catManager = Category::newInstance();
                    for($var_o=0;$var_o<$var_l;$var_o++) {
                        echo $catManager->update_order($ids[$var_o], $var_o);
                    }
                    echo '1';
                    break;

                case 'categories_name': // Save the category's name in quick edit
                    $id = explode("_", Params::getParam('div'));
                    $name = Params::getParam('name');
                    $locale = Params::getParam('locale');
                    Category::newInstance()->update_name($id, $locale, $name);
                    echo '1';
                    break;

                case 'custom': // Execute via AJAX custom file
                    $ajaxfile = Params::getParam("ajaxfile");
                    if($ajaxfile!='') {
                        require_once osc_admin_base_path() . $ajaxfile;
                    } else {
                        echo json_encode(array('error' => __('no action defined')));
                    }
                    break;
                    
                    
                /******************************
                 ** COMPLETE UPGRADE PROCESS **
                 ******************************/
	            case 'upgrade': // AT THIS POINT WE KNOW IF THERE'S AN UPDATE OR NOT
	
	                $message = "";
	                $error = 0;
                    $remove_error_msg = "";
                    $sql_error_msg = "";
                    $rm_errors = 0;
                    $perms = osc_save_permissions();
                    osc_change_permissions();


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
				                                if(!$unlink) { $remove_error_msg .= sprintf(__('Error removing file: %s'), $r_file) . "<br/>"; }
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
		            echo $message;

                    foreach($perms as $k => $v) {
                        chmod($k, $v);
                    }
		            break;
                    
                default:
                    echo json_encode(array('error' => __('no action defined')));
                    break;
            }
        }
        
        //hopefully generic...
        function doView($file) {
            osc_current_admin_theme_path($file) ;
        }

    }

?>