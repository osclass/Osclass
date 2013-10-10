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

    class CAdminSettingsComments extends AdminSecBaseModel
    {
        //Business Layer...
        function doModel()
        {
            switch($this->action) {
                case('comments'):
                    //calling the comments settings view
                    $this->doView('settings/comments.php');
                break;
                case('comments_post'):
                    // updating comment
                    osc_csrf_check();
                    $iUpdated         = 0;
                    $enabledComments  = Params::getParam('enabled_comments');
                    $enabledComments  = (($enabledComments != '') ? true : false);
                    $moderateComments = Params::getParam('moderate_comments');
                    $moderateComments = (($moderateComments != '') ? true : false);
                    $numModerateComments = Params::getParam('num_moderate_comments');
                    $commentsPerPage  = Params::getParam('comments_per_page');
                    $notifyNewComment = Params::getParam('notify_new_comment');
                    $notifyNewComment = (($notifyNewComment != '') ? true : false);
                    $notifyNewCommentUser = Params::getParam('notify_new_comment_user');
                    $notifyNewCommentUser = (($notifyNewCommentUser != '') ? true : false);
                    $regUserPostComments  = Params::getParam('reg_user_post_comments');
                    $regUserPostComments  = (($regUserPostComments != '') ? true : false);

                    $msg = '';
                    if(!osc_validate_int(Params::getParam("num_moderate_comments"))) {
                        $msg .= _m("Number of moderate comments must only contain numeric characters")."<br/>";
                    }
                    if(!osc_validate_int(Params::getParam("comments_per_page"))) {
                        $msg .= _m("Comments per page must only contain numeric characters")."<br/>";
                    }
                    if($msg!='') {
                        osc_add_flash_error_message( $msg, 'admin');
                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=comments');
                    }

                    $iUpdated += osc_set_preference('enabled_comments', $enabledComments);
                    if($moderateComments) {
                        $iUpdated += osc_set_preference('moderate_comments', $numModerateComments);
                    } else {
                        $iUpdated += osc_set_preference('moderate_comments', '-1');
                    }
                    $iUpdated += osc_set_preference('notify_new_comment', $notifyNewComment);
                    $iUpdated += osc_set_preference('notify_new_comment_user', $notifyNewCommentUser);
                    $iUpdated += osc_set_preference('comments_per_page', $commentsPerPage);

                    $iUpdated += osc_set_preference('reg_user_post_comments', $regUserPostComments);

                    if($iUpdated > 0) {
                        osc_add_flash_ok_message( _m("Comment settings have been updated"), 'admin');
                    }
                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=comments');
                break;
            }
        }
    }

    // EOF: ./oc-admin/controller/settings/comments.php