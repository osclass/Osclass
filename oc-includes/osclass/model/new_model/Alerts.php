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
     * Alerts DAO
     */
    class Alerts extends DAO
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
            $this->set_table_name('t_alerts') ;
//            $this->set_primary_key('') ; // no primary key in preference table 
//            $this->set_fields( array('s_section', 's_name', 's_value', 'e_type') ) ;
        }

        /**
         * Searches for user alerts, given an user id.
         * If user id not exist return empty array.
         *  
         * @param string $userId
         * @return array  
         */
        function getAlertsFromUser($userId) 
        {
            $this->dao->select() ;
            $this->dao->from($this->table_name) ;
            $this->dao->where('fk_i_user_id', $userId) ;
            $result = $this->dao->get() ;

            if( $result == false ) { 
                return false;
            } else {
                return $result->result();
            }
        }
        
        /**
         * Searches for user alerts, given an user id.
         * If user id not exist return empty array.
         * 
         * @param string $email
         * @return array
         */
        function getAlertsFromEmail($email) 
        {
            $this->dao->select() ;
            $this->dao->from($this->table_name) ;
            $this->dao->where('s_email', $email) ;
            $result = $this->dao->get() ;
            
            if( $result == false ) { 
                return false;
            } else {
                return $result->result();
            }
        }
        
        /**
         * Searches for alerts, given a type.
         * If type don't match return empty array.
         * 
         * @param string $type
         * @return array 
         */
        function getAlertsByType($type) 
        {
            $this->dao->select() ;
            $this->dao->from($this->table_name) ;
            $this->dao->where('e_type', $type);
            $result = $this->dao->get();
            
            if( $result == false ) { 
                return false;
            } else {
                return $result->result();
            }
        }
        
        /**
         * Searches for alerts, given a type group by s_search.
         * If type don't match return empty array.
         * 
         * @param string $type
         * @param boolean $active
         * @return array
         */
        function getAlertsByTypeGroup($type, $active = FALSE) 
        {
            $this->dao->select() ;
            $this->dao->from($this->table_name) ;
            $this->dao->where('e_type', $type);
            if($active){
                $this->dao->where('b_active', 1);
            }
            $this->dao->group_by('s_search');
            $result = $this->dao->get();
            
            if( $result == false ) { 
                return false;
            } else {
                return $result->result();
            }
        }
        
        /**
         *
         * @param string $search
         * @param string $type
         * @return array
         * 
         * OJO doble where!
         */
        function getAlertsBySearchAndType($search, $type)
        {
            $this->dao->select();
            $this->dao->from($this->table_name);
            $conditions = array('e_type'    => $type,
                                's_search'  => $search);
            $this->dao->where('e_type', $type);
            $this->dao->where('s_search', $search);
            $result = $this->dao->get();
            
            if( $result == false ) { 
                return false;
            } else {
                return $result->result();
            }
        }
        
        
        // a.s_email, a.fk_i_user_id @TODO
        function getUsersBySearchAndType($search, $type, $active = FALSE) 
        {
            $this->dao->select() ;
            $this->dao->from($this->table_name) ;
            $this->dao->where('e_type', $type);
            $this->dao->where('s_search', $search);
            if($active){
                $this->dao->where('b_active', 1); 
            }
            $this->dao->group_by('s_search');
            $result = $this->dao->get();
            
            if( $result == false ) { 
                return false;
            } else {
                return $result->result();
            }
        }
        
        
        function getAlertsFromUserByType($userId, $type)
        {
            $this->dao->select();
            $this->dao->from($this->table_name);
            $conditions = array('e_type'        => $type,
                                'fk_i_user_id'  => $userId);
            $this->dao->where('e_type', $conditions);
            $result = $this->dao->get();
            
            if( $result == false ) { 
                return false;
            } else {
                return $result->result();
            }
        }
        
        function getAlertsFromEmailByType($email, $type)
        {
            $this->dao->select();
            $this->dao->from($this->table_name);
            $conditions = array('e_type'   => $type,
                                's_email'  => $email);
            $this->dao->where('e_type', $conditions);
            $result = $this->dao->get();
            
            if( $result == false ) { 
                return false;
            } else {
                return $result->result();
            }
        }
        
        function createAlert($userid = 0, $email, $alert, $secret, $type = 'DAILY')
        {
            $results = 0;
            $this->dao->select();
            $this->dao->from($this->table_name);
            $this->dao->where('s_search', $alert);
            if($userid == 0 || $userid == null){
                $this->dao->where('fk_i_user_id', 0);
                $this->dao->where('s_email', $email);
            } else {
                $this->dao->where('fk_i_user_id', $userid);
            }
            $results = $this->dao->get();
            
            if($results->num_rows() == 0) {
                return $this->dao->insert($this->table_name, array( 'fk_i_user_id' => $userid, 's_email' => $email, 's_search' => $alert, 'e_type' => $type, 's_secret' => $secret));
            }
            return false;
        }
        
        function activate($email, $secret)
        {
            $this->dao->update($this->table_name, array('b_active' => 1), array('s_email' => $email, 's_secret' => $secret));
            return $this->dao->affected_rows();
        }
    }
    
?>