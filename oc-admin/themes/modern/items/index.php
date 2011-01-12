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

<?php $last_item = end($items); $last_id = $last_item['pk_i_id']; ?>
<?php
/*
 *  XXX: move this to items.php
 *
 */
$stat = FALSE;
if(isset($_GET)) {
    if(isset($_GET['stat'])) {
        $stat = $_GET['stat'];
    }
}
?>

<script type="text/javascript">
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
			"bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": "<?php echo ABS_WEB_URL?>oc-admin/ajax/items_processing.php",
                        <?php if($stat) { ?>
                            "fnServerData": function ( sSource, aoData, fnCallback ) {
                                    /* Add some extra data to the sender */
                                    aoData.push( { "name": "stat", "value": "<?php echo $stat ?>" } );
                                    $.getJSON( sSource, aoData, function (json) {
                                            /* Do whatever additional processing you want on the callback, then tell DataTables */
                                            fnCallback(json)
                                    } );
                            },
                        <?php } ?>
                        "bAutoWidth": false,
			"sDom": '<"top"fl>rt<"bottom"ip<"clear">',
			//"bJQueryUI": true,
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

			"aoColumns": [
				{"sTitle": "<div style='margin-left: 8px;'><input id='check_all' type='checkbox' /></div>", 
				 "bSortable": false, 
				 "sClass": "center", 
				 "sWidth": "10px",
				 "bSearchable": false
				 },
				{"sTitle": "<?php echo __('Title') ?>",
                                 "bSortable": false,
				 "sWidth": "40%"
				},
                                <?php if($stat) { ?>
                                    {"sTitle": "<?php echo __('Count') ?>",
                                     "bSortable": false
                                    },
                                <?php } else { ?>
                                    {"sTitle": "<?php echo __('Description') ?>",
                                     "bSortable": false
                                    },
                                <?php } ?>
				{"sTitle": "<?php echo __('Category') ?>",
				 "sWidth": "20%",
				 "bSortable": false 
				},
				/*{"sTitle": "<img src='<?php echo  $current_theme ?>/images/back_office/comments-icon.png />",
				 "sWidth": "10px", 
				 "bSortable": false, 
				 "sClass": "center",
				 "bSearchable": false
				},*/
				{"sTitle": "<?php echo __('Date') ?>",
				 "sWidth": "100px",
				 "bSearchable": false
				}
			]
		});
                oTable.fnSort ( [[0, 'desc']] );
               // console.log(oTable);
	});
</script>
<script type="text/javascript" src="<?php echo  $current_theme ?>/js/datatables.post_init.js"></script>
<div id="content">
	<div id="separator"></div>	

	<?php include_once $absolute_path . '/include/backoffice_menu.php'; ?>

    <div id="right_column">
	    <div id="content_header" class="content_header">
			<div style="float: left;"><img src="<?php echo  $current_theme;?>/images/back_office/new-folder-icon.png" /></div>
			<div id="content_header_arrow">&raquo; <?php echo __('Manage items'); ?></div>
			<!-- <a href="new_item.php" id="button_open"><?php echo osc_lowerCase( __('Add New Item') ); ?></a> -->
			<div style="clear: both;"></div>
		</div>
		
		<div id="content_separator"></div>
		<?php osc_showFlashMessages(); ?>
		
		<form id="datatablesForm" action="items.php" method="post">
                    <div id="TableToolsToolbar">
                        <select id="bulk_actions" name="bulk_actions" class="display">
                                <option value=""><?php echo __('Bulk Actions'); ?></option>
                                <option value="delete_all"><?php echo __('Delete') ?></option>
                                <option value="activate_all"><?php echo __('Activate') ?></option>
                                <option value="deactivate_all"><?php echo __('Deactivate') ?></option>
                                <option value="premium_all"><?php echo __('Make Premium') ?></option>
                                <option value="depremium_all"><?php echo __('Demake Premium') ?></option>
                        </select>
                        &nbsp;<button id="bulk_apply" class="display"><?php echo __('Apply') ?></button>
                    </div>
                    <div id="TableToolsLinks">
                        <strong><?php echo __('Filter by') ?>:</strong> <a href="items.php"><?php echo __('All') ?></a> |
                        <a href="?stat=pending"><?php echo __('Pending') ?></a> |
                        <a href="?stat=spam"><?php echo __('Spam') ?></a> |
                        <a href="?stat=duplicated"><?php echo __('Duplicated') ?></a> |
                        <a href="?stat=bad"><?php echo __('Bad classified') ?></a> |
                        <a href="?stat=offensive"><?php echo __('Offensive') ?></a> |
                        <a href="?stat=expired"><?php echo __('Expired') ?></a>
                    </div>
		<input type="hidden" name="action" value="bulk_actions" />
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
