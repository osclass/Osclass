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

     class UsersProcessingAjax 
     {
        private $users;
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
            array(  0=> 'dt_reg_date',
                    1=> 's_email',
                    2=> 's_name',
                    3=> 'dt_reg_date',
                    4=> 'dt_mod_date');

        private $tables_columns = 
            array(  0=> NULL,
                    1=> NULL,
                    2=> NULL,
                    3=> NULL,
                    4=> NULL);

        /* For Datatables */
        private $sOutput = null;
        private $sEcho = null;
        private $filters = array();

        private $_get;

        function __construct($params) {

            $this->_get = $params;
            $this->getDBParams();


            $list_users = User::newInstance()->search($this->start, $this->limit, $this->order_by['column_name'], $this->order_by['type']);
            $this->result = $list_users['users'];
            $this->filtered_total = $list_users['total_results'];
            $this->total = $list_users['rows'];

            $this->toDatatablesFormat();
            $this->dumpToDatatables();
        }

        function __destruct() {
            unset($this->_get);
        }

        private function getDBParams() {
            $this->order_by['column_name'] = 'pk_i_id';
            $this->order_by['type'] = 'DESC';
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

            $this->sOutput .= '"sExtraCols": [], ';

            $this->sOutput .= '"aaData": [ ';

            if(count($this->result)>0) {
                $count = 0;
                foreach ($this->result as $aRow)
                {
                    $this->sOutput .= "[";
                    $this->sOutput .= '"<div style=\'width:10px;\'><input type=\'checkbox\' name=\'id[]\' value=\''.$aRow['pk_i_id'].'\' /></div>",';
                    
                    $this->sOutput .= '"'.addslashes($aRow['s_email']).' <br/>';
                    $this->sOutput .= '<div id=\'datatable_wrapper\'><div id=\'datatables_quick_edit\' ';
                    if($count % 2) {
                        $this->sOutput .= ' class=\'even\' ';
                    }else{
                        $this->sOutput .= ' class=\'odd\' ';
                    }
                    $this->sOutput .= ' style=\'position:absolute;\'>';
                    if(isset($aRow['b_active']) && ($aRow['b_active'] == 1)) {
                        $this->sOutput .= '<a href=\''.osc_admin_base_url(true).'?page=users&action=deactivate&amp;id[]='. $aRow['pk_i_id'] .'\'>'. __('Deactivate') .'</a>';
                    } else if (isset($aRow['b_active']) && ($aRow['b_active'] == 0)) {
                        $this->sOutput .= '<a href=\''.osc_admin_base_url(true).'?page=users&action=activate&amp;id[]='. $aRow['pk_i_id'] .'\'>'. __('Activate') .'</a>';
                    }
                    if(isset($aRow['b_enabled']) && ($aRow['b_enabled'] == 1)) {
                        $this->sOutput .= ' | <a href=\''.osc_admin_base_url(true).'?page=users&action=disable&amp;id[]='. $aRow['pk_i_id'] .'\'>'. __('Disable') .'</a>';
                    } else if (isset($aRow['b_enabled']) && ($aRow['b_enabled'] == 0)) {
                        $this->sOutput .= ' | <a href=\''.osc_admin_base_url(true).'?page=users&action=enable&amp;id[]='. $aRow['pk_i_id'] .'\'>'. __('Enable') .'</a>';
                    }
                    if(osc_user_validation_enabled() && isset($aRow['b_active']) && $aRow['b_active']==0) {
                        $this->sOutput .= ' | <a href=\''.osc_admin_base_url(true).'?page=users&action=resend_activation&amp;id[]='. $aRow['pk_i_id'] .'\'>'. __('Re-send activation email') .'</a>';
                    }
                        
                    $this->sOutput .= ' | <a href=\''.osc_admin_base_url(true).'?page=users&action=edit&amp;id='. $aRow['pk_i_id'] .'\'>'. __('Edit') .'</a>';
                                            $var = 'onclick=\"javascript:return confirm(\''.__('This action can not be undone. Are you sure you want to continue?').'\')\"';
                    $this->sOutput .= ' | <a '.$var.' href=\''.osc_admin_base_url(true).'?page=users&action=delete&amp;id[]='. $aRow['pk_i_id'] .'\'>'. __('Delete') .'</a>';

                    $this->sOutput .= '</div></div>",';
                    
                    $this->sOutput .= '"'.addslashes(osc_esc_html($aRow['s_name'])).'",';
                    $this->sOutput .= '"'.addslashes(osc_esc_html($aRow['dt_reg_date'])).'",';
                    $this->sOutput .= '"'.addslashes(osc_esc_html($aRow['dt_mod_date'])).'"';

                    if($this->extraCols > 0) $this->sOutput .= ',';

                    if($this->extraCols > 0) $this->sOutput = substr($this->sOutput,0,-1);


                    if(end($this->result) == $aRow) {
                        $this->sOutput .= "]";

                    } else {
                        $this->sOutput .= "],";
                    }
                    $count++;
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