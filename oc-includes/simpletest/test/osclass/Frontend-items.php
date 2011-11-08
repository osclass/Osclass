<?php
require_once '../../../../oc-load.php';

//require_once('FrontendTest.php');

class Frontend_items extends FrontendTest {
    
    /*
     * Insert items, no user.
     *  - No validation, no user can post, no wait time
     *  - With validation.
     *  - Can't post items
     */
    function testItems_noUser()
    {
        include 'itemData.php';
        $item = $aData[0];
        $uSettings = new utilSettings();
        $items_wait_time                  = $uSettings->set_items_wait_time(0);
        $set_selectable_parent_categories = $uSettings->set_selectable_parent_categories(1);
        $bool_reg_user_post               = $uSettings->set_reg_user_post(0);
        $bool_enabled_user_validation     = $uSettings->set_moderate_items(-1);
        
        $this->insertItem($item['catId'], $item['title'], 
                                $item['description'], $item['price'],
                                $item['regionId'], $item['cityId'], 
                                $item['photo'], $item['contactName'], 
                                $this->_email);
        $this->assertTrue($this->selenium->isTextPresent("Your item has been published"),"Items, insert item, no user, no validation.") ;
        
        $uSettings->set_moderate_items(111);
        $this->insertItem($item['catId'], $item['title'], 
                                $item['description'], $item['price'],
                                $item['regionId'], $item['cityId'], 
                                $item['photo'], $item['contactName'], 
                                $this->_email);
        $this->assertTrue($this->selenium->isTextPresent("Check your inbox to verify your email address"),"Items, insert item, no user, with validation.") ;
        
        $uSettings->set_reg_user_post(1);
        $this->selenium->open( osc_base_url(true) );
        $this->selenium->click("link=Publish your ad for free");
        $this->selenium->waitForPageToLoad("30000");
        $this->assertTrue($this->selenium->isTextPresent("Only registered users are allowed to post items"), "Items, insert item, no user, can't publish");
        
        $uSettings->set_items_wait_time($items_wait_time);
        $uSettings->set_selectable_parent_categories($set_selectable_parent_categories);
        $uSettings->set_reg_user_post($bool_reg_user_post);
        $uSettings->set_moderate_items($bool_enabled_user_validation);
        
        unset($uSettings);
        /*
         * Remove all items inserted previously
         */
        $aItem = Item::newInstance()->listAll('s_contact_email = '.$this->_email." AND fk_i_user IS NULL");
        foreach($aItem as $item){
            $url = osc_item_delete_url( $item['s_secret'] , $item['pk_i_id'] );
            $this->selenium->open( $url );
            $this->assertTrue($this->selenium->isTextPresent("Your item has been deleted"), "Delete item.");
        }
    }
    
    /*
     * Insert items, user.
     * register
     * login
     * add item, without validation
     * add item, with validation
     */
    function testItems_User()
    {
        include 'itemData.php';
        $item = $aData[0];
        
        $uSettings = new utilSettings();
        $old_enabled_users           = $uSettings->set_enabled_users(1);
        $old_enabled_users_registration = $uSettings->set_enabled_user_registration(1);
        $old_enabled_user_validation = $uSettings->set_enabled_user_validation(0);
        
        $this->doRegisterUser();
        $this->loginWith();
        
        
        $old_logged_user_item_validation = $uSettings->set_logged_user_item_validation(1);
        $this->insertItem($item['catId'], $item['title'], 
                                $item['description'], $item['price'],
                                $item['regionId'], $item['cityId'], 
                                $item['photo'], $item['contactName'], 
                                $this->_email);
        $this->assertTrue($this->selenium->isTextPresent("Your item has been published"),"insert ad error ") ;
        
        $uSettings->set_logged_user_item_validation(0);
        $this->insertItem($item['catId'], $item['title'], 
                                $item['description'], $item['price'],
                                $item['regionId'], $item['cityId'], 
                                $item['photo'], $item['contactName'], 
                                $this->_email);
        $this->assertTrue($this->selenium->isTextPresent("Check your inbox to verify your email address"),"Need validation but message don't appear")   ;
        
        $uSettings->set_logged_user_item_validation( $old_logged_user_item_validation );
        $uSettings->set_enabled_users($old_enabled_users);
        $uSettings->set_enabled_user_registration($old_enabled_users_registration);
        $uSettings->set_enabled_user_validation($old_enabled_user_validation);
        
        unset($uSettings);
    }
    
    /*
     * Try to edit a item with bad id_item
     */
    function testEditUserItemBadId()
    {
        $this->selenium->open( osc_base_url(true) . "?page=item&action=item_edit&id=9999" );
        $this->assertTrue($this->selenium->isTextPresent("Sorry, we don't have any items with that ID"));
    }

