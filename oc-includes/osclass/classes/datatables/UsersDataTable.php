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
     * UsersDataTable class
     * 
     * @since 3.1
     * @package Osclass
     * @subpackage classes
     * @author Osclass
     */
    class UsersDataTable extends DataTable
    {

        private $withUserId;
        private $search;
        private $order_by;
        
        
        public function table($params)
        {
            
            $this->withUserId = false;
            $this->search = '';
            $this->addTableHeader();
            $this->getDBParams($params);

            if($this->withUserId) {
                $list_users  = User::newInstance()->searchByPrimaryKey($this->start, $this->limit, $this->userId, $this->order_by['column_name'], $this->order_by['type'] );
            } else if($this->search != ''){
                $list_users  = User::newInstance()->searchByEmail($this->start, $this->limit, $this->search, $this->order_by['column_name'], $this->order_by['type'] );
            } else {
                $list_users  = User::newInstance()->search($this->start, $this->limit, $this->order_by['column_name'], $this->order_by['type'] );
            }
            
            $this->processData($list_users['users']);
            $this->totalFiltered = $list_users['rows'];
            $this->total = $list_users['total_results'];
            
            return $this->getData();
        }

        private function addTableHeader()
        {

            $arg_date = '&sort=date';
            if(Params::getParam('sort') == 'date') {
                if(Params::getParam('direction') == 'desc') {
                    $arg_date .= '&direction=asc';
                };
            }

            
            $this->addColumn('bulkactions', '<input id="check_all" type="checkbox" />');
            $this->addColumn('email', __('E-mail'));
            $this->addColumn('username', __('Username'));
            $this->addColumn('name', __('Name'));
            $this->addColumn('date', __('Date'));
            $this->addColumn('update_date', __('Update date'));

            $dummy = &$this;
            osc_run_hook("admin_users_table", $dummy);
        }
        
        private function processData($users)
        {
            if(!empty($users)) {

                $csrf_token_url = osc_csrf_token_url();
                foreach($users as $aRow) {
                    $row = array();
                    $options        = array();
                    $options_more   = array();
                    // first column

                    $options[]  = '<a href="' . osc_admin_base_url(true) . '?page=users&action=edit&amp;id=' . $aRow['pk_i_id'] . '">' . __('Edit') . '</a>';
                    $options[]  = '<a onclick="return delete_dialog(\'' . $aRow['pk_i_id'] . '\');" href="' . osc_admin_base_url(true) . '?page=users&action=delete&amp;id[]=' . $aRow['pk_i_id'] . '">' . __('Delete') . '</a>';

                    if( $aRow['b_active'] == 1 ) {
                        $options_more[] = '<a href="' . osc_admin_base_url(true) . '?page=users&action=deactivate&amp;id[]=' . $aRow['pk_i_id'] . '&amp;' . $csrf_token_url . '">' . __('Deactivate') . '</a>';
                    } else {
                        $options_more[] = '<a href="' . osc_admin_base_url(true) . '?page=users&action=activate&amp;id[]=' . $aRow['pk_i_id'] . '&amp;' . $csrf_token_url .'">' . __('Activate') . '</a>';
                    }
                    if( $aRow['b_enabled'] == 1 ) {
                        $options_more[] = '<a href="' . osc_admin_base_url(true) . '?page=users&action=disable&amp;id[]=' . $aRow['pk_i_id'] . '&amp;' . $csrf_token_url . '">' . __('Block') . '</a>';
                    } else {
                        $options_more[] = '<a href="' . osc_admin_base_url(true) . '?page=users&action=enable&amp;id[]=' . $aRow['pk_i_id'] . '&amp;' . $csrf_token_url . '">' . __('Unblock') . '</a>';
                    }
                    if( osc_user_validation_enabled() && ( $aRow['b_active'] == 0 ) ) {
                        $options_more[] = '<a href="' . osc_admin_base_url(true) . '?page=users&action=resend_activation&amp;id[]=' . $aRow['pk_i_id'] . '&amp;' . $csrf_token_url . '">' . __('Re-send activation email') . '</a>';
                    }

                    $options_more = osc_apply_filter('more_actions_manage_users', $options_more, $aRow);
                    // more actions
                    $moreOptions = '<li class="show-more">'.PHP_EOL.'<a href="#" class="show-more-trigger">'. __('Show more') .'...</a>'. PHP_EOL .'<ul>'. PHP_EOL;
                    foreach( $options_more as $actual ) { 
                        $moreOptions .= '<li>'.$actual."</li>".PHP_EOL;
                    }
                    $moreOptions .= '</ul>'. PHP_EOL .'</li>'.PHP_EOL;

                    $options = osc_apply_filter('actions_manage_users', $options, $aRow);
                    // create list of actions
                    $auxOptions = '<ul>'.PHP_EOL;
                    foreach( $options as $actual ) {
                        $auxOptions .= '<li>'.$actual.'</li>'.PHP_EOL;
                    }
                    $auxOptions  .= $moreOptions;
                    $auxOptions  .= '</ul>'.PHP_EOL;

                    $actions = '<div class="actions">'.$auxOptions.'</div>'.PHP_EOL;

                    $row['bulkactions'] = '<input type="checkbox" name="id[]" value="' . $aRow['pk_i_id'] . '" /></div>';
                    $row['email'] = '<a href="' . osc_admin_base_url(true) . '?page=items&userId='. $aRow['pk_i_id'] .'&user='. $aRow['s_name'] .'">' . $aRow['s_email'] . '</a>'. $actions;
                    $row['username'] = $aRow['s_username'];
                    $row['name'] = $aRow['s_name'];
                    $row['date'] = $aRow['dt_reg_date'];
                    $row['update_date'] = $aRow['dt_mod_date'];

                    $row = osc_apply_filter('users_processing_row', $row, $aRow);

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