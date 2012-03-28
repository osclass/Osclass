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




function osc_meta_publish($catId = null) {
    echo '<div class="row">';
        FieldForm::meta_fields_input($catId);
    echo '</div>';
}

function osc_meta_edit($catId = null, $item_id = null) {
    echo '<div class="row">';
        FieldForm::meta_fields_input($catId, $item_id);
    echo '</div>';
}

osc_add_hook('item_form', 'osc_meta_publish');
osc_add_hook('item_edit', 'osc_meta_edit');


function meta_title( ) {
    $location = Rewrite::newInstance()->get_location();
    $section  = Rewrite::newInstance()->get_section();

    switch ($location) {
        case ('item'):
            switch ($section) {
                case 'item_add':    $text = __('Publish an item') . ' - ' . osc_page_title(); break;
                case 'item_edit':   $text = __('Edit your item') . ' - ' . osc_page_title(); break;
                case 'send_friend': $text = __('Send to a friend') . ' - ' . osc_item_title() . ' - ' . osc_page_title(); break;
                case 'contact':     $text = __('Contact seller') . ' - ' . osc_item_title() . ' - ' . osc_page_title(); break;
                default:            $text = osc_item_title() . ' - ' . osc_page_title(); break;
            }
        break;
        case('page'):
            $text = osc_static_page_title() . ' - ' . osc_page_title();
        break;
        case('error'):
            $text = __('Error') . ' - ' . osc_page_title();
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
                $s_page = __('page') . ' ' . ($i_page + 1) . ' - ';
            }

            $b_show_all = ($region == '' && $city == '' & $pattern == '' && $category == '');
            $b_category = ($category != '');
            $b_pattern  = ($pattern != '');
            $b_city     = ($city != '');
            $b_region   = ($region != '');

            if($b_show_all) {
                $text = __('Show all items') . ' - ' . $s_page . osc_page_title();
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
                $result = __('Search');
            }

            $text = $result . ' - ' . $s_page . osc_page_title();
        break;
        case('login'):
            switch ($section) {
                case('recover'): $text = __('Recover your password') . ' - ' . osc_page_title();
                default:         $text = __('Login') . ' - ' . osc_page_title();
            }
        break;
        case('register'):
            $text = __('Create a new account') . ' - ' . osc_page_title();
        break;
        case('user'):
            switch ($section) {
                case('dashboard'):       $text = __('Dashboard') . ' - ' . osc_page_title(); break;
                case('items'):           $text = __('Manage my items') . ' - ' . osc_page_title(); break;
                case('alerts'):          $text = __('Manage my alerts') . ' - ' . osc_page_title(); break;
                case('profile'):         $text = __('Update my profile') . ' - ' . osc_page_title(); break;
                case('change_email'):    $text = __('Change my email') . ' - ' . osc_page_title(); break;
                case('change_password'): $text = __('Change my password') . ' - ' . osc_page_title(); break;
                case('forgot'):          $text = __('Recover my password') . ' - ' . osc_page_title(); break;
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

    $text = str_replace("\n", '', $text) ;
    $text = trim($text) ;
    $text = osc_esc_html($text) ;
    return (osc_apply_filter('meta_title_filter', $text)) ;
}

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
            $text = osc_highlight(strip_tags(osc_static_page_text()), 140, '', '') ;
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
            break;
        case(''): // home
            $result = '';
            if(osc_count_latest_items() == 0) {
                $text = '';
            }

            if(osc_has_latest_items()) {
                $result = osc_item_category() . ', ' . osc_highlight(strip_tags(osc_item_description()), 140) . '..., ' . osc_item_category();
            }

            osc_reset_latest_items();
            $text = $result;
        break;
    }

    $text = str_replace("\n", '', $text) ;
    $text = trim($text) ;
    $text = osc_esc_html($text) ;
    return (osc_apply_filter('meta_description_filter', $text)) ;
}


?>