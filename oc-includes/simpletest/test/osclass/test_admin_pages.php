<?php
require_once('../../autorun.php');
require_once('../../web_tester.php');
require_once('../../reporter.php');

// LOAD OSCLASS
require_once '../../../../oc-load.php';
require_once LIB_PATH . 'Selenium.php';

class TestOfAdminPages extends WebTestCase {

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

    /**
     * insert new page
     * REQUIRE: user logged in
     */
    function testPagesInsert()
    {
        echo "<div style='background-color: green; color: white;'>Admin - <h2>Page insert</h2> -</div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>Admin - Page insert - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>Admin - Page insert - CREATE A NEW PAGE</div>";
        $this->createPage('test_page_example') ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>Admin - Page insert - DELETE INSERTED PAGE</div>";
        $this->deletePage('test_page_example') ;
        flush();
    }
    
    /**
     * insert new page twice
     * REQUIRE: user logged in
     */
    function testPagesInsertDuplicate()
    {
        echo "<div style='background-color: green; color: white;'>Admin - <h2>Page insert duplicate</h2> -</div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>Admin - Page insert duplicate - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>Admin - Page insert duplicate - CREATE A NEW PAGE</div>";
        $this->createPage('test_page_example') ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>Admin - Page insert duplicate - CREATE A NEW PAGE (same as previous)</div>";
        $this->createPageAgain('test_page_example') ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>Admin - Page insert duplicate - DELETE A FIRST PAGE</div>";
        $this->deletePage('test_page_example') ;
        flush();
    }

    /**
     * insert a new pages and edit it.
     */
    function testPageEdit()
    {
        echo "<div style='background-color: green; color: white;'>Admin - <h2>Page edit</h2> -</div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>Admin - Page edit - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>Admin - Page edit - CREATE A NEW PAGE</div>";
        $this->createPage('test_page_example') ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>Admin - Page edit - EDIT NEW PAGE</div>";
        $this->editPage('test_page_example') ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>Admin - Page edit - DELETE A FIRST PAGE</div>";
        $this->deletePage('test_page_example') ;
    }

    /**
     * insert 10 new pages and delete all the pages.
     */
    function testMultiplePagesInsert()
    {
        echo "<div style='background-color: green; color: white;'>Admin - <h2>Page insert multiples pages</h2> -</div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>Admin - Page insert multiples pages - LOGIN </div>";
        $this->loginCorrect() ;
        flush();

        $count = 0;
        while( $count < 10 ) {
            echo "<div style='background-color: green; color: white;padding-left:15px;'>Admin - Page insert multiples pages - CREATE A NEW PAGE test_page_example$count</div>";
            $this->createPage('test_page_example'.$count) ;
            $count++;
            flush();
        }
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>Admin - Page insert multiples pages - DELETE 5 PAGES THROUGH CHECKBOX</div>";
        $this->selectAndDelete('test_page_example', 0, 5);
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>Admin - Page insert multiples pages - DELETE ALL PAGES VIA CHECKBOX (SELECT ALL)</div>";
        $this->selectAllAndDelete();

    }

//    function testSearch()
//    {
//        echo "<div style='background-color: green; color: white;'>Admin - Table search -</div>";
//        echo "<div style='background-color: green; color: white;padding-left:15px;'>Admin - Table search - LOGIN </div>";
//        $this->loginCorrect() ;
//        flush();
//
//        echo "<div style='background-color: green; color: white;padding-left:15px;'>Admin - Table search - TESTING TABLE SEARCH</div>";
//        $this->tableSearch();
//        flush();
//    }

