<?php if ( !defined('ABS_PATH') ) exit('ABS_PATH is not loaded. Direct access is not allowed.');

/*
 * Copyright 2014 Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

    /**
     * Model database for ItemComment table
     *
     * @package Osclass
     * @subpackage Model
     * @since unknown
     */
    class ItemComment extends DAO
    {
        /**
         * It references to self object: ItemComment.
         * It is used as a singleton
         *
         * @access private
         * @since unknown
         * @var Item
         */
        private static $instance;

        /**
         * It creates a new ItemComment object class ir if it has been created
         * before, it return the previous object
         *
         * @access public
         * @since unknown
         * @return ItemComment
         */
        public static function newInstance()
        {
            if( !self::$instance instanceof self ) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        /**
         * Set data related to t_item_comment table
         */
        function __construct()
        {
            parent::__construct();
            $this->setTableName('t_item_comment');
            $this->setPrimaryKey('pk_i_id');
            $array_fields = array(
                'pk_i_id',
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
            $this->setFields($array_fields);
        }

        /**
         * Searches for comments information, given an item id.
         *
         * @access public
         * @since unknown
         * @param integer $id
         * @return array
         */
        function findByItemIDAll($id)
        {
            $this->dao->select();
            $this->dao->from($this->getTableName());
            $this->dao->where('fk_i_item_id', $id);
            $result = $this->dao->get();

            if($result == false) {
                return array();
            }

            return $result->result();
        }

        /**
         * Searches for comments information, given an item id, page and comments per page.
         *
         * @access public
         * @since unknown
         * @param integer $id
         * @param integer $page
         * @param integer $comments_per_page
         * @return array
         */
        function findByItemID($id, $page = null, $commentsPerPage = null)
        {
            $result = array();
            if( $page == null ) { $page = osc_item_comments_page(); }
            if( $page == '' ) {
                $page = 0;
            } else if($page > 0) {
                $page = $page;
            }

            if( $commentsPerPage == null ) { $commentsPerPage = osc_comments_per_page(); }

            $this->dao->select();
            $this->dao->from($this->getTableName());
            $conditions = array('fk_i_item_id'  => $id,
                                'b_active'      => 1,
                                'b_enabled'     => 1);
            $this->dao->where($conditions);

            if( $page !== 'all' && $commentsPerPage > 0 ) {
                $this->dao->limit(($page*$commentsPerPage), $commentsPerPage);
            }

            $result = $this->dao->get();

            if($result == false) {
                return array();
            }

            return $result->result();
        }

        /**
         * Return total of comments, given an item id. (active & enabled)
         *
         * @access public
         * @since unknown
         * @deprecated since 2.3
         * @see ItemComment::totalComments
         * @param integer $id
         * @return integer
         */
        function total_comments($id)
        {
            return $this->totalComments($id);
        }

        /**
         * Return total of comments, given an item id. (active & enabled)
         *
         * @access public
         * @since 2.3
         * @param integer $id
         * @return integer
         */
        function totalComments($id)
        {
            $this->dao->select('count(pk_i_id) as total');
            $this->dao->from($this->getTableName());
            $conditions = array('fk_i_item_id'  => $id,
                                'b_active'      => 1,
                                'b_enabled'     => 1);
            $this->dao->where($conditions);
            $this->dao->groupBy('fk_i_item_id');
            $result = $this->dao->get();

            if( $result == false ) {
                return false;
            } else if($result->numRows() === 0) {
                return 0;
            } else {
                $total = $result->row();
                return $total['total'];
            }
        }

        /**
         * Searches for comments information, given an user id.
         *
         * @access public
         * @since unknown
         * @param integer $id
         * @return array
         */
        function findByAuthorID($id)
        {
            $this->dao->select();
            $this->dao->from($this->getTableName());
            $conditions = array('fk_i_user_id'  => $id,
                                'b_active'      => 1,
                                'b_enabled'     => 1);
            $this->dao->where($conditions);
            $result = $this->dao->get();

            if($result == false) {
                return array();
            }

            return $result->result();
        }

        /**
         * Searches for comments information, given an user id.
         *
         * @access public
         * @since unknown
         * @param integer $itemId
         * @return array
         */
        function getAllComments($itemId = null)
        {
            $this->dao->select('c.*');
            $this->dao->from($this->getTableName().' c');
            $this->dao->from(DB_TABLE_PREFIX.'t_item i');

            $conditions = array();
            if(is_null($itemId)) {
                $conditions = 'c.fk_i_item_id = i.pk_i_id';
            } else {
                $conditions = array(
                    'i.pk_i_id'      => $itemId,
                    'c.fk_i_item_id' => $itemId
                );
            }

            $this->dao->where($conditions);
            $this->dao->orderBy('c.dt_pub_date','DESC');
            $aux = $this->dao->get();
            if($aux == false) {
                return array();
            }
            $comments = $aux->result();

            return $this->extendData($comments);
        }

        /**
         * Searches for last comments information, given a limit of comments.
         *
         * @access public
         * @since unknown
         * @param integer $num
         * @return array
         */
        function getLastComments($num)
        {
            if(!intval($num)) return false;

            $lang = osc_current_user_locale();

            $this->dao->select('c.*,c.s_title as comment_title, d.s_title');
            $this->dao->from($this->getTableName().' c');
            $this->dao->join(DB_TABLE_PREFIX.'t_item i', 'i.pk_i_id = c.fk_i_item_id');
            $this->dao->join(DB_TABLE_PREFIX.'t_item_description d', 'd.fk_i_item_id = c.fk_i_item_id');
            $this->dao->orderBy('c.pk_i_id', 'DESC');
            $this->dao->limit(0,$num);

            $result = $this->dao->get();
            if($result == false) {
                return array();
            }
            return $result->result();
        }

        /**
         * Extends an array of comments with title / description
         *
         * @access private
         * @since unknown
         * @param array $items
         * @return array
         */
        private function extendData($items)
        {
            $prefLocale = osc_current_user_locale();

            $results = array();
            foreach($items as $item) {
                $this->dao->select();
                $this->dao->from(DB_TABLE_PREFIX.'t_item_description');
                $this->dao->where('fk_i_item_id', $item['fk_i_item_id']);
                $aux = $this->dao->get();
                if($aux == false) {
                    $descriptions = array();
                } else {
                    $descriptions = $aux->result();
                }

                $item['locale'] = array();
                foreach($descriptions as $desc) {
                    $item['locale'][$desc['fk_c_locale_code']] = $desc;
                }
                if(isset($item['locale'][$prefLocale])) {
                    $item['s_title']       = $item['locale'][$prefLocale]['s_title'];
                    $item['s_description'] = $item['locale'][$prefLocale]['s_description'];
                } else {
                    $data = current($item['locale']);
                    $item['s_title']       = $data['s_title'];
                    $item['s_description'] = $data['s_description'];
                    unset($data);
                }
                $results[] = $item;
            }
            return $results;
        }

        /**
         * Return comments on command
         *
         * @access public
         * @since 2.4
         * @param int item's ID or null
         * @param int start
         * @param int limit
         * @param string order by
         * @param string order
         * @param bool $all true returns all comments, false, returns comments
         *      which not display at frontend
         * @return array
         */
        public function search($itemId = null, $start = 0, $limit = 10, $order_by = 'c.pk_i_id', $order = 'DESC', $all = true) {
            $this->dao->select('c.*');
            $this->dao->from($this->getTableName().' c');
            $this->dao->from(DB_TABLE_PREFIX.'t_item i');

            $conditions = array();
            if(is_null($itemId)) {
                $conditions = 'c.fk_i_item_id = i.pk_i_id';
            } else {
                $conditions = array(
                    'i.pk_i_id'      => $itemId,
                    'c.fk_i_item_id' => $itemId
                );
            }

            $this->dao->where($conditions);

            if(!$all) {
                $auxCond = '( c.b_enabled = 0 OR c.b_active = 0 OR c.b_spam = 1 )';
                $this->dao->where($auxCond);
            }

            $this->dao->orderBy($order_by, $order);
            $this->dao->limit($start, $limit);

            $aux = $this->dao->get();
            if($aux == false) {
                return array();
            }
            return $aux->result();
        }

        /**
         * Count the number of comments
         *
         * @param int item's ID or null
         * @return int
         */
        public function count($itemId = null) {
            $this->dao->select('COUNT(*) AS numrows');
            $this->dao->from($this->getTableName().' c');
            $this->dao->from(DB_TABLE_PREFIX.'t_item i');

            $conditions = array();
            if(is_null($itemId)) {
                $conditions = 'c.fk_i_item_id = i.pk_i_id';
            } else {
                $conditions = array(
                    'i.pk_i_id'      => $itemId,
                    'c.fk_i_item_id' => $itemId
                );
            }

            $this->dao->where($conditions);
            $aux = $this->dao->get();
            if($aux == false) {
                return array();
            }
            $row = $aux->row();
            return $row['numrows'];
        }

        public function countAll($aConditions = null )
        {
            $this->dao->select('count(*) as total');
            $this->dao->from($this->getTableName().' c');
            $this->dao->from(DB_TABLE_PREFIX.'t_item i');

            $this->dao->where('c.fk_i_item_id = i.pk_i_id');
            if(!is_null($aConditions)) {
                $this->dao->where($aConditions);
            }
            $result = $this->dao->get();

            if( $result == false ) {
                return false;
            } else if($result->numRows() === 0) {
                return 0;
            } else {
                $total = $result->row();
                return $total['total'];
            }
        }

    }
    /* file end: ./oc-includes/osclass/model/ItemComment.php */
?>