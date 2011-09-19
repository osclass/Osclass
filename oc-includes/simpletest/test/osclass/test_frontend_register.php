<?php
require_once('../../autorun.php');
require_once('../../web_tester.php');
require_once('../../reporter.php');

// LOAD OSCLASS
require_once '../../../../oc-load.php';
require_once LIB_PATH . 'Selenium.php';

class TestOfRegister extends WebTestCase {

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

    /**
     * insert new User
     * 
     */
    public function testRegisterNewUser()
    {
        echo "<div style='background-color: green; color: white;'><h2>test_frontend_register >> testRegisterNewUser</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'> - User insert -  </div>";
        $this->registerUser() ;

        $user = User::newInstance()->findByEmail($this->email);
        User::newInstance()->deleteUser($user['pk_i_id']);
        flush();
    }

    /**
     * insert new user, but modifying the value of enabled_user_validation
     */
    function testRegisterNewUser2()
    {
        echo "<div style='background-color: green; color: white;'><h2>test_frontend_register >> testRegisterNewUser2</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'> - User insert - modifying the value of enabled_user_validation ... </div>";

        if(osc_user_validation_enabled() ) {
            Preference::newInstance()->update(array('s_value' => 0)
                                             ,array('s_name'  => 'enabled_user_validation'));
            $next = 1;
        } else {
            Preference::newInstance()->update(array('s_value' => 1)
                                             ,array('s_name'  => 'enabled_user_validation'));
            $next = 0;
        }

        $this->registerUser() ;

        $user = User::newInstance()->findByEmail($this->email);
        User::newInstance()->deleteUser($user['pk_i_id']);

        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'> - User insert - restoring the value of enabled_user_validation ... </div>";
        Preference::newInstance()->update(array('s_value' => $next)
                                         ,array('s_name'  => 'enabled_user_validation'));
    }

    /**
     * Insert user twice
     */
    public function testRegisterSameUserTwice()
    {
        echo "<div style='background-color: green; color: white;'><h2>test_frontend_register >> testRegisterSameUserTwice</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'> - User insert twice- </div>";
        $this->registerUserTwice() ;
        flush();
        $user = User::newInstance()->findByEmail($this->email);
        User::newInstance()->deleteUser($user['pk_i_id']);
    }

    /**
     * Insert User - test passwords don't match
     */
     public function testRegisterUserIncorrectPasswords()
     {
        echo "<div style='background-color: green; color: white;'><h2>test_frontend_register >> testRegisterUserIncorrectPasswords</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'> - User insert - passwords incorrect</div>";
        $this->registerUserIncorrectPassword() ;
        flush();
     }

     /**
      * test validation url
      */
     public function testValidation()
     {
        echo "<div style='background-color: green; color: white;'><h2>test_frontend_register >> testValidation</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'> - User insert -</div>";
        $this->registerValidate();

        $user = User::newInstance()->findByEmail($this->email);
        User::newInstance()->deleteUser($user['pk_i_id']);
     }


    /*
     * PRIVATE FUNCTIONS
     */
    private function registerUser()
    {
        $this->selenium->open( osc_base_url(true) );
        $this->selenium->click("link=Register for a free account");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type('s_name'      , 'testuser');
        $this->selenium->type('s_password'  , 'password');
        $this->selenium->type('s_password2' , 'password');
        $this->selenium->type('s_email'     , 'carlos@osclass.org');

        $this->selenium->click("xpath=//span/button[text()='Create']");
        $this->selenium->waitForPageToLoad(1000);
        
        $this->email = "carlos@osclass.org";

        $bool = Preference::newInstance()->findValueByName('enabled_user_validation');
       
        echo "< ".$this->selenium->getText('//*[@id="FlashMessage"]')." ><br>";

        if( $bool ) {
            if( $this->selenium->isTextPresent('The user has been created. An activation email has been sent') ){
                $this->assertTrue("todo bien");
            } else {
                $this->assertFalse("Can't register + with validation - The user has been created. An activation email has been sent");
            }
        } else {
            if( $this->selenium->isTextPresent('Your account has been created successfully') ){
                $this->assertTrue("todo bien");
            } else {
                $this->assertFalse("Can't register - without validation - Your account has been created successfully");
            }
        }
    }

