<?php
require_once('../../autorun.php');
require_once('../../web_tester.php');
require_once('../../reporter.php');

// LOAD OSCLASS
require_once '../../../../oc-load.php';
require_once LIB_PATH . 'Selenium.php';

include 'test_frontend_register.php';

class TestOfLogin extends WebTestCase {

    private $selenium;
    private $email;


    function setUp()
    {
        echo "<br><div style='background-color: Wheat; color: black;'>init test</div>";

        $this->selenium = new Testing_Selenium("*firefox", "http://localhost/");
        $this->selenium->start();
        $this->selenium->setSpeed("150");
    }

    function tearDown()
    {
        $this->selenium->stop();
        echo "<div style='background-color: Wheat; color: black;'>end test</div>";
        flush();
    }
    /*           TESTS          */

    /*
     * Create a new user and login test, without confirmation
     */
    public function testLogin()
    {
        echo "<div style='background-color: green; color: white;'>FRONTEND - <h2>testLogin</h2> -</div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'> - Create new user -</div>";
        
        // need enabled_user_validation = 0, this way isn't necessary validate
        Preference::newInstance()->update(array('s_value' => 0)
                                         ,array('s_name'  => 'enabled_user_validation'));
        // create a new user.
        $mail = 'carlos+user@osclass.org';
        $pass = '123456';
        TestOfRegister::doRegisterUser($mail,$pass,$this->selenium);

        echo "<div style='background-color: green; color: white;padding-left:15px;'> - Test login - loginPassIncorrect</div>";
        $this->loginPassIncorrect($mail,'foobar');
        echo "<div style='background-color: green; color: white;padding-left:15px;'> - Test login - loginMailIncorrect</div>";
        $this->loginMailIncorrect('some@mail.com',$pass);
        echo "<div style='background-color: green; color: white;padding-left:15px;'> - Test login - login correct</div>";
        $this->login($mail,$pass);
        echo "<div style='background-color: green; color: white;padding-left:15px;'> - Test login - logout </div>";
        $this->logout();
        echo "<div style='background-color: green; color: white;padding-left:15px;'> - Test login - recover password</div>";
        $this->recoverPass($mail);

        $user = User::newInstance()->findByEmail($mail);
        User::newInstance()->deleteUser($user['pk_i_id']);

        Preference::newInstance()->update(array('s_value' => 1)
                                         ,array('s_name'  => 'enabled_user_validation'));
        flush();
    }

    /*
     * PRIVATE FUNCTIONS
     */

    private function logout()
    {
        $this->selenium->open( osc_base_url(true) );
        $this->selenium->click("link=Logout");
        $this->selenium->waitForPageToLoad("30000");

        if($this->selenium->isTextPresent("Log in")){
            $this->assertTrue("ok");
        }
    }

    private function login($mail,$pass)
    {
        $this->selenium->open( osc_base_url(true) );
        $this->selenium->click("login_open");
        $this->selenium->type("email", $mail);
        $this->selenium->type("password", $pass);

        $this->selenium->click("//button[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");

        $this->email = $mail;

        if($this->selenium->isTextPresent("User account manager")){
            $this->assertTrue("ok");
        }
    }

    private function loginPassIncorrect($mail,$pass)
    {
        $this->selenium->open( osc_base_url(true) );
        $this->selenium->click("login_open");
        $this->selenium->type("email", $mail);
        $this->selenium->type("password", $pass);

        $this->selenium->click("//button[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");

        $this->email = $mail;

        $this->assertTrue($this->selenium->isTextPresent("The password is incorrect"));
    }

    private function loginMailIncorrect($mail,$pass)
    {
        $this->selenium->open( osc_base_url(true) );
        $this->selenium->click("login_open");
        $this->selenium->type("email", $mail);
        $this->selenium->type("password", $pass);

        $this->selenium->click("//button[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");

        $this->email = $mail;

        $this->assertTrue($this->selenium->isTextPresent("The username doesn't exist"));
    }

    private function recoverPass($mail)
    {
        $this->selenium->open( osc_base_url(true) );
        $this->selenium->click("login_open");
        $this->selenium->click("link=Forgot password?");
        $this->selenium->waitForPageToLoad("30000");

        $this->selenium->type("s_email",$mail);
        $this->selenium->click('xpath=//span/button');
        $this->selenium->waitForPageToLoad("30000");

        $this->assertTrue($this->selenium->isTextPresent("A new password has been sent to your account"));
    }

}

?>
