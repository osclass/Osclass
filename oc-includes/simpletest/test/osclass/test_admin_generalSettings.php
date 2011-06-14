<?php
require_once('../../autorun.php');
require_once('../../web_tester.php');
require_once('../../reporter.php');

// LOAD OSCLASS
require_once '../../../../oc-load.php';
require_once LIB_PATH . 'Selenium.php';

class TestOfAdminGeneralSettings extends WebTestCase {

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
    function testCronTab()
    {
        echo "<div style='background-color: green; color: white;'><h2>testCronTab</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testCronTab - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testCronTab - ON/OFF CRON</div>";
        $this->cronTab() ;
        flush();
    }

    function testMediaTab()
    {
        echo "<div style='background-color: green; color: white;'><h2>testMediaTab</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testMediaTab - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testMediaTab - MEDIA SETTINGS</div>";
        $this->mediaTab() ;
        flush();
    }

    function testMailServerTab()
    {
        echo "<div style='background-color: green; color: white;'><h2>testMailServerTab</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testMailServerTab - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testMailServerTab - MAIL SERVER SETTINGS</div>";
        $this->mailServer() ;
        flush();
    }

    function testSpamAndBotsTab()
    {
        echo "<div style='background-color: green; color: white;'><h2>testSpamAndBotsTab</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testSpamAndBotsTab - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testSpamAndBotsTab - SPAM AND BOTS SETTINGS</div>";
        $this->spamAndBots() ;
        flush();
    }

    function testPermalinksTab()
    {
        echo "<div style='background-color: green; color: white;'><h2>testPermalinksTab</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testPermalinksTab - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testPermalinksTab - PERMALINKS SETTINGS</div>";
        $this->permalinks() ;
        flush();
    }

    function testContactTab()
    {
        echo "<div style='background-color: green; color: white;'><h2>testContactTab</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testContactTab - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testContactTab - CONTACT SETTINGS</div>";
        $this->contact() ;
        flush();
    }

    function testUsersTab()
    {
        echo "<div style='background-color: green; color: white;'><h2>testUsersTab</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testUsersTab - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testUsersTab - USER SETTINGS</div>";
        $this->users() ;
        flush();
    }

    function testCommentsTab()
    {
        echo "<div style='background-color: green; color: white;'><h2>testCommentsTab</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testCommentsTab - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testCommentsTab - COMMENTS SETTINGS</div>";
        $this->comments() ;
        flush();
    }

//    function testItemsTab()
//    {
//        echo "<div style='background-color: green; color: white;'><h2>testItemsTab</h2></div>";
//        echo "<div style='background-color: green; color: white;padding-left:15px;'>testItemsTab - LOGIN </div>";
//        $this->loginCorrect() ;
//        flush();
//        echo "<div style='background-color: green; color: white;padding-left:15px;'>testItemsTab - ITEMS SETTINGS</div>";
//        $this->items() ;
//        flush();
//    }
    
//    function testCategoriesTab()
//    {
//        echo "<div style='background-color: green; color: white;'><h2>testCategoriesTab</h2></div>";
//        echo "<div style='background-color: green; color: white;padding-left:15px;'>testCategoriesTab - LOGIN </div>";
//        $this->loginCorrect() ;
//        flush();
//        echo "<div style='background-color: green; color: white;padding-left:15px;'>testCategoriesTab - CATEGORIES SETTINGS</div>";
//        $this->categories() ;
//        flush();
//    }

    function testGeneralSettings()
    {
        echo "<div style='background-color: green; color: white;'><h2>testGeneralSettings</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testGeneralSettings - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testGeneralSettings - GENERAL SETTINGS </div>";
        $this->generalSettings() ;
        flush();
    }

    function testLocations()
    {
        echo "<div style='background-color: green; color: white;'><h2>testLocations</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testLocations - LOGIN </div>";
        $this->loginCorrect();
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testLocations - LOCATIONS SETTINGS - ADD FROM GEO & EDIT & DELETE </div>";
        $this->locationsGEO();
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testLocations - LOCATIONS SETTINGS - ADD NEW GEO & EDIT & DELETE </div>";
        $this->locationsNEW();
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testLocations - LOCATIONS SETTINGS - (ADD FORCE ERROR) </div>";
        $this->locationsNEWForceError();
        flush();
    }

    function testCurrencies()
    {
        echo "<div style='background-color: green; color: white;'><h2>testCurrencies</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testCurrencies - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testCurrencies - ADD & EDIT & DELETE CURRENCIES SETTINGS </div>";
        $this->currency() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testCurrencies - ADD CURRENCIES SETTINGS (INSERT TWICE) </div>";
        $this->addCurrencyTwice() ;
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

    private function categories()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=General settings");
        $this->selenium->click("link=» Categories");
        $this->selenium->waitForPageToLoad("10000");
        
        
        
        $selectable_parent_categories = Preference::newInstance()->findValueByName('selectable_parent_categories');
        if($selectable_parent_categories == 1){ $selectable_parent_categories = 'on';} else { $selectable_parent_categories = 'off'; }
        
        $this->selenium->click("selectable_parent_categories");
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");
        
        $this->assertTrue($this->selenium->isTextPresent("Categories' settings have been updated"), "Can't update categories settings. ERROR");
        if($selectable_parent_categories == 'on' ) {
            $this->assertEqual( $this->selenium->getValue("selectable_parent_categories")   , 'off');
        } else {
            $this->assertEqual( $this->selenium->getValue("selectable_parent_categories")   , 'on');
        }
        
        $this->selenium->click("selectable_parent_categories");
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");
        
        $this->assertTrue($this->selenium->isTextPresent("Categories' settings have been updated"), "Can't update categories settings. ERROR");
        $this->assertEqual( $this->selenium->getValue("selectable_parent_categories")   , $selectable_parent_categories );
    }
    
    private function cronTab()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=General settings");
        $this->selenium->click("link=» Cron system");
        $this->selenium->waitForPageToLoad("10000");
        
        $cron = Preference::newInstance()->findValueByName('auto_cron');
        if($cron == 1){ $cron = 'on';} else { $cron = 'off'; }

