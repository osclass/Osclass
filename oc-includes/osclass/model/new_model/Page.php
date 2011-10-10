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
     * Page DAO
     */
    class Page extends DAO
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
            $this->set_table_name('t_pages') ;
            $this->set_primary_key('pk_i_id') ;
            $array_fields = array(
                's_internal_name',
                'b_indelible',
                'dt_pub_date', 
                'dt_mod_date', 
                'i_order');
            $this->set_fields($array_fields) ;
        }
        
        /**
         * Find a page by page id.
         *
         * @param int $id Page id.
         * @param string $locale By default is null but you can specify locale code.
         * @return array Page information. If there's no information, return an empty array.
         */
        function findByPrimaryKey($id, $locale = null)
        {
            $result = $this->dao->findbyPrimaryKey($id) ;
            $row = $result->row() ;

            if(is_null($row)) {
                return array();
            }

            // page_description
            $this->dao->select() ;
            $this->dao->from(DB_TABLE_PREFIX.'t_pages_description') ;
            $this->dao->where('fk_i_pages_id', $id) ;
            if(!is_null($locale)) {
                $this->dao->where('fk_c_locale_code', $locale) ;
            }
            $result = $this->dao->get() ;
            $sub_rows = $result->result() ;

            $row['locale'] = array();
            foreach($sub_rows as $sub_row) {
                $row['locale'][$sub_row['fk_c_locale_code']] = $sub_row;
            }

            return $row;
        }
        
        /**
         * Find a page by internal name.
         *
         * @param string $intName Internal name of the page to find.
         * @param string $locale Locale string.
         * @return array It returns page fields. If it has no results, it returns an empty array.
         */
        function findByInternalName($intName, $locale = null)
        {
            $this->dao->select() ;
            $this->dao->from($this->table_name) ;
            $this->dao->where('s_internal_name', $intName) ;
            $result = $this->dao->get() ;
            
            $row = array() ;
            if( $result == false ) {
                return false ;
            } else {
                if($result->num_rows == 0){
                    return array() ;
                }else{
                    $row = $result->row() ;
                    $result = $this->extendDescription($row, $locale) ;
                    return $result ;
                }
            }
        }
        
        /**
         * Find a page by order.
         *
         * @param int order
         * @return array It returns page fields. If it has no results, it returns an empty array.
         */
        function findByOrder($order, $locale = null)
        {
            $this->dao->select() ;
            $this->dao->from($this->table_name) ;
            $array_where = array(
                'i_order'       => $order,
                'b_indelible'   => 0
            );
            $this->dao->where($array_where) ;
            $result = $this->dao->get() ;
            
            if($result == false){
                return array();
            } else {
                if($result->num_rows == 0) {
                    return array();
                }
                $row = $result->row() ;
                $result = $this->extendDescription($row, $locale);
                return $result;
            }
        }
        
        
    }
?>