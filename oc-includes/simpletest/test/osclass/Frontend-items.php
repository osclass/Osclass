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
        $this->assertTrue($this->selenium->isTextPresent("Your listing has been published"),"Items, insert item, no user, no validation.") ;
        
        $uSettings->set_moderate_items(111);
        $this->insertItem($item['catId'], $item['title'], 
                                $item['description'], $item['price'],
                                $item['regionId'], $item['cityId'], $item['cityArea'],
                                $item['photo'], $item['contactName'], 
                                $this->_email);
        $this->assertTrue($this->selenium->isTextPresent("Check your inbox"),"Items, insert item, no user, with validation.") ;
        
        $uSettings->set_reg_user_post(1);
        $this->selenium->open( osc_base_url() );
        $this->selenium->click("link=Publish your ad for free");
        $this->selenium->waitForPageToLoad("30000");
        $this->assertTrue($this->selenium->isTextPresent("Only registered users are allowed to post listings"), "Items, insert item, no user, can't publish");
        
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
            $this->assertTrue($this->selenium->isTextPresent("Your listing has been deleted"), "Delete item.");
        }
    }
    
    /*
     * - create user and add one item
     * - user not logged insert one item with previous user email
     * - show FM -> An user exists with that email, if it is you, please log-in
     */
    function testItems_useExistingEmail()
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
        $this->assertTrue($this->selenium->isTextPresent("Your listing has been published"),"insert ad error ") ;
        
        $uSettings->set_logged_user_item_validation( $old_logged_user_item_validation );
        $uSettings->set_enabled_users($old_enabled_users);
        $uSettings->set_enabled_user_registration($old_enabled_users_registration);
        $uSettings->set_enabled_user_validation($old_enabled_user_validation);
        
        // try to insert an item with existing user email
        $this->logout();
        
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
        $this->assertTrue($this->selenium->isTextPresent("An user exists with that email, if it is you, please log-in"),"Items, insert item, using existing user email.") ;
        
        $uSettings->set_items_wait_time($items_wait_time);
        $uSettings->set_selectable_parent_categories($set_selectable_parent_categories);
        $uSettings->set_reg_user_post($bool_reg_user_post);
        $uSettings->set_moderate_items($bool_enabled_user_validation);
        
        /*
         * Remove all items inserted previously
         */
        $aItem = Item::newInstance()->listAll();
        foreach($aItem as $item){
            $url = osc_item_delete_url( $item['s_secret'] , $item['pk_i_id'] );
            $this->selenium->open( $url );
            $this->assertTrue($this->selenium->isTextPresent("Your listing has been deleted"), "Delete item.");
        }
    }
    
    /*
     * Insert items, user.
     * register
     * login
     * add item, without validation
     * add item, with validation
     */
    function atestItems_User()
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
        $this->assertTrue($this->selenium->isTextPresent("Your listing has been published"),"insert ad error ") ;
        
        $uSettings->set_logged_user_item_validation(0);
        $this->insertItem($item['catId'], $item['title'], 
                                $item['description'], $item['price'],
                                $item['regionId'], $item['cityId'], $item['cityArea'],
                                $item['photo'], $item['contactName'], 
                                $this->_email);
        $this->assertTrue($this->selenium->isTextPresent("Check your inbox to validate your listing"),"Need validation but message don't appear")   ;
        
        $uSettings->set_logged_user_item_validation( $old_logged_user_item_validation );
        $uSettings->set_enabled_users($old_enabled_users);
        $uSettings->set_enabled_user_registration($old_enabled_users_registration);
        $uSettings->set_enabled_user_validation($old_enabled_user_validation);
        
        unset($uSettings);
    }
    
   
    
    
    /*
     * Try to edit a item with bad id_item
     */
    function atestEditUserItemBadId()
    {
        $this->selenium->open( osc_item_edit_url('', '9999') );
        $this->assertTrue($this->selenium->isTextPresent("Sorry, we don't have any listing with that ID"));
    }
    
    /*
     * Add a item, and try to edit logged as user
     */
    function atestEditUserItem1()
    {
        $this->logout();
        // create new item
        require dirname(__FILE__).'/ItemData.php';
        $item = array(
            "catId"         => 'Cars',
            'title'         => '2000 Ford Focus',
            'description'   => '2000 Ford Focus ZX3 Hatchback 2D Good Condition Clean Great Car Mileage: 175000 Passed BMV Emissions Clear Title Call me or Text if interested- Crystal 219',
            'price'         => '101',
            'regionId'      => 'Barcelona'  ,'cityId'        => 'Terrassa',
            'cityArea'      => ''           ,'address'       => '',
            'photo'         => array(),'contactName'   => 'contact ad 1','contactEmail'  => 'new@email.com'
        );
        
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
                                $item['contactEmail']);
        sleep(1);
        $this->assertTrue($this->selenium->isTextPresent("Your listing has been published"),"Items, insert item, no user, no validation.") ;
        
        $uSettings->set_items_wait_time($items_wait_time);
        $uSettings->set_selectable_parent_categories($set_selectable_parent_categories);
        $uSettings->set_reg_user_post($bool_reg_user_post);
        $uSettings->set_moderate_items($bool_enabled_user_validation);
        $itemId = $this->_lastItemId();
        
        // login and try to edit 
        $this->loginWith();
        
        $this->selenium->open(osc_item_edit_url('', $itemId));
        $this->assertTrue($this->selenium->isTextPresent(""),"Sorry, we don't have any listing with that ID") ;
        
        // remove item
        Item::newInstance()->deleteByPrimaryKey($itemId);
    }

    /*
     * Activate item via 'My account' as registered user
     */
    function atestActivate() // Activate
    {
        $this->loginWith();
        
        $this->selenium->open( osc_base_url() );
        $this->selenium->click("link=My account");
        $this->selenium->waitForPageToLoad("30000");
        $this->selenium->click("xpath=//ul/li/a[text()='Manage your listings']");
        $this->selenium->waitForPageToLoad("30000");
        $this->selenium->click("xpath=//div[@class='item']/p/a[text()='Activate']");
        $this->selenium->waitForPageToLoad("30000");
        $this->assertTrue($this->selenium->isTextPresent("The listing has been validated"), "Items, validate user item.");
    }
    
    /*
     * Try to activate a item from other user, registered user
     * Try to activate a item from other user, no registered user
     * Try to activate a item, item user / with secret
     */
    function atestActivate1()
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
        $this->assertTrue($this->selenium->isTextPresent("Page not found"), "Items, validate item from other user.");
        // 2 
        $this->logout();
        $url = osc_item_activate_url('', $itemId);
        $this->selenium->open($url);
        sleep(1);
        $this->assertTrue($this->selenium->isTextPresent("Page not found"), "Items, validate item from no user.");
        // 3 
        $item = Item::newInstance()->findByPrimaryKey($itemId);
        $url = osc_item_activate_url($item['s_secret'], $itemId);
        $this->selenium->open($url);
        sleep(1);
        $this->assertTrue($this->selenium->isTextPresent("The listing has been validated"), "Items, validate item. (direct url)");
        // remove all items 
        Item::newInstance()->deleteByPrimaryKey($itemId);
        
    }

    /*
     * Register user
     * Login
     * Edit first item, with validation
     * Edit first item, without validation
     */
    function atestEditItem()
    {
        $this->loginWith();
        
        $uSettings = new utilSettings();
        $old_moderate_items = $uSettings->set_moderate_items(0);
        
        $this->selenium->open( osc_base_url() );
        $this->selenium->click("link=My account");
        $this->selenium->waitForPageToLoad("30000");
        $this->selenium->click("xpath=//ul/li/a[text()='Manage your listings']");
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
        
        $this->assertTrue(  $this->selenium->isTextPresent("Great! We've just updated your listing"), 'Items, edit first item, with validation.' );
        
        $old_moderate_items = $uSettings->set_moderate_items(-1);
        $this->selenium->open( osc_base_url(true) );
        $this->selenium->click("link=My account");
        $this->selenium->waitForPageToLoad("30000");
        $this->selenium->click("xpath=//ul/li/a[text()='Manage your listings']");
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
        
        $this->assertTrue( $this->selenium->isTextPresent("Great! We've just updated your listing") ,"Items, edit first item, without validation." );
        
        $uSettings->set_moderate_items($old_moderate_items);
        
        unset($uSettings);
    }
    
    /*
     * Try to remove an item which not belongs to user 
     */
    function atestDeleteItemOtherUser()
    {
        $this->logout();
        $itemId = $this->_lastItemId();
        $url = osc_item_delete_url('', $itemId);
        
        $this->selenium->open($url);
        $this->assertTrue( $this->selenium->isTextPresent("The listing you are trying to delete couldn't be deleted") ,"Items, delete item without secret." );
    }
    
    function atestDeleteItem()
    {
        $this->loginWith();
        
        $this->selenium->open( osc_base_url() );
        $this->selenium->click("link=My account");
        $this->selenium->waitForPageToLoad("30000");

        $this->selenium->click("xpath=//ul/li/a[text()='Manage your listings']");
        $this->selenium->waitForPageToLoad("30000");

        $numItems = $this->selenium->getXpathCount("//div[@class='item']/p/a[text()='Delete']");

        while($numItems > 0) {
            // delete first item
            $this->selenium->click("xpath=//div[@class='item']/p/a[text()='Delete']");
            $this->selenium->waitForPageToLoad("30000");
            $this->assertTrue($this->selenium->isTextPresent("Your listing has been deleted"), "Can't delete listing. ERROR ");
            
            $numItems = $this->selenium->getXpathCount("//div[@class='item']/p/a[text()='Delete']");
            
            $this->selenium->open( osc_base_url() );
            $this->selenium->click("link=My account");
            $this->selenium->waitForPageToLoad("30000");

            $this->selenium->click("xpath=//ul/li/a[text()='Manage your listings']");
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
                                'test@force.com');
        sleep(1);
        $this->assertTrue($this->selenium->isTextPresent("Check your inbox to validate your listing"),"Items, insert item, no user, with validation.") ;
        
        $uSettings->set_items_wait_time($items_wait_time);
        $uSettings->set_selectable_parent_categories($set_selectable_parent_categories);
        $uSettings->set_reg_user_post($bool_reg_user_post);
        $uSettings->set_moderate_items($bool_enabled_user_validation);
        
        return $this->_lastItemId();
    }
}
?>