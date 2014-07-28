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