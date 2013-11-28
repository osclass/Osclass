<?php
require_once dirname(__FILE__).'/../../../../oc-load.php';

//require_once('FrontendTest.php');

class OCadmin_administrators extends OCadminTest {
    
    /*           TESTS          */
    function testInsertAdministrator()
    {
        $this->insertAdministrator();
        $this->deleteAdministrator();
    }

    function testInsertAdministratorTwice()
    {
        $this->insertAdministrator();
        $this->loginWith() ;
        
        
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("xpath=//a[@id='users_administrators_manage']");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("link=Add new");
        $this->selenium->waitForPageToLoad("10000");
        
        $this->selenium->type("s_name","Real name user one");
        $this->selenium->type("s_username","useradminone");
        $this->selenium->type("s_password", "useradminpass");
        $this->selenium->type("s_email", "admin@mail.com");
        $this->selenium->type("b_moderator", "label='Moderator'");
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");
        $this->assertTrue($this->selenium->isTextPresent("Email already in use"),"Add administrator with existing email");

        $this->deleteAdministrator();
    }

    function testInsertAdministratorFail()
    {
        $this->insertAdministrator();
        $this->loginWith() ;
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("xpath=//a[@id='users_administrators_manage']");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("link=Add new");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->type("s_name","");
        $this->selenium->type("s_username","");
        $this->selenium->type("s_password", "");
        $this->selenium->type("s_email", "");
        $this->selenium->type("b_moderator", "label='Moderator'");
        $this->selenium->click("//input[@type='submit']");
        sleep(4);
        $this->assertTrue($this->selenium->isTextPresent("Name: this field is required"),"Add aministrator existing username");
        $this->assertTrue($this->selenium->isTextPresent("Username: this field is required"),"Add aministrator existing username");
        $this->assertTrue($this->selenium->isTextPresent("Email: this field is required"),"Add aministrator existing username");
        
        $this->selenium->type("s_email", "admin(at)mailcom");
        $this->selenium->click("//input[@type='submit']");
        sleep(4);
        $this->assertTrue($this->selenium->isTextPresent("Invalid email address"),"Add aministrator existing username");
        
        $this->selenium->type("s_name","Real name user one");
        $this->selenium->type("s_username","useradminone");
        $this->selenium->type("s_password", "useradminpass_");
        $this->selenium->type("s_email", "admin_@mail.com");
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");
        $this->assertTrue($this->selenium->isTextPresent("Username already in use"),"Add aministrator existing username");

        $this->deleteAdministrator();
    }

    function testEditYourProfile()
    {
        $this->insertAdministrator();
        $this->loginWith() ;
        
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("//a[@id='users_administrators_profile']");
        $this->selenium->waitForPageToLoad("10000");
        
        $this->assertFalse($this->selenium->isTextPresent("Administrators have full control"), "Edit your profile, CHANGE TYPE");
        
        $this->selenium->type("s_name","Administrator updated");
        $this->selenium->type("s_username","adminUpdated");
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");
        $this->assertTrue($this->selenium->isTextPresent("The admin has been updated"),"Edit administrator's profile");

        $this->selenium->click("//a[@id='users_administrators_profile']");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertFalse($this->selenium->isTextPresent("Administrators have full control"), "Edit your profile, CHANGE TYPE");
        $this->selenium->type("s_name","Administrator");
        $this->selenium->type("s_username","adminnewtest");
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");
        $this->assertTrue($this->selenium->isTextPresent("The admin has been updated"),"Edit administrator's profile");

        $this->deleteAdministrator();
    }

