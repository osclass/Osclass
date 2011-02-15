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


	$(function() {
		var steps = document.getElementById('steps');
		var version = <?php echo $info['version']; ?>;
		var fileToUnzip = '';
		steps.innerHTML += "<?php _e('Checking for update (installed version: '); ?>"+version+"): ";

		$.getJSON("<?php echo $info['plugin_update_uri']; ?>", function(data) {
			if(data.version <= version) {
				steps.innerHTML += "<?php _e('HORRAY! Your plugin installation is up to date! (current version: '); ?>"+data.version+")";
			} else {
				steps.innerHTML += "<?php _e('current version: '); ?>"+data.version+"<br/>";
				steps.innerHTML += "<?php _e('Downloading update file: '); ?>";

				var tempAr = data.url.split('/');
				fileToUnzip = tempAr.pop();
				$.get('<?php echo osc_base_url() ; ?>oc-admin/plugins-update.php?action=download-file&file='+data.url, function(data) {
				
					steps.innerHTML += data+"<br/>";
					steps.innerHTML += "<?php _e('Unzipping file: '); ?>";

					$.get('<?php echo osc_base_url() ; ?>oc-admin/plugins-update.php?action=unzip-file&file='+fileToUnzip, function(data) {
					
						steps.innerHTML += data+"<br/>";
						steps.innerHTML += "<?php _e('Copying old files: '); ?>";

						$.get('<?php echo osc_base_url() ; ?>oc-admin/plugins-update.php?action=copy-files', function(data) {
						
							steps.innerHTML += data+"<br/>";
							steps.innerHTML += "<?php _e('Removing new files: '); ?>";

							$.get('<?php echo osc_base_url() ; ?>oc-admin/plugins-update.php?action=remove-files', function(data) {
							
								steps.innerHTML += data+"<br/>";
								steps.innerHTML += "<?php _e('Executing SQL: '); ?>";

								$.get('<?php echo osc_base_url() ; ?>oc-admin/plugins-update.php?action=execute-sql', function(data) {
								
									steps.innerHTML += data+"<br/>";
									steps.innerHTML += "<?php _e('Executing additional actions: '); ?>";

									$.get('<?php echo osc_base_url() ; ?>oc-admin/plugins-update.php?action=execute-actions', function(data) {
									
										steps.innerHTML += data+"<br/>";
										steps.innerHTML += "<?php _e('Cleanning all the mesh: '); ?>";

										$.get('<?php echo osc_base_url() ; ?>oc-admin/plugins-update.php?action=empty-temp', function(data) {
										
											steps.innerHTML += data+"<br/>";

											steps.innerHTML += "<?php _e('Satisfaying user with awesome and easy auto-upgrade: Done!'); ?><br/><br/>";
										});
									});
								});
							});
						});
					});
				});
			}
		});
	});
</script>

<?php defined('ABS_PATH') or die(__('Invalid OSClass request.')) ; ?>

<div id="content">
    <div id="separator"></div>

    <?php include_once osc_current_admin_theme_path() . 'include/backoffice_menu.php' ; ?>

    <div id="right_column">
        <div id="content_header" class="content_header">
            <div style="float: left;"><img src="<?php echo  osc_current_admin_theme_url() ; ?>images/tools-icon.png" /></div>
            <div id="content_header_arrow">&raquo; <?php _e('Upgrade OSClass') ; ?></div>
            <div style="clear: both;"></div>
        </div>

        <div id="content_separator"></div>
        <?php osc_show_flash_message() ; ?>

        <!-- add new item form -->
        <div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
            <div style="padding: 20px;">
            <div id="steps" name="steps"><br/></div>
        </div>
    </div>
</div> <!-- end of right column -->