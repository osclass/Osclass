<?php
require_once dirname(__FILE__).'/../../../../oc-load.php';

//require_once('FrontendTest.php');

class OCadmin_customfields extends OCadminTest 
{   
    function atestCustomAdd()
    {
        $this->loginWith() ;
        
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("//a[@id='items_cfields']");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("//a[@id='add-button']");
        sleep(2);
        $this->selenium->type("field_name", "extra_field_1");
        $this->selenium->select("field_type", "TEXT");
        $this->selenium->click("xpath=//span[text()='Advanced options']");
        $this->selenium->type('field_slug','extra_field_1');
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue($this->selenium->isTextPresent("New custom field added"), "Add field");
        $this->assertTrue($this->selenium->isTextPresent("extra_field_1"), "Add field");
        $this->selenium->click("//a[@id='add-button']");
        $this->selenium->type("field_name", "extra_field_2");
        $this->selenium->select("field_type", "TEXTAREA");
        $this->selenium->click("xpath=//span[text()='Advanced options']");
        $this->selenium->type('field_slug','extra_field_2');
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue($this->selenium->isTextPresent("New custom field added"), "Add field");
        $this->assertTrue($this->selenium->isTextPresent("extra_field_2"), "Add field");
    }

    function atestCustomEdit()
    {
        $this->loginwith() ;
        
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Custom Fields");
        $this->selenium->click("link=Manage custom fields");
        $this->selenium->waitForPageToLoad("10000");
        // edit categories,
        $this->selenium->click("link=Edit");
        // modificar s_name & type
        $this->selenium->type("//input[@id='s_name']", "NEW FIELD2");
        $this->selenium->select("//form[@id='field_form']/div/div[2]/select", "TEXTAREA");
        // uncheck all
        $this->selenium->click("link=Uncheck all");
        sleep(1);
        $this->assertFalse($this->selenium->isChecked("categories[]"), "Uncheck all categories" );
        // check all
        $this->selenium->click("link=Check all");
        sleep(1);
        $this->assertTrue($this->selenium->isChecked("categories[]"), "Check all categories" );
        // uncheck all !
        $this->selenium->click("link=Uncheck all");
        $this->selenium->click("//input[@type='submit']");
        sleep(2);
        $this->assertTrue($this->selenium->isTextPresent("Saved"), "Edit field");
    }

//    function testCustomOthers()
//    {
//        $this->loginWith() ;
////        $this->noMoreThanOneForm() ;
//        $this->selenium->open( osc_admin_base_url(true) );
//        $this->selenium->click("link=Custom Fields");
//        $this->selenium->click("link=Manage custom fields");
//        $this->selenium->waitForPageToLoad("10000");
//
//        // edit categories,
//        $this->selenium->click("link=Edit"); // first Edit link
//        usleep(250000);
//        $this->assertTrue($this->selenium->isElementPresent("xpath=//form[@id='field_form']"), "Form is showed");
//        usleep(250000);
//        $this->selenium->click("xpath=//div[@id='TableFields']/ul/li[last()]/div/div[2]/a[1]") ;
//        sleep(2);
//        $var = (int)$this->selenium->getXpathCount("//form[@id='field_form']");
//        $this->assertTrue( ( 1 == 1) , "Form is showed more than one time");
////        $this->sameField() ;
//        $this->selenium->open( osc_admin_base_url(true) );
//        $this->selenium->click("link=Custom Fields");
//        $this->selenium->click("link=Manage custom fields");
//        $this->selenium->waitForPageToLoad("10000");
//
//        $this->selenium->click("id=button_add");
//        $this->selenium->type("field_name", "sameField");
//        $this->selenium->select("field_type", "TEXT");
//        $this->selenium->click("id=field_required");
//
//        $this->selenium->click("xpath=//span[text()='Advanced options']");
//        $this->selenium->type('field_slug','sameField');
//
//        $this->selenium->click("id=button_save");
//        $this->selenium->waitForPageToLoad("10000");
//        $this->assertTrue($this->selenium->isTextPresent("New custom field added"), "Add field");
//        $this->assertTrue($this->selenium->isTextPresent("sameField"), "Add field");
//        // insert same field
//        $this->selenium->click("id=button_add");
//        $this->selenium->type("field_name", "sameField");
//        $this->selenium->select("field_type", "TEXT");
//        $this->selenium->click("id=button_save");
//        $this->selenium->waitForPageToLoad("10000");
//        $this->assertTrue($this->selenium->isTextPresent("Sorry, you already have one field with that name"), "Add field");
//    }
//
//    function testCustomOnWebsite()
//    {
//        $this->loginWith() ;
//        $this->customOnFrontEnd();
//        $this->customOnAdminPanel();
//
//    }
//
//    function testDeleteCustomFields()
//    {
//        $this->loginWith() ;
//        
//        $this->selenium->open( osc_admin_base_url(true) );
//        $this->selenium->click("link=Custom Fields");
//        $this->selenium->waitForPageToLoad("10000");
//        $this->selenium->click("link=Manage custom fields");
//        $this->selenium->waitForPageToLoad("10000");
//        
//        $this->selenium->click("xpath=//a[text()='Delete' and last()]");
//        $this->selenium->waitForPageToLoad("1000");
//        $this->assertTrue($this->selenium->isTextPresent("Saved"), "Delete field");
//        
//
//        $this->selenium->open( osc_admin_base_url(true) );
//        $this->selenium->click("link=Custom Fields");
//        $this->selenium->waitForPageToLoad("10000");
//        $this->selenium->click("link=Manage custom fields");
//        $this->selenium->waitForPageToLoad("10000");
//        
//        $this->selenium->click("xpath=//a[text()='Delete' and last()]");
//        $this->selenium->waitForPageToLoad("1000");
//        $this->assertTrue($this->selenium->isTextPresent("Saved"), "Delete field");
//
//        $this->selenium->open( osc_admin_base_url(true) );
//        $this->selenium->click("link=Custom Fields");
//        $this->selenium->waitForPageToLoad("10000");
//        $this->selenium->click("link=Manage custom fields");
//        $this->selenium->waitForPageToLoad("10000");
//
//        $this->selenium->click("xpath=//a[text()='Delete' and last()]");
//        $this->selenium->waitForPageToLoad("1000");
//        $this->assertTrue($this->selenium->isTextPresent("Saved"), "Delete field");
//        
//        
//        if($this->selenium->getXpathCount("//form[@id='field_form']") > 0) {
//            $this->assertTrue(TRUE,"Delete all field");
//        }
//        
//        $mItem = new Item();
//        $aItems = $mItem->findByEmail( 'foobar@mail.com' );
//        foreach($aItems as $item) {
//            $res = $mItem->deleteByPrimaryKey($item['pk_i_id']);
//            $this->assertTrue($res);
//        }
//        
//    }
//
//
//


