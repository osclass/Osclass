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
    * Helper Pagination
    * @package OSClass
    * @subpackage Helpers
    * @author OSClass
    */

    /**
     * Gets the pagination links of search pagination
     *
     * @return string pagination links
     */
    function osc_search_pagination() {
        $pagination = new Pagination();
        return $pagination->doPagination();
    }

    /**
     * Gets the pagination links of comments pagination
     *
     * @return string pagination links
     */
    function osc_comments_pagination() {
        if( (osc_comments_per_page() == 0) || (osc_item_comments_page() === 'all') ) {
            return '';
        } else {
            $params = array('total'    => ceil(osc_item_total_comments()/osc_comments_per_page())
                           ,'selected' => osc_item_comments_page()
                           ,'url'      => osc_item_comments_url('{PAGE}'));
            $pagination = new Pagination($params);
            return $pagination->doPagination();
        }
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
     *
     * @return string pagination links
     */
    function osc_pagination($params = null) {
        $pagination = new Pagination($params);
        return $pagination->doPagination();
    }

?>