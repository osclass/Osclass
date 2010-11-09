CREATE TABLE /*TABLE_PREFIX*/t_item_house_attr (
    fk_i_item_id INT UNSIGNED NOT NULL,
    s_square_meters INT(6) UNSIGNED,
    i_num_rooms INT(2) UNSIGNED,
    i_num_bathrooms INT(2) UNSIGNED,
    e_type ENUM('FOR RENT', 'FOR SALE'),
    e_status ENUM('NEW CONSTRUCTION', 'TO RENOVATE', 'GOOD CONDITION'),
    i_num_floors INT(2) UNSIGNED,
    i_num_garages INT(2) UNSIGNED,
    b_heating BOOLEAN,
    b_air_condition BOOLEAN,
    b_elevator BOOLEAN,
    b_terrace BOOLEAN,
    b_parking BOOLEAN,
    b_furnished BOOLEAN,
    b_new BOOLEAN,
    b_by_owner BOOLEAN,
    s_condition VARCHAR(255),
    i_year VARCHAR(4),
    s_agency VARCHAR(255),
    i_floor_number INT(3) UNSIGNED,
    i_plot_area INT(6) UNSIGNED,
    fk_i_property_type_id INT(2) UNSIGNED,
	
        PRIMARY KEY (fk_i_item_id),
        FOREIGN KEY (fk_i_item_id) REFERENCES /*TABLE_PREFIX*/t_item (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_item_house_description_attr (
    fk_i_item_id INT UNSIGNED NOT NULL,
    fk_c_locale_code CHAR(5) NOT NULL,
    s_transport VARCHAR(255),
    s_zone VARCHAR(255),

        PRIMARY KEY (fk_i_item_id, fk_c_locale_code),
        FOREIGN KEY (fk_c_locale_code) REFERENCES /*TABLE_PREFIX*/t_locale (pk_c_code),
        FOREIGN KEY (fk_i_item_id) REFERENCES /*TABLE_PREFIX*/t_item (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';


CREATE TABLE /*TABLE_PREFIX*/t_item_house_property_type_attr (
    pk_i_id INT(2) UNSIGNED NOT NULL,
    fk_c_locale_code CHAR(5) NOT NULL,
    s_name VARCHAR(255),
	
        PRIMARY KEY (pk_i_id, fk_c_locale_code),
        FOREIGN KEY (fk_c_locale_code) REFERENCES /*TABLE_PREFIX*/t_locale (pk_c_code)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';