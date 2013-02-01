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
     * OSCLocale DAO
     */
    class OSCLocale extends DAO
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
            $this->setTableName('t_locale');
            $this->setPrimaryKey('pk_c_code');
            $array_fields = array(
                'pk_c_code',
                's_name',
                's_short_name',
                's_description',
                's_version',
                's_author_name',
                's_author_url',
                's_currency_format',
                's_dec_point',
                's_thousands_sep',
                'i_num_dec',
                's_date_format',
                's_stop_words',
                'b_enabled',
                'b_enabled_bo'
            );
            $this->setFields($array_fields);
        }

        /**
         * Return all locales enabled.
         *
         * @access public
         * @since unknown
         * @param boole $isBo
         * @param boole $indexedByKk
         * @return array
         */
        function listAllEnabled($isBo = false, $indexedByPk = false)
        {
            $this->dao->select();
            $this->dao->from($this->getTableName());
            if($isBo) {
                $this->dao->where('b_enabled_bo', 1);
            } else {
                $this->dao->where('b_enabled', 1);
            }
            $this->dao->orderBy('s_name', 'ASC');
            $result = $this->dao->get();

            if($result == false) {
                return array();
            }

            $aResults = $result->result();

            if ($indexedByPk) {
                $aTmp = array();
                for ($i = 0; $i < count($aResults); $i++) {
                    $aTmp[(string)$aResults[$i][$this->getPrimaryKey()]] = $aResults[$i];
                }
                $aResults = $aTmp;
            }

            return($aResults);
        }

        /**
         * Return all locales by code
         *
         * @access public
         * @since 2.3
         * @param string $code
         * @return array
         */
        function findByCode($code)
        {
            $this->dao->select();
            $this->dao->from($this->getTableName());
            $this->dao->where('pk_c_code', $code);
            $result = $this->dao->get();

            if($result == false) {
                return array();
            }

            return $result->result();
        }

        /**
         * Delete all related to locale code.
         *
         * @access public
         * @since unknown
         * @param string $locale
         * @return bool
         */
        public function deleteLocale($locale) {
            osc_run_hook('delete_locale', $locale);

            $array_where = array('fk_c_locale_code' => $locale );
            $this->dao->delete(DB_TABLE_PREFIX.'t_category_description',  $array_where);
            $this->dao->delete(DB_TABLE_PREFIX.'t_item_description', $array_where);
            $this->dao->delete(DB_TABLE_PREFIX.'t_keywords', $array_where);
            $this->dao->delete(DB_TABLE_PREFIX.'t_user_description', $array_where);
            $this->dao->delete(DB_TABLE_PREFIX.'t_pages_description', $array_where);
            return $this->dao->delete($this->getTableName(), array('pk_c_code' => $locale ));
        }
    }

    /* file end: ./oc-includes/osclass/model/OSCLocale.php */
?>