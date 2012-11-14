<?php

require_once dirname(__FILE__).'/../../../../oc-load.php';

class Frontend_users extends FrontendTest {

    /*
     * Register a user without email validation.
     */
    function testUsers_AddNewUser()
    {
        // same as Frontend-register.php function testRegisterNewUser_NoValidation()
        $uSettings = new utilSettings();

        $old_enabled_users           = $uSettings->set_enabled_users(1);
        $old_enabled_users_registration = $uSettings->set_enabled_user_registration(1);
        $old_enabled_user_validation = $uSettings->set_enabled_user_validation(0);

        $this->doRegisterUser();
        $this->assertTrue( $this->selenium->isTextPresent('Your account has been created successfully'), 'Register new user without validation.');

        $uSettings->set_enabled_users($old_enabled_users);
        $uSettings->set_enabled_user_registration($old_enabled_users_registration);
        $uSettings->set_enabled_user_validation($old_enabled_user_validation);

        unset($uSettings);
    }

    /**
     * - check empty list of items at dashboard user
     */
    function testEmptyDashboard()
    {
        $this->loginWith();
        $url = osc_user_dashboard_url();
        $this->selenium->open($url);

        $this->assertTrue( $this->selenium->isTextPresent('No listings have been added yet'), 'User dashboard, without items.');
    }

    /*
     * - check empty list of items at dashboard user
     */
    function testEmptyManageItems()
    {
        $this->loginWith();
        $url = osc_user_dashboard_url();
        $this->selenium->open($url);
        $this->selenium->click("xpath=//li[@class='opt_items']/a");
        sleep(1);
        // click to manage items
        $this->assertTrue( $this->selenium->isTextPresent('Your listings + Post a new listing'), 'User Manage Items');
        $this->assertTrue( $this->selenium->isTextPresent('You don\'t have any listings yet'), 'User Manage Items, without items');
    }

    /*
     * - check empty list of alert at dashboard user
     */
    function testEmptyAlerts()
    {
        $this->loginWith();
        $url = osc_user_dashboard_url();
        $this->selenium->open($url);
        $this->selenium->click("xpath=//li[@class='opt_alerts']/a");
        sleep(5);
        // click to manage items
        $this->assertTrue( $this->selenium->isTextPresent('Your alerts'), 'User Manage Alerts');
        $this->assertTrue( $this->selenium->isTextPresent('You do not have any alerts yet'), 'User Manage Alerts, without alerts');
    }

    /*
     * - add an item
     * - check dashboard user
     * - check Manage items
     */
    function testDashboardAndManageItems()
    {
        $this->loginWith();
        // add item as registered user
        require 'ItemData.php';
        $item = $aData[0];
        $this->insertItem($item['catId'], $item['title'],
                                $item['description'], $item['price'],
                                $item['regionId'], $item['cityId'], $item['cityArea'],
                                $item['photo'], $item['contactName'],
                                $this->_email);
        // check dashboard
        $this->selenium->open(osc_user_dashboard_url());
        $count = 0;
        $count = (int)$this->selenium->getXpathCount("//div[@id='main']/div[@class='userItem']");
        $this->assertTrue($count==1 , "Users Dashboard with one item");
        // check manage items
        $this->selenium->click("xpath=//li[@class='opt_items']/a");
        sleep(1);
        $count = 0;
        $count = (int)$this->selenium->getXpathCount("//div[@id='main']/div[@class='item']");
        $this->assertTrue($count==1 , "Users Manage Items with one item (Should be 1, counted ".$count.")");
    }

    /*
     * - add alert
     * - check alerts
     * - test delete/unsubscribe alert
     */
    function testAlerts_create()
    {
        $this->loginWith();
        // add alert
        $this->_createAlert($this->_email);
        $this->logout();
    }

    // check alert
    function testAlerts()
    {
        $this->loginWith();
        $this->selenium->open( osc_user_dashboard_url() );

        $this->selenium->click("xpath=//li[@class='opt_alerts']/a");
        sleep(1);
        $this->assertTrue( $this->selenium->isTextPresent('Your alerts'), 'User Manage Alerts with one alert');

        $count = 0;
        $count = (int)$this->selenium->getXpathCount("//div[@id='main']/div[@class='userItem']");
        $this->assertTrue( $count==1 , "Users Dashboard with one item");

        // delete
        $this->selenium->click("xpath=//div[@id='main']/div[@class='userItem'][1]/div/a[text()='Delete this alert']");
        sleep(1);
        $this->assertTrue( $this->selenium->isTextPresent('Unsubscribed correctly'), 'User Manage Alerts, delete alert');
    }

