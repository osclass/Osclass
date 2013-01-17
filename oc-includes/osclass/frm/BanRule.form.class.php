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

    class BanRuleForm extends Form {

        static public function primary_input_hidden($rule) {
            parent::generic_input_hidden("id", (isset($rule["pk_i_id"]) ? $rule['pk_i_id'] : '') );
        }

        static public function name_text($rule = null) {
            parent::generic_input_text("s_name", isset($rule['s_name'])? $rule['s_name'] : '', null, false);
        }

        static public function ip_text($rule = null) {
            parent::generic_input_text("s_ip", isset($rule['s_ip'])? $rule['s_ip'] : '', null, false);
        }

        static public function email_text($rule = null) {
            parent::generic_input_text("s_email", isset($rule['s_email'])? $rule['s_email'] : '', null, false);
        }

    }

?>