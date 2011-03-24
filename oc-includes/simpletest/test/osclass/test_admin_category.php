<?php
require_once('../../autorun.php');
require_once('../../web_tester.php');
require_once('../../reporter.php');

// LOAD OSCLASS
require_once '../../../../oc-load.php';
require_once LIB_PATH . 'Selenium.php';

class TestOfAdminCategory extends WebTestCase {

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
     *
     */
    function testCategoryInsertParent()
    {
        echo "<div style='background-color: green; color: white;'><h2>testCategoryInsertParent</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testCategoryInsertParent - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testCategoryInsertParent - INSERT NEW CATEGORY PARENT</div>";
        $this->insertCategoryParent() ;
        flush();
    }

    function testCategoryInsertChild()
    {
        echo "<div style='background-color: green; color: white;'><h2>testCategoryInsertChild</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testCategoryInsertChild - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testCategoryInsertChild - INSERT NEW CATEGORY CHILD</div>";
        $this->insertCategoryChild() ;
        flush();
    }

    function testEnablePreviousCategories()
    {
        echo "<div style='background-color: green; color: white;'><h2>testEnablePreviousCategories</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testEnablePreviousCategories - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testEnablePreviousCategories - ENABLE ALL PREVIOUS CATEGORIES</div>";
        $this->enableCategories() ;
        flush();
    }

    function testDisablePreviousCategories()
    {
        echo "<div style='background-color: green; color: white;'><h2>testDisablePreviousCategories</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testDisablePreviousCategories - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testDisablePreviousCategories - DISABLE ALL PREVIOUS CATEGORIES</div>";
        $this->disableCategories() ;
        flush();
    }

    function testEditCategories()
    {
        echo "<div style='background-color: green; color: white;'><h2>testEditCategories</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testEditCategories - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testEditCategories - EDIT ALL PREVIOUS CATEGORIES</div>";
        $this->editCategories() ;
        flush();
    }

    function testDeleteCategories()
    {
        echo "<div style='background-color: green; color: white;'><h2>testDeleteCategories</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testDeleteCategories - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testDeleteCategories - DELETE PREVIOUS PARENT CATEGORY</div>";
        $this->deleteCategories() ;
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

    private function insertCategoryParent()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Categories");
        $this->selenium->click("link=» Add a new category");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type("i_expiration_days", "0");
        $this->selenium->type("i_position", "1");
        $this->selenium->type("en_US#s_name", "Geek");
        $this->selenium->type("en_US#s_description", "all geek classifieds");
        $this->selenium->click("//button[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");

        $this->assertTrue($this->selenium->isTextPresent("The category has been added"), "Can't insert a new category. ERROR");
    }

    private function insertCategoryChild()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Categories");
        $this->selenium->click("link=» Add a new category");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->select("parentId", "label=Geek");
        $this->selenium->type("i_expiration_days", "0");
        $this->selenium->type("i_position", "1");
        $this->selenium->type("en_US#s_name", "Toys");
        $this->selenium->type("en_US#s_description", "geek toys");
        $this->selenium->click("//button[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");

        $this->assertTrue($this->selenium->isTextPresent("The category has been added"), "Can't insert a new subcategory. ERROR");
    }

    private function editCategories()
    {
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testEditCategories - EDIT Geek category</div>";
        
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Categories");
        $this->selenium->click("link=» Manage categories");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'Geek')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'Geek')]/td/div/a[text()='Edit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type("i_expiration_days", "0");
        $this->selenium->type("i_position", "1");
        
        $this->selenium->click("b_enabled");

        $this->selenium->type("en_US#s_name", "Geek new");
        $this->selenium->type("en_US#s_description", "NEW all geek classifieds NEW");
        $this->selenium->click("//button[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");

        $this->assertTrue($this->selenium->isTextPresent("The category has been updated."), "Can't edit the category. ERROR");

        echo "<div style='background-color: green; color: white;padding-left:15px;'>testEditCategories - EDIT Geek>Toys subcategory</div>";

        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Categories");
        $this->selenium->click("link=» Manage categories");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'Geek')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'Geek')]/td/div/a[text()='View subcategories']");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'Toys')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'Toys')]/td/div/a[text()='Edit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type("i_expiration_days", "0");
        $this->selenium->type("i_position", "1");

        $this->selenium->click("b_enabled");

        $this->selenium->type("en_US#s_name", "Toys new");
        $this->selenium->type("en_US#s_description", "NEW geek toys NEW");
        $this->selenium->click("//button[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");

        $this->assertTrue($this->selenium->isTextPresent("The category has been updated."), "Can't edit the category. ERROR");
    }

    private function deleteCategories()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Categories");
        $this->selenium->click("link=» Manage categories");
        $this->selenium->waitForPageToLoad("10000");

        // parent
        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'Geek')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'Geek')]/td/div/a[text()='Delete']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("The categories have been deleted"), "Can't delete the category. ERROR");
    }

    private function enableCategories()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Categories");
        $this->selenium->click("link=» Manage categories");
        $this->selenium->waitForPageToLoad("10000");

        // parent
        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'Geek')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'Geek')]/td/div/a[text()='Enable']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("The category has been enabled"), "Can't enable parent category Geek. ERROR");

        // subcategory toys
        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'Geek')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'Geek')]/td/div/a[text()='View subcategories']");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'Toys')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'Toys')]/td/div/a[text()='Enable']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("The category has been enabled"), "Can't enable parent subcategory Geek>Toys. ERROR");
    }

    private function disableCategories()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Categories");
        $this->selenium->click("link=» Manage categories");
        $this->selenium->waitForPageToLoad("10000");

        // parent
        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'Geek')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'Geek')]/td/div/a[text()='Disable']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("The category has been disabled"), "Can't disable parent category Geek. ERROR");

        // subcategory toys
        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'Geek')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'Geek')]/td/div/a[text()='View subcategories']");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'Toys')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'Toys')]/td/div/a[text()='Disable']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("The category has been disabled"), "Can't disable parent subcategory Geek>Toys. ERROR");
    }
}
?>
