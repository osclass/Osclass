<?php if ( !defined('ABS_PATH') ) exit('ABS_PATH is not loaded. Direct access is not allowed.');

    /*
     *      Osclass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2012 OSCLASS
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
        private static $instance;
        private $language;
        private $tree;
        private $categories;
        private $categoriesEnabled;
        private $relation;
        private $empty_tree;

        public static function newInstance()
        {
            if( !self::$instance instanceof self ) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        /**
         * Set data related to t_category table
         */
        function __construct($l = '')
        {
            parent::__construct();
            $this->setTableName('t_category');
            $this->setPrimaryKey('pk_i_id');
            $array_fields = array(
                'pk_i_id',
                'fk_i_parent_id',
                'i_expiration_days',
                'i_position',
                'b_enabled',
                's_icon',
                'b_price_enabled'
            );
            $this->setFields($array_fields);

            if($l == '') {
                $l = osc_current_user_locale();
            }

            $this->language   = $l;
            $this->tree       = null;
            $this->relation   = null;
            $this->categories = null;
            $this->empty_tree = true;
            $this->toTree();
        }

        /**
         * Comodin function to serve multiple queries
         *
         * *Note: param needs to be escaped, inside function will not be escaped
         *
         * @access public
         * @since unknown
         * @param mixed
         * @return array
         */
        public function listWhere($where = '')
        {
            if( $where !== '') {
                $this->dao->where( $where );
            }

            $this->dao->select( sprintf("a.*, b.*, c.i_num_items, FIELD(fk_c_locale_code, '%s') as locale_order", $this->dao->connId->real_escape_string(osc_current_user_locale()) ) );
            $this->dao->from( $this->getTableName().' as a' );
            $this->dao->join(DB_TABLE_PREFIX.'t_category_description as b', 'a.pk_i_id = b.fk_i_category_id', 'INNER');
            $this->dao->join(DB_TABLE_PREFIX.'t_category_stats  as c ', 'a.pk_i_id = c.fk_i_category_id', 'LEFT');
            $this->dao->where("b.s_name != ''");
            $this->dao->orderBy('locale_order', 'DESC');
            $subquery = $this->dao->_getSelect();
            $this->dao->_resetSelect();

            $this->dao->select();
            $this->dao->from( sprintf( '(%s) dummytable', $subquery ) ); // $subselect.'  dummytable');
            $this->dao->groupBy('pk_i_id');
            $this->dao->orderBy('i_position', 'ASC');
            $rs = $this->dao->get();

            if( $rs === false ) {
                return array();
            }

            if( $rs->numRows() == 0 ) {
                return array();
            }

            return $rs->result();
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
            $this->dao->select( sprintf("a.*, b.*, c.i_num_items, FIELD(fk_c_locale_code, '%s') as locale_order", $this->dao->connId->real_escape_string(osc_current_user_locale()) ) );
            $this->dao->from( $this->getTableName().' as a' );
            $this->dao->join(DB_TABLE_PREFIX.'t_category_description as b', 'a.pk_i_id = b.fk_i_category_id', 'INNER');
            $this->dao->join(DB_TABLE_PREFIX.'t_category_stats  as c ', 'a.pk_i_id = c.fk_i_category_id', 'LEFT');
            $this->dao->where("b.s_name != ''");
            $this->dao->where("a.b_enabled = 1");
            $this->dao->orderBy('locale_order', 'DESC');
            $subquery = $this->dao->_getSelect();
            $this->dao->_resetSelect();

            $this->dao->select();
            $this->dao->from( sprintf( '(%s) dummytable', $subquery ) ); // $subselect.'  dummytable');
            $this->dao->groupBy('pk_i_id');
            $this->dao->orderBy('i_position', 'ASC');
            $rs = $this->dao->get();

            if( $rs === false ) {
                return array();
            }

            if( $rs->numRows() == 0 ) {
                return array();
            }

            return $rs->result();
        }

        /**
         * Return categories in a tree
         *
         * @access public
         * @since unknown
         * @param bool $empty
         * @return array
         */
        public function toTree($empty = true)
        {
            if($empty==$this->empty_tree && $this->tree!=null) {
                return $this->tree;
            }
            $this->empty_tree = $empty;
            // if listEnabled has been called before, don't redo the query
            if($this->categoriesEnabled) {
                $categories = $this->categoriesEnabled;
            } else {
                $this->categoriesEnabled = $this->listEnabled();
                $categories              = $this->categoriesEnabled;
            }
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
        private function sideTree($branch, $categories, $relation)
        {
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
        public function findRootCategories()
        {
            // juanramon: specific condition
            $this->dao->where( 'a.fk_i_parent_id IS NULL' );
            // end specific condition

            return $this->listWhere();
        }

        /**
         * Find root enabled categories
         *
         * @access public
         * @since unknown
         * @return array
         */
        public function findRootCategoriesEnabled()
        {
            // juanramon: specific condition
            $this->dao->where( 'a.fk_i_parent_id IS NULL' );
            $this->dao->where( 'a.b_enabled', '1' );
            // end specific condition

            return $this->listWhere();
        }

        /**
         * Returna  tree of a given category as the root
         *
         * @access public
         * @since unknown
         * @param integer$category
         * @return array
         */
        public function toSubTree($category = null)
        {
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
        public function listAll($description = true)
        {
            // juanramon: specific condition
            $this->dao->where( '1 = 1' );
            // end specific condition

            return $this->listWhere();
        }

        /**
         * Return a tree of ALL (enabled & disabled) categories
         *
         * @access public
         * @since unknown
         * @return array
         */
        public function toTreeAll()
        {
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
        public function toRootTree($cat = null)
        {
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
         * @param integer $categoryID
         * @return array
         */
        public function findRootCategory($categoryID)
        {
            // juanramon: specific condition
            $this->dao->where( 'a.fk_i_parent_id IS NOT NULL' );
            $this->dao->where( 'a.pk_i_id', $categoryID );
            // end specific condition

            $results = $this->listWhere();

            if( count($results) > 0 ) {
                return $this->findRootCategory( $results[0]['fk_i_parent_id'] );
            }

            return $this->findByPrimaryKey( $categoryID );
        }

        /**
         * Find a category find its slug
         *
         * @access public
         * @since unknown
         * @param string $slug
         * @return array
         */
        public function findBySlug($slug)
        {
            // juanramon: specific condition
            $this->dao->where( 'b.s_slug', $slug );
            // end specific condition

            $results = $this->listWhere();
            if( count($results) > 0 ) {
                return $results[0];
            }

            return array();
        }

        /**
         * Same as toRootTree but reverse the results
         *
         * @access public
         * @since unknown
         * @param integer$category_id
         * @return array
         */
        public function hierarchy($category_id)
        {
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
        public function isRoot($categoryID)
        {
            // juanramon: specific condition
            $this->dao->where( 'fk_i_parent_id IS NULL' );
            $this->dao->where( 'pk_i_id', $categoryID );
            // end specific condition

            $results = $this->listWhere();

            if( count($results) > 0 ) {
                return true;
            }

            return false;
        }

        /**
         * returns the children of a given category
         *
         * @access public
         * @since unknown
         * @param integer$cat_id
         * @return array
         */
        public function findSubcategories($categoryID)
        {
            // juanramon: specific condition
            $this->dao->where( 'fk_i_parent_id', $categoryID );
            // end specific condition

            return $this->listWhere();
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
                return false;
            }

            $category = array();

            if( array_key_exists($categoryID, $this->categories) ) {
                $category = $this->categories[$categoryID];

                // if we already have locale data, we return the category
                if( array_key_exists('locale', $category)) {
                    return $category;
                }
            } else {
                $this->dao->select( $this->getFields() );
                $this->dao->from( $this->getTableName() );
                $this->dao->where( 'pk_i_id', $categoryID );
                $result = $this->dao->get();

                if( $result == false ) {
                    return false;
                }

                $category = $result->row();
            }

            $this->dao->select();
            $this->dao->from( $this->getTablePrefix() . 't_category_description' );
            $this->dao->where( 'fk_i_category_id', $category['pk_i_id'] );
            $this->dao->orderBy( 'fk_c_locale_code' );
            $result = $this->dao->get();

            if( $result == false ) {
                return false;
            }

            $sub_rows = $result->result();
            $row      = array();
            foreach ($sub_rows as $sub_row) {
                $row[$sub_row['fk_c_locale_code']] = $sub_row;
            }
            $category['locale'] = $row;

            // if it exists in the $categories array, we copy the row data
            if( array_key_exists($categoryID, $this->categories) ) {
                $this->categories[$categoryID] = $category;
            }

            return $category;
        }

        /**
         * Return a category's name given an id
         *
         * @access public
         * @since 3.1
         * @param int $categoryID primary key
         * @return string
         */
        public function findNameByPrimaryKey($categoryID)
        {
            if($categoryID == null) {
                return false;
            }

            $category = array();

            if( array_key_exists($categoryID, $this->categories) ) {
                $category = $this->categories[$categoryID];
            } else {
                $this->dao->select( "s_name" );
                $this->dao->from( $this->getTablePrefix() . 't_category_description' );
                $this->dao->where( 'fk_i_category_id', $categoryID );
                $result = $this->dao->get();

                if( $result == false ) {
                    return false;
                }

                $category = $result->row();
            }

            return $category['s_name'];
        }

        /**
         * Return list of categories' name and id by locale
         *
         * @access public
         * @since 3.2.1
         * @param string $locale
         * @return array
         */
        public function _findNameIDByLocale($locale = null)
        {
            if($locale == null) {
                return false;
            }

            $this->dao->select( "s_name, fk_i_category_id as pk_i_id" );
            $this->dao->from( $this->getTablePrefix() . 't_category_description' );
            $this->dao->where( 'fk_c_locale_code', $locale );
            $result = $this->dao->get();

            if( $result == false ) {
                return array();
            }

            return $result->result();
        }

        /**
         * delete a category and all information linked to it
         *
         * @access public
         * @since unknown
         * @param integer$pk primary key
         */
        public function deleteByPrimaryKey($pk)
        {
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

            osc_run_hook('delete_category', $pk);

            $this->dao->delete( sprintf('%st_plugin_category', DB_TABLE_PREFIX), array('fk_i_category_id' => $pk) );
            $this->dao->delete( sprintf('%st_category_description', DB_TABLE_PREFIX), array('fk_i_category_id' => $pk) );
            $this->dao->delete( sprintf('%st_category_stats', DB_TABLE_PREFIX), array('fk_i_category_id' => $pk) );
            $this->dao->delete( sprintf('%st_meta_categories', DB_TABLE_PREFIX), array('fk_i_category_id' => $pk) );
            return $this->dao->delete( sprintf('%st_category', DB_TABLE_PREFIX), array('pk_i_id' => $pk) );
        }

        /**
         * Update a category
         *
         * @access public
         * @since unknown
         * @param array $fields
         * @param array $aFieldsDescriptions
         * @param int $pk primary key
         * @return mixed bool if there is an error, affectedRows if there isn't errors
         */
        public function updateByPrimaryKey($data, $pk)
        {
            $fields = $data['fields'];

            $aFieldsDescription = $data['aFieldsDescription'];
            $return       = true;
            $affectedRows = 0;
            //UPDATE for category
            $res = $this->dao->update($this->getTableName(), $fields, array('pk_i_id' => $pk));
            if($res >= 0) {
                // update dt_expiration (tablel t_item) using category.i_expiration_days
                if($fields['i_expiration_days'] > 0) {
                    $update_dt_expiration = sprintf('update %st_item as a
                        left join %st_category  as b on b.pk_i_id = a.fk_i_category_id
                        set a.dt_expiration = date_add(a.dt_pub_date, INTERVAL b.i_expiration_days DAY)
                        where a.fk_i_category_id = %d ', DB_TABLE_PREFIX, DB_TABLE_PREFIX, $pk );

                    $this->dao->query($update_dt_expiration);
                // update dt_expiration (table t_item) using the max date value
                } else if( $fields['i_expiration_days'] == 0) {
                    $update_dt_expiration = sprintf("update %st_item as a
                        set a.dt_expiration = '9999-12-31 23:59:59'
                        where a.fk_i_category_id = %s", DB_TABLE_PREFIX, $pk );

                    $this->dao->query($update_dt_expiration);
                }

                $affectedRows = $res;

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

                    $rs = $this->dao->update(DB_TABLE_PREFIX.'t_category_description', $fieldsDescription, $array_where);
                    if($rs == 0) {
                        $this->dao->select();
                        $this->dao->from($this->tableName." as a");
                        $this->dao->join(sprintf("%st_category_description as b", DB_TABLE_PREFIX), "a.pk_i_id = b.fk_i_category_id", "INNER");
                        $this->dao->where("a.pk_i_id", $pk);
                        $this->dao->where("b.fk_c_locale_code", $k);
                        $result = $this->dao->get();
                        $rows = $result->result();
                        if($result->numRows == 0) {
                            $res_insert = $this->insertDescription($fieldsDescription);
                            $affectedRows += 1;
                        }
                    } else if($rs > 0) {
                        $affectedRows += $rs;
                    } else if( is_bool($rs) ) { // catch error
                        if($return) {
                            $return = $rs;
                        }
                    }
                }
            } else {
                $return = $res;
            }

            if($return) {
                return $affectedRows;
            } else {
                return $return;
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
            $category_id = $this->dao->insertedId();
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
         * @return bool
         */
        public function insertDescription($fields_description)
        {
            if (!empty($fields_description['s_name'])) {
                return $this->dao->insert(DB_TABLE_PREFIX . 't_category_description', $fields_description );
            }
        }

        /**
         * Update categories' order
         *
         * @access public
         * @since unknown
         * @param integer $pk_i_id
         * @param integer $order
         * @return mixed false on fail, int of num. of affected rows
         */
        public function updateOrder($pk_i_id, $order)
        {
            return $this->dao->update($this->tableName, array('i_position' => $order), array('pk_i_id' => $pk_i_id));

        }

        /**
         * Update categories' expiration
         *
         * @access public
         * @since unknown
         * @param integer $pk_i_id
         * @param integer $expiration
         * @param boolean $updateSubcategories
         * @return mixed false on fail, int of num. of affected rows
         */
        public function updateExpiration($pk_i_id, $expiration, $updateSubcategories = false)
        {
            $itemManager = Item::newInstance();

            $this->dao->select('pk_i_id');
            $this->dao->from(DB_TABLE_PREFIX.'t_item');
            $this->dao->where(sprintf('fk_i_category_id = %d', $pk_i_id));
            $result = $this->dao->get();
            if($result == false) {
                $items = array();
            }
            $items  = $result->result();
            foreach($items as $item) {
                $itemManager->updateExpirationDate($item['pk_i_id'], $expiration);
            }
            $result = $this->dao->update($this->tableName, array('i_expiration_days' => $expiration), array('pk_i_id'  => $pk_i_id));
            if($updateSubcategories) {
                $subcategories = $this->findSubcategories($pk_i_id);
                foreach($subcategories as $c) {
                    $this->updateExpiration($c['pk_i_id'], $expiration, true);
                }
            }
            return $result;
        }

        /**
         * Update categories' price enabled
         *
         * @access public
         * @since unknown
         * @param integer $pk_i_id
         * @param integer $enabled
         * @param boolean $updateSubcategories
         * @return bool true on pass, false on fail
         */
        public function updatePriceEnabled($pk_i_id, $enabled, $updateSubcategories = false)
        {
            $result = $this->dao->update($this->tableName, array('b_price_enabled' => $enabled), array('pk_i_id'  => $pk_i_id));
            if($updateSubcategories) {
                $subcategories = $this->findSubcategories($pk_i_id);
                foreach($subcategories as $c) {
                    $this->updatePriceEnabled($c['pk_i_id'], $enabled, true);
                }
            }
            return $result;
        }

        /**
         * update name of a category
         *
         * @access public
         * @since unknown
         * @param integer $pk_i_id
         * @param string $locale
         * @param string $name
         * @return mixed false on fail, int of num. of affected rows
         */
        public function updateName($pk_i_id, $locale, $name)
        {
            return $this->dao->update(DB_TABLE_PREFIX.'t_category_description', array('s_name' => $name), array('fk_i_category_id' => $pk_i_id,'fk_c_locale_code' => $locale));
        }

        /**
         * Formats a value before being inserted in DB.
         */
        public function formatValue($value)
        {
            if(is_null($value)) return DB_CONST_NULL;
            else $value = trim($value);
            switch($value) {
                case DB_FUNC_NOW:
                case DB_CONST_TRUE:
                case DB_CONST_FALSE:
                case DB_CONST_NULL:
                    break;
                default:
                    $value = '\'' . addslashes($value) . '\'';
                    break;
            }

            return $value;
        }
    }

    /* file end: ./oc-includes/osclass/model/Category.php */
?>
