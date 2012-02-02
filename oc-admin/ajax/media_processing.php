<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

    /**
     * OSClass – software for creating and publishing online classified advertising platforms
     *
     * Copyright (C) 2010 OSCLASS
     *
     * This program is free software: you can redistribute it and/or modify it under the terms
     * of the GNU Affero General Public License as published by the Free Software Foundation,
     * either version 3 of the License, or (at your option) any later version.
     *
     * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
     * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
     * See the GNU Affero General Public License for more details.
     *
     * You should have received a copy of the GNU Affero General Public
     * License along with this program. If not, see <http://www.gnu.org/licenses/>.
     */
     
     class MediaProcessingAjax
     {
        private $items;
        private $result;
        private $toJSON;
        private $toDatatables;

        private $limit;
        private $start;
        private $total;
        private $search;
        private $order_by = array();
        private $stat;
        private $extraCols = 0;
        private $sExtraCol = array();

        private $column_names  = 
            array(  0=> 'r.pk_i_id',
                    1=> 'r.pk_i_id',
                    2=> 'r.pk_i_id',
                    3=> 'r.fk_i_item_id',
                    4=> 'c.dt_pub_date');


        /* For Datatables */
        private $sOutput = null;
        private $sEcho = null;
        private $filters = array();

        private $_get;

        function __construct($params) {

            $this->_get = $params;
            $this->getDBParams();
            
            $this->result = ItemResource::newInstance()->getResources(Params::getParam('resourceId'), $this->_get['iDisplayStart'], $this->_get['iDisplayLength'], isset($this->order_by['column_name'])?$this->order_by['column_name']:'pk_i_id', isset($this->order_by['type'])?$this->order_by['type']:'desc');
            
            $this->filtered_total = ItemResource::newInstance()->countResources(Params::getParam('resourceId'));
            $this->total = ItemResource::newInstance()->countResources('');

            $this->toDatatablesFormat();
            $this->dumpToDatatables();
        }

        function __destruct() {
            unset($this->_get);
        }

        private function getDBParams() {
            
            foreach($this->_get as $k=>$v) {
                if($k == 'iDisplayStart') $this->start = intval($v);
                if($k == 'iDisplayLength') $this->limit = intval($v);
                if($k == 'sEcho') $this->sEcho = intval($v);

                /* for sorting */
                if($k == 'iSortCol_0') {
                    $this->order_by['column_name'] = $this->column_names[$v];
                }
                if($k == 'sSortDir_0') $this->order_by['type'] = $v;
            }
        }

        /* START - format functions */
        private function toDatatablesFormat() {
            $this->sOutput = '{';
            $this->sOutput .= '"iTotalRecords": '.($this->total).', ';
            $this->sOutput .= '"iTotalDisplayRecords": '.($this->filtered_total).', ';
            $this->sOutput .= '"iExtraCols": '.($this->extraCols).', ';

            $this->sOutput .= '"sExtraCols": [';
            $this->sOutput .= '], ';

            $this->sOutput .= '"aaData": [ ';

            if(count($this->result)>0) {
                $count = 0;
                foreach ($this->result as $r) {
                    $this->sOutput .= "[";
                    $this->sOutput .= "\"<input type='checkbox' name='id[]' value='".$r['pk_i_id']."' />\",";
                    $this->sOutput .= "\"<div id='media_list_pic'><img src='".osc_apply_filter('resource_path', osc_base_url().$r['s_path']).$r['pk_i_id']."_thumbnail.".$r['s_extension']."' style='max-width: 60px; max-height: 60px;' /></div> <div id='media_list_filename'>".$r['s_content_type']."\",";
                    $this->sOutput .= "\"<a onclick='javascript:return confirm(\'".__('This action can not be undone. Are you sure you want to continue?')."\')\' href='".osc_admin_base_url(true)."?page=media&action=delete&amp;id[]=".$r['pk_i_id']."' id='dt_link_delete'>".__('Delete')."</a>\",";
                    $this->sOutput .= "\"<a target='_blank' href='".osc_item_url_ns($r['fk_i_item_id'])."'>item #".$r['fk_i_item_id']."</a>\",";
					$this->sOutput .= "\"".$r['dt_pub_date']."\"";
                    if(end($this->result) == $r) {
                        $this->sOutput .= "]";

                    } else {
                        $this->sOutput .= "],";
                    }
                }
            }
            $this->sOutput .= ']}';

        }

        private function toJSON($result) {
            $this->toJSON = json_encode($result);
        }
        /* END - format functions */

        /* START - dump results */
        private function dumpResult() {
            $this->toJSON($this->result);
            echo $this->toJSON();
        }

        private function dumpToDatatables() {
            echo str_replace("\'", "'", $this->sOutput);
        }
        /* END - dump results */
     }
     
?>