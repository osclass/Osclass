<?php if ( !defined('ABS_PATH') ) exit('ABS_PATH is not loaded. Direct access is not allowed.') ;

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
     * 
     */
    class Search extends DAO
    {
        /**
         *
         * @var type 
         */
        private $conditions;
        private $tables;
        private $sql;
        private $order_column;
        private $order_direction;
        private $limit_init;
        private $results_per_page;
        private $cities;
        private $city_areas;
        private $regions;
        private $countries;
        private $categories;
        private $search_fields;
        private $total_results;
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
        function __construct($expired = false) {
            $this->city_areas = array();
            $this->cities = array();
            $this->regions = array();
            $this->countries = array();
            $this->categories = array();
            $this->conditions = array();
            $this->search_fields = array();
            $this->tables[] = sprintf('%st_item_description as d, %st_category_description as cd', DB_TABLE_PREFIX, DB_TABLE_PREFIX);
            $this->order();
            $this->limit();
            $this->results_per_page = 10;
            if(!$expired) {
                $this->addTable(sprintf('%st_category', DB_TABLE_PREFIX));
                $this->addConditions(sprintf("%st_item.b_active = 1 ", DB_TABLE_PREFIX));
                $this->addConditions(sprintf("%st_item.b_enabled = 1 ", DB_TABLE_PREFIX));
                $this->addConditions(sprintf("%st_item.b_spam = 0", DB_TABLE_PREFIX));
                $this->addConditions(sprintf(" (%st_item.b_premium = 1 || %st_category.i_expiration_days = 0 ||TIMESTAMPDIFF(DAY,%st_item.dt_pub_date,'%s') < %st_category.i_expiration_days) ", DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX, date('Y-m-d H:i:s'), DB_TABLE_PREFIX));
                $this->addConditions(sprintf("%st_category.b_enabled = 1", DB_TABLE_PREFIX));
                $this->addConditions(sprintf("%st_category.pk_i_id = %st_item.fk_i_category_id", DB_TABLE_PREFIX, DB_TABLE_PREFIX));

            }
            $this->total_results = null;
            parent::__construct() ;
            $this->setTableName('t_item') ;
            $this->setFields( array('pk_i_id') ) ;
        }

        public static function getAllowedColumnsForSorting() {
            return( array('i_price', 'dt_pub_date') ) ;
        }
        
        public static function getAllowedTypesForSorting() {
            return ( array (0 => 'asc', 1 => 'desc') ) ;
        }
        

        // juanramon: little hack to get alerts work in search layout
        public function reconnect()
        {
         //   $this->conn = getConnection();
        }

        /**
         * Add conditions to the search
         *
         * @access public
         * @since unknown
         * @param mixed $conditions
         */
        public function addConditions($conditions) {
            if(is_array($conditions)) {
                foreach($conditions as $condition) {
                    $condition = trim($condition);
                    if($condition!='') {
                        if(!in_array($condition, $this->conditions)) {
                            $this->conditions[] = $condition;
                        }
                    }
                }
            } else {
                $conditions = trim($conditions);
                if($conditions!='') {
                    if(!in_array($conditions, $this->conditions)) {
                        $this->conditions[] = $conditions;
                    }
                }
            }
        }

        /**
         * Add new fields to the search
         *
         * @access public
         * @since unknown
         * @param mixed $fields
         */
        public function addField($fields) {
            if(is_array($fields)) {
                foreach($fields as $field) {
                    $field = trim($field);
                    if($field!='') {
                        if(!in_array($field, $this->fields)) {
                            $this->search_fields[] = $field;
                        }
                    }
                }
            }
            else {
                $fields = trim($fields);
                if($fields!='') {
                    if(!in_array($fields, $this->fields)) {
                        
                        $this->search_fields[] = $fields;
                    }
                }
            }
        }

        /**
         * Add extra table to the search
         *
         * @access public
         * @since unknown
         * @param mixed $tables
         */
        public function addTable($tables) {

            if(is_array($tables)) {
                foreach($tables as $table) {
                    $table = trim($table);
                    if($table!='') {
                        if(!in_array($table, $this->tables)) {
                            $this->tables[] = $table;
                        }
                    }
                }
            } else {
                $tables = trim($tables);
                if($tables!='') {
                    if(!in_array($tables, $this->tables)) {
                        $this->tables[] = $tables;
                    }
                }
            }
        }

        /**
         * Establish the order of the search
         *
         * @access public
         * @since unknown
         * @param string $o_c column
         * @param string $o_d direction
         * @param string $table
         */
        public function order($o_c = 'dt_pub_date', $o_d = 'DESC',$table = NULL) {
            if($table == '') {
                $this->order_column = $o_c;
            } else if($table != ''){
                if( $table == '%st_user' ) {
                    $this->order_column = sprintf("ISNULL($table.$o_c), $table.$o_c", DB_TABLE_PREFIX, DB_TABLE_PREFIX);
                } else {
                    $this->order_column = sprintf("$table.$o_c", DB_TABLE_PREFIX);
                }
            } else {
//                $this->order_column = sprintf("query.$o_c", DB_TABLE_PREFIX);
                $this->order_column = sprintf("$o_c", DB_TABLE_PREFIX);
            }
            $this->order_direction = $o_d;
        }

        /**
         * Limit the results of the search
         *
         * @access public
         * @since unknown
         * @param int $l_i
         * @param int $t_p_p results per page
         */
        public function limit($l_i = 0, $r_p_p = 10) {
            $this->limit_init = $l_i;
            $this->results_per_page = $r_p_p;
        }

        /**
         * Limit the results of the search
         *
         * @access public
         * @since unknown
         * @param int $t_p_p results per page
         */
        public function set_rpp($r_p_p) {
            $this->results_per_page = $r_p_p;
        }

        /**
         * Select the page of the search
         *
         * @access public
         * @since unknown
         * @param int $p page
         * @param int $t_p_p results per page
         */
        public function page($p = 0, $r_p_p = null) {
            if($r_p_p!=null) { $this->results_per_page = $r_p_p; };
            $this->limit_init = $this->results_per_page*$p;
            $this->results_per_page = $this->results_per_page;
        }

        /**
         * Add city areas to the search
         *
         * @access public
         * @since unknown
         * @param mixed $city_area
         */
        public function addCityArea($city_area = array()) {
            if(is_array($city_area)) {
                foreach($city_area as $c) {
                    $c = trim($c);
                    if($c!='') {
                        if(is_numeric($c)) {
                            $this->city_areas[] = sprintf("%st_item_location.fk_i_city_area_id = %d ", DB_TABLE_PREFIX, $c);
                        } else {
                            $this->city_areas[] = sprintf("%st_item_location.s_city_area LIKE '%%%s%%' ", DB_TABLE_PREFIX, $c);
                        }
                    }
                }
            } else {
                $city_area = trim($city_area);
                if($city_area!="") {
                    if(is_numeric($city_area)) {
                        $this->city_areas[] = sprintf("%st_item_location.fk_i_city_area_id = %d ", DB_TABLE_PREFIX, $city_area);
                    } else {
                        $this->city_areas[] = sprintf("%st_item_location.s_city_area LIKE '%%%s%%' ", DB_TABLE_PREFIX, $city_area);
                    }
                }
            }
        }

        /**
         * Add cities to the search
         *
         * @access public
         * @since unknown
         * @param mixed $city
         */
        public function addCity($city = array()) {
            if(is_array($city)) {
                foreach($city as $c) {
                    $c = trim($c);
                    if($c!='') {
                        if(is_numeric($c)) {
                            $this->cities[] = sprintf("%st_item_location.fk_i_city_id = %d ", DB_TABLE_PREFIX, $c);
                        } else {
                            $this->cities[] = sprintf("%st_item_location.s_city LIKE '%%%s%%' ", DB_TABLE_PREFIX, $c);
                        }
                    }
                }
            } else {
                $city = trim($city);
                if($city!="") {
                    if(is_numeric($city)) {
                        $this->cities[] = sprintf("%st_item_location.fk_i_city_id = %d ", DB_TABLE_PREFIX, $city);
                    } else {
                        $this->cities[] = sprintf("%st_item_location.s_city LIKE '%%%s%%' ", DB_TABLE_PREFIX, $city);
                    }
                }
            }
        }

        /**
         * Add regions to the search
         *
         * @access public
         * @since unknown
         * @param mixed $region
         */
        public function addRegion($region = array()) {
            if(is_array($region)) {
                foreach($region as $r) {
                    $r = trim($r);
                    if($r!='') {
                        if(is_numeric($r)) {
                            $this->regions[] = sprintf("%st_item_location.fk_i_region_id = %d ", DB_TABLE_PREFIX, $r);
                        } else {
                            $this->regions[] = sprintf("%st_item_location.s_region LIKE '%%%s%%' ", DB_TABLE_PREFIX, $r);
                        }
                    }
                }
            } else {
                $region = trim($region);
                if($region!="") {
                    if(is_numeric($region)) {
                        $this->regions[] = sprintf("%st_item_location.fk_i_region_id = %d ", DB_TABLE_PREFIX, $region);
                    } else {
                        $this->regions[] = sprintf("%st_item_location.s_region LIKE '%%%s%%' ", DB_TABLE_PREFIX, $region);
                    }
                }
            }
        }

        /**
         * Add countries to the search
         *
         * @access public
         * @since unknown
         * @param mixed $country
         */
        public function addCountry($country = array()) {
            if(is_array($country)) {
                foreach($country as $c) {
                    $c = trim($c);
                    if($c!='') {
                        if(strlen($c)==2) {
                            $this->countries[] = sprintf("%st_item_location.fk_c_country_code = '%s' ", DB_TABLE_PREFIX, strtolower($c));
                        } else {
                            $this->countries[] = sprintf("%st_item_location.s_country LIKE '%%%s%%' ", DB_TABLE_PREFIX, $c);
                        }
                    }
                }
            } else {
                $country = trim($country);
                if($country!="") {
                    if(strlen($country)==2) {
                        $this->countries[] = sprintf("%st_item_location.fk_c_country_code = '%s' ", DB_TABLE_PREFIX, strtolower($country));
                    } else {
                        $this->countries[] = sprintf("%st_item_location.s_country LIKE '%%%s%%' ", DB_TABLE_PREFIX, $country);
                    }
                }
            }
        }

        /**
         * Establish price range
         *
         * @access public
         * @since unknown
         * @param int $price_min
         * @param int $price_max
         */
        public function priceRange( $price_min = 0, $price_max = 0) {
            $price_min = 1000000*$price_min;
            $price_max = 1000000*$price_max;
            if(is_numeric($price_min) && $price_min!=0) {
                $this->addConditions(sprintf("i_price >= %0.0f", $price_min));
            }
            if(is_numeric($price_max) && $price_max>0) {
                $this->addConditions(sprintf("i_price <= %0.0f", $price_max));
            }
        }

        /**
         * Establish max price
         *
         * @access public
         * @since unknown
         * @param int $price
         */
        public function priceMax($price) {
            $this->priceRange(null, $price);
        }

        /**
         * Establish min price
         *
         * @access public
         * @since unknown
         * @param int $price
         */
        public function priceMin($price) {
            $this->priceRange($price, null);
        }

        /**
         * Filter by ad with picture or not
         *
         * @access public
         * @since unknown
         * @param bool $pic
         */
        public function withPicture($pic = false) {
            if($pic) {
                $this->addTable(sprintf('%st_item_resource', DB_TABLE_PREFIX));
                $this->addConditions(sprintf("%st_item_resource.s_content_type LIKE '%%image%%' AND %st_item.pk_i_id = %st_item_resource.fk_i_item_id", DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX));
            } else {

            }
        }

        /**
         * Return ads from specified users
         *
         * @access public
         * @since unknown
         * @param mixed $id
         */
        public function fromUser($id = NULL) {
            if(is_array($id)) {
                $ids = array();
                foreach($id as $_id) {
                    $ids[] = sprintf("%st_item.fk_i_user_id = %d ", DB_TABLE_PREFIX, $_id);
                }
                $this->addConditions(" ( ".implode(" || ", $ids)." ) ");
            } else {
                $this->addConditions(sprintf("%st_item.fk_i_user_id = %d ", DB_TABLE_PREFIX, $id));
            }
        }

        /**
         * Clear the categories
         *
         * @access private
         * @since unknown
         * @param array $branches
         */
        private function pruneBranches($branches = null) {
            if($branches!=null) {
                foreach($branches as $branch) {
                    if(!in_array($branch['pk_i_id'], $this->categories)) {
                        $this->categories[] = sprintf("%st_item.fk_i_category_id = %d ", DB_TABLE_PREFIX, $branch['pk_i_id']);
                        if(isset($branch['categories'])) {
                            $list = $this->pruneBranches($branch['categories']);
                        }
                    }
                }
            }
        }

        /**
         * Add categories to the search
         *
         * @access public
         * @since unknown
         * @param mixed $category
         */
        public function addCategory($category = null)
        {
            if($category == null) return '' ;

            if(!is_numeric($category)) {
                $category = preg_replace('|/$|','',$category);
                $aCategory = explode('/', $category) ;
                $category = Category::newInstance()->findBySlug($aCategory[count($aCategory)-1]) ;
                $category = $category['pk_i_id'] ;
            }
            $tree = Category::newInstance()->toSubTree($category) ;
            if(!in_array($category, $this->categories)) {
                $this->categories[] = sprintf("%st_item.fk_i_category_id = %d ", DB_TABLE_PREFIX, $category) ;
            }
            $this->pruneBranches($tree) ;
        }
        

        /**
         * Make the SQL for the search with all the conditions and filters specified
         *
         * @access public
         * @since unknown
         * @param bool $count
         */
        public function makeSQL($count = false) {

            if(count($this->city_areas)>0) {
                $this->addConditions("( ".implode(' || ', $this->city_areas)." )");
                $this->addConditions(sprintf(" %st_item.pk_i_id  = %st_item_location.fk_i_item_id ", DB_TABLE_PREFIX, DB_TABLE_PREFIX));
            }

            if(count($this->cities)>0) {
                $this->addConditions("( ".implode(' || ', $this->cities)." )");
                $this->addConditions(sprintf(" %st_item.pk_i_id  = %st_item_location.fk_i_item_id ", DB_TABLE_PREFIX, DB_TABLE_PREFIX));
            }

            if(count($this->regions)>0) {
                $this->addConditions("( ".implode(' || ', $this->regions)." )");
                $this->addConditions(sprintf(" %st_item.pk_i_id  = %st_item_location.fk_i_item_id ", DB_TABLE_PREFIX, DB_TABLE_PREFIX));
            }

            if(count($this->countries)>0) {
                $this->addConditions("( ".implode(' || ', $this->countries)." )");
                $this->addConditions(sprintf(" %st_item.pk_i_id  = %st_item_location.fk_i_item_id ", DB_TABLE_PREFIX, DB_TABLE_PREFIX));
            }

            if(count($this->categories)>0) {
                $this->addConditions("( ".implode(' || ', $this->categories)." )");
            }

            $conditionsSQL = implode(' AND ', $this->conditions);
            if($conditionsSQL!='') {
                $conditionsSQL = " AND ".$conditionsSQL;
            }

            $extraFields = "";
            if( count($this->search_fields) > 0 ) {
                $extraFields = ",";
                $extraFields .= implode(' ,', $this->search_fields);
            }
            

            if($count) {
                $this->sql = sprintf("SELECT  COUNT(DISTINCT %st_item.pk_i_id) as totalItems FROM %st_item, %st_item_location, %s WHERE %st_item_location.fk_i_item_id = %st_item.pk_i_id %s AND %st_item.pk_i_id = d.fk_i_item_id AND %st_item.fk_i_category_id = cd.fk_i_category_id", DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX, implode(', ', $this->tables), DB_TABLE_PREFIX, DB_TABLE_PREFIX, $conditionsSQL, DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX);
            } else {
                $this->sql = sprintf("SELECT  %st_item.*, %st_item_location.*, d.s_title, cd.s_name as s_category_name %s FROM %st_item, %st_item_location, %s WHERE %st_item_location.fk_i_item_id = %st_item.pk_i_id %s AND %st_item.pk_i_id = d.fk_i_item_id AND %st_item.fk_i_category_id = cd.fk_i_category_id GROUP BY %st_item.pk_i_id", DB_TABLE_PREFIX, DB_TABLE_PREFIX, $extraFields,DB_TABLE_PREFIX, DB_TABLE_PREFIX, implode(', ', $this->tables), DB_TABLE_PREFIX, DB_TABLE_PREFIX, $conditionsSQL, DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX);
                // hack include user data
                $this->sql = sprintf("SELECT SQL_CALC_FOUND_ROWS DISTINCT query.*, %st_user.s_name as s_user_name FROM ( %s ) as query LEFT JOIN %st_user on %st_user.pk_i_id = query.fk_i_user_id ORDER BY %s %s LIMIT %d, %d", DB_TABLE_PREFIX, $this->sql , DB_TABLE_PREFIX, DB_TABLE_PREFIX, $this->order_column, $this->order_direction, $this->limit_init, $this->results_per_page );
            }
            return $this->sql;
        }


        /**
         * Make the SQL for the location search (returns number of ads from each location)
         *
         * @access public
         * @since unknown
         * @deprecated it's not used anymore by OSClass' core
         * @param string $location
         */
        public function makeSQLLocation($location = 's_city') {
            
            $this->addTable(sprintf("%st_item_location", DB_TABLE_PREFIX));
            $condition_sql = implode(' AND ', $this->conditions);
            if($condition_sql!='') {
                $where_sql = " AND " . $condition_sql;
            } else {
                $where_sql = "";
            }

            $this->sql = sprintf("SELECT %st_item_location.s_country as country_name, %st_item_location.fk_i_city_id as city_id, %st_item_location.fk_c_country_code, %st_item_location.s_region as region_name, %st_item_location.fk_i_region_id as region_id, %st_item_location.s_city as city_name,COUNT( DISTINCT %st_item_location.fk_i_item_id) as items FROM %st_item, %s WHERE %s GROUP BY %st_item_location.%s", DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX, implode(', ', $this->tables), $where_sql, DB_TABLE_PREFIX, $location);

            return $this->sql;
        }

        /**
         * Return number of ads selected
         *
         * @access public
         * @since unknown
         */
        public function count() {
            if( is_null($this->total_results) ) {
                $this->doSearch();
            }

            return $this->total_results;
        }

        /**
         * Perform the search
         *
         * @access public
         * @since unknown
         * @param bool $extended if you want to extend ad's data
         */
        public function doSearch($extended = true) {
            $result = $this->dao->query($this->makeSQL(false));
            // get total items
            $datatmp  = $this->dao->query('SELECT FOUND_ROWS() as totalItems');
            $data = $datatmp->row();
            if(isset($data['totalItems'])) {
                $this->total_results = $data['totalItems'];
            } else {
                $this->total_results = 0;
            }

            if($result) {
                $items = $result->result();
            } else { 
                $items = array();
            }

            if($extended) {
                return Item::newInstance()->extendData($items);
            } else {
                return $items;
            }
        }

        /**
         * Return premium ads related to the search
         *
         * @access public
         * @since unknown
         * @param int $max
         */
        public function getPremiums($max = 2) {
            $this->order(sprintf('order_premium_views', DB_TABLE_PREFIX), 'ASC') ;
            $this->page(0, $max);
            $this->addField(sprintf('sum(%st_item_stats.i_num_premium_views) as total_premium_views', DB_TABLE_PREFIX));
            $this->addField(sprintf('(sum(%st_item_stats.i_num_premium_views) + sum(%st_item_stats.i_num_premium_views)*RAND()*0.7 + TIMESTAMPDIFF(DAY,%st_item.dt_pub_date,\'%s\')*0.3) as order_premium_views', DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX, date('Y-m-d H:i:s')));
            $this->addTable(sprintf('%st_item_stats', DB_TABLE_PREFIX));
            $this->addConditions(sprintf('%st_item_stats.fk_i_item_id = %st_item.pk_i_id', DB_TABLE_PREFIX, DB_TABLE_PREFIX));
            $this->addConditions(sprintf("%st_item.b_premium = 1", DB_TABLE_PREFIX));
            
            $items = $this->doSearch(false);
            
            $mStat = ItemStats::newInstance();
            foreach($items as $item) {
                $mStat->increase('i_num_premium_views', $item['pk_i_id']);
            }
            return Item::newInstance()->extendData($items);
        }
        
        /**
         * Returns number of ads from each country
         *
         * @access public
         * @since unknown
         * @param string $zero if you want to include locations with zero results
         * @param string $order
         */
        public function listCountries($zero = ">", $order = "items DESC") {

            $this->addConditions(sprintf('%st_item.b_enabled = 1', DB_TABLE_PREFIX));
            $this->addConditions(sprintf('%st_item.b_active = 1', DB_TABLE_PREFIX));
            $this->addConditions(sprintf('%st_item_location.fk_c_country_code = cc.pk_c_code', DB_TABLE_PREFIX));
            $this->addConditions(sprintf('%st_item.pk_i_id = %st_item_location.fk_i_item_id', DB_TABLE_PREFIX, DB_TABLE_PREFIX));
            $sql = sprintf("SELECT cc.pk_c_code, cc.fk_c_locale_code, cc.s_name as country_name, (".str_replace('%', '%%', $this->makeSQL(true)).") as items FROM %st_country as cc GROUP BY cc.pk_c_code HAVING items %s 0 ORDER BY %s ", DB_TABLE_PREFIX, $zero, $order);

            $result = $this->dao->query($sql);
            return $result->resultArray();
        }

        /**
         * Returns number of ads from each region
         *
         * @access public
         * @since unknown
         * @param string $country
         * @param string $zero if you want to include locations with zero results
         * @param string $order
         */
        public function listRegions($country = '%%%%', $zero = ">", $order = "items DESC") {

            $this->addConditions(sprintf('%st_item.b_enabled = 1', DB_TABLE_PREFIX));
            $this->addConditions(sprintf('%st_item.b_active = 1', DB_TABLE_PREFIX));
            $this->addConditions(sprintf('%st_item_location.fk_i_region_id = rr.pk_i_id', DB_TABLE_PREFIX));
            $this->addConditions(sprintf('%st_item.pk_i_id = %st_item_location.fk_i_item_id', DB_TABLE_PREFIX, DB_TABLE_PREFIX));
            $sql = sprintf("SELECT rr.pk_i_id as region_id, rr.s_name as region_name, cc.pk_c_code, cc.fk_c_locale_code, cc.s_name as country_name, (".str_replace('%', '%%', $this->makeSQL(true)).") as items FROM %st_region as rr, %st_country as cc WHERE rr.fk_c_country_code LIKE '%s' GROUP BY rr.s_name HAVING items %s 0 ORDER BY %s ", DB_TABLE_PREFIX, DB_TABLE_PREFIX, strtolower($country), $zero, $order);

            $result = $this->dao->query($sql);
            return $result->resultArray();

        }

        /**
         * Returns number of ads from each city
         *
         * @access public
         * @since unknown
         * @param string $region
         * @param string $zero if you want to include locations with zero results
         * @param string $order
         */
        public function listCities($region = null, $zero = ">", $order = "items DESC") {
            $this->addConditions(sprintf('%st_item.b_enabled = 1', DB_TABLE_PREFIX));
            $this->addConditions(sprintf('%st_item.b_active = 1', DB_TABLE_PREFIX));
            $region_int = (int)$region;
            if(is_numeric($region_int) && $region_int!=0) {

                $this->addConditions(sprintf('%st_item_location.fk_i_city_id = ct.pk_i_id', DB_TABLE_PREFIX));
                $this->addConditions(sprintf('%st_item.pk_i_id = %st_item_location.fk_i_item_id', DB_TABLE_PREFIX, DB_TABLE_PREFIX));
                $sql = sprintf("SELECT ct.pk_i_id as city_id, ct.s_name as city_name, rr.pk_i_id as region_id, rr.s_name as region_name, cc.pk_c_code, cc.fk_c_locale_code, cc.s_name as country_name, (".str_replace('%', '%%', $this->makeSQL(true)).") as items FROM %st_region as rr, %st_country as cc, %st_city as ct WHERE ct.fk_i_region_id = %d GROUP BY ct.s_name HAVING items %s 0 ORDER BY %s ", DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX,  $region_int, $zero, $order);

                $result = $this->dao->query($sql);
                return $result->resultArray();
            } else {

                $this->addConditions(sprintf('%st_item_location.fk_i_city_id = ct.pk_i_id', DB_TABLE_PREFIX));
                $this->addConditions(sprintf('%st_item.pk_i_id = %st_item_location.fk_i_item_id', DB_TABLE_PREFIX, DB_TABLE_PREFIX));
                $sql = sprintf("SELECT ct.pk_i_id as city_id, ct.s_name as city_name, rr.pk_i_id as region_id, rr.s_name as region_name, cc.pk_c_code, cc.fk_c_locale_code, cc.s_name as country_name, (".str_replace('%', '%%', $this->makeSQL(true)).") as items FROM %st_region as rr, %st_country as cc, %st_city as ct WHERE rr.s_name LIKE '%s' AND ct.fk_i_region_id = rr.pk_i_id GROUP BY ct.s_name HAVING items %s 0 ORDER BY %s ", DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX,  $region, $zero, $order);

                $result = $this->dao->query($sql);
                return $result->resultArray();
            }
        }

        /**
         * Returns number of ads from each city area
         *
         * @access public
         * @since unknown
         * @param string $city
         * @param string $zero if you want to include locations with zero results
         * @param string $order
         */
        public function listCityAreas($city = null, $zero = ">", $order = "items DESC") {
            $this->addConditions(sprintf('%st_item.b_enabled = 1', DB_TABLE_PREFIX));
            $this->addConditions(sprintf('%st_item.b_active = 1', DB_TABLE_PREFIX));
            $city_int = (int)$city;
            if(is_numeric($city_int) && $city_int!=0) {

                $this->addConditions(sprintf('%st_item_location.fk_i_city_area_id = cta.pk_i_id', DB_TABLE_PREFIX));
                $this->addConditions(sprintf('%st_item.pk_i_id = %st_item_location.fk_i_item_id', DB_TABLE_PREFIX, DB_TABLE_PREFIX));
                $sql = sprintf("SELECT cta.pk_i_id as city_area_id, cta.s_name as city_area_name, ct.pk_i_id as city_id, ct.s_name as city_name, rr.pk_i_id as region_id, rr.s_name as region_name, cc.pk_c_code, cc.fk_c_locale_code, cc.s_name as country_name, (".str_replace('%', '%%', $this->makeSQL(true)).") as items FROM %st_region as rr, %st_country as cc, %st_city as ct, %stcity_area WHERE cta.fk_i_city_id = %d GROUP BY cta.s_name HAVING items %s 0 ORDER BY %s ", DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX,  $city_int, $zero, $order);

                $result = $this->dao->query($sql);
                return $result->resultArray();
            } else {

                $this->addConditions(sprintf('%st_item_location.fk_i_city_area_id = cta.pk_i_id', DB_TABLE_PREFIX));
                $this->addConditions(sprintf('%st_item.pk_i_id = %st_item_location.fk_i_item_id', DB_TABLE_PREFIX, DB_TABLE_PREFIX));
                $sql = sprintf("SELECT cta.pk_i_id as city_area_id, cta.s_name as city_area_name, ct.pk_i_id as city_id, ct.s_name as city_name, rr.pk_i_id as region_id, rr.s_name as region_name, cc.pk_c_code, cc.fk_c_locale_code, cc.s_name as country_name, (".str_replace('%', '%%', $this->makeSQL(true)).") as items FROM %st_region as rr, %st_country as cc, %st_city as ct, %st_city_area as cta WHERE ct.s_name LIKE '%s' AND cta.fk_i_city_id = ct.pk_i_id GROUP BY cta.s_name HAVING items %s 0 ORDER BY %s ", DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX,  $city, $zero, $order);

                $result = $this->dao->query($sql);
                return $result->resultArray();
            }
        }
    }

    /* file end: ./oc-includes/osclass/model/Search.php */
?>