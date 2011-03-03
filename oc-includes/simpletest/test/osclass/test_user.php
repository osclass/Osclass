<?php

    require_once('../../autorun.php');
    require_once('../../web_tester.php');
    require_once('../../reporter.php');

    // LOAD OSCLASS
    require_once '../../../../oc-load.php';

class TestOfUserAccount extends WebTestCase {
    
    function testUserAccount() {
        // LOAD SOME DATA 
        $conn = getConnection();
        $conn->osc_dbExec(sprintf("INSERT INTO `%st_user` (`dt_reg_date` ,`dt_mod_date` ,`s_name` ,`s_username` ,`s_password` ,`s_secret` ,`s_email` ,`s_website` ,`s_phone_land` ,`s_phone_mobile` ,`b_enabled` ,`s_pass_code` ,`s_pass_date` ,`s_pass_question` ,`s_pass_answer` ,`s_pass_ip` ,`fk_c_country_code` ,`s_country` ,`s_address` ,`s_zip` ,`fk_i_region_id` ,`s_region` ,`fk_i_city_id` ,`s_city` ,`fk_i_city_area_id` ,`s_city_area` ,`d_coord_lat` ,`d_coord_long` ,`i_permissions`) VALUES (NOW(), NULL,'Test User','','5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8','mvqdnrpt','test@test.net','http://www.example.com','123456','654321',1,NULL,NULL,NULL,NULL,NULL,'ES','Spain','Calle False',NULL,3,'Barcelona',3,'Sabadell',NULL,'La Salut',NULL,NULL,'0')", DB_TABLE_PREFIX));

        // TEST WRONG PASSWORD
        $this->get(osc_user_login_url());
        $this->setField('email', 'test@test.net');
        $this->setField('password', 'wrong_password');
        $this->click('Log in');
        $this->assertNoText('Items from Test User');
        
        // TEST WRONG EMAIL
        $this->get(osc_user_login_url());
        $this->setField('email', 'wrong_email@test.net');
        $this->setField('password', 'password');
        $this->click('Log in');
        $this->assertNoText('Items from Test User');
        
        // TEST WRONG EMAIL & PASSWORD
        $this->get(osc_user_login_url());
        $this->setField('email', 'wrong_email@test.net');
        $this->setField('password', 'wrong_password');
        $this->click('Log in');
        $this->assertNoText('Items from Test User');
        
        // TEST CORRECT LOGIN
        $this->get(osc_user_login_url());
        $this->setField('email', 'test@test.net');
        $this->setField('password', 'password');
        $this->click('Log in');
        $this->assertText('Items from Test User');
        
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
        
        
        
        //osc_change_user_email_url()
        
        // We did our tests, lets get back to normal
        $user = User::newInstance()->findByEmail('test@test.net');
        User::newInstance()->deleteUser($user['pk_i_id']);
    }        
    
}

        /**************************
         * SIMPLE TEST DOES NOT SUPPORT JS
         * WE CAN NOT TEST REGISTER FORM
         ***************************/

        // REGISTER USER WITH WRONG DATA
        /*$this->get(osc_register_account_url());
        $this->setField('s_name', 'Test User');
        $this->setField('s_password', 'password');
        $this->setField('s_password2', 'password2');
        $this->setField('s_email', 'test@test.net');
        $this->click('CREATE');
        $user = User::newInstance()->findByEmail('test@test.net');
        // ACTIVATE THE USER
        if(isset($user['pk_i_id'])) {
            User::newInstance()->update(array('b_enabled' => 1), array('pk_i_id' => $user['pk_i_id']));
        }
        // TEST WRONG USER
        $this->get(osc_user_login_url());
        $this->setField('email', 'test@test.net');
        $this->setField('password', 'password');
        $this->click('Log in');
        $this->assertNoText('Items from Test User');
        $this->get(osc_user_logout_url());
        $this->assertNoText('Items from Test User');
    
        // REGISTER USER WITH CORRECT DATA
        $this->get(osc_register_account_url());
        $this->setField('s_name', 'Test User');
        $this->setField('s_password', 'password');
        $this->setField('s_password2', 'password');
        $this->setField('s_email', 'test@test.net');
        $this->click('CREATE');        
        // ACTIVATE THE USER
        if(isset($user['pk_i_id'])) {
            User::newInstance()->update(array('b_enabled' => 1), array('pk_i_id' => $user['pk_i_id']));
        }
        // TEST CORRECT USER
        $this->get(osc_user_login_url());
        $this->setField('email', 'test@test.net');
        $this->setField('password', 'password');
        $this->click('Log in');
        $this->assertText('Items from Test User');
        $this->get(osc_user_logout_url());
        $this->assertNoText('Items from Test User');*/

?>
