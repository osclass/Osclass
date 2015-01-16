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
    class PluginCategory extends DAO
    {
        /**
         *
         * @var type
         */
        private static $instance;

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
            parent::__construct();
            $this->setTableName('t_plugin_category');
            /* $this->setPrimaryKey('pk_i_id'); */
            $this->setFields( array('s_plugin_name', 'fk_i_category_id') );
        }

        /**
         * Return all information given a category id
         *
         * @access public
         * @since unknown
         * @param type $categoryId
         * @return type
         */
        function findByCategoryId($categoryId)
        {
            $this->dao->select( $this->getFields() );
            $this->dao->from( $this->getTableName() );
            $this->dao->where('fk_i_category_id', $categoryId);

            $result = $this->dao->get();

            if( $result == false ) {
                return array();
            }

            return $result->result();
        }

        /**
         * Return list of categories asociated with a plugin
         *
         * @access public
         * @since unknown
         * @param string $plugin
         * @return array
         */
        function listSelected($plugin)
        {
            $this->dao->select( $this->getFields() );
            $this->dao->from( $this->getTableName() );
            $this->dao->where('s_plugin_name', $plugin);

            $result = $this->dao->get();

            if( $result == false ) {
                return array();
            }

            $list = array();
            foreach($result->result() as $sel) {
                $list[] = $sel['fk_i_category_id'];
            }

            return $list;
        }

        /**
         * Check if a category is asociated with a plugin
         *
         * @access public
         * @since unknown
         * @param string $pluginName
         * @param int $categoryId
         * @return bool
         */
        function isThisCategory($pluginName, $categoryId)
        {
            $this->dao->select('COUNT(*) AS numrows');
            $this->dao->from( $this->getTableName() );
            $this->dao->where('fk_i_category_id', $categoryId);
            $this->dao->where('s_plugin_name', $pluginName);

            $result = $this->dao->get();

            if( $result == false ) {
                return false;
            }

            if( $result->numRows() == 0 ) {
                return false;
            }

            $row = $result->row();

            if( $row['numrows'] == 0 ) {
                return false;
            }

            return true;
        }
    }

    /* file end: ./oc-includes/osclass/model/PluginCategory.php */
?>