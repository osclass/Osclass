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
     * 
     */
    class Admin extends DAO
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
            $this->set_table_name('t_admin') ;
        }

        /**
         * Searches for admin information, given an email address.
         * If email not exist return false.
         *  
         * @param string $email
         * @return array  
         */
        function findByEmail($email) 
        {
            $this->dao->select('*') ;
            $this->dao->from($this->table_name) ;
            $this->dao->where('s_email', $email) ;
            $result = $this->dao->get() ;

            if( $result->num_rows == 0 ) {
                return false;
            } else {
                $row = $result->row();
                return $row;
            }
        }
        
        /**
         * Searches for admin information, given a username.
         * If admin not exist return false.
         * 
         * @param string $username
         * @return array
         */
        function findByUsername($username) 
        {
            $this->dao->select() ;
            $this->dao->from($this->table_name) ;
            $this->dao->where('s_username', $username) ;
            $result = $this->dao->get() ;
            
            if( $result->num_rows == 0 ) {
                return false;
            } else {    
                $row = $result->row();
                return $row;
            }
        }
        
        /**
         * Searches for admin information, given a username and password
         * If credential don't match return false.
         * 
         * @param string $userName
         * @param string $password
         * @return array 
         */
        function findByCredentials($userName, $password) 
        {
            $this->dao->select() ;
            $this->dao->from($this->table_name) ;
            $conditions = array( 's_username'    => $userName,
                                 's_password' => sha1($password) );
            $this->dao->where($conditions);
            $result = $this->dao->get();
            
            if( $result->num_rows == 0 ) {
                return false;
            } else {    
                $row = $result->row();
                return $row;
            }
        }
        
        /**
         * Searches for admin information, given a admin id and secret.
         * If credential don't match return false.
         * 
         * @param integer $id
         * @param string $secret
         * @return array
         */
        function findByIdSecret($id, $secret) 
        {
            $this->dao->select() ;
            $this->dao->from($this->table_name) ;
            $conditions = array( 'pk_i_id'  => $id,
                                 's_secret' => $secret);
            $this->dao->where($conditions);
            $result = $this->dao->get();
            
            if( $result->num_rows == 0 ) {
                return false;
            } else {    
                $row = $result->row();
                return $row;
            }
        }
        
        
//        function updateArray($admin) {
//            $admin['name']      = addslashes($admin['name']);
//            $admin['userName']  = addslashes($admin['userName']);
//            $admin['email']     = addslashes($admin['email']);
//            $admin['password']  = addslashes($admin['password']);
//            
//            $this->dao->up();
//            $this->conn->osc_dbExec("UPDATE %s SET s_name = '%s', s_username = '%s', s_email = '%s', s_password = '%s' WHERE pk_i_id = %d", $this->getTableName(),
//                $admin['name'], $admin['userName'], $admin['email'], $admin['password'], $admin['id']);
//        }
    }

    /* file end: ./oc-includes/osclass/model/new_model/Admin.php */
?>