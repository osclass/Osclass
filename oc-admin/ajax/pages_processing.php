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

     class PagesProcessing
     {
        private $result ;
        private $pages = array();

        private $limit ;
        private $start ;
        private $total ;
        private $total_filtered ;
        private $order_by = array() ;

        function __construct($params)
        {
            $this->_get = $params ;

            $p_iPage = 1;
            if( !is_numeric(Params::getParam('iPage')) || Params::getParam('iPage') < 1 ) {
                $this->_get['iPage'] = $p_iPage ;
                Params::setParam('iPage', $p_iPage);
            }

            // set start and limit using iPage param
            $start = ((int)$this->_get['iPage']-1) * $this->_get['iDisplayLength'];

            $this->start = intval( $start ) ;
            $this->limit = intval( $this->_get['iDisplayLength'] ) ;
            
            $this->pages = Page::newInstance()->listAll(0, null, $this->start, $this->limit) ;

            $this->total = Page::newInstance()->count(0) ;
            
            $this->total_filtered = $this->total ;
        }

        function __destruct()
        {
            unset($this->_get) ;
        }
        
        /* START - format functions */
        private function toArrayFormat()
        {
            $this->result['iTotalRecords']        = $this->total ;
            $this->result['iTotalDisplayRecords'] = $this->total_filtered ;
            $this->result['iDisplayLength']       = $this->_get['iDisplayLength'];
            $this->result['aaData']               = array() ;

            if( count($this->pages) == 0 ) {
                return ;
            }

            $prefLocale = osc_current_user_locale() ;
            $count = 0 ;
            foreach($this->pages as $aRow) {
                $row     = array() ;
                $content = array() ;

                if( isset($aRow['locale'][$prefLocale]) && !empty($aRow['locale'][$prefLocale]['s_title']) ) {
                    $content = $aRow['locale'][$prefLocale] ;
                } else {
                    $content = current($aRow['locale']) ;
                }

                // -- options --
                $options   = array() ;
                View::newInstance()->_exportVariableToView('page', $aRow );
                $options[] = '<a href="' . osc_static_page_url() . '" target="_blank">' . __('View page') . '</a>' ;
                $options[] = '<a href="' . osc_admin_base_url(true) . '?page=pages&amp;action=edit&amp;id=' . $aRow['pk_i_id'] . '">' . __('Edit') . '</a>' ;
                if( !$aRow['b_indelible'] ) {
                    $options[] = '<a onclick="return delete_dialog(\'' . $aRow['pk_i_id'] . '\');" href="' . osc_admin_base_url(true) . '?page=pages&amp;action=delete&amp;id=' . $aRow['pk_i_id'] . '">' . __('Delete') . '</a>' ;
                }

                $auxOptions = '<ul>'.PHP_EOL ;
                foreach( $options as $actual ) {
                    $auxOptions .= '<li>'.$actual.'</li>'.PHP_EOL;
                }
                $actions = '<div class="actions">'.$auxOptions.'</div>'.PHP_EOL ;

                $row[] = '<input type="checkbox" name="id[]"" value="' . $aRow['pk_i_id'] . '"" />' ;
                $row[] = $aRow['s_internal_name'] . $actions ;
                $row[] = $content['s_title'] ;
                $row[] = '<div class="order-box">' . $aRow['i_order'] . ' <img class="up" onclick="order_up(' . $aRow['pk_i_id'] . ');" src="' . osc_current_admin_theme_url('images/arrow_up.png') . '" alt="' . __('Up') . '" title="' . __('Up') . '" />  <img class="down" onclick="order_down(' . $aRow['pk_i_id'] . ');" src="' . osc_current_admin_theme_url('images/arrow_down.png') .'" alt="' . __('Down') . '" title="' . __('Down') . '" /></div>' ;

                $this->result['aaData'][] = $row ;
            }
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

     /* file end: ./oc-admin/ajax/pages_processing.php */
?>