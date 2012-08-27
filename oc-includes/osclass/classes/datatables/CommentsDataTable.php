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
     * CommentsDataTable class
     * 
     * @since 3.1
     * @package OSClass
     * @subpackage classes
     * @author OSClass
     */
    class CommentsDataTable extends DataTable
    {
        
        private $resourceID;
        private $order_by;
        private $showAll;
        
        public function table($params)
        {
            
            $this->addTableHeader();
            $this->getDBParams($params) ;

            $comments = ItemComment::newInstance()->search($this->resourceID, $this->start, $this->limit, 
                    ( $this->order_by['column_name'] ? $this->order_by['column_name'] : 'pk_i_id' ), 
                    ( $this->order_by['type'] ? $this->order_by['type'] : 'desc' ),
                    $this->showAll) ;
            $this->processData($comments);
            
            
            if($this->showAll) {
                $this->total          = ItemComment::newInstance()->countAll();
            } else {
                $this->total          = ItemComment::newInstance()->countAll( '( c.b_active = 0 OR c.b_enabled = 0 OR c.b_spam = 1 )' );
            }
            
            if( $this->resourceID == null ) {
                $this->total_filtered = $this->total ;
            } else {
                $this->total_filtered = ItemComment::newInstance()->count( $this->resourceID ) ;
            }
                        
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
            $this->addColumn('author', __('Author'));
            $this->addColumn('comment', __('Comment'));
            $this->addColumn('date', __('Date'));

            $dummy['table'] = &$this;
            osc_run_hook("admin_comments_table", $dummy);
        }
        
        private function processData($comments)
        {
            if(!empty($comments)) {
            
                foreach($comments as $aRow) {
                    $row = array() ;
                    $options = array() ;
                    $options_more = array() ;

                    View::newInstance()->_exportVariableToView('item', Item::newInstance()->findByPrimaryKey($comment['fk_i_item_id']));

                    if( $comment['b_active'] ) {
                        $options_more[] = '<a href="' . osc_admin_base_url(true) . '?page=comments&amp;action=status&amp;id=' . $comment['pk_i_id'] . '&amp;value=INACTIVE">' . __('Deactivate') . '</a>' ;
                    } else {
                        $options_more[] = '<a href="' . osc_admin_base_url(true) . '?page=comments&amp;action=status&amp;id=' . $comment['pk_i_id'] .'&amp;value=ACTIVE">' . __('Activate') . '</a>' ;
                    }
                    if( $comment['b_enabled'] ) {
                        $options_more[] = '<a href="' . osc_admin_base_url(true) . '?page=comments&amp;action=status&amp;id=' . $comment['pk_i_id'] . '&amp;value=DISABLE">' . __('Block') . '</a>' ;
                    } else {
                        $options_more[] = '<a href="' . osc_admin_base_url(true) . '?page=comments&amp;action=status&amp;id=' . $comment['pk_i_id'] . '&amp;value=ENABLE">' . __('Unblock') . '</a>' ;
                    }

                    $options[] = '<a href="' . osc_admin_base_url(true) . '?page=comments&amp;action=comment_edit&amp;id=' . $comment['pk_i_id'] . '" id="dt_link_edit">' . __('Edit') . '</a>' ;
                    $options[] = '<a onclick="return delete_dialog(\'' . $comment['pk_i_id'] . '\');" href="' . osc_admin_base_url(true) . '?page=comments&amp;action=delete&amp;id=' . $comment['pk_i_id'] .'" id="dt_link_delete">' . __('Delete') . '</a>' ;

                    // more actions
                    $moreOptions = '<li class="show-more">'.PHP_EOL.'<a href="#" class="show-more-trigger">'. __('Show more') .'...</a>'. PHP_EOL .'<ul>'. PHP_EOL ;
                    foreach( $options_more as $actual ) { 
                        $moreOptions .= '<li>'.$actual."</li>".PHP_EOL;
                    }
                    $moreOptions .= '</ul>'. PHP_EOL .'</li>'.PHP_EOL ;

                    // create list of actions
                    $auxOptions = '<ul>'.PHP_EOL ;
                    foreach( $options as $actual ) {
                        $auxOptions .= '<li>'.$actual.'</li>'.PHP_EOL;
                    }
                    $auxOptions  .= $moreOptions ;
                    $auxOptions  .= '</ul>'.PHP_EOL ;

                    $actions = '<div class="actions">'.$auxOptions.'</div>'.PHP_EOL ;

                    $row['bulkactions'] = '<input type="checkbox" name="id[]" value="' . $comment['pk_i_id']  . '" />' ;
                    if( empty($comment['s_author_name']) ) {
                        $user = User::newInstance()->findByPrimaryKey( $comment['fk_i_user_id'] );
                        $comment['s_author_name'] = $user['s_email'];
                    }
                    $row['author'] = $comment['s_author_name'] . ' (<a target="_blank" href="' . osc_item_url() . '">' . osc_item_title() . '</a>)'. $actions  ;
                    $row['comment'] = $comment['s_body'] ;
                    $row['date'] = $comment['dt_pub_date'] ;

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
            
            $this->showAll   = Params::getParam('showAll') ;

            foreach($_get as $k => $v) {
                if( ( $k == 'resourceId' ) && !empty($v) ) {
                    $this->resourceID = intval($v) ;
                }
                if( $k == 'iDisplayStart' ) {
                    $this->start = intval($v) ;
                }
                if( $k == 'iDisplayLength' ) {
                    $this->limit = intval($v) ;
                }
            }

            // set start and limit using iPage param
            $start = ((int)Params::getParam('iPage')-1) * $_get['iDisplayLength'];

            $this->start = intval( $start ) ;
            $this->limit = intval( $_get['iDisplayLength'] ) ;
            
        }
        
    }

?>