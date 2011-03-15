<?php
require_once('../../autorun.php');
require_once('../../web_tester.php');
require_once('../../reporter.php');

// LOAD OSCLASS
require_once '../../../../oc-load.php';
require_once LIB_PATH . 'Selenium.php';

class TestOfContact extends WebTestCase {

    private $selenium;

    function setUp()
    {
        echo "<br><div style='background-color: Wheat; color: black;'>init test</div>";

        $this->selenium = new Testing_Selenium("*firefox", "http://localhost/");
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
    function testContact()
    {
        echo "<div style='background-color: green; color: white;'>FRONTEND - <h2>testContact</h2> -</div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'> - testContact - send mail</div>";
        $this->contact();
        flush();
    }

    /*
     * PRIVATE FUNCTIONS
     */
    private function contact()
    {
        $this->selenium->open(osc_base_url(true));
        $this->selenium->click("link=Contact");
        $this->selenium->waitForPageToLoad("30000");
        $this->selenium->type("subject", "subject");
        $this->selenium->type("message", "message\nto be\nsend");
        $this->selenium->type("yourName", "Carlos");
        $this->selenium->type("yourEmail", "carlos+user@osclass.org");
        $this->selenium->click('xpath=//span/button');
        $this->selenium->waitForPageToLoad("30000");

        $this->assertTrue($this->selenium->isTextPresent("Your e-mail has been sent properly. Thank your for contacting us!"));
    }
}
?>
