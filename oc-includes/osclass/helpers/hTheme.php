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
     * Gets urls for current theme administrations options
     *
     * @param string $file must be a relative path, from ABS_PATH
     * @return string
     */
    function osc_admin_render_theme_url($file = '') {
        return osc_admin_base_url(true).'?page=appearance&action=render&file=' . $file;
    }
    
    
    /**
     * Render the specified file
     *
     * @param string $file must be a relative path, from PLUGINS_PATH
     */    
    function osc_render_file($file = '') {
        if($file=='') {
            $file = __get('file');
        }
        // Clean $file to prevent hacking of some type
        osc_sanitize_url($file);
        $file = str_replace("../", "", str_replace("://", "", preg_replace("|http([s]*)|", "", $file)));
        include osc_plugins_path().$file;
    }
    
    
    /**
     * Gets urls for render custom files in front-end
     *
     * @param string $file must be a relative path, from PLUGINS_PATH
     * @return string
     */
    function osc_render_file_url($file = '') {
        osc_sanitize_url($file);
        $file = str_replace("../", "", str_replace("://", "", preg_replace("|http([s]*)|", "", $file)));
        return osc_base_url(true).'?page=custom&file=' . $file;
    }
    
    /**
     * Re-send the flash messages of the given section. Usefull for custom theme/plugins files.
     *
     * @param string $$section
     */    
    function osc_resend_flash_messages($section = "pubMessages") {
        $message = Session::newInstance()->_getMessage($section);
        if($message["type"]=="info") {
            osc_add_flash_info_message($message['msg'], $section);
        } else if($message["type"]=="ok") {
            osc_add_flash_ok_message($message['msg'], $section);
        } else {
            osc_add_flash_error_message($message['msg'], $section);
        }
    }
?>