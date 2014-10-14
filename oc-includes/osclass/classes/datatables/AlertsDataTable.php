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
     * AlertsDataTable class
     * 
     * @since 3.1
     * @package Osclass
     * @subpackage classes
     * @author Osclass
     */
    class AlertsDataTable extends DataTable
    {
        
        private $search;
        private $order_by;
        
        public function table($params)
        {
            
            $this->addTableHeader();
            $this->getDBParams($params);

            $alerts = Alerts::newInstance()->search($this->start, $this->limit, $this->order_by['column_name'], $this->order_by['type'], $this->search);
            $this->processData($alerts);
            $this->total    = $alerts['total_results'];
            $this->total_filtered = $alerts['rows'];
                        
            return $this->getData();
        }

        private function addTableHeader()
        {

            $this->addColumn('bulkactions', '<input id="check_all" type="checkbox" />');
            $this->addColumn('email', __('E-mail'));
            $this->addColumn('alert', __('Alert'));
            $this->addColumn('date', __('Date'));

            $dummy = &$this;
            osc_run_hook("admin_alerts_table", $dummy);
        }
        
        private function processData($alerts)
        {
            if(!empty($alerts) && !empty($alerts['alerts'])) {

                $csrf_token_url = osc_csrf_token_url();
                foreach($alerts['alerts'] as $aRow) {
                    $row = array();
                    $options        = array();
                    // first column
                    $row['bulkactions'] = '<input type="checkbox" name="alert_id[]" value="' . $aRow['pk_i_id'] . '" /></div>';

                    $options[]  = '<a onclick="return delete_alert(\'' . $aRow['pk_i_id'] . '\');" href="#">' . __('Delete') . '</a>';


                    if( $aRow['b_active'] == 1 ) {
                        $options[] = '<a href="' . osc_admin_base_url(true) . '?page=users&action=status_alerts&amp;alert_id[]=' . $aRow['pk_i_id'] . '&amp;' . $csrf_token_url . '&amp;status=0" >' . __('Deactivate') . '</a>';
                    } else {
                        $options[] = '<a href="' . osc_admin_base_url(true) . '?page=users&action=status_alerts&amp;alert_id[]=' . $aRow['pk_i_id'] . '&amp;' . $csrf_token_url . '&amp;status=1" >' . __('Activate') . '</a>';
                    }


                    $options = osc_apply_filter('actions_manage_alerts', $options, $aRow);
                    // create list of actions
                    $auxOptions = '<ul>'.PHP_EOL;
                    foreach( $options as $actual ) {
                        $auxOptions .= '<li>'.$actual.'</li>'.PHP_EOL;
                    }
                    $auxOptions  .= '</ul>'.PHP_EOL;

                    $actions = '<div class="actions">'.$auxOptions.'</div>'.PHP_EOL;
                    // second column
                    $row['email'] = '<a href="' . osc_admin_base_url(true) . '?page=items&userId=">' . $aRow['s_email'] . '</a>'. $actions;

                    // third row

                    $pieces = array();
                    $conditions = osc_get_raw_search((array)json_decode($aRow['s_search'], true));
                    if(isset($conditions['sPattern']) && $conditions['sPattern']!='') {
                        $pieces[] = sprintf(__("<b>Pattern:</b> %s"), $conditions['sPattern']);
                    }
                    if(isset($conditions['aCategories']) && !empty($conditions['aCategories'])) {
                        $l = min(count($conditions['aCategories']), 4);
                        $cat_array = array();
                        for($c=0;$c<$l;$c++) {
                            $cat_array[] = $conditions['aCategories'][$c];
                        }
                        if(count($conditions['aCategories'])>$l) {
                            $cat_array[] = '<a href="#" class="more-tooltip" categories="'.osc_esc_html(implode(", ", $conditions['aCategories'])).'" >'.__("...More").'</a>';
                        }

                        $pieces[] = sprintf(__("<b>Categories:</b> %s"), implode(", ", $cat_array));
                    }

                    $row['alert'] = implode($pieces, ", ");
                    // fourth row
                    $row['date'] = osc_format_date($aRow['dt_date']);

                    $row = osc_apply_filter('alerts_processing_row', $row, $aRow);

                    $this->addRow($row);
                    $this->rawRows[] = $aRow;
                }

            }
        }
                
        private function getDBParams($_get)
        {
            
            
            $column_names  = array(
                0 => 'dt_date',
                1 => 's_email',
                2 => 's_search',
                3 => 'dt_date'
            );
            
            $this->order_by['column_name'] = 'c.dt_pub_date';
            $this->order_by['type'] = 'desc';

            if( !isset($_get['iDisplayStart']) ) {
                $_get['iDisplayStart'] = 0;
            }
            $p_iPage      = 1;
            if( !is_numeric(Params::getParam('iPage')) || Params::getParam('iPage') < 1 ) {
                Params::setParam('iPage', $p_iPage );
                $this->iPage = $p_iPage;
            } else {
                $this->iPage = Params::getParam('iPage');
            }
            
            $this->order_by['column_name'] = 'dt_date';
            $this->order_by['type'] = 'DESC';
            foreach($_get as $k=>$v) {
                if( $k == 'sSearch' ) {
                    $this->search = $v;
                }

                /* for sorting */
                if( $k == 'iSortCol_0' ) {
                    $this->order_by['column_name'] = $column_names[$v];
                }
                if( $k == 'sSortDir_0' ) {
                    $this->order_by['type'] = $v;
                }
            }
            // set start and limit using iPage param
            $start = ($this->iPage - 1) * $_get['iDisplayLength'];
            
            $this->start = intval( $start );
            $this->limit = intval( $_get['iDisplayLength'] );

        }
        
    }

?>