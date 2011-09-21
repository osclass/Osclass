<?php

require_once '../../../../oc-load.php';

//require_once('FrontendTest.php');

class OCadmin_appearance extends OCadminTest {
    /*
     * Login oc-admin
     * Appearance -> add a new theme
     */

    function testAddTheme() {
        $this->loginWith();

        $this->selenium->open(osc_admin_base_url(true));
        $this->selenium->click("link=Appearance");
        $this->selenium->click("link=» Add a new theme");
        $this->selenium->waitForPageToLoad("10000");

        if ($this->selenium->isTextPresent("chmod a+w ")) {
            $this->assertTrue(FALSE, "NOTICE, You need give permissions to the folder");
        } else {
            $this->selenium->type("package", $this->selenium->_path(LIB_PATH . "simpletest/test/osclass/newcorp.zip"));
            $this->selenium->click("button_save");
            $this->selenium->waitForPageToLoad("30000");
            $this->assertTrue($this->selenium->isTextPresent("The theme has been installed correctly"), "Add a new theme.");
        }
    }

    /*
     * Login oc-admin
     * Appearance -> Manage themes
     * Activate theme and deactivate (activate default theme)
     */

    function testActivateTheme() {
        $this->loginWith();

        $this->selenium->open(osc_admin_base_url(true));
        $this->selenium->click("link=Appearance");
        $this->selenium->click("link=» Manage themes");
        $this->selenium->waitForPageToLoad("10000");

        if ($this->selenium->isTextPresent("chmod a+w ")) {
            $this->assertTrue(FALSE, "NOTICE, You need give permissions to the folder");
        } else {
            $this->selenium->click("link=Activate");
            $this->selenium->waitForPageToLoad("30000");
            $text_element = $this->selenium->getText("xpath=//div[@id='current_theme_info']");
            if (preg_match('/NewCorp Theme/', $text_element)) {
                $this->assertTrue(TRUE, "Activate new theme.");
            } else {
                $this->assertTrue(FALSE, "Activate new theme.");
            }
            // activate default theme again
            $this->selenium->click("link=Activate");
            $this->selenium->waitForPageToLoad("30000");
            $text_element = $this->selenium->getText("xpath=//div[@id='current_theme_info']");
            if (preg_match('/Modern Theme/', $text_element)) {
                $this->assertTrue(TRUE, "Activate default theme.");
            } else {
                $this->assertTrue(FALSE, "Activate default theme.");
            }
        }
    }

    /*
     * Login oc-admin
     * Add/Edit/Delete header & footer widgets
     */

    function testWidgets() {
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

    private function widgetsHeader() {
        $this->selenium->open(osc_admin_base_url(true));
        $this->selenium->click("link=Appearance");
        $this->selenium->click("link=» Add or remove widgets");
        $this->selenium->waitForPageToLoad("10000");

        // add header widget
        $this->selenium->click("xpath=//div[@id='settings_form']/div/div[1]/div/a");
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
        $this->selenium->click("link=Appearance");
        $this->selenium->click("link=» Add or remove widgets");
        $this->selenium->waitForPageToLoad("10000");

        // remove widget
        $this->selenium->click("link=Delete");
        $this->selenium->waitForPageToLoad("30000");
        $this->assertTrue($this->selenium->isTextPresent("Widget removed correctly"), "Delete widget header.");
        $this->assertTrue(!$this->selenium->isTextPresent("header1"), "Check delete widget header.");
    }

    /*
     * add/delete footer widget
     */

    private function widgetsFooter() {
        $this->selenium->open(osc_admin_base_url(true));
        $this->selenium->click("link=Appearance");
        $this->selenium->click("link=» Add or remove widgets");
        $this->selenium->waitForPageToLoad("10000");

        // add categories widget
        $this->selenium->click("xpath=//div[@id='settings_form']/div/div[2]/div/a");
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
        $this->selenium->click("link=Appearance");
        $this->selenium->click("link=» Add or remove widgets");
        $this->selenium->waitForPageToLoad("10000");

        //remove widget
        $this->selenium->click("link=Delete");
        $this->selenium->waitForPageToLoad("30000");
        $this->assertTrue($this->selenium->isTextPresent("Widget removed correctly"), "Delete widget footer.");
        $this->assertTrue(!$this->selenium->isTextPresent("footer1"), "Check delete widget footer.");
    }

    /*
     * add/edit/delete header widget
     */

    private function editWidgetsHeader() {
        $this->selenium->open(osc_admin_base_url(true));
        $this->selenium->click("link=Appearance");
        $this->selenium->click("link=» Add or remove widgets");
        $this->selenium->waitForPageToLoad("10000");

        // add header widget
        $this->selenium->click("xpath=//div[@id='settings_form']/div/div[1]/div/a");
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
        $this->selenium->click("link=Appearance");
        $this->selenium->click("link=» Add or remove widgets");
        $this->selenium->waitForPageToLoad("10000");

        // remove widget
        $this->selenium->click("link=Delete");
        $this->selenium->waitForPageToLoad("30000");
        $this->assertTrue($this->selenium->isTextPresent("Widget removed correctly"), "Delete widget header.");
        $this->assertTrue(!$this->selenium->isTextPresent("header1"), "Check delete widget header.");
    }

}

?>
