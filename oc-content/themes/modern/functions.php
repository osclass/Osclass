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
    
    if( !function_exists('meta_title') ) {
        function meta_title( ) {
            $location = Rewrite::newInstance()->get_location();
            $section  = Rewrite::newInstance()->get_section();

            switch ($location) {
                case ('item'):
                    switch ($section) {
                        case 'item_add':    $text = __('Publish an item', 'modern') . ' - ' . osc_page_title(); break;
                        case 'item_edit':   $text = __('Edit your item', 'modern') . ' - ' . osc_page_title(); break;
                        case 'send_friend': $text = __('Send to a friend', 'modern') . ' - ' . osc_item_title() . ' - ' . osc_page_title(); break;
                        case 'contact':     $text = __('Contact seller', 'modern') . ' - ' . osc_item_title() . ' - ' . osc_page_title(); break;
                        default:            $text = osc_item_title() . ' - ' . osc_page_title(); break;
                    }
                break;
                case('page'):
                    $text = osc_static_page_title() . ' - ' . osc_page_title();
                break;
                case('error'):
                    $text = __('Error', 'modern') . ' - ' . osc_page_title();
                break;
                case('search'):
                    $region   = Params::getParam('sRegion');
                    $city     = Params::getParam('sCity');
                    $pattern  = Params::getParam('sPattern');
                    $category = osc_search_category_id();
                    $category = ((count($category) == 1) ? $category[0] : '');
                    $s_page   = '';
                    $i_page   = Params::getParam('iPage');

                    if($i_page != '' && $i_page > 0) {
                        $s_page = __('page', 'modern') . ' ' . ($i_page + 1) . ' - ';
                    }

                    $b_show_all = ($region == '' && $city == '' & $pattern == '' && $category == '');
                    $b_category = ($category != '');
                    $b_pattern  = ($pattern != '');
                    $b_city     = ($city != '');
                    $b_region   = ($region != '');

                    if($b_show_all) {
                        $text = __('Show all items', 'modern') . ' - ' . $s_page . osc_page_title();
                    }

                    $result = '';
                    if($b_pattern) {
                        $result .= $pattern . ' &raquo; ';
                    }

                    if($b_category) {
                        $list        = array();
                        $aCategories = Category::newInstance()->toRootTree($category);
                        if(count($aCategories) > 0) {
                            foreach ($aCategories as $single) {
                                $list[] = $single['s_name'];
                            }
                            $result .= implode(' &raquo; ', $list) . ' &raquo; ';
                        }
                    }

                    if($b_city) {
                        $result .= $city . ' &raquo; ';
                    }

                    if($b_region) {
                        $result .= $region . ' &raquo; ';
                    }

                    $result = preg_replace('|\s?&raquo;\s$|', '', $result);

                    if($result == '') {
                        $result = __('Search', 'modern');
                    }

                    $text = $result . ' - ' . $s_page . osc_page_title();
                break;
                case('login'):
                    switch ($section) {
                        case('recover'): $text = __('Recover your password', 'modern') . ' - ' . osc_page_title();
                        default:         $text = __('Login', 'modern') . ' - ' . osc_page_title();
                    }
                break;
                case('register'):
                    $text = __('Create a new account', 'modern') . ' - ' . osc_page_title();
                break;
                case('user'):
                    switch ($section) {
                        case('dashboard'):       $text = __('Dashboard', 'modern') . ' - ' . osc_page_title(); break;
                        case('items'):           $text = __('Manage my items', 'modern') . ' - ' . osc_page_title(); break;
                        case('alerts'):          $text = __('Manage my alerts', 'modern') . ' - ' . osc_page_title(); break;
                        case('profile'):         $text = __('Update my profile', 'modern') . ' - ' . osc_page_title(); break;
                        case('change_email'):    $text = __('Change my email', 'modern') . ' - ' . osc_page_title(); break;
                        case('change_password'): $text = __('Change my password', 'modern') . ' - ' . osc_page_title(); break;
                        case('forgot'):          $text = __('Recover my password', 'modern') . ' - ' . osc_page_title(); break;
                        default:                 $text = osc_page_title(); break;
                    }
                break;
                case('contact'):
                    $text = __('Contact','modern') . ' - ' . osc_page_title();
                break;
                default:
                    $text = osc_page_title();
                break;
            }
            
            $text = str_replace('"', "'", $text);
            return ($text);
         }
     }

     if( !function_exists('meta_description') ) {
         function meta_description( ) {
            $location = Rewrite::newInstance()->get_location();
            $section  = Rewrite::newInstance()->get_section();
            $text     = '';

            switch ($location) {
                case ('item'):
                    switch ($section) {
                        case 'item_add':    $text = ''; break;
                        case 'item_edit':   $text = ''; break;
                        case 'send_friend': $text = ''; break;
                        case 'contact':     $text = ''; break;
                        default:
                            $text = osc_item_category() . ', ' . osc_highlight(strip_tags(osc_item_description()), 140) . '..., ' . osc_item_category();
                            break;
                    }
                break;
                case('page'):
                    $text = osc_highlight(strip_tags(osc_static_page_text()), 140);
                break;
                case('search'):
                    $result = '';

                    if(osc_count_items() == 0) {
                        $text = '';
                    }

                    if(osc_has_items ()) {
                        $result = osc_item_category() . ', ' . osc_highlight(strip_tags(osc_item_description()), 140) . '..., ' . osc_item_category();
                    }

                    osc_reset_items();
                    $text = $result;
                case(''): // home
                    $result = '';

                    if(osc_count_latest_items() == 0) {
                        $text = '';
                    }

                    if(osc_has_latest_items()) {
                        $result = osc_item_category() . ', ' . osc_highlight(strip_tags(osc_item_description()), 140) . '..., ' . osc_item_category();
                    }

                    osc_reset_items();
                    $text = $result;
                break;
            }
            
            $text = str_replace('"', "'", $text);
            return ($text);
         }
     }
?>