<?php

    require_once('../../autorun.php');
    require_once('../../web_tester.php');
    require_once('../../reporter.php');

    // LOAD OSCLASS
    require_once '../../../../oc-load.php';

class TestOfUserAccount extends WebTestCase {
    
    function testUserAccount() {
        // LOAD USER DATA TO WORK WITH
        $user = User::newInstance()->findByPrimaryKey(1);
        // TEST LOAD OF URL CORRECTLY (if fails, something is very wrong)
        $this->assertTrue($this->get(osc_user_login_url()));
        
        // TEST WRONG PASSWORD
        $this->get(osc_user_login_url());
        $this->setField('email', $user['s_email']);
        $this->setField('password', 'wrong_password');
        $this->click('Log in');
        $this->assertNoText('Items from '.$user['s_name']);
        
        // TEST WRONG EMAIL
        $this->get(osc_user_login_url());
        $this->setField('email', 'wrong_email@example.com');
        $this->setField('password', 'test');
        $this->click('Log in');
        $this->assertNoText('Items from '.$user['s_name']);
        
        // TEST WRONG EMAIL & PASSWORD
        $this->get(osc_user_login_url());
        $this->setField('email', 'wrong_email@example.com');
        $this->setField('password', 'wrong_password');
        $this->click('Log in');
        $this->assertNoText('Items from '.$user['s_name']);
        
        // TEST CORRECT LOGIN
        $this->get(osc_user_login_url());
        $this->setField('email', $user['s_email']);
        $this->setField('password', 'test');
        $this->click('Log in');
        $this->assertText('Items from '.$user['s_name']);
        
        // TEST PROFILE MODIFICATION
        $this->get(osc_user_profile_url());
        $this->setField('s_name', 'New Name');
        $this->setField('s_phone_land', '00123123456');
        $this->setField('s_phone_mobile', '00123987654');
        $this->setField('regionId', 'Barcelona');
        $this->setField('cityId', 'Arenys de Mar');
        $this->setField('cityArea', 'False City Area');
        $this->setField('address', 'Falsestraat 13, 37b');
        $this->setField('s_website', 'http://www.osclass.org/');
        $this->click('Update');
        // AFTER SUBMITTING DATA, WE CHECK IF IT'S SAVED CORRECTLY
        $this->get(osc_user_profile_url());
        $this->assertField('s_name', 'New Name');
        $this->assertField('s_phone_land', '00123123456');
        $this->assertField('s_phone_mobile', '00123987654');
        $this->assertField('cityId', '10'); //370 is value for 'Arenys de Mar'
        $this->assertField('cityArea', 'False City Area');
        $this->assertField('address', 'Falsestraat 13, 37b');
        $this->assertField('s_website', 'http://www.osclass.org/');
            // ROLL BACK CHANGES SO WE CAN TEST AGAIN AND AGAIN
            $this->get(osc_user_profile_url());
            $this->setField('s_name', 'Administrator');
            $this->setField('s_phone_land', '123456');
            $this->setField('s_phone_mobile', '654321');
            $this->setField('regionId', 'Barcelona');
            $this->setField('cityId', 'Sabadell');
            $this->setField('cityArea', 'Sarria');
            $this->setField('address', 'Calle false 42');
            $this->setField('s_website', 'http://www.example.com/');
            $this->click('Update');
            // NO NEED TO CHECK IF IT'S CORRECT OR NOT, BUT WE DO IT ANYWAY ;)
            $this->get(osc_user_profile_url());
            $this->assertField('s_name', 'Administrator');
            $this->assertField('s_phone_land', '123456');
            $this->assertField('s_phone_mobile', '654321');
            $this->assertField('cityId', '3'); //3 is value for 'Sabadell'
            $this->assertField('cityArea', 'Sarria');
            $this->assertField('address', 'Calle false 42');
            $this->assertField('s_website', 'http://www.example.com/');


        
        // TEST PASSWORD CHANGE (CURRENT PASSWORD WRONG)
        $this->get(osc_change_user_password_url());
        $this->setField('password', 'wrong_password');
        $this->setField('new_password', 'new_password');
        $this->setField('new_password2', 'new_password');
        $this->click("Update");
            // Since Simpletest can not read the flash messages
            // The only way we could test if it worked or not is to logout and login again
            $this->get(osc_user_logout_url());
            $this->assertNoText('Items from Administrator');
            // Try to Login we the wrong password
            $this->get(osc_user_login_url());
            $this->setField('email', $user['s_email']);
            $this->setField('password', 'new_password');
            $this->click('Log in');
            $this->assertNoText('Items from Administrator');
            // Login with the correct data
            $this->get(osc_user_login_url());
            $this->setField('email', $user['s_email']);
            $this->setField('password', 'test');
            $this->click('Log in');
            $this->assertText('Items from Administrator');
        
        // TEST PASSWORD CHANGE (PASSWORDS DONT MATCH)
        $this->get(osc_change_user_password_url());
        $this->setField('password', 'test');
        $this->setField('new_password', 'new_password');
        $this->setField('new_password2', 'another_password');
        $this->click("Update");
            // Since Simpletest can not read the flash messages
            // The only way we could test if it worked or not is to logout and login again
            $this->get(osc_user_logout_url());
            $this->assertNoText('Items from Administrator');
            // Try to Login we the wrong password
            $this->get(osc_user_login_url());
            $this->setField('email', $user['s_email']);
            $this->setField('password', 'new_password');
            $this->click('Log in');
            $this->assertNoText('Items from Administrator');
            // Login with the correct data
            $this->get(osc_user_login_url());
            $this->setField('email', $user['s_email']);
            $this->setField('password', 'test');
            $this->click('Log in');
            $this->assertText('Items from Administrator');
        
        // TEST PASSWORD CHANGE (EVERYTHING WRONG)
        $this->get(osc_change_user_password_url());
        $this->setField('password', 'test_password');
        $this->setField('new_password', 'new_password');
        $this->setField('new_password2', 'another_password');
        $this->click("Update");
            // Since Simpletest can not read the flash messages
            // The only way we could test if it worked or not is to logout and login again
            $this->get(osc_user_logout_url());
            $this->assertNoText('Items from Administrator');
            // Try to Login we the wrong password
            $this->get(osc_user_login_url());
            $this->setField('email', $user['s_email']);
            $this->setField('password', 'new_password');
            $this->click('Log in');
            $this->assertNoText('Items from Administrator');
            // Login with the correct data
            $this->get(osc_user_login_url());
            $this->setField('email', $user['s_email']);
            $this->setField('password', 'test');
            $this->click('Log in');
            $this->assertText('Items from Administrator');
        
        // TEST PASSWORD CHANGE (EVERYTHING RIGHT)
        $this->get(osc_change_user_password_url());
        $this->setField('password', 'test');
        $this->setField('new_password', 'new_password');
        $this->setField('new_password2', 'new_password');
        $this->click("Update");
            // Since Simpletest can not read the flash messages
            // The only way we could test if it worked or not is to logout and login again
            $this->get(osc_user_logout_url());
            $this->assertNoText('Items from Administrator');
            // Try to Login (we should login correctly!)
            $this->get(osc_user_login_url());
            $this->setField('email', $user['s_email']);
            $this->setField('password', 'new_password');
            $this->click('Log in');
            $this->assertText('Items from Administrator');
        
        // ROLL BACK CHANGES TO TEST IT OVER AND OVER AGAIN
        $this->get(osc_change_user_password_url());
        $this->setField('password', 'new_password');
        $this->setField('new_password', 'test');
        $this->setField('new_password2', 'test');
        $this->click("Update");
            // Since Simpletest can not read the flash messages
            // The only way we could test if it worked or not is to logout and login again
            $this->get(osc_user_logout_url());
            $this->assertNoText('Items from Administrator');
            // Try to Login with old password
            $this->get(osc_user_login_url());
            $this->setField('email', $user['s_email']);
            $this->setField('password', 'new_password');
            $this->click('Log in');
            $this->assertNoText('Items from Administrator');
            // Try to Login (we should login correctly!)
            $this->get(osc_user_login_url());
            $this->setField('email', $user['s_email']);
            $this->setField('password', 'test');
            $this->click('Log in');
            $this->assertText('Items from Administrator');
        
        
        
        //osc_change_user_email_url()
        
        //$this->get(osc_user_logout_url());
        //$this->assertNoText('Items from nodani@gmail.com');
    }        
    
}

?>
