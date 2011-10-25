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
  
    require_once 'oc-load.php';
    /**
     * Run: $> phpunit AlertsTest.php
     */
    
    class PageTest extends PHPUnit_Framework_TestCase
    {
        private $model ;
        protected static $aSecret = array();
        
        public function __construct()
        {
            parent::__construct() ;
            $this->model = new Page() ;
        }

        public function testInsertPage()
        {
            $aFields = array(
                's_internal_name'   => 'test_page_one',
                'b_indelible'       => 0
            );
            $en_US = array(
                's_title'   => 'Page test one',
                's_text'    => 'Page test description one'
            );
            $aFieldsDescription = array(
                'en_us' => $en_US
            );
            $res = $this->model->insert($aFields, $aFieldsDescription) ;
            $this->assertTrue($res, 'Cannot insert page');
            
            $page = $this->model->findByInternalName('test_page_one') ;
            self::$aSecret['pageID'] = $page['pk_i_id'];
        }
        
        public function testExistDescription()
        {
            $conditions = array(
                'fk_i_pages_id' => self::$aSecret['pageID']
            );
            $results = $this->model->existDescription($conditions) ;
            $this->assertTrue($results, 'No exist description');
            
            $conditions_['fk_i_pages_id'] = 9999;
            $results = $this->model->existDescription($conditions_) ;
            $this->assertFalse($results, 'Exist description');
            
            $conditions['fk_i_pages_id']    = self::$aSecret['pageID'] ;
            $conditions['fk_c_locale_code'] = 'en_US' ;
            $results = $this->model->existDescription($conditions) ;
            $this->assertTrue($results, 'No exist description');
        }
        
        public function testUpdateInternalName()
        {
            $res = $this->model->updateInternalName(self::$aSecret['pageID'], 'new_test_page_one');
            $this->assertGreaterThan(0, $res);
            $result = $this->model->findByInternalName('new_test_page_one');
            foreach($this->model->getFields() as $field){
                $this->assertArrayHasKey($field, $result);
            }
        }
        
        public function testIsIndelible()
        {
            $res = $this->model->isIndelible(self::$aSecret['pageID']);
            $this->assertFalse($res);
        }
        
        
        public function testInternalNameExist()
        {
            $res = $this->model->internalNameExists(self::$aSecret['pageID'], 'new_test_page_one');
            $this->assertTrue($res);
        }
        
        public function testUpdateDescription()
        {
            $en_US = array(
                's_title'   => 'new Page test one',
                's_text'    => 'new Page test description one'
            );
            $res = $this->model->updateDescription(self::$aSecret['pageID'], 'en_US', $en_US['s_title'], $en_US['s_text']) ;
            $this->assertEquals(1, $res);
        }
        
        public function testDeletePage()
        {
            $res = $this->model->deleteByPrimaryKey( self::$aSecret['pageID'] );
            $this->assertGreaterThan(0, $res, 'Cannot delete page');
        }
    }
    
?>
