<?php
require_once dirname(__FILE__).'/../../../../oc-load.php';

//require_once('FrontendTest.php');

class OCadmin_categories extends OCadminTest {

    /*           TESTS          */
    /**
     * Test expiration of items by i_expiration_days at t_category
     * Go to Manage Categories
     * - update expiration days of category with items
     * - check dt_expiration of this items that belonging to updated category
     */
    function testCategory_updateExpiration()
    {
        $this->loginWith() ;
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Categories");
        $this->selenium->click("//a[@id='categories']");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue($this->selenium->isTextPresent("Categories"),"Categories ...");

        // add item at subcategory 'Cars'
        $this->_addItem();
        $itemId = $this->_lastItemId();
        // hardcoded - update dt_pub_date
        Item::newInstance()->update(array('dt_pub_date' => '2010-01-01 10:10:10'), array('pk_i_id' => $itemId));

        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Categories");
        $this->selenium->click("//a[@id='categories_manage']");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("xpath=//div[@class='name-cat'][contains(.,'Vehicles')]/span[@class='toggle' and not(contains(@style,'display:none'))]");
        sleep(1);
        $this->selenium->click("xpath=//div[@class='category_row' and contains(.,'Cars')]/div[@class='actions-cat']/a[text()='Edit']");
        sleep(2);
        $this->selenium->type('i_expiration_days', 5);
        $this->selenium->click("xpath=//input[@value='Save changes']");
        sleep(2);

        // check
        $item = $this->_lastItem();
        $this->assertTrue($item['dt_expiration'] == '2010-01-06 10:10:10', 'Check dt_expiration at t_item');

        Item::newInstance()->update(array('dt_expiration' => (date('Y')+1).'-01-01 10:10:10'), array('pk_i_id' => $itemId));
        Item::newInstance()->deleteByPrimaryKey($itemId);
    }

    function testCategory_createCategory()
    {
        $this->loginWith() ;
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Categories");
        $this->selenium->click("//a[@id='categories']");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertFalse($this->selenium->isTextPresent("NEW CATEGORY, EDIT ME!"),"Create category");
        $this->selenium->click("link=Add");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue($this->selenium->isTextPresent("NEW CATEGORY, EDIT ME!"),"Create category");
        $this->selenium->click("xpath=//div[@class='category_row' and contains(.,'NEW CATEGORY')]/div[@class='actions-cat']/a[text()='Delete']");
        sleep(2);
        $this->selenium->click("//a[@id='category-delete-submit']");
        sleep(2);
        $this->assertTrue($this->selenium->isTextPresent("Saved"),"Create category");

    }

    function testCategory_enableDisableCategory()
    {
        $this->loginWith() ;
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Categories");
        $this->selenium->click("//a[@id='categories']");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertFalse($this->selenium->isTextPresent("NEW CATEGORY, EDIT ME!"),"Check NEW CATEGORY does not exists");
        $this->selenium->click("link=Add");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue($this->selenium->isTextPresent("NEW CATEGORY, EDIT ME!"),"Create category");
        $this->assertFalse($this->selenium->isTextPresent("Enable"),"Check enable is not present");
        $this->selenium->click("xpath=//div[@class='category_row' and contains(.,'NEW CATEGORY')]/div[@class='actions-cat']/a[text()='Disable']");
        sleep(2);
        $this->assertTrue($this->selenium->isTextPresent("The category as well as its subcategories have been disabled"),"Category disabled");
        $this->assertTrue($this->selenium->isTextPresent("Enable"),"Check enable is present");
        $this->selenium->click("xpath=//div[@class='category_row' and contains(.,'NEW CATEGORY')]/div[@class='actions-cat']/a[text()='Enable']");
        sleep(2);
        $this->assertTrue($this->selenium->isTextPresent("The category as well as its subcategories have been enabled"),"Category disabled");
        $this->assertFalse($this->selenium->isTextPresent("Enable"),"Check enable is present");


        $this->selenium->click("xpath=//div[@class='category_row' and contains(.,'NEW CATEGORY')]/div[@class='actions-cat']/a[text()='Delete']");
        sleep(2);
        $this->selenium->click("//a[@id='category-delete-submit']");

        sleep(2);
        $this->assertTrue($this->selenium->isTextPresent("Saved"),"Create category");

    }

    function _addItem()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("xpath=//a[@id='items']");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("link=Add new");
        $this->selenium->waitForPageToLoad("10000");

        // insert non registered user
        $this->selenium->type("contactName" , "contact name");
        $this->selenium->type("contactEmail", "test@mail.com");

        $this->selenium->select("parentCatId", "label=regexp:\\s*Vehicles");
        $this->selenium->select("catId", "label=regexp:\\s*Cars");
        $this->selenium->type("title[en_US]", "title item");
        $this->selenium->type("description[en_US]", "description test description test description test");
        $this->selenium->type("price", "11");
        $this->selenium->select("currency", "label=Euro €");

        $this->selenium->select("countryId", "label=Spain");

        $this->selenium->type('id=region', 'A Coruña');
        $this->selenium->click('id=ui-active-menuitem');

        $this->selenium->type('id=city', 'A Capela');
        $this->selenium->click('id=ui-active-menuitem');

        $this->selenium->type("address", "address item");

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("A new listing has been added"), "Can't insert a new item. ERROR");
    }
}
?>
