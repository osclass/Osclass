CREATE TABLE  /*TABLE_PREFIX*/t_paypal_log (
    pk_i_id INT NOT NULL AUTO_INCREMENT ,
    s_concept VARCHAR( 200 ) NOT NULL ,
    dt_date DATETIME NOT NULL ,
    s_code VARCHAR( 17 ) NOT NULL ,
    f_amount FLOAT NOT NULL ,
    s_currency_code VARCHAR( 3 ) NULL ,
    s_email VARCHAR( 200 ) NULL ,
    fk_i_user_id INT NULL ,
    fk_i_item_id INT NULL ,
    s_source VARCHAR( 10 ) NOT NULL,
    i_product_type VARCHAR( 15 ) NOT NULL,

    PRIMARY KEY(pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_paypal_wallet (
    fk_i_user_id INT UNSIGNED NOT NULL,
    f_amount FLOAT NOT NULL,

        PRIMARY KEY (fk_i_user_id),
        FOREIGN KEY (fk_i_user_id) REFERENCES /*TABLE_PREFIX*/t_user (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_paypal_premium (
    fk_i_item_id INT UNSIGNED NOT NULL,
    dt_date DATETIME NOT NULL ,
    fk_i_paypal_id INT NOT NULL,

        PRIMARY KEY (fk_i_item_id),
        FOREIGN KEY (fk_i_item_id) REFERENCES /*TABLE_PREFIX*/t_item (pk_i_id),
        FOREIGN KEY (fk_i_paypal_id) REFERENCES /*TABLE_PREFIX*/t_paypal_log (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_paypal_publish (
    fk_i_item_id INT UNSIGNED NOT NULL,
    dt_date DATETIME NOT NULL ,
    b_paid BOOLEAN NOT NULL DEFAULT FALSE,
    fk_i_paypal_id INT NULL,

        PRIMARY KEY (fk_i_item_id),
        FOREIGN KEY (fk_i_item_id) REFERENCES /*TABLE_PREFIX*/t_item (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_paypal_prices (
    fk_i_category_id INT UNSIGNED NOT NULL,
    f_publish_cost FLOAT NULL ,
    f_premium_cost FLOAT NULL ,

        PRIMARY KEY (fk_i_category_id),
        FOREIGN KEY (fk_i_category_id) REFERENCES /*TABLE_PREFIX*/t_category (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';