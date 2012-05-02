<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

    /**
     * OSClass – software for creating and publishing online classified advertising platforms
     *
     * Copyright (C) 2010 OSCLASS
     *
     * This program is free software: you can redistribute it and/or modify it under the terms
     * of the GNU Affero General Public License as published by the Free Software Foundation,
     * either version 3 of the License, or (at your option) any later version.
     *
     * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
     * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
     * See the GNU Affero General Public License for more details.
     *
     * You should have received a copy of the GNU Affero General Public
     * License along with this program. If not, see <http://www.gnu.org/licenses/>.
     */

     class ItemsProcessingAjax 
     {
        private $mSearch;
        private $items ;
        private $result ;
        private $toJSON ;

        private $limit ;
        private $start ;
        private $total ;
        private $total_filtered ;
        private $search ;
        private $order_by = array() ;
        private $stat = array() ;
        private $filters = array() ;
        private $column_names  = array(
            0 => 'dt_pub_date',
            1 => 's_title',
            2 => 's_contact_name',
            3 => 's_category_name',
            4 => 's_country',
            5 => 's_region',
            6 => 's_city',
            7 => 'dt_pub_date'
        ) ;

        private $tables_columns = array(
            0 => NULL,
            1 => NULL,
            2 => NULL,
            3 => NULL,
            4 => NULL,
            5 => NULL,
            6 => NULL,
            7 => NULL
        ) ;

        private $tables_filters = array(
            'fCol_userIdValue' => '%st_item.fk_i_user_id',
            'fCol_countryId'   => '%st_item_location.fk_c_country_code',
            'fCol_regionId'    => '%st_item_location.fk_i_region_id',
            'fCol_cityId'      => '%st_item_location.fk_i_city_id',
            'fCol_country'     => '%st_item_location.s_country',
            'fCol_region'      => '%st_item_location.s_region',
            'fCol_city'        => '%st_item_location.s_city',
            'fCol_catId'       => '%st_item.fk_i_category_id',
            'fCol_bPremium'    => '%st_item.b_premium',
            'fCol_bActive'     => '%st_item.b_active',
            'fCol_bEnabled'    => '%st_item.b_enabled',
            'fCol_bSpam'       => '%st_item.b_spam',
            'fCol_itemIdValue' => '%st_item.pk_i_id'
        ) ;

        private $conditions = array();
        /* For Datatables */
        private $sEcho = null ;
        private $sColumns = array() ;
        private $_get ;

        function __construct($params)
        {
            $this->mSearch = new Search(true) ;
            
            $this->_get = $params ;
            $this->getDBParams() ;

            $this->mSearch->limit($this->start, $this->limit) ;
            // only some fields can be ordered
            $this->mSearch->order($this->order_by['column_name'], $this->order_by['type'], $this->order_by['table_name'] ) ;
            
            if( Params::getParam('catId') != '' ) {
                $this->mSearch->addCategory( Params::getParam('catId') ) ;
            }
            if( $this->search ) {
                $this->mSearch->addPattern($this->search);
            }

            // do Search
            $list_items = $this->mSearch->doSearch(true) ;

            $this->items = Item::newInstance()->extendCategoryName( $list_items ); 
            $this->total_filtered = $this->mSearch->countAll();
            $this->total = $this->mSearch->count() ;

            $this->toDatatablesFormat() ;
            $this->dumpToDatatables() ;
        }

        function __destruct()
        {
            unset($this->_get) ;
        }

        private function getDBParams()
        {
            foreach($this->_get as $k => $v) {
                if( $k == 'iDisplayStart' ) {
                    $this->start = intval($v) ;
                }
                if( $k == 'iDisplayLength' ) {
                    $this->limit = intval($v) ;
                }
                if( $k == 'sEcho' ) {
                    $this->sEcho = intval($v) ;
                }

                /* for sorting */
                if( $k == 'iSortCol_0' ) {
                    $this->order_by['column_name'] = $this->column_names[$v] ;
                    $this->order_by['table_name']  = $this->tables_columns[$v] ;
                }
                if( $k == 'sSortDir_0' ) {
                    $this->order_by['type'] = $v ;
                }

                if( $k == 'sSearch' ) {
                    $this->search = $v;
                }

                // filters
                if( $k == 'fCol_userIdValue' ) {
                    $this->mSearch->fromUser($v);
                }
                if( $k == 'fCol_itemIdValue' ) {
                    $this->mSearch->addItemId($v);
                }
                if( $k == 'fCol_countryId' ) {
                    $this->mSearch->addCountry($v);
                }
                if( $k == 'fCol_regionId' ) {
                    $this->mSearch->addRegion($v);
                }
                if( $k == 'fCol_cityId' ) {
                    $this->mSearch->addCity($v);
                }
                
                if( $k == 'fCol_country' ) {
                    $this->mSearch->addCountry($v);
                }
                if( $k == 'fCol_region' ) {
                    $this->mSearch->addRegion($v);
                }
                if( $k == 'fCol_city' ) {
                    $this->mSearch->addCity($v);
                }
                
                if( $k == 'fCol_catId' ) {
                    $this->mSearch->addCategory($v);
                }
                if( $k == 'fCol_bPremium' ) {
                    $this->mSearch->addItemConditions(DB_TABLE_PREFIX.'t_item.b_premium = '.$v);
                }
                if( $k == 'fCol_bActive' ) {
                    $this->mSearch->addItemConditions(DB_TABLE_PREFIX.'t_item.b_active = '.$v);
                }
                if( $k == 'fCol_bEnabled' ) {
                    $this->mSearch->addItemConditions(DB_TABLE_PREFIX.'t_item.b_enabled = '.$v);
                }
                if( $k == 'fCol_bSpam' ) {
                    $this->mSearch->addItemConditions(DB_TABLE_PREFIX.'t_item.b_spam = '.$v);
                }
            }
        }

        /* START - format functions */
        private function toDatatablesFormat() {
            $this->result['iTotalRecords']        = $this->total_filtered ;
            $this->result['iTotalDisplayRecords'] = $this->total ;
            $this->result['sEcho']                = $this->sEcho ;
            $this->result['sColumns']             = $this->sColumns ;
            $this->result['aaData']               = array() ;

            if( count($this->items) == 0 ) {
                return ;
            }

            $count = 0;
            foreach ($this->items as $aRow)
            {
                View::newInstance()->_exportVariableToView('item', $aRow);
                $row     = array() ;
                $options = array() ;

                // prepare data
                $title = mb_substr($aRow['s_title'], 0, 30, 'utf-8') ;
                if($title != $aRow['s_title']) {
                    $title .= '...' ;
                }

                $options[] = '<a href="' . osc_item_url().'">' . __('View item') . '</a>' ;
                $options[] = '<a href="' . osc_admin_base_url(true) . '?page=comments&amp;action=list&amp;id=' . $aRow['pk_i_id'] . '">' . __('View comments') . '</a>' ;
                $options[] = '<a href="' . osc_admin_base_url(true) . '?page=media&amp;action=list&amp;id=' . $aRow['pk_i_id'] . '">' . __('View media') . '</a>' ;
                if( $aRow['b_active'] ) {
                    $options[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=status&amp;id=' . $aRow['pk_i_id'] . '&amp;value=INACTIVE">' . __('Deactivate') .'</a>' ;
                } else {
                    $options[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=status&amp;id=' . $aRow['pk_i_id'] . '&amp;value=ACTIVE">' . __('Activate') .'</a>' ;
                }
                if( $aRow['b_enabled'] ) {
                    $options[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=status&amp;id=' . $aRow['pk_i_id'] . '&amp;value=DISABLE">' . __('Block') .'</a>' ;
                } else {
                    $options[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=status&amp;id=' . $aRow['pk_i_id'] . '&amp;value=ENABLE">' . __('Unblock') .'</a>' ;
                }
                if( $aRow['b_premium'] ) {
                    $options[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=status_premium&amp;id=' . $aRow['pk_i_id'] . '&amp;value=0">' . __('Unmark as premium') .'</a>' ;
                } else {
                    $options[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=status_premium&amp;id=' . $aRow['pk_i_id'] . '&amp;value=1">' . __('Mark as premium') .'</a>' ;
                }
                if( $aRow['b_spam'] ) {
                    $options[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=status_spam&amp;id=' . $aRow['pk_i_id'] . '&amp;value=0">' . __('Unmark as spam') .'</a>' ;
                } else {
                    $options[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=status_spam&amp;id=' . $aRow['pk_i_id'] . '&amp;value=1">' . __('Mark as spam') .'</a>' ;
                }
                $options[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=item_edit&amp;id=' . $aRow['pk_i_id'] . '">' . __('Edit') . '</a>' ;
                $onclick_delete = 'onclick="javascript:return confirm(\'' . osc_esc_js( __('This action can not be undone. Are you sure you want to continue?') ) . '\')"' ;
                $options[] = '<a ' . $onclick_delete . ' href="' . osc_admin_base_url(true) . '?page=items&amp;action=delete&amp;id[]=' . $aRow['pk_i_id'] . '">' . __('Delete') . '</a>' ;
                foreach($this->stat as $k => $s) {
                    $options[] = '<a ' .$onclick_delete . ' href="' . osc_admin_base_url(true) . '?page=items&amp;action=clear_stat&amp;stat=' . $k . '&amp;id=' . $aRow['pk_i_id'] . '">' . sprintf( __('Clear %s'), $k ) . '</a>' ;
                }
 
                // fill a row
                $row[] = '<input type="checkbox" name="id[]" value="' . $aRow['pk_i_id'] . '" />' ;
                $row[] = $title . ' <div class="datatable_wrapper" style="display: none;"><div class="datatables_quick_edit" style="position: absolute;" >' . implode(' &middot; ', $options) . '</div></div>' ;
                $row[] = $aRow['s_user_name'] ;
                $row[] = $aRow['s_category_name'] ;
                $row[] = $aRow['s_country'] ;
                $row[] = $aRow['s_region'] ;
                $row[] = $aRow['s_city'] ;
                $row[] = $aRow['dt_pub_date'] ;

                $count++ ;
                $this->result['aaData'][] = $row ;
            }

            return ;
        }

        /**
         * Set toJson variable with the JSON representation of $result
         * 
         * @access private
         * @since unknown
         * @param array $result
         */
        private function toJSON($result)
        {
            $this->toJSON = json_encode($result) ;
        }

        /**
         * Dump $result to JSON and echo the result
         * 
         * @access private
         * @since unknown 
         */
        private function dumpResult()
        {
            $this->toJSON($this->result) ;
            echo $this->toJSON ;
        }

        /**
         * Dump $result
         * 
         * @access private
         * @since unknown 
         */
        private function dumpToDatatables()
        {
            $this->dumpResult() ;
        }
     }

    /* file end: ./oc-admin/ajax/items_processing.php */
?>