<?php
    /**
     * OSClass – software for creating and publishing online classified advertising platforms
     *
     * Copyright (C) 2010 OSCLASS
     *
     * This program is free software: you can redistribute it and/or modify it under the terms
     * of the GNU Affero General Public License as published by the Free Software Foundation,
     * either version 3 of the License, or (at your option) any later version.
     *
     * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
     * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
     * See the GNU Affero General Public License for more details.
     *
     * You should have received a copy of the GNU Affero General Public
     * License along with this program. If not, see <http://www.gnu.org/licenses/>.
     */

    $resources = __get("resources");
    $resourceId = __get("resourceId");
    $last = end($resources); $last_id = $last['pk_i_id'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <div id="update_version" style="display:none;"></div>
        <div class="Header"><?php _e("Media");?></div>

        <script type="text/javascript">
	        $(function() {
		        $.fn.dataTableExt.oApi.fnGetFilteredNodes = function ( oSettings ) {
			        var anRows = [];
			
			        for (var i=0, iLen=oSettings.aiDisplay.length ; i < iLen; i++ ) {
				        var nRow = oSettings.aoData[ oSettings.aiDisplay[i] ].nTr;
				        anRows.push(nRow);
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
				        <?php foreach($resources as $r) { ?>
					        [
						        "<input type='checkbox' name='id[]' value='<?php echo $r['pk_i_id']; ?>' />",
						        "<div id='media_list_pic'><img src='<?php echo osc_base_url().$r['s_path'].$r['pk_i_id']."_thumbnail.".$r['s_extension'] ; ?>' style='max-width: 60px; max-height: 60px;' /></div> <div id='media_list_filename'><?php echo $r['s_content_type']; ?><br /><span id='datatables_quick_edit'><a onclick=\"javascript:return confirm('<?php _e('This action can not be undone. Are you sure you want to continue?'); ?>')\" href='<?php echo osc_admin_base_url(true);?>?page=media&action=delete&amp;id[]=<?php echo $r['pk_i_id']; ?>' id='dt_link_delete'><?php _e('Delete'); ?></a></span></div>",
						        "<a target='_blank' href='<?php echo osc_item_url_ns($r['fk_i_item_id']); ?>'>item #<?php echo $r['fk_i_item_id']; ?></a>",
						        "<?php echo $r['dt_pub_date']; ?>"
					        ] <?php echo $last_id != $r['pk_i_id'] ? ',' : ''; ?>
				        <?php } ?>
			        ], 
			        "aoColumns": [
				        {"sTitle": "<div style='margin-left: 8px;'><input id='check_all' type='checkbox' /></div>", 
				         "bSortable": false, 
				         "sClass": "center", 
				         "sWidth": "10px",
				         "bSearchable": false
				         },
				        {"sTitle": "<?php _e('File'); ?>",
				         "sWidth": "30%"
				        },
				        {"sTitle": "<?php _e('Attached to'); ?>"},
				        {"sTitle": "<?php _e('Date'); ?>",
				         "sWidth": "100px",
				         "sClass": "center",
				         "bSearchable": false
				        }
			        ]
		        });

	        });
        </script>
        <script type="text/javascript" src="<?php echo  osc_current_admin_theme_url() ; ?>js/datatables.post_init.js"></script>
        <div id="content">
            <div id="separator"></div>

            <?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>

            <div id="right_column">
                <?php
                /* this is header for right side. */
                ?>
                <div id="content_header" class="content_header">
                    <div style="float: left;">
                        <img src="<?php echo osc_current_admin_theme_url() ; ?>images/media-icon.png" title="" alt="" />
                    </div>
                    <div id="content_header_arrow">&raquo; <?php _e('Media') ; ?></div>
                    <a href="<?php echo osc_admin_base_url(true);?>?page=media&action=config" id="button_open"><?php _e('Settings') ; ?></a>
                    <div style="clear: both;"></div>
                </div>

                <div id="content_separator"></div>
                <?php osc_show_flash_message('admin') ; ?>

                <div id="TableToolsToolbar">
                <select id="bulk_actions" class="display">
                    <option value=""><?php _e('Bulk actions') ; ?></option>
                    <option value="delete_all"><?php _e('Delete') ; ?></option>
                </select>
                &nbsp;<button id="bulk_apply" class="display"><?php _e('Apply') ; ?></button>
                </div>


                <form id="datatablesForm" action="<?php echo osc_admin_base_url(true);?>?page=media" method="post">
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
            <div style="clear: both;"></div>
        </div> <!-- end of container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>
