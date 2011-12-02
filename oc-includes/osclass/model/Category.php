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
     * Category DAO
     */
    class Category extends DAO
    {
        /**
         *
         * @var type 
         */
        private static $instance ;
        private $language ;
        private $tree ;
        private $categories ;
        private $relation ;
        private $empty_tree ;

        public static function newInstance()
        {
            if( !self::$instance instanceof self ) {
                self::$instance = new self ;
            }
            return self::$instance ;
        }

        /**
         * Set data related to t_category table
         */
        function __construct($l = '')
        {
            parent::__construct() ;
            $this->setTableName('t_category') ;
            $this->setPrimaryKey('pk_i_id') ;
            $array_fields = array(
                'pk_i_id',
                'fk_i_parent_id',
                'i_expiration_days',
                'i_position',
                'b_enabled',
                's_icon'
            ) ;
            $this->setFields($array_fields) ;

            if($l == '') {
                $l = osc_current_user_locale() ;
            }

            $this->language   = $l ;
            $this->tree       = null ;
            $this->relation   = null ;
            $this->categories = null ;
            $this->empty_tree = true ;
            $this->toTree() ;
        }

        /**
         * Comodin function to serve multiple queries
         * 
         * @access public
         * @since unknown
         * @param mixed 
         * @return array 
         */
        public function listWhere() {
            $argv = func_get_args();
            $sql  = null;
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

            $result = $this->dao->query(sprintf('SELECT * FROM (SELECT * FROM %s as a INNER JOIN %st_category_description as b ON a.pk_i_id = b.fk_i_category_id WHERE b.s_name != \'\' AND %s  ORDER BY a.i_position DESC) dummytable LEFT JOIN %st_category_stats as c ON dummytable.pk_i_id = c.fk_i_category_id GROUP BY pk_i_id ORDER BY i_position ASC', $this->getTableName(), DB_TABLE_PREFIX, $sql, DB_TABLE_PREFIX));
            if($result) {
                return $result->result();
            } else {
                return array();
            }
        }
        
        /**
         * List all enabled categories
         * 
         * @access public
         * @since unknown
         * @return array 
         */
        public function listEnabled() 
        {
            $sql = 'SELECT * FROM (';
            $sql .= 'SELECT a.*, b.*, c.i_num_items, FIELD(fk_c_locale_code, \''.osc_current_user_locale().'\') as locale_order FROM '.$this->getTableName().' as a INNER JOIN '.DB_TABLE_PREFIX.'t_category_description as b ON a.pk_i_id = b.fk_i_category_id ';
            $sql .= 'LEFT JOIN '.DB_TABLE_PREFIX.'t_category_stats as c ON a.pk_i_id = c.fk_i_category_id ';
            $sql .= 'WHERE b.s_name != \'\' AND a.b_enabled = 1 ORDER BY locale_order DESC';
            $sql .= ') as dummytable GROUP BY pk_i_id ORDER BY i_position ASC';
            $result = $this->dao->query($sql);
            return $result->result();
        }
        
        /**
         * Return categories in a tree
         * 
         * @access public
         * @since unknown
         * @param bool $empty
         * @return array 
         */
        public function toTree($empty = true) {
            if($empty==$this->empty_tree && $this->tree!=null) {
                return $this->tree;
            }
            $this->empty_tree = $empty;
            $categories = $this->listEnabled();
            $this->categories = array();
            $this->relation = array();
            foreach($categories as $c) {
                if($empty || (!$empty && $c['i_num_items']>0)) {
                    $this->categories[$c['pk_i_id']] = $c;
                    if($c['fk_i_parent_id']==null) {
                        $this->tree[] = $c;
                        $this->relation[0][] = $c['pk_i_id'];
                    } else {
                        $this->relation[$c['fk_i_parent_id']][] = $c['pk_i_id'];
                    }
                }
            }

            if(count($this->relation) == 0 || !isset($this->relation[0]) ) {
                return array();
            }

            $this->tree = $this->sideTree($this->relation[0], $this->categories, $this->relation);
            return $this->tree;
        }

        /**
         * Helps create the tree
         * 
         * @access private
         * @since unknown
         * @param array $branch
         * @param array $categories
         * @param array $relation
         * @return array 
         */
        private function sideTree($branch, $categories, $relation) {
            $tree = array();
            if( !empty($branch) ) {
                foreach($branch as $b) {
                    $aux = $categories[$b];
                    if(isset($relation[$b]) && is_array($relation[$b])) {
                        $aux['categories'] = $this->sideTree($relation[$b], $categories, $relation);
                    } else {
                        $aux['categories'] = array();
                    }
                    $tree[] = $aux;
                }
            }
            return $tree;
        }

        /**
         * Find root categories
         * 
         * @access public
         * @since unknown
         * @return array 
         */
        public function findRootCategories() {
            return $this->listWhere("a.fk_i_parent_id IS NULL") ;
        }

        /**
         * Find root enabled categories
         * 
         * @access public
         * @since unknown
         * @return array 
         */
        public function findRootCategoriesEnabled() {
            return $this->listWhere("a.fk_i_parent_id IS NULL AND a.b_enabled = 1") ;
        }
        
        /**
         * Returna  tree of a given category as the root
         * 
         * @access public
         * @since unknown
         * @param integer$category
         * @return array 
         */
        public function toSubTree($category = null) {
            $this->toTree();
            if($category==null) {
                return array();
            } else {
                if(isset($this->relation[$category])) {
                    $tree = $this->sideTree($this->relation[$category], $this->categories, $this->relation);
                    return $tree;
                } else {
                    array();
                }
            }
        }

        /**
         * Lit all categories
         * 
         * @access public
         * @since unknown
         * @return array 
         */
        public function listAll($description = true) {
            if( $description ) {
                return $this->listWhere('1 = 1');
            }

            $this->dao->select( $this->getFields() ) ;
            $this->dao->from( $this->getTableName() ) ;
            $result = $this->dao->get() ;

            if( $result == false ) {
                return array() ;
            }

            if( $result->numRows() == 0 ) {
                return array() ;
            }

            return $result->result() ;
        }

        /**
         * Return a tree of ALL (enabled & disabled) categories
         * 
         * @access public
         * @since unknown
         * @return array 
         */
        public function toTreeAll() {
            $categories = $this->listAll();
            $all_categories = array();
            $all_relation = array();
            $tree = array();
            foreach($categories as $c) {
                $all_categories[$c['pk_i_id']] = $c;
                if($c['fk_i_parent_id']==null) {
                    $tree[] = $c;
                    $all_relation[0][] = $c['pk_i_id'];
                } else {
                    $all_relation[$c['fk_i_parent_id']][] = $c['pk_i_id'];
                }
            }
            if(isset($all_relation[0])) {
                $tree = $this->sideTree($all_relation[0], $all_categories, $all_relation);
            } else {
                $tree = array();
            }
            return $tree;
        }

        /**
         * Given a category, return the branch from the root to the category
         * 
         * @access public
         * @since unknown
         * @param integer$category
         * @return array 
         */
        public function toRootTree($cat = null) {
            $tree = array();
            if($cat!=null) {
                $tree_b = array();
                if(is_numeric($cat)) {
                    $cat = $this->findByPrimaryKey($cat);
                } else {
                    $cat = $this->findBySlug($cat);
                }
                $tree[0] = $cat;
                while($cat['fk_i_parent_id']!=null) {
                    $cat = $this->findByPrimaryKey($cat['fk_i_parent_id']);
                    array_unshift($tree, '');//$cat);
                    $tree[0] = $cat;
                }
            }
            return $tree;
        }

        /**
         * Return the root category of a one given
         * 
         * @access public
         * @since unknown
         * @param integer$category_id
         * @return array 
         */
        public function findRootCategory($category_id) {
            $results = $this->listWhere("a.pk_i_id = " . $category_id . " AND a.fk_i_parent_id IS NOT NULL");
            if (count($results) > 0) {
                return $this->findRootCategory($results[0]['fk_i_parent_id']);
            } else {
                return $this->findByPrimaryKey($category_id);
            }
        }

        /**
         * Find a category find its slug
         * 
         * @access public
         * @since unknown
         * @param string $slug
         * @return array 
         */
        public function findBySlug($slug) {
            $results = $this->listWhere("b.s_slug = '" . $slug . "'");
            if(isset($results[0])) {
                return $results[0];
            }
            return null;
        }

        /**
         * Same as toRootTree but reverse the results
         * 
         * @access public
         * @since unknown
         * @param integer$category_id
         * @return array 
         */
        public function hierarchy($category_id) {
            return array_reverse($this->toRootTree($category_id));
        }

        /**
         * Check if it's a root category
         * 
         * @access public
         * @since unknown
         * @param integer$category_id
         * @return boolean
         */
        public function isRoot($category_id) {
            $results = $this->listWhere("pk_i_id = " . $category_id . " AND fk_i_parent_id IS NULL");
            if (count($results) > 0) {
                return true;
            } else {
                return false;
            }
        }

        /**
         * returns the children of a given category
         * 
         * @access public
         * @since unknown
         * @param integer$cat_id
         * @return array 
         */
        public function findSubcategories($cat_id) {
            return $this->listWhere("fk_i_parent_id = %d", $cat_id);
        }

        /**
         * Return a category given an id
         * This overwrite findByPrimaryKey of DAO model because we store the 
         * categories on an array for the tree and it's faster than a SQL query
         * 
         * @access public
         * @since unknown
         * @param int $categoryID primary key
         * @return array 
         */
        public function findByPrimaryKey($categoryID)
        {
            if($categoryID == null) {
                return false ;
            }

            $category = array() ;

            if( array_key_exists($categoryID, $this->categories) ) {
                $category = $this->categories[$categoryID];

                // if we already have locale data, we return the category
                if( array_key_exists('locale', $category)) {
                    return $category ;
                }
            } else {
                $this->dao->select( $this->getFields() ) ;
                $this->dao->from( $this->getTableName() ) ;
                $this->dao->where( 'pk_i_id', $categoryID ) ;
                $result = $this->dao->get() ;

                if( $result == false ) {
                    return false ;
                }

                $category = $result->row() ;
            }

            $this->dao->select() ;
            $this->dao->from( $this->getTablePrefix() . 't_category_description' ) ;
            $this->dao->where( 'fk_i_category_id', $category['pk_i_id'] ) ;
            $this->dao->orderBy( 'fk_c_locale_code' ) ;
            $result = $this->dao->get() ;

            if( $result == false ) {
                return false ;
            }

            $sub_rows = $result->result();
            $row      = array();
            foreach ($sub_rows as $sub_row) {
                $row[$sub_row['fk_c_locale_code']] = $sub_row;
            }
            $category['locale'] = $row ;

            // if it exists in the $categories array, we copy the row data
            if( array_key_exists($categoryID, $this->categories) ) {
                $this->categories[$categoryID] = $category ;
            }

            return $category;
        }

        /**
         * delete a category and all information linked to it
         * 
         * @access public
         * @since unknown
         * @param integer$pk primary key
         */
        public function deleteByPrimaryKey($pk) {
            $items = Item::newInstance()->findByCategoryID($pk);
            $subcats = $this->findSubcategories($pk);
            if (count($subcats) > 0) {
                foreach ($subcats as $s) {
                    $this->deleteByPrimaryKey($s["pk_i_id"]);
                }
            }

            if (count($items) > 0) {
                foreach ($items as $item) {
                    Item::newInstance()->deleteByPrimaryKey($item["pk_i_id"]);
                }
            }

            osc_run_hook("delete_category", $pk);
            
            $this->dao->delete(DB_TABLE_PREFIX.'t_plugin_category', array("fk_i_category_id", $pk));
            $this->dao->delete(DB_TABLE_PREFIX.'t_category_description', array("fk_i_category_id", $pk));
            $this->dao->delete(DB_TABLE_PREFIX.'t_category_stats', array("fk_i_category_id", $pk));
            $this->dao->delete(DB_TABLE_PREFIX.'t_category', array("pk_i_id", $pk));

            $this->dao->query(sprintf("DELETE FROM %st_plugin_category WHERE fk_i_category_id = '%s'", DB_TABLE_PREFIX, $pk));
            $this->dao->query(sprintf("DELETE FROM %st_category_description WHERE fk_i_category_id = '%s'", DB_TABLE_PREFIX, $pk));
            $this->dao->query(sprintf("DELETE FROM %st_category_stats WHERE fk_i_category_id = '%s'", DB_TABLE_PREFIX, $pk));
            $this->dao->query(sprintf("DELETE FROM %s WHERE pk_i_id = '%s'", $this->tableName, $pk));
        }

        /**
         * Update a category
         * 
         * @access public
         * @since unknown
         * @param array $fields
         * @param array $aFieldsDescriptions
         * @param int $pk primary key
         */
        public function updateByPrimaryKey($data, $pk) {
            $fields = $data['fields'];
            $aFieldsDescription = $data['aFieldsDescription'];
            //UPDATE for category
            $this->dao->update($this->getTableName(), $fields, array('pk_i_id' => $pk)) ;
            foreach ($aFieldsDescription as $k => $fieldsDescription) {
                //UPDATE for description of categories
                $fieldsDescription['fk_i_category_id'] = $pk;
                $fieldsDescription['fk_c_locale_code'] = $k;
                $slug_tmp = $slug = osc_sanitizeString(osc_apply_filter('slug', isset($fieldsDescription['s_name'])?$fieldsDescription['s_name']:''));
                $slug_unique = 1;
                while(true) {
                    $cat_slug = $this->findBySlug($slug);
                    if(!isset($cat_slug['pk_i_id']) || $cat_slug['pk_i_id']==$pk) {
                        break;
                    } else {
                        $slug = $slug_tmp . "_" . $slug_unique;
                        $slug_unique++;
                    }
                }
                $fieldsDescription['s_slug'] = $slug;
                $array_where = array(
                    'fk_i_category_id'  => $pk,
                    'fk_c_locale_code'  => $fieldsDescription["fk_c_locale_code"]
                );
                
                $rs = $this->dao->update(DB_TABLE_PREFIX.'t_category_description', $fieldsDescription, $array_where) ;

                if($rs == 0) {
                    $rows = $this->dao->query(sprintf("SELECT * FROM %s as a INNER JOIN %st_category_description as b ON a.pk_i_id = b.fk_i_category_id WHERE a.pk_i_id = '%s' AND b.fk_c_locale_code = '%s'", $this->tableName, DB_TABLE_PREFIX, $pk, $k));
                    if($rows->numRows == 0) {
                        $this->insertDescription($fieldsDescription);
                    }
                }
            }
        }

        /**
         * Inser a new category
         * 
         * @access public
         * @since unknown
         * @param array $fields
         * @param array $aFieldsDescriptions
         */
        public function insert($fields, $aFieldsDescription = null )
        {
            $this->dao->insert($this->getTableName(),$fields);
            $category_id = $this->dao->insertedId() ;
            foreach ($aFieldsDescription as $k => $fieldsDescription) {
                $fieldsDescription['fk_i_category_id'] = $category_id;
                $fieldsDescription['fk_c_locale_code'] = $k;
                $slug_tmp = $slug = osc_sanitizeString(osc_apply_filter('slug', $fieldsDescription['s_name']));
                $slug_unique = 1;
                while(true) {
                    if(!$this->findBySlug($slug)) {
                        break;
                    } else {
                        $slug = $slug_tmp . "_" . $slug_unique;
                        $slug_unique++;
                    }
                }
                $fieldsDescription['s_slug'] = $slug;
                $this->dao->insert(DB_TABLE_PREFIX . 't_category_description',$fieldsDescription);
            }

            return $category_id;
        }

        /**
         * Insert the description of a category
         * 
         * @access public
         * @since unknown
         * @param array $fields_description
         */
        public function insertDescription($fields_description) {
            if (!empty($fields_description['s_name'])) {
                $columns = implode(', ', array_keys($fields_description));

                $set = "";
                foreach ($fields_description as $value) {
                    if ($set != "")
                        $set .= ", ";
                    $set .= "'$value'";
                }
                $sql = 'INSERT INTO ' . DB_TABLE_PREFIX . 't_category_description (' . $columns . ') VALUES (' . $set . ')';
                $this->dao->query($sql);
            }
        }

        /**
         * Update categories' order
         * 
         * @access public
         * @since unknown
         * @param integer$pk_i_id
         * @param integer$order
         * @return mixed false on fail, int of num. of affected rows
         */
        public function updateOrder($pk_i_id, $order) {
            $sql = 'UPDATE ' . $this->tableName . " SET `i_position` = '".$order."' WHERE `pk_i_id` = " . $pk_i_id;
            return $this->dao->query($sql);
        }

        /**
         * update name of a category
         * 
         * @access public
         * @since unknown
         * @param integer$pk_i_id
         * @param string $locale
         * @param string $name
         * @return mixed false on fail, int of num. of affected rows
         */
        public function updateName($pk_i_id, $locale, $name) {
            $array_set = array(
                's_name'    => $name
            );
            $array_where = array(
                'fk_i_category_id'  => $pk_i_id,
                'fk_c_locale_code'  => $locale
            );
            return $this->dao->update(DB_TABLE_PREFIX.'t_category_description', $array_set);
        }
        
         /**
        * Formats a value before being inserted in DB.
        */
        public function formatValue($value) {
            if(is_null($value)) return DB_CONST_NULL;
            else $value = trim($value);
            switch($value) {
                case DB_FUNC_NOW:
                case DB_CONST_TRUE:
                case DB_CONST_FALSE:
                case DB_CONST_NULL:
                    break;
                default:
                    $value = '\'' . addslashes($value) . '\'' ;
                    break;
            }

            return $value;
        }
    }

    /* file end: ./oc-includes/osclass/model/Category.php */
?>