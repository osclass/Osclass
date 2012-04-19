<?php
require_once dirname(__FILE__).'/../../../../oc-load.php';

//require_once('FrontendTest.php');

class OCadmin_tools extends OCadminTest {
    
    /*
     * Login oc-admin
     * Import sql
     * Remove imported data
     */
    function testImportData()
    {
        $this->loginWith();
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Tools");
        $this->selenium->click("link=Import data");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->type("sql", $this->selenium->_path(LIB_PATH."simpletest/test/osclass/test.sql") );
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue($this->selenium->isTextPresent("Import complete"), "Import a sql file.");
        
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Tools");
        $this->selenium->click("link=Import data");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->type("sql", $this->selenium->_path(LIB_PATH."simpletest/test/osclass/test_restore.sql") );
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue($this->selenium->isTextPresent("Import complete"), "Import a sql file.");
    }
    
    /*
     * Login oc-admin
     * Import bad file. 
     */
    function testImportDataFail()
    {
        $this->loginWith();
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Tools");
        $this->selenium->click("link=Import data");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->type("sql", $this->selenium->_path(LIB_PATH."simpletest/test/osclass/img_test1.gif") );
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue($this->selenium->isTextPresent("There was a problem importing data to the database"), "Import image as sql.");
    }  
    
    /*
     * Login oc-admin
     * Backup database
     */
    function testBackupSql()
    {
        $this->loginWith();
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Tools");
        $this->selenium->click("link=Backup data");
        $this->selenium->waitForPageToLoad("30000");
        $this->selenium->click("//input[@id='backup_sql']");
        $this->selenium->waitForPageToLoad("300000");
        $this->assertTrue($this->selenium->isTextPresent("Backup has been done properly"), "Backup database.");
        // REMOVE FILE
        foreach (glob(osc_base_path() . "OSClass_mysqlbackup.*") as $filename) {
            unlink($filename);
        }
    }
    
    /*
     * Login oc-admin
     * Backup oclass
     */
    function testBackupZip()
    {
        $this->loginWith();
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Tools");
        $this->selenium->click("link=Backup data");
        $this->selenium->waitForPageToLoad("30000");
        $this->selenium->click("//input[@id='backup_zip']");
        $this->selenium->waitForPageToLoad("300000");
        $this->assertTrue($this->selenium->isTextPresent("Archiving successful!"), "Backup osclass.");
        // REMOVE FILE
        foreach (glob(osc_base_path() . "OSClass_backup.*") as $filename) {
            unlink($filename);
        }
    }
    
    
    function testMaintenance()
    {
        $this->loginWith();
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Tools");
        $this->selenium->click("link=Maintenance mode");
        $this->selenium->waitForPageToLoad("30000");
        $maintenance = $this->selenium->isTextPresent("Maintenance mode is: ON");
        if(!$maintenance) {
            $this->selenium->click("//input[@value='Enable maintenance mode']");
            $this->selenium->waitForPageToLoad("300000");
            $this->assertTrue($this->selenium->isTextPresent("Maintenance mode is ON"), "Enabling maintenance mode");
        }
        
        $this->selenium->open( osc_base_url(true) );
        $this->assertTrue($this->selenium->isTextPresent("The website is currently under maintenance mode"), "Check maintenance mode on public website");
        
        $this->loginWith();
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Tools");
        $this->selenium->click("link=Maintenance mode");
        $this->selenium->waitForPageToLoad("30000");
        $this->selenium->click("//input[@value='Disable maintenance mode']");
        $this->selenium->waitForPageToLoad("300000");
        $this->assertTrue($this->selenium->isTextPresent("Maintenance mode is OFF"), "Disabling maintenance mode");
        
    }
    
}
?>
