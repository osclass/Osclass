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
            $this->_get = $params ;
            $this->getDBParams() ;

            $list_users  = User::newInstance()->search($this->start, $this->limit, $this->order_by['column_name'], $this->order_by['type'], $this->search) ;
            $this->users = $list_users['users'] ;
            $this->total = $list_users['total_results'] ;
            $this->total_filtered = $list_users['rows'] ;

            $this->toDatatablesFormat() ;
            $this->dumpToDatatables() ;
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
            $this->order_by['column_name'] = 'pk_i_id';
            $this->order_by['type'] = 'DESC';
            foreach($this->_get as $k=>$v) {
                if( $k == 'iDisplayStart' ) {
                    $this->start = intval($v) ;
                }
                if( $k == 'iDisplayLength' ) {
                    $this->limit = intval($v) ;
                }
                if( $k == 'sEcho' ) {
                    $this->sEcho = intval($v) ;
                }
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
        }

        /**
         * Set the $result variable with the appropiate data
         * 
         * @access private
         * @since unknown
         */
        private function toDatatablesFormat()
        {
            $this->result['iTotalRecords']        = $this->total_filtered ;
            $this->result['iTotalDisplayRecords'] = $this->total ;
            $this->result['sEcho']                = $this->sEcho ;
            $this->result['aaData']               = array() ;

            if( count($this->users) == 0 ) {
                return ;
            }

            $count = 0 ;
            foreach ($this->users as $aRow) {
                $row = array() ;
                // first column
                $row[] = '<input type="checkbox" name="id[]" value="' . $aRow['pk_i_id'] . '" /></div>' ;
                // second column
                $second  = $aRow['s_email'] . '<br/>' ;
                $second .= '<div class="datatable_wrapper"><div class="datatables_quick_edit" ' ;
                $second .= ' style="position:absolute; display:none ;">' ;
                if( $aRow['b_active'] == 1 ) {
                    $second .= '<a href="' . osc_admin_base_url(true) . '?page=users&action=deactivate&amp;id[]=' . $aRow['pk_i_id'] .'">' . __('Deactivate') . '</a>' ;
                } else {
                    $second .= '<a href="' . osc_admin_base_url(true) . '?page=users&action=activate&amp;id[]=' . $aRow['pk_i_id'] .'">' . __('Activate') . '</a>' ;
                }
                if( $aRow['b_enabled'] == 1 ) {
                    $second .= ' &middot; <a href="' . osc_admin_base_url(true) . '?page=users&action=disable&amp;id[]=' . $aRow['pk_i_id'] . '">' . __('Block') . '</a>' ;
                } else {
                    $second .= ' &middot; <a href="' . osc_admin_base_url(true) . '?page=users&action=enable&amp;id[]=' . $aRow['pk_i_id'] . '">' . __('Unblock') . '</a>' ;
                }
                if( osc_user_validation_enabled() && ( $aRow['b_active'] == 0 ) ) {
                    $second .= ' &middot; <a href="' . osc_admin_base_url(true) . '?page=users&action=resend_activation&amp;id[]=' . $aRow['pk_i_id'] . '">' . __('Re-send activation email') . '</a>' ;
                }

                $second .= ' &middot; <a href="' . osc_admin_base_url(true) . '?page=users&action=edit&amp;id=' . $aRow['pk_i_id'] . '">' . __('Edit') . '</a>' ;
                $var     = 'onclick="javascript:return confirm(\'' . osc_esc_js( __('This action can not be undone. Are you sure you want to continue?') ) . '\')"' ;
                $second .= ' &middot; <a ' . $var . ' href="' . osc_admin_base_url(true) . '?page=users&action=delete&amp;id[]=' . $aRow['pk_i_id'] . '">' . __('Delete') . '</a>' ;
                $second .= '</div></div>' ;
                $row[] = $second ;
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
        private function dumpToDatatables()
        {
            $this->dumpResult() ;
        }
     }

     /* file end: ./oc-admin/ajax/users_processing.php */
?>