<?php

require_once('util_settings.php');

require_once('../../autorun.php');
require_once('../../web_tester.php');
require_once('../../reporter.php');

// LOAD OSCLASS
require_once '../../../../oc-load.php';
require_once LIB_PATH . 'Selenium.php';

class TestOfItems extends WebTestCase {

    private $selenium;
    private $mail;
    private $password;
    private $email_fixed;
    private $array;
    private $logged;
    
    private $set_selectable_parent_categories;
    
    private $to_delete = array();

    function setUp()
    {
        $conn = getConnection();        

        echo "<br><div style='background-color: Wheat; color: black;'>init test</div>";

        $this->selenium = new Testing_Selenium("*firefox", "http://localhost/");
        $this->selenium->start();
        $this->selenium->setSpeed("150");
    }

    function tearDown()
    {
        $this->selenium->stop();
        echo "<div style='background-color: Wheat; color: black;'>end test</div>";
        flush();
    }

    function  __construct() {
        
    }

    /*           TESTS          */
    function testInitial()
    {
        echo "insert new user for testing<br>";
        $input['s_secret']          = osc_genRandomPassword() ;
        $input['dt_reg_date']       = DB_FUNC_NOW ;
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
        $input['s_email']           = "carlos+user@osclass.org";
        $this->email                = "carlos+user@osclass.org";
        $input['s_password']        = sha1('carlos');
        $this->password             = "carlos";

        $this->array = $input;

        User::newInstance()->insert($input) ;
        $input['s_email']           = "carlos+test@osclass.org";
        $this->email_fixed          = "carlos+test@osclass.org";
        User::newInstance()->insert($input) ;
    }
    /**
     * insert new item
     * Comprobar:
     *  osc_reg_user_post() => Only allow registered users to post items
     *  osc_users_enabled() => Users not enabled
     *
     * REQUIRE: user logged in
     */
    function testItemInsert()
    {
        echo "<div style='background-color: green; color: white;'><h2>test_frontend_items - testItemInsert</h2></div>";
/*
 *         TEST WITH NO LOGGED USER
 */
        $uSettings = new utilSettings();
        $this->items_wait_time              = $uSettings->set_items_wait_time(0);
        $this->set_selectable_parent_categories = $uSettings->set_selectable_parent_categories(1);
        $mItem = new Item();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>NO USER - </div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>NO USER - YES, CAN PUBLISH ITEMS</div>";
        $this->bool_reg_user_post           = $uSettings->set_reg_user_post(0);
        $this->bool_enabled_user_validation = $uSettings->set_moderate_items(-1);
        echo "<div style='background-color: green; color: white;padding-left:15px;'>NO USER - WITHOUT VALIDATION MAIL</div>";
        $this->insertItem();

        $this->bool_enabled_user_validation = $uSettings->set_moderate_items(111);
        echo "<div style='background-color: green; color: white;padding-left:15px;'>NO USER - WITH VALIDATION MAIL</div>";
        $this->insertItem();

        $this->bool_reg_user_post           = $uSettings->set_reg_user_post(1);
        echo "<div style='background-color: green; color: white;padding-left:15px;'>NO USER - NO, CAN'T PUBLISH ITEMS</div>";
        $this->insertItem();

/*
 *         TEST WITH LOGGED USER
 */
        
        echo "<div style='background-color: green; color: white;padding-left:15px;'>USER LOGGED IN - </div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'></div>";
        $uSettings->set_logged_user_item_validation(1);
        echo "<div style='background-color: green; color: white;padding-left:15px;'>USER LOGGED IN - WITHOUT VALIDATION MAIL</div>";
        $this->login();
        $this->insertItem();
        flush();
        $uSettings->set_logged_user_item_validation(0);
        echo "<div style='background-color: green; color: white;padding-left:15px;'>USER LOGGED IN - WITH VALIDATION MAIL</div>";
        $this->login();
        $this->insertItem();
        flush();

        echo "<div style='background-color: green; color: white;padding-left:15px;'>Deleting inserted item</div>";

        $item = $mItem->findByConditions( array('s_contact_email' => 'carlos+usertest@osclass.org') ) ;
        while( $item ) {
            echo "deleting item ... <br>";
            flush();
            $this->deleteItemUrl( osc_item_delete_url( $item['s_secret'] , $item['pk_i_id'] ) );
            flush();
            $item = $mItem->findByConditions( array('s_contact_email' => 'carlos+usertest@osclass.org') ) ;
            flush();
        }
        
    }

