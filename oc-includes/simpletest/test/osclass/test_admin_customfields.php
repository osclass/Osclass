<?php
require_once('../../autorun.php');
require_once('../../web_tester.php');
require_once('../../reporter.php');

// LOAD OSCLASS
require_once '../../../../oc-load.php';
require_once LIB_PATH . 'Selenium.php';

class TestOfAdminCustomFields extends WebTestCase {

    private $selenium;

    function setUp()
    {
        $conn = getConnection();
        $conn->osc_dbExec(sprintf("INSERT INTO `%st_admin` (`s_name` ,`s_username` ,`s_password` ,`s_secret` ,`s_email`) VALUES ('Test Admin','testadmin','5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8','mvqdnrpt','testadmin@test.net')", DB_TABLE_PREFIX));

        echo "<br><div style='background-color: Wheat; color: black;'>init test</div>";

        $browser = "*firefox";
        $this->selenium = new Testing_Selenium($browser, "http://localhost/");
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
//    function testCustomAdd()
//    {
//        echo "<div style='background-color: green; color: white;'><h2>TestOfAdminCustomFields</h2></div>";
//        echo "<div style='background-color: green; color: white;padding-left:15px;'>TestOfAdminCustomFields - LOGIN </div>";
//        $this->loginCorrect() ;
//        flush();
//        echo "<div style='background-color: green; color: white;padding-left:15px;'>TestOfAdminCustomFields - ADD NEW CUSTOM FIELD</div>";
//        $this->addCustomFields() ;
//        flush();
//    }

//    function testCustomEdit()
//    {
//        echo "<div style='background-color: green; color: white;'><h2>TestOfAdminCustomFields</h2></div>";
//        echo "<div style='background-color: green; color: white;padding-left:15px;'>TestOfAdminCustomFields - LOGIN </div>";
//        $this->loginCorrect() ;
//        flush();
//        echo "<div style='background-color: green; color: white;padding-left:15px;'>TestOfAdminCustomFields - EDIT CUSTOM FIELD</div>";
//        $this->editCustomFields() ;
//        flush();
//    }

    function testCustomOthers()
    {
        echo "<div style='background-color: green; color: white;'><h2>TestOfAdminCustomFields</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>TestOfAdminCustomFields - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>TestOfAdminCustomFields - No more than one forms when edit fields</div>";
        $this->noMoreThanOneForm() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>TestOfAdminCustomFields - No more than one fields can be the same</div>";
        $this->sameField() ;
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

    private function addCustomFields()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Custom Fields");
        $this->selenium->click("link=» Manage custom fields");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->click("id=button_add");
        $this->selenium->type("field_name", "extra_field_1");
        $this->selenium->select("field_type", "TEXT");
        $this->selenium->click("id=button_save");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue($this->selenium->isTextPresent("New custom field added"), "Can't add new custom field. ERROR");
        $this->assertTrue($this->selenium->isTextPresent("extra_field_1"), "Can't add new custom field. ERROR");

        /**
         *  @todo  ADD HERE TEST SAME NAME AND TYPE 
         */

        $this->selenium->click("id=button_add");
        $this->selenium->type("field_name", "extra_field_2");
        $this->selenium->select("field_type", "TEXTAREA");
        $this->selenium->click("id=button_save");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue($this->selenium->isTextPresent("New custom field added"), "Can't add new custom field. ERROR");
        $this->assertTrue($this->selenium->isTextPresent("extra_field_2"), "Can't add new custom field. ERROR");
        
    }

    private function editCustomFields()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Custom Fields");
        $this->selenium->click("link=» Manage custom fields");
        $this->selenium->waitForPageToLoad("10000");

        // edit categories,
        $this->selenium->click("link=Edit");
        
        // modificar s_name & type
        $this->selenium->type('s_name', "NEW FIELD");
        $this->selenium->select("xpath=//form[@id='field_form']/div/div[2]/select", "TEXTAREA");
        // uncheck all
        $this->selenium->click("link=Uncheck all");
        $this->assertFalse($this->selenium->isChecked("categories[]"), "Cannot uncheck all categories" );
        // check all
        $this->selenium->click("link=Check all");
        $this->assertTrue($this->selenium->isChecked("categories[]"), "Cannot check all categories" );
        // uncheck all !
        $this->selenium->click("link=Uncheck all");
        
        $this->selenium->click("xpath=//button[@type='submit']");
        sleep(2);
        $this->assertTrue($this->selenium->isTextPresent("Saved"), "Can't edit custom field. ERROR");
    }

