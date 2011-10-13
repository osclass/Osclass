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
            $this->setTableName('t_item') ;
            $this->setPrimaryKey('pk_i_id') ;
            $array_fields = array(
                'pk_i_id',
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
            $this->setFields($array_fields) ;
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
            $this->dao->from($this->getTableName().' i') ;
            $this->dao->join(DB_TABLE_PREFIX.'t_item_location l', 'l.fk_i_item_id = i.pk_i_id ', 'LEFT') ;
            $this->dao->join(DB_TABLE_PREFIX.'t_item_stats s', 'i.pk_i_id = s.fk_i_item_id', 'LEFT') ;
            $this->dao->where('i.pk_i_id', $id) ;
            $this->dao->groupBy('s.fk_i_item_id') ;
            $result = $this->dao->get() ;
            $item   = $result->row() ; 

            if(!is_null($item) ) {
                return $this->extendDataSingle($item);
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
            return $this->dao->insert(DB_TABLE_PREFIX.'t_item_description', $array_set) ;
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
            $this->dao->limit($limit) ;
            $result = $this->dao->get() ;
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
            $item = $this->findByPrimaryKey($id);
            if (!is_null($item)) {
                if($item['b_active']==1) {
                    CategoryStats::newInstance()->decreaseNumItems($item['fk_i_category_id']);
                }
                $this->dao->delete(DB_TABLE_PREFIX.'t_item_description', "fk_i_item_id = $id") ;
                $this->dao->delete(DB_TABLE_PREFIX.'t_item_comment' , "fk_i_item_id = $id") ;
                $this->dao->delete(DB_TABLE_PREFIX.'t_item_resource', "fk_i_item_id = $id") ;
                $this->dao->delete(DB_TABLE_PREFIX.'t_item_location', "fk_i_item_id = $id") ;
                $this->dao->delete(DB_TABLE_PREFIX.'t_item_stats'   , "fk_i_item_id = $id") ;
                $this->dao->delete(DB_TABLE_PREFIX.'t_item_meta'    , "fk_i_item_id = $id") ;
                $res = parent::deleteByPrimaryKey($id) ;
                return $res;
            } else {
                return false;
            }
        }
        
        public function extendDataSingle($item)
        {
            $prefLocale = osc_current_user_locale();

            $this->dao->select() ;
            $this->dao->from(DB_TABLE_PREFIX.'t_item_description') ;
            $this->dao->where('fk_i_item_id', $item['pk_i_id']) ;
            $result = $this->dao->get() ;
            $descriptions = $result->result() ;
            
            $item['locale'] = array();
            foreach ($descriptions as $desc) {
                if ($desc['s_title'] != "" || $desc['s_description'] != "") {
                    $item['locale'][$desc['fk_c_locale_code']] = $desc;
                }
            }
            $is_itemLanguageAvailable = (!empty($item['locale'][$prefLocale]['s_title'])
                                         && !empty($item['locale'][$prefLocale]['s_description']));
            if (isset($item['locale'][$prefLocale]) && $is_itemLanguageAvailable) {
                $item['s_title'] = $item['locale'][$prefLocale]['s_title'];
                $item['s_description'] = $item['locale'][$prefLocale]['s_description'];
            } else {
                $aCategory = Category::newInstance()->findByPrimaryKey($item['fk_i_category_id']);

                $title = sprintf(__('%s in'), $aCategory['s_name']);
                if(isset($item['s_city'])) {
                    $title .= ' ' . $item['s_city'];
                } else if(isset($item['s_region'])) {
                    $title .= ' ' .$item['s_region'];
                } else if(isset($item['s_country'])) {
                    $title .= ' ' . $item['s_country'];
                }
                $item['s_title'] = $title;
                $item['s_description'] = __('There\'s no description available in your language');
                unset($data);
            }
            return $item;
        }
        
        public function extendCategoryName($items)
        {
            if(defined('OC_ADMIN')) {
                $prefLocale = osc_current_admin_locale();
            } else {
                $prefLocale = osc_current_user_locale();
            }

            $results = array();
            foreach ($items as $item) {
                $this->dao->select('fk_c_locale_code, s_name as s_category_name') ;
                $this->dao->from(DB_TABLE_PREFIX.'t_category_description') ;
                $this->dao->where('fk_i_category_id', $item['fk_i_category_id']) ;
                $result = $this->dao->get() ;
                $descriptions = $result->result() ;
                
                foreach ($descriptions as $desc) {
                    $item['locale'][$desc['fk_c_locale_code']]['s_category_name'] = $desc['s_category_name'];
                }
                if (isset($item['locale'][$prefLocale]['s_category_name'])) {
                    $item['s_category_name'] = $item['locale'][$prefLocale]['s_category_name'];
                } else {
                    $data = current($item['locale']);
                    $item['s_category_name'] = $data['s_category_name'];
                    unset($data);
                }
                $results[] = $item;
            }
            return $results;
        }
        
        public function extendData($items)
        {
            if(defined('OC_ADMIN')) {
                $prefLocale = osc_current_admin_locale();
            } else {
                $prefLocale = osc_current_user_locale();
            }

            $results = array();
            foreach ($items as $item) {
                $this->dao->select() ;
                $this->dao->from(DB_TABLE_PREFIX.'t_item_description') ;
                $this->dao->where('fk_i_item_id', $item['pk_i_id']) ;
                
                $result = $this->dao->get() ;
                $descriptions = $result->result() ;
                
                $item['locale'] = array();
                foreach ($descriptions as $desc) {
                    if ($desc['s_title'] != "" || $desc['s_description'] != "") {
                        $item['locale'][$desc['fk_c_locale_code']] = $desc;
                    }
                }
                if (isset($item['locale'][$prefLocale])) {
                    $item['s_title'] = $item['locale'][$prefLocale]['s_title'];
                    $item['s_description'] = $item['locale'][$prefLocale]['s_description'];
                    $item['s_what'] = $item['locale'][$prefLocale]['s_what'];
                } else {
                    $data = current($item['locale']);
                    $item['s_title'] = $data['s_title'];
                    $item['s_description'] = $data['s_description'];
                    $item['s_what'] = $data['s_what'];
                    unset($data);
                }
                $results[] = $item;
            }
            return $results;
        }
        
    }
?>