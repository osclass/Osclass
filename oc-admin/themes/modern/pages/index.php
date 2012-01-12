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

    $pages = __get("pages");
    $prefLocale = __get("prefLocale");
    $last = end($pages); $last_id = $last['pk_i_id'];

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
            function order_up(id) {
                $('#datatables_list_processing').show();
                $.ajax({
                    url: "<?php echo osc_admin_base_url(true)?>?page=ajax&action=order_pages&id="+id+"&order=up",
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
                $.fn.dataTableExt.oApi.fnGetFilteredNodes = function ( oSettings ) {
                    var anRows = [];
                    for ( var i=0, iLen=oSettings.aiDisplay.length ; i<iLen ; i++ ){
                        var nRow = oSettings.aoData[ oSettings.aiDisplay[i] ].nTr;
                        anRows.push( nRow );
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
                                       '</select> <?php _e('entries'); ?>',
                        "sSearch": '<span class="ui-icon ui-icon-search" style="display: inline-block;"></span>'
                     },
                    "sPaginationType": "full_numbers",
                    "aaData": [
                        <?php if(osc_count_static_pages()>0) {
                        while(osc_has_static_pages()) { ?>
                        <?php
                            $body = array();
                            $page = osc_static_page();
                            if(isset($page['locale'][$prefLocale]) && !empty($page['locale'][$prefLocale]['s_title'])) {
                                $body = $page['locale'][$prefLocale];
                            } else {
                                $body = current($page['locale']);
                            }
                            $p_body = addslashes(trim(strip_tags($body['s_title'])));
                        ?>
                                  [
                                    "<input type='checkbox' name='id[]' value='<?php echo osc_static_page_id(); ?>' />",
                                    "<?php echo addslashes(osc_esc_html($page['s_internal_name'])); ?><div id='datatables_quick_edit'>" +
                                    "<a href='<?php echo osc_static_page_url(); ?>'>" +
                                    "<?php _e('View page'); ?></a> | " +
                                    "<a href='<?php echo osc_admin_base_url(true); ?>?page=pages&action=edit&id=<?php echo osc_static_page_id(); ?>'>" +
                                    "<?php _e('Edit'); ?></a><?php if(!$page['b_indelible']) { ?> | " +
                                    "<a onclick=\"javascript:return confirm('" +
                                    "<?php _e('This action can\\\\\'t be undone. Are you sure you want to continue?'); ?>')\"" +
                                    "href='<?php echo osc_admin_base_url(true); ?>?page=pages&action=delete&id=<?php echo osc_static_page_id(); ?>'>" +
                                    "<?php _e('Delete'); ?></a><?php }; ?></div>",
                                    '<?php echo $p_body; ?>',
                                    "<img id='up' onclick='order_up(<?php echo osc_static_page_id(); ?>);' style='cursor:pointer;width:15;height:15px;' src='<?php echo osc_current_admin_theme_url('images/arrow_up.png');?>'/> <br/><img id='down' onclick='order_down(<?php echo osc_static_page_id(); ?>);' style='cursor:pointer;width:15;height:15px;' src='<?php echo osc_current_admin_theme_url('images/arrow_down.png');?>'/>"
                                  ] <?php echo $last_id != osc_static_page_id() ? ',' : ''; ?>
                        <?php };}; ?>
                              ],
                    "aoColumns": [
                        {"sTitle": "<div style='margin-left: 8px;'><input id='check_all' type='checkbox' /></div>",
                         "bSortable": false,
                         "sClass": "center",
                         "sWidth": "10px",
                         "bSearchable": false
                         },
                        {"sTitle": "<?php _e('Name'); ?>",
                         "bSortable": false,
                         "sWidth": "30%"
                        },
                        {"sTitle": "<?php _e('Description'); ?>",
                            "bSortable": false
                        },
                        {"sTitle": "Order",
                         "bSortable": false,
                         "sWidth": "30px"
                        }
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
                    <div style="float: left;"><img src="<?php echo osc_current_admin_theme_url('images/pages-icon.png') ; ?>" alt="" title=""/></div>
                    <div id="content_header_arrow">&raquo; <?php _e('Pages') ; ?></div>
                    <a href="<?php echo osc_admin_base_url(true); ?>?page=pages&action=add" id="button_open"><?php _e('Create page') ; ?></a>
                    <div style="clear: both;"></div>
                </div>

                <div id="content_separator"></div>
                <?php osc_show_flash_message('admin') ; ?>

                <div id="TableToolsToolbar">
                    <select id="bulk_actions" class="display">
                        <option value=""><?php _e('Bulk actions') ; ?></option>
                        <option value="delete_all"><?php _e('Delete') ; ?></option>
                    </select>
                    &nbsp;
                    <button id="bulk_apply" class="display"><?php _e('Apply') ; ?></button>
                </div>

                

                <form id="datatablesForm" action="<?php echo osc_admin_base_url(true); ?>?page=pages" method="post">
                    <input type="hidden" name="action" value="delete" />
                    <div id="datatables_list_processing" class="dataTables_processing" style="display:none;z-index:3;"><?php _e('Processing'); ?>...</div>
                    <table cellpadding="0" cellspacing="0" border="0" class="display" id="datatables_list"></table>
                    <br />
                </form>
                <div style="clear: both;"></div>
            </div> <!-- end of right column -->
            <script type="text/javascript">
                $(document).ready(function() {
                    $('#datatables_list tr').live('mouseover', function(event) {
                        $('#datatables_quick_edit', this).show();
                    });

                    $('#datatables_list tr').live('mouseleave', function(event) {
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
            <div style="clear: both;"></div>
        </div> <!-- end of container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>