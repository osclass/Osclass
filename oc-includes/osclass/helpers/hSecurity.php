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


    /**
    * Helper Security
    * @package OSClass
    * @subpackage Helpers
    * @author OSClass
    */

    /**
     * Creates a random password.
     * @param int password $length. Default to 8.
     * @return string
     */
    function osc_genRandomPassword($length = 8) {
        $dict = array_merge(range('a', 'z'), range('0', '9'), range('A', 'Z'));
        shuffle($dict);

        $pass = '';
        for($i = 0; $i < $length; $i++)
            $pass .= $dict[rand(0, count($dict) - 1)];

        return $pass;
    }


    function osc_csrf_token_form() {
        $name = osc_csrf_name()."_".mt_rand(0,mt_getrandmax());
        $token = osc_csrfguard_generate_token($name);
        return "<input type='hidden' name='CSRFName' value='".$name."' />
        <input type='hidden' name='CSRFToken' value='".$token."' />";
    }

    function osc_csrf_token_url() {
        $name = osc_csrf_name()."_".mt_rand(0,mt_getrandmax());
        $token = osc_csrfguard_generate_token($name);
        return "CSRFName='".$name."&CSRFToken='".$token;
    }

    function osc_csrf_check() {
        if(Params::getParam('CSRFName')=='' || Params::getParam('CSRFToken')=='') {
            trigger_error( __("Probable invalid request."),E_USER_ERROR);
        }
        $name = Params::getParam('CSRFName');
        $token = Params::getParam('CSRFToken');
        if (!osc_csrfguard_validate_token($name, $token)) {
            trigger_error(__("Invalid CSRF token."),E_USER_ERROR);
        }
    }

?>