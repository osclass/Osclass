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
        $this->assertTrue($this->selenium->isTextPresent("Backup has completed successfully"), "Backup database.");
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
    
    
    function testLocations()
    {
        
        $this->loginWith();
        $this->removeLoadedItems(false);
        $this->loadItems();
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("//a[@id='tools_location']");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("300000");
        $complete = 0;
        $max_time_limit = 0; // Add a time limit of 10 minutes to execute this while, in other case is infinite (if the ajax to get the percent is wrong!)!
        while($complete!='100' && $max_time_limit<40) {
            sleep(15);
            $complete = $this->selenium->getText("//div/p/span[@id='percent']");
            $max_time_limit++;
        }
        $this->assertTrue($this->selenium->isTextPresent("100 % Complete"), "Re-calculating location stats");
        
        $countries = CountryStats::newInstance()->listCountries(">=");
        foreach($countries as $c) {
            if($c['country_code']=="ES") {
                $this->assertTrue(($c['items']==14), "Spain items (should be 14, ".$c['items']." found)");
            } else {
                $this->assertTrue(($c['items']==0), $c['country_name']." items (should be 0, ".$c['items']." found)");
            }
        }

        $regions = RegionStats::newInstance()->listRegions('%%%%', ">=");
        foreach($regions as $r) {
            if($r['region_name']=="Barcelona") {
                $this->assertTrue(($r['items']==8), "Barcelona items (should be 8, ".$r['items']." found)");
            } else if($r['region_name']=="Madrid") {
                $this->assertTrue(($r['items']==3), "Madrid items (should be 3, ".$r['items']." found)");
            } else if($r['region_name']=="Alicante") {
                $this->assertTrue(($r['items']==3), "Alicante items (should be 3, ".$r['items']." found)");
            } else {
                $this->assertTrue(($r['items']==0), $r['region_name']." items (should be 0, ".$r['items']." found)");
            }
        }
        
        $cities = CityStats::newInstance()->listCities(null, ">=");
        foreach($cities as $c) {
            if($c['city_name']=="Terrassa") {
                $this->assertTrue(($c['items']==4), "Terrassa items (should be 4, ".$c['items']." found)");
            } else if($c['city_name']=="Balsareny") {
                $this->assertTrue(($c['items']==4), "Balsareny items (should be 4, ".$c['items']." found)");
            } else if($c['city_name']=="Alameda del Valle") {
                $this->assertTrue(($c['items']==3), "Alameda del Valle items (should be 3, ".$c['items']." found)");
            } else if($c['city_name']=="Agres") {
                $this->assertTrue(($c['items']==3), "Agres items (should be 3, ".$c['items']." found)");
            } else {
                $this->assertTrue(($c['items']==0), $c['city_name']." items (should be 0, ".$c['items']." found)");
            }
        }
        
        $this->removeLoadedItems();
        
    }
    
    
    /*
     * Test if the http_referer functionality is working on admin
     */
    function testHTTPReferer()
    {
        $this->HTTPReferer( osc_admin_base_url(true)."?page=items" , "Manage listings");
        $this->HTTPReferer( osc_admin_base_url(true)."?page=stats&action=comments" , "Comments Statistics");
    }
    
    function HTTPReferer($url, $text) 
    {
        
        // CORRECT LOGIN
        $this->logout();
        $this->selenium->open( $url );
        $this->selenium->waitForPageToLoad(10000);
        $this->selenium->type('user', $this->_adminUser);
        $this->selenium->type('password', $this->_password);
        $this->selenium->click('submit');
        $this->selenium->waitForPageToLoad(1000);
        $this->assertTrue($this->selenium->isTextPresent($text), "HTTP REFERER CORRECT");
        
        // INCORRECT LOGIN (ONE TIME)
        $this->logout();
        $this->selenium->open( $url );
        $this->selenium->waitForPageToLoad(10000);
        $this->selenium->type('user', $this->_adminUser);
        $this->selenium->type('password', $this->_password."ax");
        $this->selenium->click('submit');
        $this->selenium->waitForPageToLoad(1000);
        $this->assertTrue($this->selenium->isTextPresent("Sorry, incorrect password"), "HTTP REFERER INCORRECT ONE TIME");
        $this->selenium->type('user', $this->_adminUser);
        $this->selenium->type('password', $this->_password);
        $this->selenium->click('submit');
        $this->selenium->waitForPageToLoad(1000);
        $this->assertTrue($this->selenium->isTextPresent($text), "HTTP REFERER INCORRECT ONE TIME");
        
        // INCORRECT LOGIN (TWICE)
        $this->logout();
        $this->selenium->open( $url );
        $this->selenium->waitForPageToLoad(10000);
        $this->selenium->type('user', $this->_adminUser);
        $this->selenium->type('password', $this->_password."ax");
        $this->selenium->click('submit');
        $this->selenium->waitForPageToLoad(1000);
        $this->assertTrue($this->selenium->isTextPresent("Sorry, incorrect password"), "HTTP REFERER INCORRECT TWICE");
        $this->selenium->type('user', $this->_adminUser);
        $this->selenium->type('password', $this->_password."ab");
        $this->selenium->click('submit');
        $this->selenium->waitForPageToLoad(1000);
        $this->assertTrue($this->selenium->isTextPresent("Sorry, incorrect password"), "HTTP REFERER INCORRECT TWICE");
        $this->selenium->type('user', $this->_adminUser);
        $this->selenium->type('password', $this->_password);
        $this->selenium->click('submit');
        $this->selenium->waitForPageToLoad(1000);
        $this->assertTrue($this->selenium->isTextPresent($text), "HTTP REFERER INCORRECT TWICE");

    }
    
    
    /*
     * Load items for test propouse.
     */
    function loadItems()
    {
        // insert items for test
        require 'ItemData.php';
        $uSettings = new utilSettings();
        $old_reg_user_port          = $uSettings->set_reg_user_post(0);
        $old_items_wait_time        = $uSettings->set_items_wait_time(0);
        $old_enabled_recaptcha_items = $uSettings->set_enabled_recaptcha_items(0);
        $old_moderate_items         = $uSettings->set_moderate_items(-1);
        
        foreach($aData as $item){
            $this->insertItem(  $item['catId'], $item['title'], 
                                $item['description'], $item['price'],
                                $item['regionId'], $item['cityId'],  $item['cityArea'],
                                $item['photo'], $item['contactName'], 
                                $this->_email);
        }
        
        $uSettings->set_reg_user_post( $old_reg_user_port );
        $uSettings->set_items_wait_time( $old_items_wait_time );
        $uSettings->set_enabled_recaptcha_items( $old_enabled_recaptcha_items );
        $uSettings->set_moderate_items( $old_moderate_items );
        
        unset($uSettings);
    }
    
    function removeLoadedItems($check = true)
    {
            $this->selenium->open( osc_admin_base_url(true) );
            $this->selenium->click("//a[@id='items_manage']");
            $this->selenium->waitForPageToLoad("10000");
            
            $this->selenium->click("check_all");
            sleep(4);
            $this->selenium->select("//select[@id='bulk_actions']", "label=regexp:\\s*Delete");
            sleep(4);
            $this->selenium->click("//input[@id='bulk_apply']");
            sleep(4);
            $this->selenium->click("//a[@id='bulk-actions-submit']");
            $this->selenium->waitForPageToLoad("60000");

            if($check) {
                $this->assertTrue($this->selenium->isTextPresent("listings have been deleted"), "Can't delete item. ERROR");
            }
    }
    
    public function insertItem($cat, $title, $description, $price, $regionId, $cityId, $cityArea, $bPhotos, $user, $email , $logged = 0)
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("//a[@id='items_manage']");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("link=Add new");
        $this->selenium->waitForPageToLoad("10000");

        // insert non registered user
        $this->selenium->type("contactName" , $user);
        $this->selenium->type("contactEmail", $email);

        $this->selenium->select("catId", "label=regexp:\\s*".$cat);
        $this->selenium->type("title[en_US]", $title);
        $this->selenium->type("description[en_US]", $description);
        $this->selenium->type("price", $price);
        $this->selenium->select("currency", "label=Euro €");

        $this->selenium->select("countryId", "label=Spain");

        $this->selenium->type('id=region', $regionId);
        $this->selenium->click('id=ui-active-menuitem');

        $this->selenium->type('id=city', $cityId);
        $this->selenium->click('id=ui-active-menuitem');

        $this->selenium->type("address", $cityArea);

        if( $bPhotos ){
            $this->selenium->type("photos[]", LIB_PATH."simpletest/test/osclass/img_test1.gif");
            sleep(0.5);
            $this->selenium->type("//div[@id='p-0']/input", LIB_PATH."simpletest/test/osclass/img_test2.gif");
        }
        
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");
        
        $this->assertTrue($this->selenium->isTextPresent("A new listing has been added"), "Can't insert a new item. ERROR");
    }    
    
}
?>
