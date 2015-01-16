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