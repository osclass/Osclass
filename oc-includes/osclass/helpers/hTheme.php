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
        $file = str_replace("../", "", str_replace("..\\", "", str_replace("://", "", preg_replace("|http([s]*)|", "", $file))));
        if(file_exists(osc_theme() . $file)) {
            include osc_themes_path() . $file;
        } else if(file_exists(osc_plugins_path() . $file)) {
            include osc_plugins_path() . $file;
        }
    }


    /**
     * Gets urls for render custom files in front-end
     *
     * @param string $file must be a relative path, from PLUGINS_PATH
     * @return string
     */
    function osc_render_file_url($file = '') {
        osc_sanitize_url($file);
        $file = str_replace("../", "", str_replace("..\\", "", str_replace("://", "", preg_replace("|http([s]*)|", "", $file))));
        return osc_base_url(true).'?page=custom&file=' . $file;
    }

    /**
     * Re-send the flash messages of the given section. Usefull for custom theme/plugins files.
     *
     * @param string $$section
     */
    function osc_resend_flash_messages($section = "pubMessages") {
        $messages = Session::newInstance()->_getMessage($section);
        if (is_array($messages)) {

            foreach ($messages as $message) {
      
                $message = Session::newInstance()->_getMessage($section);
                if(isset($message['msg'])) {
                    if(isset($message["type"]) && $message["type"]=="info") {
                        osc_add_flash_info_message($message['msg'], $section);
                    } else if(isset($message["type"]) && $message["type"]=="ok") {
                        osc_add_flash_ok_message($message['msg'], $section);
                    } else {
                        osc_add_flash_error_message($message['msg'], $section);
                    }
                }
            }
        }
    }

    /**
     * Enqueue script
     *
     * @param type $id
     */
    function osc_enqueue_script($id) {
        Scripts::newInstance()->enqueuScript($id);
    }

    /**
     * Remove script from the queue, so it will not be loaded
     *
     * @param type $id
     */
    function osc_remove_script($id) {
        Scripts::newInstance()->removeScript($id);
    }

    /**
     * Add script to be loaded
     *
     * @param $id keyname to identify the script
     * @param $url url of the .js file
     * @param $dependencies mixed, could be an array or a string
     */
    function osc_register_script($id, $url, $dependencies = null) {
        Scripts::newInstance()->registerScript($id, $url, $dependencies);
    }

    /**
     * Remove script from the queue, so it will not be loaded
     *
     * @param type $id
     */
    function osc_unregister_script($id) {
        Scripts::newInstance()->unregisterScript($id);
    }

    /**
     * Print the HTML tags to make the script load
     */
    function osc_load_scripts() {
        Scripts::newInstance()->printScripts();
        if( OC_ADMIN ) {
            osc_run_hook('admin_scripts_loaded');
        } else {
            osc_run_hook('scripts_loaded');
        }
    }

    /**
     * Add style to be loaded
     *
     * @param $id keyname to identify the style
     * @param $url url of the .css file
     */
    function osc_enqueue_style($id, $url) {
        Styles::newInstance()->addStyle($id, $url);
    }

    /**
     * Remove style from the queue, so it will not be loaded
     *
     * @param type $id
     */
    function osc_remove_style($id) {
        Styles::newInstance()->removeStyle($id);
    }

    /**
     * Print the HTML tags to make the style load
     */
    function osc_load_styles() {
        Styles::newInstance()->printStyles();
    }

    function osc_print_bulk_actions($id, $name, $options, $class = '') {
        echo '<select id="'.$id.'" name="'.$name.'" '.($class!=''?'class="'.$class.'"':'').'>';
        foreach($options as $o) {
            $opt = '';
            $label = '';
            foreach($o as $k => $v) {
                if($k!='label') {
                    $opt .= $k.'="'.$v.'" ';
                } else {
                    $label = $v;
                }
            }
            echo '<option '.$opt.'>'.$label.'</option>';
        }
        echo '</select>';
    }


    /* file end: ./oc-includes/osclass/hTheme.php */