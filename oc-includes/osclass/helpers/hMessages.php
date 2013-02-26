<?php

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
        $message = Session::newInstance()->_getMessage($section);

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

        Session::newInstance()->_dropMessage($section);
    }

    /**
     *
     *
     * @param string $section
     * @return string Message
     */
    function osc_get_flash_message($section = 'pubMessages') {
        $message = Session::newInstance()->_getMessage($section);
        Session::newInstance()->_dropMessage($section);

        return $message;
    }

?>