    private function registerUserTwice()
    {
        $this->selenium->open( osc_base_url(true) );
        $this->selenium->click("link=Register for a free account");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type('s_name'      , 'testuser');
        $this->selenium->type('s_password'  , 'password_');
        $this->selenium->type('s_password2' , 'password_');
        $this->selenium->type('s_email'     , 'carlos+user@osclass.org');
        
        $this->email = 'carlos+user@osclass.org';

        $this->selenium->click("xpath=//span/button[text()='Create']");
        $this->selenium->waitForPageToLoad("1000");

        echo "< ".$this->selenium->getText('//*[@id="FlashMessage"]')." ><br>";

        if( $this->selenium->isTextPresent('The user has been created. An activation email has been sent') ){
            $this->assertTrue("todo bien");
            
            $this->selenium->click("link=Register for a free account");
            $this->selenium->waitForPageToLoad("10000");

            $this->selenium->type('s_name'      , 'testuser');
            $this->selenium->type('s_password'  , 'password_');
            $this->selenium->type('s_password2' , 'password_');
            $this->selenium->type('s_email'     , 'carlos+user@osclass.org');

            $this->selenium->click("xpath=//span/button[text()='Create']");
            $this->selenium->waitForPageToLoad(1000);

            echo "< ".$this->selenium->getText('//*[@id="FlashMessage"]')." ><br>";
            
            if( $this->selenium->isTextPresent('The specified e-mail is already in use') ){
                $this->assertTrue("todo bien");
            } else {
                $this->assertFalse("Can Register Twice. ERROR");
            }

        } else {
            $this->assertFalse("Can't register");
        }
    }

    private function registerUserIncorrectPassword()
    {
        $this->selenium->open( osc_base_url(true) );
        $this->selenium->click("link=Register for a free account");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type('s_name'      , 'testuser');
        $this->selenium->type('s_password'  , '_password');
        $this->selenium->type('s_password2' , 'password_');
        $this->selenium->type('s_email'     , 'carlos+user_@osclass.org');

        $this->selenium->click("xpath=//span/button[text()='Create']");
        $this->selenium->waitForPageToLoad(1000);

        if( $this->selenium->isTextPresent('regexpi:Passwords don\'t match.') ){
            $this->assertTrue("todo bien");
        } else {
            $this->assertFalse("Can Register with different passwords. ERROR");
        }
    }

    private function registerValidate()
    {
        // set value 1 => enabled_user_validation
        Preference::newInstance()->update(array('s_value' => 1)
                                         ,array('s_name'  => 'enabled_user_validation'));
        // INSERT NEW USER
        $this->registerUser();
        $user = User::newInstance()->findByEmail($this->email);

//      url malformed
        $url_validate = osc_base_url(true) . "?page=register&action=validate&id=".$user['pk_i_id']."&code=1231231";
        $this->selenium->open( $url_validate );
        $this->selenium->waitForPageToLoad("1000");
        echo "< ".$this->selenium->getText('//*[@id="FlashMessage"]')." ><br>";
        if( $this->selenium->isTextPresent('regexpi:The link is not valid anymore. Sorry for the inconvenience!') ){
            $this->assertTrue("todo bien");
        } else {
            $this->assertFalse("Can validate an malformed validation user url. ERROR");
        }

//      Validation ok
        $url_validate = osc_base_url(true) . "?page=register&action=validate&id=".$user['pk_i_id']."&code=".$user['s_secret'];
        $this->selenium->open( $url_validate );
        $this->selenium->waitForPageToLoad("1000");
        echo "< ".$this->selenium->getText('//*[@id="FlashMessage"]')." ><br>";
        if( $this->selenium->isTextPresent('regexpi:Your account has been validated') ){
            $this->assertTrue("todo bien");
        } else {
            $this->assertFalse("Can validate user. ERROR");
        }

//      Try to revalidate new user
        $this->selenium->open( $url_validate );
        $this->selenium->waitForPageToLoad("1000");
        echo "< ".$this->selenium->getText('//*[@id="FlashMessage"]')." ><br>";
        if( $this->selenium->isTextPresent('regexpi:Your account has already been validated') ){
            $this->assertTrue("todo bien");
        } else {
            $this->assertFalse("Validate an validated user. ERROR");
        }
    }
}
?>
