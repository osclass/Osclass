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
 *            the License, or (a
 * t your option) any later version.
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

    if(isset($locale['pk_c_code'])) {
        //editing...
        $edit = true ;
        $title = "Edit page" ;
        $action_frm = "edit_post" ;
    }

?>

<?php
    require_once LIB_PATH . 'osclass/classes/HTML.php';
    $enabledCtrl = new HTMLInputCheckbox('b_enabled', __('Enabled'));
    $enabledCtrl->setChecked($locale['b_enabled']);
    $enabledCtrl->setValue(1);
?>
<script>
	$(function() {
		// Here we include specific jQuery, jQuery UI and Datatables functions.
	});
</script>
		<div id="content">
			<div id="separator"></div>	
			
			<?php include_once $absolute_path . '/include/backoffice_menu.php'; ?>

		    <div id="right_column">
				<div id="content_header" class="content_header">
					<div style="float: left;"><img src="<?php echo  $current_theme; ?>/images/back_office/icon-language.png" /></div>
					<div id="content_header_arrow">&raquo; <?php echo __('Edit language'); ?></div> 
					<div style="clear: both;"></div>
				</div>
				
				<div id="content_separator"></div>
				<?php osc_show_flash_messages(); ?>
				
				<!-- add new plugin form -->
				<div id="settings_form">
					<form action="languages.php" method="post">
						<input type="hidden" name="action" value="<?php echo $action_frm ; ?>" />
						
                        <?php if ($edit) {
                            LanguageForm::primary_input_hidden($locale) ;
                        } ?>
						
						<div class="FormElement">
						    <div class="FormElementName"><?php echo __('Name'); ?></div>
                            <div class="FormElementInput">
                                <?php LanguageForm::name_input_text($locale) ; ?>
                            </div>
						</div>
						<div class="FormElement">
						    <div class="FormElementName"><?php echo __('Short name'); ?></div>
							<div class="FormElementInput">
                                <?php LanguageForm::short_name_input_text($locale) ; ?>
                            </div>
						</div>
    					<div class="FormElement">
						    <div class="FormElementName"><?php echo __('Description'); ?></div>
							<div class="FormElementInput">
                                <?php LanguageForm::description_input_text($locale) ; ?>
                            </div>
						</div>
						<div class="FormElement">
						    <div class="FormElementName"><?php echo __('Currency format'); ?></div>
							<div class="FormElementInput">
                                <?php LanguageForm::currency_format_input_text($locale) ; ?>
                            </div>
						</div>
						<div class="FormElement">
						    <div class="FormElementName"><?php echo __('Date format'); ?></div>
							<div class="FormElementInput">
                                <?php LanguageForm::date_format_input_text($locale) ; ?>
                            </div>
						</div>
						<div class="FormElement">
						    <div class="FormElementName"><?php echo __('Stop words'); ?></div>
							<div class="FormElementInput">
                                <?php LanguageForm::description_textarea($locale); ?>
                            </div>
						</div>
						<div class="FormElement">
						    <div class="FormElementName"></div>
							<div class="FormElementInput">
                                <?php LanguageForm::enabled_input_checkbox($locale); ?>&nbsp;<?php echo __('Enabled for the public website'); ?>
							</div>
						</div>
                        <div class="FormElement">
						    <div class="FormElementName"></div>
							<div class="FormElementInput">
                                <?php LanguageForm::enabled_bo_input_checkbox($locale); ?>&nbsp;<?php echo __('Enabled for the backoffice (oc-admin)'); ?>
							</div>
						</div>
						<div class="FormElement">
							<div class="FormElementName"></div>
							<div class="FormElementInput">
								<button class="formButton" type="button" onclick="window.location='languages.php';" ><?php echo __('Cancel'); ?></button>
								<button class="formButton" type="submit"><?php echo __('Save'); ?></button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
