<?php
require_once dirname(__FILE__).'/../../../../oc-load.php';

//require_once('FrontendTest.php');

class Frontend_csrf extends FrontendTest {

    /*
     * Test redirection when errors with csrf token
     */
    function testCsrfRedirect()
    {
        // Probable invalid request
        $this->selenium->open( osc_base_url(true) . '?page=login' );
        $this->selenium->waitForPageToLoad("30000");

        $this->selenium->runScript("javascript{ this.browserbot.getCurrentWindow().document.getElementsByName('CSRFName')[0].value = ''; }");
        $this->selenium->runScript("javascript{ this.browserbot.getCurrentWindow().document.getElementsByName('CSRFToken')[0].value = ''; }");

        $this->selenium->runScript("javascript{ this.browserbot.getCurrentWindow().document.getElementsByName('CSRFName')[1].value = ''; }");
        $this->selenium->runScript("javascript{ this.browserbot.getCurrentWindow().document.getElementsByName('CSRFToken')[1].value = ''; }");

        $this->selenium->click("xpath=//span/button[text()='Log in']");
        $this->selenium->waitForPageToLoad("30000");

        $this->assertTrue($this->selenium->isTextPresent("Probable invalid request"), 'Testing, CSRFName, CSRFToken empty.');

        // Invalid CSRF token
        $this->selenium->open( osc_base_url(true) . '?page=login' );
        $this->selenium->waitForPageToLoad("30000");

        $this->selenium->runScript("javascript{ this.browserbot.getCurrentWindow().document.getElementsByName('CSRFName')[0].value = 'foo'; }");
        $this->selenium->runScript("javascript{ this.browserbot.getCurrentWindow().document.getElementsByName('CSRFToken')[0].value = 'bar'; }");

        $this->selenium->runScript("javascript{ this.browserbot.getCurrentWindow().document.getElementsByName('CSRFName')[1].value = 'foo'; }");
        $this->selenium->runScript("javascript{ this.browserbot.getCurrentWindow().document.getElementsByName('CSRFToken')[1].value = 'bar'; }");

        $this->selenium->click("xpath=//span/button[text()='Log in']");
        $this->selenium->waitForPageToLoad("30000");

        $this->assertTrue($this->selenium->isTextPresent("Invalid CSRF token"), 'Testing, CSRFName, CSRFToken incorrect.');
    }
}
?>