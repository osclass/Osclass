<?php if ( ! defined('OC_ADMIN')) exit('Direct access is not allowed.');
/*
 * Copyright 2014 Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

    osc_enqueue_script('jquery-validate');
    osc_enqueue_script('colorpicker');
    osc_enqueue_style('colorpicker', osc_assets_url('js/colorpicker/css/colorpicker.css'));

    $maxPHPsize    = View::newInstance()->_get('max_size_upload');
    $imagickLoaded = extension_loaded('imagick');
    $aGD           = @gd_info();
    $freeType      = array_key_exists('FreeType Support', $aGD);

    //customize Head
    function customHead() { ?>
        <link rel="stylesheet" media="screen" type="text/css" href="<?php echo osc_assets_url('js/colorpicker/css/colorpicker.css'); ?>" />
        <script type="text/javascript">
            $(document).ready(function(){
                // Code for form validation
                $.validator.addMethod('regexp', function(value, element, param) {
                    return this.optional(element) || value.match(param);
                }, '<?php echo osc_esc_js( __('Size is not in the correct format') ); ?>');

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
                            required: '<?php echo osc_esc_js( __("Thumbnail size: this field is required")); ?>',
                            regexp: '<?php echo osc_esc_js( __("Thumbnail size: is not in the correct format")); ?>'
                        },
                        dimPreview: {
                            required: '<?php echo osc_esc_js( __("Preview size: this field is required")); ?>',
                            regexp: '<?php echo osc_esc_js( __("Preview size: is not in the correct format")); ?>'
                        },
                        dimNormal: {
                            required: '<?php echo osc_esc_js( __("Normal size: this field is required")); ?>',
                            regexp: '<?php echo osc_esc_js( __("Normal size: is not in the correct format")); ?>'
                        },
                        maxSizeKb: {
                            required: '<?php echo osc_esc_js( __("Maximum size: this field is required")); ?>',
                            digits: '<?php echo osc_esc_js( __("Maximum size: this field must only contain numeric characters")); ?>'
                        }
                    },
                    wrapper: "li",
                    errorLabelContainer: "#error_list",
                    invalidHandler: function(form, validator) {
                        $('html,body').animate({ scrollTop: $('h1').offset().top }, { duration: 250, easing: 'swing'});
                    },
                    submitHandler: function(form){
                        $('button[type=submit], input[type=submit]').attr('disabled', 'disabled');
                        form.submit();
                    }
                });

                $('#colorpickerField').ColorPicker({
                    onSubmit: function(hsb, hex, rgb, el) { },
                    onChange: function (hsb, hex, rgb) {
                        $('#colorpickerField').val(hex);
                    }
                });

                $('#watermark_none').bind('change', function() {
                    if( $(this).prop('checked') ) {
                        $('#watermark_text_box').hide();
                        $('#watermark_image_box').hide();
                        resetLayout();
                    }
                });

                // dialog bulk actions
                $("#dialog-watermark-warning").dialog({
                    autoOpen: false,
                    modal: true
                });

                $('#watermark_text').on('change', function() {
                    if( $(this).prop('checked') ) {
                        $('#watermark_text_box').show();
                        $('#watermark_image_box').hide();
                        if( !$('input[name="keep_original_image"]').prop('checked') ) {
                            $("#dialog-watermark-warning").dialog('open');
                        }
                        resetLayout();
                    }
                });

                $('#watermark_image').on('change', function() {
                    if( $(this).prop('checked') ) {
                        $('#watermark_text_box').hide();
                        $('#watermark_image_box').show();
                        if( !$('input[name="keep_original_image"]').prop('checked') ) {
                            $("#dialog-watermark-warning").dialog('open');
                        }
                        resetLayout();
                    }
                });

                $('input[name="keep_original_image"]').on("change",function() {
                    if( !$(this).prop('checked') ) {
                        if( !$('#watermark_none').prop('checked') ) {
                            $("#dialog-watermark-warning").dialog('open');
                        }
                        resetLayout();
                    }
                });
            });
        </script>
        <?php
    }
    osc_add_hook('admin_header','customHead', 10);

    function render_offset(){
        return 'row-offset';
    }

    function addHelp() {
        echo '<p>' . __('Manage the options for the images users can upload along with their listings. You can limit their size, the number of images per ad, include a watermark, etc.') . '</p>';
    }
    osc_add_hook('help_box','addHelp');

    osc_add_hook('admin_page_header','customPageHeader');
    function customPageHeader(){ ?>
        <h1><?php _e('Settings'); ?>
            <a href="#" class="btn ico ico-32 ico-help float-right"></a>
        </h1>
    <?php
    }

    function customPageTitle($string) {
        return sprintf(__('Media Settings &raquo; %s'), $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');

    osc_current_admin_theme_path( 'parts/header.php' ); ?>
<!--los input tienen una class para el tamaño ...-->
<div id="general-settings">
    <h2 class="render-title"><?php _e('Media Settings'); ?></h2>
    <ul id="error_list"></ul>
    <form name="media_form" action="<?php echo osc_admin_base_url(true); ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="page" value="settings" />
        <input type="hidden" name="action" value="media_post" />
        <fieldset>
            <div class="form-horizontal">
                <h2 class="render-title"><?php _e('Image sizes'); ?></h2>
                <div class="form-row">
                    <p><?php _e('The sizes listed below determine the maximum dimensions in pixels to use when uploading a image. Format: <b>Width</b> x <b>Height</b>.'); ?></p>
                    <div class="form-label"><?php _e('Thumbnail size'); ?></div>
                    <div class="form-controls"><input type="text" class="input-medium" name="dimThumbnail" value="<?php echo osc_esc_html( osc_thumbnail_dimensions() ); ?>" /></div>
                </div>
                <div class="form-row">
                    <div class="form-label"><?php _e('Preview size'); ?></div>
                    <div class="form-controls"><input type="text" class="input-medium" name="dimPreview" value="<?php echo osc_esc_html( osc_preview_dimensions() ); ?>" /></div>
                </div>
                <div class="form-row">
                    <div class="form-label"><?php _e('Normal size'); ?></div>
                    <div class="form-controls"><input type="text" class="input-medium"  name="dimNormal" value="<?php echo osc_esc_html( osc_normal_dimensions() ); ?>" /></div>
                </div>
                <div class="form-row">
                    <div class="form-label"><?php _e('Original size'); ?></div>
                    <div class="form-controls">
                        <div class="form-label-checkbox">
                            <input type="checkbox" id="keep_original_image" name="keep_original_image" value="1" <?php echo ( osc_keep_original_image() ? 'checked="checked"' : '' ); ?> />
                            <label for="keep_original_image"><?php _e('Keep original image, unaltered after uploading.'); ?></label>
                            <span class="help-box"><?php _e('Image may occupy more space than usual.'); ?></span>
                        </div>
                    </div>
                </div>
                <h2 class="render-title"><?php _e('Restrictions'); ?></h2>
                <div class="form-row">
                    <div class="form-label"><?php _e('Force JPEG'); ?></div>
                    <div class="form-controls">
                        <div class="form-label-checkbox">
                            <input type="checkbox" id="force_jpeg" name="force_jpeg" value="1" <?php echo ( osc_force_jpeg() ? 'checked="checked"' : '' ); ?> />
                            <label for="force_jpeg"><?php _e('Force JPEG extension.'); ?></label>
                            <span class="help-box"><?php _e('Uploaded images will be saved in JPG/JPEG format, it saves space but images will not have transparent background.'); ?></span>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label"><?php _e('Force aspect'); ?></div>
                    <div class="form-controls">
                        <div class="form-label-checkbox">
                            <input type="checkbox" id="force_aspect_image" name="force_aspect_image" value="1" <?php echo ( osc_force_aspect_image() ? 'checked="checked"' : '' ); ?> />
                            <label for="force_aspect_image"><?php _e('Force image aspect.'); ?></label>
                            <span class="help-box"><?php _e('No white background will be added to keep the size.'); ?></span>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label"><?php _e('Maximum size'); ?></div>
                    <div class="form-controls">
                        <input type="text" class="input-medium" name="maxSizeKb" value="<?php echo osc_esc_html( osc_max_size_kb() ); ?>" />
                        <span class="help-box"><?php _e('Size in KB'); ?></span>
                        <div class="flashmessage flashmessage-warning flashmessage-inline">
                            <p><?php printf( __('Maximum size PHP configuration allows: %d KB'), $maxPHPsize ); ?></p>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label"><?php _e('ImageMagick'); ?></div>
                    <div class="form-controls">
                        <div class="form-label-checkbox">
                            <input type="checkbox" name="use_imagick" value="1" <?php echo ( osc_use_imagick()?'checked="checked"':'' ); ?> <?php if( !$imagickLoaded ) echo 'disabled="disabled"'; ?> />
                            <label for="use_imagick"><?php _e('Use ImageMagick instead of GD library'); ?></label>
                        </div>
                        <?php if( !$imagickLoaded ) { ?>
                        <div class="flashmessage flashmessage-error flashmessage-inline">
                            <p><?php _e('ImageMagick library is not loaded'); ?></p>
                        </div>
                        <?php } ?>
                        <div class="help-box"><?php _e("It's faster and consumes less resources than GD library."); ?></div>
                    </div>
                </div>
                <h2 class="render-title"><?php _e('Watermark'); ?></h2>
                <div class="form-row">
                    <div class="form-label"><?php _e('Watermark type'); ?></div>
                    <div class="form-controls">
                        <div class="form-label-checkbox">
                            <input type="radio" id="watermark_none" name="watermark_type" value="none" <?php echo ( ( !osc_is_watermark_image() && !osc_is_watermark_text() ) ? 'checked="checked"' : '' ); ?> />
                            <label for="watermark_none"><?php _e('None'); ?></label>
                        </div>
                        <div class="form-label-checkbox">
                            <input type="radio" id="watermark_text" name="watermark_type" value="text" <?php echo ( osc_is_watermark_text() ? 'checked="checked"' : '' ); ?> <?php echo ( $freeType ? '' : 'disabled="disabled"' ); ?> />
                            <label for="watermark_text"><?php _e('Text'); ?></label>
                            <?php if( !$freeType ) { ?>
                            <div class="flashmessage flashmessage-inline error">
                                <p><?php printf( __('Freetype library is required. How to <a target="_blank" href="%s">install/configure</a>') , 'http://www.php.net/manual/en/image.installation.php' ); ?></p>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="form-label-checkbox">
                            <input type="radio" id="watermark_image" name="watermark_type" value="image" <?php echo ( osc_is_watermark_image() ? 'checked="checked"' : '' ); ?> />
                            <label for="watermark_image"><?php _e('Image'); ?></label>
                        </div>
                    </div>
                </div>
                <div id="watermark_text_box" class="table-backoffice-form" <?php echo ( osc_is_watermark_text() ? '' : 'style="display:none;"' ); ?>>
                    <h2 class="render-title"><?php _e('Watermark Text Settings'); ?></h2>
                    <div class="form-row">
                        <div class="form-label"><?php _e('Text'); ?></div>
                        <div class="form-controls">
                            <input type="text" class="large" name="watermark_text" value="<?php echo osc_esc_html( osc_watermark_text() ); ?>" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label"><?php _e('Color'); ?></div>
                        <div class="form-controls">
                            <input type="text" maxlength="6" id="colorpickerField" class="small" name="watermark_text_color" value="<?php echo osc_esc_html( osc_watermark_text_color() ); ?>" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label"><?php _e('Position'); ?></div>
                        <div class="form-controls">
                            <select name="watermark_text_place" id="watermark_text_place">
                                <option value="centre" <?php echo (osc_watermark_place() == 'centre') ? 'selected="true"' : ''; ?>><?php _e('Centre'); ?></option>
                                <option value="tl" <?php echo (osc_watermark_place() == 'tl') ? 'selected="true"' : ''; ?>><?php _e('Top Left'); ?></option>
                                <option value="tr" <?php echo (osc_watermark_place() == 'tr') ? 'selected="true"' : ''; ?>><?php _e('Top Right'); ?></option>
                                <option value="bl" <?php echo (osc_watermark_place() == 'bl') ? 'selected="true"' : ''; ?>><?php _e('Bottom Left'); ?></option>
                                <option value="br" <?php echo (osc_watermark_place() == 'br') ? 'selected="true"' : ''; ?>><?php _e('Bottom Right'); ?></option>
                            </select>
                        </div>
                    </div>
                </div>
                <div id="watermark_image_box" <?php echo ( osc_is_watermark_image() ? '' : 'style="display:none;"' ); ?>>
                    <h2 class="render-title"><?php _e('Watermark Image Settings'); ?></h2>
                    <div class="form-row">
                        <div class="form-label"><?php _e('Image'); ?></div>
                        <div class="form-controls">
                            <input type="file" name="watermark_image" id="watermark_image_file"/>
                            <?php if(osc_is_watermark_image()!='') { ?>
                                <div class="help-box"><img width="100px" src="<?php echo osc_base_url() . str_replace(osc_base_path(), '', osc_uploads_path()) . "watermark.png" ?>" /></div>
                            <?php }; ?>
                            <div class="help-box"><?php _e("It has to be a .PNG image"); ?></div>
                            <div class="help-box"><?php _e("Osclass doesn't check the watermark image size"); ?></div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label"><?php _e('Position'); ?></div>
                        <div class="form-controls">
                            <select name="watermark_image_place" id="watermark_image_place" >
                                <option value="centre" <?php echo (osc_watermark_place() == 'centre') ? 'selected="true"' : ''; ?>><?php _e('Centre'); ?></option>
                                <option value="tl" <?php echo (osc_watermark_place() == 'tl') ? 'selected="true"' : ''; ?>><?php _e('Top Left'); ?></option>
                                <option value="tr" <?php echo (osc_watermark_place() == 'tr') ? 'selected="true"' : ''; ?>><?php _e('Top Right'); ?></option>
                                <option value="bl" <?php echo (osc_watermark_place() == 'bl') ? 'selected="true"' : ''; ?>><?php _e('Bottom Left'); ?></option>
                                <option value="br" <?php echo (osc_watermark_place() == 'br') ? 'selected="true"' : ''; ?>><?php _e('Bottom Right'); ?></option>
                            </select>
                        </div>
                    </div>
                </div>
                <h2 class="render-title"><?php _e('Regenerate images'); ?></h2>
                <div class="form-row">
                    <div class="form-controls">
                    <p>
                        <?php _e("You can regenerate different image dimensions. If you have changed the dimension of thumbnails, preview or normal images, you might want to regenerate your images."); ?>
                    </p>
                    <a class="btn" href="<?php echo osc_admin_base_url(true) . '?page=settings&action=images_post'.'&'.osc_csrf_token_url(); ?>"><?php  _e('Regenerate'); ?></a>
                    </div>
                </div>
                <div class="clear"></div>
                <div class="form-actions">
                    <input type="submit" id="save_changes" value="<?php echo osc_esc_html( __('Save changes') ); ?>" class="btn btn-submit" />
                </div>
            </div>
        </fieldset>
    </form>
</div>
<div id="dialog-watermark-warning" title="<?php echo osc_esc_html(__('Recommendation')); ?>" class="has-form-actions hide">
    <div class="form-horizontal">
        <div class="form-row">
            <?php _e("We highly recommend you have the 'Keep original image' option active when you use watermarks."); ?>
        </div>
        <div class="form-actions">
            <div class="wrapper">
                <a class="btn float-right" href="javascript:void(0);" onclick="$('#dialog-watermark-warning').dialog('close');"><?php _e('Close'); ?></a>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>
<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>
