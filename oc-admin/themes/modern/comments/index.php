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

    $comments = __get('comments');
    if(!is_array($comments)) { $comments = array(); };
    $last = end($comments);
    $last_id = $last['pk_i_id'] ;
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

                    for (var i=0, iLen=oSettings.aiDisplay.length ; i<iLen ; i++ ) {
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
                            '</select> <?php _e('entries'); ?>'
                            ,"sSearch": '<span class="ui-icon ui-icon-search" style="display: inline-block;"></span>'
                     }
                    ,"sPaginationType": "full_numbers"
                    ,"aaData": [
                        <?php foreach(__get('comments') as $c) { ?>
                            [
                                "<input type='checkbox' name='id[]' value='<?php echo $c['pk_i_id']; ?>' />"
                                ,"<?php echo addcslashes($c['s_author_name'],'"'); ?> (<a target='_blank' href='<?php echo osc_item_url_ns( $c['fk_i_item_id'] ) ; ?>'><?php echo $c['s_title']; ?></a>)<div id='datatables_quick_edit'><a href='<?php echo osc_admin_base_url(true) ; ?>?page=comments&action=comment_edit&id=<?php echo $c['pk_i_id'] ; ?>' id='dt_link_edit'><?php _e('Edit'); ?></a><?php
                                    if(isset($c['b_active']) && ($c['b_active'] == 1)) {
                                        echo ' | <a href=\'' . osc_admin_base_url(true) . '?page=comments&action=status&id='. $c['pk_i_id'] .'&value=INACTIVE\'>' . __('Deactivate') . '</a>' ;
                                    } else if (isset($c['b_active']) && ($c['b_active'] == 0)) {
                                        echo ' | <a href=\'' . osc_admin_base_url(true) . '?page=comments&action=status&id='. $c['pk_i_id'] .'&value=ACTIVE\'>' . __('Activate') . '</a>' ;
                                    }
                                    if(isset($c['b_enabled']) && ($c['b_enabled'] == 1)) {
                                        echo ' | <a href=\'' . osc_admin_base_url(true) . '?page=comments&action=status&id='. $c['pk_i_id'] .'&value=DISABLE\'>' . __('Disable') . '</a>' ;
                                    } else if (isset($c['b_enabled']) && ($c['b_enabled'] == 0)) {
                                        echo ' | <a href=\'' . osc_admin_base_url(true) . '?page=comments&action=status&id='. $c['pk_i_id'] .'&value=ENABLE\'>' . __('Enable') . '</a>' ;
                                    }
                                    
                                    ?> | <a onclick=\"javascript:return confirm('<?php _e('This action can\'t be undone. Are you sure you want to continue?'); ?>')\" href='<?php echo osc_admin_base_url(true) ; ?>?page=comments&action=delete&id=<?php echo $c['pk_i_id'] ; ?>' id='dt_link_delete'><?php _e('Delete') ; ?></a></div>"
                                ,"<?php echo addcslashes(preg_replace('|\s+|',' ',$c['s_body']),'"'); ?>"
                                ,"<?php echo $c['dt_pub_date'] ; ?>"
                            ] <?php echo $last_id != $c['pk_i_id'] ? ',' : ''; ?>
                        <?php } ?>
                    ]

                    ,"aoColumns": [
                        {"sTitle": "<div style='margin-left: 8px;'><input id='check_all' type='checkbox' /></div>"
                         ,"bSortable": false
                         ,"sClass": "center"
                         ,"sWidth": "10px"
                         ,"bSearchable": false
                         }
                        ,{"sTitle": "<?php _e('Author'); ?>"
                         ,"sWidth": "auto"
                        }
                        ,{"sTitle": "<?php _e('Comment'); ?>"}
                        ,{"sTitle": "<?php _e('Date'); ?>"
                         ,"sWidth": "100px"
                         ,"sClass": "center"
                         ,"bSearchable": false
                        }
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
                        <img src="<?php echo  osc_current_admin_theme_url('images/comments-icon2.png') ; ?>" title="" alt=""/>
                    </div>
                    <div id="content_header_arrow">&raquo; <?php _e('Manage Comments') ; ?></div>
                    <div style="clear: both;"></div>
                </div>

                <div id="content_separator"></div>
                <?php osc_show_flash_message('admin') ; ?>

                <form id="datatablesForm" action="<?php osc_admin_base_url(true) ; ?>" method="post">
                <div id="TableToolsToolbar">
                <select id="bulk_actions" name="bulk_actions" class="display">
                    <option value=""><?php _e('Bulk actions'); ?></option>
                    <option value="delete_all"><?php _e('Delete') ?></option>
                    <option value="activate_all"><?php _e('Activate') ?></option>
                    <option value="deactivate_all"><?php _e('Deactivate') ?></option>
                    <option value="enable_all"><?php _e('Enable') ?></option>
                    <option value="disable_all"><?php _e('Disable') ?></option>
                </select>
                &nbsp;<button id="bulk_apply" class="display"><?php _e('Apply') ?></button>
                </div>
                <input type="hidden" name="action" value="bulk_actions" />
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