<?php
require_once('../../autorun.php');
require_once('../../web_tester.php');
require_once('../../reporter.php');
require_once('util_settings.php');

// LOAD OSCLASS
require_once '../../../../oc-load.php';
require_once LIB_PATH . 'Selenium.php';

class TestOfAdminUsers extends WebTestCase {

    private $selenium;

    function setUp()
    {
        $conn = getConnection();
        $conn->osc_dbExec(sprintf("INSERT INTO `%st_admin` (`s_name` ,`s_username` ,`s_password` ,`s_secret` ,`s_email`) VALUES ('Test Admin','testadmin','5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8','mvqdnrpt','testadmin@test.net')", DB_TABLE_PREFIX));

        echo "<br><div style='background-color: Wheat; color: black;'>init test</div>";

        $browser = "*firefox";
        $this->selenium = new Testing_Selenium( $browser, "http://localhost/");
        $this->selenium->start();
        $this->selenium->setSpeed("150");
    }

    function tearDown()
    {
        $this->selenium->stop();
        $admin = Admin::newInstance()->findByEmail('testadmin@test.net');
        Admin::newInstance()->delete(array('pk_i_id' =>$admin['pk_i_id']));
        echo "<div style='background-color: Wheat; color: black;'>end test</div>";
        flush();
    }
    /*           TESTS          */

    /**
     * upload new language
     * REQUIRE: user logged in
     */
    function testUserInsert()
    {
        echo "<div style='background-color: green; color: white;'><h2>testUserInsert</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testUserInsert - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testUserInsert - INSERT NEW USER</div>";
        $this->insertUser() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testUserInsert - DELETE USER</div>";
        $this->deleteUser();
        flush();
    }

    function testUserInsertbyLink()
    {
        echo "<div style='background-color: green; color: white;'><h2>testUserInsertbyLink</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testLantestUserInsertbyLinkguageInsertbyLink - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testUserInsertbyLink - INSERT NEW USER</div>";
        $this->insertUserByLink() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testUserInsert - DELETE USER</div>";
        $this->deleteUser();
        flush();
    }

    public function testUserEdit()
    {
        echo "<div style='background-color: green; color: white;'><h2>testUserEdit</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testUserEdit - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testUserInsert - INSERT NEW USER</div>";
        $this->insertUser() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testUserEdit - EDIT USER</div>";
        $this->editUser();
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testUserInsert - DELETE USER</div>";
        $this->deleteUser();
        flush();
    }

    public function testExtraValidations()
    {
        echo "<div style='background-color: green; color: white;'><h2>testExtraValidations</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testExtraValidations - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testExtraValidations - INSERT NEW USER</div>";
        $this->insertUser() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testExtraValidations - EXTRA VALIDATIONS USER - WEBSITE</div>";
        $this->extraValidations();
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testExtraValidations - DELETE USER</div>";
        $this->deleteUser();
        flush();
    }

    public function testSettings()
    {
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;'><h2>testExtraValidations</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testExtraValidations - LOGIN </div>";
        $this->settings();
        flush();
    }

    /*
     * PRIVATE FUNCTIONS
     */
    private function loginCorrect()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->waitForPageToLoad(10000);

        // if you are logged fo log out
        if( $this->selenium->isTextPresent('Log Out') ){
            $this->selenium->click('Log Out');
            $this->selenium->waitForPageToLoad(1000);
        }

        $this->selenium->type('user', 'testadmin');
        $this->selenium->type('password', 'password');
        $this->selenium->click('submit');
        $this->selenium->waitForPageToLoad(1000);

        if( !$this->selenium->isTextPresent('Log in') ){
            $this->assertTrue("todo bien");
        } else {
            $this->assertFalse("can't loggin");
        }
    }

    /**
     * Upload a new language.
     */
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

        $this->assertTrue($this->selenium->isTextPresent("The user has been created and activated"),"Can't create new user");
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

        echo "<div style='background-color: green; color: white;padding-left:15px;'>Login user ...</div>";
        if($this->selenium->isTextPresent("User account manager")){
            $this->assertTrue("ok");
        }

        // check username at left up corner
        $this->assertTrue($this->selenium->isTextPresent('real name user'),"Cannot login new user at website");
        // check autofill locations when user add nen advert
        $this->selenium->open(osc_base_url(true) . '?page=item&action=item_add');
        $this->assertTrue( ($this->selenium->getSelectedLabel('id=countryId') == 'Spain'), 'Country not auto filled ERROR');
        $this->assertTrue( ($this->selenium->getValue('id=region')  == 'Barcelona'), 'Region not auto filled ERROR');
//        $this->assertTrue( ($this->selenium->getSelectedLabel('id=regionId')  == 'Barcelona'), 'Region not auto filled ERROR');
        $this->assertTrue( ($this->selenium->getValue('id=city')  == 'Sabadell'), 'City not auto filled ERROR');
