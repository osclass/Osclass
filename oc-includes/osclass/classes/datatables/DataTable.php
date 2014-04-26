<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

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
     * DataTable class
     *
     * @since 3.1
     * @package Osclass
     * @subpackage classes
     * @author Osclass
     */
    abstract class DataTable
    {
        protected $aColumns;
        protected $aRows;
        protected $rawRows;

        protected $limit;
        protected $start;
        protected $iPage;
        protected $total;
        protected $totalFiltered;

        public function __construct()
        {
            $this->aColumns = array();
            $this->aRows = array();
            $this->rawRows = array();
        }


        /**
         * FUNCTIONS THAT SHOULD BE REDECLARED IN SUB-CLASSES
         */
        public function setResults($results = null) {
            if(is_array($results)) {
                $this->start = 0;
                $this->limit = count($results);
                $this->total = count($results);
                $this->totalFiltered = count($results);

                if(count($results)>0) {
                    foreach($results as $r) {
                        $row = array();
                        if(is_array($r)) {
                            foreach($r as $k => $v) {
                                $row[$k] = $v;
                            }
                        }
                        $this->addRow($row);
                    }
                    if(is_array($results[0])) {
                        foreach($results[0] as $k => $v) {
                            $this->addColumn($k, $k);
                        }
                    }
                }
            }
        }




        /**
         * COMMON FUNCTIONS . DO NOT MODIFY THEM
         */


        /**
         * Add a colum
         * @param type $id
         * @param type $text
         * @param type $priority
         */
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

        protected function addRow($aRow)
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
            if(count($this->aRows)===0) {
                return $rows;
            }
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

        public function getData()
        {
            return array(
                    'aColumns'              => $this->sortedColumns()
                    ,'aRows'                => $this->sortedRows()
                    ,'iDisplayLength'       => $this->limit
                    ,'iTotalDisplayRecords' => $this->total
                    ,'iTotalRecords'        => $this->totalFiltered
                    ,'iPage'                => $this->iPage
            );
        }

        public function rawRows()
        {
            return $this->rawRows;
        }



    }

?>