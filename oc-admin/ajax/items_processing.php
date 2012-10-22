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
        private $withFilters = false;
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
        }

        function __destruct()
        {
            unset($this->_get) ;
        }
        
        // ------------------------------------------------------------------------
        
        /*
         * Return data for be displayed at 'Admin panel -> Reported listings'
         * 
         */
        public function reported_listings( $params )
        {
            $this->_get = $params ;
            
            $this->getDBParams() ;

            $this->mSearch->limit($this->start, $this->limit) ;
            // only some fields can be ordered
            $direction  = Params::getParam('direction') ;
            $arrayDirection = array('desc', 'asc');
            if( !in_array($direction, $arrayDirection) ) {
                Params::setParam('direction', 'desc') ;
                $direction = 'desc'; 
            }
            
            $sort       = Params::getParam('sort') ;
            $arraySortColumns = array(
                'spam'  => 'i_num_spam',
                'bad'   => 'i_num_bad_classified',
                'rep'   => 'i_num_repeated',
                'off'   => 'i_num_offensive',
                'exp'   => 'i_num_expired',
                'date'  => 'dt_pub_date'
                );
            // column sort
            if( !key_exists($sort, $arraySortColumns) ) {
                $sort       = 'dt_pub_date' ;
                $this->mSearch->addHaving('i_num_spam > 0 OR i_num_bad_classified > 0 OR i_num_repeated > 0 OR i_num_offensive > 0 OR i_num_expired > 0');
            } else {
                $sort = $arraySortColumns[$sort];
                if($sort!='dt_pub_date') {
                    $this->mSearch->addHaving($sort.' > 0');
                } else {
                    $this->mSearch->addHaving('i_num_spam > 0 OR i_num_bad_classified > 0 OR i_num_repeated > 0 OR i_num_offensive > 0 OR i_num_expired > 0');
                }
            }
            
            $this->mSearch->order( $sort, $direction ) ;
            
            $this->mSearch->addTable(sprintf("%st_item_stats s", DB_TABLE_PREFIX));
            $this->mSearch->addField('SUM(s.`i_num_spam`) as i_num_spam');
            $this->mSearch->addField('SUM(s.`i_num_bad_classified`) as i_num_bad_classified');
            $this->mSearch->addField('SUM(s.`i_num_repeated`) as i_num_repeated');
            $this->mSearch->addField('SUM(s.`i_num_offensive`) as i_num_offensive');
            $this->mSearch->addField('SUM(s.`i_num_expired`) as i_num_expired');
            
            // having
            
            
            $this->mSearch->addConditions(sprintf(" %st_item.pk_i_id ", DB_TABLE_PREFIX));
            $this->mSearch->addConditions(sprintf(" %st_item.pk_i_id = s.fk_i_item_id", DB_TABLE_PREFIX));
            $this->mSearch->addGroupBy(sprintf(" %st_item.pk_i_id ", DB_TABLE_PREFIX)) ;
            // do Search
            $list_items = $this->mSearch->doSearch(true) ;
            $this->items = Item::newInstance()->extendCategoryName( $list_items );
            
            $this->total_filtered = $this->mSearch->countAll();
            $this->total = $this->mSearch->count() ;
            
            $this->toArrayFormatReported() ;
            return $this->result;
        }
        
        /*
         * Return data for be displayed at 'Admin panel -> Manage listings'
         * 
         */
        public function listings( $params )
        {
            $this->_get = $params ;
            
            $this->getDBParams() ;
                
            $this->mSearch->limit($this->start, $this->limit) ;
            
            $direction  = Params::getParam('direction') ;
            $arrayDirection = array('desc', 'asc');
            if( !in_array($direction, $arrayDirection) ) {
                Params::setParam('direction', 'desc') ;
                $direction = 'desc'; 
            }
            
            // column sort
            $sort       = Params::getParam('sort') ;
            $arraySortColumns = array('date'  => 'dt_pub_date');
            if( !key_exists($sort, $arraySortColumns) ) {
                $sort       = 'dt_pub_date' ;
            } else {
                $sort = $arraySortColumns[$sort];
            }
            // only some fields can be ordered
            $this->mSearch->order( $sort, $direction ) ;
            
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
            
            return $this->result();
        }
        
        // ------------------------------------------------------------------------

        public function filters()
        {
            return $this->withFilters;
        }
        
        private function getDBParams()
        {
            // default values
            if( !isset($this->_get['iDisplayStart']) ) {
                $this->_get['iDisplayStart'] = 0 ;
            }
            if( !isset($this->_get['iDisplayLength']) ) {
                $this->_get['iDisplayLength'] = 10 ;
            }
            $p_iPage      = 1;
            if( !is_numeric(Params::getParam('iPage')) || Params::getParam('iPage') < 1 ) {
                Params::setParam('iPage', $p_iPage );
                $this->iPage = $p_iPage ;
            } else {
                $this->iPage = Params::getParam('iPage') ;
            }
            
            $withUserId     = false;
            $no_user_email  = '';
            // get & set values
            foreach($this->_get as $k => $v) {

                if( $k == 'sSearch' && $v != '') {
                    $this->search = $v;
                    $this->withFilters = true;
                }

                // filters
                if( $k == 'userId' && $v != '') {
                    $this->mSearch->fromUser($v);
                    $this->withFilters = true;
                    $withUserId = true;
                }
                if( $k == 'itemId' && $v != '') {
                    $this->mSearch->addItemId($v);
                    $this->withFilters = true;
                }
                
                // si hay id mejor ...
                if( $k == 'countryId' && $v != '') {
                    $this->mSearch->addCountry($v);
                    $this->withFilters = true;
                }
                if( $k == 'regionId' && $v != '') {
                    $this->mSearch->addRegion($v);
                    $this->withFilters = true;
                }
                if( $k == 'cityId' && $v != '') {
                    $this->mSearch->addCity($v);
                    $this->withFilters = true;
                }
                
                if( $k == 'country' && $v != '') {
                    $this->mSearch->addCountry($v);
                    $this->withFilters = true;
                }
                if( $k == 'region' && $v != '') {
                    $this->mSearch->addRegion($v);
                    $this->withFilters = true;
                }
                
                if( $k == 'city' && $v != '') {
                    $this->mSearch->addCity($v);
                    $this->withFilters = true;
                }
                
                if( $k == 'catId' && $v != '') {
                    $this->mSearch->addCategory($v);
                    $this->withFilters = true;
                }
                if( $k == 'b_premium' && $v != '') {
                    $this->mSearch->addItemConditions(DB_TABLE_PREFIX.'t_item.b_premium = '.$v);
                    $this->withFilters = true;
                }
                if( $k == 'b_active' && $v != '') {
                    $this->mSearch->addItemConditions(DB_TABLE_PREFIX.'t_item.b_active = '.$v);
                    $this->withFilters = true;
                }
                if( $k == 'b_enabled' && $v != '') {
                    $this->mSearch->addItemConditions(DB_TABLE_PREFIX.'t_item.b_enabled = '.$v);
                    $this->withFilters = true;
                }
                if( $k == 'b_spam' && $v != '') {
                    $this->mSearch->addItemConditions(DB_TABLE_PREFIX.'t_item.b_spam = '.$v);
                    $this->withFilters = true;
                }
                if( $k == 'user' && $v != '') {
                    $no_user_email = $v;
                }
            }
            
            // add no registred user email if userId == '' and $no_user_email != ''
            if( $no_user_email != '' && !$withUserId ) {
                $this->mSearch->addContactEmail($no_user_email);
                $this->withFilters = true;
            }
            
            // set start and limit using iPage param
            $start = ($this->iPage - 1) * $this->_get['iDisplayLength'];
            
            $this->start = intval( $start ) ;
            $this->limit = intval( $this->_get['iDisplayLength'] ) ;
        }
        
        /**
         * new Design - return array
         * @return type 
         */
        private function toArrayFormat() {
            $this->result['iTotalRecords']        = $this->total_filtered ;
            $this->result['iTotalDisplayRecords'] = $this->total ;
            $this->result['sColumns']             = $this->sColumns ;
            $this->result['iDisplayLength']       = $this->_get['iDisplayLength'];
            $this->result['aaData']               = array();
            $this->result['aaObject']             = array();

            if( count($this->items) == 0 ) {
                return ;
            }

            $this->result['aaObject'] = $this->items;
            
            $count = 0;
            foreach ($this->items as $aRow)
            {
                View::newInstance()->_exportVariableToView('item', $aRow);
                $row     = array() ;
                $options = array() ;
                // -- prepare data --
                // prepare item title
                $title = mb_substr($aRow['s_title'], 0, 30, 'utf-8') ;
                if($title != $aRow['s_title']) {
                    $title .= '...' ;
                }
                // show more options
                $options_more = array();
                if( $aRow['b_active'] ) {
                    $options_more[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=status&amp;id=' . $aRow['pk_i_id'] . '&amp;value=INACTIVE">' . __('Deactivate') .'</a>' ;
                } else {
                    $options_more[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=status&amp;id=' . $aRow['pk_i_id'] . '&amp;value=ACTIVE">' . __('Activate') .'</a>' ;
                }
                if( $aRow['b_enabled'] ) {
                    $options_more[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=status&amp;id=' . $aRow['pk_i_id'] . '&amp;value=DISABLE">' . __('Block') .'</a>' ;
                } else {
                    $options_more[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=status&amp;id=' . $aRow['pk_i_id'] . '&amp;value=ENABLE">' . __('Unblock') .'</a>' ;
                }
                if( $aRow['b_premium'] ) {
                    $options_more[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=status_premium&amp;id=' . $aRow['pk_i_id'] . '&amp;value=0">' . __('Unmark as premium') .'</a>' ;
                } else {
                    $options_more[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=status_premium&amp;id=' . $aRow['pk_i_id'] . '&amp;value=1">' . __('Mark as premium') .'</a>' ;
                }
                if( $aRow['b_spam'] ) {
                    $options_more[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=status_spam&amp;id=' . $aRow['pk_i_id'] . '&amp;value=0">' . __('Unmark as spam') .'</a>' ;
                } else {
                    $options_more[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=status_spam&amp;id=' . $aRow['pk_i_id'] . '&amp;value=1">' . __('Mark as spam') .'</a>' ;
                }
                
                // general options
                $options[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=item_edit&amp;id=' . $aRow['pk_i_id'] . '">' . __('Edit') . '</a>' ;
                $options[] = '<a onclick="return delete_dialog(\'' . $aRow['pk_i_id'] . '\');" href="' . osc_admin_base_url(true) . '?page=items&amp;action=delete&amp;id[]=' . $aRow['pk_i_id'] . '">' . __('Delete') . '</a>' ;
                
                // only show if there are data
                if( ItemComment::newInstance()->totalComments( $aRow['pk_i_id'] ) > 0 ) {
                    $options[] = '<a href="' . osc_admin_base_url(true) . '?page=comments&amp;action=list&amp;id=' . $aRow['pk_i_id'] . '">' . __('View comments') . '</a>' ;
                }
                if( ItemResource::newInstance()->countResources( $aRow['pk_i_id'] ) > 0 ) {
                    $options[] = '<a href="' . osc_admin_base_url(true) . '?page=media&amp;action=list&amp;resourceId=' . $aRow['pk_i_id'] . '">' . __('View media') . '</a>' ;
                }
                
                $options_more = osc_apply_filter('more_actions_manage_items', $options_more, $aRow);
                // more actions
                $moreOptions = '<li class="show-more">'.PHP_EOL.'<a href="#" class="show-more-trigger">'. __('Show more') .'...</a>'. PHP_EOL .'<ul>'. PHP_EOL ;
                foreach( $options_more as $actual ) { 
                    $moreOptions .= '<li>'.$actual."</li>".PHP_EOL;
                }
                $moreOptions .= '</ul>'. PHP_EOL .'</li>'.PHP_EOL ;
                
                $options = osc_apply_filter('actions_manage_items', $options, $aRow);
                // create list of actions
                $auxOptions = '<ul>'.PHP_EOL ;
                foreach( $options as $actual ) {
                    $auxOptions .= '<li>'.$actual.'</li>'.PHP_EOL;
                }
                $auxOptions  .= $moreOptions ;
                $auxOptions  .= '</ul>'.PHP_EOL ;
                
                $actions = '<div class="actions">'.$auxOptions.'</div>'.PHP_EOL ;
 
                // fill a row
                $row[] = '<input type="checkbox" name="id[]" value="' . $aRow['pk_i_id'] . '" active="' . $aRow['b_active'] . '" blocked="' . $aRow['b_enabled'] . '"/>' ;
                $row[] = '<a href="' . osc_item_url().'" target="_blank">' . $title. '</a>'. $actions  ;
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
         * new Design - return array
         * @return type 
         */
        private function toArrayFormatReported() {
            $this->result['iTotalRecords']        = $this->total_filtered ;
            $this->result['iTotalDisplayRecords'] = $this->total ;
            $this->result['sColumns']             = $this->sColumns ;
            $this->result['iDisplayLength']       = $this->_get['iDisplayLength'];
            $this->result['aaData']               = array() ;

            if( count($this->items) == 0 ) {
                return ;
            }

            $this->result['aaObject'] = $this->items;
            
            $count = 0;
            foreach ($this->items as $aRow)
            {
                View::newInstance()->_exportVariableToView('item', $aRow);
                $row     = array() ;
                $options = array() ;
                // -- prepare data --
                // prepare item title
                $title = mb_substr($aRow['s_title'], 0, 30, 'utf-8') ;
                if($title != $aRow['s_title']) {
                    $title .= '...' ;
                }
                
                $options[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=clear_stat&amp;id=' . $aRow['pk_i_id'] . '&amp;stat=all">' . __('Clear All') .'</a>' ;
                if( $aRow['i_num_spam'] > 0 ) {
                    $options[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=clear_stat&amp;id=' . $aRow['pk_i_id'] . '&amp;stat=spam">' . __('Clear Spam') .'</a>' ;
                } 
                if( $aRow['i_num_bad_classified'] > 0 ) {
                    $options[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=clear_stat&amp;id=' . $aRow['pk_i_id'] . '&amp;stat=bad">' . __('Clear Misclassified') .'</a>' ;
                }
                if( $aRow['i_num_repeated'] > 0 ) {
                    $options[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=clear_stat&amp;id=' . $aRow['pk_i_id'] . '&amp;stat=duplicated">' . __('Clear Duplicated') .'</a>' ;
                }
                if( $aRow['i_num_offensive'] > 0 ) {
                    $options[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=clear_stat&amp;id=' . $aRow['pk_i_id'] . '&amp;stat=offensive">' . __('Clear Offensive') .'</a>' ;
                }
                if( $aRow['i_num_expired'] > 0 ) {
                    $options[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=clear_stat&amp;id=' . $aRow['pk_i_id'] . '&amp;stat=expired">' . __('Clear Expired') .'</a>' ;
                }
                if(count($options) > 0) {
                    $options[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=item_edit&amp;id=' . $aRow['pk_i_id'] . '">' . __('Edit') . '</a>' ;
                    $options[] = '<a onclick="return delete_dialog(\'' . $aRow['pk_i_id'] . '\');" href="' . osc_admin_base_url(true) . '?page=items&amp;action=delete&amp;id[]=' . $aRow['pk_i_id'] . '">' . __('Delete') . '</a>' ;
                }

                // create list of actions
                $auxOptions = '<ul>'.PHP_EOL ;
                foreach( $options as $actual ) {
                    $auxOptions .= '<li>'.$actual.'</li>'.PHP_EOL;
                }
                $auxOptions  .= '</ul>'.PHP_EOL ;
                
                $actions = '<div class="actions">'.$auxOptions.'</div>'.PHP_EOL ;

                // fill a row
                $row[] = '<input type="checkbox" name="id[]" value="' . $aRow['pk_i_id'] . '" active="' . $aRow['b_active'] . '" blocked="' . $aRow['b_enabled'] . '"/>' ;
                $row[] = '<a href="' . osc_item_url().'" target="_blank">' . $title . '</a>'. $actions  ;
                $row[] = $aRow['s_user_name'] ;
                $row[] = $aRow['i_num_spam'] ;
                $row[] = $aRow['i_num_bad_classified'] ;
                $row[] = $aRow['i_num_repeated'] ;
                $row[] = $aRow['i_num_expired'] ;
                $row[] = $aRow['i_num_offensive'] ;
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
         * Dump $result to JSON and return the result
         * 
         * @access private
         * @since unknown 
         */
        public function result()
        {
            $this->toArrayFormat();
            return $this->result;
        }

        /**
         * Dump $result
         * 
         * @access private
         * @since unknown 
         */
        public function dumpToDatatables()
        {
            $this->toDatatablesFormat() ;
            $this->dumpResult() ;
        }
     }

    /* file end: ./oc-admin/ajax/items_processing.php */
?>