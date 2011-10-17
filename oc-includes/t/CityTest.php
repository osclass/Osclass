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

    require_once '../osclass/model/new_model/City.php' ;

    /**
     * Run: $> phpunit CityTest.php
     */
    class CityTest extends PHPUnit_Framework_TestCase
    {
        private $cityDAO ;
        protected static $aInfo ;

        public function __construct()
        {
            parent::__construct() ;
            $this->cityDAO = new City() ;
            self::$aInfo   = array() ;
        }

        public function testFindByPrimaryKey()
        {
            /* it should return a correct result */
            $city = $this->cityDAO->findByPrimaryKey('3') ;
            // Check it the name is corect
            $this->assertEquals('Sabadell', $city['s_name'], $this->cityDAO->dao->lastQuery() ) ;

            // it should return a incorrect result
            $city = $this->cityDAO->findByPrimaryKey('10000') ;
            $this->assertFalse($city, $this->cityDAO->dao->lastQuery() ) ;
        }

        public function testFindByRegion()
        {
            /* regionId = 91 (Tarragona) */
            $aCities = $this->cityDAO->findByRegion(91) ;
            
            // check that is an array
            $this->assertContainsOnly('array', $aCities) ;
            // check keys
            foreach($this->cityDAO->getFields() as $field) {
                $this->assertArrayHasKey($field, $aCities[0], $field . ' does not exists. Array keys of $aCities: ' . array_keys($aCities)) ;
            }

            /* no cities for that region */
            $aCities = $this->cityDAO->findByRegion(10000000) ;

            // check that even if the result is 0, the return type is an array
            $this->assertContainsOnly('array', $aCities) ;
            // check that the count of the array is 0
            $this->assertEquals(0, count($aCities), 'Number of items in the array: ' . count($aCities)) ;
        }

        public function testAjax()
        {
            /**
             * Define internal vars for ajax method test
             */
            $ajaxFields = array('id', 'label', 'value') ;

            /**
             * Number results: 55
             * 
             * query: "ta"
             * region: null
             */
            $aCities = $this->cityDAO->ajax('ta') ;

            // check that is an array
            $this->assertContainsOnly('array', $aCities) ;
            // check keys of the array
            foreach( $ajaxFields as $field ) {
                $this->assertArrayHasKey($field, $aCities[0], $field . ' does not exists. Array keys of $aCities: ' . array_keys($aCities)) ;
            }
            // check that there are 55 results
            $this->assertEquals(55, count($aCities), 'There are not 55 results: ' . count($aCities)) ;

            /**
             * Number results: 0
             * 
             * query: "taxsa"
             * region: null
             */
            $aCities = $this->cityDAO->ajax('taxsa') ;

            // check that is an array
            $this->assertContainsOnly('array', $aCities) ;
            // check that there are 0 results
            $this->assertEquals(0, count($aCities), 'There are not 0 results: ' . count($aCities)) ;

            /**
             * Number results: 2
             * 
             * query: "ta"
             * region: 74
             */
            $aCities = $this->cityDAO->ajax('ta', 74) ;

            // check that is an array
            $this->assertContainsOnly('array', $aCities) ;
            // check keys of the array
            foreach( $ajaxFields as $field ) {
                $this->assertArrayHasKey($field, $aCities[0], $field . ' does not exists. Array keys of $aCities: ' . array_keys($aCities)) ;
            }
            // check that there are more than 1 result
            $this->assertEquals(2, count($aCities), 'There are not 2 results: ' . count($aCities)) ;

            /**
             * Number of results: 0
             * 
             * query: "tarr"
             * region: 74
             */
            $aCities = $this->cityDAO->ajax('tarr', 74) ;

            // check that is an array
            $this->assertContainsOnly('array', $aCities) ;
            // check that there are 0 results
            $this->assertEquals(0, count($aCities), 'There are not 0 results: ' . count($aCities)) ;
        }

        public function testInsert()
        {
            /**
             * It does not exist fk_i_region_id value
             */
            $values = array(
                'fk_i_region_id'    => '100000',
                's_name'            => 'Name',
                'fk_c_country_code' => 'ES',
                'b_active'          => true
            ) ;
            $result = $this->cityDAO->insert($values) ;

            // check that the insert hasn't been done
            $this->assertFalse($result, 'City::insert($values) should not be done') ;

            /**
             * Not existent key
             */
            $values = array(
                'non_existent_key'  => '100000',
                's_name'            => 'Name',
                'fk_c_country_code' => 'ES',
                'b_active'          => true
            ) ;
            $result = $this->cityDAO->insert($values, 'City::insert($values) should not be done') ;

            /**
             * Correct insert
             */
            $values = array(
                'fk_i_region_id'    => '74',
                's_name'            => 'Name',
                'fk_c_country_code' => 'ES',
                'b_active'          => true
            ) ;
            $result            = $this->cityDAO->insert($values) ;
            self::$aInfo['id'] = $this->cityDAO->dao->insertedId() ;
            
            // check that the insert hasn't been done
            $this->assertTrue($result, 'City::insert($values) should be correct') ;
        }
        
        public function testFindByNameOnRegion()
        {
            
        }
        
        public function testDeleteByPrimaryKey()
        {
            /**
             * Incorrect delete
             */
            $result = $this->cityDAO->deleteByPrimaryKey(100000000) ;
            
            // check that it has not been removed
            $this->assertEquals(0, $result, 'City::deleteByPrimaryKey should not have removed any city') ;

            /**
             * Correct delete
             */
            $result = $this->cityDAO->deleteByPrimaryKey(self::$aInfo['id']) ;

            // check that return it's 1
            $this->assertEquals(1, $result, 'City::deleteByPrimaryKey should have removed City with ID: ' . self::$aInfo['id']) ;
            // check that it does not exist
            $result = $this->cityDAO->findByPrimaryKey(self::$aInfo['id']) ;
            $this->assertFalse($result, 'City::findByPrimaryKey should not return any results') ;
        }

        public function testUpdate()
        {
            
        }

        public function testFindByName()
        {
            
        }
    }

    /* file end: ./oc-includes/osclass/t/CityTest.php */
?>