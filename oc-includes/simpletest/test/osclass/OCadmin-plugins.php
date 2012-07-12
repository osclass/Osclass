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
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("//a[@id='plugins_manage']");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("link=Add plugin");
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
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("//a[@id='plugins_manage']");
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
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("//a[@id='plugins_manage']");
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
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("//a[@id='plugins_manage']");
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
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("//a[@id='plugins_manage']");
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
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("//a[@id='plugins_manage']");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("//table/tbody/tr[contains(.,'Bread crumbs')]/td/a[text()='Uninstall']");
        sleep(2);
        $this->selenium->click("//input[@id='uninstall-submit']");

        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue($this->selenium->isTextPresent("Plugin uninstalled"),"Uninstall plugin $this->plugin");
        $this->deletePlugin();
    }
    
    
    
    function testMarket()
    {
        
        $pdir = array();
        $dir = opendir(CONTENT_PATH . "plugins/");
        while($file = readdir($dir)) {
            $pdir[] = $file;
        }
        
        $this->loginWith();
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("//a[@id='plugins_manage']");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("//div/div/div/div/div/ul/li/a[text()='Market']");

        sleep(10);
        $res = $this->selenium->getXpathCount("//table[@id='market_plugins']/tbody/tr/td");
        $nameplugin = $this->selenium->getText("//table[@id='market_plugins']/tbody/tr/td[1]");
        $this->assertTrue((10==$res), "Market loaded correctly");
        $this->selenium->click("//li/a[text()='2']");

        sleep(10);
        $res = $this->selenium->getXpathCount("//table[@id='market_plugins']/tbody/tr/td");
        $nameplugin2 = $this->selenium->getText("//table[@id='market_plugins']/tbody/tr/td[1]");
        $this->assertTrue((10==$res && $nameplugin!=$nameplugin2), "Market loaded correctly");
        $this->selenium->click("//li/a[text()='1']");

        sleep(10);
        $res = $this->selenium->getXpathCount("//table[@id='market_plugins']/tbody/tr/td");
        $this->assertTrue((10==$res), "Market loaded correctly");
        
        $this->selenium->click("//a[text()='Download plugin'][1]");
        sleep(4);
        
        $this->selenium->click("//button[text()='Continue download']");
        sleep(15);
        
        $this->assertTrue($this->selenium->isTextPresent("Everything was OK!"),"Install plugin (market)");
        $this->selenium->click("//div[@class='osc-modal-content']/p/a[text()='Ok']");
        $this->selenium->waitForPageToLoad("30000");
        
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->waitForPageToLoad("10000");
        
        $this->selenium->click("//a[@id='plugins_manage']");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("//div/div/div/div/div/ul/li/a[text()='Market']");

        sleep(10);
        $res = $this->selenium->getXpathCount("//table[@id='market_plugins']/tbody/tr/td");
        $installedtext = $this->selenium->getText("//table[@id='market_plugins']/tbody/tr/td[1]/div/div[@class='plugin-actions']/a");
        $this->assertTrue(($installedtext=="Already downloaded"),"Install plugin (market)");
        
        // TRY TO DELETE PLUGIN
        $pdir2 = array();
        $pdir2["wathclist"] = "watchlist";
        $dir = opendir(CONTENT_PATH . "plugins/");
        while($file = readdir($dir)) {
            $pdir2[$file] = $file;
        }
        
        foreach($pdir as $pd) {
            unset($pdir2[$pd]);
        }
        unset($pdir);
        
        $plugin = current($pdir2);
        
        @chmod(CONTENT_PATH."plugins/".$plugin."/index.php", 0777);
        @chmod(CONTENT_PATH."plugins/".$plugin."/", 0777);
        osc_deleteDir(CONTENT_PATH . "plugins/".$plugin."/");
        
        $this->selenium->click("//a[@id='settings_general']");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("//a[text()='Check updates']");
        $this->selenium->waitForPageToLoad("10000");
        
        
        $this->selenium->click("//a[@id='plugins_manage']");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("//div/div/div/div/div/ul/li/a[text()='Market']");

        sleep(10);
        $res = $this->selenium->getXpathCount("//table[@id='market_plugins']/tbody/tr/td");
        $installedtext = $this->selenium->getText("//table[@id='market_plugins']/tbody/tr/td[1]/div/div[@class='plugin-actions']/a");
        $this->assertTrue(($installedtext!="Already downloaded"),"CHECK PLUGIN DELETE -THIS IS A FALSE POSITIVE (plugin folder was not deleted-");
        

        
    }
    
    
    private function deletePlugin() {
        @chmod(CONTENT_PATH."plugins/breadcrumbs/index.php", 0777);
        @chmod(CONTENT_PATH."plugins/breadcrumbs/", 0777);
        osc_deleteDir(CONTENT_PATH . "plugins/breadcrumbs/");
    }


}
?>
