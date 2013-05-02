<?php
require_once dirname(__FILE__).'/../../../../oc-load.php';

define("MAX_FIELDS", 8);

class OCadmin_customfields extends OCadminTest
{
    function testCustomAdd()
    {
        $this->loginWith() ;

        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("xpath=//a[@id='items_cfields']");
        $this->selenium->waitForPageToLoad("10000");

        // ------------    text    ------------
        $this->selenium->click("//a[@id='add-button']");
        sleep(4);
        $this->selenium->selectFrame("edit-custom-field-frame");
        $this->selenium->type("s_name", "extra_field_1");
        $this->selenium->select("field_type", "TEXT");
        $this->selenium->click("//div[@id='advanced_fields_iframe']");
        $this->selenium->type('field_slug','my_extra_field');

        $this->selenium->click("xpath=//input[@id='cfield_save']");
        sleep(3);
        $this->assertTrue($this->selenium->isTextPresent("Saved"), "Add field");

        $this->assertTrue($this->selenium->isTextPresent("extra_field_1"), "Add field");

        // ------------    textarea    ------------
        $this->selenium->click("//a[@id='add-button']");
        sleep(4);
        $this->selenium->selectFrame("edit-custom-field-frame");
        $this->selenium->type("s_name", "extra_field_2");
        $this->selenium->select("field_type", "TEXTAREA");
        $this->selenium->click("//div[@id='advanced_fields_iframe']");
        $this->selenium->type('field_slug','my_extra_field_2');

        $this->selenium->click("xpath=//input[@id='cfield_save']");
        sleep(3);
        $this->assertTrue($this->selenium->isTextPresent("Saved"), "Add field");

        $this->assertTrue($this->selenium->isTextPresent("extra_field_2"), "Add field");

        // ------------    DROPDOWN    ------------
        $this->selenium->click("//a[@id='add-button']");
        sleep(4);
        $this->selenium->selectFrame("edit-custom-field-frame");
        $this->selenium->type("s_name", "extra_field_3");
        $this->selenium->select("field_type", "DROPDOWN");
        $this->selenium->type("s_options", "");
        $this->selenium->click("xpath=//input[@id='cfield_save']");
        sleep(3);
        $this->assertTrue($this->selenium->isTextPresent("At least one option is required"), "Add field check s_option empty");

        $this->selenium->type("s_options", "one,two,tree");
        $this->selenium->click("//div[@id='advanced_fields_iframe']");
        $this->selenium->type('field_slug','my_extra_field_3');

        $this->selenium->click("xpath=//input[@id='cfield_save']");
        sleep(3);
        $this->assertTrue($this->selenium->isTextPresent("Saved"), "Add field");

        $this->assertTrue($this->selenium->isTextPresent("extra_field_3"), "Add field");

        // ------------    RADIO    ------------
        $this->selenium->click("//a[@id='add-button']");
        sleep(4);
        $this->selenium->selectFrame("edit-custom-field-frame");
        $this->selenium->type("s_name", "extra_field_4");
        $this->selenium->select("field_type", "RADIO");
        $this->selenium->type("s_options", "");
        $this->selenium->click("xpath=//input[@id='cfield_save']");
        sleep(3);
        $this->assertTrue($this->selenium->isTextPresent("At least one option is required"), "Add field check s_option empty");

        $this->selenium->type("s_options", "four, fife, six");
        $this->selenium->click("//div[@id='advanced_fields_iframe']");
        $this->selenium->type('field_slug','my_extra_field_4');

        $this->selenium->click("xpath=//input[@id='cfield_save']");
        sleep(3);
        $this->assertTrue($this->selenium->isTextPresent("Saved"), "Add field");

        $this->assertTrue($this->selenium->isTextPresent("extra_field_4"), "Add field");

        // ------------    CHECKBOX    ------------
        $this->selenium->click("//a[@id='add-button']");
        sleep(4);
        $this->selenium->selectFrame("edit-custom-field-frame");
        $this->selenium->type("s_name", "extra_field_5");
        $this->selenium->select("field_type", "CHECKBOX");
        $this->selenium->type("s_options", "seven, eight, nine");
        $this->selenium->click("//div[@id='advanced_fields_iframe']");
        $this->selenium->type('field_slug','my_extra_field_5');

        $this->selenium->click("xpath=//input[@id='cfield_save']");
        sleep(3);
        $this->assertTrue($this->selenium->isTextPresent("Saved"), "Add field");

        $this->assertTrue($this->selenium->isTextPresent("extra_field_5"), "Add field");

        // ------------    URL    ------------
        $this->selenium->click("//a[@id='add-button']");
        sleep(4);
        $this->selenium->selectFrame("edit-custom-field-frame");
        $this->selenium->type("s_name", "extra_field_6");
        $this->selenium->select("field_type", "URL");
        $this->selenium->click("//input[@id='field_required']");
        $this->selenium->click("//div[@id='advanced_fields_iframe']");
        $this->selenium->type('field_slug','my_extra_field_6');

        $this->selenium->click("xpath=//input[@id='cfield_save']");
        sleep(3);
        $this->assertTrue($this->selenium->isTextPresent("Saved"), "Add field");

        $this->assertTrue($this->selenium->isTextPresent("extra_field_6"), "Add field");

        // ------------    DATE    ------------
        $this->selenium->click("//a[@id='add-button']");
        sleep(4);
        $this->selenium->selectFrame("edit-custom-field-frame");
        $this->selenium->type("s_name", "extra_field_7");
        $this->selenium->select("field_type", "DATE");
        $this->selenium->click("//div[@id='advanced_fields_iframe']");
        $this->selenium->type('field_slug','my_extra_field_7');

        $this->selenium->click("xpath=//input[@id='cfield_save']");
        sleep(3);
        $this->assertTrue($this->selenium->isTextPresent("Saved"), "Add field");

        $this->assertTrue($this->selenium->isTextPresent("extra_field_7"), "Add field");

        // ------------    DATEINTERVAL    ------------
        $this->selenium->click("//a[@id='add-button']");
        sleep(4);
        $this->selenium->selectFrame("edit-custom-field-frame");
        $this->selenium->type("s_name", "extra_field_8");
        $this->selenium->select("field_type", "DATEINTERVAL");
        $this->selenium->click("//div[@id='advanced_fields_iframe']");
        $this->selenium->type('field_slug','my_extra_field_8');

        $this->selenium->click("xpath=//input[@id='cfield_save']");
        sleep(3);
        $this->assertTrue($this->selenium->isTextPresent("Saved"), "Add field");

        $this->assertTrue($this->selenium->isTextPresent("extra_field_8"), "Add field");

    }

