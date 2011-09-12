<?php
require_once('../../autorun.php');
require_once('../../web_tester.php');
require_once('../../reporter.php');

// LOAD OSCLASS
require_once '../../../../oc-load.php';
require_once LIB_PATH . 'Selenium.php';

class TestOfUser extends WebTestCase {

    private $selenium;
    private $array = array();
    private $email;
    private $email_fixed;
    private $password;

    function setUp()
    {
        echo "<br><div style='background-color: Wheat; color: black;'>init test</div>";
        $this->selenium = new Testing_Selenium("*firefox", "http://localhost/");
        $this->selenium->start();
        $this->selenium->setSpeed("150");
    }

    function tearDown()
    {
        $this->selenium->stop();
        echo "<div style='background-color: Wheat; color: black;'>end test</div>";
        flush();
    }

    function  __construct() {
        echo "insert new user for testing<br>";
        $input['s_secret']          = osc_genRandomPassword() ;
        $input['dt_reg_date']       = DB_FUNC_NOW ;
        $input['s_name']            = "Carlos";
        $input['s_website']         = "www.osclass.org";
        $input['s_phone_land']      = "931234567";
        $input['s_phone_mobile']    = "666121212";
        $input['fk_c_country_code'] = null ;
        $input['s_country']         = null ;
        $input['fk_i_region_id']    = null ;
        $input['s_region']          = "" ;
        $input['fk_i_city_id']      = null ;
        $input['s_city']            = "";
        $input['s_city_area']       = "";
        $input['s_address']         = "c:/address nº 10 2º2ª";
        $input['b_company']         = 0;
        $input['b_enabled']         = 1;
        $input['b_active']          = 1;
        $input['s_email']           = "carlos+user@osclass.org";
        $this->email                = "carlos+user@osclass.org";
        $input['s_password']        = sha1('carlos');
        $this->password             = "carlos";

        $this->array = $input;

        User::newInstance()->insert($input) ;
        $input['s_email']           = "carlos+test@osclass.org";
        $this->email_fixed          = "carlos+test@osclass.org";
        User::newInstance()->insert($input) ;
    }

    

    /*           TESTS          */
    function testChangePassword()
    {
        echo "<div style='background-color: green; color: white;'><h2>test_frontend_user - testChangePassword</h2></div>";
        $this->changePassword();
    }

    function testChangeEmail()
    {
        echo "<div style='background-color: green; color: white;'><h2>test_frontend_user - testChangeEmail</h2></div>";
        $this->changeEmail();
    }

    function testUpdateProfile()
    {
        echo "<div style='background-color: green; color: white;'><h2>test_frontend_user - testUpdateProfile</h2></div>";
        $this->profile();
    }

    // nothing to test ?
//    function testDashboard()
//    {
//        echo "<div style='background-color: green; color: white;'>FRONTEND - <h2>testDashboard</h2> -</div>";
//        echo "<div style='background-color: green; color: white;padding-left:15px;'></div>";
//    }

    function  testdeleteUser() {
        echo "delete user for testing<br>";
        $user = User::newInstance()->findByEmail($this->email);
        User::newInstance()->deleteUser($user['pk_i_id']);
        $user = User::newInstance()->findByEmail($this->email_fixed);
        User::newInstance()->deleteUser($user['pk_i_id']);
    }

    /*
     * PRIVATE FUNCTIONS
     */
    private function login()
    {
        $this->selenium->open( osc_base_url(true) );
        $this->selenium->click("login_open");
        $this->selenium->type("email"   , $this->email);
        $this->selenium->type("password", $this->password);

        $this->selenium->click("//button[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");

        echo "<div style='background-color: green; color: white;padding-left:15px;'>Login user ...</div>";
        if($this->selenium->isTextPresent("User account manager")){
            $this->assertTrue("ok");
        }
    }

    private function changePassword()
    {
        $this->login();
        $this->selenium->click("xpath=//ul/li/a[text()='My account']");
        $this->selenium->waitForPageToLoad("30000");

        $this->selenium->click("link=Modify password");
        $this->selenium->waitForPageToLoad("30000");

        echo "<div style='background-color: green; color: white;padding-left:15px;'>test - current password don't match</div>";
        // test - current password don't match
        $this->selenium->type("password"        , "qwerty");
        $this->selenium->type("new_password"    , $this->password);
        $this->selenium->type("new_password2"   , $this->password);
        $this->selenium->click("//button[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");
        echo "< ".$this->selenium->getText('//*[@id="FlashMessage"]')." ><br>";
        $this->assertTrue( $this->selenium->isTextPresent("Current password doesn't match"),
                           "Can change password even if current password don't match. ERROR");

        echo "<div style='background-color: green; color: white;padding-left:15px;'>test - Passwords can't be empty</div>";
        // test - Passwords can't be empty
        $this->selenium->type("password"        , $this->password);
        $this->selenium->type("new_password"    , '');
        $this->selenium->type("new_password2"   , $this->password);
        $this->selenium->click("//button[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");
echo "< ".$this->selenium->getText('//*[@id="FlashMessage"]')." ><br>";
        $this->assertTrue( $this->selenium->isTextPresent("Password cannot be blank"),
                           "Passwords can be EMPTY. ERROR");
                   
        echo "<div style='background-color: green; color: white;padding-left:15px;'>test - Passwords don't match</div>";
        // test - Passwords don't match
        $this->selenium->type("password"        , $this->password);
        $this->selenium->type("new_password"    , 'abc');
        $this->selenium->type("new_password2"   , 'def');
        $this->selenium->click("//button[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");
echo "< ".$this->selenium->getText('//*[@id="FlashMessage"]')." ><br>";
        $this->assertTrue( $this->selenium->isTextPresent("Passwords don't match"),
                           "Passwords can don't match. ERROR");

        echo "<div style='background-color: green; color: white;padding-left:15px;'>test - Password has been changed</div>";
        // test - Passwords can't be empty
        $this->selenium->type("password"        , $this->password);
        $this->password = "new_password";
        $this->selenium->type("new_password"    , $this->password);
        $this->selenium->type("new_password2"   , $this->password);
        $this->selenium->click("//button[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");

        $this->assertTrue( $this->selenium->isTextPresent("Password has been changed"),
                           "Has not been updated. ERROR");
    }

