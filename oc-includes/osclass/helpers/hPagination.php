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
    * Helper Pagination
    * @package Osclass
    * @subpackage Helpers
    * @author Osclass
    */

    /**
     * Gets the pagination links of search pagination
     *
     * @return string pagination links
     */
    function osc_search_pagination()
    {
        $params = array();
        if( View::newInstance()->_exists('search_uri') ) { // CANONICAL URL
            $params['url'] = osc_base_url().View::newInstance()->_get('search_uri') . '/{PAGE}';
            $params['first_url'] = osc_base_url().View::newInstance()->_get('search_uri');
        } else {
            $params['first_url'] = osc_update_search_url(array('iPage' => null));
        }
        $pagination = new Pagination($params);
        return $pagination->doPagination();
    }

    /**
     * Gets the pagination links of comments pagination
     *
     * @return string pagination links
     */
    function osc_comments_pagination() {
        if( (osc_comments_per_page() == 0) || (osc_item_comments_page() === 'all') || (osc_item_total_comments() <= osc_comments_per_page())) {
            return '';
        } else {
            $params = array('total'    => ceil(osc_item_total_comments()/osc_comments_per_page())
                           ,'selected' => osc_item_comments_page()
                           ,'url'      => osc_item_comments_url('{PAGE}'));
            $pagination = new Pagination($params);
            return $pagination->doPagination();
        }
    }

    function osc_pagination_items($extraParams = array(), $field = false)
    {
        if(osc_is_public_profile()) {
            $url = osc_user_list_items_pub_profile_url('{PAGE}', $field);
            $first_url = osc_user_public_profile_url();;
        } elseif(osc_is_list_items()) {
            $url = osc_user_list_items_url('{PAGE}', $field);
            $first_url = osc_user_list_items_url();
        }

        $params = array('total'    => osc_search_total_pages(),
                        'selected' => osc_search_page(),
                        'url'      => $url,
                        'first_url' => $first_url
                  );

        if(is_array($extraParams) && !empty($extraParams)) {
            foreach($extraParams as $key => $value) {
                $params[$key] = $value;
            }
        }
        $pagination = new Pagination($params);
        return $pagination->doPagination();
    }

    /**
     * Gets generic pagination links
     *
     * @array $params
     *          'total' => number of total pages (default osc_search_total_pages())
     *          'selected' => number of the page selected (starting at 0) (default osc_search_page())
     *          'class_first' => css class for the first link (default 'searchPaginationFirst')
     *          'class_last' => css class for the last link (default 'searchPaginationLast')
     *          'class_prev' => css class for the prev link (default 'searchPaginationPrev')
     *          'class_next' => css class for the next link (default 'searchPaginationNext')
     *          'text_first' => text for the first link ('<<', 'First', ...) (default '&laquo;')
     *          'text_prev' => text for the first link ('<', 'Previous.', ...) (default '&raquo;')
     *          'text_next' => text for the first link ('>', 'Next', ...) (default '&lt;')
     *          'text_last' => text for the lastst link ('>>', 'Last', ...) (default '&gt;')
     *          'class_selected' => css class for the selected link (default 'searchPaginationSelected')
     *          'class_non_selected' => css class for non selected links (default 'searchPaginationNonSelected')
     *          'delimiter' => delimiter between links (default " ")
     *          'force_limits' => Always show the first/last links even if you're already on first/last page (default false)
     *          'sides' => How many pages to show (default 2)
     *          'url' => Format of the URL of the links, put "{PAGE}" on the page variable. Example http://www.example.com/index.php?page=search&amp;sCategory=2&amp;iPage={PAGE} (default osc_update_search_url(array('iPage' => '{PAGE}'))
     *          'first_url' => Format of the FIRST URL of the links, if you want to avoid to have the page variable "{PAGE}" on the page variable. Example http://www.example.com/index.php?page=search&amp;sCategory=2&amp; (default osc_update_search_url(array('iPage' => null))
     *
     * @return string pagination links
     */
    function osc_pagination($params = null)
    {
        $pagination = new Pagination($params);
        return $pagination->doPagination();
    }

    function osc_show_pagination_admin($aData)
    {
        $pageActual = isset($aData['iPage'])?$aData['iPage']:Params::getParam('iPage');
        $urlActual  = osc_admin_base_url(true).'?'.Params::getServerParam('QUERY_STRING', false, false);
        $urlActual  = preg_replace('/&iPage=(\d+)?/', '', $urlActual);
        $pageTotal  = ceil($aData['iTotalDisplayRecords']/$aData['iDisplayLength']);
        $params     = array(
            'total'    => $pageTotal,
            'selected' => $pageActual - 1,
            'url'      => $urlActual . '&iPage={PAGE}',
            'sides'    => 5
        );

    ?>
    <div class="has-pagination">
        <?php osc_run_hook('before_show_pagination_admin'); ?>
        <?php if( $pageTotal > 1 ) { ?>
        <form method="get" action="<?php echo $urlActual; ?>" style="display:inline;">
            <?php foreach( Params::getParamsAsArray('get') as $key => $value ) { ?>
            <?php if($key!='iPage') { ?>
            <input type="hidden" name="<?php echo osc_esc_html($key); ?>" value="<?php echo osc_esc_html($value); ?>" />
            <?php } } ?>
            <ul>
                <li>
                    <span class="list-first"><?php _e('Page'); ?></span>
                </li>
                <li class="pagination-input">
                    <input id="gotoPage" type="text" name="iPage" value="<?php echo osc_esc_html($pageActual); ?>"/><button type="submit"><?php _e('Go!'); ?></button>
                </li>
            </ul>
        </form>
        <?php
            $pagination = new Pagination($params);
            $aux = $pagination->doPagination();
            echo $aux;
        }
        osc_run_hook('after_show_pagination_admin');
    ?>
    </div>
    <?php
    }

    function osc_pagination_showing($from, $to, $filtered, $total = null)
    {
        if($to==0 || $filtered==0) {
            $from = $to = $filtered = 0;
        }
        if($total!=null && $total>$filtered) {
            return sprintf(__("Showing %s to %s of %s results (filtered from %s total results)"), $from, $to, $filtered, $total);
        } else {
            return sprintf(__("Showing %s to %s of %s results"), $from, $to, $filtered);
        }
    }

?>