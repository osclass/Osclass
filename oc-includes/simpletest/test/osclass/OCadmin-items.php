<?php
require_once dirname(__FILE__).'/../../../../oc-load.php';

//require_once('FrontendTest.php');

class OCadmin_items extends OCadminTest {

    /*
     * Login oc-admin
     * Insert item
     *
     */
    function testInsertItem()
    {
        $this->loginWith();
        $this->insertItem() ;
        $this->viewMedia_NoMedia();
        $this->viewComments_NoComments();
        $this->deactivate();
        $this->activate();
        $this->markAsPremium();
        $this->unmarkAsPremium();
    }

    /*
     * Login oc-admin
     * Edit item
     */
    function testEditItem()
    {
        $this->loginWith() ;
        $this->editItem() ;
    }

    /*
     * Login oc-admin
     * Delete item
     */
    function testDeleteItem()
    {
        $this->loginWith() ;
        $this->deleteItem() ;
    }

    /*
     * Login oc-admin
     * Insert item, add comments to item
     */
    function testComments()
    {
        $this->loginWith() ;
        $this->insertItemAndComments() ;
    }

    /*
     * Login oc-admin
     * Insert item, add media to item
     */
    function testMedia()
    {
        $this->loginWith() ;
        $this->insertItemAndMedia() ;
    }

    /*
     * Login oc-admin
     * Check all item settings (values & behaviour into website)
     */
    function testSettings()
    {
        $this->loginWith() ;
        $this->settings() ;
    }