    /**
     * edit custom fields, update category -> check-all
     */
    function testCustomEdit()
    {
        $this->loginWith() ;

        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("xpath=//a[@id='items_cfields']");
        $this->selenium->waitForPageToLoad("10000");


        for($k=MAX_FIELDS;$k>0;$k--) {
            $this->selenium->click("xpath=(//div[@class='cfield-div']/div[@class='actions-edit-cfield']/a[contains(.,'Edit')])[".$k."]");
            sleep(4);
            // check all
            $this->selenium->click("link=Check all");
            sleep(4);
            $this->assertTrue($this->selenium->isChecked("categories[]"), "Check all categories" );
            $this->selenium->click("//input[@type='submit']");
            sleep(4);
            $this->assertTrue($this->selenium->isTextPresent("Saved"), "Edit field");
        }
    }

    function testCustomOnWebsite()
    {
        $this->loginWith() ;
        $this->customOnFrontEnd();
        $this->customOnAdminPanel();
    }

//    function testSearchCustomFields()
//    {
//        $this->loginWith();
//        // search through custom fields
//        $this->deleteAllItems();
//    }

    /**
     * delete custom fields
     */
//    function testCustomDelete()
//    {
//        $this->loginWith() ;
//
//        $this->selenium->open( osc_admin_base_url(true) );
//        $this->selenium->click("xpath=//a[@id='items_cfields']");
//        $this->selenium->waitForPageToLoad("10000");
//
//        for($k=MAX_FIELDS;$k>0;$k--) {
//            $this->selenium->click("xpath=(//div[@class='cfield-div']/div[@class='actions-edit-cfield']/a[contains(.,'Delete')])[1]");
//            sleep(2);
//            $this->selenium->click("//a[@id='field-delete-submit']");
//            sleep(3);
//            $this->assertTrue($this->selenium->isTextPresent("The custom field has been deleted"), "Delete field");
//            sleep(2);
//        }
//    }

