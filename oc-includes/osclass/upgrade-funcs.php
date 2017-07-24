<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');
/*
 * Copyright 2014 Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

    set_time_limit(0);

    error_log(' ------- START upgrade-funcs ------- ');

    if(!defined('ABS_PATH')) {
        define('ABS_PATH', dirname(dirname(dirname(__FILE__))) . '/');
    }

    require_once ABS_PATH . 'oc-load.php';
    require_once LIB_PATH . 'osclass/helpers/hErrors.php';

    if( !defined('AUTO_UPGRADE') ) {
        if(file_exists(osc_lib_path() . 'osclass/installer/struct.sql')) {
            $sql  = file_get_contents(osc_lib_path() . 'osclass/installer/struct.sql');

            $conn = DBConnectionClass::newInstance();
            $c_db = $conn->getOsclassDb();
            $comm = new DBCommandClass( $c_db );

            $error_queries = $comm->updateDB( str_replace('/*TABLE_PREFIX*/', DB_TABLE_PREFIX, $sql) );
        }

        if( Params::getParam('skipdb') == '' ){
            if(!$error_queries[0]) {
                $skip_db_link = osc_admin_base_url(true) . "?page=upgrade&action=upgrade-funcs&skipdb=true";
                $title    = __('Osclass &raquo; Has some errors');
                $message  = __("We've encountered some problems while updating the database structure. The following queries failed:");
                $message .= "<br/><br/>" . implode("<br>", $error_queries[2]);
                $message .= "<br/><br/>" . sprintf(__("These errors could be false-positive errors. If you're sure that is the case, you can <a href=\"%s\">continue with the upgrade</a>, or <a href=\"http://forums.osclass.org/\">ask in our forums</a>."), $skip_db_link);
                osc_die($title, $message);
            }
        }
    }

    $aMessages = array();
    //osc_set_preference('last_version_check', time());

    $conn = DBConnectionClass::newInstance();
    $c_db = $conn->getOsclassDb();
    $comm = new DBCommandClass( $c_db );

    if(osc_version() < 210) {
        $comm->query(sprintf("INSERT INTO %st_preference VALUES ('osclass', 'save_latest_searches', '0', 'BOOLEAN')", DB_TABLE_PREFIX));
        $comm->query(sprintf("INSERT INTO %st_preference VALUES ('osclass', 'purge_latest_searches', '1000', 'STRING')", DB_TABLE_PREFIX));
        $comm->query(sprintf("INSERT INTO %st_preference VALUES ('osclass', 'selectable_parent_categories', '1', 'BOOLEAN')", DB_TABLE_PREFIX));
        $comm->query(sprintf("INSERT INTO %st_preference VALUES ('osclass', 'ping_search_engines', '1', 'BOOLEAN')", DB_TABLE_PREFIX));
        $comm->query(sprintf("INSERT INTO %st_preference VALUES ('osclass', 'numImages@items', '0', 'BOOLEAN')", DB_TABLE_PREFIX));
        $enableItemValidation = (getBoolPreference('enabled_item_validation') ? 0 : -1);
        $comm->query(sprintf("INSERT INTO %st_preference VALUES ('osclass', 'moderate_items', '$enableItemValidation', 'INTEGER')", DB_TABLE_PREFIX));
        $comm->query(sprintf("INSERT INTO %st_preference VALUES ('osclass', 'items_wait_time', '0', 'INTEGER')", DB_TABLE_PREFIX));
        $comm->query(sprintf("INSERT INTO %st_preference VALUES ('osclass', 'comments_per_page', '10', 'INTEGER')", DB_TABLE_PREFIX));
        $comm->query(sprintf("INSERT INTO %st_preference VALUES ('osclass', 'reg_user_post_comments', '0', 'BOOLEAN')", DB_TABLE_PREFIX));
        $comm->query(sprintf("INSERT INTO %st_preference VALUES ('osclass', 'reg_user_can_contact', '0', 'BOOLEAN')", DB_TABLE_PREFIX));
        $comm->query(sprintf("INSERT INTO %st_preference VALUES ('osclass', 'allow_report_osclass', '1', 'BOOLEAN')", DB_TABLE_PREFIX));

        // populate b_active/b_enabled (t_item_comment)
        $result   = $comm->query(sprintf("SELECT * FROM %st_item_comment", DB_TABLE_PREFIX));
        $comments = $result->result();
        foreach($comments as $comment) {
            ItemComment::newInstance()->update(array("b_active" => ($comment['e_status'] == 'ACTIVE' ? 1 : 0), 'b_enabled' => 1)
                                              ,array('pk_i_id'  => $comment['pk_i_id']));
        }
        unset($comments);

        // populate b_active/b_enabled (t_item)
        $result  = $comm->query(sprintf("SELECT * FROM %st_item", DB_TABLE_PREFIX));
        $items   = $result->result();
        foreach($items as $item) {
            Item::newInstance()->update(array("b_active" => ($item['e_status'] == 'ACTIVE' ? 1 : 0 ) , 'b_enabled' => 1)
                                       ,array('pk_i_id'  => $item['pk_i_id']));
        }
        unset($items);

        // populate i_items/i_comments/b_active/b_enabled (t_user)
        $users = User::newInstance()->listAll();
        foreach($users as $user) {
            $comments  = count(ItemComment::newInstance()->findByAuthorID($user['pk_i_id']) );
            $items    = count(Item::newInstance()->findByUserIDEnabled($user['pk_i_id']));
            User::newInstance()->update(array( 'i_items' => $items, 'i_comments' => $comments )
                                       ,array( 'pk_i_id' => $user['pk_i_id'] ) );
            // CHANGE FROM b_enabled to b_active
            User::newInstance()->update(array( 'b_active' => $user['b_enabled'], 'b_enabled' => 1 )
                                       ,array( 'pk_i_id'  => $user['pk_i_id'] ) );
        }
        unset($users);

        // Drop e_status column in t_item and t_item_comment
        $comm->query(sprintf("ALTER TABLE %st_item DROP e_status", DB_TABLE_PREFIX));
        $comm->query(sprintf("ALTER TABLE %st_item_comment DROP e_status", DB_TABLE_PREFIX));
        // Delete enabled_item_validation in t_preference
        $comm->query(sprintf("DELETE FROM %st_preference WHERE s_name = 'enabled_item_validation'", DB_TABLE_PREFIX));

        // insert two new e-mail notifications
        $comm->query(sprintf("INSERT INTO %st_pages (s_internal_name, b_indelible, dt_pub_date) VALUES ('email_alert_validation', 1, '%s' )", DB_TABLE_PREFIX, date('Y-m-d H:i:s')));
        $comm->query(sprintf("INSERT INTO %st_pages_description (fk_i_pages_id, fk_c_locale_code, s_title, s_text) VALUES (%d, 'en_US', 'Please validate your alert', '<p>Hi {USER_NAME},</p>\n<p>Please validate your alert registration by clicking on the following link: {VALIDATION_LINK}</p>\n<p>Thank you!</p>\n<p>Regards,</p>\n<p>{WEB_TITLE}</p>')", DB_TABLE_PREFIX, $comm->insertedId()));
        $comm->query(sprintf("INSERT INTO %st_pages (s_internal_name, b_indelible, dt_pub_date) VALUES ('email_comment_validated', 1, '%s' )", DB_TABLE_PREFIX, date('Y-m-d H:i:s')));
        $comm->query(sprintf("INSERT INTO %st_pages_description (fk_i_pages_id, fk_c_locale_code, s_title, s_text) VALUES (%d, 'en_US', '{WEB_TITLE} - Your comment has been approved', '<p>Hi {COMMENT_AUTHOR},</p>\n<p>Your comment has been approved on the following item: {ITEM_URL}</p>\n<p>Regards,</p>\n<p>{WEB_TITLE}</p>')", DB_TABLE_PREFIX, $comm->insertedId()));
    }

    if(osc_version() < 220) {
        $comm->query(sprintf("INSERT INTO %st_preference VALUES ('osclass', 'watermark_text', '', 'STRING')", DB_TABLE_PREFIX));
        $comm->query(sprintf("INSERT INTO %st_preference VALUES ('osclass', 'watermark_text_color', '', 'STRING')", DB_TABLE_PREFIX));
        $comm->query(sprintf("INSERT INTO %st_preference VALUES ('osclass', 'watermark_image','', 'STRING')", DB_TABLE_PREFIX));
        $comm->query(sprintf("INSERT INTO %st_preference VALUES ('osclass', 'watermark_place', 'centre', 'STRING')", DB_TABLE_PREFIX));
    }

    if(osc_version() < 230) {
        $comm->query(sprintf("
CREATE TABLE %st_item_description_tmp (
    fk_i_item_id INT UNSIGNED NOT NULL,
    fk_c_locale_code CHAR(5) NOT NULL,
    s_title VARCHAR(100) NOT NULL,
    s_description MEDIUMTEXT NOT NULL,
    s_what LONGTEXT NULL,

        PRIMARY KEY (fk_i_item_id, fk_c_locale_code),
        INDEX (fk_i_item_id),
        FOREIGN KEY (fk_i_item_id) REFERENCES %st_item (pk_i_id),
        FOREIGN KEY (fk_c_locale_code) REFERENCES %st_locale (pk_c_code)
) ENGINE=MyISAM DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';", DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX));

        $result = $comm->query(sprintf("SELECT * FROM %st_item_description", DB_TABLE_PREFIX) );
        $descriptions = $result->result();
        foreach($descriptions as $d) {
            $sql = sprintf("INSERT INTO %st_item_description_tmp (`fk_i_item_id` ,`fk_c_locale_code` ,`s_title` ,`s_description` ,`s_what`) VALUES ('%d',  '%s',  '%s',  '%s',  '%s')", DB_TABLE_PREFIX, $d['fk_i_item_id'], $d['fk_c_locale_code'], $comm->connId->real_escape_string($d['s_title']), $comm->connId->real_escape_string($d['s_description']), $comm->connId->real_escape_string($d['s_what']) );
            $comm->query($sql);
        }
        $comm->query(sprintf("RENAME TABLE `%st_item_description` TO `%st_item_description_old`", DB_TABLE_PREFIX, DB_TABLE_PREFIX));
        $comm->query(sprintf("RENAME TABLE `%st_item_description_tmp` TO `%st_item_description`", DB_TABLE_PREFIX, DB_TABLE_PREFIX));
        $comm->query(sprintf("ALTER TABLE %st_item_description ADD FULLTEXT s_description (s_description, s_title);", DB_TABLE_PREFIX));

        // remove old tables if have the same number of rows
        $nItemDesc      = $comm->query(sprintf('SELECT count(*) as total FROM %st_item_description', DB_TABLE_PREFIX));
        $nItemDesc      = $nItemDesc->row();
        $nItemDescOld   = $comm->query(sprintf('SELECT count(*) as total FROM %st_item_description_old', DB_TABLE_PREFIX));
        $nItemDescOld   = $nItemDescOld->row();

        if( $nItemDesc['total'] == $nItemDescOld['total'] ) {
            $comm->query(sprintf('DROP TABLE %st_item_description_old' ,DB_TABLE_PREFIX) );
        }

        $comm->query(sprintf("INSERT INTO %st_preference VALUES ('osclass', 'installed_plugins', '%s', 'STRING')", DB_TABLE_PREFIX, osc_get_preference('active_plugins')));
        $comm->query(sprintf("INSERT INTO %st_preference VALUES ('osclass', 'mailserver_pop', '', 'STRING')", DB_TABLE_PREFIX));
        $comm->query(sprintf("INSERT INTO %st_preference VALUES ('osclass', 'use_imagick', '0', 'BOOLEAN')", DB_TABLE_PREFIX));
        $timezone = 'Europe/Madrid';
        if(ini_get('date.timezone')!='') {
            $timezone = ini_get('date.timezone');
        };
        if(date_default_timezone_get()!='') {
            $timezone = date_default_timezone_get();
        };
        $comm->query(sprintf("INSERT INTO %st_preference VALUES ('osclass', 'timezone', '%s', 'STRING')", DB_TABLE_PREFIX, $timezone));

        // alert table pages order improvement
        $comm->query(sprintf("ALTER TABLE %st_pages ADD COLUMN i_order INT(3) NOT NULL DEFAULT 0  AFTER dt_mod_date;", DB_TABLE_PREFIX));
        // order pages
        $result = $comm->query(sprintf("SELECT pk_i_id FROM %st_pages WHERE b_indelible = 0", DB_TABLE_PREFIX) );
        $aPages = $result->result();
        foreach($aPages as $key => $page) {
            $comm->query(sprintf("UPDATE %st_pages SET i_order = %d WHERE pk_i_id = %d;", DB_TABLE_PREFIX, $key, $page['pk_i_id']) );
        }

        $comm->query(sprintf("INSERT INTO %st_pages (s_internal_name, b_indelible, dt_pub_date) VALUES ('email_item_validation_non_register_user', 1, '%s' )", DB_TABLE_PREFIX, date('Y-m-d H:i:s')));
        $comm->query(sprintf("INSERT INTO %st_pages_description (fk_i_pages_id, fk_c_locale_code, s_title, s_text) VALUES (%d, 'en_US', '{WEB_TITLE} - Validate your ad', '<p>Hi {USER_NAME},</p>\n<p>You\'re receiving this e-mail because an ad has been published at {WEB_TITLE}. Please validate this item by clicking on the link at the end of this e-mail. If you didn\'t publish this ad, please ignore this e-mail.</p>\n<p>Ad details:</p>\n<p>Contact name: {USER_NAME}<br />Contact e-mail: {USER_EMAIL}</p>\n<p>{ITEM_DESCRIPTION_ALL_LANGUAGES}</p>\n<p>Price: {ITEM_PRICE}<br />Country: {ITEM_COUNTRY}<br />Region: {ITEM_REGION}<br />City: {ITEM_CITY}<br />Url: {ITEM_URL}<br /><br />Validate your ad: {VALIDATION_LINK}</p>\n\n<p>You\'re not registered at {WEB_TITLE}, but you can still edit or delete the item {ITEM_TITLE} for a short period of time.</p>\n<p>You can edit your item by following this link: {EDIT_LINK}</p>\n<p>You can delete your item by following this link: {DELETE_LINK}</p>\n\n<p>If you register as a user to post items, you will have full access to editing options.</p>\n<p>Regards,</p>\n{WEB_TITLE}</div>')", DB_TABLE_PREFIX, $comm->insertedId()));

        $comm->query(sprintf("INSERT INTO %st_pages (s_internal_name, b_indelible, dt_pub_date) VALUES ('email_admin_new_user', 1, '%s' )", DB_TABLE_PREFIX, date('Y-m-d H:i:s')));
        $comm->query(sprintf("INSERT INTO %st_pages_description (fk_i_pages_id, fk_c_locale_code, s_title, s_text) VALUES (%d, 'en_US', '{WEB_TITLE} - New user', '<div><p>Dear {WEB_TITLE} admin,</p>\n<p>You\'re receiving this email because a new user has been created at {WEB_TITLE}.</p>\n<p>User details:</p>\n<p>Name: {USER_NAME}<br />E-mail: {USER_EMAIL}</p>\n<p>Regards,</p>\n<p>{WEB_TITLE}</p></div>')", DB_TABLE_PREFIX, $comm->insertedId()));
        $comm->query(sprintf("INSERT INTO %st_pages (s_internal_name, b_indelible, dt_pub_date) VALUES ('email_contact_user', 1, '%s' )", DB_TABLE_PREFIX, date('Y-m-d H:i:s')));
        $comm->query(sprintf("INSERT INTO %st_pages_description (fk_i_pages_id, fk_c_locale_code, s_title, s_text) VALUES (%d, 'en_US', '{WEB_TITLE} - Someone has a question for you', '<p>Hi {CONTACT_NAME}!</p>\n<p>{USER_NAME} ({USER_EMAIL}, {USER_PHONE}) left you a message:</p>\n<p>{COMMENT}</p>\n<p>Regards,</p>\n<p>{WEB_TITLE}</p>')", DB_TABLE_PREFIX, $comm->insertedId()));
        $comm->query(sprintf("INSERT INTO %st_pages (s_internal_name, b_indelible, dt_pub_date) VALUES ('email_new_comment_user', 1, '%s' )", DB_TABLE_PREFIX, date('Y-m-d H:i:s')));
        $comm->query(sprintf("INSERT INTO %st_pages_description (fk_i_pages_id, fk_c_locale_code, s_title, s_text) VALUES (%d, 'en_US', '{WEB_TITLE} - New comment on the ad with id {ITEM_ID}', '<p>There\'s a new comment on the ad with id {ITEM_ID} <br />URL: {ITEM_URL}</p>\n<p>Title: {COMMENT_TITLE}<br />Comment: {COMMENT_TEXT}<br />Author: {COMMENT_AUTHOR}<br />Author\'s email: {COMMENT_EMAIL}</p>')", DB_TABLE_PREFIX, $comm->insertedId()));
        $comm->query(sprintf("INSERT INTO %st_preference VALUES ('osclass', 'notify_new_user', '1', 'BOOLEAN')", DB_TABLE_PREFIX));
        $comm->query(sprintf("INSERT INTO %st_preference VALUES ('osclass', 'notify_new_comment_user', '0', 'BOOLEAN')", DB_TABLE_PREFIX));

        $comm->query(sprintf("UPDATE %st_locale SET s_currency_format = '{NUMBER} {CURRENCY}'", DB_TABLE_PREFIX) );
        $result = $comm->query(sprintf("SELECT pk_i_id, f_price FROM %st_item", DB_TABLE_PREFIX));
        $items  = $result->result();
        foreach($items as $item) {
            if( $item['f_price'] == null ) {
                $sql = sprintf( "UPDATE %st_item SET i_price = NULL WHERE pk_i_id = %d", DB_TABLE_PREFIX, $item['pk_i_id']);
            } else {
                $sql = sprintf( "UPDATE %st_item SET i_price = %f WHERE pk_i_id = %d", DB_TABLE_PREFIX, (1000000 * $item['f_price']), $item['pk_i_id'] );
            }
            $comm->query( $sql );
        }
    }

    if( osc_version() < 234 ) {
        @unlink(osc_admin_base_path()."upgrade.php");
        @unlink(osc_admin_base_path()."/themes/modern/tools/upgrade-plugins.php");
        @unlink(osc_admin_base_path()."upgrade-plugin.php");
    }

    if( osc_version() < 240 ) {
        // We no longer use s_what column in /*TABLE_PREFIX*/t_item_description
        $comm->query( sprintf('ALTER TABLE %st_item_description DROP COLUMN s_what', DB_TABLE_PREFIX) );

        @unlink(osc_admin_base_path()."/themes/modern/tools/images.php");

        // NEW REWRITE
        // Uncomment the unlink line prior to release
        //@unlink(osc_base_path()."generate_rules.php");
        osc_set_preference('rewrite_item_url', '{CATEGORIES}/{ITEM_TITLE}_{ITEM_ID}');
        osc_set_preference('rewrite_cat_url', '{CATEGORIES}/');
        osc_set_preference('rewrite_page_url', '{PAGE_SLUG}-p{PAGE_ID}');
        osc_set_preference('rewrite_search_url', 'search/');
        osc_set_preference('rewrite_search_country', 'country');
        osc_set_preference('rewrite_search_region', 'region');
        osc_set_preference('rewrite_search_city', 'city');
        osc_set_preference('rewrite_search_city_area', 'cityarea');
        osc_set_preference('rewrite_search_category', 'category');
        osc_set_preference('rewrite_search_user', 'user');
        osc_set_preference('rewrite_search_pattern', 'pattern');
        osc_set_preference('rewrite_contact', 'contact');
        osc_set_preference('rewrite_feed', 'feed');
        osc_set_preference('rewrite_language', 'language');
        osc_set_preference('rewrite_item_mark', 'item/mark');
        osc_set_preference('rewrite_item_send_friend', 'item/send-friend');
        osc_set_preference('rewrite_item_contact', 'item/contact');
        osc_set_preference('rewrite_item_new', 'item/new');
        osc_set_preference('rewrite_item_activate', 'item/activate');
        osc_set_preference('rewrite_item_edit', 'item/edit');
        osc_set_preference('rewrite_item_delete', 'item/delete');
        osc_set_preference('rewrite_item_resource_delete', 'item/resource/delete');
        osc_set_preference('rewrite_user_login', 'user/login');
        osc_set_preference('rewrite_user_dashboard', 'user/dashboard');
        osc_set_preference('rewrite_user_logout', 'user/logout');
        osc_set_preference('rewrite_user_register', 'user/register');
        osc_set_preference('rewrite_user_activate', 'user/activate');
        osc_set_preference('rewrite_user_activate_alert', 'user/activate_alert');
        osc_set_preference('rewrite_user_profile', 'user/profile');
        osc_set_preference('rewrite_user_items', 'user/items');
        osc_set_preference('rewrite_user_alerts', 'user/alerts');
        osc_set_preference('rewrite_user_recover', 'user/recover');
        osc_set_preference('rewrite_user_forgot', 'user/forgot');
        osc_set_preference('rewrite_user_change_password', 'user/change_password');
        osc_set_preference('rewrite_user_change_email', 'user/change_email');
        osc_set_preference('rewrite_user_change_email_confirm', 'user/change_email_confirm');

        osc_set_preference('last_version_check', time());
        osc_set_preference('update_core_json', '');

        $update_dt_expiration = sprintf('update %st_item as a
                    left join %st_category  as b on b.pk_i_id = a.fk_i_category_id
                    set a.dt_expiration = date_add(a.dt_pub_date, INTERVAL b.i_expiration_days DAY)
                    where b.i_expiration_days > 0', DB_TABLE_PREFIX, DB_TABLE_PREFIX );
        $comm->query( $update_dt_expiration );

        // we need populate location table stats
        $rs = $comm->query( sprintf('SELECT pk_c_code FROM %st_country', DB_TABLE_PREFIX) );
        $aCountry = $rs->result();
        foreach($aCountry as $country) {
            // insert into country_stats with i_num_items = 0
            $comm->query( sprintf('INSERT INTO %st_country_stats (fk_c_country_code, i_num_items) VALUES (\'%s\', 0)', DB_TABLE_PREFIX, $country['pk_c_code']) );
            $rs = $comm->query( sprintf('SELECT pk_i_id FROM %st_region WHERE fk_c_country_code = \'%s\'', DB_TABLE_PREFIX, $country['pk_c_code']) );
            $aRegion = $rs->result();
            foreach($aRegion as $region) {
                // insert into region_stats with i_num_items = 0
                $comm->query( sprintf('INSERT INTO %st_region_stats (fk_i_region_id, i_num_items) VALUES (%s, 0)', DB_TABLE_PREFIX, $region['pk_i_id']) );
                $rs = $comm->query( sprintf('SELECT pk_i_id FROM %st_city WHERE fk_i_region_id = %s', DB_TABLE_PREFIX, $region['pk_i_id']) );
                $aCity = $rs->result();
                foreach($aCity as $city) {
                    // insert into city_stats with i_num_items = 0
                    $comm->query( sprintf('INSERT INTO %st_city_stats (fk_i_city_id, i_num_items) VALUES (%s, 0)', DB_TABLE_PREFIX, $city['pk_i_id']) );
                }
            }
        }
        $url_location_stats = osc_admin_base_url(true)."?page=tools&action=locations";
        $aMessages[] = '<p><b>'.__('You need to calculate location stats, please go to admin panel, tools, recalculate location stats or click') .'  <a href="'.$url_location_stats.'">'.__('here').'</a></b></p>';

        // update t_alerts - Search object serialized to json
        $aAlerts = Alerts::newInstance()->findByType('HOURLY');
        foreach($aAlerts as $hourly) {
            convertAlert($hourly);
        }
        unset($aAlerts);

        $aAlerts = Alerts::newInstance()->findByType('DAILY');
        foreach($aAlerts as $daily) {
            convertAlert($daily);
        }
        unset($aAlerts);

        $aAlerts = Alerts::newInstance()->findByType('WEEKLY');
        foreach($aAlerts as $weekly) {
            convertAlert($weekly);
        }
        unset($aAlerts);

        // UPDATE COUNTRY PROCESS (remove fk_c_locale)
        $comm->query("CREATE TABLE ".DB_TABLE_PREFIX."t_country_aux (
    pk_c_code CHAR(2) NOT NULL,
    s_name VARCHAR(80) NOT NULL,

        PRIMARY KEY (pk_c_code),
        INDEX idx_s_name (s_name)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';");
        $rs = $comm->query("SELECT * FROM ".DB_TABLE_PREFIX."t_country GROUP BY pk_c_code");
        $countries = $rs->result();
        foreach($countries as $c) {
            $comm->insert(DB_TABLE_PREFIX."t_country_aux", array('pk_c_code' => $c['pk_c_code'], 's_name' => $c['s_name']));
        }
        $rs = $comm->query("SHOW CREATE TABLE ".DB_TABLE_PREFIX."t_city");
        $rs = $rs->result();
        foreach($rs[0] as $r) {
            if(preg_match_all('|CONSTRAINT `([^`]+)` FOREIGN KEY \(`fk_c_country_code`\) REFERENCES `'.DB_TABLE_PREFIX.'t_country` \(`pk_c_code`\)|', $r, $matches)) {
                foreach($matches[1] as $m) {
                    $comm->query("ALTER TABLE  `".DB_TABLE_PREFIX."t_city` DROP FOREIGN KEY  `".$m."`");
                }
            }
        }
        $rs = $comm->query("SHOW CREATE TABLE ".DB_TABLE_PREFIX."t_region");
        $rs = $rs->result();
        foreach($rs[0] as $r) {
            if(preg_match_all('|CONSTRAINT `([^`]+)` FOREIGN KEY \(`fk_c_country_code`\) REFERENCES `'.DB_TABLE_PREFIX.'t_country` \(`pk_c_code`\)|', $r, $matches)) {
                foreach($matches[1] as $m) {
                    $comm->query("ALTER TABLE  `".DB_TABLE_PREFIX."t_region` DROP FOREIGN KEY  `".$m."`");
                }
            }
        }
        $rs = $comm->query("SHOW CREATE TABLE ".DB_TABLE_PREFIX."t_country_stats");
        $rs = $rs->result();
        foreach($rs[0] as $r) {
            if(preg_match_all('|CONSTRAINT `([^`]+)` FOREIGN KEY \(`fk_c_country_code`\) REFERENCES `'.DB_TABLE_PREFIX.'t_country` \(`pk_c_code`\)|', $r, $matches)) {
                foreach($matches[1] as $m) {
                    $comm->query("ALTER TABLE  `".DB_TABLE_PREFIX."t_country_stats` DROP FOREIGN KEY  `".$m."`");
                }
            }
        }
        $rs = $comm->query("SHOW CREATE TABLE ".DB_TABLE_PREFIX."t_item_location");
        $rs = $rs->result();
        foreach($rs[0] as $r) {
            if(preg_match_all('|CONSTRAINT `([^`]+)` FOREIGN KEY \(`fk_c_country_code`\) REFERENCES `'.DB_TABLE_PREFIX.'t_country` \(`pk_c_code`\)|', $r, $matches)) {
                foreach($matches[1] as $m) {
                    $comm->query("ALTER TABLE  `".DB_TABLE_PREFIX."t_item_location` DROP FOREIGN KEY  `".$m."`");
                }
            }
        }
        $rs = $comm->query("SHOW CREATE TABLE ".DB_TABLE_PREFIX."t_user");
        $rs = $rs->result();
        foreach($rs[0] as $r) {
            if(preg_match_all('|CONSTRAINT `([^`]+)` FOREIGN KEY \(`fk_c_country_code`\) REFERENCES `'.DB_TABLE_PREFIX.'t_country` \(`pk_c_code`\)|', $r, $matches)) {
                foreach($matches[1] as $m) {
                    $comm->query("ALTER TABLE  `".DB_TABLE_PREFIX."t_user` DROP FOREIGN KEY  `".$m."`");
                }
            }
        }
        $comm->query("DROP TABLE ".DB_TABLE_PREFIX."t_country");
        // hack
        $comm->query("SET FOREIGN_KEY_CHECKS = 0");
        $comm->query("RENAME TABLE  `".DB_TABLE_PREFIX."t_country_aux` TO  `".DB_TABLE_PREFIX."t_country`");
        $comm->query("ALTER TABLE ".DB_TABLE_PREFIX."t_city ADD FOREIGN KEY (fk_c_country_code) REFERENCES ".DB_TABLE_PREFIX."t_country (pk_c_code)");
        $comm->query("ALTER TABLE ".DB_TABLE_PREFIX."t_region ADD FOREIGN KEY (fk_c_country_code) REFERENCES ".DB_TABLE_PREFIX."t_country (pk_c_code)");
        $comm->query("ALTER TABLE ".DB_TABLE_PREFIX."t_country_stats ADD FOREIGN KEY (fk_c_country_code) REFERENCES ".DB_TABLE_PREFIX."t_country (pk_c_code)");
        $comm->query("ALTER TABLE ".DB_TABLE_PREFIX."t_item_location ADD FOREIGN KEY (fk_c_country_code) REFERENCES ".DB_TABLE_PREFIX."t_country (pk_c_code)");
        $comm->query("ALTER TABLE ".DB_TABLE_PREFIX."t_user ADD FOREIGN KEY (fk_c_country_code) REFERENCES ".DB_TABLE_PREFIX."t_country (pk_c_code)");
        // hack
        $comm->query("SET FOREIGN_KEY_CHECKS = 1");
    }

    if(osc_version() < 241) {
        $comm->query(sprintf("INSERT INTO %st_preference VALUES ('osclass', 'use_imagick', '0', 'BOOLEAN')", DB_TABLE_PREFIX));
    }

    if(osc_version() < 300) {
        $comm->query(sprintf("ALTER TABLE %st_user DROP s_pass_answer", DB_TABLE_PREFIX));
        $comm->query(sprintf("ALTER TABLE %st_user DROP s_pass_question", DB_TABLE_PREFIX));
        osc_set_preference('marketURL', 'http://market.osclass.org/api/');
        osc_set_preference('marketAllowExternalSources', '0', 'BOOLEAN');
    }

    if(osc_version() < 310) {
        $comm->query(sprintf("ALTER TABLE  %st_pages ADD  `s_meta` TEXT NULL", DB_TABLE_PREFIX));
        $comm->query(sprintf("ALTER TABLE  %st_pages ADD  `b_link` TINYINT(1) NOT NULL DEFAULT 1", DB_TABLE_PREFIX));
        $comm->query(sprintf("UPDATE %st_alerts SET dt_date = '%s' ", DB_TABLE_PREFIX, date("Y-m-d H:i:s")));

        // remove files moved to controller folder
        @unlink(osc_base_path() . 'ajax.php');
        @unlink(osc_base_path() . 'contact.php');
        @unlink(osc_base_path() . 'custom.php');
        @unlink(osc_base_path() . 'item.php');
        @unlink(osc_base_path() . 'language.php');
        @unlink(osc_base_path() . 'login.php');
        @unlink(osc_base_path() . 'main.php');
        @unlink(osc_base_path() . 'page.php');
        @unlink(osc_base_path() . 'register.php');
        @unlink(osc_base_path() . 'search.php');
        @unlink(osc_base_path() . 'user-non-secure.php');
        @unlink(osc_base_path() . 'user.php');
        @unlink(osc_base_path() . 'readme.php');

        @unlink(osc_lib_path() . 'osclass/plugins.php');
        @unlink(osc_lib_path() . 'osclass/feeds.php');

        $comm->query(sprintf('UPDATE %st_user t, (SELECT pk_i_id FROM %st_user) t1 SET t.s_username = t1.pk_i_id WHERE t.pk_i_id = t1.pk_i_id', DB_TABLE_PREFIX, DB_TABLE_PREFIX));
        osc_set_preference('username_blacklist', 'admin,user', 'osclass', 'STRING');
        osc_set_preference('rewrite_user_change_username', 'username/change');
        osc_set_preference('csrf_name', 'CSRF'.mt_rand(0,mt_getrandmax()));

        @mkdir(osc_uploads_path() . 'page-images');

    }

    if(osc_version() < 320) {
        osc_set_preference('mailserver_mail_from', '');
        osc_set_preference('mailserver_name_from', '');
        osc_set_preference('seo_url_search_prefix', '');

        $comm->query(sprintf("ALTER TABLE  %st_category ADD  `b_price_enabled` TINYINT(1) NOT NULL DEFAULT 1", DB_TABLE_PREFIX));

        osc_set_preference('subdomain_type', '');
        osc_set_preference('subdomain_host', '');
        // email_new_admin
        $comm->query(sprintf("INSERT INTO %st_pages (s_internal_name, b_indelible, dt_pub_date) VALUES ('email_new_admin', 1, '%s' )", DB_TABLE_PREFIX, date('Y-m-d H:i:s')));
        $comm->query(sprintf("INSERT INTO %st_pages_description (fk_i_pages_id, fk_c_locale_code, s_title, s_text) VALUES (%d, 'en_US', '{WEB_TITLE} - Success creating admin account!', '<p>Hi {ADMIN_NAME},</p><p>The admin of {WEB_LINK} has created an account for you,</p><ul><li>Username: {USERNAME}</li><li>Password: {PASSWORD}</li></ul><p>You can access the admin panel here {WEB_ADMIN_LINK}.</p><p>Thank you!</p><p>Regards,</p>')", DB_TABLE_PREFIX, $comm->insertedId()));

        osc_set_preference('warn_expiration', '0', 'osclass', 'INTEGER');

        $comm->query(sprintf("INSERT INTO %st_pages (s_internal_name, b_indelible, dt_pub_date) VALUES ('email_warn_expiration', 1, '%s' )", DB_TABLE_PREFIX, date('Y-m-d H:i:s')));
        $comm->query(sprintf("INSERT INTO %st_pages_description (fk_i_pages_id, fk_c_locale_code, s_title, s_text) VALUES (%d, 'en_US', '{WEB_TITLE} - Your ad is about to expire', '<p>Hi {USER_NAME},</p><p>Your listing <a href=\"{ITEM_URL}\">{ITEM_TITLE}</a> is about to expire at {WEB_LINK}.')", DB_TABLE_PREFIX, $comm->insertedId()));

        osc_set_preference('force_aspect_image', '0', 'osclass', 'BOOLEAN');
    }

    if(osc_version() < 321) {
        if(function_exists('osc_calculate_location_slug')) {
            osc_calculate_location_slug(osc_subdomain_type());
        }
    }

    if(osc_version() < 330) {
        @mkdir(osc_content_path().'uploads/temp/');
        @mkdir(osc_content_path().'downloads/oc-temp/', 0777);
        @unlink(osc_lib_path() . 'osclass/classes/Watermark.php');
        osc_set_preference('title_character_length', '100', 'osclass', 'INTEGER');
        osc_set_preference('description_character_length', '5000', 'osclass', 'INTEGER');
    }

	if(osc_version() < 340) {
		$comm->query(sprintf("ALTER TABLE `%st_widget` ADD INDEX `idx_s_description` (`s_description`);", DB_TABLE_PREFIX));
        osc_set_preference('force_jpeg', '0', 'osclass', 'BOOLEAN');

        @unlink(ABS_PATH . '.maintenance');

        // THESE LINES PROBABLY HIT LOW TIMEOUT SCRIPTS, RUN THE LAST OF THE UPGRADE PROCESS
        //osc_calculate_location_slug('country');
        //osc_calculate_location_slug('region');
        //osc_calculate_location_slug('city');
	}

    if(osc_version() < 343) {
        // update t_alerts - Save them in plain json instead of base64
        $mAlerts = Alerts::newInstance();
        $aAlerts = $mAlerts->findByType('HOURLY');
        foreach($aAlerts as $alert) {
            $s_search = base64_decode($alert['s_search']);
            if(stripos(strtolower($s_search), 'union select')!==false || stripos(strtolower($s_search), 't_admin')!==false) {
                $mAlerts->delete(array('pk_i_id' => $alert['pk_i_id']));
            } else {
                $mAlerts->update(array('s_search' => $s_search), array('pk_i_id' => $alert['pk_i_id']));
            }
        }
        unset($aAlerts);

        $aAlerts = $mAlerts->findByType('DAILY');
        foreach($aAlerts as $alert) {
            $s_search = base64_decode($alert['s_search']);
            if(stripos(strtolower($s_search), 'union select')!==false || stripos(strtolower($s_search), 't_admin')!==false) {
                $mAlerts->delete(array('pk_i_id' => $alert['pk_i_id']));
            } else {
                $mAlerts->update(array('s_search' => $s_search), array('pk_i_id' => $alert['pk_i_id']));
            }
        }
        unset($aAlerts);

        $aAlerts = $mAlerts->findByType('WEEKLY');
        foreach($aAlerts as $alert) {
            $s_search = base64_decode($alert['s_search']);
            if(stripos(strtolower($s_search), 'union select')!==false || stripos(strtolower($s_search), 't_admin')!==false) {
                $mAlerts->delete(array('pk_i_id' => $alert['pk_i_id']));
            } else {
                $mAlerts->update(array('s_search' => $s_search), array('pk_i_id' => $alert['pk_i_id']));
            }
        }
        unset($aAlerts);
    }

    if(osc_version() < 350) {
        osc_set_preference('marketURL', 'http://market.osclass.org/api/v2/');
        osc_set_preference('marketAPIConnect', '');
        osc_set_preference('marketCategories', '');
        osc_set_preference('marketDataUpdate', 0);
    }

    if(osc_version() < 352) {
        osc_set_preference('marketURL', 'http://market.osclass.org/api/v2/');
    }

    if(osc_version() < 370) {
        osc_set_preference('marketURL', 'https://market.osclass.org/api/v2/');
        osc_set_preference('recaptcha_version', '1');
        $comm->query(sprintf("ALTER TABLE  %st_category_description MODIFY s_slug VARCHAR(255) NOT NULL", DB_TABLE_PREFIX));
        $comm->query(sprintf("ALTER TABLE  %st_preference MODIFY s_section VARCHAR(128) NOT NULL", DB_TABLE_PREFIX));
        $comm->query(sprintf("ALTER TABLE  %st_preference MODIFY s_name VARCHAR(128) NOT NULL", DB_TABLE_PREFIX));
    }

    if(osc_version() < 372) {
	    osc_delete_preference('recaptcha_version', 'STRING');
    }

    if(osc_version() < 374) {
        osc_set_preference('marketURL', 'https://market.osclass.org/api/v3/');
        osc_changeVersionTo(374);
        $admin = Admin::newInstance()->findByEmail('demo@demo.com');
        if(isset($admin['pk_i_id'])) {
            Admin::newInstance()->deleteByPrimaryKey($admin['pk_i_id']);
        }
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(ABS_PATH),RecursiveIteratorIterator::SELF_FIRST, RecursiveIteratorIterator::CATCH_GET_CHILD);
        $objects = iterator_to_array($iterator, true);
        foreach($objects as $file => $object) {
            try{
                $handle = @fopen($file, 'r');
                if($handle!==false) {
                    $exist = false;
                    $text = array("htmlspecialchars(file_get_contents(\$_POST['path']))","?option&path=\$path","msdsaa","shell_exec('cat /proc/cpuinfo');","PHPTerm","lzw_decompress");
                    while (($buffer = fgets($handle)) !== false) {
                        foreach($text as $_t) {
                            if (strpos($buffer, $_t) !== false) {
                                $exist = true;
                                break;
                            }      
                        }
                    }
                    fclose($handle);
                    if($exist) {
                        if(strpos($file, __FILE__)===false) {
                            error_log("remove " . $file);
                            @unlink($file);
                        }
                    }
                }
            } catch(Exception $e) {
                error_log($e);
            }
        }
    }

    osc_changeVersionTo(374);

    if(!defined('IS_AJAX') || !IS_AJAX) {
        if(empty($aMessages)) {
            osc_add_flash_ok_message(_m('Osclass has been updated successfully. <a href="http://forums.osclass.org/">Need more help?</a>'), 'admin');
            echo '<script type="text/javascript"> window.location = "'.osc_admin_base_url(true).'?page=tools&action=version"; </script>';
        } else {
            echo '<div class="well ui-rounded-corners separate-top-medium">';
            echo '<p>'.__('Osclass &raquo; Updated correctly').'</p>';
            echo '<p>'.__('Osclass has been updated successfully. <a href="http://forums.osclass.org/">Need more help?</a>').'</p>';
            foreach($aMessages as $msg) {
                echo "<p>".$msg."</p>";
            }
            echo "</div>";
        }
    }

    /**
     * Convert alerts < 2.4, updating s_search with json encoded to based64.
     *
     * @param string $alert base64+serialized
     */
    function convertAlert($alert)
    {
        // decode search model
        $data = base64_decode($alert['s_search']);
        if (is_serialized($data)) { // don't attempt to unserialize data that wasn't serialized going in
            $data = unserialize($data);
            // if search model, convert alert
            if(get_class($data) == 'Search') {
                // get json
                $json = $data->toJson(true);
                // insert new alert with json
                $aCondition = array(
                    's_email'       => $alert['s_email'],
                    'b_active'      => $alert['b_active'],
                    'e_type'        => $alert['e_type']
                );
                if($alert['fk_i_user_id']!='') {
                    $aCondition['fk_i_user_id'] = (int)$alert['fk_i_user_id'];
                }
                if($alert['s_secret']!='') {
                    $aCondition['s_secret']     = $alert['s_secret'];
                }
                Alerts::newInstance()->update(array('s_search' => base64_encode($json)), $aCondition);
            }
        }
    }