    function testEditUserItemBadId()
    {
        echo "<div style='background-color: green; color: white;'><h2>testEditUserItemBadId</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>Trying go to bad item url.</div>";
        $this->editUserItemBadId();
    }

    function testActivate() // Activate
    {
        echo "<div style='background-color: green; color: white;'><h2>testActivate</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>Log in user ...</div>";
        $this->login();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>Activating first item</div>";
        $this->activateUserItem();
    }

    function testEditItem()
    {
        echo "<div style='background-color: green; color: white;'><h2>testEditItem</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>Log in user ...</div>";
        $this->login();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>Editing first item</div>";
        $this->editUserItem();
    }


    function testDeleteItem()
    {
        echo "<div style='background-color: green; color: white;'><h2>testDeleteItem</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>Log in user ...</div>";
        $this->login();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>Deleting first item in the list</div>";
        $this->deleteItem();
    }

    function  testdeleteUser()
    {
        echo "delete user for testing<br>";
        $user = User::newInstance()->findByEmail($this->email);
        User::newInstance()->deleteUser($user['pk_i_id']);
        $user = User::newInstance()->findByEmail($this->email_fixed);
        User::newInstance()->deleteUser($user['pk_i_id']);
        $this->assertTrue(TRUE);
    }
    
    /*
     * PRIVATE FUNCTIONS
     */
    private function login()
    {
        $this->selenium->open( osc_base_url(true) );
        $this->selenium->click("login_open");
        $this->selenium->type("email"   , $this->email);
        $this->selenium->type("password", $this->password);

        $this->selenium->click("//button[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");

        echo "<div style='background-color: green; color: white;padding-left:15px;'>Login user ...</div>";
        if($this->selenium->isTextPresent("User account manager")){
            $this->logged = 1;
            $this->assertTrue("ok");
        }
    }

