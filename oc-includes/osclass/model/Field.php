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

    class Field extends DAO
    {
        /**
         * The columns defined in page table.
         *
         * @access private
         * @var array
         */
        private $columns;

        private static $instance ;

        public function __construct() {
            parent::__construct();

            $this->columns      = array('pk_i_id', 's_name', 'e_type');
        }

        public static function newInstance() {
            if(!self::$instance instanceof self) {
                self::$instance = new self ;
            }
            return self::$instance ;
        }

        /**
         * Return's the name of the table.
         *
         * @return string table name.
         */
        public function getTableName()
        {
            return DB_TABLE_PREFIX . 't_meta_fields';
        }


        /**
         * Find a field by its id.
         *
         * @param int $id
         * @return array Field information. If there's no information, return an empty array.
         */
        public function findByPrimaryKey($id)
        {
            return  $this->conn->osc_dbFetchResult("SELECT * FROM %st_meta_fields WHERE pk_i_id = %d", DB_TABLE_PREFIX, $id);
        }

        /**
         * Find a field by its name
         *
         * @param string $id
         * @return array Field information. If there's no information, return an empty array.
         */
        public function findByCategory($id)
        {
            return  $this->conn->osc_dbFetchResults("SELECT mf.* FROM %st_meta_fields mf, %st_meta_categories mc WHERE mc.fk_i_category_id = %d AND mf.pk_i_id = mc.fk_i_field_id", DB_TABLE_PREFIX, DB_TABLE_PREFIX, $id);
        }

        /**
         * Find fields from a category and an item
         *
         * @param string $id
         * @return array Field information. If there's no information, return an empty array.
         */
        public function findByCategoryItem($catId, $itemId)
        {
            return  $this->conn->osc_dbFetchResults("SELECT query.*, im.s_value as s_value FROM (SELECT mf.* FROM %st_meta_fields mf, %st_meta_categories mc WHERE mc.fk_i_category_id = %d AND mf.pk_i_id = mc.fk_i_field_id) as query LEFT JOIN %st_item_meta im ON im.fk_i_field_id = query.pk_i_id AND im.fk_i_item_id = %d", DB_TABLE_PREFIX, DB_TABLE_PREFIX, $catId, DB_TABLE_PREFIX, $itemId);
        }

        /**
         * Find a field by its name
         *
         * @param string $name
         * @return array Field information. If there's no information, return an empty array.
         */
        public function findByName($name)
        {
            return  $this->conn->osc_dbFetchResult("SELECT * FROM %st_meta_fields WHERE s_name = '%s'", DB_TABLE_PREFIX, $name);
        }

        /**
         * Find a field by its name
         *
         * @param string $slug
         * @return array Field information. If there's no information, return an empty array.
         */
        public function findBySlug($slug)
        {
            return  $this->conn->osc_dbFetchResult("SELECT * FROM %st_meta_fields WHERE s_slug = '%s'", DB_TABLE_PREFIX, $slug);
        }

        /**
         * Gets which categories are associated with that field
         *
         * @param string $id
         * @return array
         */
        public function categories($id)
        {
            $categories = $this->conn->osc_dbFetchResults("SELECT fk_i_category_id FROM %st_meta_categories WHERE fk_i_field_id = %d", DB_TABLE_PREFIX, $id);
            $cats = array();
            foreach($categories as $k => $v) {
                $cats[] = $v['fk_i_category_id'];
            }
            return $cats;
        }

        /**
         * Get all the fields
         *
         * @return array Return all the fields
         */
        public function listAll()
        {
            return $this->conn->osc_dbFetchResults("SELECT * FROM %st_meta_fields", DB_TABLE_PREFIX);
        }

        
        /**
         * Insert a new field
         * 
         * @param type $name
         * @param type $type
         * @param type $categories 
         */
        public function insertField($name, $type, $slug, $required, $categories = null) {
            $this->insert(array("s_name" => $name, "e_type" =>$type, "b_required" => $required, "s_slug" => $slug));
            $id = $this->conn->get_last_id();
            if($slug=='') {
                $this->conn->update(array('s_slug' => $id), array('pk_i_id' => $id));
            }
            foreach($categories as $c) {
                $this->conn->osc_dbExec("INSERT INTO %st_meta_categories ( `fk_i_category_id`, `fk_i_field_id` ) VALUES ('%d', '%d')", DB_TABLE_PREFIX, $c, $id);
            }
        }
        
        
        public function insertCategories($id, $categories = null) {
            if($categories!=null) {
                foreach($categories as $c) {
                    $this->conn->osc_dbExec("INSERT INTO %st_meta_categories ( `fk_i_category_id`, `fk_i_field_id` ) VALUES ('%d', '%d')", DB_TABLE_PREFIX, $c, $id);
                    $subcategories = Category::newInstance()->findSubcategories($c);
                    if(count($subcategories)>0) {
                        foreach($subcategories as $k => $v) {
                            $this->insertCategories($id, array($v['pk_i_id']));
                        }
                    }
                }
            }
        }
        
        public function cleanCategoriesFromField($id) {
            return $this->conn->osc_dbExec("DELETE FROM %st_meta_categories WHERE fk_i_field_id = %d", DB_TABLE_PREFIX, $id);
        }
        
        public function replace($itemId, $field, $value) {
            return $this->conn->osc_dbExec("REPLACE INTO %st_item_meta ( `fk_i_item_id`, `fk_i_field_id`, `s_value` ) VALUES ('%d', '%d', '%s')", DB_TABLE_PREFIX, $itemId, $field, $value);
        }

        public function deleteByPrimaryKey($id) {
            $this->conn->osc_dbExec("DELETE FROM %st_item_meta WHERE fk_i_field_id = '%d'", DB_TABLE_PREFIX, $id);
            $this->conn->osc_dbExec("DELETE FROM %st_meta_categories WHERE fk_i_field_id = '%d'", DB_TABLE_PREFIX, $id);
            $this->conn->osc_dbExec("DELETE FROM %st_meta_fields WHERE pk_i_id = '%d'", DB_TABLE_PREFIX, $id);
        }
        
    }

?>
