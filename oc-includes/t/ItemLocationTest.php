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

    require_once '../osclass/model/new_model/Item.php' ;
    require_once '../osclass/model/new_model/User.php';
    require_once '../osclass/model/new_model/Category.php';
    require_once '../osclass/model/new_model/Preference.php';
    require_once '../osclass/model/new_model/ItemLocation.php';
    require_once '../osclass/model/new_model/ItemResource.php';
    require_once '../osclass/model/new_model/ItemStats.php';
    require_once '../osclass/model/new_model/CategoryStats.php';
    
    require_once '../osclass/helpers/hSecurity.php' ;
    require_once '../osclass/helpers/hLocale.php' ;
    require_once '../osclass/helpers/hPreference.php';
    require_once '../osclass/helpers/hDatabaseInfo.php';
    require_once '../osclass/helpers/hDefines.php';
    require_once '../osclass/helpers/hLocale.php';
    require_once '../osclass/helpers/hMessages.php';
    require_once '../osclass/helpers/hUsers.php';
    require_once '../osclass/helpers/hItems.php';
    require_once '../osclass/helpers/hSearch.php';
    require_once '../osclass/helpers/hUtils.php';
    require_once '../osclass/helpers/hCategories.php';
    require_once '../osclass/helpers/hTranslations.php';
    require_once '../osclass/helpers/hSecurity.php';
    require_once '../osclass/helpers/hSanitize.php';
    require_once '../osclass/helpers/hValidate.php';
    require_once '../osclass/helpers/hPage.php';
    require_once '../osclass/helpers/hPagination.php';
    require_once '../osclass/helpers/hPremium.php';
    require_once '../osclass/helpers/hTheme.php';
    require_once '../osclass/core/Params.php';
    require_once '../osclass/core/Cookie.php';
    require_once '../osclass/core/Session.php';
    require_once '../osclass/core/Translation.php' ;
    require_once '../osclass/Plugins.php' ;
    
    /**
     * Run: $> phpunit ItemLocationTest.php
     */
    
    class ItemLocationTest extends PHPUnit_Framework_TestCase
    {
        private $model;
        protected static $aInfo = array();
        
        public function __construct()
        {
            parent::__construct() ;
            $this->model = new ItemLocation() ;
        }
        
        public function testInsert()
        {
            include_once 'data/ItemLocationData1.php';
            // -----------------------------------------------------------------
            $locale = Preference::newInstance()->findValueByName('language') ;
            // item 1 ----------------------------------------------------------
            $res = Item::newInstance()->insert($array_set1);
            self::$aInfo['itemID1']['id'] = Item::newInstance()->dao->insertedId();
            self::$aInfo['itemID1']['array'] = $array_set1;
            $this->assertTrue($res, Item::newInstance()->dao->lastQuery());
            $res = Item::newInstance()->insertLocale(self::$aInfo['itemID1']['id'], $locale, $title1, $description1, $what1) ;
            $this->assertTrue($res, Item::newInstance()->dao->lastQuery());
            // item 1 Location -------------------------------------------------
            include_once 'data/ItemLocationData1.1.php';
            $res = $this->model->insert($array_location1) ;
            $this->assertTrue($res, $this->model->dao->lastQuery());
            // item 2 ----------------------------------------------------------
            $res = Item::newInstance()->insert($array_set1);
            self::$aInfo['itemID2']['id'] = Item::newInstance()->dao->insertedId();
            self::$aInfo['itemID2']['array'] = $array_set1;
            $this->assertTrue($res, Item::newInstance()->dao->lastQuery());
            $res = Item::newInstance()->insertLocale(self::$aInfo['itemID2']['id'], $locale, $title1, $description1, $what1) ;
            $this->assertTrue($res, Item::newInstance()->dao->lastQuery());
            // item 2 Location -------------------------------------------------
            include_once 'data/ItemLocationData1.2.php';
            $res = $this->model->insert($array_location2) ;
            $this->assertTrue($res, $this->model->dao->lastQuery());
        }
        
        public function testListAll()
        {
            include 'data/ItemLocationData1.1.php';
            include 'data/ItemLocationData1.2.php';
            
            $result = $this->model->listAll();
            $this->assertEquals(2, count($result), 'listAll diferent to 2.') ;
            
            foreach($this->model->getFields() as $field) {
                $this->assertArrayHasKey($field, $result[0]) ;
                if(isset($array_location1[$field])) {
                    $this->assertEquals($array_location1[$field], $result[0][$field], $array_location1[$field].'  ->  '.$result[0][$field]);
                }
            }
        }
        
        public function testFindByPrimaryKey()
        {
            $result = $this->model->findByPrimaryKey(self::$aInfo['itemID1']['id']);
            $this->assertEquals($result['fk_i_item_id'], self::$aInfo['itemID1']['id'], $this->model->dao->lastQuery());
        }
        
        public function testUpdate()
        {
            $array_set      = array(
                'fk_i_city_id'  => '1992',
                's_city'        => 'Bornos'
            );
            $array_where    = array(
                'fk_i_region_id' => 61
            );
            $res = $this->model->update($array_set, $array_where) ;
            $this->assertGreaterThan(0, $res, $this->model->dao->lastQuery());
            $result = $this->model->findByPrimaryKey(self::$aInfo['itemID1']['id']);
            $this->assertEquals(1992, $result['fk_i_city_id']) ;
            $this->assertEquals('Bornos', $result['s_city']) ;
        }
        
        public function testUpdateByPrimaryKey()
        {
            $array_set      = array(
                'fk_i_city_id'  => '1994',
                's_city'        => 'Cádiz'
            );
            $res = $this->model->updateByPrimaryKey($array_set, self::$aInfo['itemID1']['id']);
            $this->assertGreaterThan(0, $res, $this->model->dao->lastQuery());
            $result = $this->model->findByPrimaryKey(self::$aInfo['itemID1']['id']);
            $this->assertEquals(1994, $result['fk_i_city_id']) ;
            $this->assertEquals('Cádiz', $result['s_city']) ;
        }
        
        public function testDelete()
        {
            $array_where = array(
                'fk_i_city_id' => 1994
            );
            $res = $this->model->delete($array_where);
            $this->assertGreaterThan(0, $res, 'Cannot delete by fk_i_city_id');
            $result = $this->model->findByPrimaryKey(self::$aInfo['itemID1']['id']);
            $this->assertEmpty($result);
        }
        
        public function testDeleteByPrimaryKey()
        {
            $res = $this->model->deleteByPrimaryKey(self::$aInfo['itemID2']['id']);
            $this->assertGreaterThan(0, $res, 'error in deleteByPrimarykey') ;
            
            $res = Item::newInstance()->deleteByPrimaryKey(self::$aInfo['itemID1']['id']);
            $this->assertGreaterThan(0, $res, 'error in deleteByPrimarykey') ;
            $res = Item::newInstance()->deleteByPrimaryKey(self::$aInfo['itemID2']['id']);
            $this->assertGreaterThan(0, $res, 'error in deleteByPrimarykey') ;
        }
    }
?>