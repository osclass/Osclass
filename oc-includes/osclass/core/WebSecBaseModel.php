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

    class WebSecBaseModel extends SecBaseModel
    {
        function __construct()
        {
            parent::__construct();
        }

        function isLogged()
        {
            return osc_is_web_user_logged_in();
        }

        //destroying current session
        function logout()
        {
            //destroying session
            Session::newInstance()->session_destroy();
            Session::newInstance()->_drop('userId');
            Session::newInstance()->_drop('userName');
            Session::newInstance()->_drop('userEmail');
            Session::newInstance()->_drop('userPhone');

            Cookie::newInstance()->pop('oc_userId');
            Cookie::newInstance()->pop('oc_userSecret');
            Cookie::newInstance()->set();
        }

        function showAuthFailPage()
        {
            $this->redirectTo( osc_user_login_url() );
        }
    }

    /* file end: ./oc-includes/osclass/core/WebSecBaseModel.php */
?>