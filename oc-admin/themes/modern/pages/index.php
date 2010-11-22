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
//print_r($pages); 
?>
<?php $last = end($pages); $last_id = $last['pk_i_id']; ?>
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
				<?php foreach($pages as $page): ?>
				<?php $body = current($page['locale']); $p_body = str_replace("'", "\'", trim(strip_tags($body['s_title']), "\x22\x27")); ?>
					[
						"<input type='checkbox' name='id[]' value='<?php echo $page['pk_i_id']; ?>' />",
						"<?php echo $page['s_internal_name']; ?><div id='datatables_quick_edit'><a href='?action=edit&id=<?php echo  $page["pk_i_id"] ?>'><?php echo __('Edit'); ?></a><?php if(!$page['b_indelible']) { ?> | <a onclick=\"javascript:return confirm('<?php echo __('This action can not be undone. Are you sure you want to continue?'); ?>')\" href='pages.php?action=delete&amp;id=<?php echo $page['pk_i_id']; ?>'><?php echo __('Delete'); ?></a><?php }; ?></div>",
						'<?php echo $p_body; ?>'
					] <?php echo $last_id != $page['pk_i_id'] ? ',' : ''; ?>
				<?php endforeach; ?>
			], 
			"aoColumns": [
				{"sTitle": "<div style='margin-left: 8px;'><input id='check_all' type='checkbox' /></div>", 
				 "bSortable": false, 
				 "sClass": "center", 
				 "sWidth": "10px",
				 "bSearchable": false
				 },
				{"sTitle": "<?php echo __('Name'); ?>",
				 "sWidth": "50%"
				},
				{"sTitle": "<?php echo __('Description'); ?>" }
		]
		});
	});
</script>
<script type="text/javascript" src="<?php echo  $current_theme ?>/js/datatables.post_init.js"></script>
		<div id="content">
			<div id="separator"></div>	

			<?php include_once $absolute_path . '/include/backoffice_menu.php'; ?>
			
		    <div id="right_column">
			    <?php
				/* this is header for right side. */ 
				?>
				<div id="content_header" class="content_header">
					<div style="float: left;"><img src="<?php echo  $current_theme; ?>/images/back_office/pages-icon.png" /></div>
					<div id="content_header_arrow">&raquo; <?php echo __('Pages'); ?></div> 
					<a href="?action=add" id="button_open"><?php echo osc_lowerCase(__('Create page')); ?></a>
					<div style="clear: both;"></div>
				</div>

				<div id="content_separator"></div>
				<?php osc_showFlashMessages(); ?>
				
				<div id="TableToolsToolbar">
				<select id="bulk_actions" class="display">
					<option value=""><?php echo __('Bulk Actions'); ?></option>
					<option value="delete_all"><?php echo __('Delete') ?></option>
				</select>
				&nbsp;<button id="bulk_apply" class="display"><?php echo __('Apply') ?></button>
				</div>
						
				
				<form id="datatablesForm" action="pages.php" method="post">
				<input type="hidden" name="action" value="delete" />
					<table cellpadding="0" cellspacing="0" border="0" class="display" id="datatables_list"></table>
					<br />
				</form>

				<div style="clear: both;"></div>

			</div> <!-- end of right column -->
<script type="text/javascript">
	$(document).ready(function() {
		$('#datatables_list tr').live('mouseover', function(event) {
			$('#datatables_quick_edit', this).show();
		});

		$('#datatables_list tr').live('mouseleave', function(event) {
			$('#datatables_quick_edit', this).hide();
		});
	});
</script>					
