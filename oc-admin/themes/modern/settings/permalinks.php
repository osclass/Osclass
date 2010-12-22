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

<?php 
    
    defined('ABS_PATH') or die(__('Invalid OSClass request')) ;
    
    $dao_preference = new Preference() ;
    if(isset($_GET['value'])) $dao_preference->update( array("s_value" => $_GET['value'] ? true : false), array("s_name" => "rewriteEnabled") ) ;
	$preferences = $dao_preference->toArray() ;
	unset($dao_preference) ;
	
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
			<div style="float: left;"><img src="<?php echo  $current_theme; ?>/images/back_office/settings-icon.png" /></div>
			<div id="content_header_arrow">&raquo; <?php echo __('Permalinks'); ?></div> 
			<div style="clear: both;"></div>
		</div>
		
		<div id="content_separator"></div>
		
		<?php osc_showFlashMessages(); ?>
		
		<!-- add new item form -->
		<div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
			<div style="padding: 20px;">

			<div><?php echo __('By default OSClass uses web URLs which have question marks and lots of numbers in them, however OSClass offers you the ability to create a custom URL structure for your permalinks and archives. This can improve the aesthetics, usability, and forward-compatibility of your links. A number of tags are available, and here are some examples to get you started'); ?>.</div>
		<br />
		
		<?php if(isset($preferences['rewriteEnabled']) && $preferences['rewriteEnabled']) { ?>
			<input id="re" type="checkbox" checked="checked" onchange="document.location = 'settings.php?action=permalinks&value=0';" /> <label for="re"><?php echo __('Enable nice urls'); ?></label>
		<?php } else { ?>
			<input id="re" type="checkbox" onchange="document.location = 'settings.php?action=permalinks&value=1';" /> <label for="re"><?php echo __('Enable nice urls'); ?></label>
		<?php } ?>
		
		<?php if(isset($_GET['value']) && $_GET['value']) { ?>
			<div><?php echo __("If your .htaccess file were writable, we could do this automatically, but it isn't so these are the mod_rewrite rules you should have in your .htaccess file. Click in the field and press CTRL + a to select all"); ?>.</div>
			<textarea rows="8" cols="75">
<IfModule mod_rewrite.c>
#Options +FollowSymlinks
RewriteEngine On
RewriteBase <?php echo REL_WEB; ?>/

#Pages: contact, feed, sitemap
RewriteRule ^contact(.html)?$ index.php?action=contact [NC,L]
RewriteRule ^feed.xml$ index.php?action=feed [NC,L]
RewriteRule ^sitemap.xml$ index.php?action=sitemap [NC,L]

#Advertisements
RewriteRule -(\d+)$ item.php?id=$1 [NC,L]

#Static pages
RewriteRule -p(\d+)$ page.php?id=$1 [NC,L]

#Redirect 301 of first page of category
RewriteCond %{REQUEST_URI} !^((oc-admin)|(oc-content)|(oc-includes)).*
RewriteRule ^([a-zA-Z\_\-]+/([a-zA-Z\_\-]*/)?)1/?$ $1 [R=301,L]

#Categories
RewriteRule ^([a-zA-Z\_\-]+/([a-zA-Z\_\-]*/)?)(\d+)/?$ category.php?slug=$1&page=$3 [NC,L]
RewriteCond %{REQUEST_URI} !^/((oc-admin)|(oc-includes)|(oc-content)).*
RewriteRule ^([a-zA-Z\_\-]+)$ $1/ [R=301,L]
RewriteCond %{REQUEST_URI} !^/((oc-admin)|(oc-includes)|(oc-content)).*
RewriteRule ^([a-zA-Z\_\-]+/[a-zA-Z\_\-]*?)/?$ category.php?slug=$1&page=1 [NC,L]

ErrorDocument 404 /index.php?action=errorPage&code=404
ErrorDocument 500 /index.php?action=errorPage&code=500
</IfModule>
		</textarea>
	<?php } ?>
	</div>
</div>
</div> <!-- end of right column -->
