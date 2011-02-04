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
    if(isset($page['pk_i_id'])) {
        //editing...
        $edit = true ;
        $title = __("Edit email/alert") ;
        $action_frm = "edit_post";
        $btn_text = __("Edit");
    } else {
        //adding...
        $edit = false ;
        $title = __("Add an email/alert");
        $action_frm = "add_post";
        $btn_text = __('Add');
    }
?>
<script type="text/javascript" src="<?php echo osc_base_url() ; ?>/oc-includes/js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="<?php echo osc_base_url() ; ?>/oc-includes/js/jquery.autosave.js"></script>
<script type="text/javascript" src="<?php echo osc_base_url() ; ?>/oc-includes/js/jquery.timers.js"></script>
<script type="text/javascript">
    tinyMCE.init({
        mode : "textareas",
        theme : "advanced",
        skin: "o2k7",
        width: "70%",
        height: "140px",
        skin_variant : "silver",
        theme_advanced_buttons1 : "bold,italic,underline,separator,undo,redo,separator,justifycenter,justifyright,justifyfull,separator,bullist,numlist,separator,link,unlink,separator,image,code",
        theme_advanced_buttons2 : "",
        theme_advanced_buttons3 : "",
        theme_advanced_toolbar_align : "left",
        theme_advanced_toolbar_location : "top",
        plugins : "media",
    theme_advanced_buttons1_add : "media"
    });
</script>
<?php
    $fields = array(
        array('name' => 's_internal_name', 'error_msg' => __('You have to write an unique internal name.'))
    );
    osc_check_form_js($fields);
?>
<div id="content">
    <div id="separator"></div>
    <?php include_once $absolute_path . '/include/backoffice_menu.php'; ?>
    <div id="right_column">
        <div id="content_header" class="content_header">
            <div style="float: left;"><img src="<?php echo $current_theme; ?>/images/back_office/pages-icon.png" /></div>
            <div id="content_header_arrow">&raquo; <?php _e($title); ?></div>
            <div style="clear: both;"></div>
        </div>
        <div id="content_separator"></div>
        <?php osc_show_flash_messages('admin') ; ?>
        <!-- add new page form -->
        <div id="settings_form">
            <form name="emails_form" id="emails_form" action="emails.php" method="post" onSubmit="return checkForm()">
                <input type="hidden" name="action" value="<?php echo $action_frm; ?>" />
                <?php PageForm::primary_input_hidden($page); ?>
                <div class="FormElement">
                    <div class="FormElementName">
                        <?php _e('Internal Name (Short name to easily identify this email/alert)'); ?>
                    </div>
                    <div class="FormElementInput">
                       <?php PageForm::internal_name_input_text($page); ?>
                    </div>
                </div>
                <div class="clear50"></div>
                <?php
                    $locales = Locale::newInstance()->listAllEnabled();
                    PageForm::multilanguage_name_description($locales, $page);
                ?>
                <div class="FormElement">
                    <div class="FormElementName"></div>
                    <div class="FormElementInput">
                        <button class="formButton" type="button" onclick="window.location='emails.php';" ><?php echo __('Cancel'); ?></button>
                        <button class="formButton" type="submit"><?php echo $btn_text; ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