    function testEditAdministrator()
    {
        $this->insertAdministrator();
        $this->loginWith() ;
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("//a[@id='users_administrators_manage']");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->mouseOver("xpath=//table/tbody/tr[contains(.,'useradminone')]");
        $this->selenium->click("xpath=//table/tbody/tr[contains(.,'useradminone')]/td/div/ul/li/a[text()='Edit']");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->type("s_name","Real name user one NEW");
        $this->selenium->type("s_username","useradminoneNEW");
        $this->selenium->type("s_password"  , "useradminpassNEW");
        $this->selenium->type("s_password2" , "useradminpassNEW");
        $this->selenium->type("s_email", "admin@mail.com");
        $this->selenium->select("b_moderator", "label=Moderator");
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");
        $this->assertTrue($this->selenium->isTextPresent("The admin has been updated"),"Edit administrator (other)");

        
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("//a[@id='users_administrators_manage']");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->mouseOver("xpath=//table/tbody/tr[contains(.,'useradminone')]");
        $this->selenium->click("xpath=//table/tbody/tr[contains(.,'useradminone')]/td/div/ul/li/a[text()='Edit']");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->type("s_name","Real name user one NEW");
        $this->selenium->type("s_username","useradminoneNEW");
        $this->selenium->type("s_email", "newadmin@mail.com");
        $this->selenium->type("s_password"  , "");
        $this->selenium->type("s_password2" , "");
        $this->selenium->select("b_moderator", "label=Moderator");
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");
        $this->assertTrue($this->selenium->isTextPresent("The admin has been updated"),"Edit administrator (other 2)");

        $this->deleteAdministrator();
    }

