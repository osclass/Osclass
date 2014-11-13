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
     * ItemsDataTable class
     *
     * @since 3.1
     * @package Osclass
     * @subpackage classes
     * @author Osclass
     */
    class ItemsDataTable extends DataTable
    {

        private $mSearch;
        private $withFilters = false;

        public function __construct()
        {
            osc_add_filter('datatable_listing_class', array(&$this, 'row_class'));
        }

        public function table($params)
        {
            $this->addTableHeader();
            $this->mSearch = new Search(true);
            $this->getDBParams($params);
            // add more conditions here
            osc_run_hook('manage_item_search_conditions', $this->mSearch);
            // do Search
            $this->processData(Item::newInstance()->extendCategoryName($this->mSearch->doSearch(true)));
            $this->totalFiltered = $this->mSearch->countAll();
            $this->total = $this->mSearch->count();

            return $this->getData();
        }

        public function tableReported($params)
        {
            $this->addTableHeaderReported();
            $this->mSearch = new Search(true);
            $this->getDBParams($params);
            // only some fields can be ordered
            $direction  = Params::getParam('direction');
            $arrayDirection = array('desc', 'asc');
            if( !in_array($direction, $arrayDirection) ) {
                Params::setParam('direction', 'desc');
                $direction = 'desc';
            }

            $sort = Params::getParam('sort');
            $arraySortColumns = array(
                'spam'  => 'i_num_spam',
                'bad'   => 'i_num_bad_classified',
                'rep'   => 'i_num_repeated',
                'off'   => 'i_num_offensive',
                'exp'   => 'i_num_expired',
                'date'  => 'dt_pub_date',
                'expiration'  => 'dt_expiration'
                );
            // column sort
            if( !key_exists($sort, $arraySortColumns) ) {
                $sort       = 'dt_pub_date';
                $this->mSearch->addHaving('i_num_spam > 0 OR i_num_bad_classified > 0 OR i_num_repeated > 0 OR i_num_offensive > 0 OR i_num_expired > 0');
            } else {
                $sort = $arraySortColumns[$sort];
                if($sort!='dt_pub_date') {
                    $this->mSearch->addHaving($sort.' > 0');
                } else {
                    $this->mSearch->addHaving('i_num_spam > 0 OR i_num_bad_classified > 0 OR i_num_repeated > 0 OR i_num_offensive > 0 OR i_num_expired > 0');
                }
            }

            $this->mSearch->order( $sort, $direction );

            $this->mSearch->addTable(sprintf("%st_item_stats s", DB_TABLE_PREFIX));
            $this->mSearch->addField('SUM(s.`i_num_spam`) as i_num_spam');
            $this->mSearch->addField('SUM(s.`i_num_bad_classified`) as i_num_bad_classified');
            $this->mSearch->addField('SUM(s.`i_num_repeated`) as i_num_repeated');
            $this->mSearch->addField('SUM(s.`i_num_offensive`) as i_num_offensive');
            $this->mSearch->addField('SUM(s.`i_num_expired`) as i_num_expired');

            // having


            $this->mSearch->addConditions(sprintf(" %st_item.pk_i_id ", DB_TABLE_PREFIX));
            $this->mSearch->addConditions(sprintf(" %st_item.pk_i_id = s.fk_i_item_id", DB_TABLE_PREFIX));
            $this->mSearch->addGroupBy(sprintf(" %st_item.pk_i_id ", DB_TABLE_PREFIX));
            // do Search
            $this->processDataReported(Item::newInstance()->extendCategoryName($this->mSearch->doSearch(true)));
            $this->totalFiltered = $this->mSearch->countAll();
            $this->total = $this->mSearch->count();

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
            $arg_expiration = '&sort=expiration';
            if(Params::getParam('sort') == 'expiration') {
                if(Params::getParam('direction') == 'desc') {
                    $arg_expiration .= '&direction=asc';
                };
            }

            Rewrite::newInstance()->init();
            $page  = (int)Params::getParam('iPage');
            if($page==0) { $page = 1; };
            Params::setParam('iPage', $page);
            $url_base = preg_replace('|&direction=([^&]*)|', '', preg_replace('|&sort=([^&]*)|', '', osc_base_url().Rewrite::newInstance()->get_raw_request_uri()));

            $this->addColumn('status-border', '');
            $this->addColumn('status', __('Status'));
            $this->addColumn('bulkactions', '<input id="check_all" type="checkbox" />');
            $this->addColumn('title', __('Title'));
            $this->addColumn('user', __('User'));
            $this->addColumn('category', __('Category'));
            $this->addColumn('location', __('Location'));
            $this->addColumn('date', '<a href="'.osc_esc_html($url_base.$arg_date).'">'.__('Date').'</a>');
            $this->addColumn('expiration', '<a href="'.osc_esc_html($url_base.$arg_expiration).'">'.__('Expiration date').'</a>');

            $dummy = &$this;
            osc_run_hook("admin_items_table", $dummy);
        }

        private function addTableHeaderReported()
        {

            Rewrite::newInstance()->init();
            $page  = (int)Params::getParam('iPage');
            if($page==0) { $page = 1; };
            Params::setParam('iPage', $page);
            $url_base = preg_replace('|&direction=([^&]*)|', '', preg_replace('|&sort=([^&]*)|', '', osc_base_url().Rewrite::newInstance()->get_raw_request_uri()));
            $arg_spam   = '&sort=spam'; $arg_bad    = '&sort=bad';
            $arg_rep    = '&sort=rep';  $arg_off    = '&sort=off';
            $arg_exp    = '&sort=exp';  $arg_date   = '&sort=date';
            $arg_expiration = '&sort=expiration';
            $sort       = Params::getParam("sort");
            $direction  = Params::getParam("direction");

            switch ($sort) {
                case('spam'):
                    if($direction == 'desc' || $direction == '') $arg_spam .= '&direction=asc';
                    break;
                case('bad'):
                    if($direction == 'desc' || $direction == '') $arg_bad .= '&direction=asc';
                    break;
                case('rep'):
                    if($direction == 'desc' || $direction == '') $arg_rep .= '&direction=asc';
                    break;
                case('off'):
                    if($direction == 'desc' || $direction == '') $arg_off .= '&direction=asc';
                    break;
                case('exp'):
                    if($direction == 'desc' || $direction == '') $arg_exp .= '&direction=asc';
                    break;
                case('date'):
                    if($direction == 'desc' || $direction == '') $arg_date .= '&direction=asc';
                    break;
                case('expiration'):
                    if($direction == 'desc' || $direction == '') $arg_expiration .= '&direction=asc';
                    break;
                default:
                    break;
            }

            $url_spam = $url_base.$arg_spam;
            $url_bad = $url_base.$arg_bad;
            $url_rep = $url_base.$arg_rep;
            $url_off = $url_base.$arg_off;
            $url_exp = $url_base.$arg_exp;
            $url_date = $url_base.$arg_date;
            $url_expiration = $url_base.$arg_expiration;

            $this->addColumn('bulkactions', '<input id="check_all" type="checkbox" />');
            $this->addColumn('title', __('Title'));
            $this->addColumn('user', __('User'));
            $this->addColumn('spam', '<a id="order_spam" href="'.osc_esc_html($url_spam).'">'.__('Spam').'</a>');
            $this->addColumn('bad', '<a id="order_bad" href="'.osc_esc_html($url_bad).'">'.__('Misclassified').'</a>');
            $this->addColumn('rep', '<a id="order_rep" href="'.osc_esc_html($url_rep).'">'.__('Duplicated').'</a>');
            $this->addColumn('exp', '<a id="order_exp" href="'.osc_esc_html($url_exp).'">'.__('Expired').'</a>');
            $this->addColumn('off', '<a id="order_off" href="'.osc_esc_html($url_off).'">'.__('Offensive').'</a>');
            $this->addColumn('date', '<a id="order_date" href="'.osc_esc_html($url_date).'">'.__('Date').'</a>');
            $this->addColumn('expiration', '<a id="order_expiration" href="'.osc_esc_html($url_expiration).'">'.__('Expiration date').'</a>');

            $dummy = &$this;
            osc_run_hook("admin_items_reported_table", $dummy);
        }

        private function processData($items)
        {
            if(!empty($items)) {

                $csrf_token_url = osc_csrf_token_url();
                foreach($items as $aRow) {
                    View::newInstance()->_exportVariableToView('item', $aRow);
                    $row     = array();
                    $options = array();
                    // -- prepare data --
                    // prepare item title
                    $title = mb_substr($aRow['s_title'], 0, 30, 'UTF-8');
                    if($title != $aRow['s_title']) {
                        $title .= '...';
                    }

                    //icon open add new window
                    $title .= '<span class="icon-new-window"></span>';

                    // Options of each row
                    $options_more = array();
                    if($aRow['b_active']) {
                        $options_more[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=status&amp;id=' . $aRow['pk_i_id'] . '&amp;' . $csrf_token_url . '&amp;value=INACTIVE">' . __('Deactivate') .'</a>';
                    } else {
                        $options_more[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=status&amp;id=' . $aRow['pk_i_id'] . '&amp;' . $csrf_token_url . '&amp;value=ACTIVE">' . __('Activate') .'</a>';
                    }
                    if($aRow['b_enabled']) {
                        $options_more[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=status&amp;id=' . $aRow['pk_i_id'] . '&amp;' . $csrf_token_url . '&amp;value=DISABLE">' . __('Block') .'</a>';
                    } else {
                        $options_more[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=status&amp;id=' . $aRow['pk_i_id'] . '&amp;' . $csrf_token_url . '&amp;value=ENABLE">' . __('Unblock') .'</a>';
                    }
                    if($aRow['b_premium']) {
                        $options_more[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=status_premium&amp;id=' . $aRow['pk_i_id'] . '&amp;' . $csrf_token_url . '&amp;value=0">' . __('Unmark as premium') .'</a>';
                    } else {
                        $options_more[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=status_premium&amp;id=' . $aRow['pk_i_id'] . '&amp;' . $csrf_token_url . '&amp;value=1">' . __('Mark as premium') .'</a>';
                    }
                    if($aRow['b_spam']) {
                        $options_more[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=status_spam&amp;id=' . $aRow['pk_i_id'] . '&amp;' . $csrf_token_url . '&amp;value=0">' . __('Unmark as spam') .'</a>';
                    } else {
                        $options_more[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=status_spam&amp;id=' . $aRow['pk_i_id'] . '&amp;' . $csrf_token_url . '&amp;value=1">' . __('Mark as spam') .'</a>';
                    }

                    // general options
                    $options[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=item_edit&amp;id=' . $aRow['pk_i_id'] . '">' . __('Edit') . '</a>';
                    $options[] = '<a onclick="return delete_dialog(\'' . $aRow['pk_i_id'] . '\');" href="' . osc_admin_base_url(true) . '?page=items&amp;action=delete&amp;id[]=' . $aRow['pk_i_id'] . '">' . __('Delete') . '</a>';

                    // only show if there are data
                    if(ItemComment::newInstance()->totalComments( $aRow['pk_i_id'] ) > 0) {
                        $options[] = '<a href="' . osc_admin_base_url(true) . '?page=comments&amp;action=list&amp;id=' . $aRow['pk_i_id'] . '">' . __('View comments') . '</a>';
                    }
                    if(ItemResource::newInstance()->countResources( $aRow['pk_i_id'] ) > 0) {
                        $options[] = '<a href="' . osc_admin_base_url(true) . '?page=media&amp;action=list&amp;resourceId=' . $aRow['pk_i_id'] . '">' . __('View media') . '</a>';
                    }

                    $options_more = osc_apply_filter('more_actions_manage_items', $options_more, $aRow);
                    // more actions
                    $moreOptions = '<li class="show-more">'.PHP_EOL.'<a href="#" class="show-more-trigger">'. __('Show more') .'...</a>'. PHP_EOL .'<ul>'. PHP_EOL;
                    foreach( $options_more as $actual) {
                        $moreOptions .= '<li>'.$actual."</li>".PHP_EOL;
                    }
                    $moreOptions .= '</ul>'. PHP_EOL .'</li>'.PHP_EOL;

                    $options = osc_apply_filter('actions_manage_items', $options, $aRow);
                    // create list of actions
                    $auxOptions = '<ul>'.PHP_EOL;
                    foreach( $options as $actual) {
                        $auxOptions .= '<li>'.$actual.'</li>'.PHP_EOL;
                    }
                    if(!empty($options_more)) {
                        $auxOptions  .= $moreOptions;
                    }
                    $auxOptions  .= '</ul>'.PHP_EOL;

                    $actions = '<div class="actions">'.$auxOptions.'</div>'.PHP_EOL;

                    // fill a row
                    $row['bulkactions'] = '<input type="checkbox" name="id[]" value="' . $aRow['pk_i_id'] . '" active="' . $aRow['b_active'] . '" blocked="' . $aRow['b_enabled'] . '"/>';
                    $status = $this->get_row_status();
                    $row['status-border'] = '';
                    $row['status'] = $status['text'];
                    $row['title'] = '<a href="' . osc_item_url() . '" target="_blank">' . $title. '</a>'. $actions;
                    if($aRow['fk_i_user_id']!=null) {
                        $row['user'] = '<a href="' . osc_admin_base_url(true) . '?page=users&action=edit&id=' . $aRow['fk_i_user_id'] . '" target="_blank">' . $aRow['s_user_name'] . '</a>';
                    } else {
                        $row['user'] = $aRow['s_user_name'];
                    }
                    $row['category'] = $aRow['s_category_name'];
                    $row['location'] = $this->get_row_location();
                    $row['date'] = osc_format_date($aRow['dt_pub_date'], osc_date_format() . ' ' . osc_time_format() );
                    $row['expiration'] = ($aRow['dt_expiration'] != '9999-12-31 23:59:59') ? osc_format_date($aRow['dt_expiration'], osc_date_format() . ' ' . osc_time_format() ) : __('Never expires');

                    $row = osc_apply_filter('items_processing_row', $row, $aRow);

                    $this->addRow($row);
                    $this->rawRows[] = $aRow;
                }

            }
        }

        private function processDataReported($items)
        {
            if(!empty($items)) {

                $csrf_token_url = osc_csrf_token_url();
                foreach($items as $aRow) {
                    View::newInstance()->_exportVariableToView('item', $aRow);
                    $row     = array();
                    $options = array();
                    // -- prepare data --
                    // prepare item title
                    $title = mb_substr($aRow['s_title'], 0, 30, 'UTF-8');
                    if($title != $aRow['s_title']) {
                        $title .= '...';
                    }

                    $options[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=clear_stat&amp;id=' . $aRow['pk_i_id'] . '&amp;' . $csrf_token_url . '&amp;stat=all">' . __('Clear All') .'</a>';
                    if( $aRow['i_num_spam'] > 0 ) {
                        $options[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=clear_stat&amp;id=' . $aRow['pk_i_id'] . '&amp;' . $csrf_token_url . '&amp;stat=spam">' . __('Clear Spam') .'</a>';
                    }
                    if( $aRow['i_num_bad_classified'] > 0 ) {
                        $options[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=clear_stat&amp;id=' . $aRow['pk_i_id'] . '&amp;' . $csrf_token_url . '&amp;stat=bad">' . __('Clear Misclassified') .'</a>';
                    }
                    if( $aRow['i_num_repeated'] > 0 ) {
                        $options[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=clear_stat&amp;id=' . $aRow['pk_i_id'] . '&amp;' . $csrf_token_url . '&amp;stat=duplicated">' . __('Clear Duplicated') .'</a>';
                    }
                    if( $aRow['i_num_offensive'] > 0 ) {
                        $options[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=clear_stat&amp;id=' . $aRow['pk_i_id'] . '&amp;' . $csrf_token_url . '&amp;stat=offensive">' . __('Clear Offensive') .'</a>';
                    }
                    if( $aRow['i_num_expired'] > 0 ) {
                        $options[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=clear_stat&amp;id=' . $aRow['pk_i_id'] . '&amp;' . $csrf_token_url . '&amp;stat=expired">' . __('Clear Expired') .'</a>';
                    }
                    if(count($options) > 0) {
                        $options[] = '<a href="' . osc_admin_base_url(true) . '?page=items&amp;action=item_edit&amp;id=' . $aRow['pk_i_id'] . '">' . __('Edit') . '</a>';
                        $options[] = '<a onclick="return delete_dialog(\'' . $aRow['pk_i_id'] . '\');" href="' . osc_admin_base_url(true) . '?page=items&amp;action=delete&amp;id[]=' . $aRow['pk_i_id'] . '&amp;' . $csrf_token_url . '">' . __('Delete') . '</a>';
                    }

                    // create list of actions
                    $auxOptions = '<ul>'.PHP_EOL;
                    foreach( $options as $actual ) {
                        $auxOptions .= '<li>'.$actual.'</li>'.PHP_EOL;
                    }
                    $auxOptions  .= '</ul>'.PHP_EOL;

                    $actions = '<div class="actions">'.$auxOptions.'</div>'.PHP_EOL;

                    // fill a row
                    $row['bulkactions'] = '<input type="checkbox" name="id[]" value="' . $aRow['pk_i_id'] . '" active="' . $aRow['b_active'] . '" blocked="' . $aRow['b_enabled'] . '"/>';
                    $row['title'] = '<a href="' . osc_item_url().'" target="_blank">' . $title . '</a>'. $actions;
                    $row['user'] = $aRow['s_user_name'];
                    $row['spam'] = $aRow['i_num_spam'];
                    $row['bad'] = $aRow['i_num_bad_classified'];
                    $row['rep'] = $aRow['i_num_repeated'];
                    $row['exp'] = $aRow['i_num_expired'];
                    $row['off'] = $aRow['i_num_offensive'];
                    $row['date'] = osc_format_date($aRow['dt_pub_date'], osc_date_format() . ' ' . osc_time_format() );
                    $row['expiration'] = ($aRow['dt_expiration'] != '9999-12-31 23:59:59') ? osc_format_date($aRow['dt_expiration'], osc_date_format() . ' ' . osc_time_format() ) : __('Never expires') ;

                    $row = osc_apply_filter('items_processing_reported_row', $row, $aRow);

                    $this->addRow($row);
                    $this->rawRows[] = $aRow;
                }

            }
        }

        private function getDBParams($_get)
        {

            if(!isset($_get['iDisplayStart'])) {
                $_get['iDisplayStart'] = 0;
            }
            if(!isset($_get['iDisplayLength'])) {
                $_get['iDisplayLength'] = 10;
            }

            if(!is_numeric($_get['iPage']) || $_get['iPage'] < 1) {
                Params::setParam('iPage', 1 );
                $this->iPage = 1;
            } else {
                $this->iPage = $_get['iPage'];
            }

            $withUserId     = false;
            $no_user_email  = '';
            // get & set values
            foreach($_get as $k => $v) {

                if($k == 'sSearch' && $v != '') {
                    $this->mSearch->addPattern($v);
                    $this->withFilters = true;
                }

                // filters
                if($k == 'userId' && $v != '') {
                    $this->mSearch->fromUser($v);
                    $this->withFilters = true;
                    $withUserId = true;
                }
                if($k == 'itemId' && $v != '') {
                    $this->mSearch->addItemId($v);
                    $this->withFilters = true;
                }

                // si hay id mejor ...
                if($k == 'countryId' && $v != '') {
                    $this->mSearch->addCountry($v);
                    $this->withFilters = true;
                }
                if($k == 'regionId' && $v != '') {
                    $this->mSearch->addRegion($v);
                    $this->withFilters = true;
                }
                if($k == 'cityId' && $v != '') {
                    $this->mSearch->addCity($v);
                    $this->withFilters = true;
                }

                if($k == 'country' && $v != '') {
                    $this->mSearch->addCountry($v);
                    $this->withFilters = true;
                }
                if($k == 'region' && $v != '') {
                    $this->mSearch->addRegion($v);
                    $this->withFilters = true;
                }

                if($k == 'city' && $v != '') {
                    $this->mSearch->addCity($v);
                    $this->withFilters = true;
                }

                if($k == 'catId' && $v != '') {
                    $this->mSearch->addCategory($v);
                    $this->withFilters = true;
                }
                if($k == 'b_premium' && $v != '') {
                    $this->mSearch->addItemConditions(DB_TABLE_PREFIX.'t_item.b_premium = '.$v);
                    $this->withFilters = true;
                }
                if($k == 'b_active' && $v != '') {
                    $this->mSearch->addItemConditions(DB_TABLE_PREFIX.'t_item.b_active = '.$v);
                    $this->withFilters = true;
                }
                if($k == 'b_enabled' && $v != '') {
                    $this->mSearch->addItemConditions(DB_TABLE_PREFIX.'t_item.b_enabled = '.$v);
                    $this->withFilters = true;
                }
                if($k == 'b_spam' && $v != '') {
                    $this->mSearch->addItemConditions(DB_TABLE_PREFIX.'t_item.b_spam = '.$v);
                    $this->withFilters = true;
                }
                if($k == 'user' && $v != '') {
                    $no_user_email = $v;
                }
            }

            // add no registred user email if userId == '' and $no_user_email != ''
            if($no_user_email != '' && !$withUserId) {
                $this->mSearch->addContactEmail($no_user_email);
                $this->withFilters = true;
            }

            // set start and limit using iPage param
            $start = ($this->iPage - 1) * $_get['iDisplayLength'];

            $this->start = intval( $start );
            $this->limit = intval( $_get['iDisplayLength'] );
            $this->mSearch->limit($this->start, $this->limit);

            $direction = $_get['direction'];
            $arrayDirection = array('desc', 'asc');
            if(!in_array($direction, $arrayDirection)) {
                Params::setParam('direction', 'desc');
                $direction = 'desc';
            }

            // column sort
            $sort       = $_get['sort'];
            $arraySortColumns = array('date'  => 'dt_pub_date', 'expiration'  => 'dt_expiration');
            if(!key_exists($sort, $arraySortColumns)) {
                $sort       = 'dt_pub_date';
            } else {
                $sort = $arraySortColumns[$sort];
            }
            // only some fields can be ordered
            $this->mSearch->order($sort, $direction);


        }

        public function withFilters()
        {
            return $this->withFilters;
        }

        public function rawRows()
        {
            return $this->rawRows;
        }

        public function row_class($class, $rawRow, $row)
        {
            View::newInstance()->_exportVariableToView('item', $rawRow);
            $status = $this->get_row_status();
            $class[] = $status['class'];
            View::newInstance()->_erase('item');
            return $class;
        }

        /**
         * Get the status of the row. There are five status:
         *     - spam
         *     - blocked
         *     - inactive
         *     - premium
         *     - active
         *     - expired
         *
         * @since 3.2 -> 3.4.x
         *
         * @return array Array with the class and text of the status of the listing in this row. Example:
         *     array(
         *         'class' => '',
         *         'text'  => ''
         *     )
         */
        private function get_row_status()
        {
            if( osc_item_is_spam() ) {
                return array(
                    'class' => 'status-spam',
                    'text'  => __('Spam')
                );
            }

            if( !osc_item_is_enabled() ) {
                return array(
                    'class' => 'status-blocked',
                    'text'  => __('Blocked')
                );
            }

            if( !osc_item_is_active() ) {
                return array(
                    'class' => 'status-inactive',
                    'text'  => __('Inactive')
                );
            }

            if( osc_item_is_premium() ) {
                return array(
                    'class' => 'status-premium',
                    'text'  => __('Premium')
                );
            }

            if( osc_item_is_expired() ) {
                return array(
                    'class' => 'status-expired',
                    'text'  => __('Expired')
                );
            }

            return array(
                'class' => 'status-active',
                'text'  => __('Active')
            );
        }

        /**
         * Get the location separated by commas of a row
         *
         * @since 3.2
         *
         * @return string Location separated by commas
         */
        private function get_row_location()
        {
            $location = array();
            if( osc_item_city() !== '' ) {
                $location[] = osc_item_city();
            }
            if( osc_item_region() !== '' ) {
                $location[] = osc_item_region();
            }
            if( osc_item_country() !== '' ) {
                $location[] = osc_item_country();
            }

            return implode(', ', $location);
        }
    }

?>
