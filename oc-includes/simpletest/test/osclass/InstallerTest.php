<?php
require_once dirname(__FILE__).'/../../../Selenium.php';

require_once(dirname(__FILE__).'/../../simpletest.php');
require_once(dirname(__FILE__).'/../../web_tester.php');
require_once(dirname(__FILE__).'/MyWebTestCase.php');

// LOAD OSCLASS
define( 'ABS_PATH', dirname( dirname( dirname( dirname( dirname(__FILE__) ) ) ) ) . '/' ) ;
require_once dirname( dirname( dirname( dirname(__FILE__) ) ) )  . '/osclass/helpers/hErrors.php';

class InstallerTest extends MyWebTestCase {

    protected $selenium;
    protected $can_continue;
    
    function __construct($label = false) {
        parent::__construct($label);
    }

    function setUp()
    {
        require(dirname(__FILE__).'/config_test.php');
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
        require(dirname(__FILE__).'/config_test.php');
        // DROP DATABASE
        $mysqli = new mysqli($db_host, $db_user, $db_pass);
        $mysqli->query("DROP DATABASE ".$db_name);
        // REMOVE config.php file
        @unlink(ABS_PATH . "config.php");
    }
    
}
?>