    private function noMoreThanOneForm()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Custom Fields");
        $this->selenium->click("link=» Manage custom fields");
        $this->selenium->waitForPageToLoad("10000");

        // edit categories,
        $this->selenium->click("link=Edit"); // first Edit link
        $this->assertTrue($this->selenium->isElementPresent("id=field_form"), "Form is not showed. ERROR");

        $this->selenium->click("xpath=//div[@id='TableFields']/ul/li[last()]/div/div[2]/a[1]") ;
        $this->assertTrue( ( $this->selenium->getXpathCount("xpath=//form[@id='field_form']") == 1) , "Form is showed more than one time. ERROR");
    }
//
//    private function deleteCategories()
//    {
//        $this->selenium->open( osc_admin_base_url(true) );
//        $this->selenium->click("link=Categories");
//        $this->selenium->click("link=» Manage categories");
//        $this->selenium->waitForPageToLoad("10000");
//
//        // parent
//        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'Geek')]");
//        $this->selenium->click("//table/tbody/tr[contains(.,'Geek')]/td/div/a[text()='Delete']");
//        $this->selenium->waitForPageToLoad("10000");
//
//        $this->assertTrue($this->selenium->isTextPresent("The categories have been deleted"), "Can't delete the category. ERROR");
//    }
//
//    private function enableCategories()
//    {
//        $this->selenium->open( osc_admin_base_url(true) );
//        $this->selenium->click("link=Categories");
//        $this->selenium->click("link=» Manage categories");
//        $this->selenium->waitForPageToLoad("10000");
//
//        // parent
//        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'Geek')]");
//        $this->selenium->click("//table/tbody/tr[contains(.,'Geek')]/td/div/a[text()='Enable']");
//        $this->selenium->waitForPageToLoad("10000");
//
//        $this->assertTrue($this->selenium->isTextPresent("The category has been enabled"), "Can't enable parent category Geek. ERROR");
//
//        // subcategory toys
//        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'Geek')]");
//        $this->selenium->click("//table/tbody/tr[contains(.,'Geek')]/td/div/a[text()='View subcategories']");
//        $this->selenium->waitForPageToLoad("10000");
//
//        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'Toys')]");
//        $this->selenium->click("//table/tbody/tr[contains(.,'Toys')]/td/div/a[text()='Enable']");
//        $this->selenium->waitForPageToLoad("10000");
//
//        $this->assertTrue($this->selenium->isTextPresent("The category has been enabled"), "Can't enable parent subcategory Geek>Toys. ERROR");
//    }
//
//    private function disableCategories()
//    {
//        $this->selenium->open( osc_admin_base_url(true) );
//        $this->selenium->click("link=Categories");
//        $this->selenium->click("link=» Manage categories");
//        $this->selenium->waitForPageToLoad("10000");
//
//        // parent
//        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'Geek')]");
//        $this->selenium->click("//table/tbody/tr[contains(.,'Geek')]/td/div/a[text()='Disable']");
//        $this->selenium->waitForPageToLoad("10000");
//
//        $this->assertTrue($this->selenium->isTextPresent("The category has been disabled"), "Can't disable parent category Geek. ERROR");
//
//        // subcategory toys
//        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'Geek')]");
//        $this->selenium->click("//table/tbody/tr[contains(.,'Geek')]/td/div/a[text()='View subcategories']");
//        $this->selenium->waitForPageToLoad("10000");
//
//        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'Toys')]");
//        $this->selenium->click("//table/tbody/tr[contains(.,'Toys')]/td/div/a[text()='Disable']");
//        $this->selenium->waitForPageToLoad("10000");
//
//        $this->assertTrue($this->selenium->isTextPresent("The category has been disabled"), "Can't disable parent subcategory Geek>Toys. ERROR");
//    }
}
?>
