<?php
require_once('../../autorun.php');
require_once('../../web_tester.php');
require_once('../../reporter.php');

// LOAD OSCLASS
define( 'ABS_PATH', dirname( dirname( dirname( dirname( dirname(__FILE__) ) ) ) ) . '/' ) ;
define( 'LIB_PATH',  ABS_PATH .'oc-includes/' );
require_once LIB_PATH . 'osclass/helpers/hErrors.php';



require_once LIB_PATH . 'Selenium.php';

class TestOfInstaller extends WebTestCase {

    private $selenium;
    private $can_continue;

    function setUp()
    {
        echo "<br><div style='background-color: Wheat; color: black;'>init test</div>";
        flush();
        $browser = "*googlechrome";
        $this->selenium = new Testing_Selenium($browser, "http://localhost/");
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
    function testInstaller1()
    {
        echo "<div style='background-color: green; color: white;'><em><h2>drop database & remove \$ROOT/config.php</h2></em></div>";
        echo "<div style='background-color: green; color: white;'><h2>testInstaller1</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testInstaller1</div>";
        flush();
       
        $config_file = ABS_PATH . "config.php";
        if( !file_exists($config_file) ) {
            $this->can_continue = true;
            
            $this->selenium->open( osc_get_absolute_url() . "oc-includes/osclass/install.php" );
            // step 1
            $this->selenium->click("css=input.button");
            $this->selenium->waitForPageToLoad("30000");

            // step 2
            $this->assertTrue( $this->selenium->isTextPresent("Database information"), "IS NOT STEP 2 ! (databse information)" );
            $this->selenium->type("username", "root");
            $this->selenium->type("password", "password");
            $this->selenium->click("css=span");
            $this->selenium->click("createdb");
            $this->selenium->type("admin_username", "root");
            $this->selenium->type("admin_password", "password");
            $this->selenium->type("tableprefix", "test_");
            $this->selenium->click("submit");
            $this->selenium->waitForPageToLoad("30000");

            // step 3
            if( $this->assertFalse($this->selenium->isTextPresent("There are tables with the same name in the database. Change the table prefix or the database and try again."), "NEED DROP DATABASE osclass, for continue the installation!") ) {
                $this->can_continue = false;
            }
            $this->assertTrue( $this->selenium->isTextPresent("Information needed"), "IS NOT STEP 3 ! (information needed)" );
            $this->selenium->type("s_name", "admin");
            $this->selenium->type("s_passwd", "admin");
            
            $this->selenium->type("webtitle", "test_web_osclass");
            $this->selenium->type("email", "nodani@gmail.com");

            $this->selenium->type("xpath=//input[@id='t_country']", "spai");
            $this->selenium->keyDown( "xpath=//input[@id='t_country']", "n" ) ;
            $this->selenium->keyPress( "xpath=//input[@id='t_country']", "\\13");
            sleep(2);
            $this->selenium->click("xpath=//div[@id='location']/div[@id='country-box']/div[@id='a_country']/ul/li/a");
            $this->selenium->click("link=Next");
            $this->selenium->waitForPageToLoad("600000");
            // step 4
            $this->assertTrue($this->selenium->isTextPresent("Categories"), "IS NOT STEP 4 ! (categories)");
            $this->selenium->click("link=Check all");
            $this->selenium->click("submit");
            $this->selenium->waitForPageToLoad("30000");
            // step 5
            $this->assertTrue($this->selenium->isTextPresent("OSClass has been installed."), "OSClass has NOT been installed!");
        } else {
            echo "<div style='background-color: red; color: white;padding-left:15px;'>$config_file EXIST, CANNOT INSTALL OSCLASS IF EXIST</div>";
            $this->can_continue = false;
        }
    }
}
?>

