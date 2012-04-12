<?php
require_once dirname(__FILE__).'/../../../../oc-load.php';

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
        require 'ItemData.php';
        $item = $aData[0];
        $uSettings = new utilSettings();
        $items_wait_time                  = $uSettings->set_items_wait_time(0);
        $set_selectable_parent_categories = $uSettings->set_selectable_parent_categories(1);
        $bool_reg_user_post               = $uSettings->set_reg_user_post(0);
        $bool_enabled_user_validation     = $uSettings->set_moderate_items(-1);
        
        $this->insertItem($item['catId'], $item['title'], 
                                $item['description'], $item['price'],
                                $item['regionId'], $item['cityId'], $item['cityArea'],
                                $item['photo'], $item['contactName'], 
                                $this->_email);
        $this->assertTrue($this->selenium->isTextPresent("Your item has been published"),"Items, insert item, no user, no validation.") ;
        
        $uSettings->set_moderate_items(111);
        $this->insertItem($item['catId'], $item['title'], 
                                $item['description'], $item['price'],
                                $item['regionId'], $item['cityId'], $item['cityArea'],
                                $item['photo'], $item['contactName'], 
                                $this->_email);
        $this->assertTrue($this->selenium->isTextPresent("Check your inbox to verify your email address"),"Items, insert item, no user, with validation.") ;
        
        $uSettings->set_reg_user_post(1);
        $this->selenium->open( osc_base_url() );
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
        require dirname(__FILE__).'/ItemData.php';
        $item = $aData[0];
        
        $uSettings = new utilSettings();
        $old_enabled_users              = $uSettings->set_enabled_users(1);
        $old_enabled_users_registration = $uSettings->set_enabled_user_registration(1);
        $old_enabled_user_validation    = $uSettings->set_enabled_user_validation(0);
        
        $this->doRegisterUser();
        $this->loginWith();
        
        
        $old_logged_user_item_validation = $uSettings->set_logged_user_item_validation(1);
        $this->insertItem($item['catId'], $item['title'], 
                                $item['description'], $item['price'],
                                $item['regionId'], $item['cityId'], $item['cityArea'],
                                $item['photo'], $item['contactName'], 
                                $this->_email);
        $this->assertTrue($this->selenium->isTextPresent("Your item has been published"),"insert ad error ") ;
        
        $uSettings->set_logged_user_item_validation(0);
        $this->insertItem($item['catId'], $item['title'], 
                                $item['description'], $item['price'],
                                $item['regionId'], $item['cityId'], $item['cityArea'],
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
        $this->selenium->open( osc_item_edit_url('', '9999') );
        $this->assertTrue($this->selenium->isTextPresent("Sorry, we don't have any items with that ID"));
    }
    
    /*
     * Add a item, and try to edit logged as user
     */
    function testEditUserItem1()
    {
        $this->logout();
        // create new item
        require dirname(__FILE__).'/ItemData.php';
        $item = $aData[2];
        
        $uSettings = new utilSettings();
        $items_wait_time                  = $uSettings->set_items_wait_time(0);
        $set_selectable_parent_categories = $uSettings->set_selectable_parent_categories(1);
        $bool_reg_user_post               = $uSettings->set_reg_user_post(0);
        $bool_enabled_user_validation     = $uSettings->set_moderate_items(-1);
        
        $old_logged_user_item_validation = $uSettings->set_logged_user_item_validation(1);
        $this->insertItem($item['catId'], $item['title'], 
                                $item['description'], $item['price'],
                                $item['regionId'], $item['cityId'], $item['cityArea'],
                                $item['photo'], $item['contactName'], 
                                $this->_email);
        sleep(1);
        $this->assertTrue($this->selenium->isTextPresent("Your item has been published"),"Items, insert item, no user, no validation.") ;
        
        $uSettings->set_items_wait_time($items_wait_time);
        $uSettings->set_selectable_parent_categories($set_selectable_parent_categories);
        $uSettings->set_reg_user_post($bool_reg_user_post);
        $uSettings->set_moderate_items($bool_enabled_user_validation);
        $itemId = $this->_lastItemId();
        
        // login and try to edit 
        $this->loginWith();
        
        $this->selenium->open(osc_item_edit_url('', $itemId));
        $this->assertTrue($this->selenium->isTextPresent(""),"Sorry, we don't have any items with that ID") ;
        
        // remove item
        Item::newInstance()->deleteByPrimaryKey($itemId);
    }

    /*
     * Activate item via 'My account' as registered user
     */
    function testActivate() // Activate
    {
        $this->loginWith();
        
        $this->selenium->open( osc_base_url() );
        $this->selenium->click("link=My account");
        $this->selenium->waitForPageToLoad("30000");
        $this->selenium->click("xpath=//ul/li/a[text()='Manage your items']");
        $this->selenium->waitForPageToLoad("30000");
        $this->selenium->click("xpath=//div[@class='item']/p/a[text()='Activate']");
        $this->selenium->waitForPageToLoad("30000");
        $this->assertTrue($this->selenium->isTextPresent("The item has been validated"), "Items, validate user item.");
    }
    
    /*
     * Try to activate a item from other user, registered user
     * Try to activate a item from other user, no registered user
     * Try to activate a item, item user / with secret
     */
    function testActivate1()
    {
        $uSettings = new utilSettings();
        $old_enabled_users              = $uSettings->set_enabled_users(1);
        $old_enabled_users_registration = $uSettings->set_enabled_user_registration(1);
        $old_enabled_user_validation    = $uSettings->set_enabled_user_validation(0);
        $this->doRegisterUser();
        $uSettings->set_logged_user_item_validation( $old_logged_user_item_validation );
        $uSettings->set_enabled_users($old_enabled_users);
        $uSettings->set_enabled_user_registration($old_enabled_users_registration);
         
        $itemId = $this->_insertItemToValidate();
        
        // 1
        $this->loginWith();
        $url = osc_item_activate_url('', $itemId);
        $this->selenium->open($url);
        sleep(1);
        $this->assertTrue($this->selenium->isTextPresent("This item doesn't exist"), "Items, validate item from other user.");
        // 2 
        $this->logout();
        $url = osc_item_activate_url('', $itemId);
        $this->selenium->open($url);
        sleep(1);
        $this->assertTrue($this->selenium->isTextPresent("This item doesn't exist"), "Items, validate item from no user.");
        // 3 
        $item = Item::newInstance()->findByPrimaryKey($itemId);
        $url = osc_item_activate_url($item['s_secret'], $itemId);
        $this->selenium->open($url);
        sleep(1);
        $this->assertTrue($this->selenium->isTextPresent("The item has been validated"), "Items, validate item. (direct url)");
        // remove all items 
        Item::newInstance()->deleteByPrimaryKey($itemId);
        
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
        
        $this->selenium->open( osc_base_url() );
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
        
        $this->assertTrue( $this->selenium->isTextPresent("Great! We've just updated your item") ,"Items, edit first item, without validation." );
        
        $uSettings->set_moderate_items($old_moderate_items);
        
        unset($uSettings);
    }
    
    /*
     * Try to remove an item which not belongs to user 
     */
    function testDeleteItemOtherUser()
    {
        $this->logout();
        $itemId = $this->_lastItemId();
        $url = osc_item_delete_url('', $itemId);
        
        $this->selenium->open($url);
        $this->assertTrue( $this->selenium->isTextPresent("The item you are trying to delete couldn't be deleted") ,"Items, delete item without secret." );
    }
    
    function testDeleteItem()
    {
        $this->loginWith();
        
        $this->selenium->open( osc_base_url() );
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
            
            $this->selenium->open( osc_base_url() );
            $this->selenium->click("link=My account");
            $this->selenium->waitForPageToLoad("30000");

            $this->selenium->click("xpath=//ul/li/a[text()='Manage your items']");
            $this->selenium->waitForPageToLoad("30000");
        }
        $this->removeUserByMail();
    }
    
    /*
     * private function, insert item which need validation
     */
    function _insertItemToValidate()
    {
        $this->logout();
        require dirname(__FILE__).'/ItemData.php';
        $item = $aData[3];
        
        $uSettings = new utilSettings();
        $items_wait_time                  = $uSettings->set_items_wait_time(0);
        $set_selectable_parent_categories = $uSettings->set_selectable_parent_categories(1);
        $bool_reg_user_post               = $uSettings->set_reg_user_post(0);
        $bool_enabled_user_validation     = $uSettings->set_moderate_items(2);
        
        $old_logged_user_item_validation = $uSettings->set_logged_user_item_validation(1);
        $this->insertItem($item['catId'], $item['title'], 
                                $item['description'], $item['price'],
                                $item['regionId'], $item['cityId'], $item['cityArea'],
                                $item['photo'], $item['contactName'], 
                                $this->_email);
        sleep(1);
        $this->assertTrue($this->selenium->isTextPresent("Check your inbox to verify your email address"),"Items, insert item, no user, with validation.") ;
        
        $uSettings->set_items_wait_time($items_wait_time);
        $uSettings->set_selectable_parent_categories($set_selectable_parent_categories);
        $uSettings->set_reg_user_post($bool_reg_user_post);
        $uSettings->set_moderate_items($bool_enabled_user_validation);
        
        return $this->_lastItemId();
    }
}
?>