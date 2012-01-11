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

    $locales = __get('locales');
    $last = end($locales);
    $last_id = $last['pk_c_code'] ;
    $default_lang = osc_language() ;
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
                $.fn.dataTableExt.oApi.fnGetFilteredNodes = function ( oSettings )
                {
                    var anRows = [];
                    for ( var i=0, iLen=oSettings.aiDisplay.length ; i<iLen ; i++ )
                    {
                        var nRow = oSettings.aoData[ oSettings.aiDisplay[i] ].nTr ;
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
                        ,"sZeroRecords":  "<?php _e('No matching records found') ; ?>"
                        ,"sInfo":         "<?php _e('Showing _START_ to _END_ of _TOTAL_ entries') ; ?>"
                        ,"sInfoEmpty":    "<?php _e('Showing 0 to 0 of 0 entries'); ?>"
                        ,"sInfoFiltered": "(<?php _e('filtered from _MAX_ total entries'); ?>)"
                        ,"sInfoPostFix":  ""
                        ,"sSearch":       "<?php _e('Search'); ?>:"
                        ,"sUrl":          ""
                        ,"oPaginate": {
                            "sFirst":    "<?php _e('First'); ?>"
                            ,"sPrevious": "<?php _e('Previous'); ?>"
                            ,"sNext":     "<?php _e('Next'); ?>"
                            ,"sLast":     "<?php _e('Last'); ?>"
                        }
                        ,"sLengthMenu": '<div style="float:left;"><?php _e('Show'); ?> <select class="display" id="select_range">'
                                        + '<option value="10">10</option>'
                                        + '<option value="15">15</option>'
                                        + '<option value="20">20</option>'
                                        + '<option value="100">100</option>'
                                        + '</select> <?php _e('entries') ; ?>'
                        ,"sSearch": '<span class="ui-icon ui-icon-search" style="display: inline-block;"></span>'
                    }
                    ,"sPaginationType": "full_numbers"
                    ,"aaData": [
                            <?php foreach ($locales as $l) { ?>
                            [
                                "<input type='checkbox' name='id[]' value='<?php echo addslashes(osc_esc_html($l['pk_c_code'])); ?>' />"
                                ,"<?php echo addslashes(osc_esc_html($l['s_name'])); ?> <div id='datatables_quick_edit'> <a href='<?php echo osc_admin_base_url(true); ?>?page=languages&action=edit&amp;id=<?php echo $l['pk_c_code'] ; ?>'><?php _e('Edit') ; ?></a> | <a href='<?php echo osc_admin_base_url(true); ?>?page=languages&action=<?php echo $l['b_enabled'] == 1 ? 'disable_selected' : 'enable_selected'; ?>&amp;id[]=<?php echo $l['pk_c_code']; ?>'><?php _e($l['b_enabled'] == 1 ? 'Disable (website)' : 'Enable (website)'); ?></a> | <a href='<?php echo osc_admin_base_url(true); ?>?page=languages&action=<?php echo $l['b_enabled_bo'] == 1 ? 'disable_bo_selected' : 'enable_bo_selected'; ?>&amp;id[]=<?php echo $l['pk_c_code']; ?>'><?php _e($l['b_enabled_bo'] == 1 ? 'Disable (oc-admin)' : 'Enable (oc-admin)'); ?></a> | <a onclick=\"javascript:return confirm('<?php _e('This action can\\\\\'t be undone. Are you sure you want to continue?'); ?>')\" href='<?php echo osc_admin_base_url(true); ?>?page=languages&action=delete&amp;id[]=<?php echo $l['pk_c_code']; ?>'><?php _e('Delete'); ?></a></div>"
                                ,"<?php echo addslashes(osc_esc_html($l['s_short_name'])); ?>"
                                ,"<?php echo addslashes(osc_esc_html($l['s_description'])) ; ?>"
                                ,"<?php echo ($l['b_enabled']) ? __("Yes") : __("No"); ?>"
                                ,"<?php echo ($l['b_enabled_bo']) ? __("Yes") : __("No"); ?>"
                            ]  <?php echo $last_id != $l['pk_c_code'] ? ',' : ''; ?>
                            <?php } ?>
                        ],
                        "aoColumns": [
                            {   "sTitle": "<div style='margin-left: 8px;'><input id='check_all' type='checkbox' /></div>"
                                ,"bSortable": false
                                ,"sClass": "center"
                                ,"sWidth": "10px"
                                ,"bSearchable": false
                            }
                            ,{"sTitle": "<?php _e('Name'); ?>", "sWidth": "300px"}
                            ,{"sTitle": "<?php _e('Short name'); ?>"}
                            ,{"sTitle": "<?php _e('Description'); ?>"}
                            ,{"sTitle": "<?php _e('Enabled (website)'); ?>"}
                            ,{"sTitle": "<?php _e('Enabled (oc-admin)'); ?>"}
                        ]
                    });

                });
        </script>

        <script type="text/javascript" src="<?php echo osc_current_admin_theme_url('js/datatables.post_init.js') ; ?>"></script>

        <div id="content">
            <div id="separator"></div>

            <?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>

            <div id="right_column">

                <div id="content_header" class="content_header">
                    <div style="float: left;">
                        <img src="<?php echo osc_current_admin_theme_url('images/icon-language.png') ; ?>" title="" alt=""/>
                    </div>
                    <div id="content_header_arrow">&raquo; <?php _e('Languages'); ?></div>
                    <a href="<?php echo osc_admin_base_url(true); ?>?page=languages&action=add" id="button_open"><?php _e('Add') ; ?></a>
                    <div style="clear: both;"></div>
                </div>

                <div id="content_separator"></div>

                <?php osc_show_flash_message('admin') ; ?>

                <div id="TableToolsToolbar">
                    <select name="action" id="bulk_actions" class="display">
                        <option value=""><?php _e('Bulk Actions') ; ?></option>
                        <option value="enable_selected"><?php _e('Enable (Website)') ; ?></option>
                        <option value="disable_selected"><?php _e('Disable (Website)') ; ?></option>
                        <option value="enable_bo_selected"><?php _e('Enable (oc-admin)') ; ?></option>
                        <option value="disable_bo_selected"><?php _e('Disable (oc-admin)') ; ?></option>
                        <option value="delete"><?php _e('Delete') ?></option>
                    </select>
                    &nbsp;<button id="bulk_apply" class="display"><?php _e('Apply') ; ?></button>
                </div>

                <form id="datatablesForm" action="<?php echo osc_admin_base_url(true); ?>?page=languages" method="post">
                    <table cellpadding="0" cellspacing="0" border="0" class="display" id="datatables_list"></table>
                    <br />
                    <div style="clear: both;"></div>
                </form>
            </div> <!-- end of right column -->

            <script type="text/javascript">
                $(document).ready(function() {
                    $('#datatables_list tr').live('mouseover', function(event) {
                        $('#datatables_quick_edit', this).show() ;
                    });

                    $('#datatables_list tr').live('mouseleave', function(event) {
                        $('#datatables_quick_edit', this).hide() ;
                    });
                });
            </script>
        </div> <!-- end of container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>