    /*
     * Test user profile & public user profile.
     * Add user info and check this info at public user profile
     */
    function testUsers_profile()
    {

        $this->loginWith();
        $this->selenium->open( osc_user_profile_url() );
        // fill all information
        $this->selenium->type('s_name'          , 'updated usertest');
        $this->selenium->type('s_phone_mobile'  , '666006600');
        $this->selenium->type('cityArea'        , 'city area');
        $this->selenium->type('address'         , 'address 30');
        $this->selenium->type('s_website'       , 'www.osclass.org');
        $this->selenium->type('s_info[en_US]'   , 'user description test');

        $this->selenium->click("xpath=//span/button[text()='Update']");
        $this->selenium->waitForPageToLoad("3000");

        $this->assertTrue( $this->selenium->isTextPresent('Your profile has been updated successfully'), 'User profile update');

        $this->assertEqual( $this->selenium->getValue('s_name')         , 'updated usertest' ) ;
        $this->assertEqual( $this->selenium->getValue('s_phone_mobile') , '666006600' ) ;
        $this->assertEqual( $this->selenium->getValue('cityArea')       , 'city area' ) ;
        $this->assertEqual( $this->selenium->getValue('address')        , 'address 30' ) ;
        $this->assertEqual( $this->selenium->getValue('s_website')      , 'www.osclass.org' ) ;
        $this->assertEqual( $this->selenium->getValue('s_info[en_US]')  , 'user description test' ) ;
        $this->assertFalse( $this->selenium->isElementPresent("xpath=//div[@id='contact']") );

        // test public user profile + logged in
        $this->logout();
        $user = User::newInstance()->findByEmail($this->_email);
        $this->selenium->open( osc_user_public_profile_url($user['pk_i_id']) );

        // check values
        $this->assertTrue( $this->selenium->isTextPresent( 'Full name: updated usertest') );
        $this->assertTrue( $this->selenium->isTextPresent( 'Address: address 30, city area') );
        $this->assertTrue( $this->selenium->isTextPresent( 'User Description: user description test') );
        $this->assertTrue( $this->selenium->isTextPresent( 'Website: www.osclass.org') );

    }

    /*
     * Login user.
     * Change the password:
     *  - Incorrect current password.
     *  - Empty passwords.
     *  - Passwords do not match.
     * Logout user
     */
    function testUsers_ChangePassword()
    {
        $this->loginWith();
        $this->assertTrue($this->selenium->isTextPresent("User account manager"), 'Login at website.');

        $this->selenium->click("xpath=//ul/li/a[text()='My profile']");
        $this->selenium->waitForPageToLoad("30000");

        $this->selenium->click("link=Modify password");
        $this->selenium->waitForPageToLoad("3000");

        // test - current password don't match
        $this->selenium->type("password"        , "qwerty");
        $this->selenium->type("new_password"    , $this->_password);
        $this->selenium->type("new_password2"   , $this->_password);
        $this->selenium->click("//button[@type='submit']");
        $this->selenium->waitForPageToLoad("3000");
        $this->assertTrue( $this->selenium->isTextPresent("Current password doesn't match"), "User, change the user password.");

        // test - Passwords can't be empty
        $this->selenium->type("password"        , $this->_password);
        $this->selenium->type("new_password"    , '');
        $this->selenium->type("new_password2"   , $this->_password);
        $this->selenium->click("//button[@type='submit']");
        $this->selenium->waitForPageToLoad("3000");
        $this->assertTrue( $this->selenium->isTextPresent("Password cannot be blank"), "User, change the user password, one blank password field.");

        // test - Passwords don't match
        $this->selenium->type("password"        , $this->_password);
        $this->selenium->type("new_password"    , 'abc');
        $this->selenium->type("new_password2"   , 'def');
        $this->selenium->click("//button[@type='submit']");
        $this->selenium->waitForPageToLoad("3000");
        $this->assertTrue( $this->selenium->isTextPresent("Passwords don't match"), "User, change the user password, passwords don't match.");

        $this->logout();
    }

    /*
     * Registrer user2 without validation email
     * Login user1
     * Change email:
     *  - The specified e-mail is already in use.
     *  - Change email correctly.
     * Logout
     * Remove user2
     */
    function testUser_ChangeEmail()
    {
        $uSettings = new utilSettings();

        $old_enabled_users           = $uSettings->set_enabled_users(1);
        $old_enabled_users_registration = $uSettings->set_enabled_user_registration(1);
        $old_enabled_user_validation = $uSettings->set_enabled_user_validation(0);

        // add another user
        $this->doRegisterUser('foo@bar.com', 'password');

        $this->loginWith();
        $this->assertTrue($this->selenium->isTextPresent("User account manager"), 'Login at website.');

        $this->selenium->click("xpath=//ul/li/a[text()='My profile']");
        $this->selenium->waitForPageToLoad("30000");

        $this->selenium->click("link=Modify e-mail");
        $this->selenium->waitForPageToLoad("30000");

        // test - The specified e-mail is already in use
        $this->selenium->type("email"     , $this->_email);
        $this->selenium->type("new_email" , 'foo@bar.com');

        $this->selenium->click("//button[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");

        $this->assertTrue( $this->selenium->isTextPresent("The specified e-mail is already in use"), "Change user email, for an existent user email.");

        /*
         *   ------------     Force validation !  =>  enabled_user_validation()   ------------------
         */
        $uSettings->set_enabled_user_validation(1);
        // with validation
        $this->selenium->click("xpath=//ul/li/a[text()='My profile']");
        $this->selenium->waitForPageToLoad("3000");

        $this->selenium->click("link=Modify e-mail");
        $this->selenium->waitForPageToLoad("3000");

        $this->selenium->type("email"     , $this->_email);
        $this->selenium->type("new_email" , "test@test.com");
        $this->selenium->click("//button[@type='submit']");
        $this->selenium->waitForPageToLoad("3000");

        $this->assertTrue( $this->selenium->isTextPresent("We've sent you an e-mail. Follow its instructions to validate the changes"), "Change user email, with email validation.");

        $this->logout();

        $this->removeUserByMail('foo@bar.com');

        $uSettings->set_enabled_users($old_enabled_users);
        $uSettings->set_enabled_user_registration($old_enabled_users_registration);
        $uSettings->set_enabled_user_validation($old_enabled_user_validation);

        unset($uSettings);
    }

    /*
     * Remove user1
     */
    function testUser_RemoveNewUser()
    {
        $this->removeUserByMail($this->_email);
    }
}

?>
