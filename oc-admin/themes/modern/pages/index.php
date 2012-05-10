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

    $prefLocale = osc_current_user_locale() ;

    $aData = array() ;
    while( osc_has_static_pages() ) {
        $row  = array() ;
        $page = osc_static_page();

        $content = array() ;
        $page = osc_static_page() ;
        if( isset($page['locale'][$prefLocale]) && !empty($page['locale'][$prefLocale]['s_title']) ) {
            $content = $page['locale'][$prefLocale] ;
        } else {
            $content = current($page['locale']) ;
        }

        $options   = array() ;
        $options[] = '<a href="' . osc_static_page_url() . '">' . __('View page') . '</a>' ;
        $options[] = '<a href="' . osc_admin_base_url(true) . '?page=pages&amp;action=edit&amp;id=' . osc_static_page_id() . '">' . __('Edit') . '</a>' ;
        if( !$page['b_indelible'] ) {
            $options[] = '<a onclick="javascript:return confirm(\'' . osc_esc_js("This action can't be undone. Are you sure you want to continue?") . '\')" href="' . osc_admin_base_url(true) . '?page=pages&amp;action=delete&amp;id=' . osc_static_page_id() . '">' . __('Delete') . '</a>' ;
        }

        $row[] = '<input type="checkbox" name="id[]"" value="' . osc_static_page_id() . '"" />' ;
        $row[] = $page['s_internal_name'] . '<div id="datatables_quick_edit" style="display: none;">' . implode(' &middot; ', $options) . '</div>' ;
        $row[] = $content['s_title'] ;
        $row[] = osc_static_page_order() . ' <img id="up" onclick="order_up(' . osc_static_page_id() . ');" style="cursor:pointer; width:15px; height:15px;" src="' . osc_current_admin_theme_url('images/arrow_up.png') . '"/> <br/><img id="down" onclick="order_down(' . osc_static_page_id() . ');" style="cursor:pointer; width:15px; height:15px; margin-left: 10px;" src="' . osc_current_admin_theme_url('images/arrow_down.png') .'"/>' ;
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
            function order_up(id) {
                $('#datatables_list_processing').show() ;
                $.ajax({
                    url: "<?php echo osc_admin_base_url(true)?>?page=ajax&action=order_pages&id="+id+"&order=up",
                    success: function(res) {
                        oTable.fnClearTable();
                        json = eval( '(' + res + ')') ;
                        oTable.fnAddData(json);
                        $('#datatables_list_processing').hide();
                    },
                    error: function(){
                        $('#datatables_list_processing').hide();
                    }
                });
            }
            
            function order_down(id) {
                $('#datatables_list_processing').show();
                $.ajax({
                    url: "<?php echo osc_admin_base_url(true)?>?page=ajax&action=order_pages&id="+id+"&order=down",
                    success: function(res){
                        oTable.fnClearTable();
                        json = eval( '(' + res + ')') ;
                        oTable.fnAddData(json);
                        $('#datatables_list_processing').hide();
                    },
                    error: function(){
                        $('#datatables_list_processing').hide();
                    }
                });
            }

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
                            "sTitle": '<input id="check_all" type="checkbox" />',
                            "bSortable": false,
                            "sWidth": "10px",
                            "bSearchable": false
                        },
                        {
                            "sTitle": "<?php echo osc_esc_html( __('Internal name') ) ; ?>",
                            "bSortable": false,
                            "sWidth": "30%"
                        },
                        {
                            "sTitle": "<?php echo osc_esc_html( __('Title') ) ; ?>",
                            "bSortable": false
                        },
                        {
                            "sTitle": "<?php echo osc_esc_html( __('Order') ) ; ?>",
                            "bSortable": false,
                            "sWidth": "30px"
                        }
                    ],
                    "aaSorting": [[3,'asc']]
                });

                $('.length-menu').append( $("#bulk_actions") ) ;
                $('.filter').append( $("#add_page_button") ) ;

                $('.datatables tr').live('mouseover', function(event) {
                    $('#datatables_quick_edit', this).show();
                });

                $('.datatables tr').live('mouseleave', function(event) {
                    $('#datatables_quick_edit', this).hide();
                });

                $('#up').live('mouseover', function(event) {
                    $(this).attr('src', '<?php echo osc_current_admin_theme_url('images/arrow_up_dark.png');?>');
                });
                $('#down').live('mouseover', function(event) {
                    $(this).attr('src', '<?php echo osc_current_admin_theme_url('images/arrow_down_dark.png');?>');
                });
                $('#up').live('mouseleave', function(event) {
                    $(this).attr('src', '<?php echo osc_current_admin_theme_url('images/arrow_up.png');?>');
                });
                $('#down').live('mouseleave', function(event) {
                    $(this).attr('src', '<?php echo osc_current_admin_theme_url('images/arrow_down.png');?>');
                });
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
                    <h1 class="pages"><?php _e('Pages') ; ?></h1>
                </div>
                <?php osc_show_flash_message('admin') ; ?>
                <!-- datatables pages -->
                <form class="pages datatables" id="datatablesForm" action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
                    <input type="hidden" name="page" value="pages" />
                    <div id="bulk_actions">
                        <label>
                            <select name="action" id="action" class="display">
                                <option value=""><?php _e('Bulk actions') ; ?></option>
                                <option value="delete"><?php _e('Delete') ; ?></option>
                            </select> <input type="submit" id="bulk_apply" class="btn" value="<?php echo osc_esc_html( __('Apply') ) ; ?>">
                        </label>
                    </div>
                    <div id="add_page_button">
                        <a href="<?php echo osc_admin_base_url(true); ?>?page=pages&amp;action=add" class="btn"><?php _e('Create page') ; ?></a>
                    </div>
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="datatables_list"></table>
                </form>
                <!-- /datatables pages -->
            </div>
            <!-- /right container -->
        </div>
        <!-- /container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>