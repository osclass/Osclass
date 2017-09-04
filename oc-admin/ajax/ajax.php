<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

/*
 * Copyright 2014 Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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
                    $aIds        = json_decode(Params::getParam('list'), true);
                    $order       = array();
                    $error       = 0;

                    $catManager  = Category::newInstance();
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

                    osc_run_hook('edited_category_order', $error);

                    echo json_encode($result);
                break;
                case 'category_edit_iframe':
                    $this->_exportVariableToView('category', Category::newInstance()->findByPrimaryKey(Params::getParam("id"), 'all'));
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

                    osc_run_hook('edited_category', (int)($id), $error);

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
                    if( stripos($file, '../') !== false || stripos($file, '..\\') !== false ) {
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
                    $body  = Params::getParam("body", false, false);

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
                case 'check_version':
                    $data = osc_file_get_contents('https://osclass.org/latest_version_v1.php?callback=?');
                    $data = preg_replace('|^\?\((.*?)\);$|', '$01', $data);
                    $json = json_decode($data);
                    if(isset($json->version)) {
                        if ($json->version > osc_version()) {
                            osc_set_preference('update_core_json', $data);
                            echo json_encode(array('error' => 0, 'msg' => __('Update available')));
                        } else {
                            osc_set_preference('update_core_json', '');
                            echo json_encode(array('error' => 0, 'msg' => __('No update available')));
                        }
                        osc_set_preference( 'last_version_check', time() );
                    } else { // Latest version couldn't be checked (site down?)
                        osc_set_preference( 'last_version_check', time()-82800 ); // 82800 = 23 hours, so repeat check in one hour
                        echo json_encode(array('error' => 1, 'msg' => __('Version could not be checked')));
                    }
                    break;
                case 'check_languages':
                    $total = _osc_check_languages_update();
                    echo json_encode(array('msg' => __('Checked updates'), 'total' => $total));
                    break;
                case 'check_themes':
                    $total = _osc_check_themes_update();
                    echo json_encode(array('msg' => __('Checked updates'), 'total' => $total));
                    break;
                case 'check_plugins':
                    $total = _osc_check_plugins_update();
                    echo json_encode(array('msg' => __('Checked updates'), 'total' => $total));
                    break;

                /******************************
                 ** COMPLETE UPGRADE PROCESS **
                 ******************************/
                case 'upgrade': // AT THIS POINT WE KNOW IF THERE'S AN UPDATE OR NOT
                    osc_csrf_check();
                    if( defined('DEMO') ) {
                        $msg = __("This action cannot be done because it is a demo site");
                        $result = array("error" => 6, "message" => $msg);
                        osc_add_flash_warning_message( $msg, 'admin');
                    } else {
                        $result = osc_do_upgrade();
                        if (!defined('__FROM_CRON__') || !__FROM_CRON__) {
                            if ($result['error'] == 0) {
                                osc_add_flash_ok_message($result['message'], 'admin');
                            } else if ($result['error'] == 6) {
                                osc_add_flash_warning_message($result['message'], 'admin');
                            }
                        }
                    }
                    echo json_encode($result);
                    break;

                /*******************************
                 ** COMPLETE MARKET PROCESS **
                 *******************************/
                case 'market': // AT THIS POINT WE KNOW IF THERE'S AN UPDATE OR NOT
                    osc_csrf_check(false);
                    $result = osc_market(Params::getParam('section'), Params::getParam('code'));
                    echo json_encode($result);
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
                            $data = json_decode(osc_file_get_contents(osc_market_url($section, $code), array('api_key' => osc_market_api_connect())), true);
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
                            //$data = array('error' => 2, 'error_msg' => __('Invalid code'));
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

                    $data = json_decode(osc_file_get_contents($url, array('api_key' => osc_market_api_connect())), true);

                    if( !isset($data[$section])) {
                        $data = array('error' => 1, 'error_msg' => __('No market data'));
                    }
                    echo 'var market_data = window.market_data || {}; market_data.'.$section.' = '.json_encode($data).';';

                    break;
                case 'market_connect':
                    $json = osc_file_get_contents(osc_market_url() . 'connect/', array('s_email' => Params::getParam('s_email'), 's_password' => Params::getParam('s_password')));
                    $data = json_decode($json, true);
                    if($data['error']==0) {
                        osc_set_preference('marketAPIConnect', $data['api_key']);
                        unset($data['api_key']);
                        $json = json_encode($data);
                    }
                    echo $json;
                    break;
                case 'market_header':
                case 'dashboardbox_market':
                    $error = 0;
                    // make market call
                    $url = osc_get_preference('marketURL') . 'dashboardbox/';
                    if(Params::getParam("action")=="market_header") {
                        $url = osc_get_preference('marketURL') . 'market_header/';
                    }
                    $content = '';
                    if(false===($json=@osc_file_get_contents($url))) {
                        $error = 1;
                    } else {
                        $content = json_decode($json, true);
                    }
                    if(!isset($content["banner"]) || !isset($content["url"]) || $error==1) {
                        echo json_encode(array('error' => 1));
                    } else {
                        // replace content with correct urls
                        $content["url"] = str_replace('{URL_MARKET_THEMES}'    , osc_admin_base_url(true).'?page=market&action=themes' , $content["url"]);
                        $content["url"] = str_replace('{URL_MARKET_PLUGINS}'   , osc_admin_base_url(true).'?page=market&action=plugins', $content["url"]);
                        $content["url"] = osc_esc_html(osc_sanitize_url($content["url"]));
                        $content["banner"] = osc_esc_html(osc_sanitize_url($content["banner"]));
                        $content["error"] = 0;
                        echo json_encode($content);
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