    private function customOnFrontEnd()
    {
        $uSettings = new utilSettings();
        
        $bool_reg_user_post  = $uSettings->set_reg_user_post(0);
        $bool_moderate_items = $uSettings->set_moderate_items(-1);
        // check if custom fields appears at website
        $this->selenium->open( osc_base_url(true) );
        $this->selenium->click("link=Publish your ad for free");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->select("catId", "label=regexp:\\s*Animals");
        usleep(500000);
        $this->selenium->type("id=title[en_US]", "foo title");
        $this->selenium->type("id=description[en_US]","description foo title");
        $this->selenium->select("countryId", "label=Spain");
        $this->selenium->select("regionId", "label=Albacete");
        $this->selenium->select("cityId", "label=Albacete");
        $this->selenium->type("cityArea", "my area");
        $this->selenium->type("address", "my address");

        $this->selenium->type('id=contactName' , 'foobar');
        $this->selenium->type('id=contactEmail', 'foobar@mail.com');

        $this->assertTrue($this->selenium->isTextPresent("extra_field_2"), "Custom fields at frontend");
        $this->assertTrue($this->selenium->isTextPresent("sameField")    , "Custom fields at frontend");
        $this->assertTrue($this->selenium->isTextPresent("NEW FIELD")    , "Custom fields at frontend");

        $this->selenium->type("id=meta_extra_field_1"  , "custom2");
        $this->selenium->type("id=meta_extra_field_2"  , "custom3");

        $this->selenium->click("//button[text()='Publish']");
        $this->selenium->waitForPageToLoad("30000");
        $this->assertTrue($this->selenium->isTextPresent("sameField field is required.","Field required") );

        $this->selenium->type("id=meta_samefield"      , "custom1");
        $this->selenium->type("id=meta_extra_field_1"  , "custom2");
        $this->selenium->type("id=meta_extra_field_2"  , "custom3");

        $this->selenium->click("//button[text()='Publish']");
        $this->selenium->waitForPageToLoad("30000");
        
        $this->assertTrue($this->selenium->isTextPresent("Your item has been published","Item published") );
        // volver a dejar reg_user_post flag en su estado original
        $uSettings->set_reg_user_post($bool_reg_user_post);
        $uSettings->set_moderate_items($bool_moderate_items);

        // remove item
        Item::newInstance()->delete( array('s_contact_email' => 'foobar@mail.com') ) ;
        
        unset($uSettings);
    }

    private function customOnAdminPanel()
    {
        // check if custom fields appears at website
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Items");
        $this->selenium->click("link=Add new item");
        $this->selenium->waitForPageToLoad("10000");
        
        $this->selenium->select("catId", "label=regexp:\\s*Animals");
        usleep(500000);
        $this->selenium->type("id=title[en_US]", "foo title");
        $this->selenium->type("id=description[en_US]","description foo title");
        $this->selenium->select("countryId", "label=Spain");
        $this->selenium->select("regionId", "label=Albacete");
        $this->selenium->select("cityId", "label=Albacete");
        $this->selenium->type("cityArea", "my area");
        $this->selenium->type("address", "my address");

        $this->selenium->type('id=contactName' , 'foobar');
        $this->selenium->type('id=contactEmail', 'foobar@mail.com');

        $this->assertTrue($this->selenium->isTextPresent("extra_field_2"), "Custom fields at frontend");
        $this->assertTrue($this->selenium->isTextPresent("sameField")    , "Custom fields at frontend");
        $this->assertTrue($this->selenium->isTextPresent("NEW FIELD")    , "Custom fields at frontend");

        $this->selenium->type("id=meta_extra_field_1"  , "custom2");
        $this->selenium->type("id=meta_extra_field_2"  , "custom3");

        $this->selenium->click("//button[text()='Add item']");
        $this->selenium->waitForPageToLoad("30000");
        $this->assertTrue($this->selenium->isTextPresent("sameField field is required."),"Field required" );

        $this->selenium->type("id=meta_samefield"      , "custom1");
        $this->selenium->type("id=meta_extra_field_1"  , "custom2");
        $this->selenium->type("id=meta_extra_field_2"  , "custom3");

        $this->selenium->click("//button[text()='Add item']");
        $this->selenium->waitForPageToLoad("30000");

        $this->assertTrue($this->selenium->isTextPresent("A new item has been added"),"Item published" );
    }
}
?>
