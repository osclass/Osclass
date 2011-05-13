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
                                                                            osc_add_flash_ok_message( _m('The comments have been deleted'), 'admin') ;
                                                    break;
                                                    case 'activate_all':    $value = 'ACTIVE' ;
                                                                            foreach ($id as $_id) {
                                                                                $iUpdated = $this->itemCommentManager->update(
                                                                                     array('e_status' => $value)
                                                                                    ,array('pk_i_id'  => $_id)
                                                                                );
                                                                                if($iUpdated) {
                                                                                    $this->sendCommentActivated($_id);
                                                                                }
                                                                            }
                                                                            osc_add_flash_ok_message( _m('The comments have been approved'), 'admin') ;
                                                    break;
                                                    case 'deactivate_all':  $value = 'INACTIVE' ;
                                                                            foreach ($id as $_id) {
                                                                                $this->itemCommentManager->update(
                                                                                    array('e_status' => $value),
                                                                                    array('pk_i_id' => $_id)
                                                                                );
                                                                            }
                                                                            osc_add_flash_ok_message( _m('The comments have been disapproved'), 'admin') ;
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

                                            $iUpdated = $this->itemCommentManager->update(
                                                    array('e_status' => $value)
                                                    ,array('pk_i_id' => $id)
                                            );
                                            if( $value == 'ACTIVE' ) {
                                                if($iUpdated) {
                                                    $this->sendCommentActivated($id);
                                                }
                                                osc_add_flash_ok_message( _m('The comment has been approved'), 'admin');
                                            } else {
                                                osc_add_flash_ok_message( _m('The comment has been disapproved'), 'admin');
                                            }

                                            $this->redirectTo( osc_admin_base_url(true) . "?page=comments" ) ;
                break;
                case 'comment_edit':        $id = Params::getParam('id') ;
                                            $comment = ItemComment::newInstance()->findByPrimaryKey($id) ;

                                            $this->_exportVariableToView('comment', $comment) ;

                                            $this->doView('comments/frm.php') ;
                break;
                case 'comment_edit_post':   $this->itemCommentManager->update(
                                                 array('s_title' => Params::getParam('title')
                                                     ,'s_body' => Params::getParam('body')
                                                     ,'s_author_name' => Params::getParam('authorName')
                                                     ,'s_author_email' => Params::getParam('authorEmail'))
                                                ,array(
                                                    'pk_i_id' => Params::getParam('id')
                                                ));

                                            osc_run_hook('item_edit_post') ;

                                            osc_add_flash_ok_message( _m('Great! We just updated your comment'), 'admin') ;
                                            $this->redirectTo( osc_admin_base_url(true) . "?page=comments" ) ;
                break;
                case 'delete':              $this->itemCommentManager->deleteByPrimaryKey( Params::getParam('id') );
                                            osc_add_flash_ok_message( _m('The comment have been deleted'), 'admin') ;
                                            $this->redirectTo( osc_admin_base_url(true) . "?page=comments" ) ;
                break;
                default:                    if( Params::getParam('id') != '' ){
                                                $comments = $this->itemCommentManager->getAllComments( Params::getParam('id') ) ;
                                            } else {
                                                $comments = $this->itemCommentManager->getAllComments( ) ;
                                            }

                                            $this->_exportVariableToView('comments', $comments) ;

                                            //calling the view...
                                            $this->doView('comments/index.php') ;

            }
        }

        //hopefully generic...
        function doView($file) {
            osc_current_admin_theme_path($file) ;
        }

        function sendCommentActivated ($commentId)
        {
            $aComment = $this->itemCommentManager->findByPrimaryKey($commentId);
            $aItem    = Item::newInstance()->findByPrimaryKey($aComment['fk_i_item_id']);
            View::newInstance()->_exportVariableToView('item', $aItem);

            $mPages = new Page() ;
            $locale = osc_current_user_locale() ;

            $aPage = $mPages->findByInternalName('email_comment_validated') ;

            $content = array() ;
            if(isset($aPage['locale'][$locale]['s_title'])) {
                $content = $aPage['locale'][$locale] ;
            } else {
                $content = current($aPage['locale']) ;
            }

            if (!is_null($content)) {
                $words   = array();
                $words[] = array('{COMMENT_AUTHOR}', '{COMMENT_EMAIL}',
                                 '{COMMENT_TITLE}', '{COMMENT_BODY}',
                                 '{WEB_URL}', '{ITEM_URL}',
                                 '{ITEM_LINK}') ;
                $words[] = array($aComment['s_author_name'], $aComment['s_author_email'],
                                 $aComment['s_title'], $aComment['s_body'],
                                 osc_base_url(), osc_item_url(),
                                 '<a href="' . osc_item_url() . '">' . osc_item_url() . '</a>') ;
                $title = osc_mailBeauty($content['s_title'], $words) ;
                $body = osc_mailBeauty($content['s_text'], $words) ;

                $emailParams = array('subject'  => $title
                                     ,'to'       => $aComment['s_author_email']
                                     ,'to_name'  => $aComment['s_author_name']
                                     ,'body'     => $body
                                     ,'alt_body' => $body
                ) ;
                osc_sendMail($emailParams) ;
            }
        }
    }

?>