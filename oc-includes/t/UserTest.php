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

    require_once '../osclass/model/new_model/User.php' ;
    require_once '../osclass/helpers/hSecurity.php' ;
    require_once '../osclass/helpers/hLocale.php' ;
    
//    require_once LIB_PATH . 'osclass/db.php';
    
    require_once '../osclass/model/new_model/Preference.php';
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
     * Run: $> phpunit UserTest.php
     */
    class UserTest extends PHPUnit_Framework_TestCase
    {
        private $preference ;
        protected static $aInfo = array();


        public function __construct()
        {
            parent::__construct() ;
            $this->model = new User() ;
        }

        public function testInsert()
        {
            $secret = osc_genRandomPassword() ;
            $pass_secret = osc_genRandomPassword() ;
            $array_set = array(
                's_name'        => 'user name',
                's_password'    => 'password',
                's_secret'      => $secret,
                'dt_reg_date'   => date('Y-m-d H:i:s'),
                's_email'       => 'test@email.com',
                's_pass_code'   => $pass_secret
            );
            self::$aInfo['test@email.com']['secret'] = $secret;
            self::$aInfo['test@email.com']['pass_code'] = $pass_secret;
            $res = $this->model->dao->insert($this->model->getTableName(), $array_set ) ;
            self::$aInfo['test@email.com']['id']     = $this->model->dao->insertedId();
            $this->assertTrue($res, $this->model->dao->lastQuery()) ;
            
            $res = $this->model->dao->insert($this->model->getTableName(), $array_set ) ;
            $this->assertFalse($res, $this->model->dao->errorLevel) ;
            
            $array_set['s_email']       = 'new@email.com' ;
            $array_set['b_active']      = '1' ;
            $array_set['s_password']    = 'password2' ;
            
            $res = $this->model->dao->insert($this->model->getTableName(), $array_set ) ;
            self::$aInfo['new@email.com']['id']     = $this->model->dao->insertedId();
            $this->assertTrue($res, $this->model->dao->lastQuery()) ;
        }
        
        public function testFindByPrimaryKey()
        {   // findByPrimaryKey($id, $locale = null)
            
            
        }

        public function testFindByEmail()
        {
            $email = 'test@email.com';
            $result = $this->model->findByEmail($email);
            $this->assertNotEmpty($result, $this->model->dao->errorLevel);
            
            foreach($this->model->getFields() as $field){
                $this->assertArrayHasKey($field, $result);
            }
            
            $result = $this->model->findByEmail('foo@bar.com');
            $this->assertEmpty($result, $this->model->dao->errorLevel);
        }
        
        public function testFindByCredentials()
        {
            $key      = 'test@email.com';
            $password = 'password';
            $result = $this->model->findByCredentials($key, $password);
            $this->assertNotEmpty($result, $this->model->dao->errorLevel);
            
            $result = $this->model->findByCredentials($key, 'foobar');
            $this->assertEmpty($result, $this->model->dao->errorLevel);
        }
        
        public function testFindByIdSecret()
        {
            $id     = self::$aInfo['test@email.com']['id'];
            $secret = self::$aInfo['test@email.com']['secret'];
            $result = $this->model->findByIdSecret($id, $secret) ;
            $this->assertNotEmpty($result, $this->model->dao->errorLevel);
            
            $result = $this->model->findByIdSecret($id, 'foobar') ;
            $this->assertEmpty($result, $this->model->dao->errorLevel);
        }
        
        public function testFindByIdPasswordSecret()
        {
            $id     = self::$aInfo['test@email.com']['id'];
            $secret = self::$aInfo['test@email.com']['pass_code'];
            $result = $this->model->findByIdPasswordSecret($id, $secret) ;
            $this->assertNotEmpty($result, $this->model->dao->errorLevel);
            
            $result = $this->model->findByIdSecret($id, 'foobar') ;
            $this->assertEmpty($result, $this->model->dao->errorLevel);
        }
        
        public function testUpdateDescription()
        {
            $id     = self::$aInfo['test@email.com']['id'];
            $res = $this->model->updateDescription($id, osc_current_user_locale(), 'User information');
            $this->assertTrue($res, $this->model->dao->errorLevel);
            
            $res = $this->model->updateDescription('foobar', osc_current_user_locale(), 'User information');
            $this->assertFalse($res, $this->model->dao->errorLevel);
        }
        
        public function testDeleteUser()
        {
            $res = $this->model->deleteUser(self::$aInfo['test@email.com']['id']);
            $this->assertTrue($res, $this->model->dao->errorLevel);
            $res = $this->model->deleteUser(self::$aInfo['test@email.com']['id']);
            $this->assertTrue($res, $this->model->dao->errorLevel);
            
            $res = $this->model->deleteUser('foobar');
            $this->assertFalse($res, $this->model->dao->errorLevel);
        }
    }
    
?>
