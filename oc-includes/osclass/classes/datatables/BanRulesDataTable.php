<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

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
     * BanRulesDataTable class
     * 
     * @since 3.1
     * @package Osclass
     * @subpackage classes
     * @author Osclass
     */
    class BanRulesDataTable extends DataTable
    {

        private $order_by;
        
        
        public function table($params)
        {
            
            $this->addTableHeader();
            $this->getDBParams($params);

            $list_rules  = BanRule::newInstance()->search($this->start, $this->limit, $this->order_by['column_name'], $this->order_by['type'] );
            
            $this->processData($list_rules['rules']);
            $this->totalFiltered = $list_rules['rows'];
            $this->total = $list_rules['total_results'];
            
            return $this->getData();
        }

        private function addTableHeader()
        {

            $this->addColumn('bulkactions', '<input id="check_all" type="checkbox" />');
            $this->addColumn('name', __('Ban name / Reason'));
            $this->addColumn('ip', __('IP rule'));
            $this->addColumn('email', __('E-mail rule'));

            $dummy = &$this;
            osc_run_hook("admin_rules_table", $dummy);
        }
        
        private function processData($rules)
        {
            if(!empty($rules)) {

                $csrf_token_url = osc_csrf_token_url();
                foreach($rules as $aRow) {
                    $row = array();
                    $options        = array();
                    $options_more   = array();
                    // first column

                    $options[]  = '<a href="' . osc_admin_base_url(true) . '?page=users&action=edit_ban_rule&amp;id=' . $aRow['pk_i_id'] . '">' . __('Edit') . '</a>';
                    $options[]  = '<a onclick="return delete_dialog(\'' . $aRow['pk_i_id'] . '\');" href="' . osc_admin_base_url(true) . '?page=users&action=delete_ban_rule&amp;id[]=' . $aRow['pk_i_id'] . '">' . __('Delete') . '</a>';

                    $options_more = osc_apply_filter('more_actions_manage_rules', $options_more, $aRow);
                    // more actions
                    $moreOptions = '<li class="show-more">'.PHP_EOL.'<a href="#" class="show-more-trigger">'. __('Show more') .'...</a>'. PHP_EOL .'<ul>'. PHP_EOL;
                    foreach( $options_more as $actual ) { 
                        $moreOptions .= '<li>'.$actual."</li>".PHP_EOL;
                    }
                    $moreOptions .= '</ul>'. PHP_EOL .'</li>'.PHP_EOL;

                    $options = osc_apply_filter('actions_manage_rules', $options, $aRow);
                    // create list of actions
                    $auxOptions = '<ul>'.PHP_EOL;
                    foreach( $options as $actual ) {
                        $auxOptions .= '<li>'.$actual.'</li>'.PHP_EOL;
                    }
                    $auxOptions  .= $moreOptions;
                    $auxOptions  .= '</ul>'.PHP_EOL;

                    $actions = '<div class="actions">'.$auxOptions.'</div>'.PHP_EOL;

                    $row['bulkactions'] = '<input type="checkbox" name="id[]" value="' . $aRow['pk_i_id'] . '" /></div>';
                    $row['name'] = $aRow['s_name'] . $actions;
                    $row['ip'] = $aRow['s_ip'];
                    $row['email'] = $aRow['s_email'];

                    $row = osc_apply_filter('rules_processing_row', $row, $aRow);

                    $this->addRow($row);
                    $this->rawRows[] = $aRow;
                }

            }
        }
                
        private function getDBParams($_get)
        {
            
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
            
            $this->order_by['column_name'] = 'pk_i_id';
            $this->order_by['type'] = 'DESC';
            foreach($_get as $k=>$v) {
                if( $k == 'user') {
                    $this->search = $v;
                }
                if( $k == 'userId' && $v != '') {
                    $this->withUserId = true;
                    $this->userId = $v;
                }

                /* for sorting */
                if( $k == 'iSortCol_0' ) {
                    $this->order_by['column_name'] = $this->column_names[$v];
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