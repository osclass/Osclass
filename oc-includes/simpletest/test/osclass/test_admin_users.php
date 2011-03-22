<?php
require_once('../../autorun.php');
require_once('../../web_tester.php');
require_once('../../reporter.php');

// LOAD OSCLASS
require_once '../../../../oc-load.php';
require_once LIB_PATH . 'Selenium.php';

class TestOfAdminUsers extends WebTestCase {

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
    function testUserInsert()
    {
        echo "<div style='background-color: green; color: white;'><h2>testUserInsert</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testUserInsert - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testUserInsert - INSERT NEW USER</div>";
        $this->insertUser() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testUserInsert - DELETE USER</div>";
        $this->deleteUser();
        flush();
    }

    function testUserInsertbyLink()
    {
        echo "<div style='background-color: green; color: white;'><h2>testUserInsertbyLink</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testLantestUserInsertbyLinkguageInsertbyLink - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testUserInsertbyLink - INSERT NEW USER</div>";
        $this->insertUserByLink() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testUserInsert - DELETE USER</div>";
        $this->deleteUser();
        flush();
    }

    public function testUserEdit()
    {
        echo "<div style='background-color: green; color: white;'><h2>testUserEdit</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testUserEdit - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testUserInsert - INSERT NEW USER</div>";
        $this->insertUser() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testUserEdit - EDIT USER</div>";
        $this->editUser();
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testUserInsert - DELETE USER</div>";
        $this->deleteUser();
        flush();
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
    private function insertUser()
    {
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->click("link=Users");
        $this->selenium->click("link=» Add new user");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type("s_email"         ,"test@mail.com");
        $this->selenium->type("s_password"      ,"password");
        $this->selenium->type("s_password2"     ,"password");

        $this->selenium->type("s_name"          ,"real name user");

        $this->selenium->type("s_phone_mobile"  ,"666666666");
        $this->selenium->type("s_phone_land"    ,"930112233");

        $this->selenium->type("s_website"       ,"http://osclass.org");
        $this->selenium->type("s_info[en_US]"   ,"foobar description");

        $this->selenium->type("cityArea"        ,"city area");
        $this->selenium->type("address"         ,"address user");

        $this->selenium->select("regionId"      , "label=Barcelona");
        $this->selenium->select("cityId"        , "label=Sabadell");
        $this->selenium->select("b_company"     , "label=User");
        
        $this->selenium->click("//form/input[@id='button_save']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("The user has been created and activated"),"Can't create new user");
    }
    private function insertUserByLink()
    {
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->click("link=Users");
        $this->selenium->click("link=» Add new user");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("link=Add a new user");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type("s_email"         ,"test1@mail.com");
        $this->selenium->type("s_password"      ,"password");
        $this->selenium->type("s_password2"     ,"password");

        $this->selenium->type("s_name"          ,"real name user");

        $this->selenium->type("s_phone_mobile"  ,"666666666");
        $this->selenium->type("s_phone_land"    ,"930112233");

        $this->selenium->type("s_website"       ,"http://osclass.org");
        $this->selenium->type("s_info[en_US]"   ,"foobar description");

        $this->selenium->type("cityArea"        ,"city area");
        $this->selenium->type("address"         ,"address user");

        $this->selenium->select("regionId"      , "label=Barcelona");
        $this->selenium->select("cityId"        , "label=Sabadell");
        $this->selenium->select("b_company"     , "label=User");

        $this->selenium->click("//form/input[@id='button_save']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("The user has been created and activated"),"Can't create new user");
    }

    private function editUser()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Users");
        $this->selenium->click("link=» Manage users");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'mail.com')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'mail.com')]/td/div/a[text()='Edit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type("s_email"         ,"newtest@mail.com");
        $this->selenium->type("s_password"      ,"newpassword");
        $this->selenium->type("s_password2"     ,"newpassword");

        $this->selenium->type("s_name"          ,"new real name user");

        $this->selenium->type("s_phone_mobile"  ,"999999999");
        $this->selenium->type("s_phone_land"    ,"332211039");

        $this->selenium->type("s_website"       ,"http://osclass.org");
        $this->selenium->type("s_info[en_US]"   ,"new foobar description");

        $this->selenium->type("cityArea"        ,"new city area");
        $this->selenium->type("address"         ,"new address user");

        $this->selenium->select("regionId"      , "label=Madrid");
        $this->selenium->select("cityId"        , "label=La Acebeda");
        $this->selenium->select("b_company"     , "label=Company");

        $this->selenium->click("xpath=//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("The user has been updated"),"Can't edit user. ERROR");
    }

    private function deleteUser()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Users");
        $this->selenium->click("link=» Manage users");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'mail.com')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'mail.com')]/td/div/a[text()='Delete']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("One user has been deleted"), "Can't delete user ERROR" ) ;
    }

}
?>
