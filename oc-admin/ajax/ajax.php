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

    define('IS_AJAX', true) ;

    class CAdminAjax extends AdminSecBaseModel {

        function __construct()
        {
            parent::__construct();
            $this->ajax = true ;
        }

        //Business Layer...
        function doModel()
        {
            //specific things for this class
            switch ($this->action) {
                case 'bulk_actions':
                    break;
                case 'regions': //Return regions given a countryId
                    $regions = Region::newInstance()->findByCountry(Params::getParam("countryId"));
                    echo json_encode($regions);
                    break;
                case 'cities': //Returns cities given a regionId
                    $cities = City::newInstance()->findByRegion(Params::getParam("regionId"));
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
                    if ($alert != '' && $email != '') {
                        Alerts::newInstance()->insert(array('fk_i_user_id' => $userid, 's_email' => $email, 's_search' => $alert, 'e_type' => 'DAILY'));
                        echo "1";
                        return true;
                    }
                    echo '0';
                    break;
                case 'runhook': //Run hooks
                    $hook = Params::getParam("hook");
                    switch ($hook) {
                        case 'item_form':
                            $catId = Params::getParam("catId");
                            if ($catId != '') {
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
                            if ($hook == '') {
                                return false;
                            } else {
                                osc_run_hook($hook);
                            }
                            break;
                    }
                    break;
                case 'items': // Return items (use external file oc-admin/ajax/item_processing.php)
                    require_once osc_admin_base_path() . 'ajax/items_processing.php';
                    $items_processing = new ItemsProcessingAjax(Params::getParamsAsArray("get"));
                    break;
                case 'media': // Return items (use external file oc-admin/ajax/media_processing.php)
                    require_once osc_admin_base_path() . 'ajax/media_processing.php';
                    $media_processing = new MediaProcessingAjax(Params::getParamsAsArray("get"));
                    break;
                case 'categories_order': // Save the order of the categories
                    $aIds = Params::getParam('list');
                    $orderParent = 0;
                    $orderSub = 0;
                    $catParent = 0;

                    $catManager = Category::newInstance();

                    foreach ($aIds as $id => $parent) {
                        if ($parent == 'root') {
                            $res = $catManager->updateOrder($id, $orderParent);
                            if (is_bool($res) && !$res) {
                                $error = 1;
                            }
                            // set parent category 
                            $conditions = array('pk_i_id' => $id);
                            $array['fk_i_parent_id'] = NULL;
                            $res = $catManager->update($array, $conditions);
                            if (is_bool($res) && !$res) {
                                $error = 1;
                            }
                            $orderParent++;
                        } else {
                            if ($parent != $catParent) {
                                $catParent = $parent;
                                $orderSub = 0;
                            }
                            
                            $res = $catManager->updateOrder($id, $orderSub);
                            if (is_bool($res) && !$res ) {
                                $error = 1;
                            }

                            // set parent category 
                            $conditions = array('pk_i_id' => $id);
                            $array['fk_i_parent_id'] = $catParent;
                            
                            $res = $catManager->update($array, $conditions);
                            if (is_bool($res) && !$res) {
                                $error = 1;
                            }
                            $orderSub++;
                        }
                    }

                    if($error) {
                        $result = array( 'error' => __("Some error ocurred") ) ;
                    } else {
                        $result = array( 'ok' => __("Order saved") ) ;
                    }
                    echo json_encode($result) ;
                    
                    break;
                case 'category_edit_iframe':
                    $this->_exportVariableToView( 'category', Category::newInstance()->findByPrimaryKey( Params::getParam("id") ) ) ;
                    $this->_exportVariableToView( 'languages', OSCLocale::newInstance()->listAllEnabled() ) ;
                    $this->doView("categories/iframe.php") ;
                    break;
                case 'field_categories_iframe':
                    $selected = Field::newInstance()->categories(Params::getParam("id"));
                    if ($selected == null) {
                        $selected = array();
                    };
                    $this->_exportVariableToView("selected", $selected);
                    $this->_exportVariableToView("field", Field::newInstance()->findByPrimaryKey(Params::getParam("id")));
                    $this->_exportVariableToView("categories", Category::newInstance()->toTreeAll());
                    $this->doView("fields/iframe.php");
                    break;
                case 'field_categories_post':
                    $error = 0;
                    $field = Field::newInstance()->findByName(Params::getParam("s_name"));
                    
                    if (!isset($field['pk_i_id']) || (isset($field['pk_i_id']) && $field['pk_i_id'] == Params::getParam("id"))) {
                        // remove categories from a field
                        Field::newInstance()->cleanCategoriesFromField(Params::getParam("id"));
                        // no error... continue updating fields
                        if($error == 0) {
                            $slug = Params::getParam("field_slug") != '' ? Params::getParam("field_slug") : Params::getParam("id");
                            $slug = preg_replace('|([-]+)|', '-', preg_replace('|[^a-z0-9_-]|', '-', strtolower($slug)));
                            $res = Field::newInstance()->update(array('s_name' => Params::getParam("s_name"), 'e_type' => Params::getParam("field_type"), 's_slug' => $slug, 'b_required' => Params::getParam("field_required") == "1" ? 1 : 0, 's_options' => Params::getParam('s_options')), array('pk_i_id' => Params::getParam("id")));
                            if(is_bool($res) && !$res) {
                                $error = 1;
                            }
                        }
                        // no error... continue inserting categories-field
                        if($error == 0) {
                            $aCategories = Params::getParam("categories");
                            if( is_array($aCategories) && count($aCategories) > 0) {
                                $res = Field::newInstance()->insertCategories(Params::getParam("id"), $aCategories);
                                if(!$res) {
                                    $error = 1;
                                }
                            }
                        }
                        // error while updating?
                        if($error == 1) {
                            $message = __("Error while updating.");
                        }
                    } else {
                        $error = 1;
                        $message = __("Sorry, you already have one field with that name");
                    }

                    if($error) {
                        $result = array( 'error' => $message) ;
                    } else {
                        $result = array( 'ok' => __("Saved") , 'text' => Params::getParam("s_name")) ;
                    }
                    
                    echo json_encode($result) ;
                    
                    break;
                case 'delete_field':
                    $id = Params::getParam("id");
                    $error = 0;

                    $fieldManager = Field::newInstance();
                    $res = $fieldManager->deleteByPrimaryKey($id);
                    
                    if($res > 0) {
                        $message = __('The custom field have been deleted');
                    } else {
                        $error = 1;
                        $message = __('Error while deleting');
                    }

                    if($error) {
                        $result = array( 'error' => $message) ;
                    } else {
                        $result = array( 'ok' => __("Saved") ) ;
                    }
                    echo json_encode($result) ;

                    break;
                case 'enable_category':
                    $id       = strip_tags( Params::getParam('id') ) ;
                    $enabled  = (Params::getParam('enabled') != '') ? Params::getParam('enabled') : 0 ;
                    $error    = 0 ;
                    $result   = array() ;
                    $aUpdated = array() ;

                    $mCategory = Category::newInstance() ;
                    $aCategory = $mCategory->findByPrimaryKey( $id ) ;

                    if( $aCategory == false ) {
                        $result = array( 'error' => sprintf( __("It doesn't exist a category with this id: %d"), $id) ) ;
                        echo json_encode($result) ;
                        break ;
                    }

                    // root category
                    if( $aCategory['fk_i_parent_id'] == '' ) {
                        $mCategory->update( array('b_enabled' => $enabled), array('pk_i_id'        => $id) ) ;
                        $mCategory->update( array('b_enabled' => $enabled), array('fk_i_parent_id' => $id) ) ;

                        $subCategories = $mCategory->findSubcategories( $id ) ;

                        $aUpdated[] = array('id' => $id) ;
                        foreach( $subCategories as $subcategory ) {
                            $aUpdated[] = array( 'id' => $subcategory['pk_i_id'] ) ;
                        }

                        if( $enabled ) {
                            $result = array(
                                'ok' => __('The category and its subcategories have been enabled')
                            ) ;
                        } else {
                            $result = array(
                                'ok' => __('The category and its subcategories have been disabled')
                            ) ;
                        }
                        $result['affectedIds'] = $aUpdated ;
                        echo json_encode($result) ;
                        break ;
                    }

                    // subcategory
                    $parentCategory = $mCategory->findRootCategory( $id ) ;
                    if( !$parentCategory['b_enabled'] ) {
                        $result = array( 'error' => __('Parent category is disabled, you can not enable that category') ) ;
                        echo json_encode( $result ) ;
                        break ;
                    }

                    $mCategory->update( array('b_enabled' => $enabled), array('pk_i_id' => $id) ) ;
                    if( $enabled ) {
                        $result = array(
                            'ok' => __('The subcategory has been enabled')
                        ) ;
                    } else {
                        $result = array(
                            'ok' => __('The subcategory has been disabled')
                        ) ;
                    }
                    $result['affectedIds'] = array( array('id' => $id) ) ;
                    echo json_encode($result) ;
                    
                    break ;
                case 'delete_category':
                    $id = Params::getParam("id");
                    $error = 0;
                    
                    $categoryManager = Category::newInstance();
                    $res = $categoryManager->deleteByPrimaryKey($id);
                    
                    if($res > 0) {
                        $message = __('The categories have been deleted');
                    } else {
                        $error = 1;
                        $message = __('Error while deleting');
                    }

                    if($error) {
                        $result = array( 'error' => $message) ;
                    } else {
                        $result = array( 'ok' => __("Saved") ) ;
                    }
                    echo json_encode($result) ;
                    
                    break;
                case 'edit_category_post':
                    $id = Params::getParam("id");
                    $fields['i_expiration_days'] = (Params::getParam("i_expiration_days") != '') ? Params::getParam("i_expiration_days") : 0;

                    $error = 0;
                    $has_one_title = 0;
                    $postParams = Params::getParamsAsArray();
                    foreach ($postParams as $k => $v) {
                        if (preg_match('|(.+?)#(.+)|', $k, $m)) {
                            if ($m[2] == 's_name') {
                                if ($v != "") {
                                    $has_one_title = 1;
                                    $aFieldsDescription[$m[1]][$m[2]] = $v;
                                    $s_text = $v;
                                } else {
                                    $aFieldsDescription[$m[1]][$m[2]] = ' ';
                                    $error = 1;
                                }
                            } else {
                                $aFieldsDescription[$m[1]][$m[2]] = $v;
                            }
                        }
                    }

                    $l = osc_language();
                    if ($error==0 || ($error==1 && $has_one_title==1)) {
                        $categoryManager = Category::newInstance();
                        $res = $categoryManager->updateByPrimaryKey(array('fields' => $fields, 'aFieldsDescription' => $aFieldsDescription), $id);
                        
                        if( is_bool($res) ) {
                            $error = 2;
                        }
                    }
                    
                    if($error==0) {
                        $msg = __("Category updated correctly");
                    } else if($error==1) {
                        if($has_one_title==1) {
                            $error = 4;
                            $msg = __('Category updated correctly, but some titles were empty');
                        } else {
                            $msg = __('Sorry, at least a title is needed');
                        }
                    } else if($error==2) {
                        $msg = __('Error while updating');
                    }
                    echo json_encode(array('error' => $error, 'msg' => $msg, 'text' => $aFieldsDescription[$l]['s_name']));
                    
                    break;
                case 'custom': // Execute via AJAX custom file
                    $ajaxfile = Params::getParam("ajaxfile");
                    if ($ajaxfile != '') {
                        require_once osc_admin_base_path() . $ajaxfile;
                    } else {
                        echo json_encode(array('error' => __('no action defined')));
                    }
                    break;
                case 'test_mail':
                    $title = __('Test email').", ".osc_page_title();
                    $body  = __("Test email")."<br><br>".osc_page_title();

                    $emailParams = array(
                                'subject'  => $title
                                ,'to'       => osc_contact_email()
                                ,'to_name'  => 'admin'
                                ,'body'     => $body
                                ,'alt_body' => $body
                    ) ;

                    $array = array();
                    if( osc_sendMail($emailParams) ) {
                        $array = array('status' => '1', 'html' => __('Email sent successfully'));
                    } else {
                        $array = array('status' => '0', 'html' => __('An error has occurred while sending email'));
                    }
                    echo json_encode($array);
                    break;
                case 'order_pages':
                    $order = Params::getParam("order");
                    $id    = Params::getParam("id");
                    $count = osc_count_static_pages();
                    if($order != '' && $id != '') {
                        $mPages = Page::newInstance();
                        $actual_page  = $mPages->findByPrimaryKey($id);
                        $actual_order = $actual_page['i_order'];

                        $array     = array();
                        $condition = array();
                        $new_order = $actual_order;

                        if($order == 'up') {
                            if($actual_order > 0) {
                                $new_order = $actual_order-1;
                            }
                        } else if($order == 'down') {
                            if($actual_order != ($count-1)) {
                                $new_order = $actual_order+1;
                            }
                        }
                        
                        if($new_order != $actual_order) {
                            $auxpage = $mPages->findByOrder($new_order);

                            $array      = array('i_order' => $actual_order );
                            $conditions = array('pk_i_id' => $auxpage['pk_i_id']);
                            $mPages->update($array, $conditions);

                            $array      = array('i_order' => $new_order );
                            $conditions = array('pk_i_id' => $id);
                            $mPages->update($array, $conditions);

                        }
                        // TO BE IMPROVED
                        // json for datatables
                        $prefLocale = osc_current_admin_locale();
                        $aPages = $mPages->listAll(0);
                        $json = "[";
                        foreach($aPages as $key => $page) {

                            $body = array();
                            
                            if(isset($page['locale'][$prefLocale]) && !empty($page['locale'][$prefLocale]['s_title'])) {
                                $body = $page['locale'][$prefLocale];
                            } else {
                                $body = current($page['locale']);
                            }
                            $p_body =  str_replace("'", "\'", trim(strip_tags($body['s_title']), "\x22\x27"));

                            $json .= "[\"<input type='checkbox' name='id[]' value='". $page['pk_i_id'] ."' />\",";
                            $json .= "\"".osc_esc_html($page['s_internal_name'])."<div id='datatables_quick_edit'>";
                            $json .= "<a href='". osc_static_page_url() ."'>". __('View page') ."</a> | ";
                            $json .= "<a href='". osc_admin_base_url(true) ."?page=pages&action=edit&id=". $page['pk_i_id'] ."'>";
                            $json .= __('Edit') ."</a>";
                            if(!$page['b_indelible']) {
                                $json .= " | ";
                                $json .= "<a onclick=\\\"javascript:return confirm('";
                                $json .= __('This action can\\\\\'t be undone. Are you sure you want to continue?') ."')\\\" ";
                                $json .= " href='". osc_admin_base_url(true) ."?page=pages&action=delete&id=". $page['pk_i_id'] ."'>";
                                $json .= __('Delete') ."</a>";
                            }
                            $json .= "</div>\",";
                            $json .= "\"".$p_body."\",";
                            $json .= "\"<img id='up' onclick='order_up(". $page['pk_i_id'] .");' style='cursor:pointer;width:15;height:15px;' src='". osc_current_admin_theme_url('images/arrow_up.png') ."'/> <br/> <img id='down' onclick='order_down(". $page['pk_i_id'] .");' style='cursor:pointer;width:15;height:15px;' src='". osc_current_admin_theme_url('images/arrow_down.png')."'/>\"]";

                            if( $key != count($aPages)-1 ){ $json .= ','; } else { $json .= ''; }
                        }
                        $json .= "]";
                        echo $json;
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

                    $maintenance_file = ABS_PATH . '.maintenance';
                    $fileHandler = @fopen($maintenance_file, 'w');
                    fclose($fileHandler);

                    /***********************
                     **** DOWNLOAD FILE ****
                     ***********************/
                    $data = osc_file_get_contents("http://osclass.org/latest_version.php");
                    $data = json_decode(substr($data, 1, strlen($data)-3), true);
                    $source_file = $data['url'];
                    if ($source_file != '') {

                        $tmp = explode("/", $source_file);
                        $filename = end($tmp);
                        $result = osc_downloadFile($source_file, $filename);

                        if ($result) { // Everything is OK, continue
                            /**********************
                             ***** UNZIP FILE *****
                             **********************/
                            @mkdir(ABS_PATH . 'oc-temp', 0777);
                            $res = osc_unzip_file(osc_content_path() . 'downloads/' . $filename, ABS_PATH . 'oc-temp/');
                            if ($res == 1) { // Everything is OK, continue
                                /**********************
                                 ***** COPY FILES *****
                                 **********************/
                                $fail = -1;
                                if ($handle = opendir(ABS_PATH . 'oc-temp')) {
                                    $fail = 0;
                                    while (false !== ($_file = readdir($handle))) {
                                        if ($_file != '.' && $_file != '..' && $_file != 'remove.list' && $_file != 'upgrade.sql' && $_file != 'customs.actions') {
                                            $data = osc_copy(ABS_PATH . "oc-temp/" . $_file, ABS_PATH . $_file);
                                            if ($data == false) {
                                                $fail = 1;
                                            };
                                        }
                                    }
                                    closedir($handle);

                                    if ($fail == 0) { // Everything is OK, continue
                                        /**********************
                                         **** REMOVE FILES ****
                                         **********************/
                                        if (file_exists(ABS_PATH . 'oc-temp/remove.list')) {
                                            $lines = file(ABS_PATH . 'oc-temp/remove.list', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                                            foreach ($lines as $line_num => $r_file) {
                                                $unlink = @unlink(ABS_PATH . $r_file);
                                                if (!$unlink) {
                                                    $remove_error_msg .= sprintf(__('Error removing file: %s'), $r_file) . "<br/>";
                                                }
                                            }
                                        }
                                        // Removing files is not important for the rest of the proccess
                                        // We will inform the user of the problems but the upgrade could continue
                                        /************************
                                         *** UPGRADE DATABASE ***
                                         ************************/
                                        $error_queries = array();
                                        if (file_exists(osc_lib_path() . 'osclass/installer/struct.sql')) {
                                            $sql = file_get_contents(osc_lib_path() . 'osclass/installer/struct.sql');
                                            
                                            $conn = DBConnectionClass::newInstance();
                                            $c_db = $conn->getOsclassDb() ;
                                            $comm = new DBCommandClass( $c_db ) ;
                                            $error_queries = $comm->updateDB( str_replace('/*TABLE_PREFIX*/', DB_TABLE_PREFIX, $sql) ) ;
                                            
                                        }
                                        if ($error_queries[0]) { // Everything is OK, continue
                                            /**********************************
                                             ** EXECUTING ADDITIONAL ACTIONS **
                                             **********************************/
                                            if (file_exists(osc_lib_path() . 'osclass/upgrade-funcs.php')) {
                                                // There should be no errors here
                                                define('AUTO_UPGRADE', true);
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
                                                    if ($dir->getFilename() != '.' && $dir->getFilename() != '..') {
                                                        if (!rmdir($dir->getPathname())) {
                                                            $rm_errors++;
                                                        }
                                                    }
                                                } else {
                                                    if (!unlink($dir->getPathname())) {
                                                        $rm_errors++;
                                                    }
                                                }
                                            }
                                            if (!rmdir($path)) {
                                                $rm_errors++;
                                            }
                                            $deleted = @unlink(ABS_PATH . '.maintenance');
                                            if ($rm_errors == 0) {
                                                $message = __('Everything was OK! Your OSClass installation is updated');
                                            } else {
                                                $message = __('Almost everything was OK! Your OSClass installation is updated, but there were some errors removing temporary files. Please, remove manually the "oc-temp" folder');
                                                $error = 6; // Some errors removing files
                                            }
                                        } else {
                                            $sql_error_msg = $error_queries[2];
                                            $message = __('Problems upgrading the database');
                                            $error = 5; // Problems upgrading the database		                
                                        }
                                    } else {
                                        $message = __('Problems copying files. Maybe permissions are not correct');
                                        $error = 4; // Problems copying files. Maybe permissions are not correct
                                    }
                                } else {
                                    $message = __('Nothing to copy');
                                    $error = 99; // Nothing to copy. THIS SHOULD NEVER HAPPENS, means we dont update any file!
                                }
                            } else {
                                $message = __('Unzip failed');
                                $error = 3; // Unzip failed
                            }
                        } else {
                            $message = __('Download failed');
                            $error = 2; // Download failed
                        }
                    } else {
                        $message = __('Missing download URL');
                        $error = 1; // Missing download URL
                    }

                    if ($remove_error_msg != '') {
                        if ($error == 0) {
                            $message .= "<br /><br />" . __('We had some errors removing files, those are not super-sensitive errors, so we continued upgrading your installation. Please remove the following files (you already have OSClass upgraded, but to ensure maximun performance)');
                        }
                    }

                    if ($error == 5) {
                        $message .= "<br /><br />" . __('We had some errors upgrading your database. The follwing queries failed') . implode("<br />", $sql_error_msg);
                    }
                    echo $message;

                    foreach ($perms as $k => $v) {
                        @chmod($k, $v);
                    }
                    break;
                default:
                    echo json_encode(array('error' => __('no action defined')));
                    break;
            }
            // clear all keep variables into session
            Session::newInstance()->_dropKeepForm();
            Session::newInstance()->_clearVariables();
        }

        //hopefully generic...
        function doView($file) {
            osc_current_admin_theme_path($file);
        }
    }

?>