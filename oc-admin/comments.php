<?php

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


class CAdminItemComments extends AdminSecBaseModel
{
    private $itemCommentManager ;
    
    function __construct() {
        parent::__construct() ;

        //specific things for this class
        $this->itemCommentManager = ItemComment::newInstance() ;
    }

    //Business Layer...
    function doModel() {
        parent::doModel() ;

        //specific things for this class
        switch ($this->action)
        {
            case 'bulk_actions':        $id = Params::getParam('id') ;
                                        if ($id) {
                                            switch ( Params::getParam('bulk_actions') )
                                            {
                                                case 'delete_all':      $this->itemCommentManager->delete(array(
                                                                            DB_CUSTOM_COND => 'pk_i_id IN (' . implode(', ', $id) . ')'
                                                                        ));
                                                                        osc_add_flash_message(__('The comments have been deleted')) ;
                                                break;
                                                case 'activate_all':    $value = 'ACTIVE' ;
                                                                        foreach ($id as $_id) {
                                                                            $this->itemCommentManager->update(
                                                                                array('e_status' => $value),
                                                                                array('pk_i_id' => $_id)
                                                                            );
                                                                        }
                                                                        osc_add_flash_message(__('The comments have been approved')) ;
                                                break;
                                                case 'deactivate_all':  $value = 'INACTIVE' ;
                                                                        foreach ($id as $_id) {
                                                                            $this->itemCommentManager->update(
                                                                                array('e_status' => $value),
                                                                                array('pk_i_id' => $_id)
                                                                            );
                                                                        }
                                                                        osc_add_flash_message(__('The comments have been unapproved')) ;
                                                break;
                                            }
                                        }
                                        $this->redirectTo( osc_admin_base_url(true) . "?page=comments" ) ;
            break;
            case 'status':              $id = Params::getParam('id') ;
                                        $value = Params::getParam('value') ;

                                        if (!$id) return false;
                                        $id = (int) $id;
                                        if (!is_numeric($id)) return false;
                                        if (!in_array($value, array('ACTIVE', 'INACTIVE'))) return false ;

                                        $this->itemCommentManager->update(
                                                array('e_status' => $value)
                                                ,array('pk_i_id' => $id)
                                        );
                                        if( $value == 'ACTIVE' ) {
                                            osc_add_flash_message(__('The comment has been approved'));
                                        } else {
                                            osc_add_flash_message(__('The comment has been unapproved'));
                                        }
                                        
                                        $this->redirectTo( osc_admin_base_url(true) . "?page=comments" ) ;
            break;
            case 'comment_edit':        $itemId = Params::getParam('id') ;
                                        $comment = Comment::newInstance()->findByPrimaryKey($itemId) ;

                                        $this->_exportVariableToView('comments', $comments) ;
                                        
                                        $this->doView('comments/frm.php') ;
            break;
            case 'comment_edit_post':   $this->itemCommentManager->update(
                                            array(
                                                's_title' => Params::getParam('s_title')
                                                ,'s_body' => Params::getParam('s_body')
                                                ,'s_author_name' => Params::getParam('s_author_name')
                                                ,'s_author_email' => Params::getParam('s_author_email')
                                            )
                                            ,array(
                                                'pk_i_id' => Params::getParam('id')
                                            )
                                        );

                                        osc_run_hook('item_edit_post') ;

                                        osc_add_flash_message(__('Great! We just updated your comment')) ;
                                        $this->redirectTo( osc_admin_base_url(true) . "?page=comments" ) ;
            break;
            case 'delete':              $this->itemCommentManager->deleteByID( Params::getParam('id') );
            break;
            default:                    $comments = $this->itemCommentManager->getAllComments( Params::getParam('id') ) ;
                                        
                                        $this->_exportVariableToView('comments', $comments) ;

                                        //calling the view...
                                        $this->add_global_js('jquery.dataTables.min.js') ;
                                        $this->add_css('item_list_layout.css') ;
                                        $this->add_css('demo_table.css') ;
                                        $this->doView('comments/index.php') ;

        }
    }

    //hopefully generic...
    function doView($file) {
        $this->osc_print_html($file) ;
    }
}

?>
