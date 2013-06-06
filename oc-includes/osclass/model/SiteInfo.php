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
    class SiteInfo extends DAO
    {
        /**
         *
         * @var type
         */
        private static $instance;
        /**
         *
         * @var type
         */
        private $daoMetadata;
        /**
         *
         * @var type
         */
        private $siteInfo;

        /**
         *
         * @return type
         */
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
        public function __construct()
        {
            $this->setTableName('tbl_sites');
            $this->setPrimaryKey('s_site');
            $this->setFields( array('s_site', 'dt_date', 'fk_i_user_id', 's_db_name', 's_db_host', 's_db_user', 's_db_password', 's_upload_path') );

            $conn = new DBConnectionClass(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            $conn->connectToMetadataDb();
            $m_db = $conn->getMetadataDb();
            $this->daoMetadata = new DBCommandClass($m_db);

            $this->toArray();
        }

        /**
         *
         */
        public function toArray()
        {
            $domain = 'http://' . $_SERVER['HTTP_HOST'] . '/';
            $this->siteInfo = $this->findByPrimaryKey($domain);
        }

        /**
         *
         * @access public
         * @since unknown
         * @param type $key
         * @return type
         */
        public function get($key)
        {
            if (!isset($this->siteInfo[$key])) {
                return '';
            }

            return ($this->siteInfo[$key]);
        }

        /**
         *
         * @access public
         * @since unknown
         * @param type $value
         * @return type
         */
        public function findByPrimaryKey($value)
        {
            $this->daoMetadata->select($this->getFields());
            $this->daoMetadata->from($this->getTableName());
            $this->daoMetadata->where('s_site', $value);
            $result = $this->daoMetadata->get();

            if( $result == false ) {
                return array();
            }

            return $result->row();
        }

        /**
         *
         * @access public
         * @since unknown
         * @param type $table
         * @return type
         */
        public function setTableName($table)
        {
            return $this->tableName = $table;
        }
    }

    /* file end: ./oc-includes/osclass/model/SiteInfo.php */
?>