        $this->assertEqual($cron, $this->selenium->getValue("auto_cron"), "DB Value != BackOffice value");

        $this->selenium->click("auto_cron");
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");

        $cron = Preference::newInstance()->findValueByName('auto_cron');
        if($cron == 1){ $cron = 'on';} else { $cron = 'off'; }
        
        $this->assertEqual($cron, $this->selenium->getValue("auto_cron"), "DB Value != BackOffice value");
    }

    private function mediaTab()
    {
        $maxSizeKb      = Preference::newInstance()->findValueByName('maxSizeKb');
        $allowedExt     = Preference::newInstance()->findValueByName('allowedExt');
        $dimThumbnail   = Preference::newInstance()->findValueByName('dimThumbnail');
        $dimPreview     = Preference::newInstance()->findValueByName('dimPreview');
        $dimNormal      = Preference::newInstance()->findValueByName('dimNormal');
        $keep_original_image   = Preference::newInstance()->findValueByName('keep_original_image');
        if($keep_original_image == 1){ $keep_original_image = 'on';} else { $keep_original_image = 'off'; }

        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=General settings");
        $this->selenium->click("link=» Media");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertEqual($maxSizeKb   , '2048');
        $this->assertEqual($allowedExt  , 'png,gif,jpg');
        $this->assertEqual($dimThumbnail, '240x200');
        $this->assertEqual($dimPreview  , '480x340');
        $this->assertEqual($dimNormal   , '640x480');
        $this->assertEqual($keep_original_image, 'on');

        // change values to some test-defined ones

        $this->selenium->type('maxSizeKb'   , '500000');
        $this->selenium->type('allowedExt'  , 'ext,deg,osc');
        $this->selenium->type('dimThumbnail', '10x10');
        $this->selenium->type('dimPreview'  , '50x50');
        $this->selenium->type('dimNormal'   , '100x100');
        $this->selenium->click('keep_original_image');

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("Media config has been updated"), "Can't update media settings. ERROR");

        $this->assertEqual( $this->selenium->getValue("maxSizeKb")      , '500000');
        $this->assertEqual( $this->selenium->getValue('allowedExt')     , 'ext,deg,osc');
        $this->assertEqual( $this->selenium->getValue('dimThumbnail')   , '10x10');
        $this->assertEqual( $this->selenium->getValue('dimPreview')     , '50x50');
        $this->assertEqual( $this->selenium->getValue('dimNormal')      , '100x100');
        $this->assertEqual( $this->selenium->getValue('keep_original_image'), 'off');

        $this->selenium->type('maxSizeKb'   , $maxSizeKb);
        $this->selenium->type('allowedExt'  , $allowedExt);
        $this->selenium->type('dimThumbnail', $dimThumbnail);
        $this->selenium->type('dimPreview'  , $dimPreview);
        $this->selenium->type('dimNormal'   , $dimNormal);
        $this->selenium->click('keep_original_image');

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("Media config has been updated"), "Can't update media settings. ERROR");

        $this->assertEqual( $this->selenium->getValue("maxSizeKb")      , $maxSizeKb);
        $this->assertEqual( $this->selenium->getValue('allowedExt')     , $allowedExt);
        $this->assertEqual( $this->selenium->getValue('dimThumbnail')   , $dimThumbnail);
        $this->assertEqual( $this->selenium->getValue('dimPreview')     , $dimPreview);
        $this->assertEqual( $this->selenium->getValue('dimNormal')      , $dimNormal);
        $this->assertEqual( $this->selenium->getValue('keep_original_image'), 'on');
    }

    private function mailServer()
    {
        $pref = array();
        $pref['mailserver_type']        = Preference::newInstance()->findValueByName('mailserver_type');
        $pref['mailserver_host']        = Preference::newInstance()->findValueByName('mailserver_host');
        $pref['mailserver_port']        = Preference::newInstance()->findValueByName('mailserver_port');
        $pref['mailserver_username']    = Preference::newInstance()->findValueByName('mailserver_username');
        $pref['mailserver_password']    = Preference::newInstance()->findValueByName('mailserver_password');
        $pref['mailserver_ssl']         = Preference::newInstance()->findValueByName('mailserver_ssl');
        $pref['mailserver_auth']        = Preference::newInstance()->findValueByName('mailserver_auth');
        if($pref['mailserver_auth'] == 1){ $pref['mailserver_auth'] = 'on';} else { $pref['mailserver_auth'] = 'off'; }

        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=General settings");
        $this->selenium->click("link=» Mail Server");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type('mailserver_type'     , 'custom');
        $this->selenium->type('mailserver_host'     , 'mailserver.test.net');
        $this->selenium->type('mailserver_port'     , '1234');
        $this->selenium->type('mailserver_username' , 'test');
        $this->selenium->type('mailserver_password' , 'test');
        $this->selenium->type('mailserver_ssl'      , 'ssltest');
        $this->selenium->click('mailserver_auth');
        
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent( 'Mail server configuration has changed') , "Can't update mail server configuration. ERROR");

        $this->assertEqual( $this->selenium->getValue("mailserver_type")     , 'custom');
        $this->assertEqual( $this->selenium->getValue('mailserver_host')     , 'mailserver.test.net');
        $this->assertEqual( $this->selenium->getValue('mailserver_port')     , '1234');
        $this->assertEqual( $this->selenium->getValue('mailserver_username') , 'test');
        $this->assertEqual( $this->selenium->getValue('mailserver_password') , 'test');
        $this->assertEqual( $this->selenium->getValue('mailserver_ssl')      , 'ssltest');
        $this->assertEqual( $this->selenium->getValue('mailserver_auth')     , 'on');

        $this->selenium->type('mailserver_type'     , $pref['mailserver_type']);
        $this->selenium->type('mailserver_host'     , $pref['mailserver_host']);
        $this->selenium->type('mailserver_port'     , $pref['mailserver_port']);
        $this->selenium->type('mailserver_username' , $pref['mailserver_username']);
        $this->selenium->type('mailserver_password' , $pref['mailserver_password']);
        $this->selenium->type('mailserver_ssl'      , $pref['mailserver_ssl']);
        $this->selenium->click('mailserver_auth');

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent( 'Mail server configuration has changed') , "Can't update mail server configuration. ERROR");

        $this->assertEqual( $this->selenium->getValue("mailserver_type")     , $pref['mailserver_type']);
        $this->assertEqual( $this->selenium->getValue('mailserver_host')     , $pref['mailserver_host']);
        $this->assertEqual( $this->selenium->getValue('mailserver_port')     , $pref['mailserver_port']);
        $this->assertEqual( $this->selenium->getValue('mailserver_username') , $pref['mailserver_username']);
        $this->assertEqual( $this->selenium->getValue('mailserver_password') , $pref['mailserver_password']);
        $this->assertEqual( $this->selenium->getValue('mailserver_ssl')      , $pref['mailserver_ssl']);
        $this->assertEqual( $this->selenium->getValue('mailserver_auth')     , $pref['mailserver_auth']);
        unset($pref);
    }

    private function spamAndBots()
    {
        $pref = array();
        $pref['akismet_key']        = Preference::newInstance()->findValueByName('akismet_key');
        $pref['recaptchaPrivKey']   = Preference::newInstance()->findValueByName('recaptchaPrivKey');
        $pref['recaptchaPubKey']    = Preference::newInstance()->findValueByName('recaptchaPubKey');

        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=General settings");
        $this->selenium->click("link=» Spam and bots");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type('akismetKey'          , '1234567890');
        $this->selenium->type('recaptchaPrivKey'    , '1234567890');
        $this->selenium->type('recaptchaPubKey'     , '1234567890');

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("Akismet and reCAPTCHA have been updated") ,"Can't update Spam and bots. ERROR");

        $this->assertEqual( $this->selenium->getValue('akismetKey')         , '1234567890');
        $this->assertEqual( $this->selenium->getValue('recaptchaPrivKey')   , '1234567890');
        $this->assertEqual( $this->selenium->getValue('recaptchaPubKey')    , '1234567890');

        $this->selenium->type('akismetKey'         , $pref['akismet_key']);
        $this->selenium->type('recaptchaPrivKey'    , $pref['recaptchaPrivKey']);
        $this->selenium->type('recaptchaPubKey'     , $pref['recaptchaPubKey']);

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("Akismet and reCAPTCHA have been updated") ,"Can't update Spam and bots. ERROR");

        $this->assertEqual( $this->selenium->getValue('akismetKey')         , $pref['akismet_key']);
        $this->assertEqual( $this->selenium->getValue('recaptchaPrivKey')   , $pref['recaptchaPrivKey']);
        $this->assertEqual( $this->selenium->getValue('recaptchaPubKey')    , $pref['recaptchaPubKey']);
    }

    private function permalinks()
    {
        $pref = array();
        $pref['rewrite_enabled'] = Preference::newInstance()->findValueByName('rewriteEnabled') ;
        if($pref['rewrite_enabled'] == 1){ $pref['rewrite_enabled'] = 'on';} else { $pref['rewrite_enabled'] = 'off'; }

        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=General settings");
        $this->selenium->click("link=» Permalinks");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertEqual( $this->selenium->getValue('rewrite_enabled'), $pref['rewrite_enabled'] ) ;

        $this->selenium->click("xpath=//input[@id='rewrite_enabled']");
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        if( $pref['rewrite_enabled'] == 'on' ) {
            $this->assertEqual( $this->selenium->getValue('rewrite_enabled'), 'off');
        }else{
            $this->assertEqual( $this->selenium->getValue('rewrite_enabled'), 'on');
        }

        $this->selenium->click("xpath=//input[@id='rewrite_enabled']");
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertEqual( $this->selenium->getValue('rewrite_enabled'), $pref['rewrite_enabled'] ) ;
    }

    private function contact()
    {
        $pref = array();
        $pref['contact_attachment'] = Preference::newInstance()->findValueByName('contact_attachment') ;
        if($pref['contact_attachment'] == 1){ $pref['contact_attachment'] = 'on';} else { $pref['contact_attachment'] = 'off'; }

        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=General settings");
        $this->selenium->click("link=» Contact");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertEqual( $this->selenium->getValue('enabled_attachment'), $pref['contact_attachment'] ) ;

        $this->selenium->click("enabled_attachment");
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("Contact configuration has been updated") ,"Can't update contact Attachment. ERROR");

        if( $pref['contact_attachment'] == 'on' ) {
            $this->assertEqual( $this->selenium->getValue('enabled_attachment'), 'off' ) ;
        } else {
            $this->assertEqual( $this->selenium->getValue('enabled_attachment'), 'on' ) ;
        }

        $this->selenium->click("enabled_attachment");
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("Contact configuration has been updated") ,"Can't update contact Attachment. ERROR");

        $this->assertEqual( $this->selenium->getValue('enabled_attachment'), $pref['contact_attachment'] ) ;
    }

    private function users()
    {
        $pref = array();
        $pref['enabled_users'] = Preference::newInstance()->findValueByName('enabled_users') ;
        if($pref['enabled_users'] == 1){ $pref['enabled_users'] = 'on';} else { $pref['enabled_users'] = 'off'; }
        $pref['enabled_user_validation'] = Preference::newInstance()->findValueByName('enabled_user_validation') ;
        if($pref['enabled_user_validation'] == 1){ $pref['enabled_user_validation'] = 'on';} else { $pref['enabled_user_validation'] = 'off'; }
        $pref['enabled_user_registration'] = Preference::newInstance()->findValueByName('enabled_user_registration') ;
        if($pref['enabled_user_registration'] == 1){ $pref['enabled_user_registration'] = 'on';} else { $pref['enabled_user_registration'] = 'off'; }

        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=General settings");
        $this->selenium->click("link=» Users");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->click("enabled_users");
        $this->selenium->click("enabled_user_validation");
        $this->selenium->click("enabled_user_registration");

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("Users' settings have been updated") , "Can't update user settings. ERROR");

        if( $pref['enabled_users'] == 'on' ){
            $this->assertEqual( $this->selenium->getValue('enabled_users'), 'off' ) ;
        } else {
            $this->assertEqual( $this->selenium->getValue('enabled_users'), 'on' ) ;
        }
        if( $pref['enabled_user_validation'] == 'on' ){
            $this->assertEqual( $this->selenium->getValue('enabled_user_validation'), 'off' ) ;
        } else {
            $this->assertEqual( $this->selenium->getValue('enabled_user_validation'), 'on' ) ;
        }
        if( $pref['enabled_user_registration'] == 'on' ){
            $this->assertEqual( $this->selenium->getValue('enabled_user_registration'), 'off' ) ;
        } else {
            $this->assertEqual( $this->selenium->getValue('enabled_user_registration'), 'on' ) ;
        }

        $this->selenium->click("enabled_users");
        $this->selenium->click("enabled_user_validation");
        $this->selenium->click("enabled_user_registration");

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertEqual( $this->selenium->getValue('enabled_users')              ,  $pref['enabled_users'] ) ;
        $this->assertEqual( $this->selenium->getValue('enabled_user_validation')    ,  $pref['enabled_user_validation'] ) ;
        $this->assertEqual( $this->selenium->getValue('enabled_user_registration')  ,  $pref['enabled_user_registration'] ) ;

        $this->assertTrue( $this->selenium->isTextPresent("Users' settings have been updated") , "Can't update user settings. ERROR");
    }

    private function comments()
    {
        $pref = array();
        $pref['enabled_comments']   = Preference::newInstance()->findValueByName('enabled_comments') ;
        if($pref['enabled_comments'] == 1){ $pref['enabled_comments'] = 'on';} else { $pref['enabled_comments'] = 'off'; }

        $pref['moderate_comments']  = Preference::newInstance()->findValueByName('moderate_comments') ;
        if($pref['moderate_comments'] < 0){ $pref['moderate_comments'] = 'off';} else { $pref['moderate_comments'] = 'on'; }
        
        $pref['notify_new_comment'] = Preference::newInstance()->findValueByName('notify_new_comment') ;
        if($pref['notify_new_comment'] == 1){ $pref['notify_new_comment'] = 'on';} else { $pref['notify_new_comment'] = 'off'; }
        
        $pref['num_moderate_comments'] = Preference::newInstance()->findValueByName('moderate_comments');
        $pref['comments_per_page']     = Preference::newInstance()->findValueByName('comments_per_page');

        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=General settings");
        $this->selenium->click("xpath=//div[@id='menu']/ul[8]/li[2]/a");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->click("enabled_comments");
        
        if( !$pref['moderate_comments'] == 'on' ) {
            $this->selenium->click("moderate_comments");
        }
        $this->selenium->click("notify_new_comment");
        $this->selenium->type("num_moderate_comments",10);
        $this->selenium->type("comments_per_page",0);

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("Comments' settings have been updated") , "Can't update comments settings. ERROR");

        if( $pref['enabled_comments'] == 'on' ){
            $this->assertEqual( $this->selenium->getValue('enabled_comments'), 'off' ) ;
        } else {
            $this->assertEqual( $this->selenium->getValue('enabled_comments'), 'on' ) ;
        }
        
        if(! $pref['moderate_comments'] == 'on' ){
            $this->assertEqual( $this->selenium->getValue('moderate_comments'), 'on' ) ;
        }

        if( $pref['notify_new_comment'] == 'on' ){
            $this->assertEqual( $this->selenium->getValue('notify_new_comment'), 'off' ) ;
        } else {
            $this->assertEqual( $this->selenium->getValue('notify_new_comment'), 'on' ) ;
        }
        
        $this->assertTrue($this->selenium->getValue("num_moderate_comments") == 10 , "Not saved ok, num comments are 10." );
        $this->assertTrue($this->selenium->getValue("num_moderate_comments") == 10 , "Not saved ok, num comments are 10." );

        $this->selenium->click("enabled_comments");
        $this->selenium->click("notify_new_comment");
        $this->selenium->type("num_moderate_comments",$pref['num_moderate_comments'] );
        $this->selenium->type("comments_per_page",$pref['comments_per_page'] );

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("Comments' settings have been updated") , "Can't update comments settings. ERROR");
        
        $this->assertEqual( $this->selenium->getValue('enabled_comments')    ,  $pref['enabled_comments'] ) ;
        $this->assertEqual( $this->selenium->getValue('notify_new_comment')  ,  $pref['notify_new_comment'] ) ;
        $this->assertEqual( $this->selenium->getValue('num_moderate_comments')  ,  $pref['num_moderate_comments'] ) ;
        $this->assertEqual( $this->selenium->getValue('comments_per_page')  ,  $pref['comments_per_page'] ) ;
    }

    private function getPreferencesItems()
    {
        $pref = array();
        $pref['enabled_recaptcha_items']        = Preference::newInstance()->findValueByName('enabled_recaptcha_items') ;
        $pref['enabled_item_validation']        = Preference::newInstance()->findValueByName('enabled_item_validation') ;
        $pref['logged_user_item_validation']    = Preference::newInstance()->findValueByName('logged_user_item_validation') ;
        $pref['reg_user_post']                  = Preference::newInstance()->findValueByName('reg_user_post') ;
        $pref['notify_new_item']                = Preference::newInstance()->findValueByName('notify_new_item') ;
        $pref['notify_contact_item']            = Preference::newInstance()->findValueByName('notify_contact_item') ;
        $pref['notify_contact_friends']         = Preference::newInstance()->findValueByName('notify_contact_friends') ;
        $pref['enableField#f_price@items']      = Preference::newInstance()->findValueByName('enableField#f_price@items') ;
        $pref['enableField#images@items']       = Preference::newInstance()->findValueByName('enableField#images@items') ;
        
        $pref['num_moderate_items']             = Preference::newInstance()->findValueByName('moderate_items') ;
        $pref['moderate_items']                 = Preference::newInstance()->findValueByName('moderate_items') ;
        $pref['items_wait_time']                = Preference::newInstance()->findValueByName('items_wait_time') ;

        if($pref['enabled_recaptcha_items'] == 1){  $pref['enabled_recaptcha_items'] = 'on'; }
        else {                                      $pref['enabled_recaptcha_items'] = 'off'; }
        if($pref['reg_user_post'] == 1){            $pref['reg_user_post']          = 'on'; }
        else {                                      $pref['reg_user_post']          = 'off'; }
        if($pref['notify_new_item'] == 1){          $pref['notify_new_item']        = 'on';}
        else {                                      $pref['notify_new_item']        = 'off'; }
        if($pref['notify_contact_item'] == 1){      $pref['notify_contact_item']    = 'on';}
        else {                                      $pref['notify_contact_item']    = 'off'; }
        if($pref['notify_contact_friends'] == 1){   $pref['notify_contact_friends'] = 'on';}
        else {                                      $pref['notify_contact_friends'] = 'off'; }
        if($pref['enableField#f_price@items'] == 1){$pref['enableField#f_price@items'] = 'on';}
        else {                                      $pref['enableField#f_price@items'] = 'off'; }
        if($pref['enableField#images@items'] == 1){ $pref['enableField#images@items'] = 'on';}
        else {                                      $pref['enableField#images@items'] = 'off'; }
        if($pref['logged_user_item_validation'] == 1){  $pref['logged_user_item_validation'] = 'on';}
        else {                                          $pref['logged_user_item_validation'] = 'off'; }

        return $pref;
    }
    
    private function items()
    {
        $pref = $this->getPreferencesItems();

        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=General settings");
        $this->selenium->click("link=» Items");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->click("enabled_recaptcha_items");
        if( $pref['moderate_items'] == -1) {
            $this->selenium->click("moderate_items");
        }
        
        $this->selenium->type("num_moderate_items",'111');
        
        $this->selenium->type("items_wait_time", '120' );
        
        $this->selenium->click("logged_user_item_validation");
        $this->selenium->click("reg_user_post");
        $this->selenium->click("notify_new_item");
        $this->selenium->click("notify_contact_item");
        $this->selenium->click("notify_contact_friends");
        $this->selenium->click("enableField#f_price@items");
        $this->selenium->click("enableField#images@items");

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("Items' settings have been updated") , "Can't update items settings. ERROR");
        
        if( $pref['enabled_item_validation'] == 'on' ) {
            $this->assertEqual( $this->selenium->getValue('num_moderate_items'), '111' ) ;
        }
        $this->assertEqual( $this->selenium->getValue('items_wait_time'), '120' ) ;
        if( $pref['enabled_recaptcha_items'] == 'on' ){     $this->assertEqual( $this->selenium->getValue('enabled_recaptcha_items'), 'off' ) ;
        } else {                                            $this->assertEqual( $this->selenium->getValue('enabled_recaptcha_items'), 'on' ) ;}
        if( $pref['logged_user_item_validation'] == 'on' ){ $this->assertEqual( $this->selenium->getValue('logged_user_item_validation'), 'off' ) ;
        } else {                                            $this->assertEqual( $this->selenium->getValue('logged_user_item_validation'), 'on' ) ;}
        if( $pref['reg_user_post'] == 'on' ){               $this->assertEqual( $this->selenium->getValue('reg_user_post'), 'off' ) ;
        } else {                                            $this->assertEqual( $this->selenium->getValue('reg_user_post'), 'on' ) ;}
        if( $pref['notify_new_item'] == 'on' ){             $this->assertEqual( $this->selenium->getValue('notify_new_item'), 'off' ) ;
        } else {                                            $this->assertEqual( $this->selenium->getValue('notify_new_item'), 'on' ) ;}
        if( $pref['notify_contact_item'] == 'on' ){         $this->assertEqual( $this->selenium->getValue('notify_contact_item'), 'off' ) ;
        } else {                                            $this->assertEqual( $this->selenium->getValue('notify_contact_item'), 'on' ) ;}
        if( $pref['notify_contact_friends'] == 'on' ){      $this->assertEqual( $this->selenium->getValue('notify_contact_friends'), 'off' ) ;
        } else {                                            $this->assertEqual( $this->selenium->getValue('notify_contact_friends'), 'on' ) ;}
        if( $pref['enableField#f_price@items'] == 'on' ){   $this->assertEqual( $this->selenium->getValue('enableField#f_price@items'), 'off' ) ;
        } else {                                            $this->assertEqual( $this->selenium->getValue('enableField#f_price@items'), 'on' ) ;}
        if( $pref['enableField#images@items'] == 'on' ){    $this->assertEqual( $this->selenium->getValue('enableField#images@items'), 'off' ) ;
        } else {                                            $this->assertEqual( $this->selenium->getValue('enableField#images@items'), 'on' ) ;}

        $this->selenium->click("enabled_recaptcha_items");
        $this->selenium->click("logged_user_item_validation");
        $this->selenium->click("reg_user_post");
        $this->selenium->click("notify_new_item");
        $this->selenium->click("notify_contact_item");
        $this->selenium->click("notify_contact_friends");
        $this->selenium->click("enableField#f_price@items");
        $this->selenium->click("enableField#images@items");
        if( $pref['moderate_items'] == -1) {
            $this->selenium->type("num_moderate_items", $pref['num_moderate_items'] );
            $this->selenium->click("moderate_items");
        }
        $this->selenium->type("num_moderate_items", $pref['num_moderate_items'] );
        $this->selenium->type("items_wait_time", $pref['items_wait_time'] );

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("Items' settings have been updated") , "Can't update items settings. ERROR");

        $this->assertEqual( $this->selenium->getValue('enabled_recaptcha_items')        , $pref['enabled_recaptcha_items']) ;
        $this->assertEqual( $this->selenium->getValue('logged_user_item_validation')    , $pref['logged_user_item_validation'] ) ;
        $this->assertEqual( $this->selenium->getValue('reg_user_post')                  , $pref['reg_user_post'] ) ;
        $this->assertEqual( $this->selenium->getValue('notify_new_item')                , $pref['notify_new_item'] ) ;
        $this->assertEqual( $this->selenium->getValue('notify_contact_item')            , $pref['notify_contact_item'] ) ;
        $this->assertEqual( $this->selenium->getValue('notify_contact_friends')         , $pref['notify_contact_friends'] ) ;
        $this->assertEqual( $this->selenium->getValue('enableField#f_price@items')      , $pref['enableField#f_price@items']  ) ;
        $this->assertEqual( $this->selenium->getValue('enableField#images@items')       , $pref['enableField#images@items'] ) ;
        
        $this->assertEqual( $this->selenium->getValue('items_wait_time')                , $pref['items_wait_time'] ) ;
        $this->assertEqual( Preference::newInstance()->findValueByName('moderate_items'), $pref['num_moderate_items'] ) ;
            
        unset($pref);
    }

    private function getPreferencesGeneralSettings()
    {
        $pref = array();
        $pref['pageTitle']      = Preference::newInstance()->findValueByName('pageTitle') ;
        $pref['contactEmail']   = Preference::newInstance()->findValueByName('contactEmail') ;
        $pref['df']             = Preference::newInstance()->findValueByName('dateFormat') ;
        $pref['pageDesc']       = Preference::newInstance()->findValueByName('pageDesc') ;
        $pref['language']       = Preference::newInstance()->findValueByName('language') ;
        $pref['currency']       = Preference::newInstance()->findValueByName('currency') ;
        $pref['weekStart']      = Preference::newInstance()->findValueByName('weekStart') ;
        $pref['num_rss_items']  = Preference::newInstance()->findValueByName('num_rss_items') ;
        $pref['tf']             = Preference::newInstance()->findValueByName('timeFormat') ;

        return $pref;
    }
    private function generalSettings()
    {
        $pref = $this->getPreferencesGeneralSettings();
        
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=General settings");
        $this->selenium->click("link=» General settings");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type("pageTitle"   ,"New title web");
        $this->selenium->type("contactEmail","foo@bar.com");
        $this->selenium->type("pageDesc"    ,"Description web");

        $this->selenium->select("currency_admin", "label=EUR");
        $this->selenium->select("weekStart"     , "label=Saturday");
        $this->selenium->select("num_rss_items" , "label=25");

        $this->selenium->click("m/d/Y");
        $this->selenium->click("H:i");

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertEqual( $this->selenium->getValue('pageTitle')     , "New title web") ;
        $this->assertEqual( $this->selenium->getValue('contactEmail')  , "foo@bar.com" ) ;
        $this->assertEqual( $this->selenium->getValue('dateFormat')    , "m/d/Y" ) ;
        $this->assertEqual( $this->selenium->getValue('pageDesc')      , "Description web" ) ;
//        $this->assertEqual( $this->selenium->getValue('language')      , 'en_US' ) ;
        $this->assertEqual( $this->selenium->getValue('currency')      , 'EUR' ) ;
        $this->assertEqual( $this->selenium->getValue('weekStart')     , '6' ) ;
        $this->assertEqual( $this->selenium->getValue('num_rss_items') , '25'  ) ;
        $this->assertEqual( $this->selenium->getValue('timeFormat')    , "H:i" ) ;

        $this->selenium->click("link=General settings");
        $this->selenium->click("link=» General settings");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type("pageTitle"   , $pref['pageTitle']);
        $this->selenium->type("contactEmail", $pref['contactEmail']);
        $this->selenium->type("pageDesc"    , $pref['pageDesc']);

        $this->selenium->select("currency_admin", "label=" . $pref['currency'] ) ;
        $this->selenium->select("weekStart"     , "value=" . $pref['weekStart'] ) ;
        $this->selenium->select("num_rss_items" , "label=" . $pref['num_rss_items'] ) ;

        $this->selenium->click($pref['df']);
        $this->selenium->click($pref['tf']);

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");
        
        $this->assertEqual( $this->selenium->getValue('pageTitle')     , $pref['pageTitle']) ;
        $this->assertEqual( $this->selenium->getValue('contactEmail')  , $pref['contactEmail'] ) ;
        $this->assertEqual( $this->selenium->getValue('dateFormat')    , $pref['df'] ) ;
        $this->assertEqual( $this->selenium->getValue('pageDesc')      , $pref['pageDesc'] ) ;
        $this->assertEqual( $this->selenium->getValue('language')      , $pref['language'] ) ;
        $this->assertEqual( $this->selenium->getValue('currency')      , $pref['currency'] ) ;
        $this->assertEqual( $this->selenium->getValue('weekStart')     , $pref['weekStart'] ) ;
        $this->assertEqual( $this->selenium->getValue('num_rss_items') , $pref['num_rss_items']  ) ;
        $this->assertEqual( $this->selenium->getValue('timeFormat')    , $pref['tf'] ) ;
    }

    private function locationsGEO()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=General settings");
        $this->selenium->click("link=» Locations");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->click("xpath=//a[@id='b_new_country']");

        $this->selenium->type("country"     , "Andorra" ) ;
        $this->selenium->type("c_country"   , "AN" ) ;
        $this->selenium->type('c_manual'    , '0') ;

        $this->selenium->click("xpath=//div[@id='d_add_country']/div[2]/form/div/input[@type='submit']") ;
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("regexp:has been added as a new country") , "Can't add new country" );

        // edit country
        $this->selenium->click("xpath=//div[@id='l_countries']/div[1]/div[1]/div/a[@class='edit']");
        $this->selenium->type("e_country"     , "Andorra_" ) ;

        $this->selenium->click("xpath=//div[@id='d_edit_country']/div[2]/form/div/input[@type='submit']") ;
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("regexp:has been edited") , "Can't edit country name" );
        
        // delete country
        $this->selenium->click("xpath=//div[@id='l_countries']/div[1]/div[1]/div/a[1]");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue( $this->selenium->isTextPresent("regexp:has been deleted") , "Can't delete Country" ) ;

    }

    private function locationsNEW()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=General settings");
        $this->selenium->click("link=» Locations");
        $this->selenium->waitForPageToLoad("10000");
        // add Country
        $this->selenium->click("xpath=//a[@id='b_new_country']");

        $this->selenium->type("country", "ikea") ;
        $this->selenium->type("c_country", "IK") ;

        $this->selenium->click("xpath=//div[@id='d_add_country']/div[2]/form/div/input[@type='submit']") ;
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("regexp:has been added as a new country") , "Can't add new country" ) ;
        // add Region
        $this->selenium->click("xpath=//div[@id='l_countries']/div[1]/div/a[text()='View more »']") ;
        $this->selenium->click("xpath=//a[@id='b_new_region']") ;

        $this->selenium->type("region", "Republica") ;

        $this->selenium->click("xpath=//div[@id='d_add_region']/div[2]/form/div/input[@type='submit']") ;
        $this->selenium->waitForPageToLoad("10000") ;

        $this->assertTrue( $this->selenium->isTextPresent("regexp:has been added as a new region") , "Can't add new region" ) ;

        // add City
        $this->selenium->click("xpath=//div[@id='l_countries']/div[1]/div/a[text()='View more »']") ;
        $this->selenium->click("xpath=//div[@id='i_regions']/div[1]/div/a[text()='View more »']") ;
        $this->selenium->click("xpath=//a[@id='b_new_city']") ;

        $this->selenium->type("city", "Mi casa") ;
        $this->selenium->click("xpath=//div[@id='d_add_city']/div[2]/form/div/input[@type='submit']") ;
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("regexp:has been added as a new city") , "Can't add new city" ) ;

        // edit country/region/city
        // add City
        $this->selenium->click("xpath=//div[@id='l_countries']/div[1]/div/a[text()='View more »']") ;
        $this->selenium->click("xpath=//div[@id='i_regions']/div[1]/div/a[text()='View more »']") ;
        $this->selenium->click("xpath=//div[@id='i_cities']/div[1]/div/a[2]") ; // edit first city

        $this->selenium->type("e_city", "Mi casa_") ;
        $this->selenium->click("xpath=//div[@id='d_edit_city']/div[2]/form/div/input[@type='submit']") ;
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("regexp:has been edited") , "Can't edit city name" );

        // edit Region
        $this->selenium->click("xpath=//div[@id='l_countries']/div[1]/div/a[text()='View more »']") ;
        $this->selenium->click("xpath=//div[@id='i_regions']/div[1]/div[1]/a[2]") ; // edit first city

        $this->selenium->type("e_region", "Republica_") ;

        $this->selenium->click("xpath=//div[@id='d_edit_region']/div[2]/form/div/input[@type='submit']") ;
        $this->selenium->waitForPageToLoad("10000") ;

        $this->assertTrue( $this->selenium->isTextPresent("regexp:has been edited") , "Can't edit region name" ) ;

        // edit Country
        $this->selenium->click("xpath=//div[@id='l_countries']/div[1]/div[1]/div/a[@class='edit']");
        $this->selenium->type("e_country"     , "ikea_" ) ;

        $this->selenium->click("xpath=//div[@id='d_edit_country']/div[2]/form/div/input[@type='submit']") ;
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("regexp:has been edited") , "Can't edit country name" );

        // DELETE THE LOCATION
        $this->selenium->click("xpath=//div[@id='l_countries']/div[1]/div[1]/div/a[1]");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue( $this->selenium->isTextPresent("regexp:has been deleted") , "Can't delete Country" ) ;
    }

    private function locationsNEWForceError()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=General settings");
        $this->selenium->click("link=» Locations");
        $this->selenium->waitForPageToLoad("10000");
        // add Country
        $this->selenium->click("xpath=//a[@id='b_new_country']");

        $this->selenium->type("country", "ikea") ;
        $this->selenium->type("c_country", "IK") ;

        $this->selenium->click("xpath=//div[@id='d_add_country']/div[2]/form/div/input[@type='submit']") ;
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("regexp:has been added as a new country") , "Can't add new country" ) ;

        // add country again

        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=General settings");
        $this->selenium->click("link=» Locations");
        $this->selenium->waitForPageToLoad("10000");
        // add Country
        $this->selenium->click("xpath=//a[@id='b_new_country']");

        $this->selenium->type("country", "ikea") ;
        $this->selenium->type("c_country", "IK") ;

        $this->selenium->click("xpath=//div[@id='d_add_country']/div[2]/form/div/input[@type='submit']") ;
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("regexp:already was in the database") , "Can add country twice" ) ;

        // add Region
        $this->selenium->click("xpath=//div[@id='l_countries']/div[1]/div/a[text()='View more »']") ;
        $this->selenium->click("xpath=//a[@id='b_new_region']") ;

        $this->selenium->type("region", "Republica") ;

        $this->selenium->click("xpath=//div[@id='d_add_region']/div[2]/form/div/input[@type='submit']") ;
        $this->selenium->waitForPageToLoad("10000") ;

        $this->assertTrue( $this->selenium->isTextPresent("regexp:has been added as a new region") , "Can't add new region" ) ;

        // add Region again
        $this->selenium->click("xpath=//div[@id='l_countries']/div[1]/div/a[text()='View more »']") ;
        $this->selenium->click("xpath=//a[@id='b_new_region']") ;

        $this->selenium->type("region", "Republica") ;

        $this->selenium->click("xpath=//div[@id='d_add_region']/div[2]/form/div/input[@type='submit']") ;
        $this->selenium->waitForPageToLoad("10000") ;

        $this->assertTrue( $this->selenium->isTextPresent("regexp:already was in the database") , "Can add region twice" ) ;

        // add City
        $this->selenium->click("xpath=//div[@id='l_countries']/div[1]/div/a[text()='View more »']") ;
        $this->selenium->click("xpath=//div[@id='i_regions']/div[1]/div/a[text()='View more »']") ;
        $this->selenium->click("xpath=//a[@id='b_new_city']") ;

        $this->selenium->type("city", "Mi casa") ;
        $this->selenium->click("xpath=//div[@id='d_add_city']/div[2]/form/div/input[@type='submit']") ;
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("regexp:has been added as a new city") , "Can't add new city" ) ;

        // add City again
        $this->selenium->click("xpath=//div[@id='l_countries']/div[1]/div/a[text()='View more »']") ;
        $this->selenium->click("xpath=//div[@id='i_regions']/div[1]/div/a[text()='View more »']") ;
        $this->selenium->click("xpath=//a[@id='b_new_city']") ;

        $this->selenium->type("city", "Mi casa") ;
        $this->selenium->click("xpath=//div[@id='d_add_city']/div[2]/form/div/input[@type='submit']") ;
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("regexp:already was in the database") , "Can add city twice" ) ;

        // test errors when edit countries, regions, cities
        
        // add another City
        $this->selenium->click("xpath=//div[@id='l_countries']/div[1]/div/a[text()='View more »']") ;
        $this->selenium->click("xpath=//div[@id='i_regions']/div[1]/div/a[text()='View more »']") ;
        $this->selenium->click("xpath=//a[@id='b_new_city']") ;

        $this->selenium->type("city", "Mi casa_") ;
        $this->selenium->click("xpath=//div[@id='d_add_city']/div[2]/form/div/input[@type='submit']") ;
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("regexp:has been added as a new city") , "Can't add new city" ) ;
        // edit the city and change the name to existing one
        $this->selenium->click("xpath=//div[@id='l_countries']/div[1]/div/a[text()='View more »']") ;
        $this->selenium->click("xpath=//div[@id='i_regions']/div[1]/div/a[text()='View more »']") ;
        $this->selenium->click("xpath=//div[@id='i_cities']/div/div/a[text()='Mi casa_']") ; 

        $this->selenium->type("e_city", "Mi casa") ;
        $this->selenium->click("xpath=//div[@id='d_edit_city']/div[2]/form/div/input[@type='submit']") ;
        $this->selenium->waitForPageToLoad("10000");
        
        $this->assertTrue( $this->selenium->isTextPresent("regexp:already was in the database") , "Can change city name to existing one" ) ;

        // add another Region
        $this->selenium->click("xpath=//div[@id='l_countries']/div[1]/div/a[text()='View more »']") ;
        $this->selenium->click("xpath=//a[@id='b_new_region']") ;

        $this->selenium->type("region", "Republica_") ;

        $this->selenium->click("xpath=//div[@id='d_add_region']/div[2]/form/div/input[@type='submit']") ;
        $this->selenium->waitForPageToLoad("10000") ;

        $this->assertTrue( $this->selenium->isTextPresent("regexp:has been added as a new region") , "Can't add new region" ) ;

        // edit the region and change the name to existing one
        $this->selenium->click("xpath=//div[@id='l_countries']/div[1]/div/a[text()='View more »']") ;
        $this->selenium->click("xpath=//div[@id='i_regions']/div/div/a[text()='Republica_']") ; 

        $this->selenium->type("e_region", "Republica") ;

        $this->selenium->click("xpath=//div[@id='d_edit_region']/div[2]/form/div/input[@type='submit']") ;
        $this->selenium->waitForPageToLoad("10000") ;

        $this->assertTrue( $this->selenium->isTextPresent("regexp:already was in the database") , "Can change region name to existing one" ) ;

        // DELETE THE LOCATION
        $this->selenium->click("xpath=//div[@id='l_countries']/div[1]/div[1]/div/a[1]");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue( $this->selenium->isTextPresent("regexp:has been deleted") , "Can't delete Country" ) ;

    }

    private function currency()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=General settings");
        $this->selenium->click("link=» Currencies");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->click("link=Add");

        $this->selenium->click("button_open");
        $this->selenium->waitForPageToLoad("30000");

        $this->selenium->type("code", "INR");
        $this->selenium->type("name", "Indian Rupee");
        $this->selenium->type("description", "Indian Rupee र");

        $this->selenium->click("//input[@id='button_save' and @value='Create']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("regexp:New currency has been added") , "Can't add a currency" ) ;

        // edit
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=General settings");
        $this->selenium->click("link=» Currencies");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'INR')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'INR')]/td/div/a[text()='Edit']");
        $this->selenium->waitForPageToLoad("10000");
        
        $this->selenium->type("name", "Indian_Rupee");
        $this->selenium->type("description", "Indian_Rupee र");

        $this->selenium->click("//input[@id='button_save' and @value='Edit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("regexp:Currency has been updated") , "Can't edit a currency" ) ;
        $this->assertTrue( $this->selenium->isTextPresent("regexp:Indian_Rupee") , "Can't edit a currency" ) ;
        // delete
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=General settings");
        $this->selenium->click("link=» Currencies");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'INR')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'INR')]/td/div/a[text()='Delete']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("regexp:has been deleted") , "Can't delete a currency" ) ;
        $this->assertTrue( !$this->selenium->isTextPresent("regexp:Indian_Rupee") , "Can't delete a currency" ) ;
    }

    private function addCurrencyTwice()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=General settings");
        $this->selenium->click("link=» Currencies");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->click("link=Add");

        $this->selenium->click("button_open");
        $this->selenium->waitForPageToLoad("30000");

        $this->selenium->type("code", "INR");
        $this->selenium->type("name", "Indian Rupee");
        $this->selenium->type("description", "Indian Rupee र");

        $this->selenium->click("//input[@id='button_save' and @value='Create']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("regexp:New currency has been added") , "Can't add a currency" ) ;

        // add the same currency again
        // $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=General settings");
        $this->selenium->click("link=» Currencies");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->click("link=Add");

        $this->selenium->click("button_open");
        $this->selenium->waitForPageToLoad("30000");

        $this->selenium->type("code", "INR");
        $this->selenium->type("name", "Indian Rupee");
        $this->selenium->type("description", "Indian Rupee र");

        $this->selenium->click("//input[@id='button_save' and @value='Create']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("regexp:Error: currency couldn't be added") , "Can add existent currency. ERROR" ) ;

         // delete
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=General settings");
        $this->selenium->click("link=» Currencies");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->mouseOver("//table/tbody/tr[contains(.,'INR')]");
        $this->selenium->click("//table/tbody/tr[contains(.,'INR')]/td/div/a[text()='Delete']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("regexp:has been deleted") , "Can't delete a currency" ) ;
    }
}
?>
