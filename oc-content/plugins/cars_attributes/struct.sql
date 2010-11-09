CREATE TABLE /*TABLE_PREFIX*/t_item_car_vehicle_type_attr (
    pk_i_id INT(2) UNSIGNED NOT NULL,
    fk_c_locale_code CHAR(5) NOT NULL,
    s_name VARCHAR(255),
	
        PRIMARY KEY (pk_i_id, fk_c_locale_code),
        FOREIGN KEY (fk_c_locale_code) REFERENCES /*TABLE_PREFIX*/t_locale (pk_c_code)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_item_car_make_attr (
    pk_i_id INT(2) UNSIGNED NOT NULL AUTO_INCREMENT,
    s_name VARCHAR(255),
	
        
        PRIMARY KEY (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_item_car_model_attr (
    pk_i_id INT(2) UNSIGNED NOT NULL AUTO_INCREMENT,
    fk_i_make_id INT(2) UNSIGNED NOT NULL,
    s_name VARCHAR(255),

        PRIMARY KEY (pk_i_id),
        FOREIGN KEY (fk_i_make_id) REFERENCES /*TABLE_PREFIX*/t_item_car_make_attr (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';


CREATE TABLE /*TABLE_PREFIX*/t_item_car_attr (
    fk_i_item_id INT(10) UNSIGNED NOT NULL,
    i_year INT(4) UNSIGNED,
    i_doors INT(1) UNSIGNED,
    i_seats INT(4) UNSIGNED,
    i_mileage INT(10) UNSIGNED,
    i_engine_size INT(10) UNSIGNED,
    fk_i_make_id INT(2) UNSIGNED,
    fk_i_model_id INT(2) UNSIGNED,
    i_num_airbags INT(2) UNSIGNED,
    e_transmission ENUM('MANUAL', 'AUTO'),
    e_fuel ENUM('DIESEL', 'GASOLINE', 'ELECTRIC-HIBRID', 'OTHER'),
    e_seller ENUM('DEALER', 'OWNER'),
    b_warranty BOOLEAN,
    b_new BOOLEAN,
    i_power INT(10) UNSIGNED,
    e_power_unit ENUM('KW', 'CV', 'CH', 'KM', 'HP', 'PS', 'PK', 'CP'),
    i_gears INT(1) UNSIGNED,
    fk_vehicle_type_id INT(10) UNSIGNED,
	
        INDEX (fk_i_item_id),
        PRIMARY KEY (fk_i_item_id),
        FOREIGN KEY (fk_i_item_id) REFERENCES /*TABLE_PREFIX*/t_item (pk_i_id),
        FOREIGN KEY (fk_i_make_id) REFERENCES /*TABLE_PREFIX*/t_item_car_make_attr (pk_i_id),
        FOREIGN KEY (fk_i_model_id) REFERENCES /*TABLE_PREFIX*/t_item_car_model_attr (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';