    private function customOnFrontEnd()
    {
        $uSettings = new utilSettings();

        $bool_reg_user_post  = $uSettings->set_reg_user_post(0);
        $bool_moderate_items = $uSettings->set_moderate_items(-1);
        // check if custom fields appears at website
        $this->selenium->open( osc_base_url(true) );
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("link=Publish your ad for free");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->select("select_1", "label=regexp:\\s*For sale");
        $this->selenium->select("select_2", "label=regexp:\\s*Animals");
        sleep(2);
        $this->selenium->type("id=title[en_US]", "foo title");
        $this->selenium->type("id=description[en_US]","description foo title");
        $this->selenium->select("countryId", "label=Spain");
        $this->selenium->select("regionId", "label=Albacete");
        $this->selenium->select("cityId", "label=Albacete");
        $this->selenium->type("cityArea", "my area");
        $this->selenium->type("address", "my address");

        $this->selenium->type('id=contactName' , 'foobar');
        $this->selenium->type('id=contactEmail', 'foobar@mail.com');

        $this->assertTrue($this->selenium->isTextPresent("extra_field_1")    , "Custom fields at frontend");
        $this->assertTrue($this->selenium->isTextPresent("extra_field_2")    , "Custom fields at frontend");
        $this->assertTrue($this->selenium->isTextPresent("extra_field_3")    , "Custom fields at frontend");

        /**
         * DATE / DATEINTERVAL Notes:
         *
         */

        // DATE
        $this->selenium->runScript("javascript{ this.browserbot.getCurrentWindow().document.getElementsByName('CSRFName')[0].value = ''; }");
        // DATE INTERVAL
        $this->selenium->runScript("javascript{ this.browserbot.getCurrentWindow().document.getElementsByName('CSRFName')[0].value = ''; }");

        $this->selenium->type("id=meta_my_extra_field"  , "custom2");
        $this->selenium->type("id=meta_my_extra_field_2"  , "custom3");

        $this->selenium->click("//button[text()='Publish']");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue($this->selenium->isTextPresent("extra_field_6 field is required.","Field required") );

        $this->selenium->type("id=meta_my_extra_field_6"      , "custom6");

        $this->selenium->click("//button[text()='Publish']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("Your listing has been published","Item published") );
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
        $this->selenium->click("//a[@id='items_manage']");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("link=Add new");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->select("select_1", "label=regexp:\\s*For sale");
        sleep(2);
        $this->selenium->select("select_2", "label=regexp:\\s*Animals");
        sleep(2);
        $this->selenium->type("id=title[en_US]", "foo title");
        $this->selenium->type("id=description[en_US]","description foo title");
        $this->selenium->select("countryId", "label=Spain");
        $this->selenium->select("regionId", "label=Albacete");
        $this->selenium->select("cityId", "label=Albacete");
        $this->selenium->type("cityArea", "my area");
        $this->selenium->type("address", "my address");

        $this->selenium->type('id=contactName' , 'foobar');
        $this->selenium->type('id=contactEmail', 'foobar@mail.com');

        $this->assertTrue($this->selenium->isTextPresent("extra_field_1"), "Custom fields at frontend");
        $this->assertTrue($this->selenium->isTextPresent("extra_field_2")    , "Custom fields at frontend");
        $this->assertTrue($this->selenium->isTextPresent("extra_field_3")    , "Custom fields at frontend");

        $this->selenium->type("id=meta_my_extra_field"  , "custom2");
        $this->selenium->type("id=meta_my_extra_field_2"  , "custom3");

        $this->selenium->click("//input[@value='Add listing']");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue($this->selenium->isTextPresent("extra_field_6 field is required.","Field required") );

        $this->selenium->type("id=meta_my_extra_field_6"      , "custom6");

        $this->selenium->click("//input[@value='Add listing']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("A new listing has been added"),"Item published" );
    }


    private function deleteAllItems()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("//a[@id='items_manage']");
        $this->selenium->waitForPageToLoad("10000");


        $this->selenium->click("//input[@id='check_all']");
        $this->selenium->select("//select[@name='bulk_actions']", "label=Delete");
        $this->selenium->click("//input[@id='bulk_apply']");
        sleep(2);
        $this->selenium->click("//a[@id='bulk-actions-submit']");
        $this->selenium->waitForPageToLoad("30000");
        // "regexpi:This is SeleniumWiki.com"
        if( $this->selenium->isTextPresent( "regexpi:listings have been deleted") ){
            $this->assertTrue("Deleted ok");
        } else {
            $this->assertFalse("TEXT NOT PRESENT - X listings have been deleted");
        }
    }

}
?>
