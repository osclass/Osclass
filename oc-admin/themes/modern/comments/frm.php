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
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.validate.min.js') ; ?>"></script>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <?php
            if(isset($comment['pk_i_id'])) {
                //editing...
                $title = __("Edit comment") ;
                $action_frm = "comment_edit_post";
                $btn_text = __("Save");
            } else {
                //adding...
                $title = __("Add a comment");
                $action_frm = "add_comment_post";
                $btn_text = __('Add');
            }
        ?>
        <?php CommentForm::js_validation(true); ?>
        <div id="content">
            <?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>
            <div id="right_column" class="right">
                <div class="header_title">
                    <h1 class="comments"><?php echo $title ; ?></h1>
                </div>                    
                <?php osc_show_flash_message('admin') ; ?>
                <!-- add new page form -->
                <div id="settings_form">
                    <ul id="error_list" style="display: none;"></ul>
                    <form name="comment_form" id="comment_form" action="<?php echo osc_admin_base_url(true); ?>" method="post" onSubmit="return checkForm()">
                        <input type="hidden" name="action" value="<?php echo $action_frm; ?>" />
                        <input type="hidden" name="page" value="comments" />
                        <?php CommentForm::primary_input_hidden($comment); ?>
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
                            <div class="FormElementName"><label><?php _e('Title'); ?></label> <?php CommentForm::title_input_text($comment); ?>
                            </div>
                        </div>
                        <div class="FormElement">
                            <div class="FormElementName"><label><?php _e('Author'); ?></label> <?php CommentForm::author_input_text($comment); ?>
                                <?php if(isset($comment['fk_i_user_id']) && $comment['fk_i_user_id']!='') {
                                _e("It's a registered user") ; ?>
                                <a href="<?php echo osc_admin_base_url(true) ; ?>?page=users&action=edit&id=<?php echo $comment['fk_i_user_id'] ; ?>"><?php _e('Edit user') ; ?></a>
                                <?php }?>
                            </div>
                        </div>
                        <div class="FormElement">
                            <div class="FormElementName"><label><?php _e('Author\'s e-mail'); ?></label> <?php CommentForm::email_input_text($comment); ?>
                            </div>
                        </div>
                        <div class="FormElement">
                            <div class="FormElementName"><label><?php _e('Status'); ?>:</label> <?php echo ( $comment['b_active'] ? __('ACTIVE') : __('INACTIVE') ) ; ?> ( <a href="<?php echo osc_admin_base_url( true ) ; ?>?page=comments&action=status&id=<?php echo $comment['pk_i_id'] ; ?>&value=<?php echo ( ( $comment['b_active'] == 1) ? 'INACTIVE' : 'ACTIVE' ) ; ?>"><?php echo ( ( $comment['b_active'] == 1 ) ? __('De-activate') : __('Activate') ) ; ?></a> )
                            </div>
                        </div>
                        <div class="FormElement">
                            <div class="FormElementName"><label><?php _e('Status'); ?>:</label> <?php echo ( $comment['b_enabled'] ? __('ENABLED') : __('DISABLED') ) ; ?> ( <a href="<?php echo osc_admin_base_url( true ) ; ?>?page=comments&action=status&id=<?php echo $comment['pk_i_id'] ; ?>&value=<?php echo ( ( $comment['b_enabled'] == 1) ? 'DISABLE' : 'ENABLE' ) ; ?>"><?php echo ( ( $comment['b_enabled'] == 1 ) ? __('Disable') : __('Enable') ) ; ?></a> )
                            </div>
                        </div>
                        <div class="FormElement">
                            <label><div class="FormElementName"><?php _e('Comment'); ?></div></label>
                            <div class="FormElementInput">
                               <?php CommentForm::body_input_textarea($comment); ?>
                            </div>
                        </div>

                        <div class="clear50"></div>

                        <div class="FormElement FormButtonsSubmit">
                            <div class="FormElementName"></div>
                            <div class="FormElementInput">
                                <input class="formButton" type="button" onclick="window.location='<?php echo osc_admin_base_url(true);?>?page=comments';" value="<?php _e('Cancel'); ?>" />
                                <input class="formButton" type="submit" value="<?php echo $btn_text; ?>" />
                            </div>
                        </div>
                    </form>
                </div>
                <div style="clear: both;"></div>
                </div>
        </div>
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>
