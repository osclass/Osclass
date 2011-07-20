<?php
require_once('../../autorun.php');
require_once('../../web_tester.php');
require_once('../../reporter.php');

// LOAD OSCLASS
require_once '../../../../oc-load.php';
require_once LIB_PATH . 'Selenium.php';

class TestOfAdminAppearance extends WebTestCase {

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

//    function testAddTheme()
//    {
//        echo "<div style='background-color: green; color: white;'><h2>testAddTheme</h2></div>";
//        echo "<div style='background-color: green; color: white;padding-left:15px;'>testAddTheme - LOGIN </div>";
//        flush();
//        $this->loginCorrect();
//        flush();
//        echo "<div style='background-color: green; color: white;padding-left:15px;'>testAddTheme - ADD THEME</div>";
//
//        $this->selenium->open( osc_admin_base_url(true) );
//        $this->selenium->click("link=Appearance");
//        $this->selenium->click("link=» Add a new theme");
//        $this->selenium->waitForPageToLoad("10000");
//
//        if($this->selenium->isTextPresent("chmod a+w ") ){
//            $this->assertTrue(FALSE, "You need give permissions to the folder");
//        } else {
//            $this->selenium->type("package", LIB_PATH."simpletest/test/osclass/newcorp.zip");
//            $this->selenium->click("button_save");
//            $this->selenium->waitForPageToLoad("30000");
//
//            $this->assertTrue($this->selenium->isTextPresent("The theme has been installed correctly"), "Can't upload themes");
//        }
//    }
//
//    function testAddThemeCorrupt()
//    {
//        echo "<div style='background-color: green; color: white;'><h2>testAddThemeCorrupt</h2></div>";
//        echo "<div style='background-color: green; color: white;padding-left:15px;'>testAddThemeCorrupt - LOGIN </div>";
//        flush();
//        $this->loginCorrect();
//        flush();
//        echo "<div style='background-color: green; color: white;padding-left:15px;'>testAddThemeCorrupt - ADD THEME</div>";
//
//        $this->selenium->open( osc_admin_base_url(true) );
//        $this->selenium->click("link=Appearance");
//        $this->selenium->click("link=» Add a new theme");
//        $this->selenium->waitForPageToLoad("10000");
//
//        if($this->selenium->isTextPresent("chmod a+w ") ){
//            $this->assertTrue(FALSE, "You need give permissions to the folder");
//        } else {
//            $this->selenium->type("package", LIB_PATH."simpletest/test/osclass/corrupt.zip");
//            $this->selenium->click("button_save");
//            $this->selenium->waitForPageToLoad("30000");
//
//            $this->assertTrue($this->selenium->isTextPresent("The zip file is not valid"), "Can upload corrupt theme");
//        }
//    }
//
//    function testActivateTheme()
//    {
//        echo "<div style='background-color: green; color: white;'><h2>testActivateTheme</h2></div>";
//        echo "<div style='background-color: green; color: white;padding-left:15px;'>testActivateTheme - LOGIN </div>";
//        $this->loginCorrect();
//        flush();
//        echo "<div style='background-color: green; color: white;padding-left:15px;'>testActivateTheme - ACTIVATE THEME</div>";
//        $this->selenium->open( osc_admin_base_url(true) );
//        $this->selenium->click("link=Appearance");
//        $this->selenium->click("link=» Manage themes");
//        $this->selenium->waitForPageToLoad("10000");
//
//        if($this->selenium->isTextPresent("chmod a+w ") ){
//            $this->assertTrue(FALSE, "You need give permissions to the folder");
//        } else {
//
//            $this->selenium->click("link=Activate");
//            $this->selenium->waitForPageToLoad("30000");
//
//            $text_element = $this->selenium->getText("xpath=//div[@id='current_theme_info']" );
//            if(preg_match('/NewCorp Theme/', $text_element) ) {
//                $this->assertTrue(TRUE);
//            } else {
//                $this->assertTrue(FALSE, "Can't activate the theme");
//            }
//        }
//    }
//
//    function testDeleteTheme()
//    {
//        echo "<div style='background-color: green; color: white;'><h2>testDeleteTheme</h2></div>";
//        echo "<div style='background-color: green; color: white;padding-left:15px;'>testDeleteTheme - LOGIN </div>";
//        $this->loginCorrect();
//        flush();
//
//        $this->selenium->open( osc_admin_base_url(true) );
//        $this->selenium->click("link=Appearance");
//        $this->selenium->click("link=» Manage themes");
//        $this->selenium->waitForPageToLoad("10000");
//
//        if($this->selenium->isTextPresent("chmod a+w ") ){
//            $this->assertTrue(FALSE, "You need give permissions to the folder");
//        } else {
//
//            $this->selenium->click("link=Activate");
//            $this->selenium->waitForPageToLoad("30000");
//
//            $text_element = $this->selenium->getText("xpath=//div[@id='current_theme_info']" );
//            if(preg_match('/Modern Theme/', $text_element) ) {
//                $this->assertTrue(TRUE);
//            } else {
//                $this->assertTrue(FALSE, "Can't activate the theme");
//            }
//        }
//        echo "<div style='background-color: green; color: white;padding-left:15px;'>testDeleteTheme - <h2>YOU NEED TO DELETE THE PLUGIN DIRECTORY MANUALY ( /oc-content/themes/newcorp/)</h2></div>";
//    }

