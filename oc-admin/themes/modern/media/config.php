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
			    <?php
				/* this is header for right side. */ 
				?>
				<div id="content_header" class="content_header">
					<div style="float: left;"><img src="<?php echo  $current_theme;?>/images/back_office/media-config-icon.png" /></div>
					<div id="content_header_arrow">&raquo; <?php echo __('Configure Media'); ?></div> 
					<div style="clear: both;"></div>
				</div>

				<div id="content_separator"></div>
				<?php osc_showFlashMessages(); ?>
				
				<div style="border: 1px solid #ccc; background: #eee;">
					<div style="padding: 20px;">
				
						<div><?php echo __('Please set the preferred dimensions for all the images on the website. (in format WIDTHxHEIGHT, eg: 640x480)'); ?></div>
	
						<form action="categories.php" method="post">
						<input type="hidden" name="action" value="post-edit" />
						
						<fieldset>
							<legend><?php echo __('Restrictions'); ?></legend>
							<p>
							<label for="maxSize"><?php echo __('Maximum size in KB'); ?></label><br />
							<input type="text" name="name" id="maxSize" value="<?php echo $preferences['maxSizeKb']; ?>" />
							</p>
							
							<p>
							<label for="allowedExt"><?php echo __('Allowed format extensions (eg: png, jpg, gif)'); ?></label><br />
							<input type="text" name="name" id="allowedExt" value="<?php echo $preferences['allowedExt']; ?>" />
							</p>
						</fieldset>

						<fieldset>
							<legend><?php echo __('Path and URI'); ?></legend>
							
							<p>
							<label for="uploadsFolder"><?php echo __('Store uploads in this folder'); ?></label><br />
							<input type="text" name="uploadsFolder" id="uploadsFolder" value="<?php echo $preferences['uploadsFolder']; ?>" />
							</p>
							
							<p>
							<label for="uploadsURL"><?php echo __('Full URL path to files'); ?></label><br />
							<input type="text" name="uploadsURL" id="uploadsURL" value="<?php echo $preferences['uploadsURL']; ?>" />
							</p>
						
						</fieldset>
						
						<fieldset>
							<legend><?php echo __('Dimensions'); ?></legend>
							<p>
							<label for="thumbnail"><?php echo __('Thumbnail dimensions'); ?></label><br />
							<input type="text" name="name" id="thumbnail" value="<?php echo $preferences['dimThumbnail']; ?>" />
							</p>
							
							<p>
							<label for="preview"><?php echo __('Preview dimensions'); ?></label><br />
							<input type="text" name="preview" id="preview" value="<?php echo $preferences['dimPreview']; ?>" />
							</p>
							
							<p>
							<label for="normal"><?php echo __('Normal dimensions'); ?></label><br />
							<input type="text" name="normal" id="normal" value="<?php echo $preferences['dimNormal']; ?>" />
							</p>
						</fieldset>
						
						<input id="button_save" type="submit" value="<?php echo __('Update configuration'); ?>" />
						</form>			
					</div>
				</div>	
				<div style="clear: both;"></div>
