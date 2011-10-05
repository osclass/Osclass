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

    require_once '../osclass/classes/data/DBConnectionClass.php' ;
    require_once '../osclass/classes/data/DBCommandClass.php' ;
    require_once '../osclass/classes/data/DBRecordsetClass.php' ;
    require_once '../osclass/classes/data/DAO.php' ;

    require_once '../osclass/model/new_model/Region.php' ;

    /**
     * Run: $> phpunit PreferenceTest.php
     */
    class RegionTest extends PHPUnit_Framework_TestCase
    {
        private $regionDAO ;
        
        public function __construct()
        {
            parent::__construct() ;
            $this->regionDAO = new Region() ;
        }

        public function testFindByPrimaryKey()
        {
            $region = $this->regionDAO->find_by_primary_key(3) ;
            $this->assertEquals('Barcelona', $region['s_name'], $this->regionDAO->dao->last_query() ) ;
            
            $region = $this->regionDAO->find_by_primary_key(99999999) ;
            $this->assertEquals(false, $region, $this->regionDAO->dao->last_query() ) ;
        }
        
        public function testGetByCountry() {
            $region = current($this->regionDAO->getByCountry('es'));
            $this->assertEquals('A Coruña', $region['s_name'], $this->regionDAO->dao->last_query() ) ;
            
            $region = current($this->regionDAO->getByCountry('xx'));
            $this->assertEquals(false, $region, $this->regionDAO->dao->last_query() ) ;
        }

        public function testFindByName() {
            $region = $this->regionDAO->findByName('Barcelona');
            $this->assertEquals('Barcelona', $region['s_name'], $this->regionDAO->dao->last_query() ) ;
            
            $region = $this->regionDAO->findByName('barcelona');
            $this->assertEquals('Barcelona', $region['s_name'], $this->regionDAO->dao->last_query() ) ;
            
            $region = $this->regionDAO->findByName('xXx');
            $this->assertEquals(true, empty($region), $this->regionDAO->dao->last_query() ) ;
        }

        /*public function testFindByNameAndCode($name, $code) {
            $region = current($this->regionDAO->getByCountry('es') );
            $this->assertEquals('Barcelona', $region['s_name'], $this->regionDAO->dao->last_query() ) ;
            
            $region = $this->regionDAO->getByCountry('xx') ;
            $this->assertEquals(false, $region, $this->regionDAO->dao->last_query() ) ;
        }

        public function testFindByNameOnCountry($name, $region = null) {
            $region = current($this->regionDAO->getByCountry('es') );
            $this->assertEquals('Barcelona', $region['s_name'], $this->regionDAO->dao->last_query() ) ;
            
            $region = $this->regionDAO->getByCountry('xx') ;
            $this->assertEquals(false, $region, $this->regionDAO->dao->last_query() ) ;
        }*/
        
        public function testAjax() {
            $region = current($this->regionDAO->ajax('b'));
            $this->assertEquals('Barcelona', $region['s_name'], $this->regionDAO->dao->last_query() ) ;
            
            $region = current($this->regionDAO->ajax('b','es'));
            $this->assertEquals('Barcelona', $region['s_name'], $this->regionDAO->dao->last_query() ) ;
            
            $region = current($this->regionDAO->ajax('b','xx'));
            $this->assertEquals(false, isset($region['s_name']), $this->regionDAO->dao->last_query() ) ;
            
            $region = current($this->regionDAO->ajax('x', 'es'));
            $this->assertEquals(false, isset($region['s_name']), $this->regionDAO->dao->last_query() ) ;

            $region = current($this->regionDAO->ajax('x', 'xx'));
            $this->assertEquals(false, isset($region['s_name']), $this->regionDAO->dao->last_query() ) ;
        }
        
        
        
    }
    
        function osc_current_user_locale() {
            return 'en_US';
        }
        
    
    
?>