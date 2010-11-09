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

<?php $last = end($categories); $last_id = $last['pk_i_id']; ?>
<script>
	$(function() {
		$.fn.dataTableExt.oApi.fnGetFilteredNodes = function ( oSettings ) {
			var anRows = [];
			
			for (var i=0, iLen=oSettings.aiDisplay.length ; i<iLen ; i++ ) {
				var nRow = oSettings.aoData[ oSettings.aiDisplay[i] ].nTr;
				anRows.push(nRow);
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
				<?php foreach($categories as $c): ?>
                <?php $data = Category::newInstance()->isParentOf($c['pk_i_id']);
                    if(count($data)>0) {
                        $has_subcategories = true;
                    } else { 
                        $has_subcategories = false;
                    }
                ?>
				[
				'	<input type="hidden" name="enabled" value="<?php echo $c['b_enabled']; ?>" /><input type="checkbox" name="id[]" value="<?php echo $c['pk_i_id']; ?>" />',
					"<?php echo $c['s_name']; ?> <div id='datatables_quick_edit'><a href='categories.php?action=edit&amp;id=<?php echo $c['pk_i_id']; ?>'><?php echo __('Edit'); ?></a> | <a href='categories.php?action=enable&amp;id=<?php echo $c['pk_i_id']; ?>&enabled=<?php echo $c['b_enabled'] == 1 ? '0' : '1'; ?>'><?php echo __($c['b_enabled'] == 1 ? 'Disable' : 'Enable'); ?></a> <?php if($has_subcategories) { ?>| <a href='categories.php?parentId=<?php echo $c['pk_i_id']; ?>'><?php echo __('View subcategories'); ?></a><?php }; ?> | <a onclick=\"javascript:return confirm('<?php echo __('WARNING: This will also delete the items under that category. This action can not be undone. Are you sure you want to continue?'); ?>')\" href='categories.php?action=delete&amp;id[]=<?php echo $c['pk_i_id']; ?>'><?php echo __('Delete'); ?></a></div>",
					"<?php echo  isset($parent) ? $parent['s_name'] : '-' ?>",
					'<?php echo $c['s_description']; ?>'
				] <?php echo $last_id != $c['pk_i_id'] ? ',' : ''; ?>
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
				 "sWidth": "45%"
				},
				{"sTitle": "<?php echo __('Parent'); ?>"},
				{"sTitle": "<?php echo __('Description'); ?>" }
			]
		});

        $('input:hidden[name=enabled]').each(function() {
			$(this).parent().parent().children().css('background', 'none');

			if ($(this).val() == '1') {
				$(this).parent().parent().css('background-color', '#EDFFDF');
			} else {
				$(this).parent().parent().css('background-color', '#FFF0DF');
			}

		});
		
	});
</script>
<script type="text/javascript" src="<?php echo  $current_theme ?>/js/datatables.post_init.js"></script>
		<div id="content">
			<div id="separator"></div>	
			
			<?php include_once $absolute_path . '/include/backoffice_menu.php'; ?>
		    
			<div id="right_column">
			    <div id="content_header" class="content_header">
					<div style="float: left;"><img src="<?php echo  $current_theme; ?>/images/back_office/cat-icon.png" /></div>
					<div id="content_header_arrow">&raquo; <?php echo __('Categories'); ?></div>
					<!-- <div id="button_open"> <?php echo osc_lowerCase( __('Add New Category') ); ?></div> -->
					<div style="clear: both;"></div>
				</div>
				<div id="content_separator"></div>
				<?php osc_showFlashMessages(); ?>
				
				<div id="TableToolsToolbar">
				<select id="bulk_actions" class="display">
					<option value=""><?php echo __('Bulk Actions'); ?></option>
					<option value="enable_selected"><?php echo __('Enable') ?></option>
					<option value="disable_selected"><?php echo __('Disable') ?></option>
					<option value="delete_all"><?php echo __('Delete') ?></option>
				</select>
				&nbsp;<button id="bulk_apply" class="display"><?php echo __('Apply') ?></button>
				</div>
				
				<form id="datatablesForm" action="categories.php" method="post">
				<input id="form_action" type="hidden" name="action" value="delete" />
					<table cellpadding="0" cellspacing="0" border="0" class="display" id="datatables_list"></table>
					<br />
				</form>

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
