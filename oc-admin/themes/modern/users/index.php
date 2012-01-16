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

    $users = __get("users");
    $last = end($users); $last_id = $last['pk_i_id'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <div id="update_version" style="display:none;"></div>
        <script type="text/javascript">
	        $(function() {		
		        $.fn.dataTableExt.oApi.fnGetFilteredNodes = function ( oSettings ) {
			        var anRows = [];
			        for ( var i=0, iLen=oSettings.aiDisplay.length ; i<iLen ; i++ ) {
				        var nRow = oSettings.aoData[ oSettings.aiDisplay[i] ].nTr;
				        anRows.push( nRow );
			        }
			        return anRows;
		        };

		        sSearchName = "<?php _e('Search'); ?>...";	
		        oTable = $('#datatables_list').dataTable({
	               	"bAutoWidth": false
			        ,"sDom": '<"top"fl>rt<"bottom"ip<"clear">'
			        ,"oLanguage": {
					        "sProcessing":   "<?php _e('Processing'); ?>..."
					        ,"sLengthMenu":   "<?php _e('Show _MENU_ entries'); ?>"
					        ,"sZeroRecords":  "<?php _e('No matching records found'); ?>"
					        ,"sInfo":         "<?php _e('Showing _START_ to _END_ of _TOTAL_ entries'); ?>"
					        ,"sInfoEmpty":    "<?php _e('Showing 0 to 0 of 0 entries'); ?>"
					        ,"sInfoFiltered": "(<?php _e('filtered from _MAX_ total entries'); ?>)"
					        ,"sInfoPostFix":  ""
					        ,"sSearch":       "<?php _e('Search'); ?>:"
					        ,"sUrl":          ""
					        ,"oPaginate": {
						        "sFirst":    "<?php _e('First'); ?>",
						        "sPrevious": "<?php _e('Previous'); ?>",
						        "sNext":     "<?php _e('Next'); ?>",
						        "sLast":     "<?php _e('Last'); ?>"
					        }
			                ,"sLengthMenu": '<div style="float:left;"><?php _e('Show'); ?> <select class="display" id="select_range">'+
			                '<option value="10">10</option>'+
			                '<option value="15">15</option>'+
			                '<option value="20">20</option>'+
			                '<option value="100">100</option>'+
					        '</select> <?php _e('entries') ; ?>',
			                "sSearch": '<span class="ui-icon ui-icon-search" style="display: inline-block;"></span>'
			         }
			        ,"sPaginationType": "full_numbers"
			        /* THIS DATA SHOULD COME THROUGH JSON  OR MYSQL !!! */
			        ,"aaData": [
				        <?php foreach($users as $u) { ?>
					        [
						        "<input type='checkbox'  name='id[]' value='<?php echo $u['pk_i_id']; ?>' />"
						        ,"<?php echo $u['s_email'] ; ?>&nbsp;<div id='datatables_quick_edit'><?php
                                
                                if($u['b_active']==0) {?><a href='<?php echo osc_admin_base_url(true); ?>?page=users&action=activate&amp;id[]=<?php echo $u['pk_i_id']; ?>'><?php _e('Activate user'); ?></a><?php } else {?><a href='<?php echo osc_admin_base_url(true); ?>?page=users&action=deactivate&amp;id[]=<?php echo $u['pk_i_id']; ?>'><?php _e('Deactivate user'); ?></a><?php }; 

                                ?> | <?php
                                
                                if($u['b_enabled']==0) {?><a href='<?php echo osc_admin_base_url(true); ?>?page=users&action=enable&amp;id[]=<?php echo $u['pk_i_id']; ?>'><?php _e('Enable user'); ?></a><?php } else {?><a href='<?php echo osc_admin_base_url(true); ?>?page=users&action=disable&amp;id[]=<?php echo $u['pk_i_id']; ?>'><?php _e('Disable user'); ?></a><?php }; 

                                ?> | <a href='<?php echo osc_admin_base_url(true); ?>?page=users&action=edit&amp;id=<?php echo $u['pk_i_id']; ?>'><?php _e('Edit'); ?></a> | <a onclick=\"javascript:return confirm('<?php _e('This action can\\\\\'t be undone. Are you sure you want to continue?'); ?>')\" href='<?php echo osc_admin_base_url(true); ?>?page=users&action=delete&amp;id[]=<?php echo $u['pk_i_id']; ?>'><?php _e('Delete'); ?></a></div>"
						        ,"<?php echo addslashes(osc_esc_html($u['s_name'])) ; ?>"
						        ,"<?php echo $u['dt_reg_date'] ; ?>"
                                                        ,"<?php echo $u['dt_mod_date'] ; ?>"
					        ] <?php echo $last_id != $u['pk_i_id'] ? ',' : '' ; ?>
				        <?php } ?>
			        ]
			        ,"aoColumns": [
				        {"sTitle": "<div style='margin-left: 8px;'><input id='check_all' type='checkbox' /></div>"
				         ,"bSortable": false
				         ,"sClass": "center"
				         ,"sWidth": "10px"
				         ,"bSearchable": false
				         }
				        ,{"sTitle": "<?php _e('E-mail'); ?>",
				         "sWidth": "30%"
				        }
				        ,{"sTitle": "<?php _e('Real name') ?>" }
				        ,{"sTitle": "<?php _e('Date'); ?>" }
                        ,{"sTitle": "<?php _e('Update Date'); ?>" }
			        ]
		        });
	        });
        </script>
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('datatables.post_init.js') ; ?>"></script>
		<div id="content">
			<div id="separator"></div>	
			<?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>
		    <div id="right_column">
			    <div id="content_header" class="content_header">
					<div style="float: left;">
                        <img src="<?php echo osc_current_admin_theme_url('images/user-group-icon.png') ; ?>" alt="" title="" />
                    </div>
					<div id="content_header_arrow">&raquo; <?php _e('Users'); ?></div>
					<a href="<?php echo osc_admin_base_url(true); ?>?page=users&action=create" id="button_open"><?php _e('Add a new user') ; ?></a>
					<div style="clear: both;"></div>
				</div>
				<div id="content_separator"></div>
				<?php osc_show_flash_message('admin'); ?>
				<form id="datatablesForm" action="<?php echo osc_admin_base_url(true); ?>?page=users" method="post">
                    <div id="TableToolsToolbar">
                        <select name="action" id="action" class="display">
                            <option value=""><?php _e('Bulk Actions'); ?></option>
                            <option value="delete"><?php _e('Delete'); ?></option>
                            <option value="activate"><?php _e('Activate'); ?></option>
                            <option value="deactivate"><?php _e('Deactivate'); ?></option>
                            <option value="enable"><?php _e('Enable'); ?></option>
                            <option value="disable"><?php _e('Disable'); ?></option>
                        </select>
                        &nbsp;<button id="bulk_apply" class="display"><?php _e('Apply'); ?></button>
                    </div>
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
            <div style="clear: both;"></div>
        </div> <!-- end of container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>				