    /**
     * Test item's views
     */
    function testStats()
    {
        $this->loginWith();
        $this->insertItem();
        $dao = new DAO();
        $dao->dao->select();
        $dao->dao->from(DB_TABLE_PREFIX.'t_item');
        $dao->dao->orderBy('pk_i_id', 'DESC');
        $dao->dao->limit(1);

        $result = $dao->dao->get();
        if($result) {
            $item  = $result->row();
            View::newInstance()->_exportVariableToView("item", $item);
        } else {
            $this->assertTrue(false, "THERE ARE NO ITEMS");
        }

        $dao->dao->select();
        $dao->dao->from(DB_TABLE_PREFIX.'t_item_stats');
        $dao->dao->where('fk_i_item_id', $item['pk_i_id']);
        $dao->dao->orderBy('dt_date', 'DESC');
        $dao->dao->limit(1);

        $result = $dao->dao->get();
        if($result) {
            $stats = $result->row();
            $this->assertTrue($stats['i_num_views']==0, "ITEM STATS BEFORE");
        } else {
            $this->assertTrue(false, "THERE ARE NO ITEMS STATS");
        }


        $random = rand(1, 10);
        for($k = 0;$k<$random; $k++) {
            $this->selenium->open(osc_item_url());
        }

        $dao->dao->select();
        $dao->dao->from(DB_TABLE_PREFIX.'t_item_stats');
        $dao->dao->where('fk_i_item_id', $item['pk_i_id']);
        $dao->dao->orderBy('dt_date', 'DESC');
        $dao->dao->limit(1);
        $result = $dao->dao->get();
        if($result) {
            $stats  = $result->row();
            $this->assertTrue($stats['i_num_views']==0, "ITEM STATS ADMIN (should be 0)");
        } else {
            $this->assertTrue(false, "THERE ARE NO ITEMS STATS");
        }


        $this->logout();

        $random = rand(1, 10);
        for($k = 0;$k<$random; $k++) {
            $this->selenium->open(osc_item_url());
        }

        $dao->dao->select();
        $dao->dao->from(DB_TABLE_PREFIX.'t_item_stats');
        $dao->dao->where('fk_i_item_id', $item['pk_i_id']);
        $dao->dao->orderBy('dt_date', 'DESC');
        $dao->dao->limit(1);
        $result = $dao->dao->get();
        if($result) {
            $stats  = $result->row();
            $this->assertTrue($stats['i_num_views']==$random, "ITEM STATS USER (should be ".$random.")");
        } else {
            $this->assertTrue(false, "THERE ARE NO ITEMS STATS");
        }


        $this->loginWith() ;
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("//a[@id='items_manage']");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->mouseOver("xpath=//table/tbody/tr/td[contains(.,'title item')]");
        $this->selenium->click("//table/tbody/tr/td[contains(.,'title item')]/div/ul/li/a[text()='Delete']");
        sleep(1);
        $this->selenium->click("//input[@id='item-delete-submit']");

        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("The listing has been deleted"), "Can't delete item. ERROR");


    }


     /*      PRIVATE FUNCTIONS       */
    private function addUserForTesting()
    {
        $user = User::newInstance()->findByEmail($this->_email);

        if(isset($user['pk_i_id']) ) {
            User::newInstance()->deleteUser($user['pk_i_id']);
        }

        $input['s_secret']          = osc_genRandomPassword() ;
        $input['dt_reg_date']       = date('Y-m-d H:i:s');
        $input['s_name']            = "Carlos";
        $input['s_website']         = "www.osclass.org";
        $input['s_phone_land']      = "931234567";
        $input['s_phone_mobile']    = "666121212";
        $input['fk_c_country_code'] = null ;
        $input['s_country']         = null ;
        $input['fk_i_region_id']    = null ;
        $input['s_region']          = "" ;
        $input['fk_i_city_id']      = null ;
        $input['s_city']            = "";
        $input['s_city_area']       = "";
        $input['s_address']         = "c:/address nº 10 2º2ª";
        $input['b_company']         = 0;
        $input['b_enabled']         = 1;
        $input['b_active']          = 1;
        $input['s_email']           = $this->_email;

        $input['s_password']        = sha1($this->_password);

        $this->array = $input;

        User::newInstance()->insert($input) ;
    }

    private function loginWebsite()
    {
        $this->selenium->open( osc_base_url(true) );
        $bool = $this->selenium->isElementPresent('login_open') ;
        if($bool){
            $this->selenium->click("login_open");
            $this->selenium->type("email"   , $this->_email);
            $this->selenium->type("password", $this->_password);
            $this->selenium->click("xpath=//button[@type='submit']");
            $this->selenium->waitForPageToLoad("30000");
            sleep(5);
            if($this->selenium->isTextPresent("Logout")){
                $this->logged = 1;
                $this->assertTrue("ok");
                $this->assertTrue(true);
            }else {
                $this->assertTrue(false);
            }
        }
    }

    private function logOutWebsite()
    {
        $this->selenium->open( osc_base_url(true) );
        $bool = $this->selenium->isElementPresent('login_open') ;
        if(!$bool) {
            $this->selenium->click('label=Log Out');
            $this->selenium->waitForPageToLoad("30000");
        }
    }

    // todo test minim lenght title, description , contact email
    private function insertItem($bPhotos = FALSE )
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
        sleep(2);
        $this->selenium->select("catId", "label=regexp:\\s*Cars");
        $this->selenium->type("title[en_US]", "title item");
        $this->selenium->type("description[en_US]", "description test description test description test");
        $this->selenium->type("price", "12".osc_locale_thousands_sep()."34".osc_locale_thousands_sep()."56".osc_locale_dec_point()."78".osc_locale_dec_point()."90");
        $this->selenium->fireEvent("price", "blur");
        sleep(2);
        $this->assertTrue($this->selenium->getValue("price")=="123456".osc_locale_dec_point()."78", "Check price correction input");
        $this->selenium->type("price", "11");
        $this->selenium->select("currency", "label=Euro €");

        $this->selenium->select("countryId", "label=Spain");

        $this->selenium->type('id=region', 'A Coruña');
        $this->selenium->click('id=ui-active-menuitem');

        $this->selenium->type('id=city', 'A Capela');
        $this->selenium->click('id=ui-active-menuitem');

        $this->selenium->type("address", "address item");

        if( $bPhotos ) {
            $this->selenium->type("xpath=//input[@name='photos[]']", LIB_PATH."simpletest/test/osclass/img_test1.gif");
            sleep(0.5);
            $this->selenium->type("//div[@id='p-0']/input", LIB_PATH."simpletest/test/osclass/img_test2.gif");
        }

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("A new listing has been added"), "Can't insert a new item. ERROR");
    }

    /**
     *  Check if there is a link 'View Media'
     */
    private function viewMedia_NoMedia()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("//a[@id='items_manage']");
        $this->selenium->waitForPageToLoad("10000");
        sleep(2); // time enough to load table data

        $thereIsMedia = (int)$this->selenium->getXpathCount("//table/tbody/tr/td[contains(.,'title item')]/div/ul/li/a[text()='View media']");

        $this->assertTrue( ($thereIsMedia==0), "Show media when there aren't. ERROR");
    }

    private function viewComments_NoComments()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("//a[@id='items_manage']");
        $this->selenium->waitForPageToLoad("10000");
        sleep(2); // time enough to load table data

        $thereIsMedia = (int)$this->selenium->getXpathCount("//table/tbody/tr/td[contains(.,'title item')]/div/ul/li/a[text()='View comments']");

        $this->assertTrue( ($thereIsMedia==0), "Show media when there aren't. ERROR");
    }

    private function deactivate()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("//a[@id='items_manage']");
        $this->selenium->waitForPageToLoad("10000");

        sleep(2); // time enough to load table data

        $this->selenium->click("//table/tbody/tr/td[contains(.,'title item')]/div/ul/li[@class='show-more']/ul/li/a[text()='Deactivate']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("The listing has been deactivated"), "Can't deactivate item. ERROR");
    }

    private function activate()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("//a[@id='items_manage']");
        $this->selenium->waitForPageToLoad("10000");

        sleep(2); // time enough to load table data

        $this->selenium->click("//table/tbody/tr/td[contains(.,'title item')]/div/ul/li[@class='show-more']/ul/li/a[text()='Activate']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("The listing has been activated"), "Can't activate item. ERROR");
    }

    private function markAsPremium()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("//a[@id='items_manage']");
        $this->selenium->waitForPageToLoad("10000");

        sleep(2); // time enough to load table data

        $this->selenium->click("//table/tbody/tr/td[contains(.,'title item')]/div/ul/li[@class='show-more']/ul/li/a[text()='Mark as premium']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("Changes have been applied"), "Can't mark as premium item. ERROR");
    }

    private function unmarkAsPremium()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("//a[@id='items_manage']");
        $this->selenium->waitForPageToLoad("10000");

        sleep(2); // time enough to load table data

        $this->selenium->click("//table/tbody/tr/td[contains(.,'title item')]/div/ul/li[@class='show-more']/ul/li/a[text()='Unmark as premium']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("Changes have been applied"), "Can't mark as premium item. ERROR");
    }


    private function editItem()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("//a[@id='items_manage']");
        $this->selenium->waitForPageToLoad("10000");

        sleep(2); // time enough to load table data

        $this->selenium->click("//table/tbody/tr/td[contains(.,'title item')]/div/ul/li/a[text()='Edit']");
        $this->selenium->waitForPageToLoad("10000");

        // insert non registered user
        $this->selenium->type("contactName" , "contact name_");
        $this->selenium->type("contactEmail", "test_@mail.com");

        $this->selenium->select("catId", "label=regexp:\\s*Cars");
        $this->selenium->type("title[en_US]", "title_item");
        $this->selenium->type("description[en_US]", "description_test_description test description_test");
        $this->selenium->type("price", "11");
        $this->selenium->select("currency", "label=Euro €");
        $this->selenium->select("regionId", "label=A Coruña");
        $this->selenium->select("cityId", "label=A Capela");
        $this->selenium->type("address", "address_item");

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("Changes saved correctly"), "Can't edit item. ERROR");
    }

    private function deleteItem()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("//a[@id='items_manage']");
        $this->selenium->waitForPageToLoad("10000");

        sleep(2); // time enough to load table data

        $this->selenium->click("//table/tbody/tr/td[contains(.,'title_item')]/div/ul/li/a[text()='Delete']");
        $this->selenium->click("//input[@id='item-delete-submit']");
        sleep(1);
        $this->selenium->waitForPageToLoad("10000");
        sleep(1);
        $this->assertTrue($this->selenium->isTextPresent("The listing has been deleted"), "Can't delete item. ERROR");
    }

    private function insertItemAndComments()
    {
        // insert item
        $this->insertItem() ;

        $mItem = new Item();

        $item = $mItem->findByEmail( 'test@mail.com' );
        $item = $item[0];

        // force moderation comments
        $enabled_comments = Preference::newInstance()->findValueByName('enabled_comments');
        if( $enabled_comments == 0 ) {
            Preference::newInstance()->update(array('s_value' => 1)
                                             ,array('s_name'  => 'enabled_comments'));
        }
        $moderate_comments = Preference::newInstance()->findValueByName('moderate_comments');
        if( $enabled_comments != 0 ) {
            Preference::newInstance()->update(array('s_value' => 0)
                                             ,array('s_name'  => 'moderate_comments'));
        }
        osc_reset_preferences();
        // insert comment from frontend

        $this->selenium->open(osc_item_url_ns( $item['pk_i_id'] ));

        $this->selenium->type("authorName"      , "Test B user");
        $this->selenium->type("authorEmail"     , "testing+testb@osclass.org");
        $this->selenium->type("title"           , "I like it");
        $this->selenium->type("body"            , "Can you provide more info please :)");

        $this->selenium->click("//div[@id='comments']/form/fieldset/div/span/button"); // OJO
        $this->selenium->waitForPageToLoad("30000");

        // test oc-admin
        $this->loginWith();

        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("//a[@id='items_comments']");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->mouseOver("//table/tbody/tr/td[contains(text(),'Test B user')]");
        $this->selenium->click("//table/tbody/tr/td[contains(text(),'Test B user')]/div/ul/li[@class='show-more']/ul/li/a[text()='Activate']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("The comment has been approved"), "Can't activate comment. ERROR" );

        $this->selenium->mouseOver("//table/tbody/tr/td[contains(text(),'Test B user')]");
        $this->selenium->click("//table/tbody/tr/td[contains(text(),'Test B user')]/div/ul/li[@class='show-more']/ul/li/a[text()='Deactivate']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("The comment has been disapproved"), "Can't deactivate comment. ERROR" );

        $this->selenium->mouseOver("//table/tbody/tr/td[contains(text(),'Test B user')]");
        $this->selenium->click("//table/tbody/tr/td[contains(text(),'Test B user')]/div/ul/li/a[text()='Edit']");
        $this->selenium->waitForPageToLoad("10000");

        // edit comment
        $this->selenium->type("title", "I like it updated");
        $this->selenium->type("authorName", "Test user osclass");
        $this->selenium->type("body", "Can you provide more info please :) Regards");
        $this->selenium->click("xpath=//input[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");

        $this->assertTrue($this->selenium->isTextPresent("Great! We just updated your comment"), "Can't edit a comment. ERROR") ;

        $this->selenium->mouseOver("//table/tbody/tr/td[contains(text(),'Test B user')]");
        $this->selenium->click("//table/tbody/tr/td[contains(text(),'Test B user')]/div/ul/li/a[text()='Delete']");
        sleep(1);
        $this->selenium->click("//input[@id='comment-delete-submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("The comment has been deleted"), "Can't delete a comment. ERROR") ;

        // DELETE ITEM
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("//a[@id='items_manage']");
        $this->selenium->waitForPageToLoad("10000");

        sleep(2); // time enough to load table data

        $this->selenium->mouseOver("//table/tbody/tr/td[contains(text(),'title item')]");
        $this->selenium->click("//table/tbody/tr/td[contains(.,'title item')]/div/ul/li/a[text()='Delete']");
        $this->selenium->click("//input[@id='item-delete-submit']");

        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("The listing has been deleted"), "Can't delete item. ERROR");

        // restore prefereces values
        Preference::newInstance()->update(array('s_value' => $enabled_comments)
                                         ,array('s_name'  => 'enabled_comments'));
        Preference::newInstance()->update(array('s_value' => $moderate_comments)
                                         ,array('s_name'  => 'moderate_comments'));
        osc_reset_preferences();
    }

    private function insertItemAndMedia()
    {
        // insert item
        $this->insertItem( TRUE ) ;

        // test oc-admin
        $this->loginWith();

        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("//a[@id='items_media']");
        $this->selenium->waitForPageToLoad("10000");
//        $this->assertTrue($this->selenium->isTextPresent("Showing 1 to 2 of 2 entries"), "Inconsistent . ERROR" );

        // only can delete resources
        $this->selenium->click("xpath=//a[position()=1 and contains(.,'Delete')]");
        sleep(4);
        $this->selenium->click("//input[@id='media-delete-submit']");
        $this->selenium->waitForPageToLoad("10000");
        sleep(20);
        $this->assertTrue($this->selenium->isTextPresent("Resource deleted"), "Can't delete media. ERROR" );
//        $this->assertTrue($this->selenium->isTextPresent("Showing 1 to 1 of 1 entries"), "Can't delete media. ERROR" );

        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("//a[@id='items_media']");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->click("xpath=//a[position()=1 and contains(.,'Delete')]");
        sleep(4);
        $this->selenium->click("//input[@id='media-delete-submit']");
        $this->selenium->waitForPageToLoad("10000");
        sleep(20);
        $this->assertTrue($this->selenium->isTextPresent("Resource deleted"), "Can't delete media. ERROR" );
        $this->assertTrue($this->selenium->isTextPresent("No data available in table"), "Can't delete media. ERROR" );

        // DELETE ITEM
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("//a[@id='items_manage']");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->mouseOver("//table/tbody/tr/td[contains(text(),'title item')]");
        $this->selenium->click("//table/tbody/tr/td[contains(.,'title item')]/div/ul/li/a[text()='Delete']");
        $this->selenium->click("//input[@id='item-delete-submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("The listing has been deleted"), "Can't delete item. ERROR");
    }

    private function settings()
    {
// reg_user_post
        Preference::newInstance()->replace('reg_user_post', '0',"osclass", 'INTEGER') ;
        $this->checkWebsite_reg_user_post(0);
        $this->checkWebsite_reg_user_post(0,true);
        Preference::newInstance()->replace('reg_user_post', '1',"osclass", 'INTEGER') ;
        $this->checkWebsite_reg_user_post(1);
        $this->checkWebsite_reg_user_post(1,true);
// enabled_recaptcha_items
        Preference::newInstance()->replace('reg_user_post', '0',"osclass", 'INTEGER') ;
        Preference::newInstance()->replace('enabled_recaptcha_items', 1,"osclass", 'BOOLEAN') ;
        $this->checkWebsite_recaptcha(1);
        Preference::newInstance()->replace('enabled_recaptcha_items', 0,"osclass", 'BOOLEAN') ;
        $this->checkWebsite_recaptcha(0);
// moderate_items
        // moderate only one item.
        Preference::newInstance()->replace('logged_user_item_validation', '0',"osclass", 'INTEGER') ;
        Preference::newInstance()->replace('moderate_items', '1',"osclass", 'INTEGER') ;
        $this->checkWebsite_moderate_items('1');
        // never moderate
        Preference::newInstance()->replace('moderate_items', '-1',"osclass", 'INTEGER') ;
        $this->checkWebsite_moderate_items('-1');
        // always moderate
        Preference::newInstance()->replace('moderate_items', '0',"osclass", 'INTEGER') ;
        $this->checkWebsite_moderate_items('0');
// logged_user_item_validation
        Preference::newInstance()->replace('logged_user_item_validation', '0',"osclass", 'INTEGER') ;
        $this->checkWebsite_logged_user_item_validation('0');
        Preference::newInstance()->replace('logged_user_item_validation', '1',"osclass", 'INTEGER') ;
        $this->checkWebsite_logged_user_item_validation('1');
// items_wait_time
        Preference::newInstance()->replace('items_wait_time', '0',"osclass", 'INTEGER') ;
        $this->checkWebsite_items_wait_time('0');
        $this->selenium->deleteAllVisibleCookies();
        Preference::newInstance()->replace('items_wait_time', '30',"osclass", 'INTEGER') ;
        $this->checkWebsite_items_wait_time('30');
// reg_user_can_contact
        Preference::newInstance()->replace('items_wait_time', '0',"osclass", 'INTEGER') ;
        Preference::newInstance()->replace('reg_user_can_contact', '0',"osclass", 'BOOLEAN') ;
        $this->checkWebsite_reg_user_can_contact('0');
        usleep(25000);
        Preference::newInstance()->replace('reg_user_can_contact', '1',"osclass", 'BOOLEAN') ;
        $this->checkWebsite_reg_user_can_contact('1');
// enableField#f_price@items
        Preference::newInstance()->replace('enableField#f_price@items', '0',"osclass", 'BOOLEAN') ;
        $this->checkWebsite_enableField_f_price_items('0');
        usleep(25000);
        Preference::newInstance()->replace('enableField#f_price@items', '1',"osclass", 'BOOLEAN') ;
        $this->checkWebsite_enableField_f_price_items('1');
// enableField#images@items  //  numImages@items
        Preference::newInstance()->replace('enableField#images@items', '0',"osclass", 'BOOLEAN') ;
        $this->checkWebsite_enableField_images_items('0');
        Preference::newInstance()->replace('enableField#images@items', '1',"osclass", 'BOOLEAN') ;
        Preference::newInstance()->replace('numImages@items', '1',"osclass", 'INTEGER') ;
        $this->checkWebsite_enableField_images_items('1','1');
        Preference::newInstance()->replace('numImages@items', '4',"osclass", 'INTEGER') ;

        $mItem = new Item();
        $aItems = $mItem->findByEmail( 'foobar@mail.com' );
        foreach($aItems as $item) {
            $res = $mItem->deleteByPrimaryKey($item['pk_i_id']);
            $this->assertTrue($res, 'Item deleted ok');
        }
        osc_reset_preferences();
    }

    private function post_item_website(){
        $this->selenium->open( osc_item_post_url() );
        $this->selenium->select("catId", "label=regexp:\\s*Animals");
        $this->selenium->type("id=title[en_US]", "foo title");
        $this->selenium->type("id=description[en_US]","description foo title");
        $this->selenium->select("countryId", "label=Spain");
        $this->selenium->select("regionId", "label=Albacete");
        $this->selenium->select("cityId", "label=Albacete");
        $this->selenium->type("cityArea", "my area");
        $this->selenium->type("address", "my address");

        $this->selenium->type('id=contactName' , 'foobar');
        $this->selenium->type('id=contactEmail', 'foobar@mail.com');

        $this->selenium->click("xpath=//button[text()='Publish']");
        $this->selenium->waitForPageToLoad("30000");
    }

    private function checkWebsite_reg_user_post($bool,$loginUser = false)
    {
        if($loginUser){
            $this->addUserForTesting();
            $this->loginWebsite();
        } else {
            $this->logOutWebsite();
        }

        if($bool == 0) {
            $this->post_item_website();
            $this->assertTrue($this->selenium->isTextPresent("Your listing has been published") || $this->selenium->isTextPresent('Check your inbox to validate your listing'),"Can post an item (all can post items). ERROR" ) ;
        } else if($bool == 1 && !$loginUser) {
            $this->selenium->open(osc_base_url(true) );
            // i need click twice, if not don't appear flash message
            $this->selenium->click("xpath=//a[text()='Publish your ad for free']");
            $this->selenium->waitForPageToLoad("30000");
            $this->selenium->click("xpath=//a[text()='Publish your ad for free']");
            $this->selenium->waitForPageToLoad("30000");
            $this->assertTrue($this->selenium->isTextPresent("Only registered users are allowed to post listings"),"No user can post a item. ERROR" ) ;
        } else if($bool == 1 && $loginUser) {
            $this->post_item_website();
            $this->assertTrue($this->selenium->isTextPresent("Your listing has been published") || $this->selenium->isTextPresent('Check your inbox to verify your listing'),"User cannot post an item. ERROR" ) ;
        }

        if($loginUser){
            // detele user and items
            $user = User::newInstance()->findByEmail($this->_email);
            User::newInstance()->deleteUser($user['pk_i_id']);
        } else {
            // delete items
            Item::newInstance()->delete(array( 's_contact_name' => 'foobar') );
        }
    }

    private function checkWebsite_recaptcha($bool)
    {
        // spam & boots -> fill  private & public keys
        $this->loginWith();
        $this->selenium->open( osc_admin_base_url(true) .'?page=settings&action=spamNbots' );
        $this->selenium->type('recaptchaPubKey', '6Lc5PsQSAAAAAEWQYBh5X7pepBL1FuYvdhEFTk0v') ;
        $this->selenium->type('recaptchaPrivKey' , '6Lc5PsQSAAAAADnbAmtxG_kfwIxPikL-mjSMyv22');
        $this->selenium->click("//input[@id='submit_recaptcha']");
        $this->selenium->waitForPageToLoad("10000");

        // test website
        $this->selenium->open( osc_item_post_url() );
        $exist_recaptcha = $this->selenium->isElementPresent("//table[@id='recaptcha_table']");

        // recaptcha enabled
        if($bool == 1){
            $this->assertTrue($exist_recaptcha, "Recaptcha is not present ! ERROR") ;
        // recaptcha disabled
        } else {
            $this->assertTrue(!$exist_recaptcha, "Recaptcha is present ! ERROR") ;
        }

        $this->loginWith();
        $this->selenium->open( osc_admin_base_url(true) .'?page=settings&action=spamNbots' );
        $this->selenium->type('recaptchaPubKey', '') ;
        $this->selenium->type('recaptchaPrivKey' , '');
        $this->selenium->click("//input[@id='submit_recaptcha']");
        $this->selenium->waitForPageToLoad("10000");
    }

    private function checkWebsite_moderate_items($moderation, $user = 1)
    {
        // create user
        $this->addUserForTesting();
        // loginWebsite
        $this->loginWebsite();

        $this->post_item_website();
        if($moderation == -1) {
            $this->assertTrue($this->selenium->isTextPresent("Your listing has been published"),"Item need validation moderate_items = -1 (NEVER MODERATE). ERROR" );
        } else if($moderation == 0 || $moderation == 1) {
            $this->assertTrue($this->selenium->isTextPresent("Check your inbox to validate your listing"),"Need validation but message don't appear") ;
            // fake validate item
            $user = User::newInstance()->findByEmail($this->_email);
            $new_i_item = $user['i_items']+1;
            User::newInstance()->update(array('i_items' => $new_i_item), array('pk_i_id' => $user['pk_i_id']));
        }

        $this->post_item_website();
        if($moderation == -1 || $moderation == 1) {
            $this->assertTrue($this->selenium->isTextPresent("Your listing has been published"),"Item need validation moderate_items = -1 (NEVER MODERATE). ERROR" );
        } else if($moderation == 0) {
            $this->assertTrue($this->selenium->isTextPresent("Check your inbox to validate your listing"),"Need validation but message don't appear" );
        }

        $user = User::newInstance()->findByEmail($this->_email);
        User::newInstance()->deleteUser($user['pk_i_id']);

    }

    private function checkWebsite_logged_user_item_validation($bool)
    {
        // create user
        $this->addUserForTesting();
        // loginWebsite
        $this->loginWebsite();
        // force validation
        Preference::newInstance()->replace('moderate_items', '0',"osclass", 'INTEGER') ;
        osc_reset_preferences();
        // add new item
        $this->post_item_website();

        if($bool == 0){
            $this->assertTrue($this->selenium->isTextPresent("Check your inbox to validate your listing"),"Need validation but message don't appear" );
        } else {
            $this->assertTrue($this->selenium->isTextPresent("Your listing has been published"),"Item need validation moderate_items = -1 (NEVER MODERATE). ERROR" );
        }

        $user = User::newInstance()->findByEmail($this->_email);
        User::newInstance()->deleteUser($user['pk_i_id']);
    }

    private function checkWebsite_items_wait_time($sec)
    {
        // create user
        $this->addUserForTesting();
        // loginWebsite
        $this->loginWebsite();
        Preference::newInstance()->replace('moderate_items', '-1',"osclass", 'INTEGER') ;
        osc_reset_preferences();
        if($sec == 0){
            $this->post_item_website();
            $this->assertTrue($this->selenium->isTextPresent("Your listing has been published"),"Cannot insert item. ERROR" );
            $this->post_item_website();
            $this->assertTrue($this->selenium->isTextPresent("Your listing has been published"),"Cannot insert item. ERROR" );
        } else if($sec > 0) {
            $this->post_item_website();
            $this->assertTrue($this->selenium->isTextPresent("Your listing has been published"),"Cannot insert item. ERROR" );
            $this->post_item_website();
            $this->assertTrue($this->selenium->isTextPresent("Too fast. You should wait a little to publish your ad."),"CAN insert item. ERROR" );
        }

        $user = User::newInstance()->findByEmail($this->_email);
        User::newInstance()->deleteUser($user['pk_i_id']);
    }

    private function checkWebsite_reg_user_can_contact($bool)
    {
        // create user
        $this->addUserForTesting();
        // loginWebsite
        $this->loginWebsite();
        Preference::newInstance()->replace('moderate_items', '-1',"osclass", 'INTEGER') ;
        osc_reset_preferences();

        $this->post_item_website();
        // ir a search

        $this->selenium->open( osc_base_url(true) );
        $this->selenium->click('link=Logout');
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->open( osc_search_url() );
        // visit fisrt item
        $this->selenium->click('link=foo title');
        $this->selenium->waitForPageToLoad("10000");

        $div_present = $this->selenium->isElementPresent("xpath=//div[@id='contact']/form[@name='contact_form']");

        if($bool == 1){
            $this->assertFalse($div_present, "There are form contact_form form. ERROR");
        } else if($bool == 0) {
            $this->assertTrue($div_present, "There aren't form contact_form form. ERROR");
        }

        $user = User::newInstance()->findByEmail($this->_email);
        User::newInstance()->deleteUser($user['pk_i_id']);
    }

    private function checkWebsite_enableField_f_price_items( $bool )
    {
        $this->addUserForTesting();
        // loginWebsite
        $this->loginWebsite();
        Preference::newInstance()->replace('moderate_items', '-1',"osclass", 'INTEGER') ;
        osc_reset_preferences();
        // check item_post()
        $this->selenium->open( osc_item_post_url() );
        $exist_input_price = $this->selenium->isElementPresent("xpath=//input[@id='price']") ;

        if($bool == 1){
            $this->assertTrue($exist_input_price, "Not exist input price!. ERROR");
        } else {
            $this->assertTrue(!$exist_input_price, "Exist input price!. ERROR");
        }
        // insert item
        $this->post_item_website();

        $this->selenium->open( osc_search_url() );
        // visit fisrt item
        $this->selenium->click('link=foo title');
        $this->selenium->waitForPageToLoad("10000");

        $exist_span_price = $this->selenium->isElementPresent("xpath=//span[@class='price']") ;

        if($bool == 1) { //muestra precio
            $this->assertTrue($exist_span_price , "Not exist span price!. ERROR");
        } else {
            $this->assertTrue( !$exist_span_price , "Exist span price!. ERROR");
        }
        $user = User::newInstance()->findByEmail($this->_email);
        User::newInstance()->deleteUser($user['pk_i_id']);
    }

    private function checkWebsite_enableField_images_items($bool, $num=0)
    {
        // crear user
        $this->addUserForTesting();
        // logear con user
        $this->loginWebsite();
        // entrar en la pag de post_item
        $this->selenium->open( osc_item_post_url() );
        $exist_input_photo = $this->selenium->isElementPresent("xpath=//input[@name='photos[]']") ;
        if($bool == 1) {
            $this->assertTrue($exist_input_photo, "Not exist input photos[]. ERROR");
        } else if ($bool == 0){
            $this->assertTrue( !$exist_input_photo, "Exist input photos[]. ERROR");
        }
        if($num>0){
            $this->selenium->open( osc_item_post_url() );
            for($i = 0;$i < $num; $i++)
                $this->selenium->click('link=Add new photo');

            $num_photo_input = (int)$this->selenium->getXpathCount("//input[@name='photos[]']") ;

            $this->assertTrue(($num == $num_photo_input), "More or less input photos[]! ERROR") ;
        }
        $user = User::newInstance()->findByEmail($this->_email);
        User::newInstance()->deleteUser($user['pk_i_id']);
    }

    private function settings_()
    {
        $pref = $this->getPreferencesItems();

        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("//a[@id='items_settings']");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->click("enabled_recaptcha_items");
        if( $pref['moderate_items'] == -1) {
            $this->selenium->click("moderate_items");
        }

        $this->selenium->type("num_moderate_items",'111');

        $this->selenium->type("items_wait_time", '120' );

        $this->selenium->click("logged_user_item_validation");
        $this->selenium->click("reg_user_post");
        $this->selenium->click("notify_new_item");
        $this->selenium->click("notify_contact_item");
        $this->selenium->click("notify_contact_friends");
        $this->selenium->click("enableField#f_price@items");
        $this->selenium->click("enableField#images@items");

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("Items' settings have been updated") , "Can't update items settings. ERROR");

        if( $pref['enabled_item_validation'] == 'on' ) {
            $this->assertEqual( $this->selenium->getValue('num_moderate_items'), '111' ) ;
        }
        $this->assertEqual( $this->selenium->getValue('items_wait_time'), '120' ) ;
        if( $pref['enabled_recaptcha_items'] == 'on' ){     $this->assertEqual( $this->selenium->getValue('enabled_recaptcha_items'), 'off' ) ;
        } else {                                            $this->assertEqual( $this->selenium->getValue('enabled_recaptcha_items'), 'on' ) ;}
        if( $pref['logged_user_item_validation'] == 'on' ){ $this->assertEqual( $this->selenium->getValue('logged_user_item_validation'), 'off' ) ;
        } else {                                            $this->assertEqual( $this->selenium->getValue('logged_user_item_validation'), 'on' ) ;}
        if( $pref['reg_user_post'] == 'on' ){               $this->assertEqual( $this->selenium->getValue('reg_user_post'), 'off' ) ;
        } else {                                            $this->assertEqual( $this->selenium->getValue('reg_user_post'), 'on' ) ;}
        if( $pref['notify_new_item'] == 'on' ){             $this->assertEqual( $this->selenium->getValue('notify_new_item'), 'off' ) ;
        } else {                                            $this->assertEqual( $this->selenium->getValue('notify_new_item'), 'on' ) ;}
        if( $pref['notify_contact_item'] == 'on' ){         $this->assertEqual( $this->selenium->getValue('notify_contact_item'), 'off' ) ;
        } else {                                            $this->assertEqual( $this->selenium->getValue('notify_contact_item'), 'on' ) ;}
        if( $pref['notify_contact_friends'] == 'on' ){      $this->assertEqual( $this->selenium->getValue('notify_contact_friends'), 'off' ) ;
        } else {                                            $this->assertEqual( $this->selenium->getValue('notify_contact_friends'), 'on' ) ;}
        if( $pref['enableField#f_price@items'] == 'on' ){   $this->assertEqual( $this->selenium->getValue('enableField#f_price@items'), 'off' ) ;
        } else {                                            $this->assertEqual( $this->selenium->getValue('enableField#f_price@items'), 'on' ) ;}
        if( $pref['enableField#images@items'] == 'on' ){    $this->assertEqual( $this->selenium->getValue('enableField#images@items'), 'off' ) ;
        } else {                                            $this->assertEqual( $this->selenium->getValue('enableField#images@items'), 'on' ) ;}

        $this->selenium->click("enabled_recaptcha_items");
        $this->selenium->click("logged_user_item_validation");
        $this->selenium->click("reg_user_post");
        $this->selenium->click("notify_new_item");
        $this->selenium->click("notify_contact_item");
        $this->selenium->click("notify_contact_friends");
        $this->selenium->click("enableField#f_price@items");
        $this->selenium->click("enableField#images@items");
        if( $pref['moderate_items'] == -1) {
            $this->selenium->type("num_moderate_items", $pref['num_moderate_items'] );
            $this->selenium->click("moderate_items");
        }
        $this->selenium->type("num_moderate_items", $pref['num_moderate_items'] );
        $this->selenium->type("items_wait_time", $pref['items_wait_time'] );

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("Items' settings have been updated") , "Can't update items settings. ERROR");

        $this->assertEqual( $this->selenium->getValue('enabled_recaptcha_items')        , $pref['enabled_recaptcha_items']) ;
        $this->assertEqual( $this->selenium->getValue('logged_user_item_validation')    , $pref['logged_user_item_validation'] ) ;
        $this->assertEqual( $this->selenium->getValue('reg_user_post')                  , $pref['reg_user_post'] ) ;
        $this->assertEqual( $this->selenium->getValue('notify_new_item')                , $pref['notify_new_item'] ) ;
        $this->assertEqual( $this->selenium->getValue('notify_contact_item')            , $pref['notify_contact_item'] ) ;
        $this->assertEqual( $this->selenium->getValue('notify_contact_friends')         , $pref['notify_contact_friends'] ) ;
        $this->assertEqual( $this->selenium->getValue('enableField#f_price@items')      , $pref['enableField#f_price@items']  ) ;
        $this->assertEqual( $this->selenium->getValue('enableField#images@items')       , $pref['enableField#images@items'] ) ;

        $this->assertEqual( $this->selenium->getValue('items_wait_time')                , $pref['items_wait_time'] ) ;
        $this->assertEqual( Preference::newInstance()->findValueByName('moderate_items'), $pref['num_moderate_items'] ) ;

        unset($pref);
        osc_reset_preferences();

    }

    private function getPreferencesItems()
    {
        osc_reset_preferences();
        $pref = array();
        $pref['enabled_recaptcha_items']        = Preference::newInstance()->findValueByName('enabled_recaptcha_items') ;
        $pref['enabled_item_validation']        = Preference::newInstance()->findValueByName('enabled_item_validation') ;
        $pref['logged_user_item_validation']    = Preference::newInstance()->findValueByName('logged_user_item_validation') ;
        $pref['reg_user_post']                  = Preference::newInstance()->findValueByName('reg_user_post') ;
        $pref['notify_new_item']                = Preference::newInstance()->findValueByName('notify_new_item') ;
        $pref['notify_contact_item']            = Preference::newInstance()->findValueByName('notify_contact_item') ;
        $pref['notify_contact_friends']         = Preference::newInstance()->findValueByName('notify_contact_friends') ;
        $pref['enableField#f_price@items']      = Preference::newInstance()->findValueByName('enableField#f_price@items') ;
        $pref['enableField#images@items']       = Preference::newInstance()->findValueByName('enableField#images@items') ;

        $pref['num_moderate_items']             = Preference::newInstance()->findValueByName('moderate_items') ;
        $pref['moderate_items']                 = Preference::newInstance()->findValueByName('moderate_items') ;
        $pref['items_wait_time']                = Preference::newInstance()->findValueByName('items_wait_time') ;

        if($pref['enabled_recaptcha_items'] == 1){  $pref['enabled_recaptcha_items'] = 'on'; }
        else {                                      $pref['enabled_recaptcha_items'] = 'off'; }
        if($pref['reg_user_post'] == 1){            $pref['reg_user_post']          = 'on'; }
        else {                                      $pref['reg_user_post']          = 'off'; }
        if($pref['notify_new_item'] == 1){          $pref['notify_new_item']        = 'on';}
        else {                                      $pref['notify_new_item']        = 'off'; }
        if($pref['notify_contact_item'] == 1){      $pref['notify_contact_item']    = 'on';}
        else {                                      $pref['notify_contact_item']    = 'off'; }
        if($pref['notify_contact_friends'] == 1){   $pref['notify_contact_friends'] = 'on';}
        else {                                      $pref['notify_contact_friends'] = 'off'; }
        if($pref['enableField#f_price@items'] == 1){$pref['enableField#f_price@items'] = 'on';}
        else {                                      $pref['enableField#f_price@items'] = 'off'; }
        if($pref['enableField#images@items'] == 1){ $pref['enableField#images@items'] = 'on';}
        else {                                      $pref['enableField#images@items'] = 'off'; }
        if($pref['logged_user_item_validation'] == 1){  $pref['logged_user_item_validation'] = 'on';}
        else {                                          $pref['logged_user_item_validation'] = 'off'; }

        return $pref;
    }
}
?>
