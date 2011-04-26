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


class Search extends DAO
{
    private $conditions;
    private $tables;
    private $sql;
    private $order_column;
    private $order_direction;
    private $limit_init;
    private $results_per_page;
    private $cities;
    private $regions;
    private $countries;
    private $categories;
    private static $instance ;


    public function __construct() {
        $this->cities = array();
        $this->regions = array();
        $this->countries = array();
        $this->categories = array();
        $this->conditions = array();
        $this->tables[] = sprintf('%st_item_description as d', DB_TABLE_PREFIX);
        $this->order();
        $this->limit();
        $this->results_per_page = 10;
        parent::__construct();
    }

    public static function newInstance() {
        if(!self::$instance instanceof self) {
            self::$instance = new self ;
        }
        return self::$instance ;
    }

    public function  getTableName() { return ''; }

    public static function getAllowedColumnsForSorting() {
        return( array('f_price', 'dt_pub_date') ) ;
    }

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

    public function order($o_c = 'dt_pub_date', $o_d = 'DESC') {
        $this->order_column = $o_c;
        $this->order_direction = $o_d;
    }

    public function limit($l_i = 0, $r_p_p = 10) {
        $this->limit_init = $l_i;
        $this->results_per_page = $r_p_p;
    }

    public function set_rpp($rpp) {
        $this->results_per_page = $rpp;
    }

    public function page($p = 0, $r_p_p = null) {
        if($r_p_p!=null) { $this->results_per_page = $r_p_p; };
        $this->limit_init = $this->results_per_page*$p;
        $this->results_per_page = $this->results_per_page;
    }

    public function addCity($city = array()) {
        if(is_array($city)) {
            foreach($city as $c) {
                $c = trim($c);
                if($c!='') {
                    if(is_int($c)) {
                    	$this->cities[] = sprintf("%st_item_location.fk_i_city_id = %d ", DB_TABLE_PREFIX, $c);
                    } else {
                    	$this->cities[] = sprintf("%st_item_location.s_city LIKE '%%%s%%' ", DB_TABLE_PREFIX, $c);
                    }
                }
            }
        } else {
            $city = trim($city);
            if($city!="") {
                if(is_int($city)) {
                	$this->cities[] = sprintf("%st_item_location.fk_i_city_id = %d ", DB_TABLE_PREFIX, $city);
                } else {
                	$this->cities[] = sprintf("%st_item_location.s_city LIKE '%%%s%%' ", DB_TABLE_PREFIX, $city);
                }
            }
        }
    }

    public function addRegion($region = array()) {
        if(is_array($region)) {
            foreach($region as $r) {
                $r = trim($r);
                if($r!='') {
                    if(is_int($r)) {
                    	$this->regions[] = sprintf("%st_item_location.fk_i_region_id = %d ", DB_TABLE_PREFIX, $r);
                    } else {
                    	$this->regions[] = sprintf("%st_item_location.s_region LIKE '%%%s%%' ", DB_TABLE_PREFIX, $r);
                    }
                }
            }
        } else {
            $region = trim($region);
            if($region!="") {
                if(is_int($region)) {
                	$this->regions[] = sprintf("%st_item_location.fk_i_region_id = %d ", DB_TABLE_PREFIX, $region);
                } else {
                	$this->regions[] = sprintf("%st_item_location.s_region LIKE '%%%s%%' ", DB_TABLE_PREFIX, $region);
                }
            }
        }
    }

