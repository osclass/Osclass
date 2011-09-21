<?php
require_once '../../../../oc-load.php';

//require_once('FrontendTest.php');

class OCadmin_plugins extends OCadminTest {
    
    /*
     * Login oc-admin
     * UPLOAD / INSTALL / CONFIGURE / DISABLE / ENABLE / UNINSTALL PLUGIN
     */
    function testPluginsUpload()
    {
        $plugin = "plugins_breadcrumbs_2.0.zip" ;
        
        $this->loginWith();
        // UPLOAD
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->click("link=Plugins");
        $this->selenium->click("link=» Add new plugin");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->type("package", $this->selenium->_path(LIB_PATH."simpletest/test/osclass/plugins_breadcrumbs_2.0.zip") );
        $this->selenium->click("//form/input[@id='button_save']");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue($this->selenium->isTextPresent("The plugin has been uploaded correctly"),"Upload plugin $plugin");
        // INSTALL
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->click("link=Plugins");
        $this->selenium->click("link=» Manage plugins");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("//table[@id='datatables_list']/tbody/tr/td/a[@href[contains(.,'breadcrumbs')]]");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue($this->selenium->isTextPresent("Plugin installed"),"Install plugin $plugin");
        // CONFIGURE
        $this->selenium->click("//table[@id='datatables_list']/tbody/tr/td/a[text()='Configure' and @href[contains(.,'breadcrumbs')]]");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue($this->selenium->isTextPresent("Breadcrumbs Help"),"Configure plugin $plugin");
        // DISABLE
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->click("link=Plugins");
        $this->selenium->click("link=» Manage plugins");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("//table[@id='datatables_list']/tbody/tr/td/a[text()='Disable' and @href[contains(.,'breadcrumbs')]]");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue($this->selenium->isTextPresent("Plugin disabled"),"Disable plugin $plugin");
        // ENABLE
        $this->selenium->click("//table[@id='datatables_list']/tbody/tr/td/a[text()='Enable' and @href[contains(.,'breadcrumbs')]]");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue($this->selenium->isTextPresent("Plugin enabled"),"Enable plugin $plugin");
        // UNINSTALL
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->click("link=Plugins");
        $this->selenium->click("link=» Manage plugins");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("//table[@id='datatables_list']/tbody/tr/td/a[text()='Uninstall' and @href[contains(.,'breadcrumbs')]]");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue($this->selenium->isTextPresent("Plugin uninstalled"),"Uninstall plugin $plugin");
    }

}
?>
