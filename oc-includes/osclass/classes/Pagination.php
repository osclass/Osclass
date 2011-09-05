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

    class Pagination
    {
        protected $total;
        protected $selected;
        protected $class_first;
        protected $class_last;
        protected $class_prev;
        protected $class_next;
        protected $text_first;
        protected $text_last;
        protected $text_prev;
        protected $text_next;
        protected $class_selected;
        protected $class_non_selected;
        protected $delimiter;
        protected $force_limits;
        protected $sides;
        protected $url;

        public function __construct($params = null)
        {
            $this->total              = isset($params['total']) ? $params['total'] : osc_search_total_pages() ;
            $this->selected           = isset($params['selected']) ? $params['selected'] : osc_search_page() ;
            $this->class_first        = isset($params['class_first']) ? $params['class_first'] : 'searchPaginationFirst' ;
            $this->class_last         = isset($params['class_last']) ? $params['class_last'] : 'searchPaginationLast' ;
            $this->class_prev         = isset($params['class_prev']) ? $params['class_prev'] : 'searchPaginationPrev' ;
            $this->class_next         = isset($params['class_next']) ? $params['class_next'] : 'searchPaginationNext' ;
            $this->text_first         = isset($params['text_first']) ? $params['text_first'] : '&laquo;' ;
            $this->text_last          = isset($params['text_last']) ? $params['text_last'] : '&raquo' ;
            $this->text_prev          = isset($params['text_prev']) ? $params['text_prev'] : '&lt;' ;
            $this->text_next          = isset($params['text_next']) ? $params['text_next'] : '&gt;' ;
            $this->class_selected     = isset($params['class_selected']) ? $params['class_selected'] : 'searchPaginationSelected' ;
            $this->class_non_selected = isset($params['class_non_selected']) ? $params['class_non_selected'] : 'searchPaginationNonSelected' ;
            $this->delimiter          = isset($params['delimiter']) ? $params['delimiter'] : " " ;
            $this->force_limits       = isset($params['force_limits']) ? (bool)$params['delimiter'] : false ;
            $this->sides              = isset($params['sides']) ? $params['sides'] : 2 ;
            $this->url                = isset($params['url']) ? $params['url'] : osc_update_search_url(array('iPage' => '{PAGE}')) ;
        }

        public function get_raw_pages($params = null)
        {
            $pages = array();

            $pages['first'] = 0 ;
            $pages['prev']  = ($this->selected>0)?$this->selected-1:'' ;

            for( $p = ($this->selected - $this->sides); $p < $this->selected; $p++ ) {
                if( $p >= 0 ) {
                    $pages['pages'][] = $p ;
                }
            }

            $pages['pages'][] = $this->selected ;

            for($p = ( $this->selected + 1 ); $p <= ( $this->selected + $this->sides ); $p++ ) {
                if( $p < $this->total ) {
                    $pages['pages'][] = $p ;
                }
            }

            $pages['next'] = ($this->selected < ($this->total - 1) ) ? $this->selected + 1 : '' ;
            $pages['last'] = $this->total - 1 ;
            return $pages;
        }

        public function get_pages()
        {
            $pages = $this->get_raw_pages();

            if( !$this->force_limits ) {
                if( $pages['first'] == $pages['pages'][0] ) {
                    unset($pages['first']);
                }
                if( $pages['last'] == $pages['pages'][count($pages['pages']) - 1] ) {
                    unset($pages['last']);
                }
            }
            if( $pages['prev'] === '' ) {
                unset($pages['prev']);
            }
            if( $pages['next'] === '' ) {
                unset($pages['next']);
            }

            return $pages;
        }

        public function get_links()
        {
            $pages = $this->get_pages();
            $links = array();
            if( isset($pages['first']) ) {
                $links[] = '<a class="' . $this->class_first . '" href="' . str_replace('{PAGE}', $pages['first'], str_replace(urlencode('{PAGE}'), $pages['first'], $this->url)) . '">' . $this->text_first . '</a>';
            }
            if( isset($pages['prev']) ) {
                $links[] = '<a class="' . $this->class_prev . '" href="' . str_replace('{PAGE}', $pages['prev'], str_replace(urlencode('{PAGE}'), $pages['prev'], $this->url)) . '">' . $this->text_prev . '</a>';
            }
            foreach($pages['pages'] as $p) {
                if( $p == $this->selected ) {
                    $links[] = '<a class="' . $this->class_selected . '" href="' . str_replace('{PAGE}', $p, str_replace(urlencode('{PAGE}'), $p, $this->url)) . '">' . ($p + 1) . '</a>';
                } else {
                    $links[] = '<a class="' . $this->class_non_selected . '" href="' . str_replace('{PAGE}', $p, str_replace(urlencode('{PAGE}'), $p, $this->url)) . '">' . ($p + 1) . '</a>';
                }
            }
            if( isset($pages['next']) ) {
                $links[] = '<a class="' . $this->class_next . '" href="' . str_replace('{PAGE}', $pages['next'], str_replace(urlencode('{PAGE}'), $pages['next'], $this->url)) . '">' . $this->text_next . '</a>';
            }
            if( isset($pages['last']) ) {
                $links[] = '<a class="' . $this->class_last . '" href="' . str_replace('{PAGE}', $pages['last'], str_replace(urlencode('{PAGE}'), $pages['last'], $this->url)) . '">' . $this->text_last . '</a>';
            }

            return $links;
        }

        public function doPagination()
        {
            if( $this->total > 1 ) {
                $links = $this->get_links();
                return implode($this->delimiter, $links);            
            } else {
                return '';
            }
        }
    }
  
?>