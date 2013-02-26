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
     * Model database for Admin table
     *
     * @package Osclass
     * @subpackage Model
     * @since unknown
     */
    class Admin extends DAO
    {
        /**
         * It references to self object: Admin.
         * It is used as a singleton
         *
         * @access private
         * @since unknown
         * @var Admin
         */
        private static $instance;

        public static function newInstance()
        {
            if( !self::$instance instanceof self ) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        /**
         * Set data from t_admin table
         */
        function __construct()
        {
            parent::__construct();
            $this->setTableName('t_admin');
            $this->setPrimaryKey('pk_i_id');

            $return = $this->dao->query('SHOW COLUMNS FROM ' . $this->getTableName() . ' where Field = "b_moderator" ');

            if( $return->numRows() > 0 ) {
                $this->setFields( array('pk_i_id', 's_name', 's_username', 's_password', 's_email', 's_secret', 'b_moderator') );
            } else {
                $this->setFields( array('pk_i_id', 's_name', 's_username', 's_password', 's_email', 's_secret') );
            }
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
            $this->dao->select();
            $this->dao->from($this->getTableName());
            $this->dao->where('s_email', $email);
            $result = $this->dao->get();

            if( $result->numRows == 0 ) {
                return false;
            }

            return $result->row();
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
            $this->dao->select();
            $this->dao->from($this->getTableName());
            $this->dao->where('s_username', $username);
            $result = $this->dao->get();

            if( $result->numRows == 0 ) {
                return false;
            }

            return $result->row();
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
            $this->dao->select();
            $this->dao->from($this->getTableName());
            $conditions = array( 's_username' => $userName,
                                 's_password' => sha1($password) );
            $this->dao->where($conditions);
            $result = $this->dao->get();

            if( $result->numRows == 0 ) {
                return false;
            }

            return $result->row();
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
            $this->dao->select();
            $this->dao->from($this->getTableName());
            $conditions = array( 'pk_i_id'  => $id,
                                 's_secret' => $secret);
            $this->dao->where($conditions);
            $result = $this->dao->get();

            if( $result->numRows == 0 ) {
                return false;
            }

            return $result->row();
        }

        /**
         * Searches for admin information, given a admin id and password.
         * If credential don't match return false.
         *
         * @access public
         * @since unknown
         * @param integer $id
         * @param string $password
         * @return array
         */
        function findByIdPassword($id, $password)
        {
            $this->dao->select();
            $this->dao->from($this->getTableName());
            $conditions = array( 'pk_i_id'  => $id,
                                 's_password' => $secret);
            $this->dao->where($conditions);
            $result = $this->dao->get();

            if( $result->numRows == 0 ) {
                return false;
            }

            return $result->row();
        }

        /**
         * Perform a batch delete (for more than one admin ID)
         *
         * @access public
         * @since 2.3.4
         * @param array $id
         * @return boolean
         */
        function deleteBatch( $id )
        {
            $this->dao->from( $this->getTableName() );
            $this->dao->whereIn( 'pk_i_id', $id );
            return $this->dao->delete();
        }
    }

    /* file end: ./oc-includes/osclass/model/Admin.php */
?>