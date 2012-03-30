<?php
require_once dirname(__FILE__).'/../../../../oc-load.php';

//require_once('FrontendTest.php');

class OCadmin_generalSettings extends OCadminTest {
    
    /*
     * Login into oc-admin.
     * GeneralSettings->Cron system.
     * - switch inputs.
     * Logout.
     */

    function testCrontab()
    {
        $uSettings = new utilSettings();
        
        $this->loginWith();
        $this->assertTrue(!$this->selenium->isTextPresent('Log in'), "Login oc-admin.");
        
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Settings");
        $this->selenium->click("link=General");
        $this->selenium->waitForPageToLoad("10000");
        
        $cron = $uSettings->findValueByName('auto_cron');
        if($cron == 1){ $cron = 'on';} else { $cron = 'off'; }
        
        $this->assertEqual($cron, $this->selenium->getValue("auto_cron"), "Cron tab, check values/ preference values.");

        $this->selenium->click("auto_cron");
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("30000");
        
        $cron = $uSettings->findValueByName('auto_cron');
        if($cron == 1){ $cron = 'on';} else { $cron = 'off'; }
        
        $this->assertEqual($cron, $this->selenium->getValue("auto_cron"), "Cron tab, check values/ preference values.");
        
        unset($uSettings);
    }
    
    /*
     * Login oc-admin
     * Update all inputs and check if change has been saved, update old configuration and check again.
     * Logout
     */
    function testMediatab()
    {
        $uSettings = new utilSettings();
        
        $this->loginWith();
        
        $maxSizeKb      = $uSettings->findValueByName('maxSizeKb');
        $allowedExt     = $uSettings->findValueByName('allowedExt');
        $dimThumbnail   = $uSettings->findValueByName('dimThumbnail');
        $dimPreview     = $uSettings->findValueByName('dimPreview');
        $dimNormal      = $uSettings->findValueByName('dimNormal');
        $keep_original_image   = $uSettings->findValueByName('keep_original_image');
        if($keep_original_image == 1){ $keep_original_image = 'on';} else { $keep_original_image = 'off'; }

        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Settings");
        $this->selenium->click("link=Media");
        $this->selenium->waitForPageToLoad("10000");

        // change values to some test-defined ones
        $this->selenium->type('maxSizeKb'   , '500');
        $this->selenium->type('allowedExt'  , 'ext,deg,osc');
        $this->selenium->type('dimThumbnail', '10x10');
        $this->selenium->type('dimPreview'  , '50x50');
        $this->selenium->type('dimNormal'   , '100x100');
        $this->selenium->click('keep_original_image');

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("Media config has been updated"), "Media tab, update.");

        $this->assertEqual( $this->selenium->getValue("maxSizeKb")      , '500', 'Media tab, check maxSizeKb');
        $this->assertEqual( $this->selenium->getValue('allowedExt')     , 'ext,deg,osc', 'Media tab, check allowedExt ext,deg,osc');
        $this->assertEqual( $this->selenium->getValue('dimThumbnail')   , '10x10', 'Media tab, check dimThumnai 10x10');
        $this->assertEqual( $this->selenium->getValue('dimPreview')     , '50x50' , 'Media tab, check dimPreview 50x50');
        $this->assertEqual( $this->selenium->getValue('dimNormal')      , '100x100', 'Media tab, check dimNormal 100x100');
        $this->assertEqual( $this->selenium->getValue('keep_original_image'), 'off', 'Media tab, check keep_original_image');

