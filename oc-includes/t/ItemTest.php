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
    echo ABS_PATH."\n";
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
    require_once '../osclass/model/new_model/Preference.php';
    
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
         * insert / insertLo
         */
        public function testInsert()
        {
            //insert user for testing propouse
            $secret = osc_genRandomPassword() ;
            $pass_secret = osc_genRandomPassword() ;
            $array_set_user = array(
                's_name'        => 'user name',
                's_password'    => 'password',
                's_secret'      => $secret,
                'dt_reg_date'   => date('Y-m-d H:i:s'),
                's_email'       => 'test@email.com',
                's_pass_code'   => $pass_secret
            );
            $user = new User();
            $res = $user->insert($array_set_user);
            $this->assertGreaterThan(0, $res, $this->model->dao->lastQuery());
            self::$aInfo['userID'] = $user->dao->insertedId();
            // ---------------------
            echo self::$aInfo['userID']."\n";
            $locale = Preference::newInstance()->findValueByName('language') ;
            // item 1
            $array_set = array(
                'fk_i_user_id'          => self::$aInfo['userID'],
                'dt_pub_date'           => date('Y-m-d H:i:s'),
                'fk_i_category_id'      => 1,
                'i_price'               => '1000',
                'fk_c_currency_code'    => 'USD',
                's_contact_name'        => 'contact name 1',
                's_contact_email'       => 'contact1@email.com',
                's_secret'              => osc_genRandomPassword(),
                'b_active'              => 0,
                'b_enabled'             => 1,
                'b_show_email'          => 0
            );
            
            $title       = "Title ad 1";
            $description = "Description ad 1 keywords car , foobar, osclass";
            $what        = $title." ".$description ;
            
            $res = $this->model->dao->insert($this->model->getTableName(), $array_set);
            $this->assertTrue($res, $this->model->dao->lastQuery());
            self::$aInfo['itemID1']['id'] = $this->model->dao->insertedId();
            
            $res = $this->model->insertLocale(self::$aInfo['itemID1']['id'], $locale, $title, $description, $what) ;
            $this->assertTrue($res, $this->model->dao->lastQuery());
            // item 2
            $array_set['s_secret'] = osc_genRandomPassword();
            $array_set['i_price']  = '2200';
            
            
            $title = "Title ad 2";
            $description = "Description ad 2 keywords moto , forums , osclass";
            
            $res = $this->model->dao->insert($this->model->getTableName(), $array_set);
            $this->assertTrue($res, $this->model->getErrorLevel());
            self::$aInfo['itemID2']['id'] = $this->model->dao->insertedId();
            self::$aInfo['itemID2']['array'] = $array_set;
            
            $res = $this->model->insertLocale(self::$aInfo['itemID2']['id'], $locale, $title, $description, $what) ;
            $this->assertTrue($res, $this->model->dao->lastQuery());
        
            // item 3
            unset($array_set['fk_i_user_id']);
            
            $array_set['s_secret'] = osc_genRandomPassword();
            $array_set['i_price']  = '200';
            
            $title = "Title ad 3";
            $description = "Description ad 3 keywords moto , forums , osclass";
            
            $res = $this->model->dao->insert($this->model->getTableName(), $array_set);
            $this->assertTrue($res, $this->model->dao->lastQuery());
            self::$aInfo['itemID3']['id'] = $this->model->dao->insertedId();
            self::$aInfo['itemID3']['array'] = $array_set;
            
            $res = $this->model->insertLocale(self::$aInfo['itemID3']['id'], $locale, $title, $description, $what) ;
            $this->assertTrue($res, $this->model->dao->lastQuery());
            
        }
    }
?>