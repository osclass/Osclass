<?php
require_once('../../autorun.php');
require_once('../../web_tester.php');
require_once('../../reporter.php');

// LOAD OSCLASS
require_once '../../../../oc-load.php';
require_once LIB_PATH . 'Selenium.php';

class TestOfAdminLanguage extends WebTestCase {

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
     * upload new language
     * REQUIRE: user logged in
     */
    function testLanguageInsert()
    {
        echo "<div style='background-color: green; color: white;'><h2>TestOfAdminLanguage</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testLanguageInsert - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testLanguageInsert - UPLOAD NEW LANGUAGE</div>";
        $this->insertLanguage() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testLanguageInsert - DELETE LANGUAGE</div>";
        $this->deleteLanguage();
        flush();
    }

    function testLanguageInsertbyLink()
    {
        echo "<div style='background-color: green; color: white;'><h2>testLanguageInsertbyLink</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testLanguageInsertbyLink - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testLanguageInsertbyLink - UPLOAD NEW LANGUAGE</div>";
        $this->insertLanguageByLink() ;
        flush();
    }

    public function testEnableDisable()
    {
        echo "<div style='background-color: green; color: white;'><h2>testEnableDisable</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testEnableDisable - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testEnableDisable - Enable website LANGUAGE</div>";
        $this->enableWebsite("Spanish");
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testEnableDisable - Disable website LANGUAGE</div>";
        $this->disableWebsite("Spanish");
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testEnableDisable - Disable oc-admin LANGUAGE</div>";
        $this->disableOCAdmin("Spanish");
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testEnableDisable - Enable oc-admin LANGUAGE</div>";
        $this->enableOCAdmin("Spanish");
        flush();
    }

    public function testLanguageEdit()
    {
        echo "<div style='background-color: green; color: white;'><h2>testLanguageEdit</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testLanguageEdit - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testLanguageEdit - EDIT LANGUAGE</div>";
        $this->editAndEnable();
        flush();
    }

    public function testDeleteLanguage()
    {
        echo "<div style='background-color: green; color: white;'><h2>testDeleteLanguage</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testDeleteLanguage - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testDeleteLanguage - DELETE LANGUAGE</div>";
        $this->deleteLanguage();
        flush();
    }

    
    /*
     * PRIVATE FUNCTIONS
     */
    private function enableWebsite($lang)
    {
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->click("link=Languages");
        $this->selenium->click("link=» Manage languages");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'$lang')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'$lang')]/td/div/a[text()='Enable (website)']");
        $this->selenium->waitForPageToLoad("10000");
        
        $this->assertTrue($this->selenium->isTextPresent("Selected languages have been enabled for the website"),"Can't enable (website) language $lang");
    }
    private function disableWebsite($lang)
    {
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->click("link=Languages");
        $this->selenium->click("link=» Manage languages");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'$lang')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'$lang')]/td/div/a[text()='Disable (website)']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("Selected languages have been disabled for the website"),"Can't disable (website) language $lang");
    }
    private function enableOCAdmin($lang)
    {
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->click("link=Languages");
        $this->selenium->click("link=» Manage languages");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'$lang')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'$lang')]/td/div/a[text()='Enable (oc-admin)']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("Selected languages have been enabled for the backoffice (oc-admin)"),"Can't enable (backoffice) language $lang");
    }
    private function disableOCAdmin($lang)
    {
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->click("link=Languages");
        $this->selenium->click("link=» Manage languages");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'$lang')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'$lang')]/td/div/a[text()='Disable (oc-admin)']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("Selected languages have been disabled for the backoffice (oc-admin)"),"Can't disable (backoffice) language $lang");
    }
    
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

    /**
     * Upload a new language.
     */
    private function insertLanguage()
    {
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->click("link=Languages");
        $this->selenium->click("link=» Add a language");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type("package", LIB_PATH."simpletest/test/osclass/lang_es_ES_2.0.zip");
        $this->selenium->click("//form/input[@id='button_save']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("The language has been installed correctly"),"Can't upload language lang_es_ES_2.0.zip");
    }
    private function insertLanguageByLink()
    {
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->click("link=Languages");
        $this->selenium->click("link=» Manage languages");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("link=Add");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type("package", LIB_PATH."simpletest/test/osclass/lang_es_ES_2.0.zip");
        $this->selenium->click("//form/input[@id='button_save']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("The language has been installed correctly"),"Can't upload language lang_es_ES_2.0.zip");
    }

    private function editAndEnable()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Languages");
        $this->selenium->click("link=» Manage languages");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'Spanish')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'Spanish')]/td/div/a[text()='Edit']");

        $this->selenium->type("s_name","Spanish upadated");
        $this->selenium->type("s_short_name","Spanish upadated");
        $this->selenium->type("s_description","Spanish translation updated");
//        $this->selenium->type("s_currency_format","");
//        $this->selenium->type("s_date_format","");
        $this->selenium->type("s_stop_words","foo,bar");

        $this->selenium->click("b_enabled");
//        $this->selenium->click("b_enabled_bo");

        $this->selenium->click("xpath=//button[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("Spanish upadated has been updated"),"Can't edit language Spanish");
    }

    private function deleteLanguage()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Languages");
        $this->selenium->click("link=» Manage languages");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'Spanish')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'Spanish')]/td/div/a[text()='Delete']");

        // todo assertTrue on FM (issue 405)
    }

}
?>
