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
    
    $comment = __get('comment');
    
    if(isset($comment['pk_i_id'])) {
        //editing...
        $title = __("Edit comment") ;
        $action_frm = "comment_edit_post";
        $btn_text = osc_esc_html( __("Save"));
    } else {
        //adding...
        $title = __("Add a comment");
        $action_frm = "add_comment_post";
        $btn_text = osc_esc_html( __('Add'));
    }

    function customPageHeader() { ?>
        <h1><?php _e('Manage Comments') ; ?></h1>
<?php
    }
    osc_add_hook('admin_page_header','customPageHeader');

    function customPageTitle($string) {
        return sprintf(__(' '));
    }
    osc_add_filter('admin_title', 'customPageTitle');

    //customize Head
    function customHead() { ?>
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.validate.min.js') ; ?>"></script>
        <?php CommentForm::js_validation(true); 
    }
    osc_add_hook('admin_header','customHead');
    
    $comment = __get('comment');
    
?>
<?php osc_current_admin_theme_path( 'parts/header.php' ) ; ?>
<div id="help-box">
</div>
<h2 class="render-title"><?php echo $title; ?></h2>
<div id="language-form">
    <ul id="error_list"></ul>
    <form name="language_form" action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
        <input type="hidden" name="action" value="<?php echo $action_frm; ?>" />
        <input type="hidden" name="page" value="comments" />

        <div class="form-horizontal">
            <div class="form-row">
                <div class="form-label"><?php _e('Title'); ?></div>
                <div class="form-controls">
                    <?php CommentForm::title_input_text($comment); ?>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label"><?php _e('Author'); ?></div>
                <div class="form-controls">
                    <?php CommentForm::author_input_text($comment); ?>
                    <?php if(isset($comment['fk_i_user_id']) && $comment['fk_i_user_id']!='') {
                    _e("It's a registered user") ; ?>
                    <a href="<?php echo osc_admin_base_url(true) ; ?>?page=users&action=edit&id=<?php echo $comment['fk_i_user_id'] ; ?>"><?php _e('Edit user') ; ?></a>
                    <?php }?>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label"><?php _e("Author's e-mail"); ?></div>
                <div class="form-controls">
                    <?php CommentForm::email_input_text($comment); ?>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label"><?php _e('Status'); ?></div>
                <div class="form-controls">
                    <?php echo ( $comment['b_active'] ? __('ACTIVE') : __('INACTIVE') ) ; ?> ( <a href="<?php echo osc_admin_base_url( true ) ; ?>?page=comments&action=status&id=<?php echo $comment['pk_i_id'] ; ?>&value=<?php echo ( ( $comment['b_active'] == 1) ? 'INACTIVE' : 'ACTIVE' ) ; ?>"><?php echo ( ( $comment['b_active'] == 1 ) ? __('De-activate') : __('Activate') ) ; ?></a> )
                </div>
            </div>
            <div class="form-row">
                <div class="form-label"><?php _e('Status'); ?></div>
                <div class="form-controls">
                    <?php echo ( $comment['b_enabled'] ? __('ENABLED') : __('DISABLED') ) ; ?> ( <a href="<?php echo osc_admin_base_url( true ) ; ?>?page=comments&action=status&id=<?php echo $comment['pk_i_id'] ; ?>&value=<?php echo ( ( $comment['b_enabled'] == 1) ? 'DISABLE' : 'ENABLE' ) ; ?>"><?php echo ( ( $comment['b_enabled'] == 1 ) ? __('Disable') : __('Enable') ) ; ?></a> )
                </div>
            </div>
            <div class="form-row">
                <div class="form-label"><?php _e('Comment'); ?></div>
                <div class="form-controls">
                    <?php CommentForm::body_input_textarea($comment); ?>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <input type="submit" value="<?php echo $btn_text; ?>" class="btn btn-submit" />
        </div>
    </form>
</div>
<?php osc_current_admin_theme_path( 'parts/footer.php' ) ; ?>