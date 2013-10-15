<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

    /**
     * Osclass - software for creating and publishing online classified advertising platforms
     *
     * Copyright (C) 2012 OSCLASS
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

    define('IS_AJAX', true);

    class CAdminAjax extends AdminSecBaseModel {

        function __construct()
        {
            parent::__construct();
            $this->ajax = true;
            if( $this->isModerator() ) {
                if( !in_array($this->action, array('items', 'media', 'comments', 'custom', 'runhook')) ) {
                    $this->action = 'error_permissions';
                }
            }
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
                case 'userajax': // This is the autocomplete AJAX
                    $users = User::newInstance()->ajax(Params::getParam("term"));
                    if(count($users)==0) {
                        echo json_encode(array(0 => array('id'=> '', 'label' => __('No results'), 'value' => __('No results')) ));
                    } else {
                        echo json_encode($users);
                    }
                    break;
                case 'date_format':
                    echo json_encode(array('format' => Params::getParam('format'), 'str_formatted' => osc_format_date(date('Y-m-d H:i:s'),Params::getParam('format'))));
                    break;
                case 'runhook': // run hooks
                    $hook = Params::getParam('hook');

                    if($hook == '') {
                        echo json_encode(array('error' => 'hook parameter not defined'));
                        break;
                    }

                    switch($hook) {
                        case 'item_form':
                            osc_run_hook('item_form', Params::getParam('catId'));
                        break;
                        case 'item_edit':
                            $catId  = Params::getParam("catId");
                            $itemId = Params::getParam("itemId");
                            osc_run_hook("item_edit", $catId, $itemId);
                        break;
                        default:
                            osc_run_hook('ajax_admin_' . $hook);
                        break;
                    }
                break;
                case 'categories_order': // Save the order of the categories
                    osc_csrf_check(false);
                    $aIds        = Params::getParam('list');
                    $order = array();
                    $error       = 0;

                    $catManager = Category::newInstance();
                    $aRecountCat = array();

                    foreach($aIds as $cat) {
                        if(!isset($order[$cat['p']])) {
                            $order[$cat['p']] = 0;
                        }

                        $res = $catManager->update(
                            array(
                                'fk_i_parent_id' => ($cat['p']=='root'?NULL:$cat['p']),
                                'i_position' => $order[$cat['p']]
                            ),
                            array('pk_i_id' => $cat['c'])
                        );
                        if( is_bool($res) && !$res ) {
                            $error = 1;
                        } else if($res==1) {
                            $aRecountCat[] = $cat['c'];
                        }
                        $order[$cat['p']] = $order[$cat['p']]+1;
                    }

                    // update category stats
                    foreach($aRecountCat as $rId) {
                        osc_update_cat_stats_id($rId);
                    }

                    if( $error ) {
                        $result = array( 'error' => __("An error occurred") );
                    } else {
                        $result = array( 'ok' => __("Order saved"));
                    }

                    echo json_encode($result);
                break;
                case 'category_edit_iframe':
                    $this->_exportVariableToView( 'category', Category::newInstance()->findByPrimaryKey( Params::getParam("id") ) );
                    if(count(Category::newInstance()->findSubcategories( Params::getParam("id") ) )>0) {
                        $this->_exportVariableToView( 'has_subcategories', true);
                    } else {
                        $this->_exportVariableToView( 'has_subcategories', false);
                    };
                    $this->_exportVariableToView( 'languages', OSCLocale::newInstance()->listAllEnabled() );
                    $this->doView("categories/iframe.php");
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
                    osc_csrf_check(false);
                    $error = 0;
                    $field = Field::newInstance()->findByName(Params::getParam("s_name"));

                    if (!isset($field['pk_i_id']) || (isset($field['pk_i_id']) && $field['pk_i_id'] == Params::getParam("id"))) {
                        // remove categories from a field
                        Field::newInstance()->cleanCategoriesFromField(Params::getParam("id"));
                        // no error... continue updating fields
                        if($error == 0) {
                            $slug = Params::getParam("field_slug") != '' ? Params::getParam("field_slug") : Params::getParam("s_name");
                            $slug_tmp = $slug = preg_replace('|([-]+)|', '-', preg_replace('|[^a-z0-9_-]|', '-', strtolower($slug)));
                            $slug_k = 0;
                            while(true) {
                                $field = Field::newInstance()->findBySlug($slug);
                                if(!$field || $field['pk_i_id']==Params::getParam("id")) {
                                    break;
                                } else {
                                    $slug_k++;
                                    $slug = $slug_tmp."_".$slug_k;
                                }
                            }

                            // trim options
                            $s_options = '';
                            $aux  = Params::getParam('s_options');
                            $aAux = explode(',', $aux);

                            foreach($aAux as &$option) {
                                $option = trim($option);
                            }

                            $s_options = implode(',', $aAux);

                            $res = Field::newInstance()->update(
                                    array(
                                        's_name'        => Params::getParam("s_name"),
                                        'e_type'        => Params::getParam("field_type"),
                                        's_slug'        => $slug,
                                        'b_required'    => Params::getParam("field_required") == "1" ? 1 : 0,
                                        'b_searchable'  => Params::getParam("field_searchable") == "1" ? 1 : 0,
                                        's_options'     => $s_options),
                                    array('pk_i_id' => Params::getParam("id"))
                                    );

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
                            $message = __("An error occurred while updating.");
                        }
                    } else {
                        $error = 1;
                        $message = __("Sorry, you already have a field with that name");
                    }

                    if($error) {
                        $result = array( 'error' => $message);
                    } else {
                        $result = array( 'ok' => __("Saved") , 'text' => Params::getParam("s_name"), 'field_id' => Params::getParam("id") );
                    }

                    echo json_encode($result);

                    break;
                case 'delete_field':
                    osc_csrf_check(false);
                    $res = Field::newInstance()->deleteByPrimaryKey(Params::getParam('id'));

                    if( $res > 0 ) {
                        $result = array('ok' => __('The custom field has been deleted'));
                    } else {
                        $result = array('error' => __('An error occurred while deleting'));
                    }

                    echo json_encode($result);
                break;
                case 'add_field':
                    osc_csrf_check(false);
                    $s_name = __('NEW custom field');
                    $slug_tmp = $slug = preg_replace('|([-]+)|', '-', preg_replace('|[^a-z0-9_-]|', '-', strtolower($s_name)));
                    $slug_k = 0;
                    while(true) {
                        $field = Field::newInstance()->findBySlug($slug);
                        if(!$field || $field['pk_i_id']==Params::getParam("id")) {
                            break;
                        } else {
                            $slug_k++;
                            $slug = $slug_tmp."_".$slug_k;
                        }
                    }
                    $fieldManager = Field::newInstance();
                    $result = $fieldManager->insertField($s_name, 'TEXT', $slug, 0, '', array());
                    if($result) {
                        echo json_encode(array('error' => 0, 'field_id' => $fieldManager->dao->insertedId(), 'field_name' => $s_name));
                    } else {
                        echo json_encode(array('error' => 1));
                    }
                    break;
                case 'enable_category':
                    osc_csrf_check(false);
                    $id       = strip_tags( Params::getParam('id') );
                    $enabled  = (Params::getParam('enabled') != '') ? Params::getParam('enabled') : 0;
                    $error    = 0;
                    $result   = array();
                    $aUpdated = array();

                    $mCategory = Category::newInstance();
                    $aCategory = $mCategory->findByPrimaryKey( $id );

                    if( $aCategory == false ) {
                        $result = array( 'error' => sprintf( __("No category with id %d exists"), $id) );
                        echo json_encode($result);
                        break;
                    }

                    // root category
                    if( $aCategory['fk_i_parent_id'] == '' ) {
                        $mCategory->update( array('b_enabled' => $enabled), array('pk_i_id'        => $id) );
                        $mCategory->update( array('b_enabled' => $enabled), array('fk_i_parent_id' => $id) );

                        $subCategories = $mCategory->findSubcategories( $id );

                        $aIds = array($id);
                        $aUpdated[] = array('id' => $id);
                        foreach( $subCategories as $subcategory ) {
                            $aIds[]     = $subcategory['pk_i_id'];
                            $aUpdated[] = array( 'id' => $subcategory['pk_i_id'] );
                        }

                        Item::newInstance()->enableByCategory($enabled, $aIds);

                        if( $enabled ) {
                            $result = array(
                                'ok' => __('The category as well as its subcategories have been enabled')
                            );
                        } else {
                            $result = array(
                                'ok' => __('The category as well as its subcategories have been disabled')
                            );
                        }
                        $result['affectedIds'] = $aUpdated;
                        echo json_encode($result);
                        break;
                    }

                    // subcategory
                    $parentCategory = $mCategory->findRootCategory( $id );
                    if( !$parentCategory['b_enabled'] ) {
                        $result = array( 'error' => __('Parent category is disabled, you can not enable that category') );
                        echo json_encode( $result );
                        break;
                    }

                    $mCategory->update( array('b_enabled' => $enabled), array('pk_i_id' => $id) );
                    if( $enabled ) {
                        $result = array(
                            'ok' => __('The subcategory has been enabled')
                        );
                    } else {
                        $result = array(
                            'ok' => __('The subcategory has been disabled')
                        );
                    }
                    $result['affectedIds'] = array( array('id' => $id) );
                    echo json_encode($result);

                    break;
                case 'delete_category':
                    osc_csrf_check(false);
                    $id = Params::getParam("id");
                    $error = 0;

                    $categoryManager = Category::newInstance();
                    $res = $categoryManager->deleteByPrimaryKey($id);

                    if($res > 0) {
                        $message = __('The categories have been deleted');
                    } else {
                        $error = 1;
                        $message = __('An error occurred while deleting');
                    }

                    if($error) {
                        $result = array( 'error' => $message);
                    } else {
                        $result = array( 'ok' => __("Saved") );
                    }
                    echo json_encode($result);

                    break;
                case 'edit_category_post':
                    osc_csrf_check(false);
                    $id = Params::getParam("id");
                    $fields['i_expiration_days'] = (Params::getParam("i_expiration_days") != '') ? Params::getParam("i_expiration_days") : 0;
                    $fields['b_price_enabled'] = (Params::getParam('b_price_enabled') != '') ? 1 : 0;
                    $apply_changes_to_subcategories = Params::getParam('apply_changes_to_subcategories')==1?true:false;

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
                                    $aFieldsDescription[$m[1]][$m[2]] = NULL;
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
                        $categoryManager->updateExpiration($id, $fields['i_expiration_days'], $apply_changes_to_subcategories);
                        $categoryManager->updatePriceEnabled($id, $fields['b_price_enabled'], $apply_changes_to_subcategories);
                        if( is_bool($res) ) {
                            $error = 2;
                        }
                    }

                    if($error==0) {
                        $msg = __("Category updated correctly");
                    } else if($error==1) {
                        if($has_one_title==1) {
                            $error = 4;
                            $msg = __('Category updated correctly, but some titles are empty');
                        } else {
                            $msg = __('Sorry, including at least a title is mandatory');
                        }
                    } else if($error==2) {
                        $msg = __('An error occurred while updating');
                    }
                    echo json_encode(array('error' => $error, 'msg' => $msg, 'text' => $aFieldsDescription[$l]['s_name']));

                    break;
                case 'custom': // Execute via AJAX custom file
                    if(Params::existParam('route')) {
                        $routes = Rewrite::newInstance()->getRoutes();
                        $rid = Params::getParam('route');
                        $file = '../';
                        if(isset($routes[$rid]) && isset($routes[$rid]['file'])) {
                            $file = $routes[$rid]['file'];
                        }
                    } else {
                        $file = Params::getParam("ajaxfile");
                    }

                    if($file == '') {
                        echo json_encode(array('error' => 'no action defined'));
                        break;
                    }

                    // valid file?
                    if( stripos($file, '../') !== false ) {
                        echo json_encode(array('error' => 'no valid file'));
                        break;
                    }

                    if( !file_exists(osc_plugins_path() . $file) ) {
                        echo json_encode(array('error' => "file doesn't exist"));
                        break;
                    }

                    require_once osc_plugins_path() . $file;
                break;
                case 'test_mail':
                    $title = sprintf( __('Test email, %s'), osc_page_title() );
                    $body  = __("Test email") . "<br><br>" . osc_page_title();

                    $emailParams = array(
                        'subject'  => $title,
                        'to'       => osc_contact_email(),
                        'to_name'  => 'admin',
                        'body'     => $body,
                        'alt_body' => $body
                    );

                    $array = array();
                    if( osc_sendMail($emailParams) ) {
                        $array = array('status' => '1', 'html' => __('Email sent successfully') );
                    } else {
                        $array = array('status' => '0', 'html' => __('An error occurred while sending email') );
                    }
                    echo json_encode($array);
                    break;
                case 'test_mail_template':
                    // replace por valores por defecto
                    $email = Params::getParam("email");
                    $title = Params::getParam("title");
                    $body  = urldecode(Params::getParam("body"));

                    $emailParams = array(
                        'subject'  => $title,
                        'to'       => $email,
                        'to_name'  => 'admin',
                        'body'     => $body,
                        'alt_body' => $body
                    );

                    $array = array();
                    if( osc_sendMail($emailParams) ) {
                        $array = array('status' => '1', 'html' => __('Email sent successfully') );
                    } else {
                        $array = array('status' => '0', 'html' => __('An error occurred while sending email') );
                    }
                    echo json_encode($array);
                    break;
                case 'order_pages':
                    osc_csrf_check(false);
                    $order = Params::getParam("order");
                    $id    = Params::getParam("id");
                    if($order != '' && $id != '') {
                        $mPages = Page::newInstance();
                        $actual_page  = $mPages->findByPrimaryKey($id);
                        $actual_order = $actual_page['i_order'];

                        $array     = array();
                        $condition = array();
                        $new_order = $actual_order;

                        if($order == 'up') {
                            $page = $mPages->findPrevPage($actual_order);
                        } else if($order == 'down') {
                            $page = $mPages->findNextPage($actual_order);
                        }
                        if(isset($page['i_order'])) {
                            $mPages->update(array('i_order' => $page['i_order']), array('pk_i_id' => $id));
                            $mPages->update(array('i_order' => $actual_order), array('pk_i_id' => $page['pk_i_id']));
                        }
                    }
                break;

                /******************************
                 ** COMPLETE UPGRADE PROCESS **
                 ******************************/
                case 'upgrade': // AT THIS POINT WE KNOW IF THERE'S AN UPDATE OR NOT
                    osc_csrf_check(false);
                    $message = "";
                    $error = 0;
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
                                    //TRY TO REMOVE THE ZIP PACKAGE
                                    @unlink(osc_content_path() . 'downloads/' . $filename);

                                    if ($fail == 0) { // Everything is OK, continue
                                        /************************
                                         *** UPGRADE DATABASE ***
                                         ************************/
                                        $error_queries = array();
                                        if (file_exists(osc_lib_path() . 'osclass/installer/struct.sql')) {
                                            $sql = file_get_contents(osc_lib_path() . 'osclass/installer/struct.sql');

                                            $conn = DBConnectionClass::newInstance();
                                            $c_db = $conn->getOsclassDb();
                                            $comm = new DBCommandClass( $c_db );
                                            $error_queries = $comm->updateDB( str_replace('/*TABLE_PREFIX*/', DB_TABLE_PREFIX, $sql) );

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
                                                $message = __('Everything looks good! Your Osclass installation is up-to-date');
                                            } else {
                                                $message = __('Nearly everything looks good! Your Osclass installation is up-to-date, but there were some errors removing temporary files. Please manually remove the "oc-temp" folder');
                                                $error = 6; // Some errors removing files
                                            }
                                        } else {
                                            $sql_error_msg = $error_queries[2];
                                            $message = __('Problems when upgrading the database');
                                            $error = 5; // Problems upgrading the database
                                        }
                                    } else {
                                        $message = __('Problems when copying files. Please check your permissions. ');
                                        $error = 4; // Problems copying files. Maybe permissions are not correct
                                    }
                                } else {
                                    $message = __('Nothing to copy');
                                    $error = 99; // Nothing to copy. THIS SHOULD NEVER HAPPEN, means we don't update any file!
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

                    if ($error == 5) {
                        $message .= "<br /><br />" . __('We had some errors upgrading your database. The follwing queries failed:') . implode("<br />", $sql_error_msg);
                    }
                    echo $message;

                    foreach ($perms as $k => $v) {
                        @chmod($k, $v);
                    }
                    break;

                /*******************************
                 ** COMPLETE MARKET PROCESS **
                 *******************************/
                case 'market': // AT THIS POINT WE KNOW IF THERE'S AN UPDATE OR NOT
                    osc_csrf_check(false);
                    $section = Params::getParam('section');
                    $code    = Params::getParam('code');
                    $plugin  = false;
                    $re_enable = false;
                    $message = "";
                    $error = 0;
                    $data = array();
                    /************************
                     *** CHECK VALID CODE ***
                     ************************/
                    if ($code != '' && $section != '') {
                        if(stripos($code, "http://")===FALSE) {
                            // OSCLASS OFFICIAL REPOSITORY
                            $url = osc_market_url($section, $code);
                            $data = json_decode(osc_file_get_contents($url), true);
                        } else {
                            // THIRD PARTY REPOSITORY
                            if(osc_market_external_sources()) {
                                $data = json_decode(osc_file_get_contents($code), true);
                            } else {
                                echo json_encode(array('error' => 8, 'error_msg' => __('No external sources are allowed')));
                                break;
                            }
                        }

                        /***********************
                         **** DOWNLOAD FILE ****
                         ***********************/
                        if( isset($data['s_update_url']) && isset($data['s_source_file']) && isset($data['e_type'])) {

                            if($data['e_type']=='THEME') {
                                $folder = 'themes/';
                            } else if($data['e_type']=='LANGUAGE') {
                                $folder = 'languages/';
                            } else { // PLUGINS
                                $folder = 'plugins/';
                                $plugin = Plugins::findByUpdateURI($data['s_update_url']);
                                if($plugin!=false) {
                                    if(Plugins::isEnabled($plugin)) {
                                        Plugins::runHook($plugin.'_disable');
                                        Plugins::deactivate($plugin);
                                        $re_enable = true;
                                    }
                                }
                            }

                            $filename = date('YmdHis')."_".osc_sanitize_string($data['s_title'])."_".$data['s_version'].".zip";
                            $url_source_file = $data['s_source_file'];

                            $result   = osc_downloadFile($url_source_file, $filename);

                            if ($result) { // Everything is OK, continue
                                /**********************
                                 ***** UNZIP FILE *****
                                 **********************/
                                @mkdir(osc_content_path() . 'downloads/oc-temp/', 0777);
                                $res = osc_unzip_file(osc_content_path() . 'downloads/' . $filename, osc_content_path() . 'downloads/oc-temp/');
                                if ($res == 1) { // Everything is OK, continue
                                    /**********************
                                     ***** COPY FILES *****
                                     **********************/
                                    $fail = -1;
                                    if ($handle = opendir(osc_content_path() . 'downloads/oc-temp')) {
                                        $folder_dest    = ABS_PATH . "oc-content/".$folder;

                                        if( function_exists('posix_getpwuid') ) {
                                            $current_user   = posix_getpwuid(posix_geteuid());
                                            $ownerFolder    = posix_getpwuid(fileowner($folder_dest));
                                        }

                                        $fail = 0;
                                        while (false !== ($_file = readdir($handle))) {
                                            if ($_file != '.' && $_file != '..') {
                                                $copyprocess = osc_copy(osc_content_path() . "downloads/oc-temp/" . $_file, $folder_dest . $_file);
                                                if ($copyprocess == false) {
                                                    $fail = 1;
                                                };
                                            }
                                        }
                                        closedir($handle);

                                        // Additional actions is not important for the rest of the proccess
                                        // We will inform the user of the problems but the upgrade could continue
                                        // Also remove the zip package
                                        /****************************
                                         ** REMOVE TEMPORARY FILES **
                                         ****************************/
                                        @unlink(osc_content_path() . 'downloads/' . $filename);
                                        $path = osc_content_path() . 'downloads/oc-temp';
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

                                        if ($fail == 0) { // Everything is OK, continue
                                            if($data['e_type']!='THEME' && $data['e_type']!='LANGUAGE') {
                                                if($plugin!=false && $re_enable) {
                                                    $enabled = Plugins::activate($plugin);
                                                    if($enabled) {
                                                        Plugins::runHook($plugin.'_enable');
                                                    }
                                                }

                                            }
                                            // recount plugins&themes for update
                                            if($section == 'plugins') {
                                                osc_check_plugins_update(true);
                                            } else if($section == 'themes') {
                                                osc_check_themes_update(true);
                                            } else if($section == 'languages') {
                                                // load oc-content/
                                                if( osc_checkLocales() ) {
                                                    $message .= __('The language has been installed correctly');
                                                } else {
                                                    $message .= __('There was a problem adding the language');
                                                    $error = 8;
                                                }
                                                osc_check_languages_update(true);
                                            }

                                            if ($rm_errors == 0) {
                                                $message = __('Everything looks good!');
                                                $error = 0;
                                            } else {
                                                $message = __('Nearly everything looks good! but there were some errors removing temporary files. Please manually remove the \"oc-temp\" folder');
                                                $error = 6; // Some errors removing files
                                            }
                                        } else {
                                            $message = __('Problems when copying files. Please check your permissions. ');

                                            if($current_user['uid'] != $ownerFolder['uid']) {
                                                if(function_exists('posix_getgrgid') ) {
                                                    $current_group  = posix_getgrgid( $current_user['gid']);
                                                    $message .= '<p><strong>' . sprintf(__('NOTE: Web user and destination folder user is not the same, you might have an issue there. <br/>Do this in your console:<br/>chown -R %s:%s %s'), $current_user['name'], $current_group['name'], $folder_dest).'</strong></p>';
                                                }
                                            }
                                            $error = 4; // Problems copying files. Maybe permissions are not correct
                                        }
                                    } else {
                                        $message = __('Nothing to copy');
                                        $error = 99; // Nothing to copy. THIS SHOULD NEVER HAPPEN, means we don't update any file!
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
                            $message = __('Input code not valid');
                            $error = 7; // Input code not valid
                        }
                    } else {
                        $message = __('Missing download URL');
                        $error = 1; // Missing download URL
                    }

                    echo json_encode(array('error' => $error, 'message' => $message, 'data' => $data));

                    break;
                case 'check_market': // AT THIS POINT WE KNOW IF THERE'S AN UPDATE OR NOT
                    $section = Params::getParam('section');
                    $code = Params::getParam('code');
                    $data = array();
                    /************************
                     *** CHECK VALID CODE ***
                     ************************/
                    if ($code != '' && $section != '') {
                        if(stripos($code, "http://")===FALSE) {
                            // OSCLASS OFFICIAL REPOSITORY
                            $data = json_decode(osc_file_get_contents(osc_market_url($section, $code)), true);
                        } else {
                            // THIRD PARTY REPOSITORY
                            if(osc_market_external_sources()) {
                                $data = json_decode(osc_file_get_contents($code), true);
                            } else {
                                echo json_encode(array('error' => 3, 'error_msg' => __('No external sources are allowed')));
                                break;
                            }
                        }
                        if( !isset($data['s_source_file']) || !isset($data['s_update_url'])) {
                            $data = array('error' => 2, 'error_msg' => __('Invalid code'));
                        }
                    } else {
                        $data = array('error' => 1, 'error_msg' => __('No code was submitted'));
                    }
                    echo json_encode($data);
                    break;
                case 'market_data':
                    $section  = Params::getParam('section');
                    $page     = Params::getParam("mPage");
                    $featured = Params::getParam("featured");

                    $sort     = Params::getParam("sort");
                    $order    = Params::getParam("order");

                    // for the moment this value is static
                    $length   = 9;

                    if($page>=1) $page--;

                    $url  = osc_market_url($section)."page/".$page.'/';

                    if($length!='' && is_numeric($length)) {
                        $url .= 'length/'.$length.'/';
                    }

                    if($sort!='') {
                        $url .= 'order/'.$sort;
                        if($order!='') {
                            $url .= '/'.$order;
                        }
                    }

                    if($featured != ''){
                        $url = osc_market_featured_url($section);
                    }

                    $data = array();

                    $data = json_decode(osc_file_get_contents($url), true);

                    if( !isset($data[$section])) {
                        $data = array('error' => 1, 'error_msg' => __('No market data'));
                    }
                    echo 'var market_data = window.market_data || {}; market_data.'.$section.' = '.json_encode($data).';';

                    break;
                case 'local_market': // AVOID CROSS DOMAIN PROBLEMS OF AJAX REQUEST
                    $marketPage = Params::getParam("mPage");
                    if($marketPage>=1) $marketPage--;

                    $out    = osc_file_get_contents(osc_market_url(Params::getParam("section"))."page/".$marketPage);
                    $array  = json_decode($out, true);
                    // do pagination
                    $pageActual = $array['page'];
                    $totalPages = ceil( $array['total'] / $array['sizePage'] );
                    $params     = array(
                        'total'    => $totalPages,
                        'selected' => $pageActual,
                        'url'      => '#{PAGE}',
                        'sides'    => 5
                    );
                    // set pagination
                    $pagination = new Pagination($params);
                    $aux = $pagination->doPagination();
                    $array['pagination_content'] = $aux;
                    // encode to json
                    echo json_encode($array);
                    break;
                case 'dashboardbox_market':
                    $error = 0;
                    // make market call
                    $url = osc_get_preference('marketURL') . 'dashboardbox/';

                    $content = '';
                    if(false===($json=@osc_file_get_contents($url))) {
                        $error = 1;
                    } else {
                        $content = $json;
                    }

                    if($error==1) {
                        echo json_encode(array('error' => 1));
                    } else {
                        // replace content with correct urls
                        $content = str_replace('{URL_MARKET_THEMES}'    , osc_admin_base_url(true).'?page=market&action=themes' , $content);
                        $content = str_replace('{URL_MARKET_PLUGINS}'   , osc_admin_base_url(true).'?page=market&action=plugins', $content);
                        echo json_encode(array('html' => $content) );
                    }
                    break;
                case 'location_stats':
                    osc_csrf_check(false);
                    $workToDo = osc_update_location_stats();
                    if( $workToDo > 0 ) {
                        $array['status']  = 'more';
                        $array['pending'] = $workToDo;
                        echo json_encode($array);
                    } else {
                        $array['status']  = 'done';
                        echo json_encode($array);
                    }
                    break;
                case 'country_slug':
                    $exists = Country::newInstance()->findBySlug(Params::getParam('slug'));
                    if(isset($exists['s_slug'])) {
                        echo json_encode(array('error' => 1, 'country' => $exists));
                    } else {
                        echo json_encode(array('error' => 0));
                    }
                    break;
                case 'region_slug':
                    $exists = Region::newInstance()->findBySlug(Params::getParam('slug'));
                    if(isset($exists['s_slug'])) {
                        echo json_encode(array('error' => 1, 'region' => $exists));
                    } else {
                        echo json_encode(array('error' => 0));
                    }
                    break;
                case 'city_slug':
                    $exists = City::newInstance()->findBySlug(Params::getParam('slug'));
                    if(isset($exists['s_slug'])) {
                        echo json_encode(array('error' => 1, 'city' => $exists));
                    } else {
                        echo json_encode(array('error' => 0));
                    }
                    break;
                case 'error_permissions':
                    echo json_encode(array('error' => __("You don't have the necessary permissions")));
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
