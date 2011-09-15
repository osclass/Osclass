<?php
require_once LIB_PATH . 'Selenium.php';

require_once('../../simpletest.php');
require_once('../../web_tester.php');


abstract class FrontendTest extends WebTestCase {

    protected $selenium;
    protected $_email;
    protected $_password;

    function setUp()
    {
        include 'config_test.php';

        $this->_email    = $email;
        $this->_password = $password;

        $this->selenium = new Testing_Selenium( $browser, "http://localhost/");
        $this->selenium->start();
        $this->selenium->setSpeed( $speed );
    }

    function tearDown()
    {
        $this->selenium->stop();
    }

    /**
     * Do register if exist 'Register for a free account' link
     * @param string $mail
     * @param string $pass
     * @param string $pass2
     */
    function doRegisterUser($mail = NULL, $pass = NULL, $pass2 = NULL )
    {
        if( is_null($mail) ) $mail = $this->_email;
        if( is_null($pass) ) $pass = $this->_password;
        if( is_null($pass2) ) $pass2 = $pass;

        $this->selenium->open( osc_base_url(true) );
        $this->selenium->click("link=Register for a free account");
        $this->selenium->waitForPageToLoad("3000");

        $this->selenium->type('s_name'      , 'testuser');
        $this->selenium->type('s_password'  , $pass);
        $this->selenium->type('s_password2' , $pass2);
        $this->selenium->type('s_email'     , $mail);

        $this->selenium->click("xpath=//span/button[text()='Create']");
        $this->selenium->waitForPageToLoad("3000");

        echo "< ".$this->selenium->getText('//*[@id="FlashMessage"]')." ><br>";
    }

    /**
     * Do Login at frontend, via login link at header.
     * 
     * @param string $mail
     * @param string $pass
     */
    function loginWith($mail = NULL, $pass = NULL )
    {
        if( is_null($mail) ) $mail = $this->_email;
        if( is_null($pass) ) $pass = $this->_password;
        
        $this->selenium->open( osc_base_url(true) );
        $this->selenium->click("login_open");
        $this->selenium->type("email", $mail);
        $this->selenium->type("password", $pass);

        $this->selenium->click("//button[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");
    }
    
    /**
     * Do logout at frontend, via logout link at header.
     */
    function logout()
    {
        $this->selenium->open( osc_base_url(true) );
        $this->selenium->click("link=Logout");
        $this->selenium->waitForPageToLoad("30000");
    }

    /**
     *
     * @param string $mail
     */
    function removeUserByMail( $mail = NULL )
    {
        if( is_null($mail) ) $mail = $this->_email;
        $user = User::newInstance()->findByEmail($mail);
        User::newInstance()->deleteUser($user['pk_i_id']);
    }
}
?>