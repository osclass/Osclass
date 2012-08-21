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

     class UsersProcessingAjax 
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
        
        private $withUserId;
        private $userId;

        private $column_names  = array(
            0 => 'dt_reg_date',
            1 => 's_email',
            2 => 's_name',
            3 => 'dt_reg_date',
            4 => 'dt_mod_date'
        ) ;

        /* For Datatables */
        private $sEcho = 0 ;
        private $_get ;

        function __construct($params)
        {
            $this->withUserId = false;
            $this->_get = $params ;
            $this->getDBParams() ;

            if($this->withUserId) {
                $list_users  = User::newInstance()->searchByPrimaryKey($this->start, $this->limit, $this->userId, $this->order_by['column_name'], $this->order_by['type'] ) ;
            } else if($this->search != ''){
                $list_users  = User::newInstance()->searchByEmail($this->start, $this->limit, $this->search, $this->order_by['column_name'], $this->order_by['type'] ) ;
            } else {
                $list_users  = User::newInstance()->search($this->start, $this->limit, $this->order_by['column_name'], $this->order_by['type'] ) ;
            }
            
            $this->users = $list_users['users'] ;
            $this->total = $list_users['total_results'] ;
            $this->total_filtered = $list_users['rows'] ;

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
            
            $this->order_by['column_name'] = 'pk_i_id';
            $this->order_by['type'] = 'DESC';
            foreach($this->_get as $k=>$v) {
                if( $k == 'user') {
                    $this->search = $v;
                }
                if( $k == 'userId' && $v != '') {
                    $this->withUserId = true;
                    $this->userId = $v;
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

            if( count($this->users) == 0 ) {
                return ;
            }

            $count = 0 ;
            foreach ($this->users as $aRow) {
                $row = array() ;
                $options        = array() ;
                $options_more   = array() ;
                // first column
                $row[] = '<input type="checkbox" name="id[]" value="' . $aRow['pk_i_id'] . '" /></div>' ;
                
                $options[]  = '<a href="' . osc_admin_base_url(true) . '?page=users&action=edit&amp;id=' . $aRow['pk_i_id'] . '">' . __('Edit') . '</a>' ;
                $options[]  = '<a onclick="return delete_dialog(\'' . $aRow['pk_i_id'] . '\');" href="' . osc_admin_base_url(true) . '?page=users&action=delete&amp;id[]=' . $aRow['pk_i_id'] . '">' . __('Delete') . '</a>' ;
                
                if( $aRow['b_active'] == 1 ) {
                    $options_more[] = '<a href="' . osc_admin_base_url(true) . '?page=users&action=deactivate&amp;id[]=' . $aRow['pk_i_id'] .'">' . __('Deactivate') . '</a>' ;
                } else {
                    $options_more[] = '<a href="' . osc_admin_base_url(true) . '?page=users&action=activate&amp;id[]=' . $aRow['pk_i_id'] .'">' . __('Activate') . '</a>' ;
                }
                if( $aRow['b_enabled'] == 1 ) {
                    $options_more[] = '<a href="' . osc_admin_base_url(true) . '?page=users&action=disable&amp;id[]=' . $aRow['pk_i_id'] . '">' . __('Block') . '</a>' ;
                } else {
                    $options_more[] = '<a href="' . osc_admin_base_url(true) . '?page=users&action=enable&amp;id[]=' . $aRow['pk_i_id'] . '">' . __('Unblock') . '</a>' ;
                }
                if( osc_user_validation_enabled() && ( $aRow['b_active'] == 0 ) ) {
                    $options_more[] = '<a href="' . osc_admin_base_url(true) . '?page=users&action=resend_activation&amp;id[]=' . $aRow['pk_i_id'] . '">' . __('Re-send activation email') . '</a>' ;
                }
                
                $options_more = osc_apply_filter('more_actions_manage_users', $options_more, $aRow);
                // more actions
                $moreOptions = '<li class="show-more">'.PHP_EOL.'<a href="#" class="show-more-trigger">'. __('Show more') .'...</a>'. PHP_EOL .'<ul>'. PHP_EOL ;
                foreach( $options_more as $actual ) { 
                    $moreOptions .= '<li>'.$actual."</li>".PHP_EOL;
                }
                $moreOptions .= '</ul>'. PHP_EOL .'</li>'.PHP_EOL ;

                $options = osc_apply_filter('actions_manage_users', $options, $aRow);
                // create list of actions
                $auxOptions = '<ul>'.PHP_EOL ;
                foreach( $options as $actual ) {
                    $auxOptions .= '<li>'.$actual.'</li>'.PHP_EOL;
                }
                $auxOptions  .= $moreOptions ;
                $auxOptions  .= '</ul>'.PHP_EOL ;
                
                $actions = '<div class="actions">'.$auxOptions.'</div>'.PHP_EOL ;
                // second column
                $row[] = '<a href="' . osc_admin_base_url(true) . '?page=items&userId='. $aRow['pk_i_id'] .'&user='. $aRow['s_name'] .'">' . $aRow['s_email'] . '</a>'. $actions  ;
                
                // third row
                $row[] = $aRow['s_name'] ;
                // fourth row
                $row[] = $aRow['dt_reg_date'] ;
                // fifth row
                $row[] = $aRow['dt_mod_date'] ;

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