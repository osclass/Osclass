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

    $admins = __get("admins") ;

    $aData = array() ;
    foreach($admins as $admin) {
        $row = array() ;
        $row[] = '<input type="checkbox" name="id[]" value="' . $admin['pk_i_id'] . '" />' ;
        $row[] = $admin['s_username'] . ' <div id="datatables_quick_edit"><a href="' . osc_admin_base_url(true) . '?page=admins&action=edit&amp;id='  . $admin['pk_i_id'] . '">' . __('Edit') . '</a> &middot; <a onclick="javascript:return confirm(\'' . osc_esc_js( __("This action can't be undone. Are you sure you want to continue?") ) . '\');" href="' . osc_admin_base_url(true) . '?page=admins&action=delete&amp;id[]=' . $admin['pk_i_id'] . '">' . __('Delete') . '</a></div>' ;
        $row[] = $admin['s_name'] ;
        $row[] = $admin['s_email'] ;

        $aData[] = $row ;
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()) ; ?>">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
        <link href="<?php echo osc_current_admin_theme_styles_url('datatables.css') ; ?>" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.dataTables.js') ; ?>"></script>
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('datatables.pagination.js') ; ?>"></script>
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('datatables.extend.js') ; ?>"></script>
        <script type="text/javascript">
            $(function() {
                oTable = $('#datatables_list').dataTable({
                    "sDom": "<'row-action'<'row'<'span6 length-menu'l><'span6 filter'>fr>>t<'row'<'span6 info-results'i><'span6 paginate'p>>",
                    "sPaginationType": "bootstrap",
                    "bLengthChange": false,
                    "bProcessing": true,
                    "bServerSide":false,
                    "bPaginate": true,
                    "bFilter": true,
                    "oLanguage": {
                        "oPaginate": {
				"sNext" : '<?php echo osc_esc_js( __('Next') ) ; ?>',
				"sPrevious" : '<?php echo osc_esc_js( __('Previous') ) ; ?>'
			},
			"sEmptyTable" : '<?php echo osc_esc_js( __('No data available in table') ) ; ?>',
			"sInfo": '<?php echo osc_esc_js( sprintf( __('Showing %s to %s of %s entries'), '_START_', '_END_', '_TOTAL_') ) ; ?>',
			"sInfoEmpty": '<?php echo osc_esc_js( __('No entries to show') ) ; ?>',
			"sInfoFiltered": '<?php echo osc_esc_js( sprintf( __('(filtered from %s total entries)'), '_MAX_' ) ) ; ?>',
			"sLoadingRecords": '<?php echo osc_esc_js( __('Loading...') ) ; ?>',
			"sProcessing": '<?php echo osc_esc_js( __('Processing...') ) ; ?>',
			"sSearch": '<?php echo osc_esc_js( __('Search') ) ; ?>',
			"sZeroRecords": '<?php echo osc_esc_js( __('No matching records found') ) ; ?>'
                    },
                    "aaData": <?php echo json_encode($aData) ; ?>,
                    "aoColumns": [
                        {
                            "sTitle": "<input id='check_all' type='checkbox' />",
                            "bSortable": false,
                            "sWidth": "10px",
                            "bSearchable": false
                        },
                        {
                            "sTitle": "<?php echo osc_esc_html( __('Username') ) ; ?>"
                        },
                        {
                            "sTitle": "<?php echo osc_esc_html( __('Name') ) ; ?>"
                        },
                        {
                            "sTitle": "<?php echo osc_esc_html( __('E-mail') ) ; ?>"
                        }
                    ]
                });

                $('.length-menu').append( $("#bulk_actions") ) ;
                $('.filter').append( $("#add_admin_button") ) ;
            });
        </script>
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('datatables.post_init.js') ; ?>"></script>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <!-- container -->
        <div id="content">
            <?php osc_current_admin_theme_path( 'include/backoffice_menu.php' ) ; ?>
            <!-- right container -->
		    <div class="right">
                <div class="header_title">
                    <h1 class="admins"><?php _e('Admins') ; ?></h1>
                </div>
                <?php osc_show_flash_message('admin') ; ?>
                <!-- datatables admins -->
                <form class="settings admins datatables" id="datatablesForm" action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
                    <input type="hidden" name="page" value="admins" />
                    <div id="bulk_actions">
                        <label>
                            <select name="action" id="action" class="display">
                                <option value=""><?php _e('Bulk actions') ; ?></option>
                                <option value="delete"><?php _e('Delete') ; ?></option>
                            </select> <input type="submit" id="bulk_apply" class="btn" value="<?php echo osc_esc_html( __('Apply') ) ; ?>">
                        </label>
                    </div>
                    <div id="add_admin_button">
                        <a href="<?php echo osc_admin_base_url(true); ?>?page=admins&amp;action=add" class="btn" id="button_open"><?php _e('Add admin') ; ?></a>
                    </div>
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="datatables_list"></table>
                </form>
                <!-- /datatables admins -->
            </div>
            <!-- /right container -->
        </div>
        <!-- /container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>