<?php

    require_once('../../autorun.php');
    require_once('../../web_tester.php');
    require_once('../../reporter.php');

    // LOAD OSCLASS
    require_once '../../../../oc-load.php';

class TestOfSettings extends WebTestCase {
    
    function testSettings() {
        // LOAD SOME DATA (Registration form uses some JS magic, so we can not test it with simpletest)
        // Instead, we create an user "by hand"
        $conn = getConnection();
        $conn->osc_dbExec(sprintf("INSERT INTO `%st_admin` (`s_name` ,`s_username` ,`s_password` ,`s_secret` ,`s_email`) VALUES ('Test Admin','testadmin','5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8','mvqdnrpt','testadmin@test.net')", DB_TABLE_PREFIX));

        // TEST CORRECT LOGIN
        $this->get(osc_admin_base_url());
        $this->setField('user', 'testadmin');
        $this->setField('password', 'password');
        $this->click('Log in');
        $this->assertText('Visit website');
        
        /****************
         *** CRON TAB ***
         ****************/
        $pref = array();
        $pref['cron'] = osc_auto_cron();
        $this->get(osc_admin_base_url(true).'?page=settings&action=cron');
        // CHECK IF THE VALUE IS CORRECT WITH THE ONE ON THE DATABASE
        $this->assertField('auto_cron', $pref['cron']);
        $this->setField('auto_cron', true);
        $this->click('Update');
        $this->get(osc_admin_base_url(true).'?page=settings&action=cron');
        // CHECK IF THE VALUE IS CORRECT WITH (ON)
        $this->assertField('auto_cron', 'on');
        $this->setField('auto_cron', false);
        $this->click('Update');
        $this->get(osc_admin_base_url(true).'?page=settings&action=cron');
        // CHECK IF THE VALUE IS CORRECT WITH 0 (OFF)
        $this->assertField('auto_cron', false);
        // UPDATE TO ORIGINAL STATE
        $this->setField('auto_cron', $pref['cron']);
        $this->assertField('auto_cron', $pref['cron']);
        $this->click('Update');
        unset($pref);
        
        /*****************
         *** MEDIA TAB ***
         *****************/
        $pref = array();
        // LOAD USER'S PREFERENCES
        $pref['max_size_kb'] = osc_max_size_kb();
        $pref['allowed_extension'] = osc_allowed_extension();
        $pref['thumbnail_dimensions'] = osc_thumbnail_dimensions();
        $pref['preview_dimensions'] = osc_preview_dimensions();
        $pref['normal_dimensions'] = osc_normal_dimensions();
        $pref['keep_original_image'] = osc_keep_original_image();
        // CHANGE VALUES TO SOME TEST-DEFINED ONES
        $this->get(osc_admin_base_url(true).'?page=settings&action=media');
        $this->setField('maxSizeKb', '500000');
        $this->setField('allowedExt', 'ext,deg,osc');
        $this->setField('dimThumbnail', '10x10');
        $this->setField('dimPreview', '50x50');
        $this->setField('dimNormal', '100x100');
        $this->setField('keep_original_image', true);
        $this->click('Update');
        $this->get(osc_admin_base_url(true).'?page=settings&action=media');
        $this->assertField('maxSizeKb', '500000');
        $this->assertField('allowedExt', 'ext,deg,osc');
        $this->assertField('dimThumbnail', '10x10');
        $this->assertField('dimPreview', '50x50');
        $this->assertField('dimNormal', '100x100');
        $this->assertField('keep_original_image', true);
        $this->setField('keep_original_image', false);
        $this->click('Update');
        $this->get(osc_admin_base_url(true).'?page=settings&action=media');
        $this->assertField('keep_original_image', false);
        $this->setField('maxSizeKb', $pref['max_size_kb']);
        $this->setField('allowedExt', $pref['allowed_extension']);
        $this->setField('dimThumbnail', $pref['thumbnail_dimensions']);
        $this->setField('dimPreview', $pref['preview_dimensions']);
        $this->setField('dimNormal', $pref['normal_dimensions']);
        $this->setField('keep_original_image', $pref['keep_original_image']);
        $this->click('Update');
        $this->get(osc_admin_base_url(true).'?page=settings&action=media');
        $this->assertField('maxSizeKb', $pref['max_size_kb']);
        $this->assertField('allowedExt', $pref['allowed_extension']);
        $this->assertField('dimThumbnail', $pref['thumbnail_dimensions']);
        $this->assertField('dimPreview', $pref['preview_dimensions']);
        $this->assertField('dimNormal', $pref['normal_dimensions']);
        $this->assertField('keep_original_image', $pref['keep_original_image']);
        unset($pref);
        
        /**********************
         *** MAILSERVER TAB ***
         **********************/
        $pref = array();
        $pref['mailserver_type'] = osc_mailserver_type();
        $pref['mailserver_host'] = osc_mailserver_host();
        $pref['mailserver_port'] = osc_mailserver_port();
        $pref['mailserver_username'] = osc_mailserver_username();
        $pref['mailserver_password'] = osc_mailserver_password();
        $pref['mailserver_ssl'] = osc_mailserver_ssl();
        $pref['mailserver_auth'] = osc_mailserver_auth();
        $this->get(osc_admin_base_url(true).'?page=settings&action=mailserver');
        $this->setField('mailserver_type', 'custom');
        $this->setField('mailserver_host', 'mailserver.test.net');
        $this->setField('mailserver_port', '1234');
        $this->setField('mailserver_username', 'test');
        $this->setField('mailserver_password', 'test');
        $this->setField('mailserver_ssl', 'ssltest');
        $this->setField('mailserver_auth', true);
        $this->click('Update');
        $this->get(osc_admin_base_url(true).'?page=settings&action=mailserver');
        $this->assertField('mailserver_type', 'custom');
        $this->assertField('mailserver_host', 'mailserver.test.net');
        $this->assertField('mailserver_port', '1234');
        $this->assertField('mailserver_username', 'test');
        $this->assertField('mailserver_password', 'test');
        $this->assertField('mailserver_ssl', 'ssltest');
        $this->assertField('mailserver_auth', true);
        $this->click('Update');
        $this->setField('mailserver_type', $pref['mailserver_type']);
        $this->setField('mailserver_host', $pref['mailserver_host']);
        $this->setField('mailserver_port', $pref['mailserver_port']);
        $this->setField('mailserver_username', $pref['mailserver_username']);
        $this->setField('mailserver_password', $pref['mailserver_password']);
        $this->setField('mailserver_ssl', $pref['mailserver_ssl']);
        $this->setField('mailserver_auth', $pref['mailserver_auth']);
        $this->click('Update');
        $this->get(osc_admin_base_url(true).'?page=settings&action=mailserver');
        $this->assertField('mailserver_type', $pref['mailserver_type']);
        $this->assertField('mailserver_host', $pref['mailserver_host']);
        $this->assertField('mailserver_port', $pref['mailserver_port']);
        $this->assertField('mailserver_username', $pref['mailserver_username']);
        $this->assertField('mailserver_password', $pref['mailserver_password']);
        $this->assertField('mailserver_ssl', $pref['mailserver_ssl']);
        $this->assertField('mailserver_auth', $pref['mailserver_auth']);
        
        /*************************
         *** SPAM 'N' BOTS TAB ***
         *************************/
        $pref = array();
        $pref['akismet_key'] = osc_akismet_key();
        $pref['recaptchaPrivKey'] = osc_recaptcha_private_key();
        $pref['recaptchaPubKey'] = osc_recaptcha_public_key();
        $this->get(osc_admin_base_url(true).'?page=settings&action=spamNbots');
        $this->setField('akismetKey', '1234567890');
        $this->setField('recaptchaPrivKey', '1234567890');
        $this->setField('recaptchaPubKey', '1234567890');
        $this->click('Update');
        $this->get(osc_admin_base_url(true).'?page=settings&action=spamNbots');
        $this->assertField('akismetKey', '1234567890');
        $this->assertField('recaptchaPrivKey', '1234567890');
        $this->assertField('recaptchaPubKey', '1234567890');
        $this->setField('akismetKey', $pref['akismet_key']);
        $this->setField('recaptchaPrivKey', $pref['recaptchaPrivKey']);
        $this->setField('recaptchaPubKey', $pref['recaptchaPubKey']);
        $this->click('Update');
        $this->get(osc_admin_base_url(true).'?page=settings&action=spamNbots');
        $this->assertField('akismetKey', $pref['akismet_key']);
        $this->assertField('recaptchaPrivKey', $pref['recaptchaPrivKey']);
        $this->assertField('recaptchaPubKey', $pref['recaptchaPubKey']);

        
        // We did our tests, lets get back to normal
        $admin = Admin::newInstance()->findByEmail('testadmin@test.net');
        Admin::newInstance()->delete(array('pk_i_id' =>$admin['pk_i_id']));
    }        
    
}


?>
