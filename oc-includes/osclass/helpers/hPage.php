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
    * Helper Pages
    * @package Osclass
    * @subpackage Helpers
    * @author Osclass
    */

    /**
     * Gets current page object
     *
     * @return array
     */
    function osc_static_page() {
        if (View::newInstance()->_exists('pages')) {
            $page = View::newInstance()->_current('pages');
        } else if (View::newInstance()->_exists('page')) {
            $page = View::newInstance()->_get('page');
        } else {
            $page = null;
        }

        if ( !View::newInstance()->_exists('page_meta') ) {
            View::newInstance()->_exportVariableToView('page_meta', json_decode(@$page['s_meta'], true));
        }

        return($page);
    }

    /**
     * Gets current page field
     *
     * @param string $field
     * @param string $locale
     * @return string
     */
    function osc_static_page_field($field, $locale = '') {
        return osc_field(osc_static_page(), $field, $locale);
    }

    /**
     * Gets current page title
     *
     * @param string $locale
     * @return string
     */
    function osc_static_page_title($locale = '') {
        if ($locale == "") $locale = osc_current_user_locale();
        return osc_static_page_field("s_title", $locale);
    }

    /**
     * Gets current page text
     *
     * @param string $locale
     * @return string
     */
    function osc_static_page_text($locale = '') {
        if ($locale == "") $locale = osc_current_user_locale();
        return osc_static_page_field("s_text", $locale);
    }

    /**
     * Gets current page ID
     *
     * @return string
     */
    function osc_static_page_id() {
        return osc_static_page_field("pk_i_id");
    }

    /**
     * Get page order
     *
     * @return int
     */
    function osc_static_page_order() {
        return (int)osc_static_page_field("i_order");
    }

    /**
     * Gets current page modification date
     *
     * @return string
     */
    function osc_static_page_mod_date() {
        return osc_static_page_field("dt_mod_date");
    }

    /**
     * Gets current page publish date
     *
     * @return string
     */
    function osc_static_page_pub_date() {
        return osc_static_page_field("dt_pub_date");
    }

    /**
     * Gets current page slug or internal name
     *
     * @return string
     */
    function osc_static_page_slug() {
        return osc_static_page_field("s_internal_name");
    }

    /**
     * Gets current page meta information
     *
     * @return string
     */
    function osc_static_page_meta($field = null) {
        if ( !View::newInstance()->_exists('page_meta') ) {
            $meta = json_decode(osc_static_page_field("s_meta"),  true);
        } else {
            $meta = View::newInstance()->_get('page_meta');
        }
        if ($field == null) {
            $meta = (isset($meta[$field]) && !empty($meta[$field])) ? $meta[$field] : '';
        }
        return $meta;
    }

    /**
     * Gets current page url
     *
     * @param string $locale
     * @return string
     */
    function osc_static_page_url($locale = '') {
        if ( osc_rewrite_enabled() ) {
            $sanitized_categories = array();
            $cat = Category::newInstance()->hierarchy(osc_item_category_id());
            for ($i = (count($cat)); $i > 0; $i--) {
                $sanitized_categories[] = $cat[$i - 1]['s_slug'];
            }
            $url = str_replace('{PAGE_TITLE}', osc_static_page_title(), str_replace('{PAGE_ID}', osc_static_page_id(), str_replace('{PAGE_SLUG}', urlencode(osc_static_page_slug()), osc_get_preference('rewrite_page_url'))));
            if($locale!='') {
                $path = osc_base_url().$locale."/".$url;
            } else {
                $path = osc_base_url().$url;
            }
        } else {
            if($locale!='') {
                $path = osc_base_url(true)."?page=page&id=".osc_static_page_id()."&lang=".$locale;
            } else {
                $path = osc_base_url(true)."?page=page&id=".osc_static_page_id();
            }
        }
        return $path;
    }

    /**
     * Gets the specified static page by internal name.
     *
     * @param string $internal_name
     * @param string $locale
     * @return boolean
     */
    function osc_get_static_page($internal_name, $locale = '') {
        if ($locale == "") $locale = osc_current_user_locale();
        $page = Page::newInstance()->findByInternalName($internal_name, $locale);
        View::newInstance()->_exportVariableToView('page_meta', json_decode(@$page['s_meta'], true));
        return View::newInstance()->_exportVariableToView('page', $page);
    }

    /**
     * Gets the total of static pages. If static pages are not loaded, this function will load them.
     *
     * @return int
     */
    function osc_count_static_pages() {
        if ( !View::newInstance()->_exists('pages') ) {
            View::newInstance()->_exportVariableToView('pages', Page::newInstance()->listAll(false) );
        }
        return View::newInstance()->_count('pages');
    }

    /**
     * Let you know if there are more static pages in the list. If static pages are not loaded,
     * this function will load them.
     *
     * @return boolean
     */
    function osc_has_static_pages() {
        if ( !View::newInstance()->_exists('pages') ) {
            View::newInstance()->_exportVariableToView('pages', Page::newInstance()->listAll(false, 1) );
        }

        $page = View::newInstance()->_next('pages');
        View::newInstance()->_exportVariableToView('page_meta', json_decode($page['s_meta'], true));
        return $page;
    }

    /**
     * Move the iterator to the first position of the pages array
     * It reset the osc_has_page function so you could have several loops
     * on the same page
     *
     * @return boolean
     */
    function osc_reset_static_pages() {
        return View::newInstance()->_erase('pages');
    }

?>