    private function changeEmail()
    {
        $this->login();
        $this->selenium->click("xpath=//ul/li/a[text()='My account']");
        $this->selenium->waitForPageToLoad("30000");

        $this->selenium->click("link=Modify e-mail");
        $this->selenium->waitForPageToLoad("30000");

        echo "<div style='background-color: green; color: white;padding-left:15px;'>test - The specified e-mail is already in use </div>";
        // test - The specified e-mail is already in use
        $this->selenium->type("email"     , $this->email_fixed);
        $this->selenium->type("new_email" , $this->email_fixed);
        $this->selenium->click("//button[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");

        $this->assertTrue( $this->selenium->isTextPresent("The specified e-mail is already in use"),
                           "Can change email even if it exist. ERROR");
        
        /*
         *   ------------     Force validation !  =>  enabled_user_validation()   ------------------
         */
        Preference::newInstance()->update(array('s_value' => 1)
                                         ,array('s_name'  => 'enabled_user_validation'));
        $bool = Preference::newInstance()->findValueByName('enabled_user_validation');
        if($bool) {echo "enabled_user_validation() == true<br>";}else{echo "enabled_user_validation() == false<br>";}

        echo "<div style='background-color: green; color: white;padding-left:15px;'>test - Change email </div>";
        // test -
        $this->selenium->click("xpath=//ul/li/a[text()='My account']");
        $this->selenium->waitForPageToLoad("30000");

        $this->selenium->click("link=Modify e-mail");
        $this->selenium->waitForPageToLoad("30000");

        $this->selenium->type("email"     , $this->email);
        $this->selenium->type("new_email" , "carlos+new@osclass.org");
        $this->selenium->click("//button[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");        

        $this->assertTrue( $this->selenium->isTextPresent("We have sent you an e-mail. Follow the instructions to validate the changes"),
                           "Can't change email!. ERROR");
        
        /*
         *   ------------     reversing values  =>  enabled_user_validation()   ------------------
         */
        Preference::newInstance()->update(array('s_value' => 0)
                                         ,array('s_name'  => 'enabled_user_validation'));
        
        $bool = Preference::newInstance()->findValueByName('enabled_user_validation');
        if($bool) {echo "enabled_user_validation() == true<br>";}else{echo "enabled_user_validation() == false<br>";}
        
        echo "<div style='background-color: green; color: white;padding-left:15px;'>test - Change email </div>";
        // test -
        $this->selenium->click("xpath=//ul/li/a[text()='My account']");
        $this->selenium->waitForPageToLoad("30000");

        $this->selenium->click("link=Modify e-mail");
        $this->selenium->waitForPageToLoad("30000");
        
        $this->selenium->type("email"     , $this->email);
        $this->selenium->type("new_email" , "carlos+new@osclass.org");
        $this->selenium->click("//button[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");
echo "< ".$this->selenium->getText('//*[@id="FlashMessage"]')." ><br>";
        $this->assertTrue( $this->selenium->isTextPresent("We have sent you an e-mail. Follow the instructions to validate the changes"),
                           "Can't change email!. ERROR");

        Preference::newInstance()->update(array('s_value' => 1)
                                         ,array('s_name'  => 'enabled_user_validation'));
    }

    private function profile()
    {
        $this->login();
        $this->selenium->click("xpath=//ul/li/a[text()='My account']");
        $this->selenium->waitForPageToLoad("30000");
        
        echo "<div style='background-color: green; color: white;padding-left:15px;'>test - updating user profile</div>";
        $this->selenium->type("s_name", "new carlos");
        $this->selenium->select("b_company", "label=Company");
        $this->selenium->type("s_phone_mobile", "666111111");
        $this->selenium->type("s_phone_land", "930111111");
        $this->selenium->select("regionId", "label=Barcelona");
        $this->selenium->select("cityId", "label=Sabadell");
        $this->selenium->type("cityArea", "area Sabadell");
        $this->selenium->type("address", "new address");
        $this->selenium->type("s_website", "www.osclass.org");
        $this->selenium->click("//button[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");
        
        $this->assertTrue($this->selenium->isTextPresent("Your profile has been updated successfully"), "Update user profile failed! ERROR");

    }

}
?>