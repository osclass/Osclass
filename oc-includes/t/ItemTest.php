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
     * Run: $> phpunit ItemTest.php
     */
    class ItemTest extends PHPUnit_Framework_TestCase
    {
        private $model ;
        protected static $aInfo = array();


        public function __construct()
        {
            parent::__construct() ;
            $this->model = new Item() ;
        }

        /**
         * insert Item / insert Item Locations
         * Add ONE user1;
         * Add TWO items like user1
         */
        
        public function testInsert()
        {
            include_once 'DataItemTest/user.php';
            //insert user for testing propouse
            $user = new User();
            $res = $user->insert($array_set_user);
            $this->assertTrue($res, $user->dao->lastQuery());
            self::$aInfo['userID'] = $user->dao->insertedId();
            // -----------------------------------------------------------------
            $locale = Preference::newInstance()->findValueByName('language') ;
            // item 1 ----------------------------------------------------------
            include_once 'DataItemTest/item1.php';
            $res = $this->model->dao->insert($this->model->getTableName(), $array_set1);
            $this->assertTrue($res, $this->model->dao->lastQuery());
            self::$aInfo['itemID1']['id'] = $this->model->dao->insertedId();
            $res = $this->model->insertLocale(self::$aInfo['itemID1']['id'], $locale, $title1, $description1, $what1) ;
            $this->assertTrue($res, $this->model->dao->lastQuery());
            // item 1 Location -------------------------------------------------
            include_once 'DataItemTest/item1Location.php';
            $res = ItemLocation::newInstance()->insert($array_location1) ;
            $this->assertTrue($res, ItemLocation::newInstance()->dao->lastQuery());
            // item 2 ----------------------------------------------------------
            include_once 'DataItemTest/item2.php';
            $res = $this->model->dao->insert($this->model->getTableName(), $array_set2);
            $this->assertTrue($res, $this->model->dao->lastQuery());
            self::$aInfo['itemID2']['id'] = $this->model->dao->insertedId();
            self::$aInfo['itemID2']['array'] = $array_set2;
            
            $res = $this->model->insertLocale(self::$aInfo['itemID2']['id'], $locale, $title2, $description2, $what2) ;
            $this->assertTrue($res, $this->model->dao->lastQuery());
            // item 2 Location -------------------------------------------------
            include_once 'DataItemTest/item2Location.php';
            $res = ItemLocation::newInstance()->insert($array_location2) ;
            $this->assertTrue($res, ItemLocation::newInstance()->dao->lastQuery());
            // item 3 ----------------------------------------------------------
            include_once 'DataItemTest/item3.php';
            $res = $this->model->dao->insert($this->model->getTableName(), $array_set3);
            self::$aInfo['itemID3']['id'] = $this->model->dao->insertedId();
            self::$aInfo['itemID3']['array'] = $array_set3;
            $this->assertTrue($res, $this->model->dao->lastQuery());
            $res = $this->model->insertLocale(self::$aInfo['itemID3']['id'], $locale, $title3, $description3, $what3) ;
            $this->assertTrue($res, $this->model->dao->lastQuery());
            // item 3 Location -------------------------------------------------
            include_once 'DataItemTest/item3Location.php';
            $res = ItemLocation::newInstance()->insert($array_location3) ;
            $this->assertTrue($res, ItemLocation::newInstance()->dao->lastQuery());
        }
        
        public function testFindByCategoryID()
        {
            $result = $this->model->findByCategoryID(1);
            $this->assertEquals(2, count($result), $this->model->dao->lastQuery()) ;
            $result = $this->model->findByCategoryID(2);
            $this->assertEquals(1, count($result), $this->model->dao->lastQuery()) ;
            $result = $this->model->findByCategoryID(9999);
            $this->assertEquals(0, count($result), $this->model->dao->lastQuery()) ;
            $this->assertEmpty($result, $this->model->dao->lastQuery()) ;
        }
        
        public function testListAllWithCategories()
        {
           $result = $this->model->listAllWithCategories();
           $this->assertEquals(3, count($result), $this->model->dao->lastQuery() );
           $lastItem    = end($result);
           $categoryId  = $lastItem['fk_i_category_id'];
           $category    = Category::newInstance()->findByPrimaryKey($categoryId);
           $this->assertEquals($category['s_name'], $lastItem['s_category_name'], 'Item Category NOT THE SAME with Category->s_name');
        }
        
        public function testFindByUserID()
        {
            $result = $this->model->findByUserID(self::$aInfo['userID']);
            $this->assertEquals(2, count($result), $this->model->dao->lastQuery());
            foreach($result as $item){
                $this->assertEquals(self::$aInfo['userID'], $item['fk_i_user_id'], 'User NOT equal') ;
            }
            
            $result = $this->model->findByUserID(self::$aInfo['userID'], 1);
            $this->assertEquals(1, count($result), $this->model->dao->lastQuery());
            $item = $result[0];
            $this->assertEquals(2200, $item['i_price'], $this->model->dao->lastQuery());
        }
        
        public function testFindByUserIDEnabled()
        {
            $result = $this->model->findByUserIDEnabled(self::$aInfo['userID']);
            $this->assertEquals(2, count($result), $this->model->dao->lastQuery());
            foreach($result as $item){
                $this->assertEquals(self::$aInfo['userID'], $item['fk_i_user_id'], 'User NOT equal') ;
            }
            
            $result = $this->model->findByUserIDEnabled(self::$aInfo['userID'], 1);
            $this->assertEquals(1, count($result), $this->model->dao->lastQuery());
            $item = $result[0];
            $this->assertEquals(2200, $item['i_price'], $this->model->dao->lastQuery());
            
            // update b_enabled = 0
            $res = $this->model->dao->update($this->model->getTableName(), array('b_enabled' => 0), array('pk_i_id' => self::$aInfo['itemID2']['id'] ) ) ;
            $this->assertEquals(1, $res, $this->model->dao->lastQuery());
            $result = $this->model->findByUserIDEnabled(self::$aInfo['userID']);
            $this->assertEquals(1, count($result), $this->model->dao->lastQuery());
            // update again b_enabled = 1
            $res = $this->model->dao->update($this->model->getTableName(), array('b_enabled' => 1), array('pk_i_id' => self::$aInfo['itemID2']['id'] ) ) ;
            $this->assertEquals(1, $res, $this->model->dao->lastQuery());
        }
        
        public function testListLatest()
        {
            $result = $this->model->listLatest();
            $this->assertEquals(0, count($result), $this->model->dao->lastQuery());
        }
        
        public function testActivateItem()
        {
            $res = $this->model->dao->update($this->model->getTableName(), array('b_active' => 1), array('pk_i_id' => self::$aInfo['itemID3']['id'] ) ) ;
            $this->assertEquals(1, $res, $this->model->dao->lastQuery());
            $res = $this->model->dao->update($this->model->getTableName(), array('b_active' => 1), array('pk_i_id' => self::$aInfo['itemID2']['id'] ) ) ;
            $this->assertEquals(1, $res, $this->model->dao->lastQuery());
            $result = $this->model->listLatest();
            $this->assertEquals(2, count($result), $this->model->dao->lastQuery());
        }
        
        public function testTotalItems()
        {
            $res = $this->model->totalItems() ;
            $this->assertEquals(3, $res, 'Item, totalItems()') ;
            // total category
            $res = $this->model->totalItems(2) ;
            $this->assertEquals(1, $res, 'Item, totalItems()') ;
            $res = $this->model->totalItems(1) ;
            $this->assertEquals(2, $res, 'Item, totalItems()') ;
            // category 2 & ACTIVE
            $res = $this->model->totalItems(null, 'ACTIVE') ;
            $this->assertEquals(2, $res, 'Item, totalItems()') ;
            $res = $this->model->dao->update($this->model->getTableName(), array('b_active' => 0), array('pk_i_id' => self::$aInfo['itemID2']['id'] ) ) ;
            $this->assertEquals(1, $res, $this->model->dao->lastQuery());
            $res = $this->model->totalItems(null, 'ACTIVE') ;
            $this->assertEquals(1, $res, 'Item, totalItems()') ;
        }
        
        public function testREListLatest()
        {
            $result = $this->model->listLatest();
            $this->assertEquals(1, count($result), $this->model->dao->lastQuery());
        }
        
        public function testCountByUserID()
        {
            $res = $this->model->countByUserID(self::$aInfo['userID']);
            $this->assertEquals(2, $res, $this->model->dao->lastQuery());
        }
        
        public function testCountByUserIDEnabled()
        {
            $res = $this->model->countByUserIDEnabled(self::$aInfo['userID']);
            $this->assertEquals(2, $res, $this->model->dao->lastQuery());
        }
        
        public function testStats()
        {
            // add stats ItemStats
            // ItemStats::newInstance()->increase('i_num_spam',self::$aInfo['itemID2']['id']);
            // ItemStats::newInstance()->increase('i_num_repeated',self::$aInfo['itemID2']['id']);
            // ItemStats::newInstance()->increase('i_num_bad_classified',self::$aInfo['itemID2']['id']);
            // ItemStats::newInstance()->increase('i_num_offensive',self::$aInfo['itemID2']['id']);
            // ItemStats::newInstance()->increase('i_num_expired',self::$aInfo['itemID2']['id']);
            // check 
            
        }
        
        public function testDeleteall()
        {
            $res = User::newInstance()->deleteUser(self::$aInfo['userID']) ;
            $this->assertGreaterThan(0, $res, $this->model->dao->lastQuery());
            $res = Item::newInstance()->deleteByPrimaryKey(self::$aInfo['itemID3']['id']) ;
            $this->assertGreaterThan(0, $res, $this->model->dao->lastQuery());
        }
    }
?>
