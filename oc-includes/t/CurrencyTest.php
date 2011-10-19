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

    if( !defined('ABS_PATH') ) {
        define( 'ABS_PATH', dirname(__FILE__) . '/../../' );
    }
    
    define('LIB_PATH', ABS_PATH . 'oc-includes/') ;
    define('CONTENT_PATH', ABS_PATH . 'oc-content/') ;
    define('THEMES_PATH', CONTENT_PATH . 'themes/') ;
    define('PLUGINS_PATH', CONTENT_PATH . 'plugins/') ;
    define('TRANSLATIONS_PATH', CONTENT_PATH . 'languages/') ;
    
    require_once '../osclass/Logger/LogDatabase.php' ;
    require_once '../osclass/helpers/hDatabaseInfo.php' ;
    require_once '../osclass/classes/data/DBConnectionClass.php' ;
    require_once '../osclass/classes/data/DBCommandClass.php' ;
    require_once '../osclass/classes/data/DBRecordsetClass.php' ;
    require_once '../osclass/classes/data/DAO.php' ;

    require_once '../osclass/model/new_model/Currency.php' ;
    /**
     * Run: $> phpunit CurrencyTest.php
     */
    
    class CurrencyTest extends PHPUnit_Framework_TestCase
    {
        private $model;
        protected static $aInfo = array();
        
        public function __construct()
        {
            parent::__construct() ;
            $this->model = new Currency() ;
        }
        
        public function testInsert()
        {
            $array_set = array(
                'pk_c_code'     => 'FOO',
                's_name'        => 'Foo currency my home',
                's_description' => 'Foo FB',
                'b_enabled'     => 1
            );
            $res = $this->model->insert($array_set) ;
            self::$aInfo['currency1']['id']    = $array_set['pk_c_code'];
            self::$aInfo['currency1']['array'] = $array_set;
            $this->assertTrue($res, $this->model->dao->lastQuery());
        }
        
        public function testListAll()
        {
            $result = $this->model->listAll();
            $this->assertEquals(4, count($result), 'listAll diferent to 4.') ;
            
            foreach($this->model->getFields() as $field) {
                $this->assertArrayHasKey($field, $result[0]) ;
            }
        }
        
        public function testFindByPrimaryKey()
        {
            $result = $this->model->findByPrimaryKey(self::$aInfo['currency1']['id']);
            $this->assertEquals($result['pk_c_code'], self::$aInfo['currency1']['array']['pk_c_code'], $this->model->dao->lastQuery());
            $array_aux = self::$aInfo['currency1']['array'];
            foreach($this->model->getFields() as $field){
                $this->assertEquals($array_aux[$field], $result[$field]);
            }
        }
        
        public function testUpdate()
        {
            $array_set      = array(
                's_name'    => 'new NAME',
                'b_enabled' => 0
            );
            $array_where    = array(
                's_description' => 'Foo FB'
            );
            $res = $this->model->update($array_set, $array_where) ;
            $this->assertGreaterThan(0, $res, $this->model->dao->lastQuery());
            $result = $this->model->findByPrimaryKey(self::$aInfo['currency1']['id']);
            $this->assertEquals('new NAME', $result['s_name']) ;
            $this->assertEquals(0, $result['b_enabled']) ;
        }
        
        public function testUpdateByPrimaryKey()
        {
            $array_set      = array(
                's_name'    => 'Foo currency my home',
                'b_enabled' => 1
            );
            $res = $this->model->updateByPrimaryKey($array_set, self::$aInfo['currency1']['id']);
            $this->assertGreaterThan(0, $res, $this->model->dao->lastQuery());
            $result = $this->model->findByPrimaryKey(self::$aInfo['currency1']['id']);
            $this->assertEquals('Foo currency my home', $result['s_name']) ;
            $this->assertEquals(1, $result['b_enabled']) ;
        }
        
        public function testDelete()
        {
            $array_where = array(
                's_name' => 'Foo currency my home'
            );
            $res = $this->model->delete($array_where);
            $this->assertGreaterThan(0, $res, 'Cannot delete currency by s_name');
            $result = $this->model->findByPrimaryKey(self::$aInfo['currency1']['id']);
            $this->assertEmpty($result);
        }
        
        public function testDeleteByPrimaryKey()
        {
            $array_set = array(
                'pk_c_code'     => 'FOO',
                's_name'        => 'Foo currency my home',
                's_description' => 'Foo FB',
                'b_enabled'     => 1
            );
            $res = $this->model->insert($array_set) ;
            $this->assertTrue($res, $this->model->dao->lastQuery());
            $currencyId = $this->model->dao->insertedId();
            
            $res = $this->model->deleteByPrimaryKey($currencyId);
            $this->assertGreaterThan(0, $res, 'error in deleteByPrimarykey') ;
        }
    }
?>