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

    $comments = __get('comments') ;

    $aData = array() ;
    foreach($comments as $comment) {
        $row = array() ;

        $options = array() ;
        $options[] = '<a href="' . osc_admin_base_url(true) . '?page=comments&amp;action=comment_edit&amp;id=' . $comment['pk_i_id'] . '" id="dt_link_edit">' . __('Edit') . '</a>' ;
        if( $comment['b_active'] ) {
            $options[] = '<a href="' . osc_admin_base_url(true) . '?page=comments&amp;action=status&amp;id=' . $comment['pk_i_id'] . '&amp;value=INACTIVE">' . __('Deactivate') . '</a>' ;
        } else {
            $options[] = '<a href="' . osc_admin_base_url(true) . '?page=comments&amp;action=status&amp;id=' . $comment['pk_i_id'] .'&amp;value=ACTIVE">' . __('Activate') . '</a>' ;
        }
        if( $comment['b_enabled'] ) {
            $options[] = '<a href="' . osc_admin_base_url(true) . '?page=comments&amp;action=status&amp;id=' . $comment['pk_i_id'] . '&amp;value=DISABLE">' . __('Unblock') . '</a>' ;
        } else {
            $options[] = '<a href="' . osc_admin_base_url(true) . '?page=comments&amp;action=status&amp;id=' . $comment['pk_i_id'] . '&amp;value=ENABLE">' . __('Block') . '</a>' ;
        }
        $options[] = '<a onclick="javascript:return confirm(\'' . osc_esc_js( __("This action can't be undone. Are you sure you want to continue?") ) . '\')" href="' . osc_admin_base_url(true) . '?page=comments&amp;action=delete&amp;id=' . $comment['pk_i_id'] .'" id="dt_link_delete">' . __('Delete') . '</a>' ;

        $row[] = '<input type="checkbox" name="id[]" value="' . $comment['pk_i_id']  . '" />' ;
        if( empty($comment['s_author_name']) ) {
            $user = User::newInstance()->findByPrimaryKey( $comment['fk_i_user_id'] );
            $comment['s_author_name'] = $user['s_email'];
        }
        $row[] = $comment['s_author_name'] . ' (<a target="_blank" href="' . osc_item_url_ns( $comment['fk_i_item_id'] ) . '">' . $comment['s_title'] . '</a>)<div class="datatables_quick_edit" style="display:none;">' . implode(' &middot; ', $options) . '</div>' ;
        $row[] = $comment['s_body'] ;
        $row[] = $comment['dt_pub_date'] ;

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
                    "bFilter": false,
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
                            "sTitle": "<?php echo osc_esc_html( __('Author') ) ; ?>",
                            "sWidth": "auto"
                        },
                        {
                            "sTitle": "<?php echo osc_esc_html( __('Comment') ) ; ?>",
                            "bSortable": false
                        },
                        {
                            "sTitle": "<?php echo osc_esc_html( __('Date') ) ; ?>",
                            "sWidth": "150px",
                            "bSearchable": false
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
                    <h1 class="comments"><?php _e('Manage Comments') ; ?></h1>
                </div>
                <?php osc_show_flash_message('admin') ; ?>
                <!-- datatables comments -->
                <form class="comments datatables" id="datatablesForm" action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
                    <input type="hidden" name="page" value="comments" />
                    <input type="hidden" name="action" value="bulk_actions" />
                    <div id="bulk_actions">
                        <label>
                            <select id="bulk_actions" name="bulk_actions" class="display">
                                <option value=""><?php _e('Bulk actions') ; ?></option>
                                <option value="delete_all"><?php _e('Delete') ; ?></option>
                                <option value="activate_all"><?php _e('Activate') ; ?></option>
                                <option value="deactivate_all"><?php _e('Deactivate') ; ?></option>
                                <option value="enable_all"><?php _e('Block') ; ?></option>
                                <option value="disable_all"><?php _e('Unblock') ; ?></option>
                            </select> <input type="submit" id="bulk_apply" class="btn" value="<?php echo osc_esc_html( __('Apply') ) ; ?>">
                        </label>
                    </div>
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="datatables_list"></table>
                </form>
                <!-- /datatables comments -->
            </div>
            <!-- /right container -->
        </div>
        <!-- /container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>