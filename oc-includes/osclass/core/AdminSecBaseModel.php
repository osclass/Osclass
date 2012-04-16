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
        }

        function isLogged()
        {
            return osc_is_admin_user_logged_in() ;
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
            // juanramon: we add here de init_admin hook becuase if not, it's not called
            osc_run_hook( 'init_admin' ) ;
            require osc_admin_base_path() . 'gui/login.php' ;
            exit ;
        }
    }

    /* file end: ./oc-includes/osclass/core/AdminSecBaseModel.php */
?>