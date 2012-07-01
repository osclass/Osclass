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

	 $maxPHPsize    = View::newInstance()->_get('max_size_upload') ;
	 $imagickLoaded = extension_loaded('imagick') ;
	 $aGD           = @gd_info() ;
	 $freeType      = array_key_exists('FreeType Support', $aGD) ;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()) ; ?>">
	<head>
		<?php osc_current_admin_theme_path('head.php') ; ?>
		<script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.validate.min.js') ; ?>"></script>
		<link rel="stylesheet" media="screen" type="text/css" href="<?php echo osc_current_admin_theme_js_url('colorpicker/css/colorpicker.css') ; ?>" />
		<script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('colorpicker/js/colorpicker.js') ; ?>"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				// Code for form validation
				$.validator.addMethod('regexp', function(value, element, param) {
					return this.optional(element) || value.match(param);
				},
				'<?php echo osc_esc_js( __('Size is not in the correct format') ); ?>');

				$("form[name=media_form]").validate({
					rules: {
						dimThumbnail: {
							required: true,
							regexp: /^[0-9]+x[0-9]+$/i
						},
						dimPreview: {
							required: true,
							regexp: /^[0-9]+x[0-9]+$/i
						},
						dimNormal: {
							required: true,
							regexp: /^[0-9]+x[0-9]+$/i
						},
						maxSizeKb: {
							required: true,
							digits: true
						}
					},
					messages: {
						dimThumbnail: {
							required: '<?php echo osc_esc_js ( __("Thumbnail size: this field is required") ); ?>.',
							regexp: '<?php echo osc_esc_js ( __("Thumbnail size: is not in the correct format") ); ?>.'
						},
						dimPreview: {
							required: '<?php echo osc_esc_js ( __("Preview size: this field is required") ); ?>.',
							regexp: '<?php echo osc_esc_js ( __("Preview size: is not in the correct format") ); ?>.'
						},
						dimNormal: {
							required: '<?php echo osc_esc_js ( __("Normal size: this field is required") ); ?>.',
							regexp: '<?php echo osc_esc_js ( __("Normal size: is not in the correct format") ); ?>.'
						},
						maxSizeKb: {
							required: '<?php echo osc_esc_js ( __("Maximun size: this field is required") ); ?>.',
							digits: '<?php echo osc_esc_js ( __("Maximun size: this field has to be numeric only") ); ?>.'
						}
					},
					wrapper: "li",
					errorLabelContainer: "#error_list",
					invalidHandler: function(form, validator) {
						$('html,body').animate({ scrollTop: $('h1').offset().top }, { duration: 250, easing: 'swing'});
					}
				});

				$('#colorpickerField').ColorPicker({
					onSubmit: function(hsb, hex, rgb, el) { },
					onChange: function (hsb, hex, rgb) {
						$('#colorpickerField').val(hex) ;
					}
				}) ;

				$('#watermark_none').bind('change', function() {
					if( $(this).attr('checked') ) {
						$('#watermark_text_box').hide() ;
						$('#watermark_image_box').hide() ;
					}
				}) ;

				$('#watermark_text').bind('change', function() {
					if( $(this).attr('checked') ) {
						$('#watermark_text_box').show() ;
						$('#watermark_image_box').hide() ;
						if( !$('input[name="keep_original_image"]').attr('checked') ) {
							alert('<?php echo osc_esc_js ( __("It's highly recommended to have 'Keep original image' option active when you use watermarks.") ) ; ?>') ;
						}
					}
				}) ;

				$('#watermark_image').bind('change', function() {
					if( $(this).attr('checked') ) {
						$('#watermark_text_box').hide() ;
						$('#watermark_image_box').show() ;
						if( !$('input[name="keep_original_image"]').attr('checked') ) {
							alert('<?php echo osc_esc_js( __("It's highly recommended to have 'Keep original image' option active when you use watermarks.") ) ; ?>') ;
						}
					}
				}) ;

				$('input[name="keep_original_image"]').change(function() {
					if( !$(this).attr('checked') ) {
						if( !$('#watermark_none').attr('checked') ) {
							alert('<?php echo osc_esc_js( __("It's highly recommended to have 'Keep original image' option active when you use watermarks.") ) ; ?>') ;
						}
					}
				}) ;
			}) ;

		</script>
	</head>
	<body>
		<?php osc_current_admin_theme_path('header.php') ; ?>
		<!-- container -->
		<div id="content">
			<?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>
			<!-- right container -->
			<div class="right">
				<div class="header_title">
					<h1 class="media"><?php _e('Media Settings') ; ?></h1>
				</div>
				<?php osc_show_flash_message('admin') ; ?>
				<!-- media settings -->
				<div class="settings media">
					<!-- media form -->
					<ul id="error_list" style="display: none;"></ul>
					<form name="media_form" action="<?php echo osc_admin_base_url(true) ; ?>" method="post" enctype="multipart/form-data">
						<input type="hidden" name="page" value="settings" />
						<input type="hidden" name="action" value="media_post" />
						<fieldset>
							<table class="table-backoffice-form">
								<tbody>
								<tr>
									<td colspan="2"><h2><?php _e('Images sizes') ; ?></h2></td>
								</tr>
								<tr>
									<td colspan="2"><?php _e('The sizes listed below determine the maximum dimensions in pixels to use when uploading a image. Format: <b>Width</b> x <b>Height</b>.') ; ?></td>
								</tr>
								<tr>
									<td class="labeled"><?php _e('Thumbnail size') ; ?></td>
									<td>
										<input type="text" class="small" name="dimThumbnail" value="<?php echo osc_esc_html( osc_thumbnail_dimensions() ) ; ?>" />
									</td>
								</tr>
								<tr>
									<td><?php _e('Preview size') ; ?></td>
									<td>
										<input type="text" class="small" name="dimPreview" value="<?php echo osc_esc_html( osc_preview_dimensions() ) ; ?>" />
									</td>
								</tr>
								<tr>
									<td><?php _e('Normal size') ; ?></td>
									<td>
										<input type="text" class="small"  name="dimNormal" value="<?php echo osc_esc_html( osc_normal_dimensions() ) ; ?>" />
									</td>
								</tr>
								<tr>
									<td><?php _e('Original image') ; ?></td>
									<td>
										<input type="checkbox" name="keep_original_image" value="1" <?php if ( osc_keep_original_image() ) echo 'checked="checked"'; ?> />
										<?php _e('Keep original image, unaltered after uploading.') ; ?>
										<span class="help-box"><?php _e('It might occupy more space than usual.') ; ?></span>
									</td>
								</tr>
								<tr class="separate">
									<td colspan="2"><h2><?php _e('Restrictions') ; ?></h2></td>
								</tr>
								<tr>
									<td><?php _e('Maximum size') ; ?></td>
									<td>
										<input type="text" class="medium" name="maxSizeKb" value="<?php echo osc_esc_html( osc_max_size_kb() ) ; ?>" />
										<span class="help-box"><?php _e('Size in KB') ; ?></span>
										<div class="FlashMessage FlashMessage-inline warning">
											<p><?php printf( __('Maximum size PHP configuration allows: %d KB'), $maxPHPsize ) ; ?></p>
										</div>
									</td>
								</tr>
								<tr>
									<td><?php _e('Allowed formats') ; ?></td>
									<td>
										<input type="text" class="medium" name="allowedExt" value="<?php echo osc_esc_html( osc_allowed_extension() ) ; ?>" />
										<span class="help-box"><?php _e('For example: jpg, png, gif') ; ?></span>
									</td>
								</tr>
								<tr>
									<td><?php _e('ImageMagick') ; ?></td>
									<td>
										<input type="checkbox" name="use_imagick" value="1" <?php if ( osc_use_imagick() ) echo 'checked="checked"'; ?> <?php if( !$imagickLoaded ) echo 'disabled="disabled"' ; ?> />
										<?php _e('Use ImageMagick instead of GD library') ; ?>
										<?php if( !$imagickLoaded ) { ?>
										<div class="FlashMessage FlashMessage-inline error">
											<p><?php _e('ImageMagick library is not loaded') ; ?></p>
										</div>
										<?php } ?>
										<div class="help-box"><?php _e("It's faster and consumes less resources than GD library.") ; ?></div>
									</td>
								</tr>
								<tr class="separate">
									<td colspan="2"><h2><?php _e('Watermark') ; ?></h2></td>
								</tr>
								<tr>
									<td><?php _e('Watermark type'); ?></td>
									<td>
										<div>
											<input type="radio" id="watermark_none" name="watermark_type" value="none" <?php if ( !osc_is_watermark_image() && !osc_is_watermark_text() ) echo 'checked="checked"' ; ?> />
											<?php _e('None') ; ?>
										</div>
										<div>
											<input type="radio" id="watermark_text" name="watermark_type" value="text" <?php if ( osc_is_watermark_text() ) echo 'checked="checked"' ; ?> <?php if ( !$freeType ) echo 'disabled="disabled"' ; ?> />
											<?php _e('Text') ; ?>
											<?php if( !$freeType ) { ?>
											<div class="FlashMessage FlashMessage-inline error">
												<p><?php printf( __('Freetype library is required. How to <a target="_blank" href="%s">install/configure</a>') , 'http://www.php.net/manual/en/image.installation.php' ) ; ?></p>
											</div>
											<?php } ?>
										</div>
										<div>
											<input type="radio" id="watermark_image" name="watermark_type" value="image" <?php if ( osc_is_watermark_image() ) echo 'checked="checked"' ; ?> />
											<?php _e('Image') ; ?>
										</div>
									</td>
								</tr>
							</tbody>
							<tbody id="watermark_text_box" class="table-backoffice-form" <?php if  ( !osc_is_watermark_text() )  echo 'style="display:none;"' ; ?>>
								<tr class="separate">
									<td colspan="2"><h3><?php _e('Watermark Text Settings') ; ?></h3></td>
								</tr>
								<tr>
									<td class="labeled"><?php _e('Text') ; ?></td>
									<td>
										<input type="text" class="large" name="watermark_text" value="<?php echo osc_esc_html( osc_watermark_text() ) ; ?>" />
									</td>
								</tr>
								<tr>
									<td class="labeled"><?php _e('Color') ; ?></td>
									<td>
										<input type="text" maxlength="6" id="colorpickerField" class="small" name="watermark_text_color" value="<?php echo osc_esc_html( osc_watermark_text_color() ) ; ?>" />
									</td>
								</tr>
								<tr>
									<td class="labeled"><?php _e('Position') ; ?></td>
									<td>
										<select name="watermark_text_place" id="watermark_text_place">
											<option value="centre" <?php echo (osc_watermark_place() == 'centre') ? 'selected="selected"' : '' ; ?>><?php _e('Centre') ; ?></option>
											<option value="tl" <?php echo (osc_watermark_place() == 'tl') ? 'selected="selected"' : '' ; ?>><?php _e('Top Left') ; ?></option>
											<option value="tr" <?php echo (osc_watermark_place() == 'tr') ? 'selected="selected"' : '' ; ?>><?php _e('Top Right') ; ?></option>
											<option value="bl" <?php echo (osc_watermark_place() == 'bl') ? 'selected="selected"' : '' ; ?>><?php _e('Bottom Left') ; ?></option>
											<option value="br" <?php echo (osc_watermark_place() == 'br') ? 'selected="selected"' : '' ; ?>><?php _e('Bottom Right') ; ?></option>
										</select>
									</td>
								</tr>
								</tbody>
								 <tbody id="watermark_image_box" class="table-backoffice-form" <?php if ( !osc_is_watermark_image() ) echo 'style="display:none;"' ; ?>>
									<tr class="separate">
										<td colspan="2"><h3><?php _e('Watermark Image Settings') ; ?></h3></td>
									</tr>
									<tr>
										<td class="labeled"><?php _e('Image'); ?></td>
										<td>
											<input type="file" name="watermark_image"/>
											<?php if(osc_is_watermark_image()!='') { ?>
												<div class="help-box"><img width="100px" src="<?php echo osc_base_url()."oc-content/uploads/watermark.png" ?>" /></div>
											<?php }; ?>
											<div class="help-box"><?php _e("It has to be a .PNG image") ; ?></div>
											<div class="help-box"><?php _e("OSClass doesn't check the watermark image size") ; ?></div>
										</td>
									</tr>
									<tr>
										<td class="labeled"><?php _e('Position'); ?></td>
										<td>
											<select name="watermark_image_place" id="watermark_image_place" >
												<option value="centre" <?php echo (osc_watermark_place() == 'centre') ? 'selected="selected"' : '' ; ?>><?php _e('Centre') ; ?></option>
												<option value="tl" <?php echo (osc_watermark_place() == 'tl') ? 'selected="selected"' : '' ; ?>><?php _e('Top Left') ; ?></option>
												<option value="tr" <?php echo (osc_watermark_place() == 'tr') ? 'selected="selected"' : '' ; ?>><?php _e('Top Right') ; ?></option>
												<option value="bl" <?php echo (osc_watermark_place() == 'bl') ? 'selected="selected"' : '' ; ?>><?php _e('Bottom Left') ; ?></option>
												<option value="br" <?php echo (osc_watermark_place() == 'br') ? 'selected="selected"' : '' ; ?>><?php _e('Bottom Right') ; ?></option>
											</select>
										</td>
									</tr>
								</tbody>
								<tbody>
								<tr class="separate">
									<td></td>
									<td>
										<input type="submit" value="<?php echo osc_esc_html( __('Save changes') ) ; ?>" />
									</td>
								</tr>
							</tbody>
							</table>
						</fieldset>
					</form>
					<!-- /media form -->
					<!-- regenerate images -->
					<form action="<?php echo osc_admin_base_url(true); ?>" method="post">
						<input type="hidden" name="action" value="images_post" />
						<input type="hidden" name="page" value="settings" />
						<fieldset>
							<h2><?php _e('Regenerate images') ; ?></h2>
							<p class="text">
								<?php _e("You can regenerate your different image dimensions. If you have changed the dimension of thumbnails, preview or normal images, you might want to regenerate your images.") ; ?>
							</p>
							<div class="actions-nomargin">
								<input type="submit" value="<?php echo osc_esc_html( __('Regenerate') ) ; ?>" />
							</div>
						</fieldset>
					</form>
					<div class="clear"></div>
					<!-- /regenerate images -->
				</div>
				<!-- /media settings -->
			</div>
			<!-- /right container -->
		</div>
		<!-- /container -->
		<?php osc_current_admin_theme_path('footer.php') ; ?>
	</body>
</html>
