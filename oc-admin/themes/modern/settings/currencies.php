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

<?php defined('APP_PATH') or die('Invalid OSClass request.'); ?>
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
		
		sSearchName = "<?php echo __('Search'); ?>...";	
		oTable = $('#datatables_list').dataTable({
	       	"bAutoWidth": false,
			"sDom": '<"top"fl>rt<"bottom"ip<"clear">',
			"oLanguage": {
					"sProcessing":   "<?php echo __('Processing'); ?>...",
					"sLengthMenu":   "<?php echo __('Show _MENU_ entries'); ?>",
					"sZeroRecords":  "<?php echo __('No matching records found'); ?>",
					"sInfo":         "<?php echo __('Showing _START_ to _END_ of _TOTAL_ entries'); ?>",
					"sInfoEmpty":    "<?php echo __('Showing 0 to 0 of 0 entries'); ?>",
					"sInfoFiltered": "(<?php echo __('filtered from _MAX_ total entries'); ?>)",
					"sInfoPostFix":  "",
					"sSearch":       "<?php echo __('Search'); ?>:",
					"sUrl":          "",				
					"oPaginate": {
						"sFirst":    "<?php echo __('First'); ?>",
						"sPrevious": "<?php echo __('Previous'); ?>",
						"sNext":     "<?php echo __('Next'); ?>",
						"sLast":     "<?php echo __('Last'); ?>"
					},
			       	"sLengthMenu": '<div style="float:left;"><?php echo __('Show'); ?> <select class="display" id="select_range">'+
			        '<option value="10">10</option>'+
			        '<option value="15">15</option>'+
			        '<option value="20">20</option>'+
			        '<option value="100">100</option>'+
					'</select> <?php echo __('entries'); ?>',
			        "sSearch": '<span class="ui-icon ui-icon-search" style="display: inline-block;"></span>'
			 },
			"sPaginationType": "full_numbers",
			"aaData": [
				<?php foreach($currencies as $c): ?>
				[
					"<input type='checkbox' name='id[]' value='<?php echo $c['pk_c_code']; ?>' />",
					"<?php echo $c['pk_c_code']; ?> <div><a onclick=\"javascript:return confirm('<?php echo __('This action can not be undone. Are you sure you want to continue?'); ?>')\" href='settings.php?action=deleteCurrency&amp;code[]=<?php echo $c['pk_c_code']; ?>'><?php echo __('Delete'); ?></a> | <a href='settings.php?action=editCurrency&amp;code=<?php echo $c['pk_c_code']; ?>'><?php echo __('Edit'); ?></a></div>",
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
				{"sTitle": "<?php echo __('Code'); ?>",
				 "sWidth": "150px" },
				{"sTitle": "<?php echo __('Name'); ?>" },
				{"sTitle": "<?php echo __('Description'); ?>" },
			]
		});
		
	});
</script>
<script type="text/javascript" src="<?php echo  $current_theme ?>/js/datatables.post_init.js"></script>
	<div id="content">
		<div id="separator"></div>	

		<?php include_once $absolute_path . '/include/backoffice_menu.php'; ?>
		
	    <div id="right_column">

			<div id="content_header" class="content_header">
				<div id="content_header" class="content_header">
					<div style="float: left;"><img src="<?php echo  $current_theme; ?>/images/back_office/currencies.gif" /></div>
					<div id="content_header_arrow">&raquo; <?php echo __('Currencies'); ?></div> 
					<a href="settings.php?action=addCurrency" id="button_open"><?php echo osc_lowerCase(__('Add')); ?></a>
					<div style="clear: both;"></div>
				</div>
			</div>
			<?php osc_showFlashMessages(); ?>

			<div id="content_separator"></div>

			<div id="TableToolsToolbar">
			<select id="bulk_actions" class="display">
				<option value=""><?php echo __('Bulk Actions'); ?></option>
				<option value="delete_all"><?php echo __('Delete') ?></option>
			</select>
			&nbsp;<button id="bulk_apply" class="display"><?php echo __('Apply') ?></button>
			</div>
			
			<form id="datatablesForm" action="settings.php" method="post">
			<input type="hidden" name="action" value="deleteCurrency" />
			<table cellpadding="0" cellspacing="0" border="0" class="display" id="datatables_list"></table>
			<br />
			<div style="clear: both;"></div>
			</form>

		</div> <!-- end of right column -->
