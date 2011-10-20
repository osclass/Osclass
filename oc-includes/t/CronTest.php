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
    require_once '../osclass/classes/database/DBConnectionClass.php' ;
    require_once '../osclass/classes/database/DBCommandClass.php' ;
    require_once '../osclass/classes/database/DBRecordsetClass.php' ;
    require_once '../osclass/classes/database/DAO.php' ;

    require_once '../osclass/model/new_model/Cron.php' ;

    /**
     * Run: $> phpunit CronTest.php
     */
    class CronTest extends PHPUnit_Framework_TestCase
    {
        private $cron;

        public function __construct()
        {
            parent::__construct() ;
            $this->cron = new Cron() ;
        }

        public function testGetCronByType()
        {
            $res = $this->cron->getCronByType('HOURLY');
            $this->assertNotEmpty($res, $this->cron->dao->errorLevel);
            $res = $this->cron->getCronByType('DAILY');
            $this->assertNotEmpty($res, $this->cron->dao->errorLevel);
            $res = $this->cron->getCronByType('WEEKLY');
            $this->assertNotEmpty($res, $this->cron->dao->errorLevel);
            
            $res = $this->cron->getCronByType('FOOBAR');
            $this->assertFalse($res, $this->cron->dao->errorLevel);
        }

    }
    
?>
