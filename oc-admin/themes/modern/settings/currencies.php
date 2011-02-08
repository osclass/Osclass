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

<?php defined('ABS_PATH') or die('Invalid OSClass request.'); ?>
<?php $last = end($currencies); $last_id = $last['pk_c_code']; ?>
<script>
	$(function() {
		$.fn.dataTableExt.oApi.fnGetFilteredNodes = function ( oSettings )
		{
			var anRows = [];
			for ( var i=0, iLen=oSettings.aiDisplay.length ; i<iLen ; i++ )
			{
				var nRow = oSettings.aoData[ oSettings.aiDisplay[i] ].nTr;
				anRows.push( nRow );
			}
			return anRows;
		};
		
		sSearchName = "<?php _e('Search'); ?>...";	
		oTable = $('#datatables_list').dataTable({
	       	"bAutoWidth": false,
			"sDom": '<"top"fl>rt<"bottom"ip<"clear">',
			"oLanguage": {
					"sProcessing":   "<?php _e('Processing'); ?>...",
					"sLengthMenu":   "<?php _e('Show _MENU_ entries'); ?>",
					"sZeroRecords":  "<?php _e('No matching records found'); ?>",
					"sInfo":         "<?php _e('Showing _START_ to _END_ of _TOTAL_ entries'); ?>",
					"sInfoEmpty":    "<?php _e('Showing 0 to 0 of 0 entries'); ?>",
					"sInfoFiltered": "(<?php _e('filtered from _MAX_ total entries'); ?>)",
					"sInfoPostFix":  "",
					"sSearch":       "<?php _e('Search'); ?>:",
					"sUrl":          "",				
					"oPaginate": {
						"sFirst":    "<?php _e('First'); ?>",
						"sPrevious": "<?php _e('Previous'); ?>",
						"sNext":     "<?php _e('Next'); ?>",
						"sLast":     "<?php _e('Last'); ?>"
					},
			       	"sLengthMenu": '<div style="float:left;"><?php _e('Show'); ?> <select class="display" id="select_range">'+
			        '<option value="10">10</option>'+
			        '<option value="15">15</option>'+
			        '<option value="20">20</option>'+
			        '<option value="100">100</option>'+
					'</select> <?php _e('entries'); ?>',
			        "sSearch": '<span class="ui-icon ui-icon-search" style="display: inline-block;"></span>'
			 },
			"sPaginationType": "full_numbers",
			"aaData": [
				<?php foreach($currencies as $c): ?>
				[
					"<input type='checkbox' name='id[]' value='<?php echo $c['pk_c_code']; ?>' />",
					"<?php echo $c['pk_c_code']; ?> <div><a onclick=\"javascript:return confirm('<?php _e('This action can not be undone. Are you sure you want to continue?'); ?>')\" href='settings.php?action=deleteCurrency&amp;code[]=<?php echo $c['pk_c_code']; ?>'><?php _e('Delete'); ?></a> | <a href='settings.php?action=editCurrency&amp;code=<?php echo $c['pk_c_code']; ?>'><?php _e('Edit'); ?></a></div>",
					"<?php echo $c['s_name']; ?>",
					"<?php echo $c['s_description']; ?>"
				]  <?php echo $last_id != $c['pk_c_code'] ? ',' : ''; ?>
				<?php endforeach; ?>
			], 
			"aoColumns": [
				{"sTitle": "<div style='margin-left: 8px;'><input id='check_all' type='checkbox' /></div>", 
				 "bSortable": false, 
				 "sClass": "center", 
				 "sWidth": "10px",
				 "bSearchable": false
				 },
				{"sTitle": "<?php _e('Code'); ?>",
				 "sWidth": "150px" },
				{"sTitle": "<?php _e('Name'); ?>" },
				{"sTitle": "<?php _e('Description'); ?>" },
			]
		});
		
	});
</script>
<script type="text/javascript" src="<?php echo  osc_current_admin_theme_url() ; ?>/js/datatables.post_init.js"></script>
	<div id="content">
		<div id="separator"></div>	

		<?php include_once osc_current_admin_theme_path() . '/include/backoffice_menu.php'; ?>
		
	    <div id="right_column">

			<div id="content_header" class="content_header">
				<div id="content_header" class="content_header">
					<div style="float: left;"><img src="<?php echo  osc_current_admin_theme_url() ; ?>/images/back_office/currencies.gif" /></div>
					<div id="content_header_arrow">&raquo; <?php _e('Currencies'); ?></div> 
					<a href="settings.php?action=addCurrency" id="button_open"><?php echo osc_lowerCase(__('Add')); ?></a>
					<div style="clear: both;"></div>
				</div>
			</div>
			<?php osc_show_flash_messages() ; ?>

			<div id="content_separator"></div>

			<div id="TableToolsToolbar">
			<select id="bulk_actions" class="display">
				<option value=""><?php _e('Bulk Actions'); ?></option>
				<option value="delete_all"><?php _e('Delete') ?></option>
			</select>
			&nbsp;<button id="bulk_apply" class="display"><?php _e('Apply') ?></button>
			</div>
			
			<form id="datatablesForm" action="settings.php" method="post">
			<input type="hidden" name="action" value="deleteCurrency" />
			<table cellpadding="0" cellspacing="0" border="0" class="display" id="datatables_list"></table>
			<br />
			<div style="clear: both;"></div>
			</form>

		</div> <!-- end of right column -->
