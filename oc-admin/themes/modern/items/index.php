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

    $items = __get("items") ;
    $last_item = end( $items ) ;
    $last_id = $last_item['pk_i_id'] ;
    $stat = __get("stat");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <div id="update_version" style="display:none;"></div>
        <div class="Header"><?php _e("Items");?></div>
        
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
                            "bProcessing": true
                            ,"bServerSide": true
                            ,"sAjaxSource": "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=items&catId=<?php echo Params::getParam('catId');?>"
                                            <?php if($stat) { ?>
                                                ,"fnServerData": function ( sSource, aoData, fnCallback ) {
                                                        /* Add some extra data to the sender */
                                                        aoData.push( { "name": "stat", "value": "<?php echo $stat ; ?>" } );
                                                        $.getJSON( sSource, aoData, function (json) {
                                                                /* Do whatever additional processing you want on the callback, then tell DataTables */
                                                                fnCallback(json)
                                                        } );
                                                }
                                            <?php } ?>
                                            ,"bAutoWidth": false
                                            ,"sDom": '<"top"fl>rt<"bottom"ip<"clear">'
                                            ,"oLanguage": {
                                                    "sProcessing":   "<?php _e('Processing') ; ?>..."
                                                    ,"sLengthMenu":   "<?php _e('Show _MENU_ entries') ; ?>"
                                                    ,"sZeroRecords":  "<?php _e('No matching records found') ; ?>"
                                                    ,"sInfo":         "<?php _e('Showing _START_ to _END_ of _TOTAL_ entries') ; ?>"
                                                    ,"sInfoEmpty":    "<?php _e('Showing 0 to 0 of 0 entries') ; ?>"
                                                    ,"sInfoFiltered": "(<?php _e('filtered from _MAX_ total entries') ; ?>)"
                                                    ,"sInfoPostFix":  ""
                                                    ,"sSearch":       "<?php _e('Search') ; ?>:"
                                                    ,"sUrl":          ""
                                                    ,"oPaginate": {
                                                        "sFirst":    "<?php _e('First') ; ?>",
                                                        "sPrevious": "<?php _e('Previous') ; ?>",
                                                        "sNext":     "<?php _e('Next') ; ?>",
                                                        "sLast":     "<?php _e('Last') ; ?>"
                                                    }
                                                    ,"sLengthMenu": '<div style="float:left;"><?php _e('Show') ; ?> <select class="display" id="select_range">'+
                                                                                                                        '<option value="10">10</option>'+
                                                                                                                        '<option value="15">15</option>'+
                                                                                                                        '<option value="20">20</option>'+
                                                                                                                        '<option value="100">100</option>'+
                                                                                                                   '</select> <?php _e('entries') ; ?>'
                                                    ,"sSearch": '<span class="ui-icon ui-icon-search" style="display: inline-block;"></span>'
                                            }
                                            ,"sPaginationType": "full_numbers"
                                            ,"aoColumns": [
                                                {"sTitle": "<div style='margin-left: 8px;'><input id='check_all' type='checkbox' /></div>"
                                                 ,"bSortable": false
                                                 ,"sClass": "center"
                                                 ,"sWidth": "10px"
                                                 ,"bSearchable": false
                                                }
                                                ,{"sTitle": "<?php _e('Title') ; ?>"
                                                  ,"bSortable": false
                                                  ,"sWidth": "25%"
                                                 }
                                                <?php if($stat) { ?>
                                                    ,{"sTitle": "<?php _e('Count') ; ?>"
                                                     ,"bSortable": false
                                                    }
                                                <?php } else { ?>
                                                    ,{"sTitle": "<?php _e('Description') ; ?>"
                                                     ,"bSortable": false
                                                    }
                                                <?php } ?>
                                                ,{"sTitle": "<?php _e('Category') ; ?>"
                                                 ,"sWidth": "20%"
                                                 ,"bSortable": false
                                                }
                                                ,{"sTitle": "<?php _e('Date') ; ?>"
                                                 ,"sWidth": "100px"
                                                 ,"bSearchable": false
                                                }
                                            ]
                        });
                        oTable.fnSort ( [[0, 'desc']] );
            });
        </script>
        <script type="text/javascript" src="<?php echo  osc_current_admin_theme_url() ; ?>js/datatables.post_init.js"></script>

        <div id="content">
            <div id="separator"></div>

            <?php osc_current_admin_theme_path('include/backoffice_menu.php') ; ?>

            <div id="right_column">
                <div id="content_header" class="content_header">
                    <div style="float: left;">
                        <img src="<?php echo  osc_current_admin_theme_url() ; ?>images/new-folder-icon.png" title="" alt=""/>
                    </div>
                    <div id="content_header_arrow">&raquo; <?php _e('Manage items'); ?></div>
                    <div style="clear: both;"></div>
                </div>

                <div id="content_separator"></div>
                <?php osc_show_flash_message('admin') ; ?>

                <form id="datatablesForm" action="<?php echo osc_admin_base_url(true); ?>?page=items" method="post">
                            <div id="TableToolsToolbar">
                                <select id="bulk_actions" name="bulk_actions" class="display">
                                        <option value=""><?php _e('Bulk actions'); ?></option>
                                        <option value="delete_all"><?php _e('Delete') ?></option>
                                        <option value="activate_all"><?php _e('Activate') ?></option>
                                        <option value="deactivate_all"><?php _e('Deactivate') ?></option>
                                        <option value="enable_all"><?php _e('Enable') ?></option>
                                        <option value="disable_all"><?php _e('Disable') ?></option>
                                        <option value="premium_all"><?php _e('Mark as premium') ?></option>
                                        <option value="depremium_all"><?php _e('Unmark as premium') ?></option>
                                </select>
                                &nbsp;<button id="bulk_apply" class="display"><?php _e('Apply') ?></button>
                            </div>
                            <div id="TableToolsLinks">
                                <strong><?php _e('Filter by') ?>:</strong> 
                                <a href="<?php echo osc_admin_base_url(true); ?>?page=items" <?php if($stat == '') {echo "style='font-weight:bold;'";} ?> ><?php _e('All') ?></a> |
                                <a href="<?php echo osc_admin_base_url(true); ?>?page=items&stat=pending" <?php if($stat == 'pending') {echo "style='font-weight:bold;'";} ?> ><?php _e('Pending') ?></a> |
                                <a href="<?php echo osc_admin_base_url(true); ?>?page=items&stat=spam" <?php if($stat == 'spam') {echo "style='font-weight:bold;'";} ?> ><?php _e('Spam') ?></a> |
                                <a href="<?php echo osc_admin_base_url(true); ?>?page=items&stat=duplicated" <?php if($stat == 'duplicated') {echo "style='font-weight:bold;'";} ?> ><?php _e('Duplicated') ?></a> |
                                <a href="<?php echo osc_admin_base_url(true); ?>?page=items&stat=bad" <?php if($stat == 'bad') {echo "style='font-weight:bold;'";} ?> ><?php _e('Misclassified') ?></a> |
                                <a href="<?php echo osc_admin_base_url(true); ?>?page=items&stat=offensive" <?php if($stat == 'offensive') {echo "style='font-weight:bold;'";} ?> ><?php _e('Offensive') ?></a> |
                                <a href="<?php echo osc_admin_base_url(true); ?>?page=items&stat=expired" <?php if($stat == 'expired') {echo "style='font-weight:bold;'";} ?> ><?php _e('Expired') ?></a>
                                <a href="<?php echo osc_admin_base_url(true); ?>?page=items&stat=enabled" <?php if($stat == 'enabled') {echo "style='font-weight:bold;'";} ?> ><?php _e('Enabled') ?></a>
                                <a href="<?php echo osc_admin_base_url(true); ?>?page=items&stat=disabled" <?php if($stat == 'disabled') {echo "style='font-weight:bold;'";} ?> ><?php _e('Disabled') ?></a>
                            </div>
                <input type="hidden" name="action" value="bulk_actions" />
                    <table cellpadding="0" cellspacing="0" border="0" class="display" id="datatables_list"></table>
                    <br />
                </form>

            </div> <!-- end of right column -->

            <script type="text/javascript">
                $(document).ready(function() {
                    $('#datatables_list tr').live('mouseover', function(event) {
                        $('#datatable_wrapper', this).show();
                        $('#datatables_quick_edit', this).show();
                    });

                    $('#datatables_list tr').live('mouseleave', function(event) {
                        $('#datatable_wrapper', this).hide();
                        $('#datatables_quick_edit', this).hide();
                    });
                });
            </script>

            <div style="clear: both;"></div>

        </div> <!-- end of container -->

        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>
