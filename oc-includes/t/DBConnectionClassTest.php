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

    require_once '../osclass/classes/data/DBConnectionClass.php' ;
    require_once '../osclass/classes/data/DBCommandClass.php' ;
    require_once '../osclass/classes/data/DBRecordsetClass.php' ;

    /**
     * Run: $> phpunit DBConnectionClassTest.php
     */
    class DBConnectionClassTest extends PHPUnit_Framework_TestCase
    {
        private $conn ;

        public function __construct()
        {
            parent::__construct() ;
            $this->conn = new DBConnectionClass() ;
            $this->conn->init('localhost', 'root', '', 'osclass', 0) ;
        }

        public function testDatabaseConnection()
        {
            $this->assertEquals(true, $this->conn->connect_to_db()) ;
        }

        public function testDatabaseSelectDB()
        {
            // select default database
            $this->assertEquals(true, $this->conn->select_db()) ;
            // select another database
            $this->assertEquals(true, $this->conn->select_db()) ;
            // non existent database
            $this->assertEquals(false, $this->conn->select_db('nodatabase')) ;
        }

        public function testReleaseDatabase()
        {
            $this->assertEquals(true, $this->conn->release_db()) ;
        }
    }

?>