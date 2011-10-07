<?php

    /**
     * OSClass – software for creating and publishing online classified advertising platforms
     *
     * Copyright (C) 2010 OSCLASS
     *
     * This program is free software: you can redistribute it and/or modify it under the terms
     * of the GNU Affero General Public License as published by the Free Software Foundation,
     * either version 3 of the License, or (at your option) any later version.
     *
     * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
     * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
     * See the GNU Affero General Public License for more details.
     *
     * You should have received a copy of the GNU Affero General Public
     * License along with this program. If not, see <http://www.gnu.org/licenses/>.
     */

    require_once 'config.php' ;

    require_once '../osclass/Logger/LogDatabase.php' ;
    require_once '../osclass/helpers/hDatabaseInfo.php' ;
    require_once '../osclass/classes/data/DBConnectionClass.php' ;
    require_once '../osclass/classes/data/DBCommandClass.php' ;
    require_once '../osclass/classes/data/DBRecordsetClass.php' ;
    require_once '../osclass/classes/data/DAO.php' ;

    require_once '../osclass/model/new_model/User.php' ;
    require_once '../osclass/helpers/hSecurity.php' ;

    /**
     * Run: $> phpunit UserTest.php
     */
    class UserTest extends PHPUnit_Framework_TestCase
    {
        private $preference ;
        
        public function __construct()
        {
            parent::__construct() ;
            $this->model = new User() ;
        }

        public function testInsert()
        {// minimal information
            $array_set = array(
                's_name'        => 'user name',
                's_password'    => 'password',
                's_secret'      => osc_genRandomPassword(),
                'dt_reg_date'   => date('Y-m-d H:i:s'),
                's_email'       => 'test@email.com'
            );
            $res = $this->model->dao->insert($this->model->get_table_name(), $array_set ) ;
            $this->assertTrue($res, $this->model->dao->last_query()) ;
            
            $array_set['s_email']       = 'test@email.com' ;
            $array_set['b_active']      = '1' ;
            $array_set['s_password']    = 'password2';
            $res = $this->model->dao->insert($this->model->get_table_name(), $array_set ) ;
            $this->assertTrue($res, $this->model->dao->error_level) ;
        }
        
        public function testFindByPrimaryKey()
        {   // findByPrimaryKey($id, $locale = null)
            
            
        }

        public function testFindByEmail()
        {
            $email = 'test@email.com';
            $result = $this->model->findByEmail($email);
            
            print_r($result);
            
            $this->assertEquals('1', count($result), $this->model->dao->error_level);
            $this->assertNotNull($result, $this->model->dao->error_level);
            
            foreach($this->model->get_fields() as $field){
                $this->assertArrayHasKey($field, $result);
            }
        }
        
        public function testFindByCredentials()
        {
            
        }
        
        public function testFindByIdSecret()
        {
            
        }
        
        public function testFindByIdPasswordSecret()
        {
            
        }
        
        public function testUpdateDescription()
        {
            
        }
        
        public function testDeleteUser()
        {
            
        }
    }
    
?>
