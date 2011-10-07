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

    require_once '../osclass/model/new_model/Admin.php' ;
    
    /**
     * Run: $> phpunit AdminTest.php
     */
    
    class AdminTest extends PHPUnit_Framework_TestCase
    {
        private $admin ;
        private $email      = 'fsoo@test.com';
        private $username   = 'newAdmin';
        private $password   = 'admin';
        private $secret     = 'foobarOsclasS';
        
        protected static $adminId = -1;
        
        public function __construct()
        {
            parent::__construct() ;
            $this->admin = new Admin() ;
        }

        public function testInsertAdmin()
        {
            $set = array(
                's_name'        => 'Administrator name',
                's_username'    => 'newAdmin',
                's_email'       => $this->email,
                's_password'    => sha1('admin'),
                's_secret'      => $this->secret
            );
            
            $res = $this->admin->dao->insert( $this->admin->getTableName(), $set) ;
            self::$adminId = (int) $this->admin->dao->insertedId() ;
            $this->assertTrue($res, $this->admin->dao->getErrorLevel() ) ;
        }
        
        public function testFindByEmail()
        {
            $res = $this->admin->findByEmail($this->email) ;
            $this->assertNotEmpty($res, $this->admin->dao->getErrorLevel() ) ;
            
            $res = $this->admin->findByEmail('incorrect@email.com');
            $this->assertFalse($res, $this->admin->dao->getErrorLevel() );
        }
        
        public function testFindByUsername()
        {
            $res = $this->admin->findByUsername($this->username) ;
            $this->assertNotEmpty($res, $this->admin->dao->getErrorLevel() ) ;
            
            $res = $this->admin->findByUsername('incorrect');
            $this->assertFalse($res, $this->admin->dao->getErrorLevel() );
        }
        
        public function testFindByCredentials()
        {
            $res = $this->admin->findByCredentials($this->username, $this->password) ;
            $this->assertNotEmpty($res, sprintf('%s > %s', $this->admin->dao->getErrorLevel(), $this->admin->dao->lastQuery()) ) ;
            
            $res = $this->admin->findByCredentials('incorrect', 'incorrect secret') ;
            $this->assertFalse($res, $this->admin->dao->getErrorLevel() ) ;
        }
        
        public function testFindByIdSecret()
        {
            $res = $this->admin->findByIdSecret(self::$adminId, $this->secret) ;
            $this->assertNotEmpty($res, $this->admin->dao->getErrorLevel() ) ;
            
            $res = $this->admin->findByIdSecret('11', 'incorrect secret');
            $this->assertFalse($res, $this->admin->dao->getErrorLevel() ) ;
        }
        
        public function testUpdateArray()
        {
            $condition = array('pk_i_id' => 'not_id') ;
            $values    = array('s_name' => 'updated name') ;
            $res = $this->admin->dao->update($this->admin->getTableName(), $values, $condition) ;
            $this->assertFalse($res, $this->admin->dao->lastQuery()) ;
            
            $condition = array('pk_i_id' => self::$adminId) ;
            $values    = array('s_name' => 'updated name') ;
            $res = $this->admin->dao->update($this->admin->getTableName(), $values, $condition) ;
            $this->assertTrue($res, $this->admin->dao->getErrorLevel()) ;
        }
        
        public function testDelete()
        {
            $conditions = array('pk_i_id' => 'not_id') ;
            $res = $this->admin->dao->delete($this->admin->getTableName(), $conditions) ;
            $this->assertFalse($res, $this->admin->dao->getErrorLevel());
            
            $conditions = array('pk_i_id' => self::$adminId) ;
            $res = $this->admin->dao->delete($this->admin->getTableName(), $conditions) ;
            $this->assertTrue($res, $this->admin->dao->getErrorLevel());
        }
    }

    /* file end: ./oc-includes/osclass/t/AdminTest.php */
?>