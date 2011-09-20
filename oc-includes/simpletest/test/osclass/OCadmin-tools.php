<?php
require_once '../../../../oc-load.php';

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
        $this->selenium->click("link=» Import data");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->type("sql", $this->selenium->_path(LIB_PATH."simpletest/test/osclass/test.sql") );
        $this->selenium->click("xpath=//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue($this->selenium->isTextPresent("Import complete"), "Import a sql file.");
        
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Tools");
        $this->selenium->click("link=» Import data");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->type("sql", $this->selenium->_path(LIB_PATH."simpletest/test/osclass/test_restore.sql") );
        $this->selenium->click("xpath=//input[@type='submit']");
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
        $this->selenium->click("link=» Import data");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->type("sql", $this->selenium->_path(LIB_PATH."simpletest/test/osclass/img_test1.gif") );
        $this->selenium->click("xpath=//input[@type='submit']");
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
        $this->selenium->click("link=» Backup data");
        $this->selenium->waitForPageToLoad("30000");
        $this->selenium->click("xpath=//p[2]/button");
        $this->selenium->waitForPageToLoad("3000000");
        $this->assertTrue($this->selenium->isTextPresent("Backup has been done properly"), "Backup database.");
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
        $this->selenium->click("link=» Backup data");
        $this->selenium->waitForPageToLoad("30000");
        $this->selenium->click("xpath=//p[2]/button");
        $this->selenium->waitForPageToLoad("3000000");
        $this->assertTrue($this->selenium->isTextPresent("Backup has been done properly"), "Backup osclass.");
    }
    
    function testRegenerateThumbnails()
    {
        $this->loginWith();
        $this->changeMediaSettings() ;
        $this->regenerateResources();
        $this->restoreMediasettings() ;
    }
    
    // private functions
    
    private function changeMediaSettings()
    {
        $this->dimThumbnail   = Preference::newInstance()->findValueByName('dimThumbnail');
        $this->dimPreview     = Preference::newInstance()->findValueByName('dimPreview');
        $this->dimNormal      = Preference::newInstance()->findValueByName('dimNormal');

        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=General settings");
        $this->selenium->click("link=» Media");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->type('dimThumbnail', '10x10');
        $this->selenium->type('dimPreview'  , '50x50');
        $this->selenium->type('dimNormal'   , '100x100');
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue($this->selenium->isTextPresent("Media config has been updated"), "Update media settings.");
    }

    private function insertItem()
    {

        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Items");
        $this->selenium->click("link=» Add new item");
        $this->selenium->waitForPageToLoad("10000");

        // insert non registered user
        $this->selenium->type("contactName" , "contact name");
        $this->selenium->type("contactEmail", "test@mail.com");

        $this->selenium->select("catId", "label=regexp:\\s*Cars");
        $this->selenium->type("title[en_US]", "title item");
        $this->selenium->type("description[en_US]", "description test description test description test");
        $this->selenium->type("price", "11");
        $this->selenium->select("currency", "label=Euro €");
        $this->selenium->select("countryId", "label=Spain");
        $this->selenium->select("regionId", "label=A Coruña");
        $this->selenium->select("cityId", "label=A Capela");
        $this->selenium->type("address", "address item");

        $this->selenium->type("photos[]", LIB_PATH."simpletest/test/osclass/img_test1.gif");
        $this->selenium->click("link=Add new photo");
        $this->selenium->type("//div[@id='p-0']/input", LIB_PATH."simpletest/test/osclass/img_test2.gif");

        $this->selenium->click("//button[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("A new item has been added"), "Insert a new item.");
    }

    private function deleteItem()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Items");
        $this->selenium->click("link=» Manage items");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'title item')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'title item')]/td/div/div/a[text()='Delete']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("The item has been deleted"), "Delete item.");
    }

    private function regenerateResources()
    {
        $this->insertItem();

        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Tools");
        $this->selenium->click("link=» Regenerate thumbnails");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("3600000");

        $this->assertTrue($this->selenium->isTextPresent("Re-generation complete"), "Re-generate thumbnails.");

        $this->deleteItem();
    }

    private function restoreMediasettings()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=General settings");
        $this->selenium->click("link=» Media");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type('dimThumbnail', $this->dimThumbnail);
        $this->selenium->type('dimPreview'  , $this->dimPreview);
        $this->selenium->type('dimNormal'   , $this->dimNormal);

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("Media config has been updated"), "Update media settings.");
    }
    
    
}
?>
