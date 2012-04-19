<?php
require_once dirname(__FILE__) . '/../../../Selenium.php';

require_once(dirname(__FILE__).'/../../simpletest.php');
require_once(dirname(__FILE__).'/../../web_tester.php');


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
        include dirname(__FILE__).'/config_test.php';
        
        $this->_adminUser = "testadmin";
        $this->_email    = "testing+testadmin@osclass.org";
        $this->_password = $password_admin;
        
        Admin::newInstance()->delete(array('s_email' => $this->_email));
        Admin::newInstance()->insert(array(
            's_name' => 'Test Admin',
            's_username' => 'testadmin',
            's_password' => sha1($this->_password),
            's_secret' => 'mvqdnrpt',
            's_email' => $this->_email
        ));
        

        $this->selenium = new Testing_Selenium( $browser, "http://localhost/");
        $this->selenium->start();
        $this->selenium->setSpeed( $speed );
    }

    function tearDown()
    {
        $this->selenium->stop();
        Admin::newInstance()->delete(array('s_email' => $this->_email));
    }

    function assert($expectation, $compare, $message = '%s')
    {
        $res = parent::assert( $expectation, $compare, $message = '%s' );
        
        $bt = debug_backtrace();
        $function = $bt[2]['function'];

        $date = $function."_".time().".png";
        $path = "/var/www/vm-test-osclass.office/subdomains/images_test/httpdocs/img/";
        $img  = $path.$date;
        
        if(!$res) {
            $a = "<a target='_blank' href='http://images_test.vm-test-osclass.office/img/$date'>Image test failed</a>";
            $this->reporter->addFail($a);
            $cmd = "DISPLAY=:1 import -window root ".$img;
            system($cmd);
            $this->selenium->captureScreenshot($date);
        }
        
        return $res;
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
        $this->selenium->waitForPageToLoad(10000);
        $this->selenium->click('link=Sign out');
        $this->selenium->waitForPageToLoad(10000);
    }
}
?>