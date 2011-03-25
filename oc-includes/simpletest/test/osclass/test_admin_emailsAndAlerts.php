<?php
require_once('../../autorun.php');
require_once('../../web_tester.php');
require_once('../../reporter.php');

// LOAD OSCLASS
require_once '../../../../oc-load.php';
require_once LIB_PATH . 'Selenium.php';

class TestOfAdminEmailsAndalerts extends WebTestCase {

    private $selenium;

    function setUp()
    {
        $conn = getConnection();
        $conn->osc_dbExec(sprintf("INSERT INTO `%st_admin` (`s_name` ,`s_username` ,`s_password` ,`s_secret` ,`s_email`) VALUES ('Test Admin','testadmin','5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8','mvqdnrpt','testadmin@test.net')", DB_TABLE_PREFIX));

        echo "<br><div style='background-color: Wheat; color: black;'>init test</div>";

        $this->selenium = new Testing_Selenium("*firefox", "http://localhost/");
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
    
    function testEditEmailAlert()
    {
        echo "<div style='background-color: green; color: white;'><h2>testEditEmailAlert</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testEditEmailAlert - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testEditEmailAlert - EDIT AN EMAIL/ALERT</div>";
        $this->editEmailAlert() ;
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

    private function editEmailAlert()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Emails & Alerts");
        $this->selenium->click("link=» Manage emails & alerts");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->click("link=Edit"); // edit first email/alert
        $this->selenium->waitForPageToLoad("10000");

        $title = $this->selenium->getValue("en_US#s_title");
        $title .= " UPDATED";
        $this->selenium->type("en_US#s_title",$title);

        $this->selenium->selectFrame("index=0");
        $body = $this->selenium->getText("//html/body");
        $this->selenium->type("xpath=//html/body[@id='tinymce']", "NEW MAIL TEXT".$body);
        $this->selenium->selectFrame("relative=top");

        $this->selenium->click("//button[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");

        $this->assertTrue($this->selenium->isTextPresent("The email/alert has been updated"), "Can't update emails & alerts. ERROR");
    }
}
?>
