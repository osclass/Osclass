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
        $this->selenium->setSpeed("100");
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
        echo "<div style='background-color: green; color: white;'>Admin - Page insert -</div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>Admin - Page insert - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>Admin - Page insert - CREATE A NEW PAGE</div>";
        $this->createPage('test_page_example') ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>Admin - Page insert - DELETE A FIRST PAGE</div>";
        $this->deletePage('test_page_example') ;
    }

    /**
     * insert 10 new pages and delete all the pages.
     */
    function testMultiplePagesInsert()
    {
        echo "<div style='background-color: green; color: white;'>Admin - Page insert -</div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>Admin - Page insert - LOGIN </div>";
        $this->loginCorrect() ;
        flush();

        $count = 0;
        while( $count < 10 ) {
            echo "<div style='background-color: green; color: white;padding-left:15px;'>Admin - Page insert - CREATE A NEW PAGE test_page_example$count</div>";
            $this->createPage('test_page_example'.$count) ;
            $count++;
            flush();
        }
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>Admin - Page insert - DELETE 5 PAGES THROUGH CHECKBOX</div>";
        $this->selectAndDelete('test_page_example', 0, 5);
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>Admin - Page insert - DELETE ALL PAGES VIA CHECKBOX (SELECT ALL)</div>";
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

    private function createPage($internal_name)
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

        $message = "<span style='color-background:blue;'>".$this->selenium->getText('//*[@id="FlashMessage"]')."</span>";

        if( $this->selenium->isTextPresent('The page has been added') ) {
            $this->assertTrue("text present");
        } else {
            $this->assertFalse("TEXT NOT PRESENT - The page has been added - " . $message);
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

        if( $this->selenium->isTextPresent( "5 pages have been deleted correctly") ){
            $this->assertTrue("Deleted ok");
        } else {
            $this->assertFalse("TEXT NOT PRESENT - 5 pages have been deleted correctly");
        }
    }
}
?>
