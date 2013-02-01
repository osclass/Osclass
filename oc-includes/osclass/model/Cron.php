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
     *
     */
    class Cron extends DAO
    {
        /**
         *
         * @var type
         */
        private static $instance;

        public static function newInstance()
        {
            if( !self::$instance instanceof self ) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        /**
         *
         */
        function __construct()
        {
            parent::__construct();
            $this->setTableName('t_cron');
            $this->setFields( array('e_type', 'd_last_exec', 'd_next_exec') );
        }

        /**
         * Return crons by type
         *
         * @access public
         * @since unknown
         * @param string $type
         * @return array
         */
        function getCronByType($type)
        {
            $this->dao->select('*');
            $this->dao->from($this->getTableName());
            $this->dao->where('e_type', $type);
            $result = $this->dao->get();

            if( $result->numRows == 0 ) {
                return false;
            }

            return $result->row();
        }
    }

    /* file end: ./oc-includes/osclass/model/Cron.php */
?>