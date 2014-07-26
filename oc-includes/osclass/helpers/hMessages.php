<?php

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


    /**
    * Helper Flash Messages
    * @package Osclass
    * @subpackage Helpers
    * @author Osclass
    */

    /**
     * Adds an ephemeral message to the session. (error style)
     *
     * @param $msg
     * @param $section
     * @return string
     */
    function osc_add_flash_message($msg, $section = 'pubMessages') {
        Session::newInstance()->_setMessage($section, $msg, 'error');
    }

    /**
     * Adds an ephemeral message to the session. (ok style)
     *
     * @param $msg
     * @param $section
     * @return string
     */
    function osc_add_flash_ok_message($msg, $section = 'pubMessages') {
        Session::newInstance()->_setMessage($section, $msg, 'ok');
    }

    /**
     * Adds an ephemeral message to the session. (error style)
     *
     * @param $msg
     * @param $section
     * @return string
     */
    function osc_add_flash_error_message($msg, $section = 'pubMessages') {
        Session::newInstance()->_setMessage($section, $msg, 'error');
    }

    /**
     * Adds an ephemeral message to the session. (info style)
     *
     * @param $msg
     * @param $section
     * @return string
     */
    function osc_add_flash_info_message($msg, $section = 'pubMessages') {
        Session::newInstance()->_setMessage($section, $msg, 'info');
    }

    /**
     * Adds an ephemeral message to the session. (warning style)
     *
     * @param $msg
     * @param $section
     * @return string
     */
    function osc_add_flash_warning_message($msg, $section = 'pubMessages') {
        Session::newInstance()->_setMessage($section, $msg, 'warning');
    }

    /**
     * Shows all the pending flash messages in session and cleans up the array.
     *
     * @param $section
     * @param $class
     * @param $id
     * @return void
     */
    function osc_show_flash_message($section = 'pubMessages', $class = "flashmessage", $id = "flashmessage") {
        $messages = Session::newInstance()->_getMessage($section);
        if (is_array($messages)) {

            foreach ($messages as $message) {

                echo '<div id="flash_js"></div>';
        
                if (isset($message['msg']) && $message['msg'] != '') {
                    echo '<div id="' . $id . '" class="' . strtolower($class) . ' ' . strtolower($class) . '-' .$message['type'] . '"><a class="btn ico btn-mini ico-close">x</a>';
                    echo osc_apply_filter('flash_message_text', $message['msg']);
                    echo '</div>';
                } else if($message!='') {
                    echo '<div id="' . $id . '" class="' . $class . '">';
                    echo osc_apply_filter('flash_message_text', $message);
                    echo '</div>';
                } else {
                    echo '<div id="' . $id . '" class="' . $class . '" style="display:none;">';
                    echo osc_apply_filter('flash_message_text', '');
                    echo '</div>';
                }
            }
        }  
        Session::newInstance()->_dropMessage($section);
    }

    /**
     *
     *
     * @param string $section
     * @return string Message
     */
    function osc_get_flash_message($section = 'pubMessages', $dropMessages = true) {
        $message = Session::newInstance()->_getMessage($section);
        if ($dropMessages) Session::newInstance()->_dropMessage($section);

        return $message;
    }

?>