    function testWidgets()
    {
        echo "<div style='background-color: green; color: white;'><h2>testWidgets</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testWidgets - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testWidgets - widgetsHeader</div>";
        $this->widgetsHeader() ;
        flush();
//        echo "<div style='background-color: green; color: white;padding-left:15px;'>testWidgets - widgetsCategories</div>";
//        $this->widgetsCategories() ;
//        flush();
//        echo "<div style='background-color: green; color: white;padding-left:15px;'>testWidgets - widgetsFooter</div>";
//        $this->widgetsFooter() ;
//        flush();
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

    private function widgetsHeader()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Appearance");
        $this->selenium->click("link=» Add or remove widgets");
        $this->selenium->waitForPageToLoad("10000");

        // add header widget
        $this->selenium->click("xpath=//div[@id='settings_form']/div/div[1]/div/a");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type("description", "header1");
        
        $this->selenium->selectFrame("index=0");
        $this->selenium->type("xpath=//html/body[@id='tinymce']", "New Widget Header");
        $this->selenium->selectFrame("relative=top");

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");

        $this->assertTrue($this->selenium->isTextPresent("Widget added correctly"), "Can't add widget header. ERROR" );
        $this->assertTrue($this->selenium->isTextPresent("header1"), "Can't add widget header. header1 not present. ERROR");

        // check if appear at frontend
        $this->selenium->open( osc_base_url(true) );
        $this->assertTrue($this->selenium->isTextPresent('New Widget Header') , "Header widget is not visible at website. ERROR");

        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Appearance");
        $this->selenium->click("link=» Add or remove widgets");
        $this->selenium->waitForPageToLoad("10000");
        
        // remove widget
        $this->selenium->click("link=Delete");
        $this->selenium->waitForPageToLoad("30000");
        $this->assertTrue($this->selenium->isTextPresent("Widget removed correctly"), "Can't delete widget header. ERROR" );
        $this->assertTrue( ! $this->selenium->isTextPresent("header1"), "Can't delete widget header. header1 still present. ERROR");
    }

    private function widgetsCategories()
    {
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->click("link=Appearance") ;
        $this->selenium->click("link=» Add or remove widgets") ;
        $this->selenium->waitForPageToLoad("10000") ;

        // add categories widget
        $this->selenium->click("xpath=//div[@id='settings_form']/div/div[2]/div/a") ;
        $this->selenium->waitForPageToLoad("10000") ;

        $this->selenium->type("description", "categories1") ;

        $this->selenium->selectFrame("index=0");
        $this->selenium->type("xpath=//html/body[@id='tinymce']", "New Widget Category") ;
        $this->selenium->selectFrame("relative=top") ;

        $this->selenium->click("//input[@type='submit']") ;
        $this->selenium->waitForPageToLoad("30000");

        $this->assertTrue($this->selenium->isTextPresent("Widget added correctly"), "Can't add widget categories. ERROR" );
        $this->assertTrue($this->selenium->isTextPresent("categories1"), "Can't add widget categories. ERROR");

        //remove widget
        $this->selenium->click("link=Delete");
        $this->selenium->waitForPageToLoad("30000");
        $this->assertTrue($this->selenium->isTextPresent("Widget removed correctly"), "Can't delete widget categories. ERROR" );
        $this->assertTrue( ! $this->selenium->isTextPresent("categories1"), "Can't delete widget categories. ERROR");
    }

    private function widgetsFooter()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Appearance");
        $this->selenium->click("link=» Add or remove widgets");
        $this->selenium->waitForPageToLoad("10000");

        // add categories widget
        $this->selenium->click("xpath=//div[@id='settings_form']/div/div[3]/div/a");
        $this->selenium->waitForPageToLoad("10000") ;

        $this->selenium->type("description", "footer1") ;

        $this->selenium->selectFrame("index=0");
        $this->selenium->type("xpath=//html/body[@id='tinymce']", "New Widget Footer") ;
        $this->selenium->selectFrame("relative=top") ;

        $this->selenium->click("//input[@type='submit']") ;
        $this->selenium->waitForPageToLoad("30000");

        $this->assertTrue($this->selenium->isTextPresent("Widget added correctly"), "Can't add widget footer. ERROR" );
        $this->assertTrue($this->selenium->isTextPresent("footer1"), "Can't add widget footer. ERROR");

        //remove widget
        $this->selenium->click("link=Delete");
        $this->selenium->waitForPageToLoad("30000");
        $this->assertTrue($this->selenium->isTextPresent("Widget removed correctly"), "Can't delete widget footer. ERROR" );
        $this->assertTrue( ! $this->selenium->isTextPresent("footer1"), "Can't delete widget footer. ERROR");
    }
}
?>
