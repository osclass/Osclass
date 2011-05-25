<?php
require_once('../../autorun.php');
require_once('../../web_tester.php');
require_once('../../reporter.php');

// LOAD OSCLASS
require_once '../../../../oc-load.php';
require_once LIB_PATH . 'Selenium.php';

class TestOfAdminPlugins extends WebTestCase {

    private $selenium;

    function setUp()
    {
        $conn = getConnection();
        $conn->osc_dbExec(sprintf("INSERT INTO `%st_admin` (`s_name` ,`s_username` ,`s_password` ,`s_secret` ,`s_email`) VALUES ('Test Admin','testadmin','5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8','mvqdnrpt','testadmin@test.net')", DB_TABLE_PREFIX));

        echo "<br><div style='background-color: Wheat; color: black;'>init test</div>";

        $browser = "*firefox";
        $this->selenium = new Testing_Selenium($browser, "http://localhost/");
        $this->selenium->start();
        $this->selenium->setSpeed("150");
    }

    function tearDown()
    {
        $this->selenium->stop();
        $admin = Admin::newInstance()->findByEmail('testadmin@test.net');
        Admin::newInstance()->delete(array('pk_i_id' =>$admin['pk_i_id']));
        echo "<div style='background-color: Wheat; color: black;'>end test</div>";
        flush();
    }
    /*           TESTS          */

    /**
     * upload new language
     * REQUIRE: user logged in
     */
    function testPluginsUpload()
    {
        echo "<div style='background-color: green; color: white;'><h2>testPluginsUpload</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testPluginsUpload - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testPluginsUpload - UPLOAD / INSTALL / CONFIGURE / UNINSTALL PLUGIN</div>";
        $this->allPlugin() ;
        flush();
    }

    /*
     * PRIVATE FUNCTIONS
     */
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

    private function allPlugin()
    {
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->click("link=Plugins");
        $this->selenium->click("link=» Add new plugin");
        $this->selenium->waitForPageToLoad("10000");

        $plugin = "plugins_breadcrumbs_2.0.zip" ;

        $this->selenium->type("package", LIB_PATH."simpletest/test/osclass/plugins_breadcrumbs_2.0.zip");
        $this->selenium->click("//form/input[@id='button_save']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("The plugin has been uploaded correctly"),"Can't upload plugin $plugin");

        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->click("link=Plugins");
        $this->selenium->click("link=» Manage plugins");
        $this->selenium->waitForPageToLoad("10000");
        
        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'Bread crumbs')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'Bread crumbs')]/td/div/a[text()='Install']");
        $this->selenium->waitForPageToLoad("10000");
        
        $this->assertTrue($this->selenium->isTextPresent("Plugin installed"),"Can't install plugin $plugin");

        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'Bread crumbs')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'Bread crumbs')]/td/div/a[text()='Configure']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("Breadcrumbs Help"),"Can't configure plugin $plugin");

        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->click("link=Plugins");
        $this->selenium->click("link=» Manage plugins");
        $this->selenium->waitForPageToLoad("10000");
        
        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'Bread crumbs')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'Bread crumbs')]/td/div/a[text()='Uninstall']");
        $this->selenium->waitForPageToLoad("10000");
        
        $this->assertTrue($this->selenium->isTextPresent("Plugin uninstalled"),"Can't uninstall plugin $plugin");
    }
}
?>
