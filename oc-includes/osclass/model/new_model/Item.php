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
     * Item DAO
     */
    class Item extends DAO
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
            parent::__construct();
            $this->set_table_name('t_item') ;
            $this->set_primary_key('pk_i_id') ;
            $array_fields = array(
                'fk_i_user_id',
                'fk_i_category_id',
                'dt_pub_date',
                'dt_mod_date',
                'f_price',
                'i_price',
                'fk_c_currency_code',
                's_contact_name',
                's_contact_email',
                'b_premium',
                'b_enabled',
                'b_active',
                'b_spam',
                's_secret',
                'b_show_email'
            );
            $this->set_fields($array_fields) ;
        }
        
        public function mostViewed($limit = 10) 
        {
            $this->dao->select() ;
            $this->dao->from($this->getTableName().' i, '.DB_TABLE_PREFIX.'t_item_location l, '.DB_TABLE_PREFIX.'t_item_stats s') ;
            $this->dao->where('l.fk_i_item_id = i.pk_i_id AND s.fk_i_item_id = i.pk_i_id') ;
            $this->dao->groupBy('s.fk_i_item_id') ;
            $this->dao->orderBy('i_num_views', 'DESC') ;
            $this->dao->limit($limit) ;
            
            $result = $this->dao->get() ;
            $items  = $result->result() ;
            
            return $this->extendData($items);
        }
        

        public function findByPrimaryKey($id)
        {
            $this->dao->select('l.*, i.*, SUM(s.i_num_views) AS i_num_views') ;
            $this->dao->from($this->getTableName(),' i') ;
            $this->dao->join(DB_TABLE_PREFIX.'t_item_location', 'l.fk_i_item_id = i.pk_i_id ', 'LEFT') ;
            $this->dao->join(DB_TABLE_PREFIX.'t_item_stats', 'i.pk_i_id = s.fk_i_item_id', 'LEFT') ;
            $this->dao->where('i.pk_i_id', $id) ;
            $this->dao->groupBy('s.fk_i_item_id') ;
            $result = $this->dao->get() ;
            $item   = $result->result() ; 

            if(count($item) == 0) {
                return $this->extendDataSingle($item[0]);
            } else {
                return array();
            }
        }
        
        public function listWhere()
        {
            $argv = func_get_args();
            $sql = null;
            switch (func_num_args ()) {
                case 0: return array();
                    break;
                case 1: $sql = $argv[0];
                    break;
                default:
                    $args = func_get_args();
                    $format = array_shift($args);
                    $sql = vsprintf($format, $args);
                    break;
            }
            
            $this->dao->select('l.*, i.*') ;
            $this->dao->from($this->getTableName().' i, '.DB_TABLE_PREFIX.'t_item_location l') ;
            $this->dao->where('l.fk_i_item_id = i.pk_i_id') ;
            $this->dao->where($sql) ;
            $result = $this->dao->get() ;
            $items  = $result->result() ;
            
            return $this->extendData($items);
        }
        
        public function findResourcesByID($id)
        {
            $this->dao->select() ;
            $this->dao->from(DB_TABLE_PREFIX.'t_item_resource') ;
            $this->dao->where('fk_i_item_id', $id) ;
            $result = $this->dao->get() ;
            
            return $result->result();
        }

        public function findLocationByID($id)
        {
            $this->dao->select() ;
            $this->dao->from(DB_TABLE_PREFIX.'t_item_location') ;
            $this->dao->where('fk_i_item_id', $id) ;
            $result = $this->dao->get() ;
            
            return $result->result();
        }

        public function findByCategoryID($catId)
        {
            return $this->listWhere('fk_i_category_id = %d', $catId);
        }

        public function found_rows()
        {
            $sql = "SELECT FOUND_ROWS() as total" ;
            $result = $this->conn->query($sql) ;
            $total_ads = $result->row() ;
            return $total_ads['total'] ;
        }
        
        public function total_items($category = null, $active = null)
        {
            $this->dao->select('count(*) as total') ;
            $this->dao->from($this->getTableName().' i') ;
            $this->dao->join(DB_TABLE_PREFIX.'t_category c', 'c.pk_i_id = i.fk_i_category_id') ;
            
            $conditions = '';
            if (!is_null($active)) {
                if (($active == 'ACTIVE') ||  ($active == 'INACTIVE') ||  ($active == 'SPAM')) {
                    $condition = "e_status = '$active'";
                    $this->dao->where($condition) ;
                }
            }

            $result = $this->dao->get() ;
            $total_ads = $result->result() ;
            return $total_ads['total'];
        }

        // LEAVE THIS FOR COMPATIBILITIES ISSUES (ONLY SITEMAP GENERATOR)
        // BUT REMEMBER TO DELETE IN ANYTHING > 2.1.x THANKS
        public function listLatest($limit = 10)
        {
            return $this->listWhere(" b_active = 1 AND b_enabled = 1 ORDER BY dt_pub_date DESC LIMIT " . $limit);
        }
        
        /**
         * Insert title, description and what given a locale.
         * 
         * @param string $id
         * @param string $locale
         * @param string $title
         * @param string $description
         * @param string $what
         * @return boolean
         */
        public function insertLocale($id, $locale, $title, $description, $what)
        {
            $title = addslashes($title);
            $description = addslashes($description);
            $what = addslashes($what);
            $array_set = array(
                'fk_i_item_id'      => $id,
                'fk_c_locale_code'  => $locale,
                's_title'           => $title,
                's_description'     => $description,
                's_what'            => $what
            );  
            return $this->dao->insert($this->getTableName(), $array_set) ;
        }

        public function listLatestExtended($limit = 10)
        {
            $this->dao->select() ;
            $this->dao->from($this->getTableName().', '.DB_TABLE_PREFIX.'t_item_location') ;
            $array_where = array(
                $this->getTableName().'.b_active'    => 1,
                $this->getTableName().'.b_enabled'   => 1,
                DB_TABLE_PREFIX.'t_item_location.fk_i_item_id' => $this->getTableName().'.pk_i_id'   
            );
            $this->dao->where($array_where) ;
            $this->dao->orderBy($this->getTableName().'.dt_pub_date', 'DESC') ;
            $result = $this->dao->limit($limit) ;
            return $result->result();
        }

        public function listAllWithCategories()
        {
            $this->dao->select('i.*, cd.s_name AS s_category_name') ;
            $this->dao->from($this->getTableName().' i, '.DB_TABLE_PREFIX.'t_category c, '.DB_TABLE_PREFIX.'t_category_description cd') ;
            $this->dao->where('c.pk_i_id = i.fk_i_category_id AND cd.fk_i_category_id = i.fk_i_category_id') ;
            $result = $this->dao->get() ;
            return $result->result() ;
        }

        public function search($pattern)
        {
            return $this->listWhere("s_title LIKE '%%%s%%' OR s_description LIKE '%%%1\$s%%'", $pattern);
        }
        
        public function findByUserID($userId, $start = 0, $end = null)
        {
            $this->dao->select('l.*, i.*') ;
            $this->dao->from($this->getTableName().' i, '.DB_TABLE_PREFIX.'t_item_location l') ;
            $array_where = array(
                'l.fk_i_item_id' => 'i.pk_i_id',
                'i.fk_i_user_id' => $userId
            );
            $this->dao->where($array_where) ;
            $this->dao->orderBy('i.pk_i_id', 'DESC') ;
            if($end!=null) {
                $this->dao->limit($start, $end) ;
            }
            
            $result = $this->dao->get() ;
            $items  = $result->result() ;
            
            return $this->extendData($items) ;
        }

        public function countByUserID($userId)
        {
            $this->dao->select('count(i.pk_i_id) as ') ;
            $this->dao->from($this->getTableName().' i') ;
            $this->dao->where('i.fk_i_user_id', $userId) ;
            $this->dao->orderBy('i.pk_i_id', 'DESC') ;
            
            $result = $this->dao->get() ;
            $total_ads = $result->result() ;
            return $total_ads['total'];
        }
        
        public function findByUserIDEnabled($userId, $start = 0, $end = null)
        {
            $this->dao->select('l.*, i.*') ;
            $this->dao->from($this->getTableName().' i, '.DB_TABLE_PREFIX.'t_item_location l') ;
            $array_where = array(
                'l.fk_i_item_id'    => 'i.pk_i_id',
                'i.b_enabled'       => 1,
                'i.fk_i_user_id' => $userId
            );
            $this->dao->where($array_where) ;
            $this->dao->oderBy('i.pk_i_id', 'DESC') ;
            if($end!=null) {
                $this->dao->limit($start, $end) ;
            }
            $result = $this->dao->get() ;
            $items  = $result->result() ;
            return $this->extendData($items);
        }

        public function countByUserIDEnabled($userId)
        {
            $this->dao->select('count(i.pk_i_id) as total') ;
            $this->dao->from($this->getTableName.' i') ;
            $array_where = array(
                'i.b_enabled'     => 1,
                'i.fk_i_iser_id'  => $userId
            );
            $this->dao->where($array_where) ;
            $this->dao->orderBy('i.pk_i_id', 'DESC') ;
            
            $result = $this->dao->get() ;
            $items  = $result->row() ;
            return $items['total'];
        }
        
        public function listLocations($scope)
        {
            $availabe_scopes = array('country', 'region', 'city');
            $fields = array('country' => 's_country',
                            'region'  => 's_region',
                            'city'    => 's_city');
            $stringFields = array('country' => 's_country',
                                  'region'  => 's_region',
                                  'city'    => 's_city');

            if(!in_array($scope, $availabe_scopes)) {
                return array();
            }

            $this->dao->select('*, count(*) as total') ;
            $this->dao->from(DB_TABLE_PREFIX.'t_item_location') ;
            $this->dao->where("$fields[$scope] IS NOT NULL") ;
            $this->dao->groupBy($fields[$scope]) ;
            $this->dao->orderBy($stringFields[$scope]) ;

            $results = $this->dao->get() ;
            $results = $results->result() ;
            return $results;
        }
        
        public function clearStat($id, $stat)
        {
            switch($stat) {
                case 'spam':
                    $array_set  = array('i_num_spam' => 0);
                    break;
                case 'duplicated':
                    $array_set  = array('i_num_repeated' => 0);
                    break;
                case 'bad':
                    $array_set  = array('i_num_bad_classified' => 0);
                    break;
                case 'offensive':
                    $array_set  = array('i_num_offensive' => 0);
                    break;
                case 'expired':
                    $array_set  = array('i_num_expired' => 0);
                    break;
                default:
                    break;
            }
            
            $array_conditions = array('fk_i_item_id' => $id);
            
            return $this->update(DB_TABLE_PREFIX.'t_item_stats', $array_set, $array_conditions);
        }
        
        public function updateLocaleForce($id, $locale, $title, $text)
        {
            $array_replace = array(
                's_title'           => $title,
                's_description'     => $tex,
                'fk_c_locale_code'  => $locale,
                'fk_i_item_id'      => $id,
                's_what'            => $title . " " . $text
            );
            return $this->dao->replace(DB_TABLE_PREFIX.'t_item_description', $array_replace) ;
        }        
        
        public function meta_fields($id)
        {
            $this->dao->select('im.s_value as s_value,mf.pk_i_id as pk_i_id, mf.s_name as s_name, mf.e_type as e_type') ;
            $this->dao->from($this->getTableName().' i, '.DB_TABLE_PREFIX.'t_item_meta im, '.DB_TABLE_PREFIX.'t_meta_categories mc, '.DB_TABLE_PREFIX.'t_meta_fields mf') ;
            $array_where = array(
                'im.fk_i_item_id'       => $id,
                'mf.pk_i_id'            => 'im.fk_i_field_id',
                'i.pk_i_id'             => $id,
                'mf.pk_i_id'            => 'mc.fk_i_field_id' ,
                'mc.fk_i_category_id'   => 'i.fk_i_category_id'
            );
            $this->dao->where($array_where) ;
            $result = $this->dao->get() ;
            return $result->result() ;
        }
        
        // TODO
        public function deleteByPrimaryKey($id)
        {
//            osc_run_hook('delete_item', $id);
//            $item = $this->findByPrimaryKey($id);
//            if($item['b_active']==1) {
//                CategoryStats::newInstance()->decreaseNumItems($item['fk_i_category_id']);
//            }
//            
//            $this->conn->osc_dbExec('DELETE FROM %st_item_description WHERE fk_i_item_id = %d', DB_TABLE_PREFIX, $id);
//            $this->conn->osc_dbExec('DELETE FROM %st_item_comment WHERE fk_i_item_id = %d', DB_TABLE_PREFIX, $id);
//            $this->conn->osc_dbExec('DELETE FROM %st_item_resource WHERE fk_i_item_id = %d', DB_TABLE_PREFIX, $id);
//            $this->conn->osc_dbExec('DELETE FROM %st_item_location WHERE fk_i_item_id = %d', DB_TABLE_PREFIX, $id);
//            $this->conn->osc_dbExec('DELETE FROM %st_item_stats WHERE fk_i_item_id = %d', DB_TABLE_PREFIX, $id);
//            $this->conn->osc_dbExec('DELETE FROM %st_item_meta WHERE fk_i_item_id = %d', DB_TABLE_PREFIX, $id);
//            return $this->conn->osc_dbExec('DELETE FROM %st_item WHERE pk_i_id = %d', DB_TABLE_PREFIX, $id);
        }
        
        public function extendDataSingle($item)
        {
            
        }
        
        public function extendCategoryName($items)
        {
            
        }
        
        public function extendData($items)
        {
            
        }
        
    }
?>