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

    $locales      = __get('locales') ;

    $aData = array() ;
    foreach($locales as $l) {
        $row = array() ;
        $row[] = '<input type="checkbox" name="id[]" value="' . $l['pk_c_code'] . '" />' ;

        $options   = array() ;
        $options[] = '<a href="' . osc_admin_base_url(true) . '?page=languages&amp;action=edit&amp;id='  . $l['pk_c_code'] . '">' . __('Edit') . '</a>' ;
        $options[] = '<a href="' . osc_admin_base_url(true) . '?page=languages&amp;action=' . ( $l['b_enabled'] == 1 ? 'disable_selected' : 'enable_selected' ) . '&amp;id[]=' . $l['pk_c_code'] . '"> ' . ($l['b_enabled'] == 1 ? __('Disable (website)') : __('Enable (website)') ) . '</a> ' ;
        $options[] = '<a href="' . osc_admin_base_url(true) . '?page=languages&amp;action=' . ( $l['b_enabled_bo'] == 1 ? 'disable_bo_selected' : 'enable_bo_selected' ) . '&amp;id[]=' . $l['pk_c_code'] . '">' . ( $l['b_enabled_bo'] == 1 ? __('Disable (oc-admin)') : __('Enable (oc-admin)') ) . '</a>' ;
        $options[] = '<a onclick="javascript:return confirm(\'' . osc_esc_js("This action can't be undone. Are you sure you want to continue?") . '\');" href="' . osc_admin_base_url(true) . '?page=languages&amp;action=delete&amp;id[]=' . $l['pk_c_code'] . '">' . __('Delete') . '</a>' ;

        $row[] = $l['s_name'] . ' <div class="datatable_wrapper"><div class="datatables_quick_edit" style="display:none;"> ' . implode(' &middot; ', $options) . ' </div></div>' ;
        $row[] = $l['s_short_name'] ;
        $row[] = $l['s_description'] ;
        $row[] = ( $l['b_enabled'] ? __('Yes') : __('No') ) ;
        $row[] = ( $l['b_enabled_bo'] ? __('Yes') : __('No') ) ;

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
                    "bProcessing": false,
                    "bServerSide":false,
                    "bPaginate": false,
                    "bFilter": false,
                    "bInfo": false,
                    "oLanguage": {
                        "oPaginate": {
                            "sNext" : "<?php echo osc_esc_html( __('Next') ) ; ?>",
                            "sPrevious" : "<?php echo osc_esc_html( __('Previous') ) ; ?>"
                        },
                        "sEmptyTable" : "<?php echo osc_esc_html( __('No data available in table') ) ; ?>",
                        "sInfo": "<?php echo osc_esc_html( sprintf( __('Showing %s to %s of %s entries'), '_START_', '_END_', '_TOTAL_') ) ; ?>",
                        "sInfoEmpty": "<?php echo osc_esc_html( __('No entries to show') ) ; ?>",
                        "sInfoFiltered": "<?php echo osc_esc_html( sprintf( __('(filtered from %s total entries)'), '_MAX_' ) ) ; ?>",
                        "sLoadingRecords": "<?php echo osc_esc_html( __('Loading...') ) ; ?>",
                        "sProcessing": "<?php echo osc_esc_html( __('Processing...') ) ; ?>",
                        "sSearch": "<?php echo osc_esc_html( __('Search') ) ; ?>",
                        "sZeroRecords": "<?php echo osc_esc_html( __('No matching records found') ) ; ?>"
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
                            "sTitle": "<?php echo osc_esc_html( __('Name') ) ; ?>",
                            "sWidth": "300px"
                        },
                        {
                            "sTitle": "<?php echo osc_esc_html( __('Short name') ) ; ?>"
                        },
                        {
                            "sTitle": "<?php echo osc_esc_html( __('Description') ) ; ?>"
                        },
                        {
                            "sTitle": "<?php echo osc_esc_html( __('Enabled (website)') ) ; ?>"
                        },
                        {
                            "sTitle": "<?php echo osc_esc_html( __('Enabled (oc-admin)') ) ; ?>"
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
                $('.filter').append( $("#add_language_button") ) ;
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
                    <h1 class="languages"><?php _e('Languages') ; ?></h1>
                </div>
                <?php osc_show_flash_message('admin') ; ?>
                <!-- datatables languages -->
                <form class="settings languages datatables" id="datatablesForm" action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
                    <input type="hidden" name="page" value="languages" />
                    <div id="bulk_actions">
                        <select name="action" id="bulk_actions" class="display">
                            <option value=""><?php _e('Bulk Actions') ; ?></option>
                            <option value="enable_selected"><?php _e('Enable (Website)') ; ?></option>
                            <option value="disable_selected"><?php _e('Disable (Website)') ; ?></option>
                            <option value="enable_bo_selected"><?php _e('Enable (oc-admin)') ; ?></option>
                            <option value="disable_bo_selected"><?php _e('Disable (oc-admin)') ; ?></option>
                            <option value="delete"><?php _e('Delete') ?></option>
                        </select> <input type="submit" id="bulk_apply" class="btn" value="<?php echo osc_esc_html( __('Apply') ) ; ?>">
                    </div>
                    <div id="add_language_button">
                        <a href="<?php echo osc_admin_base_url(true) ; ?>?page=languages&amp;action=add" class="btn" id="button_open"><?php _e('Add language') ; ?></a>
                    </div>
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="datatables_list"></table>
                </form>
                <!-- /datatables languages -->
            </div>
            <!-- /right container -->
        </div>
        <!-- /container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>