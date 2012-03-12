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

    $plugins        = __get('plugins') ;
    $active_plugins = osc_get_plugins() ;

    $aData = array() ;
    foreach($plugins as $plugin) {
        $row       = array() ;
        $p_info    = osc_plugin_get_info($plugin) ;
        $installed = ( osc_plugin_is_installed($plugin) ?  1 : 0 ) ;
        $enabled   = ( osc_plugin_is_enabled($plugin) ? 1 : 0 ) ;

        $row[] = '<input type="hidden" name="installed" value="' . $installed . '" enabled="' . $enabled . '" />' . $p_info['plugin_name'] . ' <div id="datatables_quick_edit">' . ( osc_plugin_check_update($p_info['filename']) ? '<a href="' . osc_admin_base_url(true) . '?page=upgrade-plugin&amp;plugin=' . $p_info['filename'] . '">' . __("There's a new version. You should update!") . '</a>' : '' ) . '</div>' ;
        $row[] = $p_info['description'] ;
        $row[] = ( isset($active_plugins[$plugin . '_configure']) ? '<a href="' . osc_admin_base_url(true) . '?page=plugins&amp;action=admin&amp;plugin=' . $p_info['filename'] . '">' . __('Configure') . '</a>' : '' ) ;
        if( $installed ) {
            $row[] = ( $enabled ? '<a href="' . osc_admin_base_url(true) . '?page=plugins&amp;action=disable&amp;plugin=' . $p_info['filename'] . '">' . __('Disable') . '</a>' : '<a href="' . osc_admin_base_url(true) . '?page=plugins&amp;action=enable&amp;plugin=' . $p_info['filename'] . '">' . __('Enable') . '</a>') ;
        } else {
            $row[] = '' ;
        }
        $row[] = ( $installed ? '<a onclick="javascript:return confirm(\'' . osc_esc_js( __('This action can not be undone. Uninstalling plugins may result in a permanent lost of data. Are you sure you want to continue?') ) . '\')" href="' . osc_admin_base_url(true) . '?page=plugins&amp;action=uninstall&amp;plugin=' . $p_info['filename'] . '">' . __('Uninstall') . '</a>' : '<a href="' . osc_admin_base_url(true) . '?page=plugins&amp;action=install&amp;plugin=' . $p_info['filename'] . '">' . __('Install') . '</a>' ) ;

        $aData[] = $row ;
    }

?>
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
                    "sDom": "<'row'<'span6 length-menu'l><'span6 filter'>fr>t<'row'<'span6 info-results'i><'span6 paginate'p>>",
                    "sPaginationType": "bootstrap",
                    "bLengthChange": false,
                    "bProcessing": true,
                    "bServerSide":false,
                    "bPaginate": true,
                    "bFilter": false,
                    "aaSorting": [[4,'desc'], [3,'asc']],
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
                            "sTitle": "<?php echo osc_esc_html( __('Name') ) ; ?>"
                        },
                        {
                            "sTitle": "<?php echo osc_esc_html( __('Description') ) ; ?>"
                        },
                        {
                            "sTitle": "",
                            "sWidth": "65px"
                        },
                        {
                            "sTitle": "",
                            "sWidth": "65px"
                        },
                        {
                            "sTitle": "",
                            "sWidth": "65px"
                        }
                    ],
                    "fnDrawCallback": function() {
                        $('input:hidden[name="installed"]').each(function() {
                            $(this).parent().parent().children().css('background', 'none') ;
                            if( $(this).val() == '1' ) {
                                if( $(this).attr("enabled") == 1 ) {
                                    $(this).parent().parent().css('background-color', '#EDFFDF') ;
                                } else {
                                    $(this).parent().parent().css('background-color', '#FFFFDF') ;
                                }
                            } else {
                                $(this).parent().parent().css('background-color', '#FFF0DF') ;
                            }
                        }) ;
                    }
                });

                $('.filter').append( $("#add_plugin_button") ) ;
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
                    <h1 class="plugins"><?php _e('Plugins') ; ?></h1>
                </div>
                <?php osc_show_admin_flash_messages() ; ?>
                <!-- datatables plugins -->
                <div id="add_plugin_button">
                    <a href="<?php echo osc_admin_base_url(true); ?>?page=plugins&amp;action=add" class="btn" id="button_open"><?php _e('Add new plugin') ; ?></a>
                </div>
                <div class="datatables">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="datatables_list"></table>
                </div>
                <!-- /datatables plugins -->
            </div>
            <!-- /right container -->
        </div>
        <!-- /container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>