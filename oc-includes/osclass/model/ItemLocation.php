<?php if ( !defined('ABS_PATH') ) exit('ABS_PATH is not loaded. Direct access is not allowed.');

/*
 * Copyright 2014 Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

    /**
     * Model database for ItemLocation table
     *
     * @package Osclass
     * @subpackage Model
     * @since unknown
     */
    class ItemLocation extends DAO
    {
        /**
         * It references to self object: ItemLocation.
         * It is used as a singleton
         *
         * @access private
         * @since unknown
         * @var ItemResource
         */
        private static $instance;

        /**
         * It creates a new ItemLocation object class ir if it has been created
         * before, it return the previous object
         *
         * @access public
         * @since unknown
         * @return ItemLocation
         */
        public static function newInstance()
        {
            if( !self::$instance instanceof self ) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        /**
         * Set data related to t_item_location table
         */
        function __construct()
        {
            parent::__construct();
            $this->setTableName('t_item_location');
            $this->setPrimaryKey('fk_i_item_id');
            $array_fields = array(
                'fk_i_item_id',
                'fk_c_country_code',
                's_country',
                's_address',
                's_zip',
                'fk_i_region_id',
                's_region',
                'fk_i_city_id',
                's_city',
                'fk_i_city_area_id',
                's_city_area',
                'd_coord_lat',
                'd_coord_long'
                );
            $this->setFields($array_fields);
        }
    }

    /* file end: ./oc-includes/osclass/model/ItemLocation.php */
?>