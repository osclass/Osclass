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

<div id="content">
    <div id="separator"></div>

    <?php include_once $absolute_path . '/include/backoffice_menu.php'; ?>

    <div id="right_column">

        <div id="content_header" class="content_header">
            <div style="float: left;"><img src="<?php echo  $current_theme ; ?>/images/back_office/media-config-icon.png" /></div>
            <div id="content_header_arrow">&raquo; <?php _e('Configure Media') ; ?></div>
            <div style="clear: both;"></div>
        </div>

        <div id="content_separator"></div>
        <?php osc_showFlashMessages() ; ?>

        <div style="border: 1px solid #ccc; background: #eee;">
            <div style="padding: 20px;">

                <div><?php _e('Please set the preferred dimensions for all the images on the website. (in format WIDTHxHEIGHT, eg: 640x480)'); ?></div>

                <form action="media.php" method="post">
                    <input type="hidden" name="action" value="config_post" />

                    <fieldset>
                        <legend><?php _e('Restrictions'); ?></legend>
                        <p>
                        <label for="maxSize"><?php _e('Maximum size in KB'); ?></label><br />
                        <input type="text" name="maxSizeKb" id="maxSize" value="<?php echo osc_max_size_kb() ; ?>" />
                        </p>

                        <p>
                        <label for="allowedExt"><?php _e('Allowed format extensions (eg: png, jpg, gif)'); ?></label><br />
                        <input type="text" name="allowedExt" id="allowedExt" value="<?php echo osc_allowed_extension() ; ?>" />
                        </p>
                    </fieldset>

                    <fieldset>
                        <legend><?php _e('Dimensions'); ?></legend>
                        <p>
                            <label for="thumbnail"><?php _e('Thumbnail dimensions'); ?></label><br />
                            <input type="text" name="dimThumbnail" id="thumbnail" value="<?php echo osc_thumbnail_dimensions() ; ?>" />
                        </p>

                        <p>
                            <label for="preview"><?php _e('Preview dimensions'); ?></label><br />
                            <input type="text" name="dimPreview" id="preview" value="<?php echo osc_preview_dimensions() ; ?>" />
                        </p>

                        <p>
                            <label for="normal"><?php _e('Normal dimensions'); ?></label><br />
                            <input type="text" name="dimNormal" id="normal" value="<?php echo osc_normal_dimensions() ; ?>" />
                        </p>

                        <p>
                            <input type="checkbox" name="keep_original_image" value="1" <?php echo ( osc_keep_original_image() ) ? 'checked' : '' ; ?>/><label><?php _e('Keep original image') ; ?></label>
                            <br />
                            <?php _e('Keeping original image files required extra storage and some files could have extra-large size. This option ensures the original quality of the file un-altered. Be careful using this option.'); ?>
                        </p>
                    </fieldset>

                    <input id="button_save" type="submit" value="<?php _e('Update configuration'); ?>" />
                </form>
            </div>
        </div>
        <div style="clear: both;"></div>
