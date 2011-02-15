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

class Category extends DAO
{
    private $language;

    public function __construct($l = "") {
    if($l == "") {
            if(isset($_SESSION['locale'])) {
                $l = $_SESSION['locale'];
            } else {
                $l = osc_language() ;
            }
        }
        
        $this->language = $l;
        parent::__construct() ;
    }

    public static function newInstance($l = "") {
        return new Category($l);
    }

    public function getTableName() {
        return DB_TABLE_PREFIX . 't_category' ;
    }

    public function getTableDescriptionName() {
        return DB_TABLE_PREFIX . 't_category_description';
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

    public function toSubTree($category = null) {
        if($category==null) {
            return null ;
        } else {
            $branches = $this->listWhere("a.fk_i_parent_id = ".$category." AND a.b_enabled = 1 ") ;
            foreach($branches as &$branch) {
                $branch['categories'] = $this->toSubTree($branch['pk_i_id']) ;
            }
            unset($branch) ;
            return $branches ;
        }
    }

    public function toSubTreeAll($category = null) {
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
            $r['categories'] = $this->toSubTree($r['pk_i_id']);//$this->listWhere("a.fk_i_parent_id = " . $r['pk_i_id'] . "");
        }
        unset($r);
        return $roots;
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

    public function findSubcategories($cat_id, $withads = false) {
        if (!$withads) {
            $results = $this->listWhere("fk_i_parent_id = %d", $cat_id);
        } else {
			// ( DATE_SUB ( CURDATE(), INTERVAL a.i_expiration_days DAY) <= c.dt_pub_date ) OR
			// That was on the SQL but I don't know why it failed.
            $results = $this->conn->osc_dbFetchResults("SELECT a.pk_i_id, b.s_name, count(a.pk_i_id) FROM %s as a, %s as b, %s as c WHERE " . 
                    "a.fk_i_parent_id = %d AND a.pk_i_id = c.fk_i_category_id AND b.fk_i_category_id = c.fk_i_category_id AND " .
                    "(  a.i_expiration_days = 0 ) GROUP BY b.s_name ORDER BY a.i_position DESC",
                    $this->getTableName(),
                    $this->getTableDescriptionName(),
                    $this->getTableItemName(),
                    $cat_id
            );
        }

        return ($results);
    }

    //overwritten
    public function listAll() {
        return $this->listWhere('1 = 1');
        //OLD
        //return $this->conn->osc_dbFetchResults("SELECT * FROM %s as a INNER JOIN %s as b ON a.pk_i_id = b.fk_i_category_id WHERE b.fk_c_locale_code = '%s' ORDER BY a.i_position DESC", $this->getTableName(), $this->getTableDescriptionName(), $this->language);
    }

    public function findByPrimaryKey($pk, $lang = true) {
        if($pk!=null) {
            $data = $this->listWhere('a.pk_i_id = '.$pk);
            $data = $data[0];
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
        // OLD
        /*if( $lang ) {
            return $this->conn->osc_dbFetchResult("SELECT * FROM %s as a INNER JOIN %s as b ON a.pk_i_id = b.fk_i_category_id WHERE a.pk_i_id = '%s' AND b.fk_c_locale_code = '%s' ORDER BY i_position DESC", $this->getTableName(), $this->getTableDescriptionName(), $pk, $this->language);
        }

        $data = $this->conn->osc_dbFetchResult('SELECT * FROM %s WHERE pk_i_id = %s ORDER BY pk_i_id', $this->getTableName(), $pk);

        $sub_rows = $this->conn->osc_dbFetchResults('SELECT * FROM %s WHERE fk_i_category_id = %s ORDER BY fk_c_locale_code', $this->getTableDescriptionName(), $data['pk_i_id']);
        $row = array();
        foreach ($sub_rows as $sub_row) {
            $row[$sub_row['fk_c_locale_code']] = $sub_row;
        }
        $data['locale'] = $row;

        return $data;*/
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
        
        return $this->conn->osc_dbFetchResults('SELECT * FROM (SELECT *, FIELD(b.fk_c_locale_code, \'en_US\', \''.$this->language.'\') as sorter FROM %s as a INNER JOIN %s as b ON a.pk_i_id = b.fk_i_category_id WHERE b.s_name != \'\' AND %s  ORDER BY sorter DESC, a.i_position DESC) dummytable GROUP BY pk_i_id ORDER BY i_position ASC', $this->getTableName(), $this->getTableDescriptionName(), $sql);
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
		
        $this->conn->osc_dbExec("DELETE FROM %s WHERE fk_i_category_id = '" . $pk . "'", $this->getTableDescriptionName());
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
            $fieldsDescription['s_slug'] = osc_sanitizeString(osc_apply_filter('slug', $fieldsDescription['s_name']));
            $set = "";
            foreach ($fieldsDescription as $key => $value) {
                if ($set != "")
                    $set .= ", ";
                $set .= $key . " = '$value'";
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

    public function insert($fields, $aFieldsDescription) 
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
            $fieldsDescription['s_slug'] = osc_sanitizeString(osc_apply_filter('slug', $fieldsDescription['s_name']));
            $columns = implode(', ', array_keys($fieldsDescription));

            $set = "";
            foreach ($fieldsDescription as $value) {
                if ($set != "")
                    $set .= ", ";
                $set .= "'$value'";
            }
            $sql = 'INSERT INTO ' . $this->getTableDescriptionName() . ' (' . $columns . ') VALUES (' . $set . ')';
            $this->conn->osc_dbExec($sql);
        }
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

}
