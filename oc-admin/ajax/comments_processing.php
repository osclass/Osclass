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
     
     class CommentsProcessingAjax
     {
        private $comments ;
        private $result ;
        private $toJSON ;

        private $resourceID = null ;
        private $limit ;
        private $start ;
        private $total ;
        private $total_filtered ;
        private $order_by = array() ;

        private $column_names  = array(
            0 => 'c.pk_i_id',
            1 => 'c.s_author_name',
            2 => 'c.s_body',
            3 => 'c.dt_pub_date'
        ) ;
        
        /* For Datatables */
        private $sEcho = null;
        private $_get ;

        function __construct($params)
        {
            $this->_get = $params ;
            $this->getDBParams() ;
            
            // force ORDER BY
            $this->order_by['column_name'] = $this->column_names[3] ;
            $this->order_by['type'] = 'desc' ;
            
            $this->comments       = ItemComment::newInstance()->search($this->resourceID, $this->start, $this->limit, ( $this->order_by['column_name'] ? $this->order_by['column_name'] : 'pk_i_id' ), ( $this->order_by['type'] ? $this->order_by['type'] : 'desc' ) ) ;
            $this->total          = ItemComment::newInstance()->count() ;
            if( $this->resourceID == null ) {
                $this->total_filtered = $this->total ;
            } else {
                $this->total_filtered = ItemResource::newInstance()->count( $this->resourceID ) ;
            }
        }

        function __destruct()
        {
            unset($this->_get) ;
        }

        private function getDBParams()
        {
            $p_iPage      = 1;
            if( !is_numeric(Params::getParam('iPage')) || Params::getParam('iPage') < 1 ) {
                Params::setParam('iPage', $p_iPage );
                $this->iPage = $p_iPage ;
            } else {
                $this->iPage = Params::getParam('iPage') ;
            }
            
            foreach($this->_get as $k => $v) {
                if( ( $k == 'resourceId' ) && !empty($v) ) {
                    $this->resourceID = intval($v) ;
                }
                if( $k == 'iDisplayStart' ) {
                    $this->start = intval($v) ;
                }
                if( $k == 'iDisplayLength' ) {
                    $this->limit = intval($v) ;
                }
                if( $k == 'sEcho' ) {
                    $this->sEcho = intval($v) ;
                }
            }
            
            // set start and limit using iPage param
            $start = ((int)Params::getParam('iPage')-1) * $this->_get['iDisplayLength'];
            
            $this->start = intval( $start ) ;
            $this->limit = intval( $this->_get['iDisplayLength'] ) ;
        }

        /* START - format functions */
        private function toDatatablesFormat()
        {
            $this->result['iTotalRecords']        = $this->total_filtered ;
            $this->result['iTotalDisplayRecords'] = $this->total ;
            $this->result['sEcho']                = $this->sEcho ;
            $this->result['aaData']               = array() ;

            if( count($this->comments) == 0 ) {
                return ;
            }

            $count = 0 ;
            foreach($this->comments as $comment) {
                $row = array() ;

                $options = array() ;
                $options[] = '<a href="' . osc_admin_base_url(true) . '?page=comments&amp;action=comment_edit&amp;id=' . $comment['pk_i_id'] . '" id="dt_link_edit">' . __('Edit') . '</a>' ;
                if( $comment['b_active'] ) {
                    $options[] = '<a href="' . osc_admin_base_url(true) . '?page=comments&amp;action=status&amp;id=' . $comment['pk_i_id'] . '&amp;value=INACTIVE">' . __('Deactivate') . '</a>' ;
                } else {
                    $options[] = '<a href="' . osc_admin_base_url(true) . '?page=comments&amp;action=status&amp;id=' . $comment['pk_i_id'] .'&amp;value=ACTIVE">' . __('Activate') . '</a>' ;
                }
                if( $comment['b_enabled'] ) {
                    $options[] = '<a href="' . osc_admin_base_url(true) . '?page=comments&amp;action=status&amp;id=' . $comment['pk_i_id'] . '&amp;value=DISABLE">' . __('Unblock') . '</a>' ;
                } else {
                    $options[] = '<a href="' . osc_admin_base_url(true) . '?page=comments&amp;action=status&amp;id=' . $comment['pk_i_id'] . '&amp;value=ENABLE">' . __('Block') . '</a>' ;
                }
                $options[] = '<a onclick="javascript:return confirm(\'' . osc_esc_js( __("This action can't be undone. Are you sure you want to continue?") ) . '\')" href="' . osc_admin_base_url(true) . '?page=comments&amp;action=delete&amp;id=' . $comment['pk_i_id'] .'" id="dt_link_delete">' . __('Delete') . '</a>' ;

                $row[] = '<input type="checkbox" name="id[]" value="' . $comment['pk_i_id']  . '" />' ;
                if( empty($comment['s_author_name']) ) {
                    $user = User::newInstance()->findByPrimaryKey( $comment['fk_i_user_id'] );
                    $comment['s_author_name'] = $user['s_email'];
                }
                $row[] = $comment['s_author_name'] . ' (<a target="_blank" href="' . osc_item_url_ns( $comment['fk_i_item_id'] ) . '">' . $comment['s_title'] . '</a>)<div class="datatables_quick_edit" style="display:none;">' . implode(' &middot; ', $options) . '</div>' ;
                $row[] = $comment['s_body'] ;
                $row[] = $comment['dt_pub_date'] ;


                $count++ ;
                $this->result['aaData'][] = $row ;
            }
        }

        private function toArrayFormat()
        {
            $this->result['iTotalRecords']        = $this->total_filtered ;
            $this->result['iTotalDisplayRecords'] = $this->total ;
            $this->result['iDisplayLength']       = $this->_get['iDisplayLength'];
            $this->result['aaData']               = array() ;

            if( count($this->comments) == 0 ) {
                return ;
            }

            $count = 0 ;
            foreach($this->comments as $comment) {
                $row = array() ;
                $options = array() ;
                $options_more = array() ;

                if( $comment['b_active'] ) {
                    $options_more[] = '<a href="' . osc_admin_base_url(true) . '?page=comments&amp;action=status&amp;id=' . $comment['pk_i_id'] . '&amp;value=INACTIVE">' . __('Deactivate') . '</a>' ;
                } else {
                    $options_more[] = '<a href="' . osc_admin_base_url(true) . '?page=comments&amp;action=status&amp;id=' . $comment['pk_i_id'] .'&amp;value=ACTIVE">' . __('Activate') . '</a>' ;
                }
                if( $comment['b_enabled'] ) {
                    $options_more[] = '<a href="' . osc_admin_base_url(true) . '?page=comments&amp;action=status&amp;id=' . $comment['pk_i_id'] . '&amp;value=DISABLE">' . __('Unblock') . '</a>' ;
                } else {
                    $options_more[] = '<a href="' . osc_admin_base_url(true) . '?page=comments&amp;action=status&amp;id=' . $comment['pk_i_id'] . '&amp;value=ENABLE">' . __('Block') . '</a>' ;
                }
                
                $options[] = '<a href="' . osc_admin_base_url(true) . '?page=comments&amp;action=comment_edit&amp;id=' . $comment['pk_i_id'] . '" id="dt_link_edit">' . __('Edit') . '</a>' ;
                $options[] = '<a onclick="javascript:return confirm(\'' . osc_esc_js( __("This action can't be undone. Are you sure you want to continue?") ) . '\')" href="' . osc_admin_base_url(true) . '?page=comments&amp;action=delete&amp;id=' . $comment['pk_i_id'] .'" id="dt_link_delete">' . __('Delete') . '</a>' ;

                // more actions
                $moreOptions = '<li class="show-more">'.PHP_EOL.'<a href="#" class="show-more-trigger">'. __('Show more') .'...</a>'. PHP_EOL .'<ul>'. PHP_EOL ;
                foreach( $options_more as $actual ) { 
                    $moreOptions .= '<li>'.$actual."</li>".PHP_EOL;
                }
                $moreOptions .= '</ul>'. PHP_EOL .'</li>'.PHP_EOL ;
                
                // create list of actions
                $auxOptions = '<ul>'.PHP_EOL ;
                foreach( $options as $actual ) {
                    $auxOptions .= '<li>'.$actual.'</li>'.PHP_EOL;
                }
                $auxOptions  .= $moreOptions ;
                $auxOptions  .= '</ul>'.PHP_EOL ;
                
                $actions = '<div class="actions">'.$auxOptions.'</div>'.PHP_EOL ;
                
                $row[] = '<input type="checkbox" name="id[]" value="' . $comment['pk_i_id']  . '" />' ;
                if( empty($comment['s_author_name']) ) {
                    $user = User::newInstance()->findByPrimaryKey( $comment['fk_i_user_id'] );
                    $comment['s_author_name'] = $user['s_email'];
                }
                $row[] = $comment['s_author_name'] . ' (<a target="_blank" href="' . osc_item_url( $comment['fk_i_item_id'] ) . '">' . $comment['s_title'] . '</a>)'. $actions  ;
                $row[] = $comment['s_body'] ;
                $row[] = $comment['dt_pub_date'] ;


                $count++ ;
                $this->result['aaData'][] = $row ;
            }
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

     /* file end: ./oc-admin/ajax/media_processing.php */
?>