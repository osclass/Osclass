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
                    "sAjaxSource": "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=users",
                    "iDisplayLength": 10,
                    "iColumns"      : 5,
                    "sDom": "<'row-action'<'row'<'span6 length-menu'l><'span6 filter'>fr>>t<'row'<'span6 info-results'i><'span6 paginate'p>>",
                    "sPaginationType": "bootstrap",
                    "bLengthChange": false,
                    "bProcessing": true,
                    "bServerSide":true,
                    "bPaginate": true,
                    "bFilter": true,
                    "oLanguage": {
                        "oPaginate": {
                            "sNext" : '<?php echo osc_esc_js ( __('Next') ) ; ?>',
                            "sPrevious" : '<?php echo osc_esc_js ( __('Previous') ) ; ?>'
                        },
                        "sEmptyTable" : '<?php echo osc_esc_js ( __('No data available in table') ) ; ?>',
                        "sInfo": '<?php echo osc_esc_js ( sprintf( __('Showing %s to %s of %s entries'), '_START_', '_END_', '_TOTAL_') ) ; ?>',
                        "sInfoEmpty": '<?php echo osc_esc_js ( __('No entries to show') ) ; ?>',
                        "sInfoFiltered": '<?php echo osc_esc_js ( sprintf( __('(filtered from %s total entries)'), '_MAX_' ) ) ; ?>',
                        "sLoadingRecords": '<?php echo osc_esc_js ( __('Loading...') ) ; ?>',
                        "sProcessing": '<?php echo osc_esc_js ( __('Processing...') ) ; ?>',
                        "sSearch": '<?php echo osc_esc_js ( __('Search by name') ) ; ?>',
                        "sZeroRecords": '<?php echo osc_esc_js ( __('No matching records found') ) ; ?>'
                    },
                    "aoColumns": [
                        {
                            "sTitle": "<input id='check_all' type='checkbox' />",
                            "bSortable": false,
                            "sWidth": "10px",
                            "bSearchable": false
                        },
                        {
                            "sTitle": '<?php echo osc_esc_js ( __('E-mail') ) ; ?>',
                            "bSortable": true
                        },
                        {
                            "sTitle": '<?php echo osc_esc_js ( __('Name') ) ; ?>',
                            "bSortable": true
                        },
                        {
                            "sTitle": '<?php echo osc_esc_js ( __('Date') ) ; ?>',
                            "bSortable": true,
                            "sWidth": "150px"
                        },
                        {
                            "sTitle": '<?php echo osc_esc_js ( __('Update Date') ) ; ?>',
                            "bSortable": true,
                            "sWidth": "150px"
                        }
                    ]
                });

                $('#datatables_list tr').live('mouseover', function(event) {
                    $('.datatables_quick_edit', this).show();
                });

                $('#datatables_list tr').live('mouseleave', function(event) {
                    $('.datatables_quick_edit', this).hide();
                });

                $('.length-menu').append( $("#bulk_actions") ) ;
                $('.filter').append( $("#add_user_button") ) ;
            }) ;
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
                    <h1 class="users"><?php _e('Users') ; ?></h1>
                </div>
                <?php osc_show_flash_message('admin') ; ?>
                <!-- datatables users -->
                <form class="settings users datatables" id="datatablesForm" action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
                    <input type="hidden" name="page" value="users" />
                    <div id="bulk_actions">
                        <label>
                            <select name="action" id="action" class="display">
                                <option value=""><?php _e('Bulk Actions') ; ?></option>
                                <option value="activate"><?php _e('Activate') ; ?></option>
                                <option value="deactivate"><?php _e('Deactivate') ; ?></option>
                                <option value="enable"><?php _e('Block') ; ?></option>
                                <option value="disable"><?php _e('Unblock') ; ?></option>
                                <option value="delete"><?php _e('Delete') ; ?></option>
                                <option value="resend_activation"><?php _e('Resend activation') ; ?></option>
                            </select> <input type="submit" id="bulk_apply" class="btn" value="<?php echo osc_esc_html( __('Apply') ) ; ?>">
                        </label>
                    </div>
                    <div id="add_user_button">
                        <a href="<?php echo osc_admin_base_url(true); ?>?page=users&amp;action=create" class="btn" id="button_open"><?php _e('Add user') ; ?></a>
                    </div>
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="datatables_list"></table>
                </form>
                <!-- /datatables users -->
            </div>
            <!-- /right container -->
        </div>
        <!-- /container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>