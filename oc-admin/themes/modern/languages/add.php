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
					<div id="content_header_arrow">&raquo; <?php echo __('Add a language'); ?></div> 
					<div style="clear: both;"></div>
				</div>
				
				<div id="content_separator"></div>
				<?php osc_showFlashMessages(); ?>
				
				<!-- add new plugin form -->
				<div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
					<div style="padding: 20px;">

						<?php if(is_writable(TRANSLATIONS_PATH)): ?>
						
						<form action="languages.php" method="POST" enctype="multipart/form-data">
						<input type="hidden" name="action" value="add_post" />
						
						<p>
						<label for="package"><?php echo __('Language package'); ?> (.zip)</label>
						<input type="file" name="package" id="package" />
						</p>
						
						<input id="button_save" type="submit" value="<?php echo __('Upload translation'); ?>" />
						
						</form>
						
						<?php else: ?>
						
						<p>
						<?php echo __('The translations folder '); ?> (<?php echo TRANSLATIONS_PATH; ?>) <?php echo __(' is not writable on your server, this'); ?> <span class="OSClass"><?php echo __('OSClass'); ?></span> <?php echo __("can't upload translations from the administration panel. Please copy the translation package using another technique (FTP, SSH) or make the mentioned translations folder writable."); ?>
						</p>
						<p>
						<?php echo __('To make a directory writable under UNIX execute this command from the shell:'); ?>
						</p>
						<div style="background-color: white; border: 1px solid black; padding: 8px;">
						chmod a+w <?php echo TRANSLATIONS_PATH; ?>
						</div>
						
						<?php endif; ?>
					
					</div>
				</div>
			</div>
		</div>
