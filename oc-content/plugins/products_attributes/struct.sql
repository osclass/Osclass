CREATE TABLE /*TABLE_PREFIX*/t_item_products_attr (
    fk_i_item_id INT UNSIGNED NOT NULL,
    s_make VARCHAR(40),
    s_model VARCHAR(60),

        PRIMARY KEY (fk_i_item_id),
        FOREIGN KEY (fk_i_item_id) REFERENCES /*TABLE_PREFIX*/t_item (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';