        $this->selenium->type('maxSizeKb'   , $maxSizeKb);
        $this->selenium->type('allowedExt'  , $allowedExt);
        $this->selenium->type('dimThumbnail', $dimThumbnail);
        $this->selenium->type('dimPreview'  , $dimPreview);
        $this->selenium->type('dimNormal'   , $dimNormal);
        $this->selenium->click('keep_original_image');

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("Media config has been updated"), "Media tab, update.");

        $this->assertEqual( $this->selenium->getValue("maxSizeKb")      , $maxSizeKb);
        $this->assertEqual( $this->selenium->getValue('allowedExt')     , $allowedExt);
        $this->assertEqual( $this->selenium->getValue('dimThumbnail')   , $dimThumbnail);
        $this->assertEqual( $this->selenium->getValue('dimPreview')     , $dimPreview);
        $this->assertEqual( $this->selenium->getValue('dimNormal')      , $dimNormal);
        $this->assertEqual( $this->selenium->getValue('keep_original_image'), 'on');
    }
    
    /*
     * Login oc-admin
     * General Settings -> Mail server
     * update configuration and check and set old configuration again and check.
     * Logout.
     */
    function testMailServer()
    {
        $uSettings = new utilSettings();
        
        $pref = array();
        $pref['mailserver_type']        = $uSettings->findValueByName('mailserver_type');
        $pref['mailserver_host']        = $uSettings->findValueByName('mailserver_host');
        $pref['mailserver_port']        = $uSettings->findValueByName('mailserver_port');
        $pref['mailserver_username']    = $uSettings->findValueByName('mailserver_username');
        $pref['mailserver_password']    = $uSettings->findValueByName('mailserver_password');
        $pref['mailserver_ssl']         = $uSettings->findValueByName('mailserver_ssl');
        $pref['mailserver_auth']        = $uSettings->findValueByName('mailserver_auth');
        if($pref['mailserver_auth'] == 1){ $pref['mailserver_auth'] = 'on';} else { $pref['mailserver_auth'] = 'off'; }
        
        $this->loginWith();

        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Settings");
        $this->selenium->click("link=Mail server");
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

        $this->assertTrue( $this->selenium->isTextPresent( 'Mail server configuration has changed') , "Mail server configuration.");

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

        $this->assertTrue( $this->selenium->isTextPresent( 'Mail server configuration has changed') , "Mail server configuration.");

        $this->assertEqual( $this->selenium->getValue("mailserver_type")     , $pref['mailserver_type']);
        $this->assertEqual( $this->selenium->getValue('mailserver_host')     , $pref['mailserver_host']);
        $this->assertEqual( $this->selenium->getValue('mailserver_port')     , $pref['mailserver_port']);
        $this->assertEqual( $this->selenium->getValue('mailserver_username') , $pref['mailserver_username']);
        $this->assertEqual( $this->selenium->getValue('mailserver_password') , $pref['mailserver_password']);
        $this->assertEqual( $this->selenium->getValue('mailserver_ssl')      , $pref['mailserver_ssl']);
        $this->assertEqual( $this->selenium->getValue('mailserver_auth')     , $pref['mailserver_auth']);
        
        unset($pref);
        unset($uSettings);
    }
    
    /*
     * Login oc-admin
     * General settings -> Spam and bots
     * Set akismet, recaptcha, check modifications
     * Logout
     */
    function testSpamAndBots()
    {
        $uSettings = new utilSettings();
        
        $pref = array();
        $pref['akismet_key']        = $uSettings->findValueByName('akismet_key');
        $pref['recaptchaPrivKey']   = $uSettings->findValueByName('recaptchaPrivKey');
        $pref['recaptchaPubKey']    = $uSettings->findValueByName('recaptchaPubKey');

        $this->loginWith();
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Settings");
        $this->selenium->click("link=Spam and bots");
        $this->selenium->waitForPageToLoad("10000");
        
        // AKISMET

        $this->selenium->type('akismetKey'          , '9f18f856aa3c');
        $this->selenium->click("//input[@id='submit_akismet']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("Your Akismet key has been updated") ,"Can't update the Akismet Key. ERROR");
        $this->assertEqual( $this->selenium->getValue('akismetKey')         , '9f18f856aa3c', 'Spam&Bots, akismet key');

        $this->selenium->type('akismetKey'          , $pref['akismet_key']);
        $this->selenium->click("//input[@id='submit_akismet']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("Your Akismet key has been cleared") ,"Can't update the Akismet Key. ERROR");
        $this->assertEqual( $this->selenium->getValue('akismetKey')         , $pref['akismet_key'] , 'Spam&Bots, akismet key');
        
        // RECAPTCHA
        
        $this->selenium->type('recaptchaPrivKey'    , '1234567890');
        $this->selenium->type('recaptchaPubKey'     , '1234567890');
        $this->selenium->click("//input[@id='submit_recaptcha']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("Your reCAPTCHA key has been updated") ,"Can't update the reCAPTCHA Key. ERROR");
        $this->assertEqual( $this->selenium->getValue('recaptchaPrivKey')   , '1234567890', 'Spam&Bots, recaptcha private key');
        $this->assertEqual( $this->selenium->getValue('recaptchaPubKey')    , '1234567890', 'Spam&Bots, recaptcha public key');

        $this->selenium->type('recaptchaPrivKey'    , $pref['recaptchaPrivKey']);
        $this->selenium->type('recaptchaPubKey'     , $pref['recaptchaPubKey']);
        $this->selenium->click("//input[@id='submit_recaptcha']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("Your reCAPTCHA key has been cleared") ,"Can't update the reCAPTCHA Key. ERROR");
        $this->assertEqual( $this->selenium->getValue('recaptchaPrivKey')   , $pref['recaptchaPrivKey'] , 'Spam&Bots, recaptcha private key');
        $this->assertEqual( $this->selenium->getValue('recaptchaPubKey')    , $pref['recaptchaPubKey'] , 'Spam&Bots, recaptcha public key');
        
        unset($pref);
        unset($uSettings);
    }
    
    /*
     * Login oc-admin
     * General Settings -> Permalinks
     * Set rewrite, and check
     * Logout
     */
    function testPermalinks()
    {
        $uSettings = new utilSettings();

        $pref = array();
        $pref['rewrite_enabled'] = $uSettings->findValueByName('rewriteEnabled') ;
        if($pref['rewrite_enabled'] == 1){ $pref['rewrite_enabled'] = 'on';} else { $pref['rewrite_enabled'] = 'off'; }

        $this->loginWith();
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Settings");
        $this->selenium->click("link=Permalinks");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertEqual( $this->selenium->getValue('rewrite_enabled'), $pref['rewrite_enabled'] , 'Permalinks, check.' ) ;

        $this->selenium->click("xpath=//input[@id='rewrite_enabled']");
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        if( $pref['rewrite_enabled'] == 'on' ) {
            $this->assertEqual( $this->selenium->getValue('rewrite_enabled'), 'off','Permalinks, check.');
        }else{
            $this->assertEqual( $this->selenium->getValue('rewrite_enabled'), 'on' ,'Permalinks, check.');
        }

        $this->selenium->click("xpath=//input[@id='rewrite_enabled']");
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertEqual( $this->selenium->getValue('rewrite_enabled'), $pref['rewrite_enabled'] , 'Permalinks, check.') ;
        
        unset($pref);
        unset($uSettings);
    }
    
    /*
     * Login oc-admin
     * GeneralSettings -> Comments
     * update settings, and check
     * Logout
     * 
     */
    function testComments()
    {
        $uSettings = new utilSettings();
        $pref = array();
        
        $pref['enabled_comments']   = $uSettings->findValueByName('enabled_comments') ;
        $pref['moderate_comments']  = $uSettings->findValueByName('moderate_comments') ;
        $pref['notify_new_comment'] = $uSettings->findValueByName('notify_new_comment') ;
        $pref['reg_user_post_comments'] = $uSettings->findValueByName('reg_user_post_comments') ;
        $pref['num_moderate_comments'] = $uSettings->findValueByName('moderate_comments');
        $pref['comments_per_page']     = $uSettings->findValueByName('comments_per_page');
        
        if($pref['enabled_comments'] == 1){ $pref['enabled_comments'] = 'on';} else { $pref['enabled_comments'] = 'off'; }
        if($pref['moderate_comments'] < 0){ $pref['moderate_comments'] = 'off';} else { $pref['moderate_comments'] = 'on'; }
        if($pref['notify_new_comment'] == 1){ $pref['notify_new_comment'] = 'on';} else { $pref['notify_new_comment'] = 'off'; }
        if($pref['reg_user_post_comments'] == 1){ $pref['reg_user_post_comments'] = 'on';} else { $pref['reg_user_post_comments'] = 'off'; }

        $this->loginWith();
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Settings");
        $this->selenium->click("xpath=(//a[text()='Comments'])[position()=2]");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("enabled_comments");
        $this->selenium->click("reg_user_post_comments");
        if( !$pref['moderate_comments'] == 'on' ) {
            $this->selenium->click("moderate_comments");
        }
        $this->selenium->click("notify_new_comment");
        $this->selenium->type("num_moderate_comments",10);
        $this->selenium->type("comments_per_page",0);

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("Comments' settings have been updated") , "Comments settings, check.");
        if( $pref['enabled_comments'] == 'on' ){
            $this->assertEqual( $this->selenium->getValue('enabled_comments'), 'off' , "Comments settings, check." ) ;
        } else {
            $this->assertEqual( $this->selenium->getValue('enabled_comments'), 'on' , "Comments settings, check." ) ;
        }

        if( $pref['reg_user_post_comments'] == 'on' ){
            $this->assertEqual( $this->selenium->getValue('reg_user_post_comments'), 'off' , "Comments settings, check." ) ;
        } else {
            $this->assertEqual( $this->selenium->getValue('reg_user_post_comments'), 'on' , "Comments settings, check." ) ;
        }
        
        if(! $pref['moderate_comments'] == 'on' ){
            $this->assertEqual( $this->selenium->getValue('moderate_comments'), 'on' , "Comments settings, check." ) ;
        }

        if( $pref['notify_new_comment'] == 'on' ){
            $this->assertEqual( $this->selenium->getValue('notify_new_comment'), 'off' , "Comments settings, check." ) ;
        } else {
            $this->assertEqual( $this->selenium->getValue('notify_new_comment'), 'on' , "Comments settings, check." ) ;
        }
        
        $this->assertTrue($this->selenium->getValue("num_moderate_comments") == 10 , "Comments settings, check. Not saved ok, num comments are 10." );
        $this->assertTrue($this->selenium->getValue("num_moderate_comments") == 10 , "Comments settings, check. Not saved ok, num comments are 10." );

        $this->selenium->click("enabled_comments");
        $this->selenium->click("reg_user_post_comments");
        $this->selenium->click("notify_new_comment");
        $this->selenium->type("num_moderate_comments",$pref['num_moderate_comments'] );
        $this->selenium->type("comments_per_page",$pref['comments_per_page'] );

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("Comments' settings have been updated") , "Can't update comments settings. ERROR");
        
        $this->assertEqual( $this->selenium->getValue('enabled_comments')       ,  $pref['enabled_comments']         , "Comments settings, check.") ;
        $this->assertEqual( $this->selenium->getValue('reg_user_post_comments') ,  $pref['reg_user_post_comments']   , "Comments settings, check.") ;
        $this->assertEqual( $this->selenium->getValue('notify_new_comment')     ,  $pref['notify_new_comment']       , "Comments settings, check.") ;
        $this->assertEqual( $this->selenium->getValue('num_moderate_comments')  ,  $pref['num_moderate_comments']    , "Comments settings, check.") ;
        $this->assertEqual( $this->selenium->getValue('comments_per_page')      ,  $pref['comments_per_page']        , "Comments settings, check.") ;
        
        unset($pref);
        unset($uSettings);
    }
    
    private function getPreferencesGeneralSettings()
    {
        $uSettings = new utilSettings();
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
        $pref['max_latest_items_at_home']  = Preference::newInstance()->findValueByName('maxLatestItems@home') ;
        $pref['contact_attachment'] = Preference::newInstance()->findValueByName('contact_attachment') ;
        if($pref['contact_attachment'] == 1){ $pref['contact_attachment'] = 'on';} else { $pref['contact_attachment'] = 'off'; }
        unset($uSettings);  
        return $pref;
    }
    
    /*
     * Login oc-admin
     * GeneralSettings->GeneralSettings
     * update settings, and check
     * Logout
     */
    function testGeneralSettings()
    {
        $pref = $this->getPreferencesGeneralSettings();
        
        $this->loginWith();
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Settings");
        $this->selenium->click("link=General");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type("pageTitle"   ,"New title web");
        $this->selenium->type("contactEmail","foo@bar.com");
        $this->selenium->type("pageDesc"    ,"Description web");
        $this->selenium->select("currency_admin", "label=EUR");
        $this->selenium->select("weekStart"     , "label=Saturday");
        $this->selenium->type("num_rss_items" , "60");
        $this->selenium->type("max_latest_items_at_home" , "20");
        $this->selenium->click("m/d/Y");
        $this->selenium->click("H:i");
        $this->assertEqual( $this->selenium->getValue('enabled_attachment'), $pref['contact_attachment'] , 'Contact, check.') ;
        $this->selenium->click("enabled_attachment");
        
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertEqual( $this->selenium->getValue('pageTitle')     , "New title web" , 'GeneralSettings, check.') ;
        $this->assertEqual( $this->selenium->getValue('contactEmail')  , "foo@bar.com"   , 'GeneralSettings, check.' ) ;
        $this->assertEqual( $this->selenium->getValue('dateFormat')    , "m/d/Y"         , 'GeneralSettings, check.') ;
        $this->assertEqual( $this->selenium->getValue('pageDesc')      , "Description web"  , 'GeneralSettings, check.') ;
//        $this->assertEqual( $this->selenium->getValue('language')      , 'en_US' ) ;
        $this->assertEqual( $this->selenium->getValue('currency')      , 'EUR'          , 'GeneralSettings, check.') ;
        $this->assertEqual( $this->selenium->getValue('weekStart')     , '6'            , 'GeneralSettings, check.') ;
        $this->assertEqual( $this->selenium->getValue('num_rss_items') , '60'           , 'GeneralSettings, check.') ;
        $this->assertEqual( $this->selenium->getValue('max_latest_items_at_home')       , '20'  , 'GeneralSettings, check.' ) ;
        $this->assertEqual( $this->selenium->getValue('timeFormat')    , "H:i"          , 'GeneralSettings, check.') ;

        if( $pref['contact_attachment'] == 'on' ) {
            $this->assertEqual( $this->selenium->getValue('enabled_attachment'), 'off', 'Contact, check.' ) ;
        } else {
            $this->assertEqual( $this->selenium->getValue('enabled_attachment'), 'on', 'Contact, check.' ) ;
        }

        $this->selenium->click("link=Settings");
        $this->selenium->click("link=General");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->type("pageTitle"   , $pref['pageTitle']);
        $this->selenium->type("contactEmail", $pref['contactEmail']);
        $this->selenium->type("pageDesc"    , $pref['pageDesc']);
        $this->selenium->select("currency_admin", "label=" . $pref['currency'] ) ;
        $this->selenium->select("weekStart"     , "value=" . $pref['weekStart'] ) ;
        $this->selenium->type("num_rss_items" , $pref['num_rss_items'] ) ;
        $this->selenium->type("max_latest_items_at_home" , $pref['max_latest_items_at_home'] ) ;
        $this->selenium->click($pref['df']);
        $this->selenium->click($pref['tf']);
        $this->selenium->click("enabled_attachment");
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");
        
        $this->assertEqual( $this->selenium->getValue('pageTitle')     , $pref['pageTitle']      , 'GeneralSettings, check.') ;
        $this->assertEqual( $this->selenium->getValue('contactEmail')  , $pref['contactEmail']   , 'GeneralSettings, check.') ;
        $this->assertEqual( $this->selenium->getValue('dateFormat')    , $pref['df']             , 'GeneralSettings, check.') ;
        $this->assertEqual( $this->selenium->getValue('pageDesc')      , $pref['pageDesc']       , 'GeneralSettings, check.') ;
//        $this->assertEqual( $this->selenium->getValue('language')      , $pref['language']       , 'GeneralSettings, check.') ;
        $this->assertEqual( $this->selenium->getValue('currency')      , $pref['currency']       , 'GeneralSettings, check.') ;
        $this->assertEqual( $this->selenium->getValue('weekStart')     , $pref['weekStart']      , 'GeneralSettings, check.') ;
        $this->assertEqual( $this->selenium->getValue('num_rss_items') , $pref['num_rss_items']  , 'GeneralSettings, check.') ;
        $this->assertEqual( $this->selenium->getValue('timeFormat')    , $pref['tf']             , 'GeneralSettings, check.') ;
        $this->assertEqual( $this->selenium->getValue('max_latest_items_at_home') , $pref['max_latest_items_at_home']  , 'GeneralSettings, check.') ;
        $this->assertEqual( $this->selenium->getValue('enabled_attachment'), $pref['contact_attachment'], 'Contact, check.' ) ;
    }

    /*
     * Login oc-admin
     * GeneralSettings -> locations 
     * Add & edit & delete locations 
     * Logout
     */
    function testLocationsGEO()
    {
        $this->loginWith();
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Settings");
        $this->selenium->click("link=Locations");
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

    /*
     * Login oc-admin
     * GeneralSettings -> locations 
     * add country,region,city
     * edit country/region/city
     * delete country
     * Logout
     */
    function testLocationsNEW()
    {
        $this->loginWith();
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Settings");
        $this->selenium->click("link=Locations");
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

    /*
     * Login oc-admin
     * GeneralSettings -> locations 
     * add country/region/city twice
     * edit country/region/city test location already exist
     * Logout
     */
    function testLocationsNEWForceError()
    {
        $this->loginWith();
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Settings");
        $this->selenium->click("link=Locations");
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
        $this->selenium->click("link=Settings");
        $this->selenium->click("link=Locations");
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
        
    /*
     * Login oc-admin
     * add new currency
     * edit & delete the currency
     * Logout
     */
    function testCurrency()
    {
        $this->loginWith();
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Settings");
        $this->selenium->click("link=Currencies");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->click("link=Add");

        $this->selenium->waitForPageToLoad("30000");

        $this->selenium->type("pk_c_code", "INR");
        $this->selenium->type("s_name", "Indian Rupee");
        $this->selenium->type("s_description", "Indian Rupee र");

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("Currency added") , "Add currency" ) ;

        // edit
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Settings");
        $this->selenium->click("link=Currencies");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->click("//table/tbody/tr[contains(.,'INR')]/td/small/a[text()='Edit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type("s_name", "Indian_Rupee");
        $this->selenium->type("s_description", "Indian_Rupee र");

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("Currency updated") , "Edit currency" ) ;
        // delete
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Settings");
        $this->selenium->click("link=Currencies");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->click("//table/tbody/tr[contains(.,'INR')]/td/small/a[text()='Delete']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("One currency has been deleted") , "Delete currency" ) ;
        $this->assertTrue( !$this->selenium->isTextPresent("Indian_Rupee") , "Delete currency" ) ;
    }

    /*
     * Login oc-admin
     * Add new currency twice
     * Delete 
     * Logout
     */
    function testAddCurrencyTwice()
    {
        $this->loginWith();
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Settings");
        $this->selenium->click("link=Currencies");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->click("link=Add");

        $this->selenium->waitForPageToLoad("30000");

        $this->selenium->type("pk_c_code", "INR");
        $this->selenium->type("s_name", "Indian Rupee");
        $this->selenium->type("s_description", "Indian Rupee र");

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("Currency added") , "Add currency" ) ;

        // add the same currency again
        // $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Settings");
        $this->selenium->click("link=Currencies");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->click("link=Add");

        $this->selenium->waitForPageToLoad("30000");

        $this->selenium->type("pk_c_code", "INR");
        $this->selenium->type("s_name", "Indian Rupee");
        $this->selenium->type("s_description", "Indian Rupee र");

        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("Currency couldn't be added") , "Add currency twice. ERROR" ) ;

         // delete
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Settings");
        $this->selenium->click("link=Currencies");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->click("//table/tbody/tr[contains(.,'INR')]/td/small/a[text()='Delete']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue( $this->selenium->isTextPresent("One currency has been deleted") , "Delete currency" ) ;
        $this->assertTrue( !$this->selenium->isTextPresent("Indian_Rupee") , "Delete currency" ) ;
    }
}
?>
