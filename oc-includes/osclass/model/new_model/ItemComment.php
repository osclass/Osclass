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
     * ItemComment DAO
     */
    class ItemComment extends DAO
    {
        /**
         *
         * @var type 
         */
        private static $instance ;

        public static function newInstance()
        {
            if( !self::$instance instanceof self ) {
                self::$instance = new self ;
            }
            return self::$instance ;
        }

        /**
         * 
         */
        function __construct()
        {
            parent::__construct() ;
            $this->set_table_name('t_item_comment') ;
            $this->set_primary_key('pk_i_id') ;
            $array_fields = array(
                'fk_i_item_id',
                'dt_pub_date',
                's_title',
                's_author_name',
                's_author_email',
                's_body',
                'b_enabled',
                'b_active',
                'b_spam',
                'fk_i_user_id'
            );
            $this->set_fields($array_fields) ;
        }
        
        /**
         * Searches for comments information, given an item id.
         * 
         * @param integer $id
         * @return array 
         */
        function findByItemIDAll($id)
        {
            $this->dao->select() ;
            $this->dao->from($this->table_name) ;
            $this->dao->where('fk_i_item_id', $id) ;
            $result = $this->dao->get() ;
            
            if( $result == false ) { 
                return false;
            } else {
                return $result->result() ;
            }
        }
        
        /**
         * Searches for comments information, given an item id, page and comments per page.
         * 
         * @param integer $id
         * @param integer $page
         * @param integer $comments_per_page
         * @return array
         */
        function findByItemID($id, $page = null, $comments_per_page = null) 
        {
            $result = array();
            if( $page == null ) { $page = osc_item_comments_page(); }
            if( $page == '' ) { $page = 0; }
            if( $comments_per_page == null ) { $comments_per_page = osc_comments_per_page(); }
            
            $this->dao->select() ;
            $this->dao->from($this->table_name) ;
            $conditions = array('fk_i_item_id'  => $id,
                                'b_active'      => 1,
                                'b_enabled'     => 1);
            $this->dao->where($conditions) ;
            
            if( ($page !== 'all') || ($comments_per_page > 0) ) {
                $this->dao->limit(($page*$comments_per_page), $comments_per_page);
            }
            
            $result = $this->dao->get() ;
            
            if( $result == false ) { 
                return false;
            } else {
                return $result->result();
            }
        }
        
        /**
         * Return total of comments, given an item id.
         * 
         * @param integer $id
         * @return integer
         */
        function total_comments($id)
        {
            $this->dao->select('count(pk_i_id) as total') ;
            $this->dao->from($this->table_name) ;
            $conditions = array('fk_i_item_id'  => $id,
                                'b_active'      => 1,
                                'b_enabled'     => 1);
            $this->dao->where($conditions) ;
            $this->dao->group_by('fk_i_item_id') ;
            $result = $this->dao->get() ;
            
            if( $result == false ) { 
                return false;
            } else {
                $total = $result->row();
                return $total['total'];
            }
        }
        
        /**
         * Searches for comments information, given an user id.
         * 
         * @param integer $id
         * @return array
         */
        function findByAuthorID($id)
        {
            $this->dao->select() ;
            $this->dao->from($this->table_name) ;
            $conditions = array('fk_i_user_id'  => $id,
                                'b_active'      => 1,
                                'b_enabled'     => 1);
            $this->dao->where($conditions) ;
            $result = $this->dao->get() ;
            
            if( $result == false ) { 
                return false;
            } else {
                return $result->result();
            }
        }
        
        /**
         * Searches for comments information, given an user id.
         *
         * @param integer $itemId
         * @return array
         */
        function getAllComments($itemId = null) 
        {
            $this->dao->select() ;
            $this->dao->from($this->table_name.' c') ;
            $this->dao->from('t_item i') ;
            
            if(is_null($itemId)) {
                $conditions = array(
                    'c.fk_i_item_id' => 'i.pk_i_id'
                );
                $this->dao->where($conditions) ;
            } else {
                $conditions = array(
                    'i.pk_i_id'      => $itemId,
                    'c.fk_i_item_id' => $itemId
                );
                $this->dao->where($conditions) ;
            }
            
            $this->dao->order_by('dt_pub_date','DESC') ;            
            $aux = $this->dao->get() ;
            $comments = $aux->result() ;
            
            return $this->extendData($comments) ;
        }
        
        /**
         * Searches for last comments information, given a limit of comments.
         *
         * @param integer $num
         * @return array
         */
        function getLastComments($num) {
            if(!intval($num)) return false;

            $lang = osc_current_user_locale() ;

            $this->dao->select('i.*, d.s_title') ;
            $this->dao->from($this->table_name.' i') ;
            $this->dao->join('t_item c', 'c.pk_i_id = i.fk_i_item_id') ;
            $this->dao->join('t_item_description d', 'd.fk_i_item_id = i.fk_i_item_id');
            $this->dao->group_by('d.fk_i_item_id');
            $this->doa->order_by('pk_i_id', 'DESC');
            $this->dao->limit(0,$num);

            $result = $this->dao->get();
            return $result->result();
        }
        
        /**
         * Extends an array of comments with title / description / what
         * 
         * @param array $items
         * @return array
         */
        function extendData($items) 
        {
            $prefLocale = osc_current_user_locale();

            $results = array();
            foreach($items as $item) {
                $this->dao->select() ;
                $this->dao->from('t_item_description') ;
                $this->dao->where('fk_i_item_id', $item['fk_i_item_id']) ;
                $aux = $this->dao->get();
                $descriptions = $aux->result();
                
                $item['locale'] = array();
                foreach($descriptions as $desc) {
                    $item['locale'][$desc['fk_c_locale_code']] = $desc;
                }
                if(isset($item['locale'][$prefLocale])) {
                    $item['s_title']        = $item['locale'][$prefLocale]['s_title'];
                    $item['s_description']  = $item['locale'][$prefLocale]['s_description'];
                    $item['s_what']         = $item['locale'][$prefLocale]['s_what'];
                } else {
                    $data = current($item['locale']);
                    $item['s_title']        = $data['s_title'];
                    $item['s_description']  = $data['s_description'];
                    $item['s_what']         = $data['s_what'];
                    unset($data);
                }
                $results[] = $item;
            }
            return $results;
        }
    }
?>