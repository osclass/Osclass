<?php
require_once LIB_PATH . 'Selenium.php';

require_once('../../simpletest.php');
require_once('../../web_tester.php');

// LOAD OSCLASS
define( 'ABS_PATH', dirname( dirname( dirname( dirname( dirname(__FILE__) ) ) ) ) . '/' ) ;
define( 'LIB_PATH',  ABS_PATH .'oc-includes/' );
require_once LIB_PATH . 'osclass/helpers/hErrors.php';

class InstallerTest extends WebTestCase {

    protected $selenium;
    protected $can_continue;
    
    function __construct($label = false) {
        parent::__construct($label);
    }

    function setUp()
    {
        require('config_test.php');
        flush();
        $this->selenium = new Testing_Selenium($browser, "http://localhost/");
        $this->selenium->start();
        $this->selenium->setSpeed("150");
    }

    function tearDown()
    {
        $this->selenium->stop();
        flush();
    }
    
    function clean() {
        require('config_test.php');
        // DROP DATABASE
        $mysqli = new mysqli($db_host, $db_user, $db_pass);
        $mysqli->query("DROP DATABASE ".$db_name);
        // REMOVE config.php file
        @unlink(ABS_PATH . "config.php");
    }
    
}
?>

