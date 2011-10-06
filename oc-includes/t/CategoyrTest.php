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
            print_r($cat);
        }
        
    }
    
    
            function osc_current_user_locale() {
            return 'en_US';
        }
        

    
?>