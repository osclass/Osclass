<?php
require_once dirname(__FILE__) . '/../../../Selenium.php';

require_once(dirname(__FILE__).'/../../simpletest.php');
require_once(dirname(__FILE__).'/MyWebTestCase.php');


abstract class OCadminTest extends MyWebTestCase {

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

    /*
     * Do login at oc-admin
     */
    function loginWith($mail = NULL, $pass = NULL )
    {
        if( is_null($mail) ) $mail = $this->_adminUser;
        if( is_null($pass) ) $pass = $this->_password;
        
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->waitForPageToLoad(10000);
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
        $this->selenium->click("//a[@id='osc_toolbar_logout']");
        $this->selenium->waitForPageToLoad(10000);
    }
    
    function _lastItemId()
    {
        // get last id from t_item.
        $item   = Item::newInstance()->dao->query('select pk_i_id from '.DB_TABLE_PREFIX.'t_item order by pk_i_id DESC limit 0,1');
        $aItem  = $item->result();
        return $aItem[0]['pk_i_id'];
    }
    
    function _lastItem()
    {
        // get last id from t_item.
        $item   = Item::newInstance()->dao->query('select * from '.DB_TABLE_PREFIX.'t_item order by pk_i_id DESC limit 0,1');
        $aItem  = $item->result();
        return $aItem[0];
    }
}
?>