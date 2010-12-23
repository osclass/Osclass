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

<?php defined('ABS_PATH') or die(__('Invalid OSClass request.')); ?>

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
					<div style="float: left;"><img src="<?php echo  $current_theme; ?>/images/back_office/settings-icon.png" /></div>
					<div id="content_header_arrow">&raquo; <?php echo __('Permalinks'); ?></div>
					<div style="clear: both;"></div>
				</div>
				
				<div id="content_separator"></div>
				<?php osc_showFlashMessages(); ?>
				<!-- settings form -->
				<div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
					<div style="padding: 20px;">

						<form action="settings.php" method="post">
						<input type="hidden" name="action" value="permalinks_post" />
						
						<div style="float: left; width: 100%;">
							<fieldset>
								<legend><?php echo __('Nice URLs'); ?></legend>
<div><?php echo __('By default OSClass uses web URLs which have question marks and lots of numbers in them, however OSClass offers you the ability to create a custom URL structure for your permalinks and archives. This can improve the aesthetics, usability, and forward-compatibility of your links. A number of tags are available, and here are some examples to get you started'); ?>.</div>
<br />
                                                                <?php if(isset($preferences['rewriteEnabled']) && $preferences['rewriteEnabled']): ?>
                                                                <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" checked="true" name="rewrite_enabled" id="rewrite_enabled"/>
                                                                <?php else: ?>
                                                                <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" name="rewrite_enabled" id="rewrite_enabled"/>
                                                                <?php endif; ?>
                                                                <label><?php echo __('Enable nice URLs'); ?></label>

                                                                <br/>
							</fieldset>
						</div>

                        <?php if(isset($preferences['rewriteEnabled']) && $preferences['rewriteEnabled']): ?>

                        <div style="float: left; width: 100%;">
							<fieldset>
								<legend><?php echo __('.htaccess file'); ?></legend>
                                <?php switch($htaccess_status) {

                                    case 0:
                                        _e('Error. This should not happen.');
                                        break;

                                    case 1:
                                        _e('WARNING! Rewrite module isn\'t found on your server. You could still use nice urls if AllowPathInfo option is On in your Apache configuration (we can not know if it\'s enabled or not, but usually it is). With restricted nice url "index.php" will appear as a part of your URL (ie. http://www.yourdomain.com/index.php/nice/url)');
                                        break;

                                    case 2:
                                        _e('Error. We could not write the .htaccess file on your server. Please create a file called .htaccess on the root of your OSClass installation with the following content.');
                                        break;

                                    case 3:
                                        _e('File .htaccess already exists. Please, check that the .htaccess file has the following content.');
                                        break;

                                    case 4:
                                        _e('We create a .htaccess file on the root of your OSClass installation.');
                                        break;

                                    case 5:
                                        _e('Error. We did not find the .htaccess file on your server. Please create a file called .htaccess on the root of your OSClass installation with the following content.');
                                        break;

                                };
                                   
                                echo "<br />";     
                                echo "<br />";     
                                _e('Content of the .htaccess file:');

                                ?>
                                <br />
			                    <textarea rows="8" cols="140">
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase <?php echo REL_WEB_URL; ?>

RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . <?php echo REL_WEB_URL; ?>index.php [L]
</IfModule>
    		                    </textarea>

							</fieldset>
						</div>


                        <?php endif; ?>

						<div style="clear: both;"></div>
												
						<input id="button_save" type="submit" value="<?php echo __('Update'); ?>" />
						
						</form>

					</div>
				</div>
			</div> <!-- end of right column -->
