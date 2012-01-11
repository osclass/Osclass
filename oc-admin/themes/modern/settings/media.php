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
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
        <link rel="stylesheet" media="screen" type="text/css" href="<?php echo osc_current_admin_theme_js_url()?>colorpicker/css/colorpicker.css" />
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url()?>colorpicker/js/colorpicker.js"></script>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <div id="update_version" style="display:none;"></div>
        <div class="Header"><?php _e('Media settings'); ?></div>
        <div id="content">
            <div id="separator"></div>
            <?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>
            <div id="right_column">
                <div id="content_header" class="content_header">
                    <div style="float: left;">
                        <img src="<?php echo osc_current_admin_theme_url('images/media-config-icon.png') ; ?>" title="" alt=""/>
                    </div>
                    <div id="content_header_arrow">&raquo; <?php _e('Configure media') ; ?></div>
                    <div style="clear: both;"></div>
                </div>
                <div id="content_separator"></div>
                <?php osc_show_flash_message('admin') ; ?>
                <div style="border: 1px solid #ccc; background: #eee;">
                    <div style="padding: 20px;">
                        <div><?php _e('Please set the preferred dimensions for all the images on the website. (format: WIDTHxHEIGHT, eg: 640x480)'); ?></div>
                        <form action="<?php echo osc_admin_base_url(true); ?>" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="page" value="settings" />
                            <input type="hidden" name="action" value="media_post" />
                            <fieldset>
                                <legend><?php _e('Restrictions'); ?></legend>
                                <p>
                                    <label for="maxSize"><?php _e('Maximum size, in KB'); ?></label><br />
                                    <input type="text" name="maxSizeKb" id="maxSize" value="<?php echo osc_esc_html(osc_max_size_kb()) ; ?>" />
                                </p>

                                <p>
                                    <label for="allowedExt"><?php _e('Allowed format extensions (eg: png, jpg, gif)'); ?></label><br />
                                    <input type="text" name="allowedExt" id="allowedExt" value="<?php echo osc_esc_html(osc_allowed_extension()) ; ?>" />
                                </p>
                                <?php if(extension_loaded('imagick')) { ?>
                                <p>
                                    <input id="use_imagick" type="checkbox" name="use_imagick" value="1" <?php echo ( osc_use_imagick() ) ? 'checked' : '' ; ?>/><label for="use_imagick"><?php _e('Use imagick instead of GD') ; ?></label>
                                    <br />
                                    <?php _e('It\'s faster and consume less resources than GD library'); ?>
                                </p>
                                <?php }; ?>
                            </fieldset>

                            <fieldset>
                                <legend><?php _e('Dimensions'); ?></legend>
                                <p>
                                    <label for="thumbnail"><?php _e('Thumbnail dimensions'); ?></label><br />
                                    <input type="text" name="dimThumbnail" id="thumbnail" value="<?php echo osc_esc_html(osc_thumbnail_dimensions()) ; ?>" />
                                </p>

                                <p>
                                    <label for="preview"><?php _e('Preview dimensions'); ?></label><br />
                                    <input type="text" name="dimPreview" id="preview" value="<?php echo osc_esc_html(osc_preview_dimensions()) ; ?>" />
                                </p>

                                <p>
                                    <label for="normal"><?php _e('Normal dimensions'); ?></label><br />
                                    <input type="text" name="dimNormal" id="normal" value="<?php echo osc_esc_html(osc_normal_dimensions()) ; ?>" />
                                </p>

                                <p>
                                    <input id="keep_original_image" type="checkbox" name="keep_original_image" value="1" <?php echo ( osc_keep_original_image() ) ? 'checked' : '' ; ?>/><label for="keep_original_image"><?php _e('Keep original image') ; ?></label>
                                    <br />
                                    <?php _e('Keeping original image files requires extra storage. This option ensures that the original quality of the file is un-altered. Be careful when using this option.'); ?>
                                </p>
                            </fieldset>
                            <fieldset>
                                <legend><?php _e('Watermark settings'); ?></legend>
                                <p style="float:left;">
                                    <input type="radio" id="watermark_none" <?php if(!osc_is_watermark_image() && !osc_is_watermark_text() ){echo "checked";} ?> name="watermark_type" value="none"/><label for="watermark_none">None watermark</label>
                                </p>
                                <p style="float:left;">
                                    <input type="radio" id="watermark_text" <?php if(osc_is_watermark_text()){echo "checked";} ?> name="watermark_type" value="text"/><label for="watermark_text">Watermark text</label>
                                </p>
                                <p style="float:left;">
                                    <input type="radio" id="watermark_image" <?php if(osc_is_watermark_image()){echo "checked";} ?> name="watermark_type" value="image"/><label for="watermark_image">Watermark image</label>
                                </p>

                                <div style="clear:both;"></div>

                                <div class="watermark_text" style="<?php if(!osc_is_watermark_text()){echo "display:none;";}?>">
                                    <?php $gd = @gd_info();
                                        if(@$gd["FreeType Support"] == true) {
                                    ?>

                                    <p>
                                        <label for="watermark_color"><?php _e('Watermark color'); ?></label><br />
                                        <input type="text" maxlength="6" id="colorpickerField" value="<?php echo osc_esc_html(osc_watermark_text_color()); ?>" name="watermark_text_color"/>
                                    </p>
                                    <p>
                                        <label for="watermark_text"><?php _e('Watermark text'); ?></label><br />
                                        <input type="text" name="watermark_text" value="<?php echo osc_esc_html(osc_watermark_text()); ?>"/>
                                    </p>
                                    <p>
                                        <label><?php _e('Watermark place'); ?></label>
                                        <select name="watermark_text_place" id="watermark_text_place" >
                                            <option value="centre" <?php echo (osc_watermark_place() == 'centre') ? 'selected="true"' : '' ; ?>><?php _e('Centre') ; ?></option>
                                            <option value="tl" <?php echo (osc_watermark_place() == 'tl') ? 'selected="true"' : '' ; ?>><?php _e('Top Left') ; ?></option>
                                            <option value="tr" <?php echo (osc_watermark_place() == 'tr') ? 'selected="true"' : '' ; ?>><?php _e('Top Right') ; ?></option>
                                            <option value="bl" <?php echo (osc_watermark_place() == 'bl') ? 'selected="true"' : '' ; ?>><?php _e('Bottom Left') ; ?></option>
                                            <option value="br" <?php echo (osc_watermark_place() == 'br') ? 'selected="true"' : '' ; ?>><?php _e('Bottom Right') ; ?></option>
                                        </select>
                                    </p>
                                    <?php }else{ ?>
                                    <div id="flash_message">
                                        <p><?php _e('Freetype library is required. How to'); ?> <a target="_blank" href="http://www.php.net/manual/en/image.installation.php"><?php _e('install/configure'); ?></a></p>
                                    </div>
                                    <?php } ?>
                                </div>

                                <div class="watermark_image" style="<?php if(!osc_is_watermark_image()){echo "display:none;";}?>">
                                    <p>
                                        <label for="watermark_image"><?php _e('Watermark image'); ?></label><br />
                                        <input type="file" name="watermark_image"/>
                                        *<?php _e("Notice that OSClass don't take care about size of watermark image");?>.
                                    </p>
                                    <p>
                                        <label><?php _e('Watermark place'); ?></label>
                                        <select name="watermark_image_place" id="watermark_image_place" >
                                            <option value="centre" <?php echo (osc_watermark_place() == 'centre') ? 'selected="true"' : '' ; ?>><?php _e('Centre') ; ?></option>
                                            <option value="tl" <?php echo (osc_watermark_place() == 'tl') ? 'selected="true"' : '' ; ?>><?php _e('Top Left') ; ?></option>
                                            <option value="tr" <?php echo (osc_watermark_place() == 'tr') ? 'selected="true"' : '' ; ?>><?php _e('Top Right') ; ?></option>
                                            <option value="bl" <?php echo (osc_watermark_place() == 'bl') ? 'selected="true"' : '' ; ?>><?php _e('Bottom Left') ; ?></option>
                                            <option value="br" <?php echo (osc_watermark_place() == 'br') ? 'selected="true"' : '' ; ?>><?php _e('Bottom Right') ; ?></option>
                                        </select>
                                    </p>
                                </div>

                            </fieldset>
                            <input id="button_save" type="submit" value="<?php osc_esc_html(_e('Update')); ?>" />
                        </form>
                    </div>
                </div>
                <div style="clear: both;"></div>
            </div> <!-- end of right column -->
        </div><!-- end of container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
        <script type="text/javascript">
            $('input#watermark_none').change(function(){
                if( $(this).attr('checked') ){
                    $('.watermark_text').hide();
                    $('.watermark_image').hide();
                }
            });
            $('#watermark_text').change(function(){
                if($(this).checked != 'undefined'){
                    $('.watermark_text').show();
                    $('.watermark_image').hide();
                    if( !$('input#keep_original_image').attr('checked') ) {
                        alert("<?php _e("Is highly recommended to have 'Keep original image' option active when you want watermarks."); ?>");
                    }
                }
            });
            $('#watermark_image').change(function(){
                if($(this).checked != 'undefined'){
                    $('.watermark_image').show();
                    $('.watermark_text').hide();
                    if( !$('input#keep_original_image').attr('checked') ) {
                        alert("<?php _e("Is highly recommended to have 'Keep original image' option active when you want watermarks."); ?>");
                    }
                }
            });
            $('#keep_original_image').change(function(){
                if( !$(this).attr('checked') ){
                    if( !$('#watermark_none').attr('checked') ) {
                        alert("<?php _e("Is highly recommended to have 'Keep original image' option active when you want watermarks."); ?>");
                    }
                }
            });
            $(document).ready(function() {
                $('#colorpickerField').ColorPicker({
                    onSubmit: function(hsb, hex, rgb, el) {
                    },onChange: function (hsb, hex, rgb) {
                        $('#colorpickerField').val(hex);
                    }

                });
            });
            
        </script>
    </body>
</html>