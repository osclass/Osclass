<?php
require_once('../../autorun.php');
require_once('../../web_tester.php');
require_once('../../reporter.php');

// LOAD OSCLASS
require_once '../../../../oc-load.php';
require_once LIB_PATH . 'Selenium.php';

class TestOfAdminAdministrators extends WebTestCase {

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
    function testInsertAdministrator()
    {
        echo "<div style='background-color: green; color: white;'><h2>testInsertAdministrator</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testInsertAdministrator - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testInsertAdministrator - INSERT NEW ADMINISTRATOR</div>";
        $this->insertAdministrator() ;
        flush();
    }

    function testInsertAdministratorTwice()
    {
        echo "<div style='background-color: green; color: white;'><h2>testInsertAdministratorTwice</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testInsertAdministratorTwice - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testInsertAdministratorTwice - INSERT SAME ADMINISTRATOR AGAIN</div>";
        $this->insertAdministratorAgain();
        flush();
    }

    function testInsertAdministratorFail()
    {
        echo "<div style='background-color: green; color: white;'><h2>testInsertAdministratorFail</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testInsertAdministratorFail - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testInsertAdministratorFail - INSERT ADMINISTRATOR - INVALID EMAIL</div>";
        $this->insertAdministratorInvalidEmail() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testInsertAdministratorFail - INSERT ADMINISTRATOR - EXISTENT USERNAME</div>";
        $this->insertAdministratorExistentUsername();
        flush();
    }

    function testEditYourProfile()
    {
        echo "<div style='background-color: green; color: white;'><h2>testEditYourProfile</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testEditYourProfile - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testEditYourProfile - EDIT YOUR PROFILE ADMINISTRATOR</div>";
        $this->editYourProfileAdministrator();
        flush();
    }

    function testEditAdministrator()
    {
        echo "<div style='background-color: green; color: white;'><h2>testEditAdministrator</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testEditAdministrator - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testEditAdministrator - EDIT ADMINISTRATOR</div>";
        $this->editAdministrator();
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testEditAdministrator - EDIT ADMINISTRATOR WITHOUT CHANGE PASSWORD</div>";
        $this->editAdministrator2();
        flush();
    }

    function testEditAdministratorFailPasswMatch()
    {
        echo "<div style='background-color: green; color: white;'><h2>testEditAdministratorFailPasswMatch</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testEditAdministratorFailPasswMatch - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testEditAdministratorFailPasswMatch - EDIT ADMINISTRATOR PASSW DON'T MATCH</div>";
        $this->editAdministratorFailPass();
        flush();
    }


    function testDeleteAdministrator()
    {
        echo "<div style='background-color: green; color: white;'><h2>testDeleteAdministrator</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testDeleteAdministrator - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testDeleteAdministrator - DELETE ADMINISTRATOR</div>";
        $this->deleteAdministrator();
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

    private function insertAdministrator()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Administrators");
        $this->selenium->click("link=» Add new administrator");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type("s_name","Real name user one");
        $this->selenium->type("s_username","useradminone");
        $this->selenium->type("s_password", "useradminpass");

        $this->selenium->type("s_email", "admin@mail.com");

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");

        $this->assertTrue($this->selenium->isTextPresent("The admin has been added"),"Can't insert administrator. ERROR");
    }

    private function insertAdministratorAgain()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Administrators");
        $this->selenium->click("link=» Add new administrator");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type("s_name","Real name user one");
        $this->selenium->type("s_username","useradminone");
        $this->selenium->type("s_password", "useradminpass");

        $this->selenium->type("s_email", "admin@mail.com");

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");

        $this->assertTrue($this->selenium->isTextPresent("Email already in use"),"Can insert administrator with an existent email. ERROR");
    }

    private function insertAdministratorInvalidEmail()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Administrators");
        $this->selenium->click("link=» Add new administrator");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type("s_name","Real name user one");
        $this->selenium->type("s_username","useradminone_");
        $this->selenium->type("s_password", "useradminpass_");

        $this->selenium->type("s_email", "admin(at)mailcom");

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");

        $this->assertTrue($this->selenium->isTextPresent("Email invalid"),"Can insert administrator with invalid email. ERROR");
    }

    private function insertAdministratorExistentUsername()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Administrators");
        $this->selenium->click("link=» Add new administrator");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type("s_name","Real name user one");
        $this->selenium->type("s_username","useradminone");
        $this->selenium->type("s_password", "useradminpass");

        $this->selenium->type("s_email", "admin_@mail.com");

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");

        $this->assertTrue($this->selenium->isTextPresent("Username already in use"),"Can insert administrator with existent username. ERROR");
    }

    private function editYourProfileAdministrator()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Administrators");
        $this->selenium->click("link=» Edit Your Profile");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type("s_name","Administrator updated");
        $this->selenium->type("s_username","adminUpdated");

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");

        $this->assertTrue($this->selenium->isTextPresent("The admin has been updated"),"Can edit administrator. ERROR");
    }

    private function editAdministrator()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Administrators");
        $this->selenium->click("link=» List administrators");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'useradminone')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'useradminone')]/td/div/a[text()='Edit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type("s_name","Real name user one NEW");
        $this->selenium->type("s_username","useradminoneNEW");
        $this->selenium->type("old_password", "useradminpass");
        $this->selenium->type("s_password"  , "useradminpassNEW");
        $this->selenium->type("s_password2" , "useradminpassNEW");

        $this->selenium->type("s_email", "admin@mail.com");

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");
        
        $this->assertTrue($this->selenium->isTextPresent("The admin has been updated"),"Can edit administrator. ERROR");
    }

    private function editAdministrator2()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Administrators");
        $this->selenium->click("link=» List administrators");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'useradminone')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'useradminone')]/td/div/a[text()='Edit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type("s_name","Real name user one NEW");
        $this->selenium->type("s_username","useradminoneNEW");

        $this->selenium->type("s_email", "newadmin@mail.com");

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");

        $this->assertTrue($this->selenium->isTextPresent("The admin has been updated"),"Can edit administrator. ERROR");
    }

    private function editAdministratorFailPass()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Administrators");
        $this->selenium->click("link=» List administrators");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'useradminone')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'useradminone')]/td/div/a[text()='Edit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type("s_name","Real name user one NEW");
        $this->selenium->type("s_username","useradminoneNEW");
        $this->selenium->type("old_password", "useradminpassNEW");
        $this->selenium->type("s_password"  , "useradminpass");
        $this->selenium->type("s_password2" , "useradminpassNEW");

        $this->selenium->type("s_email", "admin@mail.com");

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");

        $this->assertTrue($this->selenium->isTextPresent("The password couldn't be updated. Passwords don't match"),"Can edit administrator. ERROR");
    }

    private function deleteAdministrator()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Administrators");
        $this->selenium->click("link=» List administrators");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'useradminone')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'useradminone')]/td/div/a[text()='Delete']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("The admin has been deleted correctly"),"Can't delete administrator. ERROR");    
    }
}
?>
