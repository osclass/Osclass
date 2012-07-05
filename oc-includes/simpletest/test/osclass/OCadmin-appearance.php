<?php

require_once dirname(__FILE__).'/../../../../oc-load.php';

//require_once('FrontendTest.php');

class OCadmin_appearance extends OCadminTest {
    /*
     * Login oc-admin
     * Appearance -> add a new theme
     */
    function testAddTheme() 
    {
        $this->loginWith();

        @chmod(CONTENT_PATH."themes/", 0777);
        $this->selenium->open(osc_admin_base_url(true));
        $this->selenium->click("link=Appearance");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("link=Add new");
        $this->selenium->waitForPageToLoad("10000");

        if ($this->selenium->isTextPresent("chmod a+w ")) {
            $this->assertTrue(FALSE, "NOTICE, You need give permissions to the folder");
        } else {
            $this->selenium->type("package", $this->selenium->_path(LIB_PATH . "simpletest/test/osclass/newcorp.zip"));
            $this->selenium->click("//input[@type='submit']");
            $this->selenium->waitForPageToLoad("30000");
            $this->assertTrue($this->selenium->isTextPresent("The theme has been installed correctly"), "Add a new theme.");
        }
    }

    /*
     * Login oc-admin
     * Appearance -> Manage themes
     * Activate theme and deactivate (activate default theme)
     */
    function testActivateTheme() 
    {
        $this->loginWith();

        $this->selenium->open(osc_admin_base_url(true));
        $this->selenium->click("link=Appearance");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->click("//a[@href[contains(.,'newcorp')] and text()='Activate']");
        $this->selenium->waitForPageToLoad("10000");
        sleep(10);
        $this->assertTrue('Theme activated correctly', "Activate newcorp theme.");

        
        $this->selenium->click("link=Appearance");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->click("//a[@href[contains(.,'modern')] and text()='Activate']");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue('Theme activated correctly', "Activate modern theme.");
    }

    /*
     * Login oc-admin
     * Add/Edit/Delete header & footer widgets
     */

    function testWidgets() 
    {
        $this->loginWith();
        $this->widgetsHeader();
        $this->widgetsFooter();
        $this->editWidgetsHeader();
    }

    /*
     * Private functions
     */

    /*
     * add/delete header widget
     */

    private function widgetsHeader() 
    {
        $this->selenium->open(osc_admin_base_url(true));
        $this->selenium->click("link=Manage widgets");
        $this->selenium->waitForPageToLoad("10000");

        // add header widget
        $this->selenium->click("//a[@id='add_header']");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->type("description", "header1");
        $this->selenium->selectFrame("index=0");
        $this->selenium->type("xpath=//html/body[@id='tinymce']", "New Widget Header");
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");

        $this->assertTrue($this->selenium->isTextPresent("Widget added correctly"), "Add widget header.");
        $this->assertTrue($this->selenium->isTextPresent("header1"), "Check widget header oc-admin.");

        // check if appear at frontend
        $this->selenium->open(osc_base_url(true));
        $this->assertTrue($this->selenium->isTextPresent('New Widget Header'), "Header widget at website.");

        $this->selenium->open(osc_admin_base_url(true));
        $this->selenium->click("link=Manage widgets");
        $this->selenium->waitForPageToLoad("10000");

        // remove widget
        $this->selenium->click("link=Delete");
        $this->selenium->click("xpath=//a[@id='widget-delete-submit']");
        $this->selenium->waitForPageToLoad("30000");
        $this->assertTrue($this->selenium->isTextPresent("Widget removed correctly"), "Delete widget header.");
        $this->assertTrue(!$this->selenium->isTextPresent("header1"), "Check delete widget header.");
    }

    /*
     * add/delete footer widget
     */

    private function widgetsFooter() {
        $this->selenium->open(osc_admin_base_url(true));
        $this->selenium->click("link=Manage widgets");
        $this->selenium->waitForPageToLoad("10000");

        // add categories widget
        $this->selenium->click("//a[@id='footer']");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->type("description", "footer1");
        $this->selenium->selectFrame("index=0");
        $this->selenium->type("xpath=//html/body[@id='tinymce']", "New Widget Footer");
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");

        $this->assertTrue($this->selenium->isTextPresent("Widget added correctly"), "Ad widget footer.");
        $this->assertTrue($this->selenium->isTextPresent("footer1"), "Check add widget footer.");

        // check if appear at frontend
        $this->selenium->open(osc_base_url(true));
        $this->assertTrue($this->selenium->isTextPresent('New Widget Footer'), "Footer widget at website.");

        $this->selenium->open(osc_admin_base_url(true));
        $this->selenium->click("link=Manage widgets");
        $this->selenium->waitForPageToLoad("10000");

        //remove widget
        $this->selenium->click("link=Delete");
        $this->selenium->click("xpath=//a[@id='widget-delete-submit']");
        $this->selenium->waitForPageToLoad("30000");
        $this->assertTrue($this->selenium->isTextPresent("Widget removed correctly"), "Delete widget footer.");
        $this->assertTrue(!$this->selenium->isTextPresent("footer1"), "Check delete widget footer.");
    }

    /*
     * add/edit/delete header widget
     */

    private function editWidgetsHeader() 
    {
        $this->selenium->open(osc_admin_base_url(true));
        $this->selenium->click("link=Manage widgets");
        $this->selenium->waitForPageToLoad("10000");

        // add header widget
        $this->selenium->click("//h4[contains(.,'header')]/a");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->type("description", "header1");
        $this->selenium->selectFrame("index=0");
        $this->selenium->type("xpath=//html/body[@id='tinymce']", "New Widget Header");
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");

        $this->assertTrue($this->selenium->isTextPresent("Widget added correctly"), "Add widget header.");
        $this->assertTrue($this->selenium->isTextPresent("header1"), "Check add widget header.");

        // edit html
        // add header widget
        $this->selenium->click("link=Edit");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->selectFrame("index=0");
        $this->selenium->type("xpath=//html/body[@id='tinymce']", "New Widget Header - NEW CONTENT");
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");

        $this->assertTrue($this->selenium->isTextPresent("Widget updated correctly"), "Update widget header.");

        // check if appear at frontend
        $this->selenium->open(osc_base_url(true));
        $this->assertTrue($this->selenium->isTextPresent('New Widget Header - NEW CONTENT'), "Check header widget at website.");

        $this->selenium->open(osc_admin_base_url(true));
        $this->selenium->click("link=Manage widgets");
        $this->selenium->waitForPageToLoad("10000");

        // remove widget
        $this->selenium->click("link=Delete");
        $this->selenium->click("xpath=//a[@id='widget-delete-submit']");
        $this->selenium->waitForPageToLoad("30000");
        $this->assertTrue($this->selenium->isTextPresent("Widget removed correctly"), "Delete widget header.");
        $this->assertTrue(!$this->selenium->isTextPresent("header1"), "Check delete widget header.");
    }

}

?>
