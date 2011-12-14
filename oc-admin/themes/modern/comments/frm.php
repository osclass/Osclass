<?php
    /**
     * OSClass – software for creating and publishing online classified advertising platforms
     *
     * Copyright (C) 2010 OSCLASS
     *
     * This program is free software: you can redistribute it and/or modify it under the terms
     * of the GNU Affero General Public License as published by the Free Software Foundation,
     * either version 3 of the License, or (at your option) any later version.
     *
     * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
     * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
     * See the GNU Affero General Public License for more details.
     *
     * You should have received a copy of the GNU Affero General Public
     * License along with this program. If not, see <http://www.gnu.org/licenses/>.
     */

    $comment = __get('comment') ;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <div id="update_version" style="display:none;"></div>
        <?php
            if(isset($comment['pk_i_id'])) {
                //editing...
                $edit = true ;
                $title = __("Edit comment") ;
                $action_frm = "comment_edit_post";
                $btn_text = __("Save");
            } else {
                //adding...
                $edit = false ;
                $title = __("Add a comment");
                $action_frm = "add_comment_post";
                $btn_text = __('Add');
            }
        ?>
        <script type="text/javascript">
            function checkForm() {
                if(document.getElementById('s_title').value == "") {
                    alert("<?php  _e('You have to write a title.');?>");
                    return false;
                }

                if(document.getElementById('s_body').value == "") {
                    alert("<?php  _e('You have to write a comment.');?>");
                    return false;
                }

                if(document.getElementById('s_author_name').value == "") {
                    alert("<?php  _e('Author\'s name can not be empty.');?>");
                    return false;
                }

                if(document.getElementById('s_author_email').value == "") {
                    alert("<?php  _e('Author\'s email can not be empty.');?>");
                    return false;
                }

                return true;
            }
        </script>
        <div id="content">
            <div id="separator"></div>

            <?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>

            <div id="right_column">
                <div id="content_header" class="content_header">
                    <div style="float: left;">
                        <img src="<?php echo osc_current_admin_theme_url('images/comments-icon2.png') ; ?>" title="" alt=""/>
                    </div>
                    <div id="content_header_arrow">&raquo; <?php _e($title); ?></div>
                    <div style="clear: both;"></div>
                </div>

                <div id="content_separator"></div>
                <?php osc_show_flash_message('admin'); ?>

                <!-- add new page form -->
                <div id="settings_form">
                    <form name="comment_form" id="comment_form" action="<?php echo osc_admin_base_url(true); ?>" method="post" onSubmit="return checkForm()">
                        <input type="hidden" name="action" value="<?php echo $action_frm; ?>" />
                        <input type="hidden" name="page" value="comments" />
                        <?php PageForm::primary_input_hidden($comment); ?>

                        <div class="FormElement">
                            <div class="FormElementName">
                                <?php _e('Edit a comment on item:'); ?>
                                <?php $item = Item::newInstance()->findByPrimaryKey($comment['fk_i_item_id']) ; ?>
                                <b><?php echo $item['s_title'] ; ?></b>
                                ( <a href="<?php echo osc_item_url_ns( $comment['fk_i_item_id'] ) ; ?>" target="_blank"><?php _e('View') ; ?></a> |
                                <a href="<?php echo osc_admin_base_url(true) ; ?>?page=items&action=item_edit&id=<?php echo $item['pk_i_id'] ; ?>"><?php _e('Edit') ; ?></a> )
                            </div>
                        </div>

                        <div class="FormElement">
                            <div class="FormElementName"><?php _e('Title'); ?> <?php CommentForm::title_input_text($comment); ?>
                            </div>
                        </div>
                        <div class="FormElement">
                            <div class="FormElementName"><?php _e('Author'); ?> <?php CommentForm::author_input_text($comment); ?>
                                <?php if(isset($comment['fk_i_user_id']) && $comment['fk_i_user_id']!='') {
                                _e("It's a registered user") ; ?>
                                <a href="<?php echo osc_admin_base_url(true) ; ?>?page=users&action=edit&id=<?php echo $comment['fk_i_user_id'] ; ?>"><?php _e('Edit user') ; ?></a>
                                <?php }?>
                            </div>
                        </div>
                        <div class="FormElement">
                            <div class="FormElementName"><?php _e('Author\'s e-mail'); ?> <?php CommentForm::email_input_text($comment); ?>
                            </div>
                        </div>
                        <div class="FormElement">
                            <div class="FormElementName"><?php _e('Status'); ?>: <?php echo ( $comment['b_active'] ? __('ACTIVE') : __('INACTIVE') ) ; ?> ( <a href="<?php echo osc_admin_base_url( true ) ; ?>?page=comments&action=status&id=<?php echo $comment['pk_i_id'] ; ?>&value=<?php echo ( ( $comment['b_active'] == 1) ? 'INACTIVE' : 'ACTIVE' ) ; ?>"><?php echo ( ( $comment['b_active'] == 1 ) ? __('De-activate') : __('Activate') ) ; ?></a> )
                            </div>
                        </div>
                        <div class="FormElement">
                            <div class="FormElementName"><?php _e('Status'); ?>: <?php echo ( $comment['b_enabled'] ? __('ENABLED') : __('DISABLED') ) ; ?> ( <a href="<?php echo osc_admin_base_url( true ) ; ?>?page=comments&action=status&id=<?php echo $comment['pk_i_id'] ; ?>&value=<?php echo ( ( $comment['b_enabled'] == 1) ? 'DISABLE' : 'ENABLE' ) ; ?>"><?php echo ( ( $comment['b_enabled'] == 1 ) ? __('Disable') : __('Enable') ) ; ?></a> )
                            </div>
                        </div>
                        <div class="FormElement">
                            <div class="FormElementName"><?php _e('Comment'); ?></div>
                            <div class="FormElementInput">
                               <?php CommentForm::body_input_textarea($comment); ?>
                            </div>
                        </div>

                        <div class="clear50"></div>

                        <div class="FormElement">
                            <div class="FormElementName"></div>
                            <div class="FormElementInput">
                                <button class="formButton" type="button" onclick="window.location='<?php echo osc_admin_base_url(true);?>?page=comments';" ><?php _e('Cancel'); ?></button>
                                <button class="formButton" type="submit"><?php echo $btn_text; ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>