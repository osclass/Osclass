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
     * Admin DAO
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
            $this->setTableName('t_admin') ;
        }

        /**
         * Searches for admin information, given an email address.
         * If email not exist return false.
         * 
         * @access public
         * @since unknown
         * @param string $email
         * @return array
         */
        function findByEmail($email) 
        {
            $this->dao->select() ;
            $this->dao->from($this->getTableName()) ;
            $this->dao->where('s_email', $email) ;
            $result = $this->dao->get() ;

            if( $result->numRows == 0 ) {
                return false ;
            }

            return $result->row() ;
        }
        
        /**
         * Searches for admin information, given a username.
         * If admin not exist return false.
         * 
         * @access public
         * @since unknown
         * @param string $username
         * @return array
         */
        function findByUsername($username) 
        {
            $this->dao->select() ;
            $this->dao->from($this->getTableName()) ;
            $this->dao->where('s_username', $username) ;
            $result = $this->dao->get() ;
            
            if( $result->numRows == 0 ) {
                return false ;
            }

            return $result->row() ;
        }
        
        /**
         * Searches for admin information, given a username and password
         * If credential don't match return false.
         * 
         * @access public
         * @since unknown
         * @param string $userName
         * @param string $password
         * @return array 
         */
        function findByCredentials($userName, $password) 
        {
            $this->dao->select() ;
            $this->dao->from($this->getTableName()) ;
            $conditions = array( 's_username' => $userName,
                                 's_password' => sha1($password) );
            $this->dao->where($conditions);
            $result = $this->dao->get();

            if( $result->numRows == 0 ) {
                return false ;
            }

            return $result->row() ;
        }
        
        /**
         * Searches for admin information, given a admin id and secret.
         * If credential don't match return false.
         * 
         * @access public
         * @since unknown
         * @param integer $id
         * @param string $secret
         * @return array
         */
        function findByIdSecret($id, $secret) 
        {
            $this->dao->select() ;
            $this->dao->from($this->getTableName()) ;
            $conditions = array( 'pk_i_id'  => $id,
                                 's_secret' => $secret);
            $this->dao->where($conditions);
            $result = $this->dao->get();

            if( $result->numRows == 0 ) {
                return false ;
            }

            return $result->row() ;
        }
    }

    /* file end: ./oc-includes/osclass/model/new_model/Admin.php */
?>