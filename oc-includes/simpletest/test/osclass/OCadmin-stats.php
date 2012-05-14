<?php
require_once dirname(__FILE__).'/../../../../oc-load.php';

//require_once('FrontendTest.php');

class OCadmin_stats extends OCadminTest {
    
    /*           TESTS          */
    function testStats()
    {
        $this->loginWith() ;
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Statistics");
        $this->selenium->click("//a[@id='stats_users']");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue($this->selenium->isTextPresent("Users Statistics"),"Stats users");

        $this->selenium->click("link=Statistics");
        $this->selenium->click("//a[@id='stats_items']");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue($this->selenium->isTextPresent("Listings Statistics"),"Items users");
        
        $this->selenium->click("link=Statistics");
        $this->selenium->click("//a[@id='stats_comments']");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue($this->selenium->isTextPresent("Comments Statistics"),"Comments users");
        
        $this->selenium->click("link=Statistics");
        $this->selenium->click("//a[@id='stats_reports']");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue($this->selenium->isTextPresent("Reports Statistics"),"Reports users");
    }

}
?>
