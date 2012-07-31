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

     class AlertsProcessingAjax 
     {
        private $users ;
        private $result ;
        private $toJSON ;

        private $limit ;
        private $start ;
        private $total ;
        private $total_filtered ;
        private $search ;
        private $order_by = array() ;

        private $column_names  = array(
            0 => 'dt_date',
            1 => 's_email',
            2 => 's_search',
            3 => 'dt_date'
        ) ;

        /* For Datatables */
        private $sEcho = 0 ;
        private $_get ;

        function __construct($params)
        {
            $this->_get = $params ;
            $this->getDBParams() ;

            $list_alerts    = Alerts::newInstance()->search($this->start, $this->limit, $this->order_by['column_name'], $this->order_by['type'], $this->search) ;
            $this->alerts   = $list_alerts['alerts'] ;
            $this->total    = $list_alerts['total_results'] ;
            $this->total_filtered = $list_alerts['rows'] ;

        }

        function __destruct()
        {
            unset($this->_get) ;
        }

        /**
         * Set variables to perform the search from $_GET
         * 
         * @access private
         * @since unkwnon 
         */
        private function getDBParams()
        {
            // default values
            if( !isset($this->_get['iDisplayStart']) ) {
                $this->_get['iDisplayStart'] = 0 ;
            }
            $p_iPage      = 1;
            if( !is_numeric(Params::getParam('iPage')) || Params::getParam('iPage') < 1 ) {
                Params::setParam('iPage', $p_iPage );
                $this->iPage = $p_iPage ;
            } else {
                $this->iPage = Params::getParam('iPage') ;
            }
            
            $this->order_by['column_name'] = 'dt_date';
            $this->order_by['type'] = 'DESC';
            foreach($this->_get as $k=>$v) {
                if( $k == 'sSearch' ) {
                    $this->search = $v ;
                }

                /* for sorting */
                if( $k == 'iSortCol_0' ) {
                    $this->order_by['column_name'] = $this->column_names[$v];
                }
                if( $k == 'sSortDir_0' ) {
                    $this->order_by['type'] = $v ;
                }
            }
            // set start and limit using iPage param
            $start = ($this->iPage - 1) * $this->_get['iDisplayLength'];
            
            $this->start = intval( $start ) ;
            $this->limit = intval( $this->_get['iDisplayLength'] ) ;
        }
        
        public function toArrayFormat()
        {
            $this->result['iTotalRecords']        = $this->total_filtered ;
            $this->result['iTotalDisplayRecords'] = $this->total ;
            $this->result['iDisplayLength']       = $this->_get['iDisplayLength'];
            $this->result['aaData']               = array() ;

            if( count($this->alerts) == 0 ) {
                return ;
            }

            $count = 0 ;
            foreach ($this->alerts as $aRow) {
                $row = array() ;
                $options        = array() ;
                // first column
                $row[] = '<input type="checkbox" name="alert_id[]" value="' . $aRow['pk_i_id'] . '" /></div>' ;
                
                $options[]  = '<a onclick="return delete_alert(\'' . $aRow['pk_i_id'] . '\');" href="#">' . __('Delete') . '</a>' ;

                
                if( $aRow['b_active'] == 1 ) {
                    $options[] = '<a href="' . osc_admin_base_url(true) . '?page=users&action=status_alerts&amp;alert_id[]=' . $aRow['pk_i_id'] . '&amp;status=0" >' . __('Deactivate') . '</a>' ;
                } else {
                    $options[] = '<a href="' . osc_admin_base_url(true) . '?page=users&action=status_alerts&amp;alert_id[]=' . $aRow['pk_i_id'] . '&amp;status=1" >' . __('Activate') . '</a>' ;
                }
                
                if( $aRow['dt_unsub_date'] == null ) {
                    $options[] = '<a href="' . osc_admin_base_url(true) . '?page=users&action=sub_alerts&amp;alert_id[]=' . $aRow['pk_i_id'] . '&amp;status=0" >' . __('Unsubscribe') . '</a>' ;
                } else {
                    $options[] = '<a href="' . osc_admin_base_url(true) . '?page=users&action=sub_alerts&amp;alert_id[]=' . $aRow['pk_i_id'] . '&amp;status=1" >' . __('Subscribe') . '</a>' ;
                }
                

                $options = osc_apply_filter('actions_manage_alerts', $options, $aRow);
                // create list of actions
                $auxOptions = '<ul>'.PHP_EOL ;
                foreach( $options as $actual ) {
                    $auxOptions .= '<li>'.$actual.'</li>'.PHP_EOL;
                }
                $auxOptions  .= '</ul>'.PHP_EOL ;
                
                $actions = '<div class="actions">'.$auxOptions.'</div>'.PHP_EOL ;
                // second column
                $row[] = '<a href="' . osc_admin_base_url(true) . '?page=items&userId=">' . $aRow['s_email'] . '</a>'. $actions  ;
                
                // third row
                
                $pieces = array();
                $conditions = osc_get_raw_search((array)json_decode(base64_decode($aRow['s_search']), true));
                if(isset($conditions['sPattern']) && $conditions['sPattern']!='') {
                    $pieces[] = sprintf(__("<b>Pattern:</b> %s"), $conditions['sPattern']);
                }
                if(isset($conditions['aCategories']) && !empty($conditions['aCategories'])) {
                    $l = min(count($conditions['aCategories']), 4);
                    $cat_array = array();
                    for($c=0;$c<$l;$c++) {
                        $cat_array[] = $conditions['aCategories'][$c];
                    }
                    if(count($conditions['aCategories'])>$l) {
                        $cat_array[] = '<a href="#" class="more-tooltip" categories="'.osc_esc_html(implode(", ", $conditions['aCategories'])).'" >'.__("...More").'</a>';
                    }

                    $pieces[] = sprintf(__("<b>Categories:</b> %s"), implode(", ", $cat_array));
                }

                $row[] = implode($pieces, ", ");
                // fourth row
                $row[] = $aRow['dt_date'] ;

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
        public function dumpToDatatables()
        {
            $this->toDatatablesFormat() ;
            $this->dumpResult() ;
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
     }

     /* file end: ./oc-admin/ajax/users_processing.php */
?>