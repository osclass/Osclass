<?php
require_once dirname(__FILE__).'/../../../../oc-load.php';

//require_once('FrontendTest.php');

/*
 *  ADD :
 * The reCAPTCHA was not introduced correctly
 * The email is not valid
 * The password cannot be empty
 */



class Frontend_register extends FrontendTest {

    /*
     * insert new user,  modifying the value of enabled_user_validation = 1
     */
    function testRegisterNewUser_WithValidation()
    {
        $uSettings = new utilSettings();

        $old_enabled_users              = $uSettings->set_enabled_users(1);
        $old_enabled_users_registration = $uSettings->set_enabled_user_registration(1);
        $old_enabled_user_validation    = $uSettings->set_enabled_user_validation(1);

        $this->doRegisterUser();
        $this->assertTrue( $this->selenium->isTextPresent('The user has been created. An activation email has been sent'), 'Register new user with validation.');

        $this->removeUserByMail($this->_email);

        $uSettings->set_enabled_users($old_enabled_users);
        $uSettings->set_enabled_user_registration($old_enabled_users_registration);
        $uSettings->set_enabled_user_validation($old_enabled_user_validation);

        unset($uSettings);
    }

    /*
     * insert new user, modifying the value of enabled_user_validation = 0
     */
    function testRegisterNewUser_NoValidation()
    {
        $uSettings = new utilSettings();

        $old_enabled_users           = $uSettings->set_enabled_users(1);
        $old_enabled_users_registration = $uSettings->set_enabled_user_registration(1);
        $old_enabled_user_validation = $uSettings->set_enabled_user_validation(0);

        $this->doRegisterUser();
        $this->assertTrue( $this->selenium->isTextPresent('Your account has been created successfully'), 'Register new user without validation.');

        $this->removeUserByMail($this->_email);

        $uSettings->set_enabled_users($old_enabled_users);
        $uSettings->set_enabled_user_registration($old_enabled_users_registration);
        $uSettings->set_enabled_user_validation($old_enabled_user_validation);

        unset($uSettings);
    }

    /*
     * insert same user twice.
     */
    function testRegisterUserTwice()
    {
        $uSettings = new utilSettings();

        $old_enabled_users           = $uSettings->set_enabled_users(1);
        $old_enabled_users_registration = $uSettings->set_enabled_user_registration(1);
        $old_enabled_user_validation = $uSettings->set_enabled_user_validation(0);

        $this->doRegisterUser();
        $this->assertTrue( $this->selenium->isTextPresent('Your account has been created successfully'), 'Register new user without validation.');

        // auto login, Bender default
        $this->logout();

        // register again, same user
        $this->doRegisterUser();
        $this->assertTrue( $this->selenium->isTextPresent('The specified e-mail is already in use'), 'Register user twice.');

        $this->removeUserByMail($this->_email);

        $uSettings->set_enabled_users($old_enabled_users);
        $uSettings->set_enabled_user_registration($old_enabled_users_registration);
        $uSettings->set_enabled_user_validation($old_enabled_user_validation);

        unset($uSettings);
    }


   function testRegisterUserEmptyPasswords()
    {
        $uSettings = new utilSettings();

        $old_enabled_users              = $uSettings->set_enabled_users(1);
        $old_enabled_users_registration = $uSettings->set_enabled_user_registration(1);
        $old_enabled_user_validation    = $uSettings->set_enabled_user_validation(0);

        $this->doRegisterUser($this->_email, '', '');

        // js validation
        $this->assertTrue( $this->selenium->isTextPresent('Password: this field is required.'), 'Register new user, empty passwords.');
        $this->assertTrue( $this->selenium->isTextPresent('Second password: this field is required.'), 'Register new user, empty passwords.');
        // modern theme
//        $this->assertTrue( $this->selenium->isTextPresent('The password cannot be empty'), 'Register new user, empty passwords.');

        $uSettings->set_enabled_users($old_enabled_users);
        $uSettings->set_enabled_user_registration($old_enabled_users_registration);
        $uSettings->set_enabled_user_validation($old_enabled_user_validation);

        unset($uSettings);
    }

    /*
     * insert new  user, passwords don't match
     */
    function testRegisterUserIncorrectPasswords()
    {
        $uSettings = new utilSettings();

        $old_enabled_users           = $uSettings->set_enabled_users(1);
        $old_enabled_users_registration = $uSettings->set_enabled_user_registration(1);
        $old_enabled_user_validation = $uSettings->set_enabled_user_validation(0);

        $this->doRegisterUser($this->_email, $this->_password, 'foobar_no_password');
        $this->assertTrue( $this->selenium->isTextPresent('Passwords don\'t match'), 'Register new user, passwords don\' match.');

        $uSettings->set_enabled_users($old_enabled_users);
        $uSettings->set_enabled_user_registration($old_enabled_users_registration);
        $uSettings->set_enabled_user_validation($old_enabled_user_validation);

        unset($uSettings);
    }

    /*
     * insert new user, with validation link,
     * goto wrong validation link
     * goto validation link and validate the user
     */
    function testCheckValidationLink()
    {
        $uSettings = new utilSettings();

        $old_enabled_users           = $uSettings->set_enabled_users(1);
        $old_enabled_users_registration = $uSettings->set_enabled_user_registration(1);
        $old_enabled_user_validation = $uSettings->set_enabled_user_validation(1);

        $this->doRegisterUser();
        $this->assertTrue( $this->selenium->isTextPresent('The user has been created. An activation email has been sent'), 'Testing, Register new user with validation.');

        $user = User::newInstance()->findByEmail($this->_email);
        // goto wrong validation link
        $url_validate = osc_user_activate_url($user['pk_i_id'], '1231231');
        $this->selenium->open( $url_validate );
        $this->selenium->waitForPageToLoad("1000");
        $this->assertTrue( $this->selenium->isTextPresent('regexpi:The link is not valid anymore. Sorry for the inconvenience!'), 'Validate user. Go to wrong user validation link.');
        // goto correct validation link

        $url_validate = osc_user_activate_url($user['pk_i_id'], $user['s_secret']);
        $this->selenium->open( $url_validate );
        $this->selenium->waitForPageToLoad("1000");
        $this->assertTrue( $this->selenium->isTextPresent('regexpi:Your account has been validated'), 'Validate user. Go to user validation link.');

        $this->removeUserByMail($this->_email);

        $uSettings->set_enabled_users($old_enabled_users);
        $uSettings->set_enabled_user_registration($old_enabled_users_registration);
        $uSettings->set_enabled_user_validation($old_enabled_user_validation);

        unset($uSettings);
    }

}
?>
