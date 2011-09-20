<?php
require_once '../../../../oc-load.php';

//require_once('FrontendTest.php');

class OCadmin_administrators extends OCadminTest {
    
        function testUserInsert()
    {
        $this->loginCorrect() ;
        $this->insertUser() ;
        $this->deleteUser();
    }

    function testUserInsertbyLink()
    {
        $this->loginCorrect() ;
        $this->insertUserByLink() ;
        $this->deleteUser();
    }

    public function testUserEdit()
    {
        $this->loginCorrect() ;
        $this->insertUser() ;
        $this->editUser();
        $this->deleteUser();
    }

    public function testExtraValidations()
    {
        $this->loginCorrect() ;
        $this->insertUser() ;
        $this->extraValidations();
        $this->deleteUser();
    }

    public function testSettings()
    {
        $this->loginCorrect() ;
        $this->settings();
    }

    
    private function insertUser()
    {
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->click("link=Users");
        $this->selenium->click("link=» Add new user");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type("s_email"         ,"test@mail.com");
        $this->selenium->type("s_password"      ,"password");
        $this->selenium->type("s_password2"     ,"password");

        $this->selenium->type("s_name"          ,"real name user");

        $this->selenium->type("s_phone_mobile"  ,"666666666");
        $this->selenium->type("s_phone_land"    ,"930112233");

        $this->selenium->type("s_website"       ,"http://osclass.org");
        $this->selenium->type("s_info[en_US]"   ,"foobar description");

        $this->selenium->type("cityArea"        ,"city area");
        $this->selenium->type("address"         ,"address user");

        $this->selenium->select("countryId"     , "label=Spain");
        $this->selenium->select("regionId"      , "label=Barcelona");
        $this->selenium->select("cityId"        , "label=Sabadell");
        $this->selenium->select("b_company"     , "label=User");
        
        $this->selenium->click("//form/input[@id='button_save']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("The user has been created and activated"),"Create user");
    }

    private function extraValidations()
    {
        // add item no user logged
        
        $uSettings = new utilSettings();
        $bool_reg_user_post  = $uSettings->set_reg_user_post(0);
        $bool_moderate_items = $uSettings->set_moderate_items(-1);
        
        $this->selenium->open(osc_base_url(true) . '?page=item&action=item_add' );
        $this->selenium->select("catId", "label=regexp:\\s*Animals");

        $this->selenium->type("title[en_US]", 'Title new add test');
        $this->selenium->type("description[en_US]", "description new add");
        $this->selenium->type("price", '11');

        $this->selenium->select("countryId", "label=Spain");
        $this->selenium->select("regionId", "label=Barcelona");
        $this->selenium->select("cityId", "label=Barcelona");

        $this->selenium->type('id=contactName', 'foobar');
        $this->selenium->type('id=contactEmail', 'foobar@mail.com');

        $this->selenium->select("currency", "label=Euro €");
        $this->selenium->click("//button[text()='Publish']");
        $this->selenium->waitForPageToLoad("30000");

        $bool_reg_user_post = $uSettings->set_reg_user_post($bool_reg_user_post);
        $bool_moderate_items = $uSettings->set_moderate_items($bool_moderate_items);

        // log in website
        $this->selenium->open( osc_base_url(true) );
        $this->selenium->click("login_open");
        $this->selenium->type("email"   , 'test@mail.com');
        $this->selenium->type("password", 'password');

        $this->selenium->click("//button[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");

        if($this->selenium->isTextPresent("User account manager")){
            $this->assertTrue("User's dashboard");
        }

        // check username at left up corner
        $this->assertTrue($this->selenium->isTextPresent('real name user'),"Login at website");
        // check autofill locations when user add nen advert
        $this->selenium->open(osc_base_url(true) . '?page=item&action=item_add');
        $this->assertTrue( ($this->selenium->getSelectedLabel('id=countryId') == 'Spain'), 'Country auto fill');
        $this->assertTrue( ($this->selenium->getValue('id=region')  == 'Barcelona'), 'Region auto fill');
//        $this->assertTrue( ($this->selenium->getSelectedLabel('id=regionId')  == 'Barcelona'), 'Region not auto filled ERROR');
        $this->assertTrue( ($this->selenium->getValue('id=city')  == 'Sabadell'), 'City auto fill');
//        $this->assertTrue( ($this->selenium->getSelectedLabel('id=cityId')  == 'Sabadell'), 'City not auto filled ERROR');
        $this->assertTrue( ($this->selenium->getValue('id=cityArea') == 'city area'), 'City area auto fill');
        $this->assertTrue( ($this->selenium->getValue('id=address') == 'address user'), 'Address auto fill');
        // alerts
        $this->selenium->open(osc_base_url(true) . '?page=search');
        $this->assertTrue( ($this->selenium->getValue('id=alert_email') == 'test@mail.com' ), 'Email inserted for alert');
        // contact publisher (need add one item)
        $this->selenium->open(osc_base_url(true) . '?page=search');
        $this->selenium->click('link=Title new add test');
        $this->selenium->waitForPageToLoad("30000");

        $this->assertTrue( ($this->selenium->getValue('id=yourName') == 'real name user'), 'Name auto fill');
        $this->assertTrue( ($this->selenium->getValue('id=yourEmail') == 'test@mail.com'), 'Email auto fill');
        $this->assertTrue( ($this->selenium->getValue('id=phoneNumber') == '666666666'), 'Phone auto fill');

        // remove item
        Item::newInstance()->delete( array('s_contact_email' => 'foobar@mail.com') ) ;
    }

    private function insertUserByLink()
    {
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->click("link=Users");
        $this->selenium->click("link=» Add new user");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("link=Add a new user");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type("s_email"         ,"test1@mail.com");
        $this->selenium->type("s_password"      ,"password");
        $this->selenium->type("s_password2"     ,"password");

        $this->selenium->type("s_name"          ,"real name user");

        $this->selenium->type("s_phone_mobile"  ,"666666666");
        $this->selenium->type("s_phone_land"    ,"930112233");

        $this->selenium->type("s_website"       ,"http://osclass.org");
        $this->selenium->type("s_info[en_US]"   ,"foobar description");

        $this->selenium->type("cityArea"        ,"city area");
        $this->selenium->type("address"         ,"address user");

        $this->selenium->select("countryId"     , "label=Spain");
        $this->selenium->select("regionId"      , "label=Barcelona");
        $this->selenium->select("cityId"        , "label=Sabadell");
        $this->selenium->select("b_company"     , "label=User");

        $this->selenium->click("//form/input[@id='button_save']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("The user has been created and activated"),"Create user");
    }

    private function editUser()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Users");
        $this->selenium->click("link=» Manage users");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'mail.com')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'mail.com')]/td/div/a[text()='Edit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type("s_email"         ,"newtest@mail.com");
        $this->selenium->type("s_password"      ,"newpassword");
        $this->selenium->type("s_password2"     ,"newpassword");

        $this->selenium->type("s_name"          ,"new real name user");

        $this->selenium->type("s_phone_mobile"  ,"999999999");
        $this->selenium->type("s_phone_land"    ,"332211039");

        $this->selenium->type("s_website"       ,"http://osclass.org");
        $this->selenium->type("s_info[en_US]"   ,"new foobar description");

        $this->selenium->type("cityArea"        ,"new city area");
        $this->selenium->type("address"         ,"new address user");

        $this->selenium->select("countryId"     , "label=Spain");
        $this->selenium->select("regionId"      , "label=Madrid");
        $this->selenium->select("cityId"        , "label=La Acebeda");
        $this->selenium->select("b_company"     , "label=Company");

        $this->selenium->click("xpath=//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("The user has been updated"),"Edit user");
    }

    private function deleteUser()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Users");
        $this->selenium->click("link=» Manage users");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'mail.com')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'mail.com')]/td/div/a[text()='Delete']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("One user has been deleted"), "Delete user" ) ;
    }

    private function settings()
    {
        $pref = array();
        $pref['enabled_users'] = Preference::newInstance()->findValueByName('enabled_users') ;
        if($pref['enabled_users'] == 1){ $pref['enabled_users'] = 'on';} else { $pref['enabled_users'] = 'off'; }
        $pref['enabled_user_validation'] = Preference::newInstance()->findValueByName('enabled_user_validation') ;
        if($pref['enabled_user_validation'] == 1){ $pref['enabled_user_validation'] = 'on';} else { $pref['enabled_user_validation'] = 'off'; }
        $pref['enabled_user_registration'] = Preference::newInstance()->findValueByName('enabled_user_registration') ;
        if($pref['enabled_user_registration'] == 1){ $pref['enabled_user_registration'] = 'on';} else { $pref['enabled_user_registration'] = 'off'; }

        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("xpath=//a[text()='Users']");
        $this->selenium->click("xpath=//li[3]/a[text()='» Settings']");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->click("enabled_users");
        $this->selenium->click("enabled_user_validation");
        $this->selenium->click("enabled_user_registration");

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("Users' settings have been updated") , "Can't update user settings. ERROR");

        if( $pref['enabled_users'] == 'on' ){
            $this->assertEqual( $this->selenium->getValue('enabled_users'), 'off' ) ;
        } else {
            $this->assertEqual( $this->selenium->getValue('enabled_users'), 'on' ) ;
        }
        if( $pref['enabled_user_validation'] == 'on' ){
            $this->assertEqual( $this->selenium->getValue('enabled_user_validation'), 'off' ) ;
        } else {
            $this->assertEqual( $this->selenium->getValue('enabled_user_validation'), 'on' ) ;
        }
        if( $pref['enabled_user_registration'] == 'on' ){
            $this->assertEqual( $this->selenium->getValue('enabled_user_registration'), 'off' ) ;
        } else {
            $this->assertEqual( $this->selenium->getValue('enabled_user_registration'), 'on' ) ;
        }

        $this->selenium->click("enabled_users");
        $this->selenium->click("enabled_user_validation");
        $this->selenium->click("enabled_user_registration");

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertEqual( $this->selenium->getValue('enabled_users')              ,  $pref['enabled_users'] ) ;
        $this->assertEqual( $this->selenium->getValue('enabled_user_validation')    ,  $pref['enabled_user_validation'] ) ;
        $this->assertEqual( $this->selenium->getValue('enabled_user_registration')  ,  $pref['enabled_user_registration'] ) ;

        $this->assertTrue( $this->selenium->isTextPresent("Users' settings have been updated") , "Can't update user settings. ERROR");

        /*
         * Testing deeper
         */
        
    // enabled_users
        Preference::newInstance()->replace('enabled_users', '0',"osclass", 'INTEGER') ;
        $this->checkWebsite_enabled_users(0);
        Preference::newInstance()->replace('enabled_users', '1',"osclass", 'INTEGER') ;
        $this->checkWebsite_enabled_users(1);
    // enabled_user_validation
        Preference::newInstance()->replace('enabled_user_validation', '0',"osclass", 'INTEGER') ;
        $this->checkWebsite_enabled_user_validation(0);
        Preference::newInstance()->replace('enabled_user_validation', '1',"osclass", 'INTEGER') ;
        $this->checkWebsite_enabled_user_validation(1);
    // enabled_user_registration
        Preference::newInstance()->replace('enabled_user_registration', '0',"osclass", 'INTEGER') ;
        $this->checkWebsite_enabled_user_registration(0);
        Preference::newInstance()->replace('enabled_user_registration', '1',"osclass", 'INTEGER') ;
        $this->checkWebsite_enabled_user_registration(1);
    }

    private function checkWebsite_enabled_users($bool)
    {
        $this->selenium->open( osc_user_login_url() );
        if($bool == 1) {
            $is_present_email = $this->selenium->isElementPresent('id=email');
            $is_present_pass  = $this->selenium->isElementPresent('id=password');
            $this->assertTrue(( $is_present_email && $is_present_pass), "Login" ) ;
        } else if ($bool == 0) {
            $this->assertTrue($this->selenium->isTextPresent('Users not enabled'), "Login" );
        }

        $this->selenium->open( osc_register_account_url() );
        if($bool == 1) {
            $is_present_email = $this->selenium->isElementPresent('id=s_name');
            $is_present_pass  = $this->selenium->isElementPresent('id=s_password');
            $is_present_pass2 = $this->selenium->isElementPresent('id=s_password2');
            $this->assertTrue(( $is_present_email && $is_present_pass && $is_present_pass2 ), "Register" ) ;
        } else if ($bool == 0) {
            $this->assertTrue($this->selenium->isTextPresent('Users not enabled'), "Register" );
        }
    }

    private function checkWebsite_enabled_user_validation($bool)
    {
        $this->selenium->open( osc_register_account_url() );
        $this->selenium->type('id=s_name', "carlos");
        $this->selenium->type('id=s_password', "carlos");
        $this->selenium->type('id=s_password2', "carlos");
        $this->selenium->type('id=s_email', "carlos+testtest@osclass.org");
        $this->selenium->click("xpath=//button[text()='Create']");
        $this->selenium->waitForPageToLoad("10000");

        if($bool == 1) {
            $this->assertTrue( $this->selenium->isTextPresent('The user has been created. An activation email has been sent'), "No-Validate user" ) ;
        } else if ($bool == 0) {
            $this->assertTrue($this->selenium->isTextPresent('Your account has been created successfully'), "Validate user" );
        }

        $user = User::newInstance()->findByEmail("carlos+testtest@osclass.org");
        User::newInstance()->deleteUser($user['pk_i_id']);
    }

    private function checkWebsite_enabled_user_registration($bool)
    {
        $this->selenium->open( osc_register_account_url() );
        if($bool == 1) {
            $is_present_email = $this->selenium->isElementPresent('id=s_name');
            $is_present_pass  = $this->selenium->isElementPresent('id=s_password');
            $is_present_pass2 = $this->selenium->isElementPresent('id=s_password2');
            $this->assertTrue(( $is_present_email && $is_present_pass && $is_present_pass2 ), "Register user" ) ;
        } else if ($bool == 0) {
            $this->assertTrue($this->selenium->isTextPresent('User registration is not enabled'), "Register user" );
        }
    }

}
?>
