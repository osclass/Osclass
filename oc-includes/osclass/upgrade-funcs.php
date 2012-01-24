<?php
    /**
     * OSClass – software for creating and publishing online classified advertising platforms
     *
     * Copyright (C) 2010 OSCLASS
     *
     * This program is free software: you can redistribute it and/or modify it under the terms
     * of the GNU Affero General Public License as published by the Free Software Foundation,
     * either version 3 of the License, or (at your option) any later version.
     *
     * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
     * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
     * See the GNU Affero General Public License for more details.
     *
     * You should have received a copy of the GNU Affero General Public
     * License along with this program. If not, see <http://www.gnu.org/licenses/>.
     */

    if(!defined('ABS_PATH')) {
        define('ABS_PATH', dirname(dirname(dirname(__FILE__))) . '/');
    }

    require_once ABS_PATH . 'oc-load.php';
    require_once LIB_PATH . 'osclass/helpers/hErrors.php' ;
    
    if( !defined('AUTO_UPGRADE') ) {
        if(file_exists(osc_lib_path() . 'osclass/installer/struct.sql')) {
            $sql  = file_get_contents(osc_lib_path() . 'osclass/installer/struct.sql');
            
            $conn = DBConnectionClass::newInstance();
            $c_db = $conn->getOsclassDb() ;
            $comm = new DBCommandClass( $c_db ) ;
            $error_queries = $comm->updateDB( str_replace('/*TABLE_PREFIX*/', DB_TABLE_PREFIX, $sql) ) ;
            
        }
        
        if( Params::getParam('skipdb') == '' ){
            if(!$error_queries[0]) {
                $skip_db_link = osc_base_url() . "oc-includes/osclass/upgrade-funcs.php?skipdb=true";
                $title    = __('OSClass &raquo; Has some errors') ;
                $message  = __('We encountered some problems updating the database structure. The following queries failed:');
                $message .= "<br/><br/>" . implode("<br>", $error_queries[2]);
                $message .= "<br/><br/>" . sprintf(__('These errors could be false-positive errors. If you\'re sure that is the case, you could <a href="%s">continue with the upgrade</a>, or <a href="http://forums.osclass.org/">ask in our forums</a>.'), $skip_db_link);
                osc_die($title, $message) ;
            }
        }
    }

    Preference::newInstance()->update(array('s_value' => time()), array( 's_section' => 'osclass', 's_name' => 'last_version_check'));

    $conn = DBConnectionClass::newInstance();
    $c_db = $conn->getOsclassDb() ;
    $comm = new DBCommandClass( $c_db ) ;

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
                                       ,array( 'pk_i_id' => $user['pk_i_id'] ) ) ;
            // CHANGE FROM b_enabled to b_active
            User::newInstance()->update(array( 'b_active' => $user['b_enabled'], 'b_enabled' => 1 )
                                       ,array( 'pk_i_id'  => $user['pk_i_id'] ) ) ;
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
        
        osc_changeVersionTo(210) ;
    }

    if(osc_version() < 220) {
        $comm->query(sprintf("INSERT INTO %st_preference VALUES ('osclass', 'watermark_text', '', 'STRING')", DB_TABLE_PREFIX));
        $comm->query(sprintf("INSERT INTO %st_preference VALUES ('osclass', 'watermark_text_color', '', 'STRING')", DB_TABLE_PREFIX));
        $comm->query(sprintf("INSERT INTO %st_preference VALUES ('osclass', 'watermark_image','', 'STRING')", DB_TABLE_PREFIX));
        $comm->query(sprintf("INSERT INTO %st_preference VALUES ('osclass', 'watermark_place', 'centre', 'STRING')", DB_TABLE_PREFIX));
        osc_changeVersionTo(220) ;
    }

    if(osc_version() < 230) {
        $comm->query(sprintf("CREATE TABLE %st_item_description_tmp (
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
        $comm->query(sprintf("ALTER TABLE %st_pages ADD COLUMN i_order INT(3) NOT NULL DEFAULT 0  AFTER dt_mod_date ;", DB_TABLE_PREFIX));
        // order pages
        $result = $comm->query(sprintf("SELECT pk_i_id FROM %st_pages WHERE b_indelible = 0", DB_TABLE_PREFIX) );
        $aPages = $result->result();
        foreach($aPages as $key => $page) {
            $comm->query(sprintf("UPDATE %st_pages SET i_order = %d WHERE pk_i_id = %d ;", DB_TABLE_PREFIX, $key, $page['pk_i_id']) );
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
                $sql = sprintf( "UPDATE %st_item SET i_price = NULL WHERE pk_i_id = %d", DB_TABLE_PREFIX, $item['pk_i_id']) ;
            } else {
                $sql = sprintf( "UPDATE %st_item SET i_price = %f WHERE pk_i_id = %d", DB_TABLE_PREFIX, (1000000 * $item['f_price']), $item['pk_i_id'] )  ;
            }
            $comm->query( $sql );
        }

        osc_changeVersionTo(230) ;
    }

    if(osc_version() < 234) {
        @unlink(osc_admin_base_path()."upgrade.php");
        @unlink(osc_admin_base_path()."/themes/modern/tools/upgrade-plugins.php");
        @unlink(osc_admin_base_path()."upgrade-plugin.php");
        osc_changeVersionTo(234) ;
    }

    osc_changeVersionTo(235) ;

    if(Params::getParam('action') == '') {
        $title   = 'OSClass &raquo; Updated correctly' ;
        $message = 'OSClass has been updated successfully. <a href="http://forums.osclass.org/">Need more help?</a>';
        osc_die($title, $message) ;
    }

?>