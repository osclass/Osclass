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

    if( !OC_ADMIN ) {
        if( !function_exists('add_close_button_fm') ) {
            function add_close_button_fm($message){
                return $message.'<a class="close">×</a>' ;
            }
            osc_add_filter('flash_message_text', 'add_close_button_fm') ;
        }
        if( !function_exists('add_close_button_action') ) {
            function add_close_button_action(){
                echo '<script type="text/javascript">';
                    echo '$(".flashmessage .ico-close").click(function(){';
                        echo '$(this).parent().hide();';
                    echo '});';
                echo '</script>';
            }
            osc_add_hook('footer', 'add_close_button_action') ;
        }
    }

    if( !function_exists('add_logo_header') ) {
        function add_logo_header() {
             $html = '<img border="0" alt="' . osc_page_title() . '" src="' . osc_current_web_theme_url('images/logo.jpg') . '">';
             $js   = "<script>
                          $(document).ready(function () {
                              $('#logo').html('".$html."');
                          });
                      </script>";

             if( file_exists( WebThemes::newInstance()->getCurrentThemePath() . "images/logo.jpg" ) ) {
                echo $js;
             }
        }

        osc_add_hook("header", "add_logo_header");
    }

    if( !function_exists('modern_admin_menu') ) {
        function modern_admin_menu() {
            echo '<h3><a href="#">'. __('Modern theme','modern') .'</a></h3>
            <ul>
                <li><a href="' . osc_admin_render_theme_url('oc-content/themes/modern/admin/admin_settings.php') . '">&raquo; '.__('Settings theme', 'modern').'</a></li>
            </ul>';
        }

        osc_add_hook('admin_menu', 'modern_admin_menu');
    }
    
?>