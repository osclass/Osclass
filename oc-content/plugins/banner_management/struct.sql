CREATE TABLE /*TABLE_PREFIX*/t_bm_banner (
    pk_i_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    s_name VARCHAR(100),
    s_code TINYTEXT,
    i_weight INT SIGNED,
    i_views INT SIGNED,

        PRIMARY KEY (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_bm_campaign (
    pk_i_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    s_campaign_name VARCHAR(100),
    s_code TINYTEXT,

        PRIMARY KEY (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_bm_banner_campaign (
    fk_i_banner_id INT UNSIGNED NOT NULL,
    fk_i_campaign_id INT UNSIGNED NOT NULL,

        PRIMARY KEY (fk_i_banner_id, fk_i_campaign_id),
        FOREIGN KEY (fk_i_banner_id) REFERENCES /*TABLE_PREFIX*/t_bm_banner (pk_i_id),
        FOREIGN KEY (fk_i_campaign_id) REFERENCES /*TABLE_PREFIX*/t_bm_campaign (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';