    /*
     * 
     */
    function testActivate() // Activate
    {
        $this->loginWith();
        
        $this->selenium->open( osc_base_url(true) );
        $this->selenium->click("link=My account");
        $this->selenium->waitForPageToLoad("30000");
        $this->selenium->click("xpath=//ul/li/a[text()='Manage your items']");
        $this->selenium->waitForPageToLoad("30000");
        $this->selenium->click("xpath=//div[@class='item']/p/a[text()='Activate']");
        $this->selenium->waitForPageToLoad("30000");
        $this->assertTrue($this->selenium->isTextPresent("The item has been validated"), "Items, validate user item.");
    }

    /*
     * Register user
     * Login
     * Edit first item, with validation
     * Edit first item, without validation
     */
    function testEditItem()
    {
        $this->loginWith();
        
        $uSettings = new utilSettings();
        $old_moderate_items = $uSettings->set_moderate_items(0);
        
        $this->selenium->open( osc_base_url(true) );
        $this->selenium->click("link=My account");
        $this->selenium->waitForPageToLoad("30000");
        $this->selenium->click("xpath=//ul/li/a[text()='Manage your items']");
        $this->selenium->waitForPageToLoad("30000");
        // edit first item
        $this->selenium->click("xpath=//div[@class='item'][1]/p[@class='options']/strong/a[text()='Edit']");
        $this->selenium->waitForPageToLoad("30000");
        $this->selenium->select("catId", "label=regexp:\\s*Car Parts");

        $this->selenium->type("title[en_US]", "New title new item");
        $this->selenium->type("description[en_US]", "New description new item new item new item");
        $this->selenium->type("price", "222");
        $this->selenium->select("currency", "label=Euro €");
        $this->selenium->type('id=region', 'Barcelona');
        $this->selenium->click('id=ui-active-menuitem');
        $this->selenium->type('id=city', 'Sabadell');
        $this->selenium->click('id=ui-active-menuitem');
        $this->selenium->type("cityArea", "New my area");
        $this->selenium->type("address", "New my address");
        $this->selenium->click("//button[@type='submit']");
        $this->selenium->waitForPageToLoad("3000");
        
        $this->assertTrue(  $this->selenium->isTextPresent("Great! We've just updated your item"), 'Items, edit first item, with validation.' );
        
        $old_moderate_items = $uSettings->set_moderate_items(-1);
        $this->selenium->open( osc_base_url(true) );
        $this->selenium->click("link=My account");
        $this->selenium->waitForPageToLoad("30000");
        $this->selenium->click("xpath=//ul/li/a[text()='Manage your items']");
        $this->selenium->waitForPageToLoad("30000");
        // edit first item
        $this->selenium->click("xpath=//div[@class='item'][1]/p[@class='options']/strong/a[text()='Edit']");
        $this->selenium->waitForPageToLoad("30000");
        $this->selenium->select("catId", "label=regexp:\\s*Car Parts");

        $this->selenium->type("title[en_US]", "New title new item NEW ");
        $this->selenium->type("description[en_US]", "New description new item new item new item NEW ");
        $this->selenium->type("price", "666");
        $this->selenium->select("currency", "label=Euro €");
        $this->selenium->type('id=region', 'Barcelona');
        $this->selenium->click('id=ui-active-menuitem');
        $this->selenium->type('id=city', 'Sabadell');
        $this->selenium->click('id=ui-active-menuitem');
        $this->selenium->type("cityArea", "New my area");
        $this->selenium->type("address", "New my address");
        $this->selenium->click("//button[@type='submit']");
        $this->selenium->waitForPageToLoad("3000");
        
        $this->assertTrue(  $this->selenium->isTextPresent("Great! We've just updated your item") ,"Items, edit first item, without validation.");
        
        $uSettings->set_moderate_items($old_moderate_items);
        
        unset($uSettings);
    }
    
    function testDeleteItem()
    {
        $this->loginWith();
        
        $this->selenium->open( osc_base_url(true) );
        $this->selenium->click("link=My account");
        $this->selenium->waitForPageToLoad("30000");

        $this->selenium->click("xpath=//ul/li/a[text()='Manage your items']");
        $this->selenium->waitForPageToLoad("30000");

        $numItems = $this->selenium->getXpathCount("//div[@class='item']/p/a[text()='Delete']");

        while($numItems > 0) {
            // delete first item
            $this->selenium->click("xpath=//div[@class='item']/p/a[text()='Delete']");
            $this->selenium->waitForPageToLoad("30000");
            $this->assertTrue($this->selenium->isTextPresent("Your item has been deleted"), "Can't delete item. ERROR ");
            
            $numItems = $this->selenium->getXpathCount("//div[@class='item']/p/a[text()='Delete']");
            
            $this->selenium->open( osc_base_url(true) );
            $this->selenium->click("link=My account");
            $this->selenium->waitForPageToLoad("30000");

            $this->selenium->click("xpath=//ul/li/a[text()='Manage your items']");
            $this->selenium->waitForPageToLoad("30000");
        }

        $this->removeUserByMail();

    }

}
?>
