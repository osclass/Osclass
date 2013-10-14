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

    class AdminSecBaseModel extends SecBaseModel
    {
        function __construct()
        {
            parent::__construct();

            // check if is moderator and can enter to this page
            if( $this->isModerator() ) {
                if( !in_array($this->page, osc_apply_filter('moderator_access', array('items', 'comments', 'media', 'login', 'admins', 'ajax', 'stats',''))) ) {
                    osc_add_flash_error_message(_m("You don't have enough permissions"), 'admin');
                    $this->redirectTo(osc_admin_base_url());
                }
            }

            osc_run_hook( 'init_admin' );

            // check if exist a new version each day
            if( (time() - osc_last_version_check()) > (24 * 3600) ) {
                $data = osc_file_get_contents('http://osclass.org/latest_version.php?callback=?');
                $data = preg_replace('|^\?\((.*?)\);$|', '$01', $data);
                $json = json_decode($data);
                if( $json->version > osc_version() ) {
                    osc_set_preference( 'update_core_json', $data );
                } else {
                    osc_set_preference( 'update_core_json', '' );
                }
                osc_set_preference( 'last_version_check', time() );
                osc_reset_preferences();
            }

            $config_version = str_replace('.', '', OSCLASS_VERSION);
            $config_version = preg_replace('|-.*|', '', $config_version);

            if( $config_version > osc_get_preference('version') ) {
                if(get_class($this) == 'CAdminTools') {
                } else {
                    if(get_class($this) != 'CAdminUpgrade' )
                        $this->redirectTo(osc_admin_base_url(true) . '?page=upgrade');
                }
            }

            // show donation successful
            if( Params::getParam('donation') == 'successful' ) {
                osc_add_flash_ok_message(_m('Thank you very much for your donation'), 'admin');
            }

            // enqueue scripts
            osc_enqueue_script('jquery');
            osc_enqueue_script('jquery-ui');
            osc_enqueue_script('admin-osc');
            osc_enqueue_script('admin-ui-osc');
        }

        function isLogged()
        {
            return osc_is_admin_user_logged_in();
        }

        function isModerator()
        {
            return osc_is_moderator();
        }

        function logout()
        {
            //destroying session
            Session::newInstance()->_drop('adminId');
            Session::newInstance()->_drop('adminUserName');
            Session::newInstance()->_drop('adminName');
            Session::newInstance()->_drop('adminEmail');
            Session::newInstance()->_drop('adminLocale');

            Cookie::newInstance()->pop('oc_adminId');
            Cookie::newInstance()->pop('oc_adminSecret');
            Cookie::newInstance()->pop('oc_adminLocale');
            Cookie::newInstance()->set();
        }

        function showAuthFailPage()
        {
            if(Params::getParam('page')=='ajax') {
                echo json_encode(array('error' => 1, 'msg' => __('Session timed out')));
            } else {
                //Session::newInstance()->session_start();
                Session::newInstance()->_setReferer(osc_base_url() . preg_replace('|^' . REL_WEB_URL . '|', '', $_SERVER['REQUEST_URI']));
                header("Location: " . osc_admin_base_url(true)."?page=login" );
                exit;
            }
        }

        //hopefully generic...
        function doView($file)
        {
            osc_run_hook("before_admin_html");
            osc_current_admin_theme_path($file);
            Session::newInstance()->_clearVariables();
            osc_run_hook("after_admin_html");
        }
    }

    /* file end: ./oc-includes/osclass/core/AdminSecBaseModel.php */
?>