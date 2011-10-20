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
    require_once '../osclass/helpers/hDatabaseInfo.php' ;
    require_once '../osclass/Logger/LogDatabase.php' ;

    require_once '../osclass/classes/database/DBConnectionClass.php' ;
    require_once '../osclass/classes/database/DBCommandClass.php' ;
    require_once '../osclass/classes/database/DBRecordsetClass.php' ;
    require_once '../osclass/classes/database/DAO.php' ;

    require_once '../osclass/model/new_model/Country.php' ;

    /**
     * Run: $> phpunit PreferenceTest.php
     */
    class CountryTest extends PHPUnit_Framework_TestCase
    {
        private $countryDAO ;
        
        public function __construct()
        {
            parent::__construct() ;
            $this->countryDAO = new Country() ;
        }

        public function testFindByPrimaryKey()
        {
            $country = $this->countryDAO->findByPrimaryKey('ES') ;
            $this->assertEquals('Spain', $country['s_name'], $this->countryDAO->dao->lastQuery() ) ;
            
            $country = $this->countryDAO->findByPrimaryKey('AZ') ;
            $this->assertEquals(false, $country, $this->countryDAO->dao->lastQuery() ) ;
        }
        
        public function testFindByCode()
        {
            $country = $this->countryDAO->findByCode('ES') ;
            $this->assertEquals('Spain', $country['s_name'], $this->countryDAO->dao->lastQuery() ) ;
            
            $country = $this->countryDAO->findbyCode('AZ') ;
            $this->assertEquals(false, isset($country['s_name']), $this->countryDAO->dao->lastQuery() ) ;
        }
     
        public function testFindByName()
        {
            $country = $this->countryDAO->findByName('Spain') ;
            $this->assertEquals('Spain', $country['s_name'], $this->countryDAO->dao->lastQuery() ) ;
            
            $country = $this->countryDAO->findbyName('España') ;
            $this->assertEquals(false, isset($country['s_name']), $this->countryDAO->dao->lastQuery() ) ;
        }
     
        public function testListAll()
        {
            $country = end($this->countryDAO->listAll('en_US'));
            $this->assertEquals('Spain', $country['s_name'], $this->countryDAO->dao->lastQuery() ) ;
            
            $country = end($this->countryDAO->listAll('xx_XX'));
            $this->assertEquals('Spain', $country['s_name'], $this->countryDAO->dao->lastQuery() ) ;
        }
     
        public function testListAllAdmin()
        {
            $country = end($this->countryDAO->listAllAdmin('en_US'));
            $this->assertEquals('Spain', $country['s_name'], $this->countryDAO->dao->lastQuery() ) ;
            $this->assertEquals('Spain', $country['locales']['en_US'], $this->countryDAO->dao->lastQuery() ) ;
            
            $country = end($this->countryDAO->listAllAdmin('xx_XX'));
            $this->assertEquals('Spain', $country['s_name'], $this->countryDAO->dao->lastQuery() ) ;
            $this->assertEquals('Spain', $country['locales']['en_US'], $this->countryDAO->dao->lastQuery() ) ;
        }
     
        
        public function testAjax()
        {
            $country = current($this->countryDAO->ajax('s'));
            $this->assertEquals('Spain', $country['label'], $this->countryDAO->dao->lastQuery() ) ;
            
            $country = current($this->countryDAO->ajax('x'));
            $this->assertEquals(false, isset($country['label']), $this->countryDAO->dao->lastQuery() ) ;
        }
     
        
        
        
        /*

       
        public function updateLocale($code, $locale, $name) {
            $this->dao->select('*') ;
            $this->dao->from($this->table_name) ;
            $this->dao->where('pk_c_code', addslashes($code)) ;
            $this->dao->where('fk_c_locale_code', addslashes($locale)) ;
            $this->dao->limit(1);
            $result = $this->dao->get() ;
            $country = $result->result_array();
            if($country) {
                return $this->dao->update($this->table_name, array('s_name' => $name), array('pk_c_code' => $code, 'fk_c_locale_code' => $locale));
            } else {
                return $this->conn->osc_dbExec("INSERT INTO %s (pk_c_code, fk_c_locale_code, s_name) VALUES ('%s', '%s', '%s')", $this->getTableName(), addslashes($code), addslashes($locale), addslashes($name) );
            }
        }

    
        }*/
        
        
        
    }
    
        function osc_current_user_locale() {
            return 'en_US';
        }
        
    
    
?>