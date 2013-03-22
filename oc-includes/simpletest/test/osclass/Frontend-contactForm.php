<?php


class Frontend_contactForm extends FrontendTest {

    /*
     * Send an email to 'contact' (link in footer)
     */

    function testContact()
    {
        $this->selenium->open( osc_contact_url() );
        $this->selenium->waitForPageToLoad("30000");
        $this->selenium->click("link=Contact");
        $this->selenium->waitForPageToLoad("30000");
        $this->selenium->type("subject", "subject");
        $this->selenium->type("message", "message\nto be\nsent");
        $this->selenium->type("yourName", "Carlos");
        $this->selenium->type("yourEmail", $this->_email);
        $this->selenium->click("xpath=//span/button[text()='Send']");
        $this->selenium->waitForPageToLoad("30000");

        sleep(2);

        $this->assertTrue($this->selenium->isTextPresent("Your email has been sent properly. Thank you for contacting us!"), 'Testing, contact form.');

    }

    /**
     * - invalid email
     * -
     */
    function testContact1()
    {
        $this->selenium->open( osc_contact_url() );
        $this->selenium->waitForPageToLoad("30000");
        $this->selenium->click("link=Contact");
        $this->selenium->waitForPageToLoad("30000");
        $this->selenium->type("subject", "subject");
        $this->selenium->type("message", "message\nto be\nsent");
        $this->selenium->type("yourName", "Carlos");
        $this->selenium->type("yourEmail", 'invalid@mail_foobar');
        $this->selenium->click("xpath=//span/button[text()='Send']");
        sleep(2);
        $this->assertTrue($this->selenium->isTextPresent("Invalid email address"), 'Testing, contact form.');
    }
}
?>
