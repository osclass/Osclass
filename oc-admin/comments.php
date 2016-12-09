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

    class CAdminItemComments extends AdminSecBaseModel
    {
        private $itemCommentManager;

        function __construct()
        {
            parent::__construct();

            //specific things for this class
            $this->itemCommentManager = ItemComment::newInstance();
        }

        //Business Layer...
        function doModel()
        {
            parent::doModel();

            //specific things for this class
            switch($this->action) {
                case('bulk_actions'):
                                            osc_csrf_check();
                                            $id = Params::getParam('id');
                                            if( $id ) {
                                                switch( Params::getParam('bulk_actions') ) {
                                                    case('delete_all'):     $this->itemCommentManager->delete(array(
                                                                                DB_CUSTOM_COND => 'pk_i_id IN (' . implode(', ', $id) . ')'
                                                                            ));
                                                                            foreach ($id as $_id) {
                                                                                $iUpdated = $this->itemCommentManager->delete(array(
                                                                                    'pk_i_id' => $_id
                                                                                ));
                                                                                osc_run_hook("delete_comment", $_id);
                                                                            }
                                                                            osc_add_flash_ok_message( _m('The comments have been deleted'), 'admin');
                                                    break;
                                                    case('activate_all'):
                                                                            foreach ($id as $_id) {
                                                                                $iUpdated = $this->itemCommentManager->update(
                                                                                     array('b_active' => 1)
                                                                                    ,array('pk_i_id'  => $_id)
                                                                                );
                                                                                if($iUpdated) {
                                                                                    $this->sendCommentActivated($_id);
                                                                                }
                                                                                osc_run_hook("activate_comment", $_id);
                                                                            }
                                                                            osc_add_flash_ok_message( _m('The comments have been approved'), 'admin');
                                                    break;
                                                    case('deactivate_all'):
                                                                            foreach ($id as $_id) {
                                                                                $this->itemCommentManager->update(
                                                                                    array('b_active' => 0),
                                                                                    array('pk_i_id' => $_id)
                                                                                );
                                                                                osc_run_hook("deactivate_comment", $_id);
                                                                            }
                                                                            osc_add_flash_ok_message( _m('The comments have been disapproved'), 'admin');
                                                    break;
                                                    case('enable_all'):
                                                                            foreach ($id as $_id) {
                                                                                $iUpdated = $this->itemCommentManager->update(
                                                                                    array('b_enabled' => 1),
                                                                                    array('pk_i_id'   => $_id)
                                                                                );
                                                                                if($iUpdated) {
                                                                                    $this->sendCommentActivated($_id);
                                                                                }
                                                                                osc_run_hook("enable_comment", $_id);
                                                                            }
                                                                            osc_add_flash_ok_message( _m('The comments have been unblocked'), 'admin' );
                                                    break;
                                                    case('disable_all'):
                                                                            foreach ($id as $_id) {
                                                                                $this->itemCommentManager->update(
                                                                                    array('b_enabled' => 0),
                                                                                    array('pk_i_id' => $_id)
                                                                                );
                                                                                osc_run_hook("disable_comment", $_id);
                                                                            }
                                                                            osc_add_flash_ok_message( _m('The comments have been blocked'), 'admin');
                                                    break;
                                                    default:
                                                        if(Params::getParam("bulk_actions")!="") {
                                                            osc_run_hook("item_bulk_".Params::getParam("bulk_actions"), Params::getParam('id'));
                                                        }
                                                    break;
                                                }
                                            }
                                            $this->redirectTo( osc_admin_base_url(true) . "?page=comments" );
                break;
                case('status'):
                                            osc_csrf_check();
                                            $id = Params::getParam('id');
                                            $value = Params::getParam('value');

                                            if (!$id) return false;
                                            $id = (int) $id;
                                            if (!is_numeric($id)) return false;
                                            if (!in_array($value, array('ACTIVE', 'INACTIVE', 'ENABLE', 'DISABLE'))) return false;

                                            if( $value == 'ACTIVE' ) {
                                                $iUpdated = $this->itemCommentManager->update(
                                                        array('b_active' => 1)
                                                        ,array('pk_i_id' => $id)
                                                );
                                                if($iUpdated) {
                                                    $this->sendCommentActivated($id);
                                                }
                                                osc_run_hook("activate_comment", $id);
                                                osc_add_flash_ok_message( _m('The comment has been approved'), 'admin');
                                            } else if($value=='INACTIVE') {
                                                $iUpdated = $this->itemCommentManager->update(
                                                        array('b_active' => 0)
                                                        ,array('pk_i_id' => $id)
                                                );
                                                osc_run_hook("deactivate_comment", $id);
                                                osc_add_flash_ok_message( _m('The comment has been disapproved'), 'admin');
                                            } else if($value=='ENABLE') {
                                                $iUpdated = $this->itemCommentManager->update(
                                                        array('b_enabled' => 1)
                                                        ,array('pk_i_id' => $id)
                                                );
                                                osc_run_hook("enable_comment", $id);
                                                osc_add_flash_ok_message( _m('The comment has been enabled'), 'admin');
                                            } else if($value=='DISABLE') {
                                                $iUpdated = $this->itemCommentManager->update(
                                                        array('b_enabled' => 0)
                                                        ,array('pk_i_id' => $id)
                                                );
                                                osc_run_hook("disable_comment", $id);
                                                osc_add_flash_ok_message( _m('The comment has been disabled'), 'admin');
                                            }

                                            $this->redirectTo( osc_admin_base_url(true) . "?page=comments" );
                break;
                case('comment_edit'):       $comment = ItemComment::newInstance()->findByPrimaryKey( Params::getParam('id') );

                                            $this->_exportVariableToView('comment', $comment);
                                            $this->doView('comments/frm.php');
                break;
                case('comment_edit_post'):
                                            osc_csrf_check();

                                            $msg = '';
                                            if(!osc_validate_email(Params::getParam('authorEmail'),true)) {
                                                $msg .= _m('Email is not correct')."<br/>";
                                            }
                                            if(!osc_validate_text(Params::getParam('body'),1 , true)) {
                                                $msg .= _m('Comment is required')."<br/>";
                                            }

                                            if($msg!='') {
                                                osc_add_flash_error_message( $msg, 'admin' );
                                                $this->redirectTo( osc_admin_base_url(true) . "?page=comments&action=comment_edit&id=".Params::getParam('id') );
                                            }

                                            $this->itemCommentManager->update(
                                                array(
                                                    's_title'        => Params::getParam('title'),
                                                    's_body'         => Params::getParam('body'),
                                                    's_author_name'  => Params::getParam('authorName'),
                                                    's_author_email' => Params::getParam('authorEmail')
                                                ),
                                                array(
                                                    'pk_i_id' => Params::getParam('id')
                                                )
                                            );

                                            osc_run_hook( 'edit_comment', Params::getParam('id') );

                                            osc_add_flash_ok_message( _m('Great! We just updated your comment'), 'admin' );
                                            $this->redirectTo( osc_admin_base_url(true) . "?page=comments" );
                break;
                case('delete'):
                                            osc_csrf_check();
                                            $this->itemCommentManager->deleteByPrimaryKey( Params::getParam('id') );
                                            osc_add_flash_ok_message( _m('The comment has been deleted'), 'admin');
                                            osc_run_hook( 'delete_comment', Params::getParam('id') );
                                            $this->redirectTo( osc_admin_base_url(true) . "?page=comments" );
                break;
                default:
                                            require_once osc_lib_path()."osclass/classes/datatables/CommentsDataTable.php";

                                            // set default iDisplayLength
                                            if( Params::getParam('iDisplayLength') != '' ) {
                                                Cookie::newInstance()->push('listing_iDisplayLength', Params::getParam('iDisplayLength'));
                                                Cookie::newInstance()->set();
                                            } else {
                                                // set a default value if it's set in the cookie
                                                if( Cookie::newInstance()->get_value('listing_iDisplayLength') != '' ) {
                                                    Params::setParam('iDisplayLength', Cookie::newInstance()->get_value('listing_iDisplayLength'));
                                                } else {
                                                    Params::setParam('iDisplayLength', 10 );
                                                }
                                            }
                                            $this->_exportVariableToView('iDisplayLength', Params::getParam('iDisplayLength'));

                                            // Table header order by related
                                            if( Params::getParam('sort') == '') {
                                                Params::setParam('sort', 'date');
                                            }
                                            if( Params::getParam('direction') == '') {
                                                Params::setParam('direction', 'desc');
                                            }

                                            $page  = (int)Params::getParam('iPage');
                                            if($page==0) { $page = 1; };
                                            Params::setParam('iPage', $page);

                                            $params = Params::getParamsAsArray();

                                            $commentsDataTable = new CommentsDataTable();
                                            $commentsDataTable->table($params);
                                            $aData = $commentsDataTable->getData();

                                            if(count($aData['aRows']) == 0 && $page!=1) {
                                                $total = (int)$aData['iTotalDisplayRecords'];
                                                $maxPage = ceil( $total / (int)$aData['iDisplayLength'] );

                                                $url = osc_admin_base_url(true).'?'.Params::getServerParam('QUERY_STRING', false, false);

                                                if($maxPage==0) {
                                                    $url = preg_replace('/&iPage=(\d)+/', '&iPage=1', $url);
                                                    $this->redirectTo($url);
                                                }

                                                if($page > 1) {
                                                    $url = preg_replace('/&iPage=(\d)+/', '&iPage='.$maxPage, $url);
                                                    $this->redirectTo($url);
                                                }
                                            }


                                            $this->_exportVariableToView('aData', $aData);
                                            $this->_exportVariableToView('aRawRows', $commentsDataTable->rawRows());

                                            $bulk_options = array(
                                                array('value' => '', 'data-dialog-content' => '', 'label' => __('Bulk actions')),
                                                array('value' => 'delete_all', 'data-dialog-content' => sprintf(__('Are you sure you want to %s the selected comments?'), strtolower(__('Delete'))), 'label' => __('Delete')),
                                                array('value' => 'activate_all', 'data-dialog-content' => sprintf(__('Are you sure you want to %s the selected comments?'), strtolower(__('Activate'))), 'label' => __('Activate')),
                                                array('value' => 'deactivate_all', 'data-dialog-content' => sprintf(__('Are you sure you want to %s the selected comments?'), strtolower(__('Deactivate'))), 'label' => __('Deactivate')),
                                                array('value' => 'disable_all', 'data-dialog-content' => sprintf(__('Are you sure you want to %s the selected comments?'), strtolower(__('Block'))), 'label' => __('Block')),
                                                array('value' => 'enable_all', 'data-dialog-content' => sprintf(__('Are you sure you want to %s the selected comments?'), strtolower(__('Unblock'))), 'label' => __('Unblock'))
                                            );
                                            $bulk_options = osc_apply_filter("comment_bulk_filter", $bulk_options);
                                            $this->_exportVariableToView('bulk_options', $bulk_options);

                                            $this->doView('comments/index.php');
                break;
            }
        }

        //hopefully generic...
        function doView($file)
        {
            osc_run_hook("before_admin_html");
            osc_current_admin_theme_path($file);
            Session::newInstance()->_clearVariables();
            osc_run_hook("after_admin_html");
        }

        function sendCommentActivated ($commentId)
        {
            $aComment = $this->itemCommentManager->findByPrimaryKey($commentId);
            $aItem    = Item::newInstance()->findByPrimaryKey($aComment['fk_i_item_id']);
            View::newInstance()->_exportVariableToView('item', $aItem);

            osc_run_hook('hook_email_comment_validated', $aComment);
        }
    }

    /* file end: ./oc-admin/comments.php */
?>