    public function addCountry($country = array()) {
        if(is_array($country)) {
            foreach($country as $c) {
                $c = trim($c);
                if($c!='') {
                    if(strlen($c)==2) {
                    	$this->countries[] = sprintf("%st_item_location.fk_c_country_code = '%s' ", DB_TABLE_PREFIX, strtolower($c));
                    } else {
                    	$this->countries[] = sprintf("%st_item_location.s_region LIKE '%%%s%%' ", DB_TABLE_PREFIX, $c);
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

    public function priceRange( $price_min = 0, $price_max = 0) {
        if(is_numeric($price_min)) {
            $this->addConditions(sprintf("f_price >= %f", $price_min));
        }
        if(is_numeric($price_max) && $price_max>0) {
            $this->addConditions(sprintf("f_price <= %f", $price_max));
        }
    }

    public function priceMax($price) {
        $this->priceRange(null, $price);
    }

    public function priceMin($price) {
        $this->priceRange($price, null);
    }

    public function withPicture($pic = false) {
        if($pic) {
            $this->addTable(sprintf('%st_item_resource', DB_TABLE_PREFIX));
            $this->addConditions(sprintf("%st_item_resource.s_content_type LIKE '%%image%%' AND %st_item.pk_i_id = %st_item_resource.fk_i_item_id", DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX));
        } else {

        }
    }
    
    private function pruneBranches($branches = null) {
        if($branches!=null) {
            foreach($branches as $branch) {
                if(!in_array($branch['pk_i_id'], $this->categories)) {
                    $this->categories[] = sprintf("%st_item.fk_i_category_id = %d ", DB_TABLE_PREFIX, $branch['pk_i_id']);
                    $list = $this->pruneBranches($branch['categories']);
                }
            }
        }
    }

    public function addCategory($category = null)
    {
        if($category == null) return '' ;
        
        if(!is_numeric($category)) {
            $category = preg_replace('|/$|','',$category);
            $aCategory = explode('/', $category) ;
            $category = Category::newInstance()->find_by_slug($aCategory[count($aCategory)-1]) ;
            $category = $category['pk_i_id'] ;
        }
        $tree = Category::newInstance()->toSubTree($category) ;
        if(!in_array($category, $this->categories)) {
            $this->categories[] = sprintf("%st_item.fk_i_category_id = %d ", DB_TABLE_PREFIX, $category) ;
        }
        $this->pruneBranches($tree) ;
    }

    public function makeSQL($count = false) {

        //DEPRECATED. MARK FOR DELETE
        //$this->makeCompatible();

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

        if($count) {
            $this->sql = sprintf("SELECT COUNT(DISTINCT %st_item.pk_i_id) as totalItems FROM %st_item, %st_item_location, %s WHERE %st_item_location.fk_i_item_id = %st_item.pk_i_id AND %s AND %st_item.pk_i_id = d.fk_i_item_id", DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX, implode(', ', $this->tables), DB_TABLE_PREFIX, DB_TABLE_PREFIX, implode(' AND ', $this->conditions), DB_TABLE_PREFIX);
        } else {
            $this->sql = sprintf("SELECT SQL_CALC_FOUND_ROWS DISTINCT %st_item.*, %st_item_location.* FROM %st_item, %st_item_location, %s WHERE %st_item_location.fk_i_item_id = %st_item.pk_i_id AND %s AND %st_item.pk_i_id = d.fk_i_item_id ORDER BY %st_item.%s %s LIMIT %d, %d", DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX, implode(', ', $this->tables), DB_TABLE_PREFIX, DB_TABLE_PREFIX, implode(' AND ', $this->conditions), DB_TABLE_PREFIX, DB_TABLE_PREFIX, $this->order_column, $this->order_direction, $this->limit_init, $this->results_per_page);

        }
        return $this->sql;
    }

    public function makeSQLLocation($location = 's_city') {

        $this->makeCompatible();

        $this->addTable(sprintf("%st_item_location", DB_TABLE_PREFIX));

        $this->sql = sprintf("SELECT %st_item_location.s_country as country_name, %st_item_location.fk_i_city_id as city_id, %st_item_location.fk_c_country_code, %st_item_location.s_region as region_name, %st_item_location.fk_i_region_id as region_id, %st_item_location.s_city as city_name,COUNT( DISTINCT %st_item_location.fk_i_item_id) as items FROM %st_item, %s WHERE %s GROUP BY %st_item_location.%s", DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX, implode(', ', $this->tables), implode(' AND ', $this->conditions), DB_TABLE_PREFIX, $location);

        return $this->sql;
    }


    public function count() {
        $this->conn->osc_dbFetchResults($this->makeSQL(false));
        $sql = "SELECT FOUND_ROWS() as totalItems";
        $data = $this->conn->osc_dbFetchResult($sql);
        if(isset($data['totalItems'])) {
            return $data['totalItems'];
        } else {
            return 0;
        }
    }

    public function doSearch($extended = true) {
        $items = $this->conn->osc_dbFetchResults($this->makeSQL(false));
        if($extended) {
            return Item::newInstance()->extendData($items);
        } else {
            return $items;
        } 
    }

    public function searchCities($regions = null) {
        $this->addRegion($regions);
        return $this->conn->osc_dbFetchResults($this->makeSQLLocation('s_city'));
    }

    public function searchRegions($countries = null) {
        $this->addCountry($countries);
        return $this->conn->osc_dbFetchResults($this->makeSQLLocation('s_region'));
    }

    public function searchCountries() {
        return $this->conn->osc_dbFetchResults($this->makeSQLLocation('s_country'));
    }

    public function listCountries() {

        $this->addConditions(sprintf('%st_item_location.fk_c_country_code = cc.pk_c_code', DB_TABLE_PREFIX));
        $this->addConditions(sprintf('%st_item.pk_i_id = %st_item_location.fk_i_item_id', DB_TABLE_PREFIX, DB_TABLE_PREFIX));
        $sql = sprintf("SELECT cc.pk_c_code, cc.fk_c_locale_code, cc.s_name as country_name, (".str_replace('%', '%%', $this->makeSQL(true)).") as items FROM %st_country as cc GROUP BY cc.pk_c_code HAVING items > 0 ORDER BY items DESC", DB_TABLE_PREFIX);
        return $this->conn->osc_dbFetchResults($sql);
    }
    
    public function listRegions($country = '%%%%') {

        $this->addConditions(sprintf('%st_item_location.fk_i_region_id = rr.pk_i_id', DB_TABLE_PREFIX));
        $this->addConditions(sprintf('%st_item.pk_i_id = %st_item_location.fk_i_item_id', DB_TABLE_PREFIX, DB_TABLE_PREFIX));
        $sql = sprintf("SELECT rr.pk_i_id as region_id, rr.s_name as region_name, cc.pk_c_code, cc.fk_c_locale_code, cc.s_name as country_name, (".str_replace('%', '%%', $this->makeSQL(true)).") as items FROM %st_region as rr, %st_country as cc WHERE rr.fk_c_country_code LIKE '%s' GROUP BY rr.s_name HAVING items > 0 ORDER BY items DESC", DB_TABLE_PREFIX, DB_TABLE_PREFIX, strtolower($country));
        return $this->conn->osc_dbFetchResults($sql);
    }
    
    public function listCities($region = null) {
        $region_int = (int)$region;
        if(is_int($region_int) && $region_int!=0) {

            $this->addConditions(sprintf('%st_item_location.fk_i_city_id = ct.pk_i_id', DB_TABLE_PREFIX));
            $this->addConditions(sprintf('%st_item.pk_i_id = %st_item_location.fk_i_item_id', DB_TABLE_PREFIX, DB_TABLE_PREFIX));
            $sql = sprintf("SELECT ct.pk_i_id as city_id, ct.s_name as city_name, rr.pk_i_id as region_id, rr.s_name as region_name, cc.pk_c_code, cc.fk_c_locale_code, cc.s_name as country_name, (".str_replace('%', '%%', $this->makeSQL(true)).") as items FROM %st_region as rr, %st_country as cc, %st_city as ct WHERE ct.fk_i_region_id = %d GROUP BY ct.s_name HAVING items > 0 ORDER BY items DESC", DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX,  $region_int);
            return $this->conn->osc_dbFetchResults($sql);
        } else {

            $this->addConditions(sprintf('%st_item_location.fk_i_city_id = ct.pk_i_id', DB_TABLE_PREFIX));
            $this->addConditions(sprintf('%st_item.pk_i_id = %st_item_location.fk_i_item_id', DB_TABLE_PREFIX, DB_TABLE_PREFIX));
            $sql = sprintf("SELECT ct.pk_i_id as city_id, ct.s_name as city_name, rr.pk_i_id as region_id, rr.s_name as region_name, cc.pk_c_code, cc.fk_c_locale_code, cc.s_name as country_name, (".str_replace('%', '%%', $this->makeSQL(true)).") as items FROM %st_region as rr, %st_country as cc, %st_city as ct WHERE rr.s_name LIKE '%s' AND ct.fk_i_region_id = rr.pk_i_id GROUP BY ct.s_name HAVING items > 0 ORDER BY items DESC", DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX,  $region);
            return $this->conn->osc_dbFetchResults($sql);
        }
    }

    // define '__sleep()' method
    function __sleep(){
        unset($this->conn);
        return array_keys(get_object_vars($this));
    }
    // define '__wakeup()' method
    function __wakeup(){
        $this->conn = getConnection();
    }
 
    /*public function makeCompatible() {
        // COMPATIBILITY (DEPRECATED)
        global $conditions;
        global $search_tables;

        if($conditions!=null) {
            foreach($conditions as $cond) {
                $this->addConditions($cond);
            }
        }
        if($search_tables!=null) {
            foreach($search_tables as $t) {
                $this->addTable($t);
            }
        }
    }*/
}

