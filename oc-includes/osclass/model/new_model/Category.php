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
        private $tree;
        private $categories;
        private $relation;
        private $empty_tree;

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
            );
            $this->setFields($array_fields) ;

            if($l == "") {
                $l = osc_current_user_locale() ;
            }

            $this->language = $l ;
            $this->tree = null;
            $this->relation = null;
            $this->categories = null;
            $this->empty_tree = true;
            $this->toTree();
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

            $result = $this->dao->query(sprintf('SELECT * FROM (SELECT *, FIELD(b.fk_c_locale_code, \'%s\', \'%s\') as sorter FROM %s as a INNER JOIN %st_category_description as b ON a.pk_i_id = b.fk_i_category_id WHERE b.s_name != \'\' AND %s  ORDER BY sorter DESC, a.i_position DESC) dummytable LEFT JOIN %st_category_stats as c ON dummytable.pk_i_id = c.fk_i_category_id GROUP BY pk_i_id ORDER BY i_position ASC', osc_current_user_locale(), $this->language, $this->getTableName(), DB_TABLE_PREFIX, $sql, DB_TABLE_PREFIX));
            return $result->result();
        }
        
        /**
         * List all enabled categories
         * 
         * @access public
         * @since unknown
         * @return array 
         */
        public function listEnabled() {
            return $this->listWhere('a.b_enabled = 1');
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
                return null;
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
        public function listAll() {
            return $this->listWhere('1 = 1');
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
            $tree = null;
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
         * This overwrite findByPrimaryKey of DAO model because we store the categories on an array for the tree and it's faster than a SQL query
         * 
         * @access public
         * @since unknown
         * @param integer$pk primary key
         * @return array 
         */
        public function findByPrimaryKey($pk) {
            if($pk!=null) {
                if(array_key_exists($pk, $this->categories)){
                    return $this->categories[$pk];
                } else {
                    return null;
                }
            } else {
                return null;
            }
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
         * @param integer$pk primary key
         */
        public function updateByPrimaryKey($fields, $aFieldsDescription, $pk) {
            //UPDATE for category
            $set = "";
            foreach ($fields as $key => $value) {
                if ($set != "")
                    $set .= ", ";
                $set .= $key . ' = ' . $this->formatValue($value);
            }
            $sql = 'UPDATE ' . $this->tableName . ' SET ' . $set . " WHERE pk_i_id = " . $pk;
            $this->dao->query($sql);

            foreach ($aFieldsDescription as $k => $fieldsDescription) {
                //UPDATE for description of categories
                $fieldsDescription['fk_i_category_id'] = $pk;
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
                $set = "";
                foreach ($fieldsDescription as $key => $value) {
                    if ($set != "")
                        $set .= ", ";
                    $set .= $key . " = " . $this->formatValue($value);

                }

                $sql = 'UPDATE ' . DB_TABLE_PREFIX . 't_category_description SET ' . $set . " WHERE fk_i_category_id = " . $pk . " AND fk_c_locale_code = '" . $fieldsDescription["fk_c_locale_code"] . "'";


                $rs = $this->dao->query($sql);

                if($rs->numRows == 0) {
                    $rows = $this->dao->query("SELECT * FROM %s as a INNER JOIN %st_category_description as b ON a.pk_i_id = b.fk_i_category_id WHERE a.pk_i_id = '%s' AND b.fk_c_locale_code = '%s'", $this->tableName, DB_TABLE_PREFIX, $pk, $k);
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
            $columns = implode(', ', array_keys($fields));

            $set = "";
            foreach ($fields as $value) {
                if ($set != "")
                    $set .= ", ";
                $set .= $this->formatValue($value);
            }
            $sql = 'INSERT INTO ' . $this->tableName . ' (' . $columns . ') VALUES (' . $set . ')';

            $this->dao->query($sql);
            $category_id = $this->conn->get_last_id() ;

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
                $columns = implode(', ', array_keys($fieldsDescription));

                $set = "";
                foreach ($fieldsDescription as $value) {
                    if ($set != "")
                        $set .= ", ";
                    $set .= $this->formatValue($value);
                }
                $sql = 'INSERT INTO ' . DB_TABLE_PREFIX . 't_category_description (' . $columns . ') VALUES (' . $set . ')';
                $this->dao->query($sql);
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
         * @return bool on success
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
         * @return bool on success
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

        
        
        
        
        
    }
?>