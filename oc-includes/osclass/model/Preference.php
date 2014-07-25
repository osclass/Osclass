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
     *
     */
    class Preference extends DAO
    {
        /**
         *
         * @var type
         */
        private static $instance;
        /**
         * array for save preferences
         * @var array
         */
        private $pref;

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
            $this->setTableName('t_preference');
            /* $this->set_primary_key($key); // no primary key in preference table */
            $this->setFields( array('s_section', 's_name', 's_value', 'e_type') );
            $this->toArray();
        }

        /**
         * Find a value by its name
         *
         * @access public
         * @since unknown
         * @param type $name
         * @return type
         */
        function findValueByName($name)
        {
            $this->dao->select('s_value');
            $this->dao->from($this->getTableName());
            $this->dao->where('s_name', $name);
            $result = $this->dao->get();

            if( $result == false ) {
                return false;
            }

            if( $result->numRows() == 0 ) {
                return false;
            }

            $row = $result->row();
            return $row['s_value'];
        }

        /**
         * Find array preference for a given section
         *
         * @access public
         * @since unknown
         * @param string $name
         * @return array
         */
        public function findBySection($name)
        {
            $this->dao->select();
            $this->dao->from($this->getTableName());
            $this->dao->where('s_section', $name);
            $result = $this->dao->get();

            if( $result == false ) {
                return array();
            }

            if( $result->numRows() == 0 ) {
                return false;
            }

            return $result->result();
        }

        /**
         * Modify the structure of table.
         *
         * @access public
         * @since unknown
         */
        public function toArray()
        {
            $this->dao->select();
            $this->dao->from($this->getTableName());
            $result = $this->dao->get();

            if( $result == false ) {
                return false;
            }

            if( $result->numRows() == 0 ) {
                return false;
            }

            $aTmpPref = $result->result();
            foreach($aTmpPref as $tmpPref) {
                $this->pref[$tmpPref['s_section']][$tmpPref['s_name']] = $tmpPref['s_value'];
            }

            return true;
        }

        /**
         * Get value, given a preference name and a section name.
         *
         * @access public
         * @since unknown
         * @param string $key
         * @param string $section
         * @return string
         */
        public function get($key, $section = "osclass")
        {
            if ( !isset($this->pref[$section][$key]) ) {
                return '';
            }
            return $this->pref[$section][$key];
        }

        /**
         * Set preference value, given a preference name and a section name.
         *
         * @access public
         * @since unknown
         * @param string $key
         * @param string$value
         * @param string $section
         */
        public function set($key, $value, $section = "osclass")
        {
            $this->pref[$section][$key] = $value;
        }

        /**
         * Replace preference value, given preference name, preference section and type value.
         *
         * @access public
         * @since unknown
         * @param string $key
         * @param string $value
         * @param string $section
         * @param string $type
         * @return boolean
         */
        public function replace($key, $value, $section = 'osclass', $type = 'STRING')
        {
            static $aValidEnumTypes = array('STRING','INTEGER','BOOLEAN');
            $array_replace = array(
                's_name'    => $key,
                's_value'   => $value,
                's_section' => $section,
                'e_type'    => in_array($type, $aValidEnumTypes) ? $type : 'STRING'
            );
            return $this->dao->replace($this->getTableName(), $array_replace);
        }
    }

    /* file end: ./oc-includes/osclass/model/Preference.php */
?>
