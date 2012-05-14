<?php
require_once dirname(__FILE__).'/../../../../oc-load.php';

//require_once('FrontendTest.php');

class OCadmin_emailsAndAlerts extends OCadminTest {
    
    /*
     * Edit and email / alert
     */
    function testEditEmailAlert()
    {
        $this->loginWith() ;
        
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("//a[@id='settings_emails_manage']");
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
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");
        $this->assertTrue($this->selenium->isTextPresent("The email/alert has been updated"), "Edit emails and alerts");
    }
    
}
?>
