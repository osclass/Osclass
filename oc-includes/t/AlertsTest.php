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

    require_once '../osclass/model/new_model/Alerts.php' ;
    require_once '../osclass/helpers/hSecurity.php' ;
    
    /**
     * Run: $> phpunit AlertsTest.php
     */
    
    class AlertsTest extends PHPUnit_Framework_TestCase
    {
        private $model ;
        protected static $aSecret = array();
        
        public function __construct()
        {
            parent::__construct() ;
            $this->model = new Alerts() ;
        }

        public function testInsertAlerts()
        {
            $s_search = "Tzo2OiJTZWFyY2giOjE1OntzOjE4OiIAU2VhcmNoAGNvbmRpdGlvbnMiO2E6Nzp7aTowO3M6MjI6Im9jX3RfaXRlbS5iX2FjdGl2ZSA9IDEiO2k6MTtzOjIzOiJvY190X2l0ZW0uYl9lbmFibGVkID0gMSI7aToyO3M6MjA6Im9jX3RfaXRlbS5iX3NwYW0gPSAwIjtpOjM7czoxNjM6IihvY190X2l0ZW0uYl9wcmVtaXVtID0gMSB8fCBvY190X2NhdGVnb3J5LmlfZXhwaXJhdGlvbl9kYXlzID0gMCB8fFRJTUVTVEFNUERJRkYoREFZLG9jX3RfaXRlbS5kdF9wdWJfZGF0ZSwnMjAxMS0xMC0wNSAxNTo0ODoyMScpIDwgb2NfdF9jYXRlZ29yeS5pX2V4cGlyYXRpb25fZGF5cykiO2k6NDtzOjI3OiJvY190X2NhdGVnb3J5LmJfZW5hYmxlZCA9IDEiO2k6NTtzOjUwOiJvY190X2NhdGVnb3J5LnBrX2lfaWQgPSBvY190X2l0ZW0uZmtfaV9jYXRlZ29yeV9pZCI7aTo2O3M6ODI2OiIoIG9jX3RfaXRlbS5ma19pX2NhdGVnb3J5X2lkID0gMSAgfHwgb2NfdF9pdGVtLmZrX2lfY2F0ZWdvcnlfaWQgPSA5ICB8fCBvY190X2l0ZW0uZmtfaV9jYXRlZ29yeV9pZCA9IDEwICB8fCBvY190X2l0ZW0uZmtfaV9jYXRlZ29yeV9pZCA9IDExICB8fCBvY190X2l0ZW0uZmtfaV9jYXRlZ29yeV9pZCA9IDEyICB8fCBvY190X2l0ZW0uZmtfaV9jYXRlZ29yeV9pZCA9IDEzICB8fCBvY190X2l0ZW0uZmtfaV9jYXRlZ29yeV9pZCA9IDE0ICB8fCBvY190X2l0ZW0uZmtfaV9jYXRlZ29yeV9pZCA9IDE1ICB8fCBvY190X2l0ZW0uZmtfaV9jYXRlZ29yeV9pZCA9IDE2ICB8fCBvY190X2l0ZW0uZmtfaV9jYXRlZ29yeV9pZCA9IDE3ICB8fCBvY190X2l0ZW0uZmtfaV9jYXRlZ29yeV9pZCA9IDE4ICB8fCBvY190X2l0ZW0uZmtfaV9jYXRlZ29yeV9pZCA9IDE5ICB8fCBvY190X2l0ZW0uZmtfaV9jYXRlZ29yeV9pZCA9IDIwICB8fCBvY190X2l0ZW0uZmtfaV9jYXRlZ29yeV9pZCA9IDIxICB8fCBvY190X2l0ZW0uZmtfaV9jYXRlZ29yeV9pZCA9IDIyICB8fCBvY190X2l0ZW0uZmtfaV9jYXRlZ29yeV9pZCA9IDIzICB8fCBvY190X2l0ZW0uZmtfaV9jYXRlZ29yeV9pZCA9IDI0ICB8fCBvY190X2l0ZW0uZmtfaV9jYXRlZ29yeV9pZCA9IDI1ICB8fCBvY190X2l0ZW0uZmtfaV9jYXRlZ29yeV9pZCA9IDI2ICB8fCBvY190X2l0ZW0uZmtfaV9jYXRlZ29yeV9pZCA9IDI3ICB8fCBvY190X2l0ZW0uZmtfaV9jYXRlZ29yeV9pZCA9IDI4ICB8fCBvY190X2l0ZW0uZmtfaV9jYXRlZ29yeV9pZCA9IDI5ICB8fCBvY190X2l0ZW0uZmtfaV9jYXRlZ29yeV9pZCA9IDMwICApIjt9czoxNDoiAFNlYXJjaAB0YWJsZXMiO2E6Mjp7aTowO3M6NTk6Im9jX3RfaXRlbV9kZXNjcmlwdGlvbiBhcyBkLCBvY190X2NhdGVnb3J5X2Rlc2NyaXB0aW9uIGFzIGNkIjtpOjE7czoxMzoib2NfdF9jYXRlZ29yeSI7fXM6MTE6IgBTZWFyY2gAc3FsIjtzOjE3MzM6IlNFTEVDVCBTUUxfQ0FMQ19GT1VORF9ST1dTIERJU1RJTkNUIHF1ZXJ5LiosIG9jX3RfdXNlci5zX25hbWUgYXMgc191c2VyX25hbWUgRlJPTSAoIFNFTEVDVCAgb2NfdF9pdGVtLiosIG9jX3RfaXRlbV9sb2NhdGlvbi4qLCBkLnNfdGl0bGUsIGNkLnNfbmFtZSBhcyBzX2NhdGVnb3J5X25hbWUgIEZST00gb2NfdF9pdGVtLCBvY190X2l0ZW1fbG9jYXRpb24sIG9jX3RfaXRlbV9kZXNjcmlwdGlvbiBhcyBkLCBvY190X2NhdGVnb3J5X2Rlc2NyaXB0aW9uIGFzIGNkLCBvY190X2NhdGVnb3J5IFdIRVJFIG9jX3RfaXRlbV9sb2NhdGlvbi5ma19pX2l0ZW1faWQgPSBvY190X2l0ZW0ucGtfaV9pZCAgQU5EIG9jX3RfaXRlbS5iX2FjdGl2ZSA9IDEgQU5EIG9jX3RfaXRlbS5iX2VuYWJsZWQgPSAxIEFORCBvY190X2l0ZW0uYl9zcGFtID0gMCBBTkQgKG9jX3RfaXRlbS5iX3ByZW1pdW0gPSAxIHx8IG9jX3RfY2F0ZWdvcnkuaV9leHBpcmF0aW9uX2RheXMgPSAwIHx8VElNRVNUQU1QRElGRihEQVksb2NfdF9pdGVtLmR0X3B1Yl9kYXRlLCcyMDExLTEwLTA1IDE1OjQ4OjIxJykgPCBvY190X2NhdGVnb3J5LmlfZXhwaXJhdGlvbl9kYXlzKSBBTkQgb2NfdF9jYXRlZ29yeS5iX2VuYWJsZWQgPSAxIEFORCBvY190X2NhdGVnb3J5LnBrX2lfaWQgPSBvY190X2l0ZW0uZmtfaV9jYXRlZ29yeV9pZCBBTkQgKCBvY190X2l0ZW0uZmtfaV9jYXRlZ29yeV9pZCA9IDEgIHx8IG9jX3RfaXRlbS5ma19pX2NhdGVnb3J5X2lkID0gOSAgfHwgb2NfdF9pdGVtLmZrX2lfY2F0ZWdvcnlfaWQgPSAxMCAgfHwgb2NfdF9pdGVtLmZrX2lfY2F0ZWdvcnlfaWQgPSAxMSAgfHwgb2NfdF9pdGVtLmZrX2lfY2F0ZWdvcnlfaWQgPSAxMiAgfHwgb2NfdF9pdGVtLmZrX2lfY2F0ZWdvcnlfaWQgPSAxMyAgfHwgb2NfdF9pdGVtLmZrX2lfY2F0ZWdvcnlfaWQgPSAxNCAgfHwgb2NfdF9pdGVtLmZrX2lfY2F0ZWdvcnlfaWQgPSAxNSAgfHwgb2NfdF9pdGVtLmZrX2lfY2F0ZWdvcnlfaWQgPSAxNiAgfHwgb2NfdF9pdGVtLmZrX2lfY2F0ZWdvcnlfaWQgPSAxNyAgfHwgb2NfdF9pdGVtLmZrX2lfY2F0ZWdvcnlfaWQgPSAxOCAgfHwgb2NfdF9pdGVtLmZrX2lfY2F0ZWdvcnlfaWQgPSAxOSAgfHwgb2NfdF9pdGVtLmZrX2lfY2F0ZWdvcnlfaWQgPSAyMCAgfHwgb2NfdF9pdGVtLmZrX2lfY2F0ZWdvcnlfaWQgPSAyMSAgfHwgb2NfdF9pdGVtLmZrX2lfY2F0ZWdvcnlfaWQgPSAyMiAgfHwgb2NfdF9pdGVtLmZrX2lfY2F0ZWdvcnlfaWQgPSAyMyAgfHwgb2NfdF9pdGVtLmZrX2lfY2F0ZWdvcnlfaWQgPSAyNCAgfHwgb2NfdF9pdGVtLmZrX2lfY2F0ZWdvcnlfaWQgPSAyNSAgfHwgb2NfdF9pdGVtLmZrX2lfY2F0ZWdvcnlfaWQgPSAyNiAgfHwgb2NfdF9pdGVtLmZrX2lfY2F0ZWdvcnlfaWQgPSAyNyAgfHwgb2NfdF9pdGVtLmZrX2lfY2F0ZWdvcnlfaWQgPSAyOCAgfHwgb2NfdF9pdGVtLmZrX2lfY2F0ZWdvcnlfaWQgPSAyOSAgfHwgb2NfdF9pdGVtLmZrX2lfY2F0ZWdvcnlfaWQgPSAzMCAgKSBBTkQgb2NfdF9pdGVtLnBrX2lfaWQgPSBkLmZrX2lfaXRlbV9pZCBBTkQgb2NfdF9pdGVtLmZrX2lfY2F0ZWdvcnlfaWQgPSBjZC5ma19pX2NhdGVnb3J5X2lkIEdST1VQIEJZIG9jX3RfaXRlbS5wa19pX2lkICkgYXMgcXVlcnkgTEVGVCBKT0lOIG9jX3RfdXNlciBvbiBvY190X3VzZXIucGtfaV9pZCA9IHF1ZXJ5LmZrX2lfdXNlcl9pZCBPUkRFUiBCWSBkdF9wdWJfZGF0ZSBkZXNjIExJTUlUIDAsIDEwIjtzOjIwOiIAU2VhcmNoAG9yZGVyX2NvbHVtbiI7czoxMToiZHRfcHViX2RhdGUiO3M6MjM6IgBTZWFyY2gAb3JkZXJfZGlyZWN0aW9uIjtzOjQ6ImRlc2MiO3M6MTg6IgBTZWFyY2gAbGltaXRfaW5pdCI7aTowO3M6MjQ6IgBTZWFyY2gAcmVzdWx0c19wZXJfcGFnZSI7czoyOiIxMCI7czoxNDoiAFNlYXJjaABjaXRpZXMiO2E6MDp7fXM6MTg6IgBTZWFyY2gAY2l0eV9hcmVhcyI7YTowOnt9czoxNToiAFNlYXJjaAByZWdpb25zIjthOjA6e31zOjE3OiIAU2VhcmNoAGNvdW50cmllcyI7YTowOnt9czoxODoiAFNlYXJjaABjYXRlZ29yaWVzIjthOjIzOntpOjA7czozMToib2NfdF9pdGVtLmZrX2lfY2F0ZWdvcnlfaWQgPSAxICI7aToxO3M6MzE6Im9jX3RfaXRlbS5ma19pX2NhdGVnb3J5X2lkID0gOSAiO2k6MjtzOjMyOiJvY190X2l0ZW0uZmtfaV9jYXRlZ29yeV9pZCA9IDEwICI7aTozO3M6MzI6Im9jX3RfaXRlbS5ma19pX2NhdGVnb3J5X2lkID0gMTEgIjtpOjQ7czozMjoib2NfdF9pdGVtLmZrX2lfY2F0ZWdvcnlfaWQgPSAxMiAiO2k6NTtzOjMyOiJvY190X2l0ZW0uZmtfaV9jYXRlZ29yeV9pZCA9IDEzICI7aTo2O3M6MzI6Im9jX3RfaXRlbS5ma19pX2NhdGVnb3J5X2lkID0gMTQgIjtpOjc7czozMjoib2NfdF9pdGVtLmZrX2lfY2F0ZWdvcnlfaWQgPSAxNSAiO2k6ODtzOjMyOiJvY190X2l0ZW0uZmtfaV9jYXRlZ29yeV9pZCA9IDE2ICI7aTo5O3M6MzI6Im9jX3RfaXRlbS5ma19pX2NhdGVnb3J5X2lkID0gMTcgIjtpOjEwO3M6MzI6Im9jX3RfaXRlbS5ma19pX2NhdGVnb3J5X2lkID0gMTggIjtpOjExO3M6MzI6Im9jX3RfaXRlbS5ma19pX2NhdGVnb3J5X2lkID0gMTkgIjtpOjEyO3M6MzI6Im9jX3RfaXRlbS5ma19pX2NhdGVnb3J5X2lkID0gMjAgIjtpOjEzO3M6MzI6Im9jX3RfaXRlbS5ma19pX2NhdGVnb3J5X2lkID0gMjEgIjtpOjE0O3M6MzI6Im9jX3RfaXRlbS5ma19pX2NhdGVnb3J5X2lkID0gMjIgIjtpOjE1O3M6MzI6Im9jX3RfaXRlbS5ma19pX2NhdGVnb3J5X2lkID0gMjMgIjtpOjE2O3M6MzI6Im9jX3RfaXRlbS5ma19pX2NhdGVnb3J5X2lkID0gMjQgIjtpOjE3O3M6MzI6Im9jX3RfaXRlbS5ma19pX2NhdGVnb3J5X2lkID0gMjUgIjtpOjE4O3M6MzI6Im9jX3RfaXRlbS5ma19pX2NhdGVnb3J5X2lkID0gMjYgIjtpOjE5O3M6MzI6Im9jX3RfaXRlbS5ma19pX2NhdGVnb3J5X2lkID0gMjcgIjtpOjIwO3M6MzI6Im9jX3RfaXRlbS5ma19pX2NhdGVnb3J5X2lkID0gMjggIjtpOjIxO3M6MzI6Im9jX3RfaXRlbS5ma19pX2NhdGVnb3J5X2lkID0gMjkgIjtpOjIyO3M6MzI6Im9jX3RfaXRlbS5ma19pX2NhdGVnb3J5X2lkID0gMzAgIjt9czoxNDoiAFNlYXJjaABmaWVsZHMiO2E6MDp7fXM6MjE6IgBTZWFyY2gAdG90YWxfcmVzdWx0cyI7czoxOiIzIjtzOjE2OiIAKgBtZXRhZGF0YV9jb25uIjtOO30=";
            $s_email     = 'test@email.com';
            $s_secret    = osc_genRandomPassword();
            $type        = 'DAILY';
            
            $res = $this->model->createAlert( 0, $s_email, $s_search, $s_secret, $type) ;
            $this->assertTrue($res, $this->model->dao->error_level ) ;
            // same s_search return FALSE
            $res = $this->model->createAlert( 0, $s_email, $s_search, $s_secret, $type) ;
            $this->assertFalse($res, $this->model->dao->error_level ) ;
            
            for($i=0; $i<10;$i++){
                $s_secret    = osc_genRandomPassword();
                array_push(self::$aSecret, $s_secret) ;
                $s_search = base64_encode($i) ;
                $res = $this->model->createAlert( 0, $s_email, $s_search, $s_secret, $type) ;
                $this->assertTrue($res, $this->model->dao->error_level ) ;
            }
            
            for($i=1; $i<11;$i++){
                $s_secret    = osc_genRandomPassword();
                $s_search = base64_encode($i-1) ;
                $res = $this->model->createAlert( $i, $s_email, $s_search, $s_secret, $type) ;
                $this->assertTrue($res, $this->model->dao->error_level ) ;
            }
            
            $s_email = "new@email.com" ;
            $type    = 'WEEKLY' ;
            for($i=2; $i<12;$i++){
                $s_secret    = osc_genRandomPassword();
                $s_search = base64_encode($i-2) ;
                $res = $this->model->createAlert( $i, $s_email, $s_search, $s_secret, $type) ;
                $this->assertTrue($res, $this->model->dao->error_level ) ;
            }
        }
        
        public function testGetAlertsFromUser()
        {
            // assert count($array_result)
            $result = $this->model->findByUser(1) ;
            $this->assertEquals('1', count($result)) ;
            
            // assert keys & values
            $this->assertArrayHasKey('fk_i_user_id', $result[0]) ;
            $this->assertEquals('1', $result[0]['fk_i_user_id'] ) ;
            
            $this->assertArrayHasKey('s_email'  , $result[0]) ;
            $this->assertEquals('test@email.com', $result[0]['s_email'] ) ;
            
            $this->assertArrayHasKey('s_search' , $result[0]) ;
            $this->assertEquals(base64_encode('0'), $result[0]['s_search'] ) ;
            
            $this->assertArrayHasKey('s_secret' , $result[0]) ;
            $this->assertNotNull($result[0]['s_secret'] ) ;
            
            $this->assertArrayHasKey('b_active' , $result[0]) ;
            $this->assertEquals('0', $result[0]['b_active'] ) ;
            
            $this->assertArrayHasKey('e_type'   , $result[0]) ;
            $this->assertEquals('DAILY', $result[0]['e_type'] ) ;
        }
        
        public function  testGetAlertsFromEmail()
        {
            $s_email     = 'test@email.com';
            $result = $this->model->findByEmail($s_email) ;
            $this->assertEquals('21', count($result)) ;
            $row = $result[0] ;
            $this->assertEquals($s_email, $row['s_email']) ;
            
            $s_email = 'new@email.com' ;
            $result = $this->model->findByEmail($s_email) ;
            $this->assertEquals('10', count($result)) ;
            $row = $result[0] ;
            $this->assertEquals($s_email, $row['s_email']) ;
            
            $result = $this->model->findByEmail('inexistene@email.com') ;
            $this->assertEquals('0', count($result)) ;
        }
        
        public function testGetAlertsByType()
        {
            $result = $this->model->findByType('DAILY') ;
            $this->assertEquals('21', count($result)) ;
            
            $result = $this->model->findByType('WEEKLY') ;
            $this->assertEquals('10', count($result)) ;
            
            $result = $this->model->findByType('FOOBAR') ;
            $this->assertEquals('0', count($result)) ;
        }
        
        public function testActivate()
        {
            $email = 'test@email.com' ;
            $array = self::$aSecret;
            foreach($array as $secret) {
                $res = $this->model->activate($email, $secret) ;
                $this->assertEquals('1', $res, $this->model->dao->lastQuery()) ;
            }
        }
        
        public function testGetAlertsByTypeGroup()
        {
            $result = $this->model->findByTypeGroup('DAILY') ;
            $this->assertEquals('11', count($result), $this->model->dao->lastQuery()) ;
            
            $result = $this->model->findByTypeGroup('DAILY', TRUE) ;
            $this->assertEquals('10', count($result), $this->model->dao->lastQuery()) ;
            
            $result = $this->model->findByTypeGroup('WEEKLY') ;
            $this->assertEquals('10', count($result), $this->model->dao->lastQuery()) ;
            
            $result = $this->model->findByTypeGroup('WEEKLY', TRUE) ;
            $this->assertEquals('0', count($result), $this->model->dao->lastQuery()) ;
        }
        
        public function testGetAlertsBySearchAndType()
        {
            $search = base64_encode('1') ;
            $type   = 'DAILY' ;
            $result = $this->model->findBySearchAndType($search, $type) ;
            $this->assertEquals('2', count($result), $this->model->dao->lastQuery()) ;
            
            $type   = 'WEEKLY' ;
            $result = $this->model->findBySearchAndType($search, $type) ;
            $this->assertEquals('1', count($result), $this->model->dao->lastQuery()) ;
        }
        
        public function testGetUsersBySearchAndType()
        {
            $search = base64_encode('1') ;
            $type   = 'DAILY' ;
            $result = $this->model->findUsersBySearchAndType($search, $type) ;
            $this->assertEquals('1', count($result), $this->model->dao->lastQuery()) ;
            
            $result = $this->model->findUsersBySearchAndType($search, $type, FALSE) ;
            $this->assertEquals('1', count($result), $this->model->dao->lastQuery()) ;
        }
        
        public function testGetAlertsFromUserByType()
        {
            $type  = 'DAILY' ;
            $result = $this->model->findByUserByType('1', $type) ;
            $this->assertEquals('1', count($result), $this->model->dao->lastQuery()) ;
        }
     
        public function testGetAlertsFromEmailByType()
        {
            $email = 'test@email.com' ;
            $type  = 'DAILY' ;
            $result = $this->model->findByEmailByType($email, $type);
            $this->assertEquals('21', count($result), $this->model->dao->lastQuery()) ;
        }
        
        public function testDelete()
        {
            $conditions = array('s_email' => 'test@email.com') ;
            $res = $this->model->dao->delete( $this->model->table_name, $conditions ) ;
            $this->assertTrue($res, $this->model->dao->lastQuery()) ;
            $res = $this->model->dao->delete( $this->model->table_name, array('s_email' => 'new@email.com' ) ) ;
            $this->assertTrue($res, $this->model->dao->lastQuery()) ;
        }
    }
    
?>
