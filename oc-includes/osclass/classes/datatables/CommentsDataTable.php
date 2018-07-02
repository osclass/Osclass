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
     * CommentsDataTable class
     *
     * @since 3.1
     * @package Osclass
     * @subpackage classes
     * @author Osclass
     */
    class CommentsDataTable extends DataTable
    {

        private $resourceID;
        private $order_by;
        private $showAll;

        public function __construct()
        {
            parent::__construct();
            osc_add_filter('datatable_comment_class', array(&$this, 'row_class'));
        }

        public function table($params)
        {

            $this->addTableHeader();
            $this->getDBParams($params);

            $comments = ItemComment::newInstance()->search($this->resourceID, $this->start, $this->limit,
                    ( $this->order_by['column_name'] ? $this->order_by['column_name'] : 'pk_i_id' ),
                    ( $this->order_by['type'] ? $this->order_by['type'] : 'desc' ),
                    $this->showAll);
            $this->processData($comments);


            if($this->showAll) {
                $this->total          = ItemComment::newInstance()->countAll();
            } else {
                $this->total          = ItemComment::newInstance()->countAll( '( c.b_active = 0 OR c.b_enabled = 0 OR c.b_spam = 1 )' );
            }

            if( $this->resourceID == null ) {
                $this->total_filtered = $this->total;
            } else {
                $this->total_filtered = ItemComment::newInstance()->count( $this->resourceID );
            }

            return $this->getData();
        }

        private function addTableHeader()
        {

            $this->addColumn('status-border', '');
            $this->addColumn('status', __('Status'));
            $this->addColumn('bulkactions', '<input id="check_all" type="checkbox" />');
            $this->addColumn('author', __('Author'));
            $this->addColumn('comment', __('Comment'));
            $this->addColumn('date', __('Date'));

            $dummy = &$this;
            osc_run_hook("admin_comments_table", $dummy);
        }

        private function processData($comments)
        {
            if(!empty($comments)) {

                $csrf_token_url = osc_csrf_token_url();
                foreach($comments as $aRow) {
                    $row = array();
                    $options = array();
                    $options_more = array();

                    View::newInstance()->_exportVariableToView('item', Item::newInstance()->findByPrimaryKey($aRow['fk_i_item_id']));

                    if( $aRow['b_enabled'] ) {
                        $options_more[] = '<a href="' . osc_admin_base_url(true) . '?page=comments&amp;action=status&amp;id=' . $aRow['pk_i_id'] . '&amp;' . $csrf_token_url . '&amp;value=DISABLE">' . __('Block') . '</a>';
                    } else {
                        $options_more[] = '<a href="' . osc_admin_base_url(true) . '?page=comments&amp;action=status&amp;id=' . $aRow['pk_i_id'] . '&amp;' . $csrf_token_url . '&amp;value=ENABLE">' . __('Unblock') . '</a>';
                    }
                    $options_more[] = '<a onclick="return delete_dialog(\'' . $aRow['pk_i_id'] . '\');" href="' . osc_admin_base_url(true) . '?page=comments&amp;action=delete&amp;id=' . $aRow['pk_i_id'] .'" id="dt_link_delete">' . __('Delete') . '</a>';

                    $options[] = '<a href="' . osc_admin_base_url(true) . '?page=comments&amp;action=comment_edit&amp;id=' . $aRow['pk_i_id'] . '" id="dt_link_edit">' . __('Edit') . '</a>';
                    if( $aRow['b_active'] ) {
                        $options[] = '<a href="' . osc_admin_base_url(true) . '?page=comments&amp;action=status&amp;id=' . $aRow['pk_i_id'] . '&amp;' . $csrf_token_url . '&amp;value=INACTIVE">' . __('Deactivate') . '</a>';
                    } else {
                        $options[] = '<a href="' . osc_admin_base_url(true) . '?page=comments&amp;action=status&amp;id=' . $aRow['pk_i_id'] . '&amp;' . $csrf_token_url .'&amp;value=ACTIVE">' . __('Activate') . '</a>';
                    }

                    // more actions
                    $moreOptions = '<li class="show-more">'.PHP_EOL.'<a href="#" class="show-more-trigger">'. __('Show more') .'...</a>'. PHP_EOL .'<ul>'. PHP_EOL;
                    foreach( $options_more as $actual ) {
                        $moreOptions .= '<li>'.$actual."</li>".PHP_EOL;
                    }
                    $moreOptions .= '</ul>'. PHP_EOL .'</li>'.PHP_EOL;

                    // create list of actions
                    $auxOptions = '<ul>'.PHP_EOL;
                    foreach( $options as $actual ) {
                        $auxOptions .= '<li>'.$actual.'</li>'.PHP_EOL;
                    }
                    $auxOptions  .= $moreOptions;
                    $auxOptions  .= '</ul>'.PHP_EOL;

                    $actions = '<div class="actions">'.$auxOptions.'</div>'.PHP_EOL;

                    $status = $this->get_row_status($aRow);
                    $row['status-border'] = '';
                    $row['status'] = $status['text'];
                    $row['bulkactions'] = '<input type="checkbox" name="id[]" value="' . $aRow['pk_i_id']  . '" />';
                    if( empty($aRow['s_author_name']) ) {
                        $user = User::newInstance()->findByPrimaryKey( $aRow['fk_i_user_id'] );
                        $aRow['s_author_name'] = $user['s_email'];
                    }
                    $row['author'] = $aRow['s_author_name'] . ' (<a target="_blank" href="' . osc_item_url() . '">' . osc_item_title() . '</a>)'. $actions;
                    $row['comment'] = $aRow['s_body'];
                    $row['date'] = osc_format_date($aRow['dt_pub_date']);

                    $row = osc_apply_filter('comments_processing_row', $row, $aRow);

                    $this->addRow($row);
                    $this->rawRows[] = $aRow;
                }

            }
        }

        private function getDBParams($_get)
        {

            $this->order_by['column_name'] = 'c.dt_pub_date';
            $this->order_by['type'] = 'desc';

            $this->showAll   = Params::getParam('showAll')=='off'?false:true;

            foreach($_get as $k => $v) {
                if( ( $k == 'resourceId' ) && !empty($v) ) {
                    $this->resourceID = intval($v);
                }
                if( $k == 'iDisplayStart' ) {
                    $this->start = intval($v);
                }
                if( $k == 'iDisplayLength' ) {
                    $this->limit = intval($v);
                }
            }

            // set start and limit using iPage param
            $start = ((int)Params::getParam('iPage')-1) * $_get['iDisplayLength'];

            $this->start = intval( $start );
            $this->limit = intval( $_get['iDisplayLength'] );

        }

        public function row_class($class, $rawRow, $row)
        {
            $status = $this->get_row_status($rawRow);
            $class[] = $status['class'];
            return $class;
        }

        /**
         * Get the status of the row. There are three status:
         *     - blocked
         *     - inactive
         *     - active
         *
         * @since 3.3
         *
         * @return array Array with the class and text of the status of the listing in this row. Example:
         *     array(
         *         'class' => '',
         *         'text'  => ''
         *     )
         */
        private function get_row_status($user)
        {

            if( $user['b_enabled']==0 ) {
                return array(
                    'class' => 'status-blocked',
                    'text'  => __('Blocked')
                );
            }

            if( $user['b_active']==0 ) {
                return array(
                    'class' => 'status-inactive',
                    'text'  => __('Inactive')
                );
            }

            return array(
                'class' => 'status-active',
                'text'  => __('Active')
            );
        }

    }

?>
