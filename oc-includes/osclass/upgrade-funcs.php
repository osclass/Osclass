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

require_once "../../oc-load.php";

    $version = osc_version() ;
    Preference::newInstance()->update(array('s_value' => time()), array( 's_section' => 'osclass', 's_name' => 'last_version_check'));
    if($version < 119) {
        osc_sql110() ;
        osc_changeVersionTo(119) ;
    }
    
    if($version < 120) {
        osc_sql119() ;
        osc_changeVersionTo(120) ;
    }
    
    if($version < 121) {
        osc_sql120() ;
        osc_changeVersionTo(121) ;
    }
    
    
    
    
    function osc_sql110() {
    
        $sql = "INSERT INTO /*TABLE_PREFIX*/t_preference VALUES ('osclass', 'enabled_user_validation', true, 'BOOLEAN');
INSERT INTO /*TABLE_PREFIX*/t_preference VALUES ('osclass', 'keep_original_image', '1', 'BOOLEAN');
INSERT INTO /*TABLE_PREFIX*/t_preference VALUES ('osclass', 'enabled_user_registration', '1', 'BOOLEAN');
INSERT INTO /*TABLE_PREFIX*/t_preference VALUES ('osclass', 'enabled_users', '1', 'BOOLEAN');
INSERT INTO /*TABLE_PREFIX*/t_preference VALUES ('osclass', 'last_version_check', '".time()."', 'INTEGER');

ALTER TABLE  /*TABLE_PREFIX*/t_user ADD  `s_pass_code` VARCHAR(100) NULL ,
ADD  `s_pass_date` DATETIME NULL ,
ADD  `s_pass_question` VARCHAR(100) NULL ,
ADD  `s_pass_answer` VARCHAR(100) NULL,
ADD  `s_pass_ip` VARCHAR(15) NULL;


INSERT INTO /*TABLE_PREFIX*/t_pages (pk_i_id, s_internal_name, b_indelible, dt_pub_date) VALUES (13, 'email_user_forgot_password', 1, NOW() );
INSERT INTO /*TABLE_PREFIX*/t_pages (pk_i_id, s_internal_name, b_indelible, dt_pub_date) VALUES (14, 'email_new_email', 1, NOW() );

INSERT INTO /*TABLE_PREFIX*/t_pages_description (fk_i_pages_id, fk_c_locale_code, s_title, s_text) VALUES (13, 'en_US', '{WEB_TITLE} Recover your password', '<p>Hi {USER_NAME},</p>\r\n<p> </p>\r\n<p>We sent this e-mail because you forgot your password. Follow the link to recover it : {PASSWORD_LINK}</p>\r\n<p>The link will be disabled in 24 hours.</p>\r\n<p> </p>\r\n<p>If you didn''t forget your password, ignore this message. This petition was made from IP : {IP_ADDRESS} on {DATE_TIME}</p>');
INSERT INTO /*TABLE_PREFIX*/t_pages_description (fk_i_pages_id, fk_c_locale_code, s_title, s_text) VALUES (14, 'en_US', '[ {WEB_TITLE} ] You requested to change your email', '<p>\r\n<p>Dear {USER_NAME}</p>\r\n<p>You''re receiving this email because you requested to change your e-mail. You need to confirm this new e-mail address by clicking on the following validation link : {VALIDATION_LINK}</p>\r\n</p>');



ALTER TABLE /*TABLE_PREFIX*/t_user ADD COLUMN `fk_c_country_code` char(2) DEFAULT NULL;
ALTER TABLE /*TABLE_PREFIX*/t_user ADD COLUMN `s_country` varchar(40) DEFAULT NULL;
ALTER TABLE /*TABLE_PREFIX*/t_user ADD COLUMN `s_address` varchar(100) DEFAULT NULL;
ALTER TABLE /*TABLE_PREFIX*/t_user ADD COLUMN `s_zip` varchar(15) DEFAULT NULL;
ALTER TABLE /*TABLE_PREFIX*/t_user ADD COLUMN `fk_i_region_id` int(10) unsigned DEFAULT NULL;
ALTER TABLE /*TABLE_PREFIX*/t_user ADD COLUMN `s_region` varchar(100) DEFAULT NULL;
ALTER TABLE /*TABLE_PREFIX*/t_user ADD COLUMN `fk_i_city_id` int(10) unsigned DEFAULT NULL;
ALTER TABLE /*TABLE_PREFIX*/t_user ADD COLUMN `s_city` varchar(100) DEFAULT NULL;
ALTER TABLE /*TABLE_PREFIX*/t_user ADD COLUMN `fk_i_city_area_id` int(10) unsigned DEFAULT NULL;
ALTER TABLE /*TABLE_PREFIX*/t_user ADD COLUMN `s_city_area` varchar(200) DEFAULT NULL;
ALTER TABLE /*TABLE_PREFIX*/t_user ADD COLUMN `i_permissions` varchar(2) DEFAULT 0;

ALTER TABLE /*TABLE_PREFIX*/t_user ADD FOREIGN KEY (`fk_c_country_code`) REFERENCES `/*TABLE_PREFIX*/t_country` (`pk_c_code`);
ALTER TABLE /*TABLE_PREFIX*/t_user ADD FOREIGN KEY (`fk_i_region_id`) REFERENCES `/*TABLE_PREFIX*/t_region` (`pk_i_id`);
ALTER TABLE /*TABLE_PREFIX*/t_user ADD FOREIGN KEY (`fk_i_city_id`) REFERENCES `/*TABLE_PREFIX*/t_city` (`pk_i_id`);
ALTER TABLE /*TABLE_PREFIX*/t_user ADD FOREIGN KEY (`fk_i_city_area_id`) REFERENCES `/*TABLE_PREFIX*/t_city_area` (`pk_i_id`);
ALTER TABLE /*TABLE_PREFIX*/t_user ADD UNIQUE (`s_email`);

CREATE TABLE /*TABLE_PREFIX*/t_user_preferences (
    fk_i_user_id INT UNSIGNED NULL,
    s_name VARCHAR(40) NOT NULL,
    s_value LONGTEXT NOT NULL,
    e_type ENUM('STRING', 'INTEGER', 'BOOLEAN') NOT NULL,

        UNIQUE KEY (fk_i_user_id, s_name),
        FOREIGN KEY (fk_i_user_id) REFERENCES /*TABLE_PREFIX*/t_user (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';";
        $conn = getConnection();
        try {
            $conn->osc_updateDB(str_replace('/*TABLE_PREFIX*/', DB_TABLE_PREFIX, $sql));
        } catch(Exception $e) {
            echo "Error: ".$e->getMessage()."\n";
        }
    }
    
    
    function osc_sql119() {
            $conn = getConnection();
        try {
            $resources = $conn->osc_dbFetchResults("SELECT * FROM %st_item_resource", DB_TABLE_PREFIX);
            foreach($resources as $r) {
                $conn->osc_dbExec("UPDATE %st_item_resource SET s_name = '%s', s_path = 'oc-content/uploads/', s_extension = 'png', s_content_type = 'image/png' WHERE pk_i_id = %d", DB_TABLE_PREFIX, $r["pk_i_id"], $r["pk_i_id"]);
            }
        } catch(Exception $e) {
            echo "Error: ".$e->getMessage()."\n";
        }
    }
    
    function osc_sql120() {
            $conn = getConnection();
        try {
            $conn->osc_dbExec(sprintf("INSERT INTO /*TABLE_PREFIX*/t_preference VALUES ('osclass', 'logged_user_item_validation', true, 'BOOLEAN');", DB_TABLE_PREFIX));
        } catch(Exception $e) {
            echo "Error: ".$e->getMessage()."\n";
        }
    }


?>