    public function testTableNavigation()
    {
        echo "<div style='background-color: green; color: white;'>Admin - <h2>Table Navigation</h2> -</div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>Admin - Table Navigation - LOGIN </div>";
        $this->loginCorrect() ;
        flush();

        $count = 0;
        while( $count < 15 ) {
            echo "<div style='background-color: green; color: white;padding-left:15px;'>Admin - Table Navigation - CREATE A NEW PAGE test_page_example$count</div>";
            $this->createPage('test_page_example'.$count) ;
            $count++;
            flush();
        }

        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Pages");
        $this->selenium->click("link=» Manage pages");
        $this->selenium->waitForPageToLoad(500);

        echo "<div style='background-color: green; color: white;padding-left:15px;'>Admin - Table Navigation - check row count</div>";
        $res = $this->selenium->getXpathCount("//table[@id='datatables_list']/tbody/tr");
        $this->assertEqual(10, $res,"10 rows does not appear [$res]");

        $this->selenium->click("//span[@class='next paginate_button']");
        $this->selenium->waitForPageToLoad(500);

        echo "<div style='background-color: green; color: white;padding-left:15px;'>Admin - Table Navigation - check row count (when previously go to next page)</div>";
        $res = $this->selenium->getXpathCount("//table[@id='datatables_list']/tbody/tr");
        $this->assertEqual(5, $res,"5 rows does not appear [$res]");

        // two pages
        $this->selectAllAndDelete();
        $this->selectAllAndDelete();
    }

    /*
     * PRIVATE FUNCTIONS
     */
    private function loginCorrect()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->waitForPageToLoad(10000);

        // if you are logged fo log out
        if( $this->selenium->isTextPresent('Log Out') ){
            $this->selenium->click('Log Out');
            $this->selenium->waitForPageToLoad(1000);
        }
        
        $this->selenium->type('user', 'admin');
        $this->selenium->type('password', 'xdf9emho');
        $this->selenium->click('submit');
        $this->selenium->waitForPageToLoad(1000);
        
        if( !$this->selenium->isTextPresent('Log in') ){
            $this->assertTrue("todo bien");
        } else {
            $this->assertFalse("can't loggin");
        }
    }

    private function loginIncorrect()
    {
        // TEST CORRECT LOGIN
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->type('user', 'testadmin');
        $this->selenium->type('password', '_password_');
        $this->selenium->click('submit');
        $this->selenium->waitForPageToLoad(1000);

        if( $this->selenium->isTextPresent('The password is incorrect') ){
            $this->assertTrue("todo ok");
        } else {
            $this->assertFalse("FM - The password is incorrect - NOT APPEAR");
        }
    }

    private function newPage($internal_name)
    {
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->click("link=Pages");
        $this->selenium->click("link=» Create page");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type("s_internal_name", $internal_name );
        $this->selenium->type("en_US#s_title", "title US");
        $this->selenium->type("en_US#s_text", "text for US");

        $this->selenium->click("//button[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");
    }

    private function createPage($internal_name)
    {
        $this->newPage($internal_name);
        $message = "<span style='color-background:blue;'>".$this->selenium->getText('//*[@id="FlashMessage"]')."</span>";

        if( $this->selenium->isTextPresent('The page has been added') ) {
            $this->assertTrue("text present");
        } else {
            $this->assertFalse("TEXT NOT PRESENT - The page has been added - " . $message);
        }
    }

    private function createPageAgain($internal_name)
    {
        $this->newPage($internal_name);
        $message = "<span style='color-background:blue;'>".$this->selenium->getText('//*[@id="FlashMessage"]')."</span>";
        
        if( $this->selenium->isTextPresent('Oops! That internal name is already in use. We can\'t made the changes') ) {
            $this->assertTrue("text present");
        } else {
            $this->assertFalse("TEXT NOT PRESENT - The page has been added - " . $message);
        }
    }

    private function editPage($internal_name)
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Pages");
        $this->selenium->click("link=» Manage pages");

        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'$internal_name')]");
        $this->selenium->click("//table/tbody/tr/td[contains(.,'$internal_name')]/div/a[text()='Edit']");

        // editing page ...
        $this->selenium->type("s_internal_name", "new foo");
        $this->selenium->type("en_US#s_title", "new bar");
        $this->selenium->type("en_US#s_text", "new foobar");
        $this->selenium->click("//button[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");

        if( $this->selenium->isTextPresent("The page has been updated") ){
            $this->assertTrue("text present");
        } else {
            $this->assertFalse("TEXT NOT PRESENT - The page has been updated - ");
        }
    }

    private function deletePage($internal_name)
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Pages");
        $this->selenium->click("link=» Manage pages");

        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'$internal_name')]");
        $this->selenium->click("//table/tbody/tr/td[contains(.,'$internal_name')]/div/a[text()='Delete']");

        $this->selenium->waitForPageToLoad("30000");

        if( $this->selenium->isTextPresent('One page has been deleted correctly') ){
            $this->assertTrue("text present");
        } else {
            $this->assertFalse("TEXT NOT PRESENT - One page has been deleted correctly - ");
        }
    }

    /*
     *  can not simulate the performance of the filter
     */