    private function insertItem()
    {
        $reg_user_post               = Preference::newInstance()->findValueByName('reg_user_post');
        $moderate_items              = Preference::newInstance()->findValueByName('moderate_items');
        $logged_user_item_validation = Preference::newInstance()->findValueByName('logged_user_item_validation');

//        if($reg_user_post) {echo "reg_user_post == true<br>";}else{echo "reg_user_post == false<br>";}
//        if($enabled_item_validation) {echo "enabled_item_validation == true<br>";}else{echo "enabled_item_validation == false<br>";}
//        if($logged_user_item_validation) {echo "logged_user_item_validation == true NO HAY QUE VALIDAR<br>";}else{echo "logged_user_item_validation == false HAY QUE VALIDAR<br>";}

        flush();

        $this->selenium->open( osc_base_url(true) );
        
        $this->selenium->click("link=Publish your ad for free");
        $this->selenium->waitForPageToLoad("30000");

        if( $this->logged == 0 && $reg_user_post ) {
            echo "<div style='background-color: green; color: white;padding-left:15px;'>Only registered users are allowed to post items</div>";
            $this->assertTrue($this->selenium->isTextPresent("Only registered users are allowed to post items"), "Allow no users to post items .reg_user_post == true. ERROR ");
        } else {

            $this->selenium->select("catId", "label=regexp:\\s*Cars");

            $this->selenium->type("title[en_US]", "title new item");
            $this->selenium->type("description[en_US]", "description new item new item new item");
            $this->selenium->type("price", "111");

            $this->selenium->select("currency", "label=Euro €");

            $this->selenium->select("regionId", "label=Barcelona");
            $this->selenium->select("cityId", "label=Sabadell");
            $this->selenium->type("cityArea", "my area");
            $this->selenium->type("address", "my address");

            $this->selenium->type("photos[]", LIB_PATH."simpletest/test/osclass/img_test1.gif");
            $this->selenium->click("link=Add new photo");
            $this->selenium->type("//div[@id='p-0']/input",  LIB_PATH."simpletest/test/osclass/img_test2.gif");

            if($this->logged == 0){
                $this->selenium->type("contactName", "carlos");
                $this->selenium->type("contactEmail", "carlos+usertest@osclass.org");
            }
            
            $this->selenium->click("//button[text()='Publish']");
            $this->selenium->waitForPageToLoad("30000");

            echo "< ".$this->selenium->getText('//*[@id="FlashMessage"]')." ><br>";

            if( $this->logged == 0 ){
                if($moderate_items > 0){
//                    echo "<div style='background-color: green; color: white;padding-left:15px;'>No user and need validation item - Great! You'll receive an e-mail to activate your item</div>";
                    $this->assertTrue($this->selenium->isTextPresent("Check your inbox to verify your email address","Need validation but message don't appear") );
                } else {
//                    echo "<div style='background-color: green; color: white;padding-left:15px;'>Great! We've just published your item</div>";
                    $this->assertTrue($this->selenium->isTextPresent("Your post has been published","no logged in error inserting ad.") );
                }
            } else {
                if($logged_user_item_validation){
//                    echo "<div style='background-color: green; color: white;padding-left:15px;'>without validation. Great! We've just published your item</div>";
                    $this->assertTrue($this->selenium->isTextPresent("Your post has been published","insert ad error ") );
                } else {
//                    echo "<div style='background-color: green; color: white;padding-left:15px;'>Registered user and need validation item - Great! You'll receive an e-mail to activate your item</div>";
                    $this->assertTrue($this->selenium->isTextPresent("Check your inbox to verify your email address","Need validation but message don't appear") );
                }
            }
            return 1;
        }
        return 0;
    }

    
    
    
    private function editUserItemBadId()
    {
        $this->selenium->open( osc_base_url(true) . "?page=item&action=item_edit&id=9999" );
        echo "<div style='background-color: green; color: white;padding-left:15px;'>Sorry, we don't have any items with that ID</div>";
        $this->assertTrue($this->selenium->isTextPresent("Sorry, we don't have any items with that ID"));
    }

    private function editUserItem()
    {
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

        $this->selenium->select("regionId", "label=Barcelona");
        $this->selenium->select("cityId", "label=Sabadell");
        $this->selenium->type("cityArea", "New my area");
        $this->selenium->type("address", "New my address");

        $this->selenium->click("//button[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");

        // if validation required or not -> update ok
        echo "<div style='background-color: green; color: white;padding-left:15px;'>Great! We've just updated your item</div>";
        $this->assertTrue(  $this->selenium->isTextPresent("Great! We've just updated your item") ||
                            $this->selenium->isTextPresent("The item hasn't been validated. Please validate it in order to show it to the rest of users") ,
                        "Can't edit item user. ERROR!");
       
    }

    private function activateUserItem()
    {
        $this->selenium->open( osc_base_url(true) );

        $this->selenium->click("link=My account");
        $this->selenium->waitForPageToLoad("30000");

        $this->selenium->click("xpath=//ul/li/a[text()='Manage your items']");
        $this->selenium->waitForPageToLoad("30000");
        
        // delete first item
        $this->selenium->click("xpath=//div[@class='item']/p/a[text()='Activate']");
        $this->selenium->waitForPageToLoad("30000");
        $this->assertTrue($this->selenium->isTextPresent("The item has been validated"), "Can't validate item. ERROR ");
    }

    private function deleteItem()
    {
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
    }

    private function deleteItemUrl($url)
    {
        echo "URL -> $url<br>";
        $this->selenium->open( $url );
        $this->assertTrue($this->selenium->isTextPresent("Your item has been deleted"), "Can't delete item. ERROR ");
    }
}
?>
