<?php
require_once dirname(__FILE__).'/../../../../oc-load.php';

//require_once('FrontendTest.php');

class OCadmin_csrf extends OCadminTest {

    /*
     * Test redirection when errors with csrf token
     */
    function testCsrfRedirect()
    {
        $url_invalid_request = "?page=ajax&action=enable_category&CSRFName=&CSRFToken=&id=1&enabled=0";
        // Probable invalid request
        $this->selenium->open( osc_admin_base_url(true) . $url_invalid_request );
        $this->selenium->waitForPageToLoad("30000");
        $this->assertTrue($this->selenium->isTextPresent("Probable invalid request"), 'Testing, CSRFName, CSRFToken empty.');
        $this->assertTrue($this->selenium->isTextPresent('{"error":1,"msg":"Probable invalid request."}'), 'no json');

        $url_invalid_token   = "?page=ajax&action=enable_category&CSRFName=foo&CSRFToken=bar&id=1&enabled=0";
        // Invalid CSRF token
        $this->selenium->open( osc_admin_base_url(true) . $url_invalid_token );
        $this->selenium->waitForPageToLoad("30000");
        $this->assertTrue($this->selenium->isTextPresent("Invalid CSRF token"), 'Testing, CSRFName, CSRFToken incorrect.');
        $this->assertTrue($this->selenium->isTextPresent('{"error":1,"msg":"Invalid CSRF token."}'), 'no json');

    }
}
?>