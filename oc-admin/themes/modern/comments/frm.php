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
?>

<?php defined('ABS_PATH') or die( __('Invalid OSClass request.') ); ?>

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
			<div style="float: left;"><img src="<?php echo  osc_current_admin_theme_url('images/comments-icon2.png') ; ?>" /></div>
			<div id="content_header_arrow">&raquo; <?php _e($title); ?></div> 
			<div style="clear: both;"></div>
		</div>
		
		<div id="content_separator"></div>
		<?php osc_show_flash_message(); ?>
		
		<!-- add new page form -->
		<div id="settings_form">
			<form name="comment_form" id="comment_form" action="comments.php" method="post" onSubmit="return checkForm()">
				<input type="hidden" name="action" value="<?php echo $action_frm; ?>" />
				<?php PageForm::primary_input_hidden($comment); ?>

				<div class="FormElement">
				    <div class="FormElementName"><?php _e('Edit a comment on item:'); ?> 
				    <?php
				        $item = Item::newInstance()->findByPrimaryKey($comment['fk_i_item_id']);
				        echo '<b>'.$item['s_title'].'</b>';?>
				        
				        <a href="<?php osc_create_item_url($item, true);?>"><button><?php _e('View');?></button></a>
				        <a href="items.php?action=item_edit&id=<?php echo $item['pk_i_id'];?>"><button><?php _e('Edit');?></button></a>
					</div>
                </div>

				<div class="FormElement">
				    <div class="FormElementName"><?php _e('Title'); ?> <?php CommentForm::title_input_text($comment); ?>
					</div>
                </div>
                <div class="FormElement">
				    <div class="FormElementName"><?php _e('Author'); ?> <?php CommentForm::author_input_text($comment); ?>
				    <?php if(isset($comment['fk_i_user_id']) && $comment['fk_i_user_id']!='') { 
				    _e("It's a registered user.");?>
				    <a href="users.php?action=edit&id=<?php echo $comment['fk_i_user_id'];?>"><button><?php _e('Edit user');?></button></a>
				    <?php }?>
					</div>
                </div>
                <div class="FormElement">
				    <div class="FormElementName"><?php _e('Author\'s e-mail'); ?> <?php CommentForm::email_input_text($comment); ?>
					</div>
                </div>
                <div class="FormElement">
				    <div class="FormElementName"><?php _e('Status'); ?>: <?php echo $comment['e_status']; ?><a href="comments.php?action=status&id=<?php echo $comment['pk_i_id'];?>&value=<?php echo (($comment['e_status']=='ACTIVE')?'INACTIVE':'ACTIVE');?>"><button><?php echo (($comment['e_status']=='ACTIVE')?__('De-activate'):__('Activate'));?></button></a>
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
						<button class="formButton" type="button" onclick="window.location='pages.php';" ><?php _e('Cancel'); ?></button>
						<button class="formButton" type="submit"><?php echo $btn_text; ?></button>
					</div>
				</div>
			</form>
		</div>
	</div>
