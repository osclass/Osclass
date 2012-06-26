<?php
require_once dirname(__FILE__).'/../../../../oc-load.php';

//require_once('FrontendTest.php');

class OCadmin_plugins extends OCadminTest {

    private $plugin = "plugins_breadcrumbs_2.0.zip" ;
    
    /*
     * Login oc-admin
     * UPLOAD / INSTALL / CONFIGURE / DISABLE / ENABLE / UNINSTALL PLUGIN
     */
    function testPluginsUpload()
    {
        
        // UPLOAD
        $this->loginWith();
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->click("link=Plugins");
        $this->selenium->click("link=Add new plugin");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->type("package", $this->selenium->_path(LIB_PATH."simpletest/test/osclass/plugins_breadcrumbs_2.0.zip") );
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue($this->selenium->isTextPresent("The plugin has been uploaded correctly"),"Upload plugin $this->plugin");
    }
        
    function testPluginsInstall()
    {
        // INSTALL
        $this->loginWith();
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->click("link=Plugins");
        $this->selenium->click("link=Manage plugins");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("//table/tbody/tr[contains(.,'Bread crumbs')]/td/a[text()='Install']");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue($this->selenium->isTextPresent("Plugin installed"),"Install plugin $this->plugin");
    }
        
    function testPluginsConfigure()
    {
        // CONFIGURE
        $this->loginWith();
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->click("link=Plugins");
        $this->selenium->click("link=Manage plugins");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("//table/tbody/tr[contains(.,'Bread crumbs')]/td/a[text()='Configure']");
        //$this->selenium->click("//table/tbody/tr/td/a[@href[contains(.,'Bread crumbs')] and text()='Configure']");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue($this->selenium->isTextPresent("Breadcrumbs Help"),"Configure plugin $this->plugin");
    }
        
    function testPluginsDisable()
    {
        // DISABLE
        $this->loginWith();
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->click("link=Plugins");
        $this->selenium->click("link=Manage plugins");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("//table/tbody/tr[contains(.,'Bread crumbs')]/td/a[text()='Disable']");
        //$this->selenium->click("//table/tbody/tr/td/a[text()='Disable' and @href[contains(.,'Bread crumbs')]]");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue($this->selenium->isTextPresent("Plugin disabled"),"Disable plugin $this->plugin");
    }
        
    function testPluginsEnable()
    {
        // ENABLE
        $this->loginWith();
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->click("link=Plugins");
        $this->selenium->click("link=Manage plugins");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("//table/tbody/tr[contains(.,'Bread crumbs')]/td/a[text()='Enable']");
        //$this->selenium->click("//table/tbody/tr/td/a[text()='Enable' and @href[contains(.,'Bread crumbs')]]");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue($this->selenium->isTextPresent("Plugin enabled"),"Enable plugin $this->plugin");
    }
        
    function testPluginsUninstall()
    {
        // UNINSTALL
        $this->loginWith();
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->click("link=Plugins");
        $this->selenium->click("link=Manage plugins");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("//table/tbody/tr[contains(.,'Bread crumbs')]/td/a[text()='Uninstall']");
        //$this->selenium->click("//table/tbody/tr/td/a[text()='Uninstall' and @href[contains(.,'Bread crumbs')]]");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue($this->selenium->isTextPresent("Plugin uninstalled"),"Uninstall plugin $this->plugin");
        $this->deletePlugin();
    }
    
    
    
    function testMarket()
    {
        $this->loginWith();
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->click("link=Plugins");
        $this->selenium->click("link=Manage plugins");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("//div/div/div/div/div/ul/li/a[text()='Market']");
        $this->selenium->waitForPageToLoad("10000");
        sleep(10);
        $res = $this->selenium->getXpathCount("//a[text()='Install']");
        $this->assertTrue((10==$res), "Market loaded correctly");
        $this->selenium->click("//a[text()='2']");
        $this->selenium->waitForPageToLoad("10000");
        sleep(10);
        $res = $this->selenium->getXpathCount("//a[text()='Install']");
        $this->assertTrue((10==$res), "Market loaded correctly");
        $this->selenium->click("//a[text()='1']");
        $this->selenium->waitForPageToLoad("10000");
        sleep(10);
        $res = $this->selenium->getXpathCount("//a[text()='Install']");
        $this->assertTrue((10==$res), "Market loaded correctly");
        
        $this->selenium->click("//a[text()='Install'][1]");
        sleep(4);
        
        $this->selenium->click("//button[text()='Continue install']");
        $this->selenium->waitForPageToLoad("30000");
        
        $this->assertTrue($this->selenium->isTextPresent("Everything was OK!"),"Install plugin (market)");
        
    }
    
    
    private function deletePlugin() {
        @chmod(CONTENT_PATH."plugins/breadcrumbs/index.php", 0777);
        @chmod(CONTENT_PATH."plugins/breadcrumbs/", 0777);
        osc_deleteDir(CONTENT_PATH . "plugins/breadcrumbs/");
    }


}
?>