//    private function tableSearch()
//    {
//        echo "<div style='background-color: green; color: white;padding-left:15px;'>Admin - Table search - CREATE A NEW PAGE test_page_example</div>";
//        $this->createPage('test_page_example') ;
//        flush();
//        echo "<div style='background-color: green; color: white;padding-left:15px;'>Admin - Table search - CREATE A NEW PAGE foobar_example</div>";
//        $this->createPage('foobar_example') ;
//        flush();
//
//        $this->selenium->open( osc_admin_base_url(true) );
//        $this->selenium->click("link=Pages");
//        $this->selenium->click("link=» Manage pages");
//
//        // HACK
//        $this->selenium->click("//div[@id='datatables_list_filter']/input[@type='text']");
//        $this->selenium->type("//div[@id='datatables_list_filter']/input[@type='text']", "fooba");
//        $this->selenium->keyPress( "//div[@id='datatables_list_filter']/input[@type='text']", "r" ) ; //\\120\\97\\109\\112\\108\\101
//        sleep(20);
//        // END HACK
//
//        $res = $this->selenium->getXpathCount("//table[@id='datatables_list']/tbody/tr");
//        $this->assertEqual(2, $res,"search filter does not work - 2 rows does not appear");
//
//        $this->selenium->type("//div[@id='datatables_list_filter']/input[@type='text']", "test_page");
//        sleep(1);
//        $res = $this->selenium->getXpathCount("//table[@id='datatables_list']/tbody/tr");
//        $this->assertEqual(1, $res,"search filter does not work - 2 rows does not appear");
//
//        $this->selenium->type("//div[@id='datatables_list_filter']/input[@type='text']", "foobar");
//        sleep(1);
//        $res = $this->selenium->getXpathCount("//table[@id='datatables_list']/tbody/tr");
//        $this->assertEqual(1, $res,"search filter does not work - 2 rows does not appear");
//    }


    private function selectAndDelete($internal_name, $beg, $fin)
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Pages");
        $this->selenium->click("link=» Manage pages");

        $beg_ = $beg;
        for($beg_; $beg_ <= $fin; $beg_++){
            $this->selenium->click("//table/tbody/tr[contains(.,'$internal_name".$beg_."')]/td/input");
        }

        $this->selenium->select("bulk_actions", "label=Delete");
        $this->selenium->click("//button[@id='bulk_apply']");
        $this->selenium->waitForPageToLoad("30000");

        if( $this->selenium->isTextPresent( ($fin-$beg) . " pages have been deleted correctly") ){
            $this->assertTrue("Deleted ok");
        } else {
            $this->assertFalse("TEXT NOT PRESENT - ". ($fin-$beg) . " pages have been deleted correctly");
        }
    }

    private function selectAllAndDelete()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Pages");
        $this->selenium->click("link=» Manage pages");
        $this->selenium->waitForPageToLoad("100");


        $this->selenium->click("check_all");
        $this->selenium->select("bulk_actions", "label=Delete");
        $this->selenium->click("bulk_apply");
        $this->selenium->waitForPageToLoad("30000");
        // "regexpi:This is SeleniumWiki.com"
        if( $this->selenium->isTextPresent( "regexpi:pages have been deleted correctly") ){
            $this->assertTrue("Deleted ok");
        } else {
            $this->assertFalse("TEXT NOT PRESENT - X pages have been deleted correctly");
        }
    }
}
?>
