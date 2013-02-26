<?php if ( !defined('ABS_PATH') ) exit('ABS_PATH is not loaded. Direct access is not allowed.');

    /*
     *      Osclass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2012 OSCLASS
     *
     *       This program is free software: you can redistribute it and/or
     *     modify it under the terms of the GNU Affero General Public License
     *     as published by the Free Software Foundation, either version 3 of
     *            the License, or (at your option) any later version.
     *
     *     This program is distributed in the hope that it will be useful, but
     *         WITHOUT ANY WARRANTY; without even the implied warranty of
     *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *             GNU Affero General Public License for more details.
     *
     *      You should have received a copy of the GNU Affero General Public
     * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
     */

    /**
     * Model database for Currency table
     *
     * @package Osclass
     * @subpackage Model
     * @since unknown
     */
    class Currency extends DAO
    {
        /**
         * It references to self object: Currency.
         * It is used as a singleton
         *
         * @access private
         * @since unknown
         * @var Currency
         */
        private static $instance;

        /**
         * It creates a new Currency object class ir if it has been created
         * before, it return the previous object
         *
         * @access public
         * @since unknown
         * @return Currency
         */
        public static function newInstance()
        {
            if( !self::$instance instanceof self ) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        /**
         * Set data related to t_currency table
         */
        function __construct()
        {
            parent::__construct();
            $this->setTableName('t_currency');
            $this->setPrimaryKey('pk_c_code');
            $this->setFields(array('pk_c_code', 's_name', 's_description', 'b_enabled'));
        }

    }

    /* file end: ./oc-includes/osclass/model/Currency.php */
?>