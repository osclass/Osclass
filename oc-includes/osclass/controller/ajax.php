<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

    /*
     *      Osclass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2012 OSCLASS
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

    define('IS_AJAX', true);

    class CWebAjax extends BaseModel
    {
        function __construct()
        {
            parent::__construct();
            $this->ajax = true;
        }

        //Business Layer...
        function doModel()
        {
            //specific things for this class
            switch ($this->action)
            {
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
                    foreach($cities as $k => $city) {
                        $cities[$k]['label'] = $city['label']." (".$city['region'].")";
                    }
                    echo json_encode($cities);
                break;
                case 'location_countries': // This is the autocomplete AJAX
                    $countries = Country::newInstance()->ajax(Params::getParam("term"));
                    echo json_encode($countries);
                break;
                case 'location_regions': // This is the autocomplete AJAX
                    $regions = Region::newInstance()->ajax(Params::getParam("term"), Params::getParam("country"));
                    echo json_encode($regions);
                break;
                case 'location_cities': // This is the autocomplete AJAX
                    $cities = City::newInstance()->ajax(Params::getParam("term"), Params::getParam("region"));
                    echo json_encode($cities);
                break;
                case 'delete_image': // Delete images via AJAX
                    $id     = Params::getParam('id');
                    $item   = Params::getParam('item');
                    $code   = Params::getParam('code');
                    $secret = Params::getParam('secret');
                    $json = array();

                    if( Session::newInstance()->_get('userId') != '' ){
                        $userId = Session::newInstance()->_get('userId');
                        $user = User::newInstance()->findByPrimaryKey($userId);
                    }else{
                        $userId = null;
                        $user = null;
                    }

                    // Check for required fields
                    if ( !( is_numeric($id) && is_numeric($item) && preg_match('/^([a-z0-9]+)$/i', $code) ) ) {
                        $json['success'] = false;
                        $json['msg'] = _m("The selected photo couldn't be deleted, the url doesn't exist");
                        echo json_encode($json);
                        return false;
                    }

                    $aItem = Item::newInstance()->findByPrimaryKey($item);

                    // Check if the item exists
                    if(count($aItem) == 0) {
                        $json['success'] = false;
                        $json['msg'] = _m("The listing doesn't exist");
                        echo json_encode($json);
                        return false;
                    }

                    if(!osc_is_admin_user_logged_in()) {
                        // Check if the item belong to the user
                        if($userId != null && $userId != $aItem['fk_i_user_id']) {
                            $json['success'] = false;
                            $json['msg'] = _m("The listing doesn't belong to you");
                            echo json_encode($json);
                            return false;
                        }

                        // Check if the secret passphrase match with the item
                        if($userId == null && $aItem['fk_i_user_id']==null && $secret != $aItem['s_secret']) {
                            $json['success'] = false;
                            $json['msg'] = _m("The listing doesn't belong to you");
                            echo json_encode($json);
                            return false;
                        }
                    }

                    // Does id & code combination exist?
                    $result = ItemResource::newInstance()->existResource($id, $code);

                    if ($result > 0) {
                        $resource = ItemResource::newInstance()->findByPrimaryKey($id);

                        if($resource['fk_i_item_id']==$item) {
                            // Delete: file, db table entry
                            if(defined(OC_ADMIN)) {
                                osc_deleteResource($id, true);
                                Log::newInstance()->insertLog('ajax', 'deleteimage', $id, $id, 'admin', osc_logged_admin_id());
                            } else {
                                osc_deleteResource($id, false);
                                Log::newInstance()->insertLog('ajax', 'deleteimage', $id, $id, 'user', osc_logged_user_id());
                            }
                            ItemResource::newInstance()->delete(array('pk_i_id' => $id, 'fk_i_item_id' => $item, 's_name' => $code) );

                            $json['msg'] =  _m('The selected photo has been successfully deleted');
                            $json['success'] = 'true';
                        } else {
                            $json['msg'] = _m("The selected photo does not belong to you");
                            $json['success'] = 'false';
                        }
                    } else {
                        $json['msg'] = _m("The selected photo couldn't be deleted");
                        $json['success'] = 'false';
                    }

                    echo json_encode($json);
                    return true;
                break;
                case 'alerts': // Allow to register to an alert given (not sure it's used on admin)
                    $alert = Params::getParam("alert");
                    $email = Params::getParam("email");
                    $userid = Params::getParam("userid");

                    if($alert!='' && $email!='') {
                        if(osc_validate_email($email)) {
                            $secret = osc_genRandomPassword();

                            if( $alertID = Alerts::newInstance()->createAlert($userid, $email, $alert, $secret) ) {
                                if( (int)$userid > 0 ) {
                                    $user = User::newInstance()->findByPrimaryKey($userid);
                                    if($user['b_active']==1 && $user['b_enabled']==1) {
                                        Alerts::newInstance()->activate($alertID);
                                        echo '1';
                                        return true;
                                    } else {
                                        echo '-1';
                                        return false;
                                    }
                                } else {
                                    $aAlert = Alerts::newInstance()->findByPrimaryKey($alertID);
                                    osc_run_hook('hook_email_alert_validation', $aAlert, $email, $secret);
                                }

                                echo "1";
                            } else {
                                echo "0";
                            }
                            return true;
                        } else {
                            echo '-1';
                            return false;
                        }
                    }
                    echo '0';
                    return false;
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
                            osc_run_hook('ajax_' . $hook);
                        break;
                    }
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
                        // DEPRECATED: Disclosed path in URL is deprecated, use routes instead
                        // This will be REMOVED in 3.4
                        $file = Params::getParam('ajaxfile');
                    }

                    if($file == '') {
                        echo json_encode(array('error' => 'no action defined'));
                        break;
                    }

                    // valid file?
                    if( stripos($file, '../') !== false  || stripos($file, '/admin/') !== false ) { //If the file is inside an "admin" folder, it should NOT be opened in frontend
                        echo json_encode(array('error' => 'no valid ajaxFile'));
                        break;
                    }

                    if( !file_exists(osc_plugins_path() . $file) ) {
                        echo json_encode(array('error' => "ajaxFile doesn't exist"));
                        break;
                    }

                    require_once osc_plugins_path() . $file;
                break;
                case 'check_username_availability':
                    $username = osc_sanitize_username(Params::getParam('s_username'));
                    if(!osc_is_username_blacklisted($username)) {
                        $user = User::newInstance()->findByUsername($username);
                        if(isset($user['s_username'])) {
                            echo json_encode(array('exists' => 1, 's_username' => $username));
                        } else {
                            echo json_encode(array('exists' => 0, 's_username' => $username));
                        }
                    } else {
                        echo json_encode(array('exists' => 1, 's_username' => $username));
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
        function doView($file)
        {
            osc_run_hook("before_html");
            osc_current_web_theme_path($file);
            osc_run_hook("after_html");
        }
    }

    /* file end: ./ajax.php */
?>