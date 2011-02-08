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

<?php defined('ABS_PATH') or die(__('Invalid OSClass request.')); ?>
<?php
    $last = end($locales);
    $last_id = $last['pk_c_code'] ;
    $default_lang = osc_language() ;
?>
<script>
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
                        "<input type='checkbox' name='id[]' value='<?php echo $l['pk_c_code']; ?>' />"
                        ,"<?php echo $l['s_name']; ?> <div id='datatables_quick_edit'> <a href='languages.php?action=edit&amp;id=<?php echo $l['pk_c_code'] ; ?>'><?php _e('Edit') ; ?></a> | <a href='languages.php?action=enable&amp;id=<?php echo $l['pk_c_code']; ?>&enabled=<?php echo $l['b_enabled'] == 1 ? '0' : '1'; ?>'><?php _e($l['b_enabled'] == 1 ? 'Disable (website)' : 'Enable (website)'); ?></a> | <a href='languages.php?action=enable_bo&amp;id=<?php echo $l['pk_c_code']; ?>&enabled=<?php echo $l['b_enabled_bo'] == 1 ? '0' : '1'; ?>'><?php _e($l['b_enabled_bo'] == 1 ? 'Disable (oc-admin)' : 'Enable (oc-admin)'); ?></a> | <a onclick=\"javascript:return confirm('<?php _e('This action can not be undone. Are you sure you want to continue?'); ?>')\" href='languages.php?action=delete&amp;code[]=<?php echo $l['pk_c_code']; ?>'><?php _e('Delete'); ?></a></div>"
                        ,"<?php echo $l['s_short_name']; ?>"
                        ,"<?php echo $l['s_description']; ?>"
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
                    ,{"sTitle": "<?php _e('Short Name'); ?>"}
                    ,{"sTitle": "<?php _e('Description'); ?>"}
                    ,{"sTitle": "<?php _e('Enabled (website)'); ?>"}
                    ,{"sTitle": "<?php _e('Enabled (oc-admin)'); ?>"}
                ]
            });

        });
    </script>
    <script type="text/javascript" src="<?php echo osc_current_admin_theme_url() ; ?>/js/datatables.post_init.js"></script>

    <div id="content">
        <div id="separator"></div>

        <?php include_once osc_current_admin_theme_path() . '/include/backoffice_menu.php'; ?>

        <div id="right_column">

            <div id="content_header" class="content_header">
                <div style="float: left;"><img src="<?php echo osc_current_admin_theme_url() ; ?>/images/back_office/icon-language.png" /></div>
                <div id="content_header_arrow">&raquo; <?php _e('Languages'); ?></div>
                <a href="languages.php?action=add" id="button_open"><?php echo osc_lowerCase(__('Add')); ?></a>
                <div style="clear: both;"></div>
            </div>

            <div id="content_separator"></div>

            <?php osc_show_flash_messages() ; ?>

            <div id="TableToolsToolbar">
                <select name="action" id="bulk_actions" class="display">
                    <option value=""><?php _e('Bulk Actions') ; ?></option>
                    <option value="enable_selected"><?php _e('Enable (Website)') ; ?></option>
                    <option value="disable_selected"><?php _e('Disable (Website)') ; ?></option>
                    <option value="enable_bo_selected"><?php _e('Enable (oc-admin)') ; ?></option>
                    <option value="disable_bo_selected"><?php _e('Disable (oc-admin)') ; ?></option>
                    <option value="delete_all"><?php _e('Delete') ?></option>
                </select>
                &nbsp;<button id="bulk_apply" class="display"><?php _e('Apply') ; ?></button>
            </div>

            <form id="datatablesForm" action="languages.php" method="post">
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