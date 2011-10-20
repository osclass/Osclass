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
    require_once '../osclass/model/new_model/ItemComment.php';
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
     * Run: $> phpunit ItemTest.php
     */
    class ItemCommentTest extends PHPUnit_Framework_TestCase
    {
        private $model ;
        protected static $aInfo = array();


        public function __construct()
        {
            parent::__construct() ;
            $this->model = new ItemComment() ;
        }

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
            include 'data/ItemCommentData1.php';
            $res = Item::newInstance()->insert($array_set1);
            self::$aInfo['itemID1']['id'] = Item::newInstance()->dao->insertedId();
            self::$aInfo['itemID1']['array'] = $array_set1;
            $this->assertTrue($res, Item::newInstance()->dao->lastQuery());
            $res = Item::newInstance()->insertLocale(self::$aInfo['itemID1']['id'], $locale, $title1, $description1, $what1) ;
            $this->assertTrue($res, Item::newInstance()->dao->lastQuery());
            // item 1 Location -------------------------------------------------
            include_once 'data/ItemLocationData1.1.php';
            $res = ItemLocation::newInstance()->insert($array_location1) ;
            $this->assertTrue($res, ItemLocation::newInstance()->dao->lastQuery());
            // item 2
            $res = Item::newInstance()->insert($array_set2);
            self::$aInfo['itemID2']['id'] = Item::newInstance()->dao->insertedId();
            self::$aInfo['itemID2']['array'] = $array_set2;
            $this->assertTrue($res, Item::newInstance()->dao->lastQuery());
            $res = Item::newInstance()->insertLocale(self::$aInfo['itemID2']['id'], $locale, $title2, $description2, $what2) ;
            $this->assertTrue($res, Item::newInstance()->dao->lastQuery());
            // item 2 Location -------------------------------------------------
            include_once 'data/ItemLocationData1.2.php';
            $res = ItemLocation::newInstance()->insert($array_location2) ;
            $this->assertTrue($res, ItemLocation::newInstance()->dao->lastQuery());
            // -----------------------------------------------------------------
            // add comments item 1
            $array_set = array(
                'fk_i_item_id'  => self::$aInfo['itemID1']['id'],
                'fk_i_user_id'  => self::$aInfo['userID']
            );
            for($i=0;$i<3;$i++){
                $array_set['dt_pub_date']  = date('Y-m-d H:i:s');
                $array_set['s_title']       = "title ".$i;
                $array_set['s_body']        = "body comment ".$i;
                $res = $this->model->insert($array_set);
                $this->assertTrue($res,'Insert comment');
            }
            // more comments item 1
            unset($array_set['fk_i_user_id'] );
            for($i=3;$i<5;$i++){
                $array_set['dt_pub_date']  = date('Y-m-d H:i:s');
                $array_set['s_title']  = "title ".$i;
                $array_set['s_body']   = "body comment ".$i;
                $res = $this->model->insert($array_set);
                $this->assertTrue($res,'Insert comment');
            }
            // more comments item 2
            $array_set = array(
                'fk_i_item_id'  => self::$aInfo['itemID2']['id'],
                'fk_i_user_id'  => self::$aInfo['userID']
            );
            for($i=5;$i<8;$i++){
                $array_set['dt_pub_date']  = date('Y-m-d H:i:s');
                $array_set['s_title']       = "title ".$i;
                $array_set['s_body']        = "body comment ".$i;
                $array_set['b_active']     = 1;
                $res = $this->model->insert($array_set);
                $this->assertTrue($res,'Insert comment');
            }
            // more comments item 1
            unset($array_set['fk_i_user_id'] );
            for($i=8;$i<10;$i++){
                $array_set['dt_pub_date']  = date('Y-m-d H:i:s');
                $array_set['s_title']  = "title ".$i;
                $array_set['s_body']   = "body comment ".$i;
                $res = $this->model->insert($array_set);
                $this->assertTrue($res,'Insert comment');
            }
        }
        
        public function testFindByItemIDAll()
        {
            $result = $this->model->findByItemIDAll(self::$aInfo['itemID1']['id']);
            $this->assertEquals(5, count($result), $this->model->dao->lastQuery());
            $result = $this->model->findByItemIDAll(self::$aInfo['itemID2']['id']);
            $this->assertEquals(5, count($result), $this->model->dao->lastQuery());
        }
        
        public function testFindByItemID()
        {
            $result = $this->model->findByItemID(self::$aInfo['itemID1']['id'], 'all');
            $this->assertEquals(0, count($result), $this->model->dao->lastQuery());
            $result = $this->model->findByItemID(self::$aInfo['itemID2']['id'], 'all');
            $this->assertEquals(5, count($result), $this->model->dao->lastQuery());
            foreach($result as $comment){
                $this->assertEquals(self::$aInfo['itemID2']['id'], $comment['fk_i_item_id']);
            }
        }
        
        public function testTotalComments()
        {
            $result = $this->model->totalComments(self::$aInfo['itemID1']['id']);
            $this->assertEquals(0, $result, $this->model->dao->lastQuery());
            $result = $this->model->totalComments(self::$aInfo['itemID2']['id']);
            $this->assertEquals(5, $result, $this->model->dao->lastQuery());
        }
        
        public function testFindByAuthorID()
        {
            $result = $this->model->findByAuthorID(self::$aInfo['userID']) ;
            $this->assertEquals(3, count($result), $this->model->dao->lastQuery()) ;
        }
        
        public function testGetAllComments()
        {
            $result = $this->model->getAllComments() ;
            $this->assertEquals(10, count($result), $this->model->dao->lastQuery()) ;
            $result = $this->model->getAllComments(self::$aInfo['itemID1']['id']) ;
            $this->assertEquals(5, count($result), $this->model->dao->lastQuery()) ;
        }
        
        public function testGetLastComments()
        {
            $result = $this->model->getLastComments(5);
            $this->assertEquals(5, count($result), $this->model->dao->lastQuery()) ;
            $this->assertEquals(self::$aInfo['itemID2']['id'],$result[0]['fk_i_item_id']);
            $this->assertEquals('title 9',$result[0]['comment_title'], $this->model->dao->lastQuery());
        }
        
        function testDeteteAll()
        {
//            $res = $this->model->delete(array('s_title' => 'title 9'));
//            $this->assertGreaterThan(0, $res, $this->model->dao->lastQuery());
//            
//            $res = User::newInstance()->deleteUser(self::$aInfo['userID']) ;
//            $this->assertGreaterThan(0, $res, User::newInstance()->dao->lastQuery());
//            $res = Item::newInstance()->deleteByPrimaryKey(self::$aInfo['itemID1']['id']);
//            $this->assertGreaterThan(0, $res, Item::newInstance()->dao->lastQuery());
//            $res = Item::newInstance()->deleteByPrimaryKey(self::$aInfo['itemID2']['id']);
//            $this->assertGreaterThan(0, $res, Item::newInstance()->dao->lastQuery());
        }
    }
?>