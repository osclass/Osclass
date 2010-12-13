SET NAMES 'UTF8';

CREATE TABLE /*TABLE_PREFIX*/t_locale (
    pk_c_code CHAR(5) NOT NULL,
    s_name VARCHAR(100) NOT NULL,
    s_short_name VARCHAR(40) NOT NULL,
    s_description VARCHAR(100) NOT NULL,
    s_version VARCHAR(20) NOT NULL,
    s_author_name VARCHAR(100) NOT NULL,
    s_author_url VARCHAR(100) NOT NULL,
    s_currency_format VARCHAR(10) NOT NULL,
    s_date_format VARCHAR(20) NOT NULL,
    s_stop_words TEXT NULL,
    b_enabled BOOLEAN NOT NULL DEFAULT TRUE, /* Enabled languages for the website */
    b_enabled_bo BOOLEAN NOT NULL DEFAULT TRUE, /* Enabled languages for the oc-admin */

        PRIMARY KEY (pk_c_code),
        UNIQUE KEY (s_short_name)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_country (
    pk_c_code CHAR(2) NOT NULL,
    fk_c_locale_code CHAR(5) NOT NULL,
    s_name VARCHAR(80) NOT NULL,

        PRIMARY KEY (pk_c_code, fk_c_locale_code),
        FOREIGN KEY (fk_c_locale_code) REFERENCES /*TABLE_PREFIX*/t_locale (pk_c_code) 
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_currency (
    pk_c_code CHAR(3) NOT NULL,
    s_name VARCHAR(40) NOT NULL,
    s_description VARCHAR(80) NULL,
    b_enabled BOOLEAN NOT NULL DEFAULT TRUE,

        PRIMARY KEY (pk_c_code),
        UNIQUE KEY (s_name)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_region (
    pk_i_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    fk_c_country_code CHAR(2) NOT NULL,
    s_name VARCHAR(60) NOT NULL,
    b_active BOOLEAN NOT NULL DEFAULT TRUE,

        PRIMARY KEY (pk_i_id),
        INDEX (fk_c_country_code),
        FOREIGN KEY (fk_c_country_code) REFERENCES /*TABLE_PREFIX*/t_country (pk_c_code) 
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';


CREATE TABLE /*TABLE_PREFIX*/t_city (
    pk_i_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    fk_i_region_id INT UNSIGNED NOT NULL,
    s_name VARCHAR(60) NOT NULL,
    fk_c_country_code CHAR(2) NULL,
    b_active BOOLEAN NOT NULL DEFAULT TRUE,

        PRIMARY KEY (pk_i_id),
        INDEX (fk_i_region_id),
        FOREIGN KEY (fk_i_region_id) REFERENCES /*TABLE_PREFIX*/t_region (pk_i_id),
        FOREIGN KEY (fk_c_country_code) REFERENCES /*TABLE_PREFIX*/t_country (pk_c_code)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_city_area (
    pk_i_id INT UNSIGNED NOT NULL,
    fk_i_city_id INT UNSIGNED NOT NULL,
    s_name VARCHAR(255) NOT NULL,

        PRIMARY KEY (pk_i_id),
        INDEX (fk_i_city_id),
        FOREIGN KEY (fk_i_city_id) REFERENCES /*TABLE_PREFIX*/t_city (pk_i_id) 
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_widget (
    pk_i_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    s_description VARCHAR(40) NOT NULL,
    s_location VARCHAR(40) NOT NULL,
    e_kind ENUM('TEXT', 'HTML') NOT NULL,
    s_content MEDIUMTEXT NOT NULL,

        PRIMARY KEY (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_admin (
    pk_i_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    s_name VARCHAR(100) NOT NULL,
    s_username VARCHAR(40) NOT NULL,
    s_password VARCHAR(40) NOT NULL,
    s_email VARCHAR(100) NULL,
    s_secret VARCHAR(40) NULL,

        PRIMARY KEY (pk_i_id),
        UNIQUE KEY (s_username)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_user (
    pk_i_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    dt_reg_date DATETIME NOT NULL,
    dt_mod_date DATETIME NULL,
    s_name VARCHAR(100) NOT NULL,
    s_username VARCHAR(40) NOT NULL,
    s_password VARCHAR(40) NOT NULL,
    s_secret VARCHAR(40) NULL,
    s_email VARCHAR(100) NULL,
    s_website VARCHAR(100) NULL,
    s_info TEXT NULL,
    s_phone_land VARCHAR(45),
    s_phone_mobile VARCHAR(45),
    b_enabled BOOLEAN NOT NULL DEFAULT FALSE,
    s_pass_code VARCHAR(100) NULL ,
    s_pass_date DATETIME NULL ,
    s_pass_question VARCHAR(100) NULL ,
    s_pass_answer VARCHAR(100) NULL,
    s_pass_ip VARCHAR(15) NULL,

        PRIMARY KEY (pk_i_id),
        UNIQUE KEY (s_username)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_category (
    pk_i_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    fk_i_parent_id INT UNSIGNED NULL,
    i_expiration_days INT(3) UNSIGNED NOT NULL DEFAULT 0,
    i_position INT(2) UNSIGNED NOT NULL DEFAULT 0,
    b_enabled BOOLEAN NOT NULL DEFAULT TRUE,
    s_icon VARCHAR(250) NULL,

        PRIMARY KEY (pk_i_id),
        INDEX (fk_i_parent_id),
        INDEX (i_position),
        FOREIGN KEY (fk_i_parent_id) REFERENCES /*TABLE_PREFIX*/t_category (pk_i_id) 
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_category_description (
    fk_i_category_id INT UNSIGNED NOT NULL,
    fk_c_locale_code CHAR(5) NOT NULL,
    s_name VARCHAR(100) NOT NULL,
    s_description VARCHAR(200) NULL,
    s_slug VARCHAR(100) NOT NULL,

        PRIMARY KEY (fk_i_category_id, fk_c_locale_code),
        FOREIGN KEY (fk_i_category_id) REFERENCES /*TABLE_PREFIX*/t_category (pk_i_id),
        FOREIGN KEY (fk_c_locale_code) REFERENCES /*TABLE_PREFIX*/t_locale (pk_c_code) 
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_category_stats (
    fk_i_category_id INT UNSIGNED NOT NULL,
    i_num_items INT UNSIGNED NOT NULL DEFAULT 0,

        PRIMARY KEY (fk_i_category_id),
	FOREIGN KEY (fk_i_category_id) REFERENCES /*TABLE_PREFIX*/t_category (pk_i_id)
);

CREATE TABLE /*TABLE_PREFIX*/t_item (
    pk_i_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    fk_i_user_id INT UNSIGNED NULL,
    fk_i_category_id INT UNSIGNED NOT NULL,
    dt_pub_date DATETIME NOT NULL,
    dt_mod_date DATETIME NULL,
    f_price FLOAT(9, 3) NULL,
    fk_c_currency_code CHAR(3) NOT NULL,
    s_contact_name VARCHAR(100) NULL,
    s_contact_email VARCHAR(140) NULL,
    b_premium BOOLEAN NULL,
    e_status ENUM('ACTIVE', 'INACTIVE', 'SPAM') NOT NULL,
    s_secret VARCHAR(40) NULL,
    b_show_email BOOLEAN NULL,

        PRIMARY KEY (pk_i_id),
        INDEX (fk_i_user_id),
        FOREIGN KEY (fk_i_user_id) REFERENCES /*TABLE_PREFIX*/t_user (pk_i_id),
        INDEX (fk_i_category_id),
        FOREIGN KEY (fk_i_category_id) REFERENCES /*TABLE_PREFIX*/t_category (pk_i_id),
        INDEX (fk_c_currency_code),
        FOREIGN KEY (fk_c_currency_code) REFERENCES /*TABLE_PREFIX*/t_currency (pk_c_code)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_item_description (
    fk_i_item_id INT UNSIGNED NOT NULL,
    fk_c_locale_code CHAR(5) NOT NULL,
    s_title VARCHAR(100) NOT NULL,
    s_description MEDIUMTEXT NOT NULL,
    s_what LONGTEXT NULL,

        PRIMARY KEY (fk_i_item_id, fk_c_locale_code),
        INDEX (fk_i_item_id),
        FOREIGN KEY (fk_i_item_id) REFERENCES /*TABLE_PREFIX*/t_item (pk_i_id),
        FOREIGN KEY (fk_c_locale_code) REFERENCES /*TABLE_PREFIX*/t_locale (pk_c_code)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';


CREATE TABLE /*TABLE_PREFIX*/t_item_location (
    fk_i_item_id INT UNSIGNED NOT NULL,
    fk_c_country_code CHAR(2) NULL,
    s_country VARCHAR(40) NULL, -- readonly
    s_address VARCHAR(100) NULL,
    s_zip VARCHAR(15) NULL,
    fk_i_region_id INT UNSIGNED NULL,
    s_region VARCHAR(100),
    fk_i_city_id INT UNSIGNED NULL,
    s_city VARCHAR(100) NULL,
    fk_i_city_area_id INT UNSIGNED NULL,
    s_city_area VARCHAR(200) NULL,
    d_coord_lat DECIMAL(10, 6),
    d_coord_long DECIMAL(10, 6),

        PRIMARY KEY (fk_i_item_id),
        FOREIGN KEY (fk_i_item_id) REFERENCES /*TABLE_PREFIX*/t_item (pk_i_id),
        FOREIGN KEY (fk_c_country_code) REFERENCES /*TABLE_PREFIX*/t_country (pk_c_code),
        FOREIGN KEY (fk_i_region_id) REFERENCES /*TABLE_PREFIX*/t_region (pk_i_id),
        FOREIGN KEY (fk_i_city_id) REFERENCES /*TABLE_PREFIX*/t_city (pk_i_id),
        FOREIGN KEY (fk_i_city_area_id) REFERENCES /*TABLE_PREFIX*/t_city_area (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_item_stats (
    fk_i_item_id INT UNSIGNED NOT NULL,
    i_num_views INT UNSIGNED NOT NULL DEFAULT 0,
    i_num_spam INT UNSIGNED NOT NULL DEFAULT 0,
    i_num_repeated INT UNSIGNED NOT NULL DEFAULT 0,
    i_num_bad_classified INT UNSIGNED NOT NULL DEFAULT 0,
    i_num_offensive INT UNSIGNED NOT NULL DEFAULT 0,
    i_num_expired INT UNSIGNED NOT NULL DEFAULT 0,
    dt_date DATE NOT NULL,

        PRIMARY KEY (fk_i_item_id, dt_date),
        INDEX (dt_date, fk_i_item_id),
        FOREIGN KEY (fk_i_item_id) REFERENCES /*TABLE_PREFIX*/t_item (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_item_resource (
    pk_i_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    fk_i_item_id INT UNSIGNED NOT NULL,
    s_name VARCHAR(60) NOT NULL,
    s_content_type VARCHAR(40) NOT NULL,
    s_path VARCHAR(250) NULL,

        PRIMARY KEY (pk_i_id),
        INDEX (fk_i_item_id),
        FOREIGN KEY (fk_i_item_id) REFERENCES /*TABLE_PREFIX*/t_item (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_item_comment (
    pk_i_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    fk_i_item_id INT UNSIGNED NOT NULL,
    dt_pub_date DATETIME NOT NULL,
    s_title VARCHAR(200) NOT NULL,
    s_author_name VARCHAR(100) NOT NULL,
    s_author_email VARCHAR(100) NOT NULL,
    s_body TEXT NOT NULL,
    e_status ENUM('ACTIVE', 'INACTIVE', 'SPAM') NOT NULL,
    fk_i_user_id INT UNSIGNED NULL,

        PRIMARY KEY (pk_i_id),
        INDEX (fk_i_item_id),
        FOREIGN KEY (fk_i_item_id) REFERENCES /*TABLE_PREFIX*/t_item (pk_i_id),
        FOREIGN KEY (fk_i_user_id) REFERENCES /*TABLE_PREFIX*/t_user (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_preference (
    s_section VARCHAR(40) NOT NULL,
    s_name VARCHAR(40) NOT NULL,
    s_value LONGTEXT NOT NULL,
    e_type ENUM('STRING', 'INTEGER', 'BOOLEAN') NOT NULL,

        UNIQUE KEY (s_section, s_name)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_pages (
    pk_i_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    s_internal_name VARCHAR(50) NULL,
    b_indelible BOOLEAN NOT NULL DEFAULT FALSE,
    dt_pub_date DATETIME NOT NULL,
    dt_mod_date DATETIME NULL,

        PRIMARY KEY (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_pages_description (
    fk_i_pages_id INT UNSIGNED NOT NULL,
    fk_c_locale_code CHAR(5) NOT NULL,
    s_title VARCHAR(255) NOT NULL,
    s_text TEXT,

        PRIMARY KEY (fk_i_pages_id, fk_c_locale_code),
        FOREIGN KEY (fk_i_pages_id) REFERENCES /*TABLE_PREFIX*/t_pages (pk_i_id),
        FOREIGN KEY (fk_c_locale_code) REFERENCES /*TABLE_PREFIX*/t_locale (pk_c_code)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_plugin_category (
    s_plugin_name VARCHAR(40) NOT NULL,
    fk_i_category_id INT UNSIGNED NOT NULL,

        INDEX (fk_i_category_id),
        FOREIGN KEY (fk_i_category_id) REFERENCES /*TABLE_PREFIX*/t_category (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_cron (
  e_type enum('INSTANT','HOURLY','DAILY','WEEKLY','CUSTOM') NOT NULL,
  d_last_exec DATETIME NOT NULL,
  d_next_exec DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_alerts (
  s_email VARCHAR(100) DEFAULT NULL,
  fk_i_user_id INT UNSIGNED DEFAULT NULL,
  s_search LONGTEXT,
  e_type enum('INSTANT','DAILY','WEEKLY','CUSTOM') NOT NULL
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_keywords (
    s_md5 VARCHAR(32) NOT NULL,
    fk_c_locale_code CHAR(5) NOT NULL,
    s_original_text VARCHAR(255) NOT NULL,
    s_anchor_text VARCHAR(255) NOT NULL,
    s_normalized_text VARCHAR(255) NOT NULL,
    fk_i_category_id INT UNSIGNED NULL,
    fk_i_city_id INT UNSIGNED NULL,

        PRIMARY KEY (s_md5, fk_c_locale_code),
        INDEX (fk_i_category_id),
        INDEX (fk_i_city_id),
        FOREIGN KEY (fk_i_category_id) REFERENCES /*TABLE_PREFIX*/t_category (pk_i_id),
        FOREIGN KEY (fk_i_city_id) REFERENCES /*TABLE_PREFIX*/t_city (pk_i_id),
        FOREIGN KEY (fk_c_locale_code) REFERENCES /*TABLE_PREFIX*/t_locale (pk_c_code)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';
