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
                    $regions = Region::newInstance()->listWhere("fk_c_country_code = '%s'", Params::getParam("countryId"));
                    echo json_encode($regions);
                    break;
                
                case 'cities': //Returns cities given a regionId
                    $cities = City::newInstance()->listWhere("fk_i_region_id = %d", Params::getParam("regionId"));
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
                            
                        default:
                            if($hook=='') { return false; } else { osc_run_hook($hook); }
                            break;
                    }
                    break;
                    
                case 'items': // Return items (use external file oc-admin/ajax/item_processing.php)
                    require_once osc_admin_base_path() . 'ajax/items_processing.php';
                    $items_processing = new items_processing_ajax(Params::getParamsAsArray("get"));
                    break;

                case 'custom': // Execute via AJAX custom file
                    $ajaxfile = Params::getParam("ajaxfile");
                    if($ajaxfile!='') {
                        require_once osc_admin_base_path() . $ajaxfile;
                    } else {
                        echo json_encode(array('error' => __('no action defined')));
                    }
                    break;
                    
                default:
                    echo json_encode(array('error' => __('no action defined')));
                    break;
            }
        }
    }

?>
