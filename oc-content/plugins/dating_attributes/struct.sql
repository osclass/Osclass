CREATE TABLE /*TABLE_PREFIX*/t_item_dating_attr (
    fk_i_item_id INT UNSIGNED NOT NULL,
    e_gender_from ENUM('MAN', 'WOMAN', 'NI'),
    e_gender_to ENUM('MAN', 'WOMAN', 'NI'),
    e_relation ENUM('FRIENDSHIP', 'FORMAL', 'INFORMAL', 'NI'),

        FOREIGN KEY (fk_i_item_id) REFERENCES /*TABLE_PREFIX*/t_item (pk_i_id)
        PRIMARY KEY (fk_i_item_id),
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';