//        $this->assertTrue( ($this->selenium->getSelectedLabel('id=cityId')  == 'Sabadell'), 'City not auto filled ERROR');
        $this->assertTrue( ($this->selenium->getValue('id=cityArea') == 'city area'), 'City area not auto filled ERROR');
        $this->assertTrue( ($this->selenium->getValue('id=address') == 'address user'), 'Address not auto filled ERROR');
        // alerts
        $this->selenium->open(osc_base_url(true) . '?page=search');
        $this->assertTrue( ($this->selenium->getValue('id=alert_email') == 'test@mail.com' ), 'Cannot use email for alert.(page=search) ERROR');
        // contact publisher (need add one item)
        $this->selenium->open(osc_base_url(true) . '?page=search');
        $this->selenium->click('link=Title new add test');
        $this->selenium->waitForPageToLoad("30000");

        $this->assertTrue( ($this->selenium->getValue('id=yourName') == 'real name user'), 'Your name not auto filled (view item).ERROR');
        $this->assertTrue( ($this->selenium->getValue('id=yourEmail') == 'test@mail.com'), 'Your Email not auto filled (view item).ERROR');
        $this->assertTrue( ($this->selenium->getValue('id=phoneNumber') == '666666666'), 'Your phone not auto filled (view item).ERROR');

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

        $this->assertTrue($this->selenium->isTextPresent("The user has been created and activated"),"Can't create new user");
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

        $this->assertTrue($this->selenium->isTextPresent("The user has been updated"),"Can't edit user. ERROR");
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

        $this->assertTrue($this->selenium->isTextPresent("One user has been deleted"), "Can't delete user ERROR" ) ;
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
        echo "<div style='background-color: green; color: white;padding-left:15px;'>enabled_users 0</div>";
        $this->checkWebsite_enabled_users(0);
        Preference::newInstance()->replace('enabled_users', '1',"osclass", 'INTEGER') ;
        echo "<div style='background-color: green; color: white;padding-left:15px;'>enabled_users 1</div>";
        $this->checkWebsite_enabled_users(1);
    // enabled_user_validation
        Preference::newInstance()->replace('enabled_user_validation', '0',"osclass", 'INTEGER') ;
        echo "<div style='background-color: green; color: white;padding-left:15px;'>enabled_user_validation 0</div>";
        $this->checkWebsite_enabled_user_validation(0);
        Preference::newInstance()->replace('enabled_user_validation', '1',"osclass", 'INTEGER') ;
        echo "<div style='background-color: green; color: white;padding-left:15px;'>enabled_user_validation 1</div>";
        $this->checkWebsite_enabled_user_validation(1);
    // enabled_user_registration
        Preference::newInstance()->replace('enabled_user_registration', '0',"osclass", 'INTEGER') ;
        echo "<div style='background-color: green; color: white;padding-left:15px;'>enabled_user_registration 0</div>";
        $this->checkWebsite_enabled_user_registration(0);
        Preference::newInstance()->replace('enabled_user_registration', '1',"osclass", 'INTEGER') ;
        echo "<div style='background-color: green; color: white;padding-left:15px;'>enabled_user_registration 1</div>";
        $this->checkWebsite_enabled_user_registration(1);
    }

    private function checkWebsite_enabled_users($bool)
    {
        $this->selenium->open( osc_user_login_url() );
        if($bool == 1) {
            $is_present_email = $this->selenium->isElementPresent('id=email');
            $is_present_pass  = $this->selenium->isElementPresent('id=password');
            $this->assertTrue(( $is_present_email && $is_present_pass), "Cannot login into osclass. ERROR" ) ;
        } else if ($bool == 0) {
            $this->assertTrue($this->selenium->isTextPresent('Users not enabled'), "Users can login" );
        }

        $this->selenium->open( osc_register_account_url() );
        if($bool == 1) {
            $is_present_email = $this->selenium->isElementPresent('id=s_name');
            $is_present_pass  = $this->selenium->isElementPresent('id=s_password');
            $is_present_pass2 = $this->selenium->isElementPresent('id=s_password2');
            $this->assertTrue(( $is_present_email && $is_present_pass && $is_present_pass2 ), "Cannot register into osclass. ERROR" ) ;
        } else if ($bool == 0) {
            $this->assertTrue($this->selenium->isTextPresent('Users not enabled'), "Can register into osclass. ERROR" );
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
            $this->assertTrue( $this->selenium->isTextPresent('The user has been created. An activation email has been sent'), "No need to validate user. ERROR" ) ;
        } else if ($bool == 0) {
            $this->assertTrue($this->selenium->isTextPresent('Your account has been created successfully'), "Need to validate user. ERROR" );
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
            $this->assertTrue(( $is_present_email && $is_present_pass && $is_present_pass2 ), "Cannot register into osclass. ERROR" ) ;
        } else if ($bool == 0) {
            $this->assertTrue($this->selenium->isTextPresent('User registration is not enabled'), "Can register into osclass. ERROR" );
        }
    }
}
?>