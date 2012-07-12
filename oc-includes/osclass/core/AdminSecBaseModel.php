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

    class AdminSecBaseModel extends SecBaseModel
    {
        function __construct()
        {
            parent::__construct() ;

            // check if is moderator and can enter to this page
            if( $this->isModerator() ) {
                if( !in_array($this->page, array('items', 'comments', 'media', 'login', 'admins', 'ajax', 'stats','')) ) {
                    osc_add_flash_error_message(_m("You don't have enough permissions"), 'admin');
                    $this->redirectTo(osc_admin_base_url());
                }
            }

            osc_run_hook( 'init_admin' ) ;

            // check if exist a new version each day
            if( (time() - osc_last_version_check()) > (24 * 3600) ) {
                $data = osc_file_get_contents('http://osclass.org/latest_version.php?callback=?') ;
                $data = preg_replace('|^\?\((.*?)\);$|', '$01', $data) ;
                $json = json_decode($data) ;
                if( $json->version > osc_version() ) {
                    osc_set_preference( 'update_core_json', $data ) ;
                } else {
                    osc_set_preference( 'update_core_json', '' ) ;
                }
                osc_set_preference( 'last_version_check', time() ) ;
                osc_reset_preferences() ;
            }

            $config_version = str_replace('.', '', OSCLASS_VERSION);
            $config_version = preg_replace('|-.*|', '', $config_version);

            if( $config_version > Preference::newInstance()->get('version') ) {
                if(get_class($this) == 'CAdminTools') {
                } else {
                    if(get_class($this) != 'CAdminUpgrade' )
                        $this->redirectTo(osc_admin_base_url(true) . '?page=upgrade');
                }
            }

            // show messages subscribed
            $status_subscribe = Params::getParam('subscribe_osclass');
            if( $status_subscribe != '' ) {
                switch( $status_subscribe ) {
                    case -1:
                        osc_add_flash_error_message(_m('Entered an invalid email'), 'admin');
                    break;
                    case 0:
                        osc_add_flash_warning_message(_m("You're already subscribed"), 'admin');
                    break;
                    case 1:
                        osc_add_flash_ok_message(_m('Subscribed correctly'), 'admin');
                    break;
                    default:
                        osc_add_flash_warning_message(_m("Error subscribing"), 'admin');
                    break;
                }
            }

            // show donation successful
            if( Params::getParam('donation') == 'successful' ) {
                osc_add_flash_ok_message(_m('Thank you very much for your donation'), 'admin');
            }
        }

        function isLogged()
        {
            return osc_is_admin_user_logged_in() ;
        }

        function isModerator()
        {
            return osc_is_moderator();
        }

        function logout()
        {
            //destroying session
            Session::newInstance()->session_destroy() ;
            Session::newInstance()->_drop('adminId') ;
            Session::newInstance()->_drop('adminUserName') ;
            Session::newInstance()->_drop('adminName') ;
            Session::newInstance()->_drop('adminEmail') ;
            Session::newInstance()->_drop('adminLocale') ;

            Cookie::newInstance()->pop('oc_adminId') ;
            Cookie::newInstance()->pop('oc_adminSecret') ;
            Cookie::newInstance()->pop('oc_adminLocale') ;
            Cookie::newInstance()->set() ;
        }

        function showAuthFailPage()
        {
            Session::newInstance()->session_start();
            Session::newInstance()->_setReferer(osc_base_url() . preg_replace('|^' . REL_WEB_URL . '|', '', $_SERVER['REQUEST_URI']));
            $this->redirectTo( osc_admin_base_url(true)."?page=login" ) ;
        }
    }

    /* file end: ./oc-includes/osclass/core/AdminSecBaseModel.php */
?>