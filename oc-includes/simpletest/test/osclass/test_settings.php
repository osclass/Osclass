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
        $this->click('Update');
        $this->get(osc_admin_base_url(true).'?page=settings&action=cron');
        $this->assertField('auto_cron', $pref['cron']);
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

        /**********************
         *** PERMALINKS TAB ***
         **********************/
        $pref = array();
        $pref['rewrite_enabled'] = osc_rewrite_enabled();
        $this->get(osc_admin_base_url(true).'?page=settings&action=permalinks');
        // CHECK IF THE VALUE IS CORRECT WITH THE ONE ON THE DATABASE
        $this->assertField('rewrite_enabled', $pref['rewrite_enabled']);
        $this->setField('rewrite_enabled', true);
        $this->click('Update');
        $this->get(osc_admin_base_url(true).'?page=settings&action=permalinks');
        // CHECK IF THE VALUE IS CORRECT WITH (ON)
        $this->assertField('rewrite_enabled', '1');
        $this->setField('rewrite_enabled', false);
        $this->click('Update');
        $this->get(osc_admin_base_url(true).'?page=settings&action=permalinks');
        // CHECK IF THE VALUE IS CORRECT WITH 0 (OFF)
        $this->assertField('rewrite_enabled', false);
        // UPDATE TO ORIGINAL STATE
        $this->setField('rewrite_enabled', $pref['rewrite_enabled']);
        $this->click('Update');
        $this->get(osc_admin_base_url(true).'?page=settings&action=permalinks');
        $this->assertField('rewrite_enabled', $pref['rewrite_enabled']);
        unset($pref);

        /*******************
         *** CONTACT TAB ***
         *******************/
        $pref = array();
        $pref['contact_attachment'] = osc_contact_attachment();
        $this->get(osc_admin_base_url(true).'?page=settings&action=contact');
        // CHECK IF THE VALUE IS CORRECT WITH THE ONE ON THE DATABASE
        $this->assertField('enabled_attachment', $pref['contact_attachment']);
        $this->setField('enabled_attachment', true);
        $this->click('Update');
        $this->get(osc_admin_base_url(true).'?page=settings&action=contact');
        // CHECK IF THE VALUE IS CORRECT WITH (ON)
        $this->assertField('enabled_attachment', true);
        $this->setField('enabled_attachment', false);
        $this->click('Update');
        $this->get(osc_admin_base_url(true).'?page=settings&action=contact');
        // CHECK IF THE VALUE IS CORRECT WITH 0 (OFF)
        $this->assertField('enabled_attachment', false);
        // UPDATE TO ORIGINAL STATE
        $this->setField('enabled_attachment', $pref['contact_attachment']);
        $this->click('Update');
        $this->get(osc_admin_base_url(true).'?page=settings&action=contact');
        $this->assertField('enabled_attachment', $pref['contact_attachment']);
        unset($pref);

        /*****************
         *** USERS TAB ***
         *****************/
        $pref = array();
        $pref['enabled_users'] = osc_users_enabled();
        $pref['enabled_user_validation'] = osc_user_validation_enabled();
        $pref['enabled_user_registration'] = osc_user_registration_enabled();
        $this->get(osc_admin_base_url(true).'?page=settings&action=users');
        // CHECK IF THE VALUE IS CORRECT WITH THE ONE ON THE DATABASE
        $this->assertField('enabled_users', $pref['enabled_users']);
        $this->assertField('enabled_user_validation', $pref['enabled_user_validation']);
        $this->assertField('enabled_user_registration', $pref['enabled_user_registration']);
        $this->setField('enabled_users', true);
        $this->setField('enabled_user_validation', true);
        $this->setField('enabled_user_registration', true);
        $this->click('Update');
        $this->get(osc_admin_base_url(true).'?page=settings&action=users');
        // CHECK IF THE VALUE IS CORRECT WITH (ON)
        $this->assertField('enabled_users', true);
        $this->assertField('enabled_user_validation', true);
        $this->assertField('enabled_user_registration', true);
        $this->setField('enabled_users', false);
        $this->setField('enabled_user_validation', false);
        $this->setField('enabled_user_registration', false);
        $this->click('Update');
        $this->get(osc_admin_base_url(true).'?page=settings&action=users');
        // CHECK IF THE VALUE IS CORRECT WITH 0 (OFF)
        $this->assertField('enabled_users', false);
        $this->assertField('enabled_user_validation', false);
        $this->assertField('enabled_user_registration', false);
        // UPDATE TO ORIGINAL STATE
        $this->setField('enabled_users', $pref['enabled_users']);
        $this->setField('enabled_user_validation', $pref['enabled_user_validation']);
        $this->setField('enabled_user_registration', $pref['enabled_user_registration']);
        $this->click('Update');
        $this->get(osc_admin_base_url(true).'?page=settings&action=users');
        $this->assertField('enabled_users', $pref['enabled_users']);
        $this->assertField('enabled_user_validation', $pref['enabled_user_validation']);
        $this->assertField('enabled_user_registration', $pref['enabled_user_registration']);
        unset($pref);

        /********************
         *** COMMENTS TAB ***
         ********************/
        // Lines with moderate_comments were commented since it now required JS to work
        $pref = array();
        $pref['enabled_comments'] = osc_comments_enabled();
        //$pref['moderate_comments'] = osc_moderate_comments();
        $pref['notify_new_comment'] = osc_notify_new_comment();
        $this->get(osc_admin_base_url(true).'?page=settings&action=comments');
        // CHECK IF THE VALUE IS CORRECT WITH THE ONE ON THE DATABASE
        $this->assertField('enabled_comments', $pref['enabled_comments']);
        //$this->assertField('moderate_comments', $pref['moderate_comments']);
        $this->assertField('notify_new_comment', $pref['notify_new_comment']);
        $this->setField('enabled_comments', true);
        //$this->setField('moderate_comments', true);
        $this->setField('notify_new_comment', true);
        $this->click('Update');
        $this->get(osc_admin_base_url(true).'?page=settings&action=comments');
        // CHECK IF THE VALUE IS CORRECT WITH (ON)
        $this->assertField('enabled_comments', true);
        //$this->assertField('moderate_comments', true);
        $this->assertField('notify_new_comment', true);
        $this->setField('enabled_comments', false);
        //$this->setField('moderate_comments', false);
        $this->setField('notify_new_comment', false);
        $this->click('Update');
        $this->get(osc_admin_base_url(true).'?page=settings&action=comments');
        // CHECK IF THE VALUE IS CORRECT WITH 0 (OFF)
        $this->assertField('enabled_comments', false);
        //$this->assertField('moderate_comments', false);
        $this->assertField('notify_new_comment', false);
        // UPDATE TO ORIGINAL STATE
        $this->setField('enabled_comments', $pref['enabled_comments']);
        //$this->setField('moderate_comments', $pref['moderate_comments']);
        $this->setField('notify_new_comment', $pref['notify_new_comment']);
        $this->click('Update');
        $this->get(osc_admin_base_url(true).'?page=settings&action=comments');
        $this->assertField('enabled_comments', $pref['enabled_comments']);
        //$this->assertField('moderate_comments', $pref['moderate_comments']);
        $this->assertField('notify_new_comment', $pref['notify_new_comment']);
        unset($pref);

        /*****************
         *** ITEMS TAB ***
         *****************/
        $pref = array();
        $pref['enabled_recaptcha_items'] = osc_recaptcha_items_enabled();
        $pref['enabled_item_validation'] = osc_item_validation_enabled();
        $pref['reg_user_post'] = osc_reg_user_post();
        $pref['notify_new_item'] = osc_notify_new_item();
        $pref['notify_contact_item'] = osc_notify_contact_item();
        $pref['notify_contact_friends'] = osc_notify_contact_friends();
        $pref['enableField#f_price@items'] = osc_price_enabled_at_items();
        $pref['enableField#images@items'] = osc_images_enabled_at_items();
        $this->get(osc_admin_base_url(true).'?page=settings&action=items');
        // CHECK IF THE VALUE IS CORRECT WITH THE ONE ON THE DATABASE
        $this->assertField('enabled_recaptcha_items', $pref['enabled_recaptcha_items']);
        $this->assertField('enabled_item_validation', $pref['enabled_item_validation']);
        $this->assertField('reg_user_post', $pref['reg_user_post']);
        $this->assertField('notify_new_item', $pref['notify_new_item']);
        $this->assertField('notify_contact_item', $pref['notify_contact_item']);
        $this->assertField('notify_contact_friends', $pref['notify_contact_friends']);
        $this->assertField('enableField#f_price@items', $pref['enableField#f_price@items']);
        $this->assertField('enableField#images@items', $pref['enableField#images@items']);
        $this->setField('enabled_recaptcha_items', true);
        $this->setField('enabled_item_validation', true);
        $this->setField('reg_user_post', true);
        $this->setField('notify_new_item', true);
        $this->setField('notify_contact_item', true);
        $this->setField('notify_contact_friends', true);
        $this->setField('enableField#f_price@items', true);
        $this->setField('enableField#images@items', true);
        $this->click('Update');
        $this->get(osc_admin_base_url(true).'?page=settings&action=items');
        // CHECK IF THE VALUE IS CORRECT WITH (ON)
        $this->assertField('enabled_recaptcha_items', true);
        $this->assertField('enabled_item_validation', true);
        $this->assertField('reg_user_post', true);
        $this->assertField('notify_new_item', true);
        $this->assertField('notify_contact_item', true);
        $this->assertField('notify_contact_friends', true);
        $this->assertField('enableField#f_price@items', true);
        $this->assertField('enableField#images@items', true);
        $this->setField('enabled_recaptcha_items', false);
        $this->setField('enabled_item_validation', false);
        $this->setField('reg_user_post', false);
        $this->setField('notify_new_item', false);
        $this->setField('notify_contact_item', false);
        $this->setField('notify_contact_friends', false);
        $this->setField('enableField#f_price@items', false);
        $this->setField('enableField#images@items', false);
        $this->click('Update');
        $this->get(osc_admin_base_url(true).'?page=settings&action=items');
        // CHECK IF THE VALUE IS CORRECT WITH 0 (OFF)
        $this->assertField('enabled_recaptcha_items', false);
        $this->assertField('enabled_item_validation', false);
        $this->assertField('reg_user_post', false);
        $this->assertField('notify_new_item', false);
        $this->assertField('notify_contact_item', false);
        $this->assertField('notify_contact_friends', false);
        $this->assertField('enableField#f_price@items', false);
        $this->assertField('enableField#images@items', false);
        // UPDATE TO ORIGINAL STATE
        $this->setField('enabled_recaptcha_items', $pref['enabled_recaptcha_items']);
        $this->setField('enabled_item_validation', $pref['enabled_item_validation']);
        $this->setField('reg_user_post', $pref['reg_user_post']);
        $this->setField('notify_new_item', $pref['notify_new_item']);
        $this->setField('notify_contact_item', $pref['notify_contact_item']);
        $this->setField('notify_contact_friends', $pref['notify_contact_friends']);
        $this->setField('enableField#f_price@items', $pref['enableField#f_price@items']);
        $this->setField('enableField#images@items', $pref['enableField#images@items']);
        $this->click('Update');
        $this->get(osc_admin_base_url(true).'?page=settings&action=items');
        $this->assertField('enabled_recaptcha_items', $pref['enabled_recaptcha_items']);
        $this->assertField('enabled_item_validation', $pref['enabled_item_validation']);
        $this->assertField('reg_user_post', $pref['reg_user_post']);
        $this->assertField('notify_new_item', $pref['notify_new_item']);
        $this->assertField('notify_contact_item', $pref['notify_contact_item']);
        $this->assertField('notify_contact_friends', $pref['notify_contact_friends']);
        $this->assertField('enableField#f_price@items', $pref['enableField#f_price@items']);
        $this->assertField('enableField#images@items', $pref['enableField#images@items']);
        unset($pref);



        
        // We did our tests, lets get back to normal
        $admin = Admin::newInstance()->findByEmail('testadmin@test.net');
        Admin::newInstance()->delete(array('pk_i_id' =>$admin['pk_i_id']));
    }        
    
}


?>
