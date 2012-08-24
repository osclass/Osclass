<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

    /*
     *      OSCLass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2010 OSCLASS
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
     * TableView class
     * 
     * @since 3.0
     * @package OSClass
     * @subpackage classes
     * @author OSClass
     */
    class TableView
    {
        private static $instance ;
        private $aColumns;
        private $aRows;

        public function __construct()
        {
            $this->aColumns = array() ;
            $this->aRows = array() ;
        }

        public static function newInstance()
        {
            if(!self::$instance instanceof self) {
                self::$instance = new self ;
            }
            return self::$instance ;
        }
                
        public function addColumn($id, $text, $priority = 5)
        {
            $this->removeColumn($id);
            $this->aColumns[$priority][$id] = $text;
        }
        
        public function removeColumn($id)
        {
            for($priority=1;$priority<=10;$priority++) {
                unset($this->aColumns[$priority][$id]);
            }
        }
        
        public function addRow($aRow)
        {
            $this->aRows[] = $aRow;
        }
        
        public function sortedColumns()
        {
            $columns_ordered = array();
            for($priority=1;$priority<=10;$priority++) {
                if(isset($this->aColumns[$priority]) && is_array($this->aColumns[$priority])) {
                    foreach($this->aColumns[$priority] as $k => $v) {
                        $columns_ordered[$k] = $v;
                    }
                }
            }
            return $columns_ordered;
        }
        
        public function sortedRows()
        {
            $rows = array();
            $columns = $this->sortedColumns();
            foreach($this->aRows as $row) {
                $aux_row = array();
                foreach($columns as $k => $v) {
                    if(isset($row[$k])) {
                        $aux_row[$k] = $row[$k];
                    } else {
                        $aux_row[$k] = '';
                    }
                }
                $rows[] = $aux_row;
            }
            return $rows;
        }

    }

?>