    function testEditAdministratorFailPasswMatch()
    {
        $this->insertAdministrator();
        $this->loginWith() ;
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("//a[@id='users_administrators_manage']");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->mouseOver("xpath=//table/tbody/tr[contains(.,'useradminone')]");
        $this->selenium->click("xpath=//table/tbody/tr[contains(.,'useradminone')]/td/div/ul/li/a[text()='Edit']");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->type("s_name","Real name user one NEW");
        $this->selenium->type("s_username","useradminoneNEW");
        $this->selenium->type("old_password", "useradminpassNEW");
        $this->selenium->type("s_password", "bsg");
        $this->selenium->type("s_password2" , "useradminpassNEW");
        $this->selenium->select("b_moderator", "label=Moderator");
        $this->selenium->click("//input[@type='submit']");
        sleep(4);
        $this->assertTrue($this->selenium->isTextPresent("Password: enter at least 5 characters"),"Edit administrator password");
        
        $this->selenium->type("s_password", "valkiria");
        $this->selenium->click("//input[@type='submit']");
        sleep(4);
        $this->assertTrue($this->selenium->isTextPresent("Passwords don't match"),"Edit administrator password");
        
        $this->selenium->type("s_password"  , "useradminpassNEW");
        $this->selenium->type("s_password2" , "useradminpassNEW");
        $this->selenium->type("s_email", "admin@mail.com");
        $this->selenium->select("b_moderator", "label=Moderator");
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");
        $this->assertTrue($this->selenium->isTextPresent("The admin has been updated"),"Edit administrator password");

        $this->deleteAdministrator();
    }
    
    
    function testModeratorAccess()
    {
        $this->insertAdministrator();
        
        $this->loginWith() ;
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("//a[@id='users_administrators_manage']");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->mouseOver("xpath=//table/tbody/tr[contains(.,'useradminone')]");
        $this->selenium->click("xpath=//table/tbody/tr[contains(.,'useradminone')]/td/div/ul/li/a[text()='Edit']");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->select("b_moderator", "label=Moderator");
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");
        $this->assertTrue($this->selenium->isTextPresent("The admin has been updated"),"Edit administrator (other)");

        
        
        $this->logout();
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->waitForPageToLoad(10000);
        $this->selenium->type('user', "useradminone");
        $this->selenium->type('password', "useradminpass");
        $this->selenium->click('submit');
        $this->selenium->waitForPageToLoad(10000);
        $this->assertTrue($this->selenium->isTextPresent("Dashboard"),"Moderator access");

        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("//a[@id='items_manage']");
        $this->selenium->waitForPageToLoad(10000);
        sleep(4);
        $this->assertTrue($this->selenium->isTextPresent("Manage listings"),"Moderator access");
        
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("//a[@id='items_comments']");
        $this->selenium->waitForPageToLoad(10000);
        sleep(4);
        $this->assertTrue($this->selenium->isTextPresent("Comments"),"Moderator access");
        
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("//a[@id='items_media']");
        $this->selenium->waitForPageToLoad(10000);
        sleep(4);
        $this->assertTrue($this->selenium->isTextPresent("Manage Media"),"Moderator access");
        
        $this->selenium->open( osc_admin_base_url(true)."?page=admins" );
        $this->selenium->waitForPageToLoad(10000);
        $this->assertTrue($this->selenium->isTextPresent("You don't have enough permissions"),"Moderator access");
        
        $this->selenium->open( osc_admin_base_url(true)."?page=users" );
        $this->selenium->waitForPageToLoad(10000);
        $this->assertTrue($this->selenium->isTextPresent("You don't have enough permissions"),"Moderator access");
        
        $this->selenium->open(osc_admin_base_url(true).'?page=admins&action=edit');
        $this->selenium->waitForPageToLoad("2000");
        $this->assertTrue($this->selenium->isTextPresent("Edit admin"), "Don't have enough permissions" ) ;

        $this->selenium->open(osc_admin_base_url(true).'?page=admins&action=add');
        $this->selenium->waitForPageToLoad("2000");
        $this->assertTrue($this->selenium->isTextPresent("You don't have enough permissions"), "Don't have enough permissions" ) ;

        $this->selenium->open( osc_admin_base_url(true)."?page=stats" );
        $this->selenium->waitForPageToLoad(10000);
        $this->assertTrue($this->selenium->isTextPresent("Number of new listings"),"Moderator access");
        
        $this->selenium->open( osc_admin_base_url(true)."?page=settings" );
        $this->selenium->waitForPageToLoad(10000);
        $this->assertTrue($this->selenium->isTextPresent("You don't have enough permissions"),"Moderator access");
        
        $this->selenium->open( osc_admin_base_url(true)."?page=emails" );
        $this->selenium->waitForPageToLoad(10000);
        $this->assertTrue($this->selenium->isTextPresent("You don't have enough permissions"),"Moderator access");
        
        $this->selenium->open( osc_admin_base_url(true)."?page=tools" );
        $this->selenium->waitForPageToLoad(10000);
        $this->assertTrue($this->selenium->isTextPresent("You don't have enough permissions"),"Moderator access");
        
        $this->selenium->open( osc_admin_base_url(true)."?page=languages" );
        $this->selenium->waitForPageToLoad(10000);
        $this->assertTrue($this->selenium->isTextPresent("You don't have enough permissions"),"Moderator access");
        
        $this->selenium->open( osc_admin_base_url(true)."?page=plugins" );
        $this->selenium->waitForPageToLoad(10000);
        $this->assertTrue($this->selenium->isTextPresent("You don't have enough permissions"),"Moderator access");
        
        $this->selenium->open( osc_admin_base_url(true)."?page=pages" );
        $this->selenium->waitForPageToLoad(10000);
        $this->assertTrue($this->selenium->isTextPresent("You don't have enough permissions"),"Moderator access");
        
        $this->selenium->open( osc_admin_base_url(true)."?page=appearance" );
        $this->selenium->waitForPageToLoad(10000);
        $this->assertTrue($this->selenium->isTextPresent("You don't have enough permissions"),"Moderator access");
        
        $this->logout();
        $this->deleteAdministrator();
    }


    function insertAdministrator()
    {
        $this->loginWith() ;
        
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("xpath=//a[@id='users_administrators_manage']");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("link=Add new");
        $this->selenium->waitForPageToLoad("10000");
        
        $this->selenium->type("s_name","Real name user one");
        $this->selenium->type("s_username","useradminone");
        $this->selenium->type("s_password", "useradminpass");
        $this->selenium->type("s_email", "admin@mail.com");
        $this->selenium->type("b_moderator", "label='Moderator'");
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");
        $this->assertTrue($this->selenium->isTextPresent("The admin has been added"),"Add administrator");
    }
    
    function deleteAdministrator()
    {
        $this->loginWith() ;
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("//a[@id='users_administrators_manage']");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->mouseOver("xpath=//table/tbody/tr[contains(.,'useradminone')]");
        $this->selenium->click("xpath=//table/tbody/tr[contains(.,'useradminone')]/td/div/ul/li/a[text()='Delete']");
        $this->selenium->click("//input[@id='admin-delete-submit']");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue($this->selenium->isTextPresent("The admin has been deleted correctly"),"Delete administrator");
    }  
}
?>