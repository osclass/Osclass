<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

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

    class Category extends DAO
    {
        private static $instance ;
        private $language ;
        private $tree;
        private $categories;
        private $relation;
        private $empty_tree;

        public function __construct($l = "") {
            if($l == "") {
                $l = osc_current_user_locale() ;
            }

            $this->language = $l ;
            $this->tree = null;
            $this->relation = null;
            $this->categories = null;
            parent::__construct() ;
            $this->empty_tree = true;
            $this->toTree();
        }

        public static function newInstance() {
            if(!self::$instance instanceof self) {
                self::$instance = new self ;
            }
            return self::$instance ;
        }

        public function getTableName() {
            return DB_TABLE_PREFIX . 't_category' ;
        }

        public function getTableDescriptionName() {
            return DB_TABLE_PREFIX . 't_category_description';
        }

        public function getTableStats() {
            return DB_TABLE_PREFIX . 't_category_stats';
        }

        public function getTableItemName() {
            return DB_TABLE_PREFIX . 't_item' ;
        }

        public function findRootCategories() {
            $roots = $this->listWhere("a.fk_i_parent_id IS NULL") ;
            return $roots ;
        }

        public function findRootCategoriesEnabled() {
            $roots = $this->listWhere("a.fk_i_parent_id IS NULL AND a.b_enabled = 1") ;
            return $roots ;
        }

        /*public function toSubTree($category = null) {
            if($category==null) {
                return null ;
            } else {
                $branches = $this->listWhere("a.fk_i_parent_id = ".$category." AND a.b_enabled = 1 ") ;
                foreach($branches as &$branch) {
                    $branch['categories'] = $this->toSubTree($branch['pk_i_id']) ;
                }
                unset($branch) ;
                print_r($branches);
                return $branches ;
            }
        }*/

        /*public function toSubTreeAll($category = null) {
            if($category==null) {
                return null ;
            } else {
                $branches = $this->listWhere("a.fk_i_parent_id = ".$category."") ;
                foreach($branches as &$branch) {
                    $branch['categories'] = $this->toSubTree($branch['pk_i_id']) ;
                }
                unset($branch) ;
                return $branches ;
            }
        }

        public function toTree() {
            $roots = $this->findRootCategoriesEnabled() ;
            foreach ($roots as &$r) {
                $r['categories'] = $this->toSubTree($r['pk_i_id']) ;
            }
            unset($r) ;
            return $roots ;
        }

        public function toTreeAll() {
            $roots = $this->findRootCategories();
            foreach ($roots as &$r) {
                $r['categories'] = $this->toSubTreeAll($r['pk_i_id']);
            }
            unset($r);
            return $roots;
        }*/
        
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
            };
        }

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


        public function toRootTree($cat = null) {
            $tree = null;
            if($cat!=null) {
                $tree_b = array();
                if(ctype_digit($cat)) {
                    $cat = $this->findByPrimaryKey($cat);
                } else {
                    $cat = $this->find_by_slug($cat);
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

        public function isParentOf($parent_id) {
            $children = $this->listWhere("a.fk_i_parent_id = " . $parent_id . "");
            return $children;
        }

        public function findRootCategory($category_id) {
            $results = $this->listWhere("a.pk_i_id = " . $category_id . " AND a.fk_i_parent_id IS NOT NULL");
            if (count($results) > 0) {
                return $this->findRootCategory($results['fk_i_parent_id']);
            } else {
                return $this->findByPrimaryKey($category_id);
            }
        }

        //#DANI: NOT CHANGED YET
        public function find_by_slug($slug) {
            $results = $this->listWhere("b.s_slug = '" . $slug . "'");
            if(isset($results[0])) {
                return $results[0];
            }
            return null;
        }

        public function hierarchy($category_id) {
            $hierarchy = array();
            $cat = $this->findByPrimaryKey($category_id);


            if($cat!=null) {
                while (true) {
                    $hierarchy[] = array('pk_i_id' => $cat['pk_i_id'], 's_name' => $cat['s_name'], 's_slug' => $cat['s_slug']);
                    $cat = $this->findByPrimaryKey($cat['fk_i_parent_id']);
                    if(count($cat)<=0) { return $hierarchy; };
                }
            }
            return $hierarchy;
        }

        public function is_root($category_id) {
            $results = parent::listWhere("pk_i_id = " . $category_id . " AND fk_i_parent_id IS NULL");
            if (count($results) > 0) {
                return true;
            } else {
                return false;
            }
        }

        public function findSubcategories($cat_id) {
            return $this->listWhere("fk_i_parent_id = %d", $cat_id);
        }

        //overwritten
        public function listAll() {
            return $this->listWhere('1 = 1');
        }

        public function listEnabled() {
            return $this->listWhere('a.b_enabled = 1');
        }

        public function findByPrimaryKey($pk, $lang = true) {
            if($pk!=null) {
                $data = $this->categories[$pk];
                if(isset($data)) {
                    $sub_rows = $this->conn->osc_dbFetchResults('SELECT * FROM %s WHERE fk_i_category_id = %s ORDER BY fk_c_locale_code', $this->getTableDescriptionName(), $data['pk_i_id']);
                    $row = array();
                    foreach ($sub_rows as $sub_row) {
                        $row[$sub_row['fk_c_locale_code']] = $sub_row;
                    }
                    $data['locale'] = $row;
                    return $data;
                } else {
                    return null;
                }
            } else {
                return null;
            }
        }

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

            return $this->conn->osc_dbFetchResults('SELECT * FROM (SELECT *, FIELD(b.fk_c_locale_code, \'en_US\', \''.$this->language.'\') as sorter FROM %s as a INNER JOIN %s as b ON a.pk_i_id = b.fk_i_category_id WHERE b.s_name != \'\' AND %s  ORDER BY sorter DESC, a.i_position DESC) dummytable LEFT JOIN %st_category_stats as c ON dummytable.pk_i_id = c.fk_i_category_id GROUP BY pk_i_id ORDER BY i_position ASC', $this->getTableName(), $this->getTableDescriptionName(), $sql, DB_TABLE_PREFIX);
        }

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
            
            $this->conn->osc_dbExec("DELETE FROM %st_plugin_category WHERE fk_i_category_id = '" . $pk . "'", DB_TABLE_PREFIX);
            $this->conn->osc_dbExec("DELETE FROM %s WHERE fk_i_category_id = '" . $pk . "'", $this->getTableDescriptionName());
            $this->conn->osc_dbExec("DELETE FROM %s WHERE fk_i_category_id = '" . $pk . "'", $this->getTableStats());
            $this->conn->osc_dbExec("DELETE FROM %s WHERE pk_i_id = '" . $pk . "'", $this->getTableName());
        }

        public function updateByPrimaryKey($fields, $aFieldsDescription, $pk) {
            //UPDATE for category
            $set = "";
            foreach ($fields as $key => $value) {
                if ($set != "")
                    $set .= ", ";
                $set .= $key . ' = ' . $this->formatValue($value);
            }
            $sql = 'UPDATE ' . $this->getTableName() . ' SET ' . $set . " WHERE pk_i_id = " . $pk;
            $this->conn->osc_dbExec($sql);

            foreach ($aFieldsDescription as $k => $fieldsDescription) {
                //UPDATE for description of categories
                $fieldsDescription['fk_i_category_id'] = $pk;
                $fieldsDescription['fk_c_locale_code'] = $k;
                $slug_tmp = $slug = osc_sanitizeString(osc_apply_filter('slug', $fieldsDescription['s_name']));
                $slug_unique = 1;
                while(true) {
                    if(!$this->find_by_slug($slug)) {
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

                $sql = 'UPDATE ' . $this->getTableDescriptionName() . ' SET ' . $set . " WHERE fk_i_category_id = " . $pk . " AND fk_c_locale_code = '" . $fieldsDescription["fk_c_locale_code"] . "'";


                $this->conn->osc_dbExec($sql);

                if($this->conn->get_affected_rows() == 0) {
                    $rows = $this->conn->osc_dbFetchResult("SELECT * FROM %s as a INNER JOIN %s as b ON a.pk_i_id = b.fk_i_category_id WHERE a.pk_i_id = '%s' AND b.fk_c_locale_code = '%s'", $this->getTableName(), $this->getTableDescriptionName(), $pk, $k);
                    if(count($rows) == 0) {
                        $this->insert_description($fieldsDescription);
                    }
                }
            }
        }

        public function insert($fields, $aFieldsDescription = null )
        {
            $columns = implode(', ', array_keys($fields));

            $set = "";
            foreach ($fields as $value) {
                if ($set != "")
                    $set .= ", ";
                $set .= $this->formatValue($value);
            }
            $sql = 'INSERT INTO ' . $this->getTableName() . ' (' . $columns . ') VALUES (' . $set . ')';

            $this->conn->osc_dbExec($sql);
            $category_id = $this->conn->get_last_id() ;

            foreach ($aFieldsDescription as $k => $fieldsDescription) {
                $fieldsDescription['fk_i_category_id'] = $category_id;
                $fieldsDescription['fk_c_locale_code'] = $k;
                //$fieldsDescription['s_slug'] = osc_sanitizeString(osc_apply_filter('slug', $fieldsDescription['s_name']));
                $slug_tmp = $slug = osc_sanitizeString(osc_apply_filter('slug', $fieldsDescription['s_name']));
                $slug_unique = 1;
                while(true) {
                    if(!$this->find_by_slug($slug)) {
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
                $sql = 'INSERT INTO ' . $this->getTableDescriptionName() . ' (' . $columns . ') VALUES (' . $set . ')';
                $this->conn->osc_dbExec($sql);
            }

            return $category_id;
        }

        public function insert_description($fields_description) {
            if (!empty($fields_description['s_name'])) {
                $columns = implode(', ', array_keys($fields_description));

                $set = "";
                foreach ($fields_description as $value) {
                    if ($set != "")
                        $set .= ", ";
                    $set .= "'$value'";
                }
                $sql = 'INSERT INTO ' . $this->getTableDescriptionName() . ' (' . $columns . ') VALUES (' . $set . ')';
                $this->conn->osc_dbExec($sql);
            }
        }

        public function update_order($pk_i_id, $order) {
            $sql = 'UPDATE ' . $this->getTableName() . " SET `i_position` = '".$order."' WHERE `pk_i_id` = " . $pk_i_id;
            return $this->conn->osc_dbExec($sql);
        }

        public function update_name($pk_i_id, $locale, $name) {
            $sql = 'UPDATE ' . $this->getTableDescriptionName() . " SET `s_name` = '".$name."' WHERE `fk_i_category_id` = " . $pk_i_id . " AND `fk_c_locale_code` = '" . $locale . "'";
            return $this->conn->osc_dbExec($sql);
        }

    }

?>
