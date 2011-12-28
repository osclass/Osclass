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
                oTable = new osc_datatable();
                oTable.fnInit({
                    'idTable'       : 'datatables_list'
                    ,"sAjaxSource": "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=users"
                    ,'iDisplayLength': '10'
                    ,'iColumns'      : '5'
                    ,'oLanguage'     : {
                            "sInfo":         "<?php _e('Showing _START_ to _END_ of _TOTAL_ entries') ; ?>"
                            ,"sZeroRecords":  "<?php _e('No matching records found') ; ?>"
                            ,"sInfoFiltered": "(<?php _e('filtered from _MAX_ total entries') ; ?>)"
                            ,"oPaginate": {
                                        "sFirst":    "<?php _e('First') ; ?>",
                                        "sPrevious": "<?php _e('Previous') ; ?>",
                                        "sNext":     "<?php _e('Next') ; ?>",
                                        "sLast":     "<?php _e('Last') ; ?>"
                                    }
                    }
                    ,"aoColumns": [
				        {"sTitle": "<div style='margin-left: 8px;'><input id='check_all' type='checkbox' /></div>"
				         ,"bSortable": false
				         ,"sClass": "center"
				         ,"sWidth": "10px"
				         ,"bSearchable": false
				         }
				        ,{"sTitle": "<?php _e('E-mail'); ?>",
				         "sWidth": "30%"
				         ,"bSortable": true
				        }
				        ,{"sTitle": "<?php _e('Real name') ?>"
                            ,"bSortable": true
                        }
				        ,{"sTitle": "<?php _e('Date'); ?>"
                            ,"bSortable": true
                        }
                        ,{"sTitle": "<?php _e('Update Date'); ?>"
                            ,"bSortable": true
                        }
			        ]                });
            });
            
            $('#datatables_list tr').live('mouseover', function(event) {
                $('#datatable_wrapper', this).show();
                $('#datatables_quick_edit', this).show();
            });

            $('#datatables_list tr').live('mouseleave', function(event) {
                $('#datatable_wrapper', this).hide();
                $('#datatables_quick_edit', this).hide();
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
                            <option value="resend_activation"><?php _e('Resend activation'); ?></option>
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