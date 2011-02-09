<?php
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

class CAdminLogin extends BaseModel
{

    function __construct() {
        parent::__construct() ;
    }

    //Business Layer...
    function doModel() {
       $admin = Admin::newInstance()->findByUsername( Params::getParam('user') ) ;
        if ($admin) {
            if ( $admin["s_password"] == sha1( Params::getParam('password') ) ) {
                if ( Params::getParam('remember') ) {
                    $life = time() + COOKIE_LIFE ;

                    //this include contains de osc_genRandomPassword function
                    require_once ABS_PATH . 'oc-includes/osclass/helpers/hSecurity.php';
                    $secret = osc_genRandomPassword() ;

                    Admin::newInstance()->update(
                        array('s_secret' => $secret)
                        ,array('pk_i_id' => $admin['pk_i_id'])
                    );

                    //setcookie('oc_adminId', $admin['pk_i_id'], $life, '/', $_SERVER['SERVER_NAME']);
                    //setcookie('oc_adminSecret', $secret, $life, '/', $_SERVER['SERVER_NAME']);
                } else {
                    //setcookie('oc_adminId', null, time() - 3600, '/', $_SERVER['SERVER_NAME']);
                    //setcookie('oc_adminSecret', null, time() - 3600, '/', $_SERVER['SERVER_NAME']);
                }

                //we are logged in... let's go!
                //Session::newInstance()->_view() ;
                Session::newInstance()->_set('adminId', $admin['pk_i_id']) ;
                Session::newInstance()->_set('adminTheme', Params::getParam('theme')) ;
                Session::newInstance()->_set('adminLocale', Params::getParam('locale')) ;
                //Session::newInstance()->_view() ;

            } else {
                osc_add_flash_message(__('The password is incorrect')) ;
            }
            
        } else {
            osc_add_flash_message(__('The username does not exists')) ;
        }

        //returning logged in to the main page...
        $this->redirectTo( osc_admin_base_url() ) ;
    }

    //hopefully generic...
    function doView($file) {
        $this->osc_print_html($file) ;
    }
}

?>