<?php
require_once LIB_PATH . 'Selenium.php';

require_once('../../simpletest.php');
require_once('../../web_tester.php');


abstract class OCadminTest extends WebTestCase {

    protected $selenium;
    protected $_adminUser;
    protected $_email;
    protected $_password;

    function __construct($label = false) {
        parent::__construct($label);
    }
    
    function setUp()
    {
        include 'config_test.php';
        
        $this->_adminUser = "testadmin";
        $this->_email    = $email_admin;
        $this->_password = $password_admin;
        $conn = getConnection();
        $conn->osc_dbExec(sprintf("INSERT INTO `%st_admin` (`s_name` ,`s_username` ,`s_password` ,`s_secret` ,`s_email`) VALUES ('Test Admin','testadmin','5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8','mvqdnrpt','".$this->_email."')", DB_TABLE_PREFIX));

        $this->selenium = new Testing_Selenium( $browser, "http://localhost/");
        $this->selenium->start();
        $this->selenium->setSpeed( $speed );
    }

    function tearDown()
    {
        $this->selenium->stop();
        $admin = Admin::newInstance()->findByEmail($this->_email);
        Admin::newInstance()->delete(array('pk_i_id' =>$admin['pk_i_id']));
    }
    
    /*
     * Do login at oc-admin
     */
    function loginWith($mail = NULL, $pass = NULL )
    {
        if( is_null($mail) ) $mail = $this->_adminUser;
        if( is_null($pass) ) $pass = $this->_password;
        
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->waitForPageToLoad(10000);
        // if you are logged fo log out
//        if( $this->selenium->isTextPresent('Log Out') ){
//            $this->selenium->click('Log Out');
//            $this->selenium->waitForPageToLoad(1000);
//        }
        $this->selenium->type('user', $mail);
        $this->selenium->type('password', $pass);
        $this->selenium->click('submit');
        $this->selenium->waitForPageToLoad(1000);
    }
    
    /**
     * Do logout at frontend, via logout link at header.
     */
    function logout()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Logout");
        $this->selenium->waitForPageToLoad("30000");
    }
    
    /**
     * Do login at backend
     */
    function loginCorrect()
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
            $this->assertTrue("OK LOGIN");
        } else {
            $this->assertFalse("LOGIN FAILED");
        }
    }

    
    
}
?>