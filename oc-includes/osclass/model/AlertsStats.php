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
     * Model database for AlertsStats table
     *
     * @package Osclass
     * @subpackage Model
     * @since 3.1
     */
    class AlertsStats extends DAO
    {
        /**
         * It references to self object: AlertsStats.
         * It is used as a singleton
         *
         * @access private
         * @since 3.1
         * @var AlertsStats
         */
        private static $instance;

        /**
         * It creates a new AlertsStats object class ir if it has been created
         * before, it return the previous object
         *
         * @access public
         * @since 3.1
         * @return AlertsStats
         */
        public static function newInstance()
        {
            if( !self::$instance instanceof self ) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        /**
         * Set data related to t_alerts_sent table
         */
        public function __construct()
        {
            parent::__construct();
            $this->setTableName('t_alerts_sent');
            $this->setPrimaryKey('d_date');
            $this->setFields( array('d_date', 'i_num_alerts_sent') );
        }

        /**
         * Increase the stat column given column name and item id
         *
         * @access public
         * @since 3.1
         * @param string $date
         * @return bool
         */
        function increase($date)
        {
            // check the date it's ok
            if( !preg_match('|^[0-9]{4}-[0-9]{2}-[0-9]{2}$|', $date) ) {
                return false;
            }

            // first we try to insert
            if( $this->insert(array('d_date' => $date, 'i_num_alerts_sent' => '1')) ) {
                return true;
            }

            // duplicate key?
            if( $this->getErrorLevel() != 1062 ) {
                return false;
            }

            $sql = sprintf("UPDATE %s SET i_num_alerts_sent = i_num_alerts_sent + 1 WHERE d_date = '%s'", $this->getTableName(), $date);
            $this->dao->query($sql);

            return true;
        }
    }

    /* file end: ./oc-includes/osclass/model/AlertsStats.php */
?>