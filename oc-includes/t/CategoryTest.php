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

    require_once '../osclass/classes/data/DBConnectionClass.php' ;
    require_once '../osclass/classes/data/DBCommandClass.php' ;
    require_once '../osclass/classes/data/DBRecordsetClass.php' ;
    require_once '../osclass/classes/data/DAO.php' ;

    require_once '../osclass/model/new_model/Category.php' ;

    /**
     * Run: $> phpunit PreferenceTest.php
     */
    class CategoryTest extends PHPUnit_Framework_TestCase
    {
        private $catDAO ;
        
        public function __construct()
        {
            parent::__construct() ;
            $this->catDAO = new Category() ;
        }

        public function testlistEnabled()
        {
            $cat = $this->catDAO->listEnabled();
            $tmp = current($cat);
            $this->assertEquals('Vehicles', $tmp['s_name'], $this->catDAO->dao->last_query() ) ;
            $tmp = end($cat);
            $this->assertEquals('Everything Else', $tmp['s_name'], $this->catDAO->dao->last_query() ) ;
        }

        public function testToTree()
        {
            $cat = $this->catDAO->toTree();
            $this->assertEquals(10, count($cat), $this->catDAO->dao->last_query() ) ;
            $this->assertEquals(20, count($cat[7]['categories']), $this->catDAO->dao->last_query() ) ;
            $tmp = end($cat);
            $this->assertEquals('Animalsa', $tmp['s_name'], $this->catDAO->dao->last_query() ) ;
            
            $cat = $this->catDAO->toTreeAll();
            $this->assertEquals(10, count($cat), $this->catDAO->dao->last_query() ) ;
            $this->assertEquals(20, count($cat[7]['categories']), $this->catDAO->dao->last_query() ) ;
            $tmp = end($cat);
            $this->assertEquals('Animalsa', $tmp['s_name'], $this->catDAO->dao->last_query() ) ;
        }
        
        public function testfindRootCategories()
        {
            $cat = $this->catDAO->findRootCategories();
            $this->assertEquals(10, count($cat), $this->catDAO->dao->last_query() ) ;
            $cat = $this->catDAO->findRootCategoriesEnabled();
            $this->assertEquals(10, count($cat), $this->catDAO->dao->last_query() ) ;
        }

        public function testToSubTree()
        {
            $cat = $this->catDAO->toSubTree(95);
            $this->assertEquals(true, empty($cat), $this->catDAO->dao->last_query() ) ;
            $cat = $this->catDAO->toSubTree(8);
            $this->assertEquals(20, count($cat), $this->catDAO->dao->last_query() ) ;
        }
        
        public function testIsParentOf()
        {
            $cat = $this->catDAO->isParentOf(95);
            $this->assertEquals(true, empty($cat), $this->catDAO->dao->last_query() ) ;
            $cat = $this->catDAO->isParentOf(8);
            $this->assertEquals(20, count($cat), $this->catDAO->dao->last_query() ) ;
        }
        
        public function testFindBySlug()
        {
            $cat = $this->catDAO->find_by_slug('animalsa');
            $this->assertEquals('Animalsa', $cat['s_name'], $this->catDAO->dao->last_query() ) ;

            $cat = $this->catDAO->find_by_slug('xXx');
            $this->assertEquals(null, $cat, $this->catDAO->dao->last_query() ) ;
        }
        
    }
    
    
            function osc_current_user_locale() {
            return 'en_US';
        }
        

    
?>