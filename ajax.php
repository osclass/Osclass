<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

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

    class CWebAjax extends BaseModel
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

                        if( preg_match("/^[_a-z0-9-+]+(\.[_a-z0-9-+]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/",$email) ) {

                            $secret = osc_genRandomPassword();
                            
                            if( Alerts::newInstance()->createAlert($userid, $email, $alert, $secret) ) {
                                
                                if( (int)$userid > 0 ) {
                                    $user = User::newInstance()->findByPrimaryKey($userid);
                                    Alerts::newInstance()->activate($email, $secret);
                                } else {
                                    $user['s_name'] = "";
                                    
                                    // send alert validation email
                                    $prefLocale = osc_language() ;
                                    $page = Page::newInstance()->findByInternalName('email_alert_validation') ;
                                    $page_description = $page['locale'] ;

                                    $_title = $page_description[$prefLocale]['s_title'] ;
                                    $_body  = $page_description[$prefLocale]['s_text'] ;

                                    $validation_link  = osc_user_activate_alert_url( $secret, $email );

                                    $words = array() ;
                                    $words[] = array('{USER_NAME}'    , '{USER_EMAIL}', '{VALIDATION_LINK}') ;
                                    $words[] = array($user['s_name']  , $email        , $validation_link ) ;
                                    $title = osc_mailBeauty($_title, $words) ;
                                    $body  = osc_mailBeauty($_body , $words) ;

                                    $params = array(
                                        'subject' => $_title
                                        ,'to' => $email
                                        ,'to_name' => $user['s_name']
                                        ,'body' => $body
                                        ,'alt_body' => $body
                                    ) ;

                                    osc_sendMail($params) ;
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
                    
                case 'custom': // Execute via AJAX custom file
                    $ajaxfile = Params::getParam("ajaxfile");
                    if($ajaxfile!='') {
                        require_once osc_plugins_path() . $ajaxfile;
                    } else {
                        echo json_encode(array('error' => __('no action defined')));
                    }
                    break;
                    
                default:
                    echo json_encode(array('error' => __('no action defined')));
                    break;
            }
        }
        
        //hopefully generic...
        function doView($file) {
            osc_current_web_theme_path($file) ;
        }
    }

?>