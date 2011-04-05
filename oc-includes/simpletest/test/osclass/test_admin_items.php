<?php
require_once('../../autorun.php');
require_once('../../web_tester.php');
require_once('../../reporter.php');

// LOAD OSCLASS
require_once '../../../../oc-load.php';
require_once LIB_PATH . 'Selenium.php';

class TestOfAdminItems extends WebTestCase {

    private $selenium;

    function setUp()
    {
        $conn = getConnection();
        $conn->osc_dbExec(sprintf("INSERT INTO `%st_admin` (`s_name` ,`s_username` ,`s_password` ,`s_secret` ,`s_email`) VALUES ('Test Admin','testadmin','5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8','mvqdnrpt','testadmin@test.net')", DB_TABLE_PREFIX));

        echo "<br><div style='background-color: Wheat; color: black;'>init test</div>";

        $this->selenium = new Testing_Selenium("*firefox", "http://localhost/");
        $this->selenium->start();
        $this->selenium->setSpeed("150");
    }

    function tearDown()
    {
        $this->selenium->stop();
        $admin = Admin::newInstance()->findByEmail('testadmin@test.net');
        Admin::newInstance()->delete(array('pk_i_id' =>$admin['pk_i_id']));
        echo "<div style='background-color: Wheat; color: black;'>end test</div>";
        flush();
    }
    /*           TESTS          */

    function testInsertItem()
    {
        echo "<div style='background-color: green; color: white;'><h2>testInsertItem</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testInsertItem - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testInsertItem - ADD ITEM</div>";
        $this->insertItem() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testInsertItem - NO MEDIA/ NO COMMENTS</div>";
        $this->viewMedia_NoMedia();
        $this->viewComments_NoComments();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testInsertItem - ACTIVATE/DEACTIVATE</div>";
        $this->deactivate();
        $this->activate();
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testInsertItem - MARK/UNMARK AS PREMIUM</div>";
        $this->markAsPremium();
        $this->unmarkAsPremium();
        flush();
    }

    function testEditItem()
    {
        echo "<div style='background-color: green; color: white;'><h2>testEditItem</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testEditItem - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testEditItem - EDIT ITEM</div>";
        $this->editItem() ;
        flush();
    }

    function testDeleteItem()
    {
        echo "<div style='background-color: green; color: white;'><h2>testDeleteItem</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testDeleteItem - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testDeleteItem - DELETE ITEM</div>";
        $this->deleteItem() ;
        flush();
    }

    function testComments()
    {
        echo "<div style='background-color: green; color: white;'><h2>testComments</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testComments - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testComments - INSERT ITEM AND COMMENTS TESTS</div>";
        $this->insertItemAndComments() ;
        flush();
    }

    function testMedia()
    {
        echo "<div style='background-color: green; color: white;'><h2>testMedia</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testMedia - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testMedia - MEDIA ITEM</div>";
        $this->insertItemAndMedia() ;
        flush();
    }
    
     /*      PRIVATE FUNCTIONS       */
    private function loginCorrect()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->waitForPageToLoad(10000);

        // if you are logged fo log out
        if( $this->selenium->isTextPresent('Log Out') ){
            $this->selenium->click('Log Out');
            $this->selenium->waitForPageToLoad(1000);
        }

        $this->selenium->type('user', 'testadmin');
        $this->selenium->type('password', 'password');
        $this->selenium->click('submit');
        $this->selenium->waitForPageToLoad(1000);

