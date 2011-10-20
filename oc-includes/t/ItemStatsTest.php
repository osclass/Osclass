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
    require_once '../osclass/plugins.php' ;
    
    /**
     * Run: $> phpunit ItemStatsTest.php
     */
    
    class ItemStatsTest extends PHPUnit_Framework_TestCase
    {
        private $model;
        protected static $aInfo = array();
        
        public function __construct()
        {
            parent::__construct() ;
            $this->model = new ItemStats() ;
        }
        
        public function testInsert()
        {
            // insert two items 
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
            $res = ItemLocation::newInstance()->insert($array_location1) ;
            $this->assertTrue($res, $this->model->dao->errorLevel);
            // item 2 ----------------------------------------------------------
            $res = Item::newInstance()->insert($array_set1);
            self::$aInfo['itemID2']['id'] = Item::newInstance()->dao->insertedId();
            self::$aInfo['itemID2']['array'] = $array_set1;
            $this->assertTrue($res, Item::newInstance()->dao->lastQuery());
            $res = Item::newInstance()->insertLocale(self::$aInfo['itemID2']['id'], $locale, $title1, $description1, $what1) ;
            $this->assertTrue($res, Item::newInstance()->dao->lastQuery());
            // item 2 Location -------------------------------------------------
            include_once 'data/ItemLocationData1.2.php';
            $res = ItemLocation::newInstance()->insert($array_location2) ;
            $this->assertTrue($res, $this->model->dao->lastQuery());
            // stats
            $res = $this->model->emptyRow(self::$aInfo['itemID1']['id']);
            $this->assertTrue($res, $this->model->dao->lastQuery());
            $res = $this->model->emptyRow(self::$aInfo['itemID2']['id']);
            $this->assertTrue($res, $this->model->dao->lastQuery());
        }
        
        public function testIncrease()
        {
            for($i=0;$i<5;$i++) {
                $res = $this->model->increase('i_num_views', self::$aInfo['itemID1']['id']);
                $this->assertGreaterThan(0, $res, $this->model->dao->lastQuery());
            }
            for($i=0;$i<6;$i++) {
                $res = $this->model->increase('i_num_spam', self::$aInfo['itemID1']['id']);
                $this->assertGreaterThan(0, $res, $this->model->dao->lastQuery());
            }
            for($i=0;$i<7;$i++) {
                $res = $this->model->increase('i_num_repeated', self::$aInfo['itemID1']['id']);
                $this->assertGreaterThan(0, $res, $this->model->dao->lastQuery());
            }
            for($i=0;$i<8;$i++) {
                $res = $this->model->increase('i_num_bad_classified', self::$aInfo['itemID1']['id']);
                $this->assertGreaterThan(0, $res, $this->model->dao->lastQuery());
            }
            for($i=0;$i<4;$i++) {
                $res = $this->model->increase('i_num_offensive', self::$aInfo['itemID2']['id']);
                $this->assertGreaterThan(0, $res, $this->model->dao->lastQuery());
            }
            for($i=0;$i<3;$i++) {
                $res = $this->model->increase('i_num_expired', self::$aInfo['itemID2']['id']);
                $this->assertGreaterThan(0, $res, $this->model->dao->lastQuery());
            }
            for($i=0;$i<2;$i++) {
                $res = $this->model->increase('i_num_premium_views', self::$aInfo['itemID2']['id']);
                $this->assertGreaterThan(0, $res, $this->model->dao->lastQuery());
            }
            $item1 = $this->model->findByPrimaryKey(self::$aInfo['itemID1']['id']);
            $item2 = $this->model->findByPrimaryKey(self::$aInfo['itemID2']['id']);
            
            $this->assertEquals(5, $item1['i_num_views']);
            $this->assertEquals(6, $item1['i_num_spam']);
            $this->assertEquals(7, $item1['i_num_repeated']);
            $this->assertEquals(8, $item1['i_num_bad_classified']);
            $this->assertEquals(4, $item2['i_num_offensive']);
            $this->assertEquals(3, $item2['i_num_expired']);
            $this->assertEquals(2, $item2['i_num_premium_views']);
            
            $this->assertEquals(0, $item2['i_num_views']);
            $this->assertEquals(0, $item1['i_num_expired']);
        }
        
        public function testDeleteAll()
        {
            $res = Item::newInstance()->deleteByPrimaryKey(self::$aInfo['itemID1']['id']);
            $this->assertGreaterThan(0, $res, 'error in deleteByPrimarykey') ;
            $res = Item::newInstance()->deleteByPrimaryKey(self::$aInfo['itemID2']['id']);
            $this->assertGreaterThan(0, $res, 'error in deleteByPrimarykey') ;
        }
    }
?>
