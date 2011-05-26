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
        $browser = "*firefox";
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
            $this->selenium->click("css=span");
            $this->selenium->click("createdb");
            $this->selenium->type("admin_username", "root");
            $this->selenium->click("submit");
            $this->selenium->waitForPageToLoad("30000");

            // step 3
            if( $this->assertFalse($this->selenium->isTextPresent("There are tables with the same name in the database. Change the table prefix or the database and try again."), "NEED DROP DATABASE osclass, for continue the installation!") ) {
                $this->can_continue = false;
            }
            $this->assertTrue( $this->selenium->isTextPresent("Information needed"), "IS NOT STEP 3 ! (information needed)" );
            $this->selenium->type("webtitle", "test_web_osclass");
            $this->selenium->type("email", "carlos@osclass.org");

            $this->selenium->type("xpath=//input[@id='t_country']", "spai");
            $this->selenium->keyDown( "xpath=//input[@id='t_country']", "n" ) ;
            $this->selenium->keyPress( "xpath=//input[@id='t_country']", "\\13");
            sleep(2);
            $this->selenium->click("xpath=//div[@id='location']/div[@id='country-box']/div[@id='a_country']/ul/li/a");
            $this->selenium->click("link=Next");
            $this->selenium->waitForPageToLoad("300000");
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

    function testStep2()
    {
        if($this->can_continue){
            echo "<div style='background-color: green; color: white;'><h2>testStep2</h2></div>";
        }
    }

    function testStep3()
    {
        if($this->can_continue){
            echo "<div style='background-color: green; color: white;'><h2>testStep3</h2></div>";
        }
    }

    /*      PRIVATE FUNCTIONS       */
    private function loginCorrect()
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
            $this->assertTrue("todo bien");
        } else {
            $this->assertFalse("can't loggin");
        }
    }

    private function widgetsHeader()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Appearance");
        $this->selenium->click("link=» Add or remove widgets");
        $this->selenium->waitForPageToLoad("10000");

        // add header widget
        $this->selenium->click("xpath=//div[@id='settings_form']/div/div[1]/div/a");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type("description", "header1");

        $this->selenium->selectFrame("index=0");
        $this->selenium->type("xpath=//html/body[@id='tinymce']", "New Widget Header");
        $this->selenium->selectFrame("relative=top");

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");

        $this->assertTrue($this->selenium->isTextPresent("Widget added correctly"), "Can't add widget header. ERROR" );
        $this->assertTrue($this->selenium->isTextPresent("header1"), "Can't add widget header. header1 not present. ERROR");

        //remove widget
        $this->selenium->click("link=Delete");
        $this->selenium->waitForPageToLoad("30000");
        $this->assertTrue($this->selenium->isTextPresent("Widget removed correctly"), "Can't delete widget header. ERROR" );
        $this->assertTrue( ! $this->selenium->isTextPresent("header1"), "Can't delete widget header. header1 still present. ERROR");
    }

    private function widgetsCategories()
    {
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->click("link=Appearance") ;
        $this->selenium->click("link=» Add or remove widgets") ;
        $this->selenium->waitForPageToLoad("10000") ;

        // add categories widget
        $this->selenium->click("xpath=//div[@id='settings_form']/div/div[2]/div/a") ;
        $this->selenium->waitForPageToLoad("10000") ;

        $this->selenium->type("description", "categories1") ;

        $this->selenium->selectFrame("index=0");
        $this->selenium->type("xpath=//html/body[@id='tinymce']", "New Widget Category") ;
        $this->selenium->selectFrame("relative=top") ;

        $this->selenium->click("//input[@type='submit']") ;
        $this->selenium->waitForPageToLoad("30000");

        $this->assertTrue($this->selenium->isTextPresent("Widget added correctly"), "Can't add widget categories. ERROR" );
        $this->assertTrue($this->selenium->isTextPresent("categories1"), "Can't add widget categories. ERROR");

        //remove widget
        $this->selenium->click("link=Delete");
        $this->selenium->waitForPageToLoad("30000");
        $this->assertTrue($this->selenium->isTextPresent("Widget removed correctly"), "Can't delete widget categories. ERROR" );
        $this->assertTrue( ! $this->selenium->isTextPresent("categories1"), "Can't delete widget categories. ERROR");
    }

    private function widgetsFooter()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Appearance");
        $this->selenium->click("link=» Add or remove widgets");
        $this->selenium->waitForPageToLoad("10000");

        // add categories widget
        $this->selenium->click("xpath=//div[@id='settings_form']/div/div[3]/div/a");
        $this->selenium->waitForPageToLoad("10000") ;

        $this->selenium->type("description", "footer1") ;

        $this->selenium->selectFrame("index=0");
        $this->selenium->type("xpath=//html/body[@id='tinymce']", "New Widget Footer") ;
        $this->selenium->selectFrame("relative=top") ;

        $this->selenium->click("//input[@type='submit']") ;
        $this->selenium->waitForPageToLoad("30000");

        $this->assertTrue($this->selenium->isTextPresent("Widget added correctly"), "Can't add widget footer. ERROR" );
        $this->assertTrue($this->selenium->isTextPresent("footer1"), "Can't add widget footer. ERROR");

        //remove widget
        $this->selenium->click("link=Delete");
        $this->selenium->waitForPageToLoad("30000");
        $this->assertTrue($this->selenium->isTextPresent("Widget removed correctly"), "Can't delete widget footer. ERROR" );
        $this->assertTrue( ! $this->selenium->isTextPresent("footer1"), "Can't delete widget footer. ERROR");
    }
}
?>