        if( !$this->selenium->isTextPresent('Log in') ){
            $this->assertTrue("todo bien");
        } else {
            $this->assertFalse("can't loggin");
        }
    }

    // todo test minim lenght title, description , contact email
    private function insertItem($bPhotos = FALSE )
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Items");
        $this->selenium->click("link=» Add new item");
        $this->selenium->waitForPageToLoad("10000");

        // insert non registered user
        $this->selenium->type("contactName" , "contact name");
        $this->selenium->type("contactEmail", "test@mail.com");

        $this->selenium->select("catId", "label=Cars");
        $this->selenium->type("title[en_US]", "title item");
        $this->selenium->type("description[en_US]", "description test description test description test");
        $this->selenium->type("price", "11");
        $this->selenium->select("currency", "label=Euro €");
        $this->selenium->select("regionId", "label=A Coruña");
        $this->selenium->select("cityId", "label=A Capela");
        $this->selenium->type("address", "address item");

        if( $bPhotos ){
            $this->selenium->type("photos[]", LIB_PATH."simpletest/test/osclass/img_test1.gif");
            $this->selenium->click("link=Add new photo");
            $this->selenium->type("//div[@id='p-0']/input", LIB_PATH."simpletest/test/osclass/img_test2.gif");
        }
        
        $this->selenium->click("//button[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");
        
        $this->assertTrue($this->selenium->isTextPresent("A new item has been added"), "Can't insert a new item. ERROR");
    }

    private function viewMedia_NoMedia()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Items");
        $this->selenium->click("link=» Manage items");
        $this->selenium->waitForPageToLoad("10000");

        sleep(2); // time enough to load table data

        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'title item')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'title item')]/td/span/a[text()='View media']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("No matching records found"), "Show media when there aren't. ERROR");
    }

    private function viewComments_NoComments()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Items");
        $this->selenium->click("link=» Manage items");
        $this->selenium->waitForPageToLoad("10000");

        sleep(2); // time enough to load table data

        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'title item')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'title item')]/td/span/a[text()='View comments']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("No matching records found"), "Show media when there aren't. ERROR");
    }

    private function deactivate()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Items");
        $this->selenium->click("link=» Manage items");
        $this->selenium->waitForPageToLoad("10000");

        sleep(2); // time enough to load table data

        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'title item')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'title item')]/td/span/a[text()='Deactivate']");
        $this->selenium->waitForPageToLoad("10000");
        
        $this->assertTrue($this->selenium->isTextPresent("The item has been deactivated"), "Can't deactivate item. ERROR");
    }

    private function activate()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Items");
        $this->selenium->click("link=» Manage items");
        $this->selenium->waitForPageToLoad("10000");

        sleep(2); // time enough to load table data

        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'title item')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'title item')]/td/span/a[text()='Activate']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("The item has been activated"), "Can't activate item. ERROR");
    }

    private function markAsPremium()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Items");
        $this->selenium->click("link=» Manage items");
        $this->selenium->waitForPageToLoad("10000");

        sleep(2); // time enough to load table data

        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'title item')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'title item')]/td/span/a[text()='Mark as premium']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("Changes have been applied"), "Can't mark as premium item. ERROR");
    }
    
    private function unmarkAsPremium()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Items");
        $this->selenium->click("link=» Manage items");
        $this->selenium->waitForPageToLoad("10000");

        sleep(2); // time enough to load table data

        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'title item')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'title item')]/td/span/a[text()='Unmark as premium']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("Changes have been applied"), "Can't mark as premium item. ERROR");
    }


    private function editItem()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Items");
        $this->selenium->click("link=» Manage items");
        $this->selenium->waitForPageToLoad("10000");

        sleep(2); // time enough to load table data

        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'title item')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'title item')]/td/span/a[text()='Edit']");
        $this->selenium->waitForPageToLoad("10000");

        // insert non registered user
        $this->selenium->type("contactName" , "contact name_");
        $this->selenium->type("contactEmail", "test_@mail.com");

        $this->selenium->select("catId", "label=Cars");
        $this->selenium->type("title[en_US]", "title_item");
        $this->selenium->type("description[en_US]", "description_test_description test description_test");
        $this->selenium->type("price", "11");
        $this->selenium->select("currency", "label=Euro €");
        $this->selenium->select("regionId", "label=A Coruña");
        $this->selenium->select("cityId", "label=A Capela");
        $this->selenium->type("address", "address_item");

        $this->selenium->click("//button[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("Changes saved correctly"), "Can't edit item. ERROR");
    }

    private function deleteItem()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Items");
        $this->selenium->click("link=» Manage items");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'title_item')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'title_item')]/td/span/a[text()='Delete']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("The item has been deleted"), "Can't delete item. ERROR");
    }

    private function insertItemAndComments()
    {
        // insert item
        $this->insertItem() ;

        $mItem = new Item();
        $item = $mItem->findByConditions( array('s_contact_email' => 'test@mail.com') );
        
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
        // insert comment from frontend
        echo "<".osc_item_url_ns( $item['pk_i_id'] )."><br>";

        $this->selenium->open(osc_item_url_ns( $item['pk_i_id'] ));

        $this->selenium->type("authorName"      , "carlos");
        $this->selenium->type("authorEmail"     , "carlos@osclass.org");
        $this->selenium->type("title"           , "I like it");
        $this->selenium->type("body"            , "Can you provide more info please :)");

        $this->selenium->click("//div[@id='comments']/form/fieldset/div/span/button");
        $this->selenium->waitForPageToLoad("30000");

        // test oc-admin
        $this->loginCorrect();

        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Items");
        $this->selenium->click("link=» Comments");
        $this->selenium->waitForPageToLoad("10000");

        echo "<div style='background-color: green; color: white;padding-left:15px;'>testComments - ACTIVATE COMMENT</div>";
        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'Can you provide more info please :)')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'Can you provide more info please :)')]/td/div/a[text()='Activate']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("The comment has been approved"), "Can't activate comment. ERROR" );

        echo "<div style='background-color: green; color: white;padding-left:15px;'>testComments - DEACTIVATE COMMENT</div>";
        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'Can you provide more info please :)')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'Can you provide more info please :)')]/td/div/a[text()='Deactivate']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("The comment has been disapproved"), "Can't deactivate comment. ERROR" );

        echo "<div style='background-color: green; color: white;padding-left:15px;'>testComments - EDIT COMMENT</div>";
        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'Can you provide more info please :)')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'Can you provide more info please :)')]/td/div/a[text()='Edit']");
        $this->selenium->waitForPageToLoad("10000");

        // edit comment
        $this->selenium->type("s_title", "I like it updated");
        $this->selenium->type("s_author_name", "carlos osclass");
        $this->selenium->type("s_body", "Can you provide more info please :) Regards");
        $this->selenium->click("//button[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");

        $this->assertTrue($this->selenium->isTextPresent("Great! We just updated your comment"), "Can't edit a comment. ERROR") ;

        echo "<div style='background-color: green; color: white;padding-left:15px;'>testComments - DELETE COMMENT</div>";
        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'Can you provide more info please :)')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'Can you provide more info please :)')]/td/div/a[text()='Delete']");
        $this->selenium->waitForPageToLoad("10000");
        
        $this->assertTrue($this->selenium->isTextPresent("The comment have been deleted"), "Can't delete a comment. ERROR") ;

        // DELETE ITEM
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Items");
        $this->selenium->click("link=» Manage items");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'title item')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'title item')]/td/span/a[text()='Delete']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("The item has been deleted"), "Can't delete item. ERROR");

        // restore prefereces values
        Preference::newInstance()->update(array('s_value' => $enabled_comments)
                                         ,array('s_name'  => 'enabled_comments'));
        Preference::newInstance()->update(array('s_value' => $moderate_comments)
                                         ,array('s_name'  => 'moderate_comments'));
    }

    private function insertItemAndMedia()
    {
        // insert item
        $this->insertItem( TRUE ) ;

        $mItem = new Item();
        $item = $mItem->findByConditions( array('s_contact_email' => 'test@mail.com') );

        // test oc-admin
        $this->loginCorrect();

        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Items");
        $this->selenium->click("link=» Manage media");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("Showing 1 to 2 of 2 entries"), "Can't activate comment. ERROR" );
        // only can delete resources
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testMedia - MEDIA DELETE</div>";
        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'image/png')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'image/png')]/td/div/span/a[text()='Delete']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("Resource deleted"), "Can't delete media. ERROR" );
        $this->assertTrue($this->selenium->isTextPresent("Showing 1 to 1 of 1 entries"), "Can't delete media. ERROR" );

        echo "<div style='background-color: green; color: white;padding-left:15px;'>testMedia - MEDIA DELETE</div>";
        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'image/png')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'image/png')]/td/div/span/a[text()='Delete']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("Resource deleted"), "Can't delete media. ERROR" );
        $this->assertTrue($this->selenium->isTextPresent("Showing 0 to 0 of 0 entries"), "Can't delete media. ERROR" );

        // DELETE ITEM
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Items");
        $this->selenium->click("link=» Manage items");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'title item')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'title item')]/td/span/a[text()='Delete']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("The item has been deleted"), "Can't delete item. ERROR");
    }
}

?>
