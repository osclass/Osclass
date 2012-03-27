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

    class AdminForm extends Form {

        static public function primary_input_hidden($admin) {
            parent::generic_input_hidden("id", (isset($admin["pk_i_id"]) ? $admin['pk_i_id'] : '') );
        }

        static public function name_text($admin = null) {
            parent::generic_input_text("s_name", isset($admin['s_name'])? $admin['s_name'] : '', null, false);
        }

        static public function username_text($admin = null) {
            parent::generic_input_text("s_username", isset($admin['s_username'])? $admin['s_username'] : '', null, false);
        }

        static public function old_password_text($admin = null) {
            parent::generic_password("old_password", '', null, false);
        }

        static public function password_text($admin = null) {
            parent::generic_password("s_password", '', null, false);
        }

        static public function check_password_text($admin = null) {
            parent::generic_password("s_password2", '', null, false);
        }

        static public function email_text($admin = null) {
            parent::generic_input_text("s_email", isset($admin['s_email'])? $admin['s_email'] : '', null, false);
        }

    }

    /* file end: ./oc-includes/osclass/frm/Admin.form.class.php */
?>