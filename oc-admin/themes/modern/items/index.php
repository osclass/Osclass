<?php
/*
 *      OSCLass – software for creating and publishing online classified
 *                           advertising platforms
 *
 *                        Copyright (C) 2010 OSCLASS
 *
 *       This program is free software: you can redistribute it and/or
 *     modify it under the terms of the GNU Affero General Public License
 *     as published by the Free Software Foundation, either version 3 of
 *            the License, or (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful, but
 *         WITHOUT ANY WARRANTY; without even the implied warranty of
 *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *             GNU Affero General Public License for more details.
 *
 *      You should have received a copy of the GNU Affero General Public
 * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
?>

<?php
    $last_item = end( $this->_get("items") ) ;
    $last_id = $last_item['pk_i_id'] ;
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <?php $this->osc_print_head() ; ?>
    </head>
    <body>
        <?php $this->osc_print_header() ; ?>
        <div id="update_version" style="display:none;"></div>
        <div class="Header">Items_PROBANDO</div>
        
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
                            ,"sAjaxSource": "<?php echo osc_base_url() ; ?>oc-admin/ajax/items_processing.php"
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

            <?php include_once osc_current_admin_theme_path() . 'include/backoffice_menu.php' ; ?>

            <div id="right_column">
                <div id="content_header" class="content_header">
                    <div style="float: left;"><img src="<?php echo  osc_current_admin_theme_url() ; ?>images/new-folder-icon.png" /></div>
                    <div id="content_header_arrow">&raquo; <?php _e('Manage items'); ?></div>
                    <div style="clear: both;"></div>
                </div>

                <div id="content_separator"></div>
                <?php osc_show_flash_message() ; ?>

                <form id="datatablesForm" action="items.php" method="post">
                            <div id="TableToolsToolbar">
                                <select id="bulk_actions" name="bulk_actions" class="display">
                                        <option value=""><?php _e('Bulk Actions'); ?></option>
                                        <option value="delete_all"><?php _e('Delete') ?></option>
                                        <option value="activate_all"><?php _e('Activate') ?></option>
                                        <option value="deactivate_all"><?php _e('Deactivate') ?></option>
                                        <option value="premium_all"><?php _e('Make Premium') ?></option>
                                        <option value="depremium_all"><?php _e('Demake Premium') ?></option>
                                </select>
                                &nbsp;<button id="bulk_apply" class="display"><?php _e('Apply') ?></button>
                            </div>
                            <div id="TableToolsLinks">
                                <strong><?php _e('Filter by') ?>:</strong> <a href="items.php"><?php _e('All') ?></a> |
                                <a href="?stat=pending"><?php _e('Pending') ?></a> |
                                <a href="?stat=spam"><?php _e('Spam') ?></a> |
                                <a href="?stat=duplicated"><?php _e('Duplicated') ?></a> |
                                <a href="?stat=bad"><?php _e('Bad classified') ?></a> |
                                <a href="?stat=offensive"><?php _e('Offensive') ?></a> |
                                <a href="?stat=expired"><?php _e('Expired') ?></a>
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

        <?php $this->osc_print_footer() ; ?>

    </body>
    
</html>