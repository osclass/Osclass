<?php
require_once dirname(__FILE__).'/../../../../oc-load.php';

//require_once('FrontendTest.php');

class Frontend_csrf extends FrontendTest {

    /*
     * Test redirection when errors with csrf token
     */
    function testCsrfRedirect()
    {
        // redirect to contact page adding &http_referer=_URL_

        // Probable invalid request
        $this->selenium->open( osc_base_url(true) . '?page=login&http_referer=index.php?page=contact' );
        $this->selenium->waitForPageToLoad("30000");

        $this->selenium->runScript("javascript{ this.browserbot.getCurrentWindow().document.getElementsByName('CSRFName')[0].value = ''; }");
        $this->selenium->runScript("javascript{ this.browserbot.getCurrentWindow().document.getElementsByName('CSRFToken')[0].value = ''; }");

        $this->selenium->runScript("javascript{ this.browserbot.getCurrentWindow().document.getElementsByName('CSRFName')[1].value = ''; }");
        $this->selenium->runScript("javascript{ this.browserbot.getCurrentWindow().document.getElementsByName('CSRFToken')[1].value = ''; }");

        $this->selenium->click("xpath=//span/button[text()='Log in']");
        $this->selenium->waitForPageToLoad("30000");

        $this->assertTrue($this->selenium->isTextPresent("Probable invalid request"), 'Testing, CSRFName, CSRFToken empty.');
        // check redirection
//        $this->assertTrue($this->selenium->isTextPresent("Access to your account"), 'Testing, CSRF redirect to $_SERVER[\'HTTP_REFERER\'].');
        $this->assertTrue($this->selenium->isTextPresent("Contact us"), 'Testing, CSRF redirection using GET param http_referer.');

        // Invalid CSRF token
        error_log('------------------------------------------------');
        $this->selenium->open( osc_base_url(true) . '?page=login' );
        $this->selenium->waitForPageToLoad("30000");

        $this->selenium->runScript("javascript{ this.browserbot.getCurrentWindow().document.getElementsByName('CSRFName')[0].value = 'foo'; }");
        $this->selenium->runScript("javascript{ this.browserbot.getCurrentWindow().document.getElementsByName('CSRFToken')[0].value = 'bar'; }");

        $this->selenium->runScript("javascript{ this.browserbot.getCurrentWindow().document.getElementsByName('CSRFName')[1].value = 'foo'; }");
        $this->selenium->runScript("javascript{ this.browserbot.getCurrentWindow().document.getElementsByName('CSRFToken')[1].value = 'bar'; }");

        $this->selenium->click("xpath=//span/button[text()='Log in']");
        $this->selenium->waitForPageToLoad("30000");

        $this->assertTrue($this->selenium->isTextPresent("Invalid CSRF token"), 'Testing, CSRFName, CSRFToken incorrect.');
        // check no redirection
//        $this->assertTrue($this->selenium->isTextPresent("Contact us"), 'Testing, CSRF redirection using GET param http_referer.');
        $this->assertTrue($this->selenium->isTextPresent("Access to your account"), 'Testing, CSRF redirect to $_SERVER[\'HTTP_REFERER\'].');

    }
}
?>