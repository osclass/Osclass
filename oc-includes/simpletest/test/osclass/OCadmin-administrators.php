<?php
require_once '../../../../oc-load.php';

//require_once('FrontendTest.php');

class OCadmin_administrators extends OCadminTest {
    
    /*           TESTS          */
    function testInsertAdministrator()
    {
        $this->loginWith() ;
//        $this->insertAdministrator() ;
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Administrators");
        $this->selenium->click("//h3[contains(.,'Administrators')]/ul/li/a[text()='Add new']");
        //$this->selenium->click("link=Add new");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->type("s_name","Real name user one");
        $this->selenium->type("s_username","useradminone");
        $this->selenium->type("s_password", "useradminpass");
        $this->selenium->type("s_email", "admin@mail.com");
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");
        $this->assertTrue($this->selenium->isTextPresent("The admin has been added"),"Add administrator");
    }

    /*function testInsertAdministratorTwice()
    {
        $this->loginWith() ;
//        $this->insertAdministratorAgain();
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Administrators");
        $this->selenium->click("link=Add new administrator");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->type("s_name","Real name user one");
        $this->selenium->type("s_username","useradminone");
        $this->selenium->type("s_password", "useradminpass");
        $this->selenium->type("s_email", "admin@mail.com");
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");
        $this->assertTrue($this->selenium->isTextPresent("Email already in use"),"Add administrator with existing email");
    }

    function testInsertAdministratorFail()
    {
        $this->loginWith() ;
//        $this->insertAdministratorInvalidEmail() ;
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Administrators");
        $this->selenium->click("link=Add new administrator");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->type("s_name","Real name user one");
        $this->selenium->type("s_username","useradminone_");
        $this->selenium->type("s_password", "useradminpass_");
        $this->selenium->type("s_email", "admin(at)mailcom");
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");
        $this->assertTrue($this->selenium->isTextPresent("Email invalid"),"Add administrator invalid email");
//        $this->insertAdministratorExistentUsername();
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Administrators");
        $this->selenium->click("link=Add new administrator");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->type("s_name","Real name user one");
        $this->selenium->type("s_username","useradminone");
        $this->selenium->type("s_password", "useradminpass");
        $this->selenium->type("s_email", "admin_@mail.com");
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");
        $this->assertTrue($this->selenium->isTextPresent("Username already in use"),"Add aministrator existing username");
    }

    function testEditYourProfile()
    {
        $this->loginWith() ;
//        $this->editYourProfileAdministrator();
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Administrators");
        $this->selenium->click("link=Edit Your Profile");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->type("s_name","Administrator updated");
        $this->selenium->type("s_username","adminUpdated");
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");
        $this->assertTrue($this->selenium->isTextPresent("The admin has been updated"),"Edit administrator's profile");
    }

    function testEditAdministrator()
    {
        $this->loginWith() ;
//        $this->editAdministrator();
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Administrators");
        $this->selenium->click("link=Manage administrators");
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
        $this->assertTrue($this->selenium->isTextPresent("The admin has been updated"),"Edit administrator (other)");
//        $this->editAdministrator2();
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Administrators");
        $this->selenium->click("link=Manage administrators");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'useradminone')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'useradminone')]/td/div/a[text()='Edit']");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->type("s_name","Real name user one NEW");
        $this->selenium->type("s_username","useradminoneNEW");
        $this->selenium->type("s_email", "newadmin@mail.com");
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");
        $this->assertTrue($this->selenium->isTextPresent("The admin has been updated"),"Edit administrator (other 2)");
    }

    function testEditAdministratorFailPasswMatch()
    {
        $this->loginWith() ;
//        $this->editAdministratorFailPass();
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Administrators");
        $this->selenium->click("link=Manage administrators");
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
        $this->assertTrue($this->selenium->isTextPresent("The password couldn't be updated. Passwords don't match"),"Edit administrator password");
    }*/


    /*function testDeleteAdministrator()
    {
        $this->loginWith() ;
//        $this->deleteAdministrator();
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Administrators");
        $this->selenium->click("link=Manage administrators");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'useradminone')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'useradminone')]/td/div/a[text()='Delete']");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue($this->selenium->isTextPresent("The admin has been deleted correctly"),"Delete administrator");
    }*/
}
?>
