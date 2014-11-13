SET NAMES 'UTF8';

CREATE TABLE /*TABLE_PREFIX*/t_locale (
    pk_c_code CHAR(5) NOT NULL,
    s_name VARCHAR(100) NOT NULL,
    s_short_name VARCHAR(40) NOT NULL,
    s_description VARCHAR(100) NOT NULL,
    s_version VARCHAR(20) NOT NULL,
    s_author_name VARCHAR(100) NOT NULL,
    s_author_url VARCHAR(100) NOT NULL,
    s_currency_format VARCHAR(50) NOT NULL,
    s_dec_point VARCHAR(2) NULL DEFAULT '.',
    s_thousands_sep VARCHAR(2) NULL DEFAULT '',
    i_num_dec TINYINT(4) NULL DEFAULT 2,
    s_date_format VARCHAR(20) NOT NULL,
    s_stop_words TEXT NULL,
    b_enabled TINYINT(1) NOT NULL DEFAULT 1,
    b_enabled_bo TINYINT(1) NOT NULL DEFAULT 1,

        PRIMARY KEY (pk_c_code),
        UNIQUE KEY (s_short_name)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_country (
    pk_c_code CHAR(2) NOT NULL,
    s_name VARCHAR(80) NOT NULL,
    s_slug VARCHAR(80) NOT NULL DEFAULT '',

        PRIMARY KEY (pk_c_code),
        INDEX idx_s_slug (s_slug),
        INDEX idx_s_name (s_name)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_currency (
    pk_c_code CHAR(3) NOT NULL,
    s_name VARCHAR(40) NOT NULL,
    s_description VARCHAR(80) NULL,
    b_enabled TINYINT(1) NOT NULL DEFAULT 1,

        PRIMARY KEY (pk_c_code),
        UNIQUE KEY (s_name)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_region (
    pk_i_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    fk_c_country_code CHAR(2) NOT NULL,
    s_name VARCHAR(60) NOT NULL,
    s_slug VARCHAR(60) NOT NULL DEFAULT '',
    b_active TINYINT(1) NOT NULL DEFAULT 1,

        PRIMARY KEY (pk_i_id),
        INDEX (fk_c_country_code),
        INDEX idx_s_name (s_name),
        INDEX idx_s_slug (s_slug),
        FOREIGN KEY (fk_c_country_code) REFERENCES /*TABLE_PREFIX*/t_country (pk_c_code)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';


CREATE TABLE /*TABLE_PREFIX*/t_city (
    pk_i_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    fk_i_region_id INT(10) UNSIGNED NOT NULL,
    s_name VARCHAR(60) NOT NULL,
    s_slug VARCHAR(60) NOT NULL DEFAULT '',
    fk_c_country_code CHAR(2) NULL,
    b_active TINYINT(1) NOT NULL DEFAULT 1,

        PRIMARY KEY (pk_i_id),
        INDEX (fk_i_region_id),
        INDEX idx_s_name (s_name),
        INDEX idx_s_slug (s_slug),
        FOREIGN KEY (fk_i_region_id) REFERENCES /*TABLE_PREFIX*/t_region (pk_i_id),
        FOREIGN KEY (fk_c_country_code) REFERENCES /*TABLE_PREFIX*/t_country (pk_c_code)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_city_area (
    pk_i_id INT(10) UNSIGNED NOT NULL,
    fk_i_city_id INT(10) UNSIGNED NOT NULL,
    s_name VARCHAR(255) NOT NULL,

        PRIMARY KEY (pk_i_id),
        INDEX (fk_i_city_id),
        INDEX idx_s_name (s_name),
        FOREIGN KEY (fk_i_city_id) REFERENCES /*TABLE_PREFIX*/t_city (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_widget (
    pk_i_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    s_description VARCHAR(40) NOT NULL,
    s_location VARCHAR(40) NOT NULL,
    e_kind ENUM('TEXT', 'HTML') NOT NULL,
    s_content MEDIUMTEXT NOT NULL,

        PRIMARY KEY (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_admin (
    pk_i_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    s_name VARCHAR(100) NOT NULL,
    s_username VARCHAR(40) NOT NULL,
    s_password CHAR(60) NOT NULL,
    s_email VARCHAR(100) NULL,
    s_secret VARCHAR(40) NULL,
    b_moderator TINYINT(1) NOT NULL DEFAULT 0,

        PRIMARY KEY (pk_i_id),
        UNIQUE KEY (s_username),
        UNIQUE KEY (s_email)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_user (
    pk_i_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    dt_reg_date DATETIME NOT NULL,
    dt_mod_date DATETIME NULL,
    s_name VARCHAR(100) NOT NULL,
    s_username VARCHAR(100) NOT NULL,
    s_password CHAR(60) NOT NULL,
    s_secret VARCHAR(40) NULL,
    s_email VARCHAR(100) NOT NULL,
    s_website VARCHAR(100) NULL,
    s_phone_land VARCHAR(45),
    s_phone_mobile VARCHAR(45),
    b_enabled TINYINT(1) NOT NULL DEFAULT 1,
    b_active TINYINT(1) NOT NULL DEFAULT 0,
    s_pass_code VARCHAR(100) NULL ,
    s_pass_date DATETIME NULL ,
    s_pass_ip VARCHAR(15) NULL,
    fk_c_country_code CHAR(2) NULL,
    s_country VARCHAR(40) NULL,
    s_address VARCHAR(100) NULL,
    s_zip VARCHAR(15) NULL,
    fk_i_region_id INT(10) UNSIGNED NULL,
    s_region VARCHAR(100),
    fk_i_city_id INT(10) UNSIGNED NULL,
    s_city VARCHAR(100) NULL,
    fk_i_city_area_id INT(10) UNSIGNED NULL,
    s_city_area VARCHAR(200) NULL,
    d_coord_lat DECIMAL(10,6),
    d_coord_long DECIMAL(10,6),
    b_company TINYINT(1) NOT NULL DEFAULT 0,
    i_items INT(10) UNSIGNED NULL DEFAULT 0,
    i_comments INT(10) UNSIGNED NULL DEFAULT 0,
    dt_access_date DATETIME NOT NULL DEFAULT  '0000-00-00 00:00:00',
    s_access_ip VARCHAR(15) NOT NULL DEFAULT '',

        PRIMARY KEY (pk_i_id),
        UNIQUE KEY (s_email),
        INDEX idx_s_name (s_name(6)),
        INDEX idx_s_username (s_username),
        FOREIGN KEY (fk_c_country_code) REFERENCES /*TABLE_PREFIX*/t_country (pk_c_code),
        FOREIGN KEY (fk_i_region_id) REFERENCES /*TABLE_PREFIX*/t_region (pk_i_id),
        FOREIGN KEY (fk_i_city_id) REFERENCES /*TABLE_PREFIX*/t_city (pk_i_id),
        FOREIGN KEY (fk_i_city_area_id) REFERENCES /*TABLE_PREFIX*/t_city_area (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_user_description (
    fk_i_user_id INT(10) UNSIGNED NOT NULL,
    fk_c_locale_code CHAR(5) NOT NULL,
    s_info TEXT NULL,

        PRIMARY KEY (fk_i_user_id, fk_c_locale_code),
        FOREIGN KEY (fk_i_user_id) REFERENCES /*TABLE_PREFIX*/t_user (pk_i_id),
        FOREIGN KEY (fk_c_locale_code) REFERENCES /*TABLE_PREFIX*/t_locale (pk_c_code)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_user_email_tmp (
    fk_i_user_id INT(10) UNSIGNED NOT NULL,
    s_new_email VARCHAR(100) NOT NULL,
    dt_date DATETIME NOT NULL,

        PRIMARY KEY (fk_i_user_id),
        FOREIGN KEY (fk_i_user_id) REFERENCES /*TABLE_PREFIX*/t_user (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_category (
    pk_i_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    fk_i_parent_id INT(10) UNSIGNED NULL,
    i_expiration_days INT(3) UNSIGNED NOT NULL DEFAULT 0,
    i_position INT(2) UNSIGNED NOT NULL DEFAULT 0,
    b_enabled TINYINT(1) NOT NULL DEFAULT 1,
    b_price_enabled TINYINT(1) NOT NULL DEFAULT 1,
    s_icon VARCHAR(250) NULL,

        PRIMARY KEY (pk_i_id),
        INDEX (fk_i_parent_id),
        INDEX (i_position),
        FOREIGN KEY (fk_i_parent_id) REFERENCES /*TABLE_PREFIX*/t_category (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_category_description (
    fk_i_category_id INT(10) UNSIGNED NOT NULL,
    fk_c_locale_code CHAR(5) NOT NULL,
    s_name VARCHAR(100) NULL DEFAULT NULL,
    s_description TEXT NULL,
    s_slug VARCHAR(100) NOT NULL,

        PRIMARY KEY (fk_i_category_id, fk_c_locale_code),
        INDEX idx_s_slug (s_slug),
        FOREIGN KEY (fk_i_category_id) REFERENCES /*TABLE_PREFIX*/t_category (pk_i_id),
        FOREIGN KEY (fk_c_locale_code) REFERENCES /*TABLE_PREFIX*/t_locale (pk_c_code)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_category_stats (
    fk_i_category_id INT(10) UNSIGNED NOT NULL,
    i_num_items INT(10) UNSIGNED NOT NULL DEFAULT 0,

        PRIMARY KEY (fk_i_category_id),
        FOREIGN KEY (fk_i_category_id) REFERENCES /*TABLE_PREFIX*/t_category (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_item (
    pk_i_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    fk_i_user_id INT(10) UNSIGNED NULL,
    fk_i_category_id INT(10) UNSIGNED NOT NULL,
    dt_pub_date DATETIME NOT NULL,
    dt_mod_date DATETIME NULL,
    f_price FLOAT NULL,
    i_price BIGINT(20) NULL,
    fk_c_currency_code CHAR(3) NULL,
    s_contact_name VARCHAR(100) NULL,
    s_contact_email VARCHAR(140) NOT NULL,
    s_ip VARCHAR(64) NOT NULL DEFAULT '',
    b_premium TINYINT(1) NOT NULL DEFAULT 0,
    b_enabled TINYINT(1) NOT NULL DEFAULT 1,
    b_active TINYINT(1) NOT NULL DEFAULT 0,
    b_spam TINYINT(1) NOT NULL DEFAULT 0,
    s_secret VARCHAR(40) NULL,
    b_show_email TINYINT(1) NULL,
    dt_expiration datetime NOT NULL DEFAULT '9999-12-31 23:59:59',

        PRIMARY KEY (pk_i_id),
        FOREIGN KEY (fk_i_user_id) REFERENCES /*TABLE_PREFIX*/t_user (pk_i_id),
        FOREIGN KEY (fk_i_category_id) REFERENCES /*TABLE_PREFIX*/t_category (pk_i_id),
        FOREIGN KEY (fk_c_currency_code) REFERENCES /*TABLE_PREFIX*/t_currency (pk_c_code),

        INDEX (fk_i_user_id),
        INDEX idx_b_premium (b_premium),
        INDEX idx_s_contact_email (s_contact_email(10)),
        INDEX (fk_i_category_id),
        INDEX (fk_c_currency_code),
        INDEX idx_pub_date (dt_pub_date),
        INDEX idx_price (i_price)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_item_description (
    fk_i_item_id INT(10) UNSIGNED NOT NULL,
    fk_c_locale_code CHAR(5) NOT NULL,
    s_title VARCHAR(100) NOT NULL,
    s_description MEDIUMTEXT NOT NULL,
        PRIMARY KEY (fk_i_item_id, fk_c_locale_code),
        FULLTEXT s_description (s_description, s_title)
) ENGINE=MyISAM DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';


CREATE TABLE /*TABLE_PREFIX*/t_item_location (
    fk_i_item_id INT(10) UNSIGNED NOT NULL,
    fk_c_country_code CHAR(2) NULL,
    s_country VARCHAR(40) NULL,
    s_address VARCHAR(100) NULL,
    s_zip VARCHAR(15) NULL,
    fk_i_region_id INT(10) UNSIGNED NULL,
    s_region VARCHAR(100),
    fk_i_city_id INT(10) UNSIGNED NULL,
    s_city VARCHAR(100) NULL,
    fk_i_city_area_id INT(10) UNSIGNED NULL,
    s_city_area VARCHAR(200) NULL,
    d_coord_lat DECIMAL(10,6),
    d_coord_long DECIMAL(10,6),

        PRIMARY KEY (fk_i_item_id),
        FOREIGN KEY (fk_i_item_id) REFERENCES /*TABLE_PREFIX*/t_item (pk_i_id),
        FOREIGN KEY (fk_c_country_code) REFERENCES /*TABLE_PREFIX*/t_country (pk_c_code),
        FOREIGN KEY (fk_i_region_id) REFERENCES /*TABLE_PREFIX*/t_region (pk_i_id),
        FOREIGN KEY (fk_i_city_id) REFERENCES /*TABLE_PREFIX*/t_city (pk_i_id),
        FOREIGN KEY (fk_i_city_area_id) REFERENCES /*TABLE_PREFIX*/t_city_area (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_item_stats (
    fk_i_item_id INT(10) UNSIGNED NOT NULL,
    i_num_views INT(10) UNSIGNED NOT NULL DEFAULT 0,
    i_num_spam INT(10) UNSIGNED NOT NULL DEFAULT 0,
    i_num_repeated INT(10) UNSIGNED NOT NULL DEFAULT 0,
    i_num_bad_classified INT(10) UNSIGNED NOT NULL DEFAULT 0,
    i_num_offensive INT(10) UNSIGNED NOT NULL DEFAULT 0,
    i_num_expired INT(10) UNSIGNED NOT NULL DEFAULT 0,
    i_num_premium_views INT(10) UNSIGNED NOT NULL DEFAULT 0,
    dt_date DATE NOT NULL,

        PRIMARY KEY (fk_i_item_id, dt_date),
        INDEX (dt_date, fk_i_item_id),
        FOREIGN KEY (fk_i_item_id) REFERENCES /*TABLE_PREFIX*/t_item (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_item_resource (
    pk_i_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    fk_i_item_id INT(10) UNSIGNED NOT NULL,
    s_name VARCHAR(60) NULL,
    s_extension VARCHAR(10) NULL,
    s_content_type VARCHAR(40) NULL,
    s_path VARCHAR(250) NULL,

        PRIMARY KEY (pk_i_id),
        INDEX (fk_i_item_id),
        INDEX idx_s_content_type (pk_i_id,s_content_type(10)),
        FOREIGN KEY (fk_i_item_id) REFERENCES /*TABLE_PREFIX*/t_item (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_item_comment (
    pk_i_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    fk_i_item_id INT(10) UNSIGNED NOT NULL,
    dt_pub_date DATETIME NOT NULL,
    s_title VARCHAR(200) NOT NULL,
    s_author_name VARCHAR(100) NOT NULL,
    s_author_email VARCHAR(100) NOT NULL,
    s_body TEXT NOT NULL,
    b_enabled TINYINT(1) NOT NULL DEFAULT 1,
    b_active TINYINT(1) NOT NULL DEFAULT 0,
    b_spam TINYINT(1) NOT NULL DEFAULT 0,
    fk_i_user_id INT(10) UNSIGNED NULL,

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
    pk_i_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    s_internal_name VARCHAR(50) NULL,
    b_indelible TINYINT(1) NOT NULL DEFAULT 0,
    b_link TINYINT(1) NOT NULL DEFAULT 1,
    dt_pub_date DATETIME NOT NULL,
    dt_mod_date DATETIME NULL,
    i_order INT(3) NOT NULL DEFAULT 0,
    s_meta TEXT NULL,

        PRIMARY KEY (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_pages_description (
    fk_i_pages_id INT(10) UNSIGNED NOT NULL,
    fk_c_locale_code CHAR(5) NOT NULL,
    s_title VARCHAR(255) NOT NULL,
    s_text TEXT,

        PRIMARY KEY (fk_i_pages_id, fk_c_locale_code),
        FOREIGN KEY (fk_i_pages_id) REFERENCES /*TABLE_PREFIX*/t_pages (pk_i_id),
        FOREIGN KEY (fk_c_locale_code) REFERENCES /*TABLE_PREFIX*/t_locale (pk_c_code)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_plugin_category (
    s_plugin_name VARCHAR(40) NOT NULL,
    fk_i_category_id INT(10) UNSIGNED NOT NULL,

        INDEX (fk_i_category_id),
        FOREIGN KEY (fk_i_category_id) REFERENCES /*TABLE_PREFIX*/t_category (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_cron (
  e_type enum('INSTANT','HOURLY','DAILY','WEEKLY','CUSTOM') NOT NULL,
  d_last_exec DATETIME NOT NULL,
  d_next_exec DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_alerts (
    pk_i_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    s_email VARCHAR(100) DEFAULT NULL,
    fk_i_user_id INT(10) UNSIGNED DEFAULT NULL,
    s_search LONGTEXT,
    s_secret VARCHAR(40) NULL,
    b_active TINYINT(1) NOT NULL DEFAULT 0,
    e_type enum('INSTANT','HOURLY','DAILY','WEEKLY','CUSTOM') NOT NULL,
    dt_date DATETIME NULL,
    dt_unsub_date DATETIME NULL DEFAULT NULL,

    PRIMARY KEY (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_alerts_sent (
    d_date DATE NOT NULL,
    i_num_alerts_sent INT(10) UNSIGNED NOT NULL DEFAULT 0,

    PRIMARY KEY (d_date)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_keywords (
    s_md5 VARCHAR(32) NOT NULL,
    fk_c_locale_code CHAR(5) NOT NULL,
    s_original_text VARCHAR(255) NOT NULL,
    s_anchor_text VARCHAR(255) NOT NULL,
    s_normalized_text VARCHAR(255) NOT NULL,
    fk_i_category_id INT(10) UNSIGNED NULL,
    fk_i_city_id INT(10) UNSIGNED NULL,

        PRIMARY KEY (s_md5, fk_c_locale_code),
        INDEX (fk_i_category_id),
        INDEX (fk_i_city_id),
        FOREIGN KEY (fk_i_category_id) REFERENCES /*TABLE_PREFIX*/t_category (pk_i_id),
        FOREIGN KEY (fk_i_city_id) REFERENCES /*TABLE_PREFIX*/t_city (pk_i_id),
        FOREIGN KEY (fk_c_locale_code) REFERENCES /*TABLE_PREFIX*/t_locale (pk_c_code)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_latest_searches (
  d_date DATETIME NOT NULL,
  s_search VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_meta_fields (
    pk_i_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    s_name VARCHAR(255) NOT NULL,
    s_slug VARCHAR(255) NOT NULL,
    e_type ENUM('TEXT','TEXTAREA','DROPDOWN','RADIO','CHECKBOX','URL', 'DATE', 'DATEINTERVAL') NOT NULL DEFAULT  'TEXT',
    s_options VARCHAR(2048) NULL,
    b_required TINYINT(1) NOT NULL DEFAULT 0,
    b_searchable TINYINT(1) NOT NULL DEFAULT 0,

        PRIMARY KEY (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_meta_categories (
    fk_i_category_id INT(10) UNSIGNED NOT NULL,
    fk_i_field_id INT(10) UNSIGNED NOT NULL,

        PRIMARY KEY (fk_i_category_id, fk_i_field_id),
        FOREIGN KEY (fk_i_category_id) REFERENCES /*TABLE_PREFIX*/t_category (pk_i_id),
        FOREIGN KEY (fk_i_field_id) REFERENCES /*TABLE_PREFIX*/t_meta_fields (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_item_meta (
    fk_i_item_id INT(10) UNSIGNED NOT NULL,
    fk_i_field_id INT(10) UNSIGNED NOT NULL,
    s_value TEXT NULL,
    s_multi VARCHAR(20) NOT NULL DEFAULT '',

        PRIMARY KEY (fk_i_item_id, fk_i_field_id, s_multi),
        INDEX s_value (s_value(255)),
        FOREIGN KEY (fk_i_item_id) REFERENCES /*TABLE_PREFIX*/t_item (pk_i_id),
        FOREIGN KEY (fk_i_field_id) REFERENCES /*TABLE_PREFIX*/t_meta_fields (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_log (
    dt_date DATETIME NOT NULL,
    s_section VARCHAR(50) NOT NULL,
    s_action VARCHAR(50) NOT NULL,
    fk_i_id INT(10) UNSIGNED NOT NULL,
    s_data VARCHAR(250) NOT NULL,
    s_ip VARCHAR(50) NOT NULL,
    s_who VARCHAR(50) NOT NULL,
    fk_i_who_id INT(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_city_stats (
    fk_i_city_id INT(10) UNSIGNED NOT NULL,
    i_num_items INT(10) UNSIGNED NOT NULL DEFAULT 0,

        PRIMARY KEY (fk_i_city_id),
        INDEX idx_num_items (i_num_items),
        FOREIGN KEY (fk_i_city_id) REFERENCES /*TABLE_PREFIX*/t_city (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_region_stats (
    fk_i_region_id INT(10) UNSIGNED NOT NULL,
    i_num_items INT(10) UNSIGNED NOT NULL DEFAULT 0,

        PRIMARY KEY (fk_i_region_id),
        INDEX idx_num_items (i_num_items),
        FOREIGN KEY (fk_i_region_id) REFERENCES /*TABLE_PREFIX*/t_region (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_country_stats (
    fk_c_country_code CHAR(2) NOT NULL,
    i_num_items INT(10) UNSIGNED NOT NULL DEFAULT 0,

        PRIMARY KEY (fk_c_country_code),
        INDEX idx_num_items (i_num_items),
        FOREIGN KEY (fk_c_country_code) REFERENCES /*TABLE_PREFIX*/t_country (pk_c_code)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_locations_tmp (
    id_location varchar(10) NOT NULL,
    e_type enum('COUNTRY','REGION','CITY') NOT NULL,
    PRIMARY KEY (id_location, e_type)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_ban_rule (
  pk_i_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  s_name VARCHAR(250) NOT NULL DEFAULT '',
  s_ip VARCHAR(50) NOT NULL DEFAULT '',
  s_email VARCHAR(250) NOT NULL DEFAULT '',

  PRIMARY KEY (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';
