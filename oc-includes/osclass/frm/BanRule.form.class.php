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