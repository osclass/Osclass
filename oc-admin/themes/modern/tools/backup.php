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
	$().ready(function(){
		$.ajaxSetup({
			error:function(x,e){
				if(x.status==0){
				alert('You are offline!!\n Please Check Your Network.');
				}else if(x.status==404){
				alert('Requested URL not found.');
				}else if(x.status==500){
				alert('Internel Server Error.');
				}else if(e=='parsererror'){
				alert('Error.\nParsing JSON Request failed.');
				}else if(e=='timeout'){
				alert('Request Time out.');
				}else {
				alert('Unknow Error.\n'+x.responseText);
				}
			}
		});
	});

	function submitForm(typ) {
		bck_dir = document.forms['bckform'].backup_dir.value;
		$(function() {
			if(typ=='zip') {
				var result = document.getElementById('steps_zip');
			} else {
				var result = document.getElementById('steps_sql');
			}
			result.innerHTML = "<?php echo __('Backing up data... please wait'); ?>";

				$.get('<?php echo ABS_WEB_URL; ?>oc-admin/tools.php?bck_dir='+bck_dir+'&action=backup-'+typ, function(data) {
					result.innerHTML = data;
				});
		});
	}
</script>
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
					<div style="float: left;"><img src="<?php echo  $current_theme; ?>/images/back_office/tools-icon.png" /></div>
					<div id="content_header_arrow">&raquo; <?php echo __('Upgrade OSClass'); ?></div> 
					<div style="clear: both;"></div>
				</div>
				
				<div id="content_separator"></div>
				<?php osc_showFlashMessages(); ?>
				
				<!-- add new item form -->
				<div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
					<div style="padding: 20px;">
						<?php echo __('From here you can backup OSClass. WARNING: Backup files will be created on the root of your installation OSClass.'); ?>.
						
						<form action="tools.php" method="post" id="bckform" name="bckform" >
						<input type="hidden" name="action" value="backup_post" />
						
						<p>
						<label for="data"><?php echo __('Backup Folder'); ?></label>
						<input id="backup_dir" type="text" value="<?php echo ABS_PATH; ?>" />
						<?php echo __('This is the folder in which your backups will be created. We recommend you to choose a non-public path. For more information, please refer to OSClass\' documentation.')?>
						</p>
						
						<p>
						<label for="data"><?php echo __('Backup Database'); ?> (.sql)</label>
						<button class="formButton" type="button" onclick="submitForm('sql')" ><?php echo __('backup'); ?></button>
						<div id="steps_sql" name="steps_sql"></div>
						</p>
						
						<p>
						<label for="data"><?php echo __('Backup OSClass installation'); ?> (.zip)</label>
						<button class="formButton" type="button" onclick="submitForm('zip')" ><?php echo __('backup'); ?></button>
						<div id="steps_zip" name="steps_zip"></div>
						</p>
						
						
						</form>					
					</div>
			</div>
			</div> <!-- end of right column -->
