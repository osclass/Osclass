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
            $(document).ready(function(){
                if (typeof $.uniform != 'undefined') {
                    $('textarea, button,select, input:file').uniform();
                }
            });
        </script>
        <style>
            fieldset {
                width: 100px;
            }
            fieldset label{
               width: 100px;
               display: inline-block;
            }
            .row {
               height: 32px;
            }
            .row > label {
                width: 100px;
                padding-top: 10px;
                display: inline-block;
            }
            fieldset div.selector {
                width: 70px;
            }
            fieldset div.selector span{
                width: 40px;
            }
            #uniform-select_range{
                width: 70px;
            }
            #uniform-select_range span{
                width: 40px;
            }
       </style>
        <?php ItemForm::location_javascript('admin'); ?>
        <script type="text/javascript">
            $(function() {
                oTable = new osc_datatable();
                oTable.fnInit({
                    'idTable'       : 'datatables_list',
                    "sAjaxSource": "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=media&resourceId=<?php echo Params::getParam("id"); ?>",
                    'iDisplayLength': '10',
                    'iColumns'      : '5',
                    'oLanguage'     : {
                            "sInfo":         "<?php _e('Showing _START_ to _END_ of _TOTAL_ entries') ; ?>"
                            ,"sZeroRecords":  "<?php _e('No matching records found') ; ?>"
                            ,"sInfoFiltered": "(<?php _e('filtered from _MAX_ total entries') ; ?>)"
                            ,"oPaginate": {
                                        "sFirst":    "<?php _e('First') ; ?>",
                                        "sPrevious": "<?php _e('Previous') ; ?>",
                                        "sNext":     "<?php _e('Next') ; ?>",
                                        "sLast":     "<?php _e('Last') ; ?>"
                                    }
                    },
			        "aoColumns": [
				        {"sTitle": "<div style='margin-left: 8px;'><input id='check_all' type='checkbox' /></div>", 
				         "bSortable": false, 
				         "sClass": "center", 
				         "sWidth": "10px",
				         "bSearchable": false
				         },
				        {"sTitle": "<?php _e('File'); ?>",
				         "sWidth": "25%"
				        },
				        {"sTitle": "<?php _e('Action'); ?>",
				         "sWidth": "100px"
				        },
				        {"sTitle": "<?php _e('Attached to'); ?>","bSortable": true},
				        {"sTitle": "<?php _e('Date'); ?>",
				         "sWidth": "100px",
				         "sClass": "center",
				         "bSortable": true
				        }
			        ]
                });
            });
            
            $('#datatables_list tr').live('mouseover', function(event) {
                $('#datatable_wrapper', this).show();
                $('#datatables_quick_edit', this).show();
            });

            $('#datatables_list tr').live('mouseleave', function(event) {
                $('#datatable_wrapper', this).hide();
                $('#datatables_quick_edit', this).hide();
            });

        </script>
        
        <div id="content">
            <div id="separator"></div>

            <?php osc_current_admin_theme_path('include/backoffice_menu.php') ; ?>
            
            <div id="right_column">
                <div id="content_header" class="content_header">
                    <div style="float: left;">
                        <img src="<?php echo  osc_current_admin_theme_url('images/media-icon.png') ; ?>" title="" alt=""/>
                    </div>
                    <div id="content_header_arrow">&raquo; <?php _e('Media'); ?></div>
                    <a href="<?php echo osc_admin_base_url(true);?>?page=media&action=config" id="button_open"><?php _e('Settings') ; ?></a>
                    <div style="clear: both;"></div>
                </div>

                <div id="content_separator"></div>
                <?php osc_show_flash_message('admin') ; ?>
                <div>
                    <form id="datatablesForm" action="<?php echo osc_admin_base_url(true); ?>?page=media" method="post">
                        <input type="hidden" name="action" value="bulk_actions" />
                        <div style="clear:both;"></div>

                        <div class="top" style="margin-top:10px;">
                            <div style="float:left;"><?php _e('Show') ; ?>
                                <select class="display" id="select_range">
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                    <option value="20">20</option>
                                    <option value="100">100</option>
                                </select> <?php _e('entries') ; ?>
                            </div>
                            <div id="TableToolsToolbar">
                                <select id="bulk_actions" name="bulk_actions" class="display">
                                    <option value=""><?php _e('Bulk actions'); ?></option>
                                    <option value="delete_all"><?php _e('Delete') ?></option>
                                </select>
                                &nbsp;<button id="bulk_apply" class="display"><?php _e('Apply') ?></button>
                            </div>
                        </div>
                        <table cellpadding="0" cellspacing="0" border="0" class="display" id="datatables_list"></table>
                    </form>
                </div>

            </div> <!-- end of right column -->
            <script>
                $('#check_all').live('change',
                    function(){
                        if( $(this).attr('checked') ){
                            $('#'+oTable._idTable+" input").each(function(){
                                $(this).attr('checked','checked');
                            });
                        } else {
                            $('#'+oTable._idTable+" input").each(function(){
                                $(this).attr('checked','');
                            });
                        }
                    }
                );

            </script>
            <div style="clear: both;"></div>

        </div> <!-- end of container -->

        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>