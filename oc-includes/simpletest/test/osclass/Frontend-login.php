<?php
require_once dirname(__FILE__).'/../../../../oc-load.php';

class Frontend_login extends FrontendTest {

    /*
     * Insert new user, without confirmation.
     * login :
     *  - incorrect password
     *  - incorrect email
     *  - correct login
     * logout.
     * Recover password.
     */
    public function testLogin()
    {
        $uSettings = new utilSettings();
        // need enabled_user_validation = 0, this way isn't necessary validate
        $uSettings->set_enabled_user_validation(0);

        // register a new user.
        $this->doRegisterUser();

        $this->assertTrue( $this->selenium->isTextPresent("Your account has been created successfully"), "Register an user.");

        // auto login - included in Bender default theme
        $this->logout();

        $this->loginWith(NULL, 'foobar', false);
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue( $this->selenium->isTextPresent("The password is incorrect"), 'Testing, Login user with incorrect password' );

        $this->loginWith('some@mail.com', NULL, false);
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue( $this->selenium->isTextPresent("The user doesn't exist"), 'Testing, Login user with incorrect username' );

        $this->loginWith();
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue( $this->selenium->isTextPresent("My account"), 'Testing, Login user.' );

        $this->logout();
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue( $this->selenium->isTextPresent("Login"), "Do Logout frontend." );

       // recover password
        $this->selenium->open( osc_base_url() );
        $this->selenium->click("login_open");
        $this->selenium->click("link=Forgot password?");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type("s_email",$this->_email);
        $this->selenium->click("xpath=//button[text()='Send me a new password']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("We have sent you an email with the instructions to reset your password"),"Can't recover password. ERROR");

        //$this->removeUserByMail($this->_email);

        $uSettings->set_enabled_user_validation(1);
    }
}
?>
