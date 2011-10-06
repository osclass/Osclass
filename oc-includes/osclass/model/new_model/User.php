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
     * User DAO
     */
    class User extends DAO
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
            $this->set_table_name('t_user') ;
            $this->set_primary_key('pk_i_id') ;
            $array_fields = array(
                'dt_reg_date',
                'dt_mod_date',
                's_name',
                's_password',
                's_secret',
                's_email',
                's_website',
                's_phone_land',
                's_phone_mobile',
                'b_enabled',
                'b_active',
                's_pass_code',
                's_pass_date',
                's_pass_question',
                's_pass_answer',
                's_pass_ip',
                'fk_c_country_code',
                's_country',
                's_address',
                's_zip',
                'fk_i_region_id',
                's_region',
                'fk_i_city_id',
                's_city',
                'fk_i_city_area_id',
                's_city_area',
                'd_coord_lat',
                'd_coord_long',
                'i_permissions',
                'b_company',
                'i_items',
                'i_comments'
            );
            $this->set_fields($array_fields) ;
        }
        
        /**
         * 
         *
         * @param integer $id
         * @param string $locale
         * @return array
         */
        public function findByPrimaryKey($id, $locale = null)
        {   
            $this->dao->select() ;
            $this->dao->from($this->table_name) ;
            $this->dao->where($this->primary_key, $id) ;
            $result = $this->dao->get();
            $row = $result->row() ;

            if(is_null($row)) {
                return array();
            }

            $this->dao->select() ;
            $this->dao->from(DB_TABLE_PREFIX.'t_user_description') ;
            $this->dao->where('fk_i_user_id', $id) ;
            if(!is_null($locale)) {
                $this->dao->where('fk_c_locale_code', $locale) ;
            }
            $result = $this->dao->get() ;
            $descriptions = $result->result() ;

            $row['locale'] = array();
            foreach($sub_rows as $sub_row) {
                $row['locale'][$sub_row['fk_c_locale_code']] = $sub_row;
            }

            return $row;
        }
        
        /**
         *
         * @param string $email
         * @return array
         */
        public function findByEmail($email)
        {
            $this->dao->select() ;
            $this->dao->from($this->table_name) ;
            $result = $this->dao->where('s_email', $email) ;
            
            if( $result == false ) {
                return false;
            } else if($result->num_rows == 1){
                return $result->row() ;
            } else {
                return array();
            }
        }
        
        /**
         * 
         *
         * @param string $key
         * @param string $password
         * @return array
         */
        public function findByCredentials($key, $password)
        {
            $this->dao->select() ;
            $this->dao->from($this->table_name) ;
            $conditions = array(
                's_email'   => $key,
                's_password'=> sha1($password)
            );
            $this->dao->where($conditions) ;
            $result = $this->dao->get() ;
            
            if( $result == false ) {
                return false;
            } else if($result->num_rows == 1){
                return $result->row() ;
            } else {
                return array();
            }
        }
        
        /**
         * 
         *
         * @param string $id
         * @param string $secret 
         */
        public function findByIdSecret($id, $secret)
        {
            $this->dao->select() ;
            $this->dao->from($this->table_name) ;
            $conditions = array(
                'pk_i_id'  => $id,
                's_secret' => $secret
            );
            $this->dao->where($conditions) ;
            $result = $this->dao->get() ;
            
            if( $result == false ) {
                return false;
            } else if($result->num_rows == 1){
                return $result->row() ;
            } else {
                return array();
            }
        }
        
        /**
         * 
         *
         * @param string $id
         * @param string $secret
         * @return array
         */
        public function findByIdPasswordSecret($id, $secret)
        {
            if($secret=='') { return null; }
            $date = date("Y-m-d H:i:s", (time()-(24*3600)));
            $this->dao->select() ;
            $this->dao->from($this->table_name) ;
            $conditions = array(
                'pk_i_id'       => $id,
                's_pass_code'   => $secret
            );
            $this->dao->where($conditions) ;
            $this->dao->where("s_pass_date >= '$date'");
            $result = $this->dao->get() ;
            
           if( $result == false ) {
                return false;
            } else if($result->num_rows == 1){
                return $result->row() ;
            } else {
                return array();
            }
        }
        
        /**
         * 
         *
         * @param integer $id
         * @return boolean
         */
        public function deleteUser($id = null)
            {
            if($id!=null) {
                osc_run_hook('delete_user', $id);
                
                $this->dao->select('pk_i_id, fk_i_category_id');
                $this->dao->from(DB_TABLE_PREFIX."t_item") ;
                $this->dao->where('fk_i_user_id', $id) ;
                $result = $this->dao->get() ;
                $items = $result->result() ;
                
                $itemManager = Item::newInstance();
                foreach($items as $item) {
                    $itemManager->deleteByPrimaryKey($item['pk_i_id']);
                }
                
                $this->dao->delete(DB_TABLE_PREFIX.'t_user_email_tmp', array('fk_i_user_id' => $id)) ;
                $this->dao->delete(DB_TABLE_PREFIX.'t_user_description', array('fk_i_user_id', $id)) ;
                $this->dao->delete(DB_TABLE_PREFIX.'t_alerts', array('fk_i_user_id', $id)) ;
                $this->dao->delete($this->table_name, array('pk_i_id' => $id)) ;
                return true;
            }
            return false;
        }
        
        /**
         *
         * @param integer $id
         * @param string $locale
         * @param string $info
         * @return array
         */
        private function insertDescription($id, $locale, $info)
        {
            $array_set = array(
                'fk_i_user_id'      => $id,
                'fk_c_locale_code'  => $locale,
                's_info'            => $info
            );
            
            $res = $this->dao->insert(DB_TABLE_PREFIX.'t_user_description', $array_set) ;
            
            if($res) {
                return true;
            } else {
                return false;
            }
        }
        
        /**
         *
         * @param integer $id
         * @param string $locale
         * @param string $info
         * @return boolean
         */
        public function updateDescription($id, $locale, $info)
        {
            $conditions = array('fk_c_locale_code' => $locale, 'fk_i_user_id' => $id);
            $exist = $this->existDescription($conditions);

            if(!$exist) {
                $result = $this->insertDescription($id, $locale, $info);
                return $result;
            }
            
            $array_where = array(
                'fk_c_locale_code'  => $locale,
                'fk_i_user_id'      => $id
            );
            $result = $this->dao->update(DB_TABLE_PREFIX.'t_user_description', array('s_info'    => $info), $array_where) ;
            return $result;
        }
        
        /**
         *
         * @param array $conditions
         * @return boolean
         */
        public function existDescription($conditions)
        {
            $this->dao->select() ;
            $this->dao->from(DB_TABLE_PREFIX.'t_user_description') ;
            $this->dao->where($conditions) ;
            
            $result = $this->dao->get() ;
            
            if( $result == false || $result->num_rows == 0) {
                return false;
            } else {
                return true;
            }
            
            return (bool) $result;
        }
        
    }
?>