<?php
require_once('../../autorun.php');
require_once('../../web_tester.php');
require_once('../../reporter.php');

require_once('util_settings.php');

// LOAD OSCLASS
require_once '../../../../oc-load.php';
require_once LIB_PATH . 'Selenium.php';

class TestOfAdminModertheme extends WebTestCase {

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

    function testCustomAdd()
    {
        echo "<div style='background-color: green; color: white;'><h2>TestOfAdminModertheme</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>TestOfAdminModertheme - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>TestOfAdminModertheme - ADD LOGO</div>";
        $this->addLogo() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>TestOfAdminModertheme - CHECK IF LOGO APPEARS AT WEBSITE</div>";
        $this->checkLogoWebsite() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>TestOfAdminModertheme - REMOVE LOGO</div>";
        $this->removeLogo() ;
        flush();
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

    private function addLogo()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Modern theme");
        $this->selenium->click("link=» Settings theme");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type("xpath=//input[@name='logo']", LIB_PATH."simpletest/test/osclass/logo.jpg");
        $this->selenium->click("id=button_save");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("The logo image has been uploaded correctly"), "Cannot add logo image. ERROR");
    }

    private function checkLogoWebsite()
    {
        $this->selenium->open( osc_base_url(true) );
        $this->selenium->waitForPageToLoad("10000");

        print_r($this->getBrowser()->getFieldById('logo') );
//        $this->assertTrue($this->selenium->isTextPresent("Logo not present at website"), "Logo not present at website. ERROR");
    }

    private function removeLogo()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Modern theme");
        $this->selenium->click("link=» Settings theme");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->click("id=button_remove");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("The logo image has been removed"), "Cannot remove logo image. ERROR");
        $this->assertTrue($this->selenium->isTextPresent("Has not uploaded any logo image"), "Cannot remove logo image. ERROR");
    }


}

?>