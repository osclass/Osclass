<?php

    require_once('../../autorun.php');
    require_once('../../web_tester.php');
    require_once('../../reporter.php');

    // LOAD OSCLASS
    require_once '../../../../oc-load.php';

class TestOfAdminAccount extends WebTestCase {
    
    function testAdminAccount() {
        // LOAD SOME DATA (Registration form uses some JS magic, so we can not test it with simpletest)
        // Instead, we create an user "by hand"
        $conn = getConnection();
        $conn->osc_dbExec(sprintf("INSERT INTO `%st_admin` (`s_name` ,`s_username` ,`s_password` ,`s_secret` ,`s_email`) VALUES ('Test Admin','testadmin','5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8','mvqdnrpt','testadmin@test.net')", DB_TABLE_PREFIX));

        // TEST WRONG PASSWORD
        $this->get(osc_base_url().'oc-admin');
        $this->setField('user', 'testadmin');
        $this->setField('password', 'wrong_password');
        $this->click('Log in');
        $this->assertNoText('Latest News from OSClass');
        
        // TEST WRONG USER
        $this->get(osc_base_url().'oc-admin');
        $this->setField('user', 'wrong_testadmin');
        $this->setField('password', 'password');
        $this->click('Log in');
        $this->assertNoText('Latest News from OSClass');
        
        // TEST WRONG USER & PASSWORD
        $this->get(osc_base_url().'oc-admin');
        $this->setField('user', 'wrong_testadmin');
        $this->setField('password', 'wrong_password');
        $this->click('Log in');
        $this->assertNoText('Latest News from OSClass');
        
        // TEST CORRECT LOGIN
        $this->get(osc_base_url().'oc-admin');
        $this->setField('user', 'testadmin');
        $this->setField('password', 'password');
        $this->click('Log in');
        $this->assertNoText('Latest News from OSClass');
        
        
        /*
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
            $this->setField('s_name', 'Test User');
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
            $this->assertField('s_name', 'Test User');
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
            $this->assertNoText('Items from Test User');
            // Try to Login we the wrong password
            $this->get(osc_user_login_url());
            $this->setField('email', 'test@test.net');
            $this->setField('password', 'new_password');
            $this->click('Log in');
            $this->assertNoText('Items from Test User');
            // Login with the correct data
            $this->get(osc_user_login_url());
            $this->setField('email', 'test@test.net');
            $this->setField('password', 'password');
            $this->click('Log in');
            $this->assertText('Items from Test User');
        
        // TEST PASSWORD CHANGE (PASSWORDS DONT MATCH)
        $this->get(osc_change_user_password_url());
        $this->setField('password', 'password');
        $this->setField('new_password', 'new_password');
        $this->setField('new_password2', 'another_password');
        $this->click("Update");
            // Since Simpletest can not read the flash messages
            // The only way we could test if it worked or not is to logout and login again
            $this->get(osc_user_logout_url());
            $this->assertNoText('Items from Test User');
            // Try to Login we the wrong password
            $this->get(osc_user_login_url());
            $this->setField('email', 'test@test.net');
            $this->setField('password', 'new_password');
            $this->click('Log in');
            $this->assertNoText('Items from Test User');
            // Login with the correct data
            $this->get(osc_user_login_url());
            $this->setField('email', 'test@test.net');
            $this->setField('password', 'password');
            $this->click('Log in');
            $this->assertText('Items from Test User');
        
        // TEST PASSWORD CHANGE (EVERYTHING WRONG)
        $this->get(osc_change_user_password_url());
        $this->setField('password', 'test_password');
        $this->setField('new_password', 'new_password');
        $this->setField('new_password2', 'another_password');
        $this->click("Update");
            // Since Simpletest can not read the flash messages
            // The only way we could test if it worked or not is to logout and login again
            $this->get(osc_user_logout_url());
            $this->assertNoText('Items from Test User');
            // Try to Login we the wrong password
            $this->get(osc_user_login_url());
            $this->setField('email', 'test@test.net');
            $this->setField('password', 'new_password');
            $this->click('Log in');
            $this->assertNoText('Items from Test User');
            // Login with the correct data
            $this->get(osc_user_login_url());
            $this->setField('email', 'test@test.net');
            $this->setField('password', 'password');
            $this->click('Log in');
            $this->assertText('Items from Test User');
        
        // TEST PASSWORD CHANGE (EVERYTHING RIGHT)
        $this->get(osc_change_user_password_url());
        $this->setField('password', 'password');
        $this->setField('new_password', 'new_password');
        $this->setField('new_password2', 'new_password');
        $this->click("Update");
            // Since Simpletest can not read the flash messages
            // The only way we could test if it worked or not is to logout and login again
            $this->get(osc_user_logout_url());
            $this->assertNoText('Items from Test User');
            // Try to Login (we should login correctly!)
            $this->get(osc_user_login_url());
            $this->setField('email', 'test@test.net');
            $this->setField('password', 'new_password');
            $this->click('Log in');
            $this->assertText('Items from Test User');
        
        // ROLL BACK CHANGES TO TEST IT OVER AND OVER AGAIN
        $this->get(osc_change_user_password_url());
        $this->setField('password', 'new_password');
        $this->setField('new_password', 'password');
        $this->setField('new_password2', 'password');
        $this->click("Update");
            // Since Simpletest can not read the flash messages
            // The only way we could test if it worked or not is to logout and login again
            $this->get(osc_user_logout_url());
            $this->assertNoText('Items from Test User');
            // Try to Login with old password
            $this->get(osc_user_login_url());
            $this->setField('email', 'test@test.net');
            $this->setField('password', 'new_password');
            $this->click('Log in');
            $this->assertNoText('Items from Test User');
            // Try to Login (we should login correctly!)
            $this->get(osc_user_login_url());
            $this->setField('email', 'test@test.net');
            $this->setField('password', 'password');
            $this->click('Log in');
            $this->assertText('Items from Test User');
            
        // CHANGE EMAIL
        $this->get(osc_change_user_email_url());
        $this->setField('email', 'test@test.net');
        $this->setField('new_email', 'new_test@test.net');
        $this->click('Update');
        $this->get(osc_user_logout_url());
        // WE SENT SOME EMAIL WITH A VALIDATION LINK
        // REPRODUCE THIS WITH CODE
        $user = User::newInstance()->findByEmail('test@test.net');
        $validationLink = osc_change_user_email_confirm_url( $user['pk_i_id'], $user['s_pass_code'] ) ;
        $this->assertTrue($this->get($validationLink));
        // TRY TO LOG IN WITH OLD EMAIL
        $this->get(osc_user_login_url());
        $this->setField('email', 'test@test.net');
        $this->setField('password', 'password');
        $this->click('Log in');
        $this->assertNoText('Items from Test User');
        // TRY TO LOG IN WITH NEW EMAIL
        $this->get(osc_user_login_url());
        $this->setField('email', 'new_test@test.net');
        $this->setField('password', 'password');
        $this->click('Log in');
        $this->assertText('Items from Test User');

        
        */
        // We did our tests, lets get back to normal
        $admin = Admin::newInstance()->findByEmail('testadmin@test.net');
        Admin::newInstance()->delete(array('pk_i_id' =>$admin['pk_i_id']));
    }        
    
}


?>
