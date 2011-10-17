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
     * Model database for City table
     * 
     * @package OSClass
     * @subpackage Model
     * @since unknown
     */
    class Field extends DAO
    {
        /**
         * It references to self object: City.
         * It is used as a singleton
         * 
         * @access private
         * @since unknown
         * @var Field 
         */
        private static $instance ;

        /**
         * It creates a new Field object class ir if it has been created
         * before, it return the previous object
         * 
         * @access public
         * @since unknown
         * @return Field
         */
        public static function newInstance()
        {
            if( !self::$instance instanceof self ) {
                self::$instance = new self ;
            }
            return self::$instance ;
        }

        /**
         * Set data related to t_meta_fields table
         */
        function __construct()
        {
            parent::__construct();
            $this->setTableName('t_meta_fields') ;
            $this->setPrimaryKey('pk_i_id') ;
            $this->setFields( array('pk_i_id', 's_name', 'e_type', 'b_required', 's_slug', 's_options') ) ;
        }

        /**
         * Find a field by its id.
         *
         * @param int $id
         * @return array Field information. If there's no information, return an empty array.
         */
        public function findByPrimaryKey($id)
        {
            $this->dao->select() ;
            $this->dao->from($this->getTableName()) ;
            $this->dao->where('pk_i_id', $id) ;

            $result = $this->dao->get() ;

            if( $result == false ) {
                return array() ;
            }

            return $result->row() ;
        }

        /**
         * Find a field by its name
         *
         * @param string $id
         * @return array Field information. If there's no information, return an empty array.
         */
        public function findByCategory($id)
        {
            $this->dao->select('mf.*') ;
            $this->dao->from(sprintf('%st_meta_fields mf, %st_meta_categories mc', DB_TABLE_PREFIX, DB_TABLE_PREFIX)) ;
            $this->dao->where('mc.fk_i_category_id', $id) ;
            $this->dao->where('mf.pk_i_id = mc.fk_i_field_id');

            $result = $this->dao->get() ;

            if( $result == false ) {
                return array() ;
            }

            return $result->result() ;
        }

        /**
         * Find fields from a category and an item
         *
         * @param string $id
         * @return array Field information. If there's no information, return an empty array.
         */
        public function findByCategoryItem($catId, $itemId)
        {
            $result = $this->dao->query(sprintf("SELECT query.*, im.s_value as s_value FROM (SELECT mf.* FROM %st_meta_fields mf, %st_meta_categories mc WHERE mc.fk_i_category_id = %d AND mf.pk_i_id = mc.fk_i_field_id) as query LEFT JOIN %st_item_meta im ON im.fk_i_field_id = query.pk_i_id AND im.fk_i_item_id = %d", DB_TABLE_PREFIX, DB_TABLE_PREFIX, $catId, DB_TABLE_PREFIX, $itemId));

            if( $result == false ) {
                return array() ;
            }

            return $result->row() ;
        }

        /**
         * Find a field by its name
         *
         * @param string $name
         * @return array Field information. If there's no information, return an empty array.
         */
        public function findByName($name)
        {
            $this->dao->select() ;
            $this->dao->from($this->getTableName()) ;
            $this->dao->where('s_name', $name) ;

            $result = $this->dao->get() ;

            if( $result == false ) {
                return array() ;
            }

            return $result->row();
        }

        /**
         * Find a field by its name
         *
         * @param string $slug
         * @return array Field information. If there's no information, return an empty array.
         */
        public function findBySlug($slug)
        {
            $this->dao->select() ;
            $this->dao->from($this->getTableName()) ;
            $this->dao->where('s_slug', $slug) ;

            $result = $this->dao->get() ;

            if( $result == false ) {
                return array() ;
            }

            return $result->row();
        }

        /**
         * Gets which categories are associated with that field
         *
         * @param string $id
         * @return array
         */
        public function categories($id)
        {
            $this->dao->select('fk_i_category_id') ;
            $this->dao->from(sprintf('%st_meta_categories', DB_TABLE_PREFIX)) ;
            $this->dao->where('fk_i_field_id', $id) ;

            $result = $this->dao->get() ;

            if( $result == false ) {
                return array() ;
            }

            $categories = $result->result();
            $cats = array();
            foreach($categories as $k => $v) {
                $cats[] = $v['fk_i_category_id'];
            }
            return $cats;
        }

        /**
         * Insert a new field
         * 
         * @param type $name
         * @param type $type
         * @param type $categories 
         */
        public function insertField($name, $type, $slug, $required, $options, $categories = null) {
            $this->dao->insert($this->getTableName(), array("s_name" => $name, "e_type" =>$type, "b_required" => $required, "s_slug" => $slug, 's_options' => $options));
            $id = $this->conn->get_last_id();
            if($slug=='') {
                $this->dao->update($this->getTableName(), array('s_slug' => $id), array('pk_i_id' => $id));
            }
            foreach($categories as $c) {
                $this->dao->insert(sprint('%st_meta_categories', DB_TABLE_PREFIX), array('fk_i_category_id' => $c, 'fk_i_field_id' =>$id));
            }
        }
        
        
        public function insertCategories($id, $categories = null) {
            if($categories!=null) {
                foreach($categories as $c) {
                    $this->dao->insert(sprint('%st_meta_categories', DB_TABLE_PREFIX), array('fk_i_category_id' => $c, 'fk_i_field_id' =>$id));
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
            return $this->dao->delete(sprint('%st_meta_categories', DB_TABLE_PREFIX), array('fk_i_field_id' =>$id));
        }
        
        public function replace($itemId, $field, $value) {
            return $this->dao->replace(sprintf('%st_item_meta', DB_TABLE_PREFIX), array('fk_i_item_id' => $itemId, 'fk_i_field_id' => $field, 's_value' => $value));
        }

        public function deleteByPrimaryKey($id) {
            $this->dao->delete(sprint('%st_item_meta', DB_TABLE_PREFIX), array('fk_i_field_id' =>$id));
            $this->dao->delete(sprint('%st_meta_categories', DB_TABLE_PREFIX), array('fk_i_field_id' =>$id));
            return $this->dao->delete($this->getTableName(), array('pk_i_id' =>$id));
        }

    }

    /* file end: ./oc-includes/osclass/model/new_model/City.php */
?>