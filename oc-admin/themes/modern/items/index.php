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

    $users      = __get("users");
    $stat       = __get("stat") ;
    $categories = __get("categories");
    $countries  = __get("countries");
    $regions    = __get("regions");
    $cities     = __get("cities");
    
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
                    "sAjaxSource": "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=items<?php if(Params::getParam('catId') != ""):?>&catId=<?php echo Params::getParam('catId');?><?php endif; ?>",
                    'iDisplayLength': '10',
                    'iColumns'      : '8',
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
                        {
                            "sTitle": "<div style='width:10px;'><input id='check_all' type='checkbox' /></div>"
                            ,"bSortable": false
                            ,"sClass": "center"
                            ,"sWidth": "10px"
                            ,"bSearchable": false
                        }
                        ,{
                            "sTitle": "<?php _e('Title') ; ?>"
                            ,"sWidth": "25%"
                            ,"bSortable": true
                        }
                        ,{
                            "sTitle": "<?php _e('User') ; ?>"
                            ,"bSortable": true
                            ,"sWidth": "10%"
                        }
                        ,{
                            "sTitle": "<?php _e('Category') ; ?>"
                            ,"sWidth": "15%"
                            ,"bSortable": true
                        }
                        ,{
                            "sTitle": "<?php _e('County') ; ?>"
                            ,"sWidth": "10%"
                            ,"bSortable": true
                        }
                        ,{
                            "sTitle": "<?php _e('Region') ; ?>"
                            ,"sWidth": "10%"
                            ,"bSortable": true
                        }
                        ,{
                            "sTitle": "<?php _e('City') ; ?>"
                            ,"sWidth": "10%"
                            ,"bSortable": true
                        }
                        ,{
                            "sTitle": "<?php _e('Date') ; ?>"
                            ,"sWidth": "100px"
                            ,"bSearchable": false
                            ,"bSortable": true
                            ,"defaultSortable" : true
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

            $('#show_filter').live('mouseover', function(event) {
                $(this).css('color', 'black');

            });

            $('#show_filter').live('mouseleave', function(event) {
                $(this).css('color', '#555555');
            });
            
            function show_filters(){
                div_filter = this;
                $('#TableToolsLinks').toggle(function(){
                    if( $('#show_filter strong').html() == '+ <?php _e('Show filters')?>' ){
                        $('#show_filter strong').html('- <?php _e('Show filters')?>');
                    } else {
                        $('#show_filter strong').html('+ <?php _e('Show filters')?>');
                    }
                });
            }
            
            
        </script>
        
        <div id="content">
            <div id="separator"></div>

            <?php osc_current_admin_theme_path('include/backoffice_menu.php') ; ?>
            
            <div id="right_column">
                <div id="content_header" class="content_header">
                    <div style="float: left;">
                        <img src="<?php echo  osc_current_admin_theme_url('images/new-folder-icon.png') ; ?>" title="" alt=""/>
                    </div>
                    <div id="content_header_arrow">&raquo; <?php _e('Manage items'); ?></div>
                    <div style="clear: both;"></div>
                </div>

                <div id="content_separator"></div>
                <?php osc_show_flash_message('admin') ; ?>
                <div>
                    <form id="datatablesForm" action="<?php echo osc_admin_base_url(true); ?>?page=items" method="post">
                        <input type="hidden" name="action" value="bulk_actions" />
                        <div style="clear:both;"></div>
                        <div id="show_filter" style="color:#555555; cursor: pointer;margin-top:10px;border-bottom:1px #444444 solid;" onclick="show_filters();"> <strong>+ <?php _e('Show filters')?></strong> </div>
                        <div id="TableToolsLinks" style="display:none;">
                            <div style="float:left;">
                                <div class="row">
                                    <label><?php _e('Search') ; ?></label>
                                    <input id="sSearch" type="text" name="sSearch"/><span style="padding-left:1em;">*(<?php _e('Title and Description'); ?>)</span>
                                </div>
                                <div class="row">
                                    <label><?php _e('Item posted by'); ?></label>
                                    <?php
                                        array_unshift($users, array('pk_i_id' => 'NULL', 's_name' => __('Non-registered user')) );
                                        UserForm::user_select($users);
                                    ?>
                                </div>
                                <div class="row">
                                    <label><?php _e('Country'); ?></label>
                                    <?php $item = array(); $item["countryId"] = "";ItemForm::country_select($countries, $item ) ; ?>
                                </div>
                                <div class="row">
                                    <label><?php _e('Region'); ?></label>
                                    <?php ItemForm::region_select($regions, null) ; ?>
                                </div>
                                <div class="row">
                                    <label><?php _e('City'); ?></label>
                                    <?php ItemForm::city_select($cities, null) ; ?>
                                </div>

                                <div class="row">
                                    <label for="catId"><?php _e('Category') ?>:</label>
                                    <?php ItemForm::category_select($categories, null, null, true); ?>
                                </div>
                            </div>
                            <div class="" style="float:left;">
                                <fieldset>
                                    <strong><?php _e('Status') ?></strong>
                                    <br/>
                                    <label for="b_premium"><?php _e('Premium') ?></label>
                                    <select id="b_premium" name="b_premium" style="opacity: 0;">
                                        <option value=""><?php _e('ALL'); ?></option>
                                        <option value="1"><?php _e('ON'); ?></option>
                                        <option value="0"><?php _e('OFF'); ?></option>
                                    </select>
                                    <br/>
                                    <label for="b_active"><?php _e('Active') ?></label>
                                    <select id="b_active" name="b_active" style="opacity: 0;">
                                        <option value=""><?php _e('ALL'); ?></option>
                                        <option value="1"><?php _e('ON'); ?></option>
                                        <option value="0"><?php _e('OFF'); ?></option>
                                    </select>
                                    <br/>
                                    <label for="b_enabled"><?php _e('Enabled') ?></label>
                                    <select id="b_enabled" name="b_enabled" style="opacity: 0;">
                                        <option value=""><?php _e('ALL'); ?></option>
                                        <option value="1"><?php _e('ON'); ?></option>
                                        <option value="0"><?php _e('OFF'); ?></option>
                                    </select>
                                    <br/>
                                    <label for="b_spam"><?php _e('Spam') ?></label>
                                    <select id="b_spam" name="b_spam" style="opacity: 0;">
                                        <option value=""><?php _e('ALL'); ?></option>
                                        <option value="1"><?php _e('ON'); ?></option>
                                        <option value="0"><?php _e('OFF'); ?></option>
                                    </select>
                                </fieldset>
                            </div>
                            <div class="" style="float:left;">
                                <fieldset>
                                    <strong><?php _e('Mark as') ?></strong>
                                    <br/>
                                    <label for="i_num_spam"><?php _e('Spam') ?></label>
                                    <select id="i_num_spam" name="i_num_spam" style="opacity: 0;">
                                        <option value="">-</option>
                                        <option value="1"><?php _e('ON'); ?></option>
                                    </select>
                                    <br/>
                                    <label for="i_num_bad_classified"><?php _e('Misclassified') ?></label>
                                    <select id="i_num_bad_classified" name="i_num_bad_classified" style="opacity: 0;">
                                        <option value="">-</option>
                                        <option value="1"><?php _e('ON'); ?></option>
                                    </select>
                                    <br/>
                                    <label for="i_num_repeated"><?php _e('Duplicated') ?></label>
                                    <select id="i_num_repeated" name="i_num_repeated" style="opacity: 0;">
                                        <option value="">-</option>
                                        <option value="1"><?php _e('ON'); ?></option>
                                    </select>
                                    <br/>
                                    <label for="i_num_offensive"><?php _e('Offensive') ?></label>
                                    <select id="i_num_offensive" name="i_num_offensive" style="opacity: 0;">
                                        <option value="">-</option>
                                        <option value="1"><?php _e('ON'); ?></option>
                                    </select>
                                    <br/>
                                    <label for="i_num_expired"><?php _e('Expired') ?></label>
                                    <select id="i_num_expired" name="i_num_expired" style="opacity: 0;">
                                        <option value="">-</option>
                                        <option value="1"><?php _e('ON'); ?></option>
                                    </select>
                                </fieldset>
                            </div>
                            <div style="clear:both;"></div>
                            <div>
                                <div style="float:left;width:70px;padding-top:10px;"><button type="button" onclick="oTable.applyFilters();"><?php _e('Apply') ; ?></button></div>
                                <div style="float:left;width:140px;padding-top:10px;padding-left:10px;"><button type="button" onclick="window.location.href='<?php echo osc_admin_base_url(true);?>?page=items'"><?php _e('Reset filters') ; ?></button></div>
                            </div>
                            <div style="padding-top:10px;border-bottom:1px gray solid;clear:both;"></div>
                        </div>

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
                                    <option value="activate_all"><?php _e('Activate') ?></option>
                                    <option value="deactivate_all"><?php _e('Deactivate') ?></option>
                                    <option value="enable_all"><?php _e('Enable') ?></option>
                                    <option value="disable_all"><?php _e('Disable') ?></option>
                                    <option value="premium_all"><?php _e('Mark as premium') ?></option>spam_all
                                    <option value="depremium_all"><?php _e('Unmark as premium') ?></option>
                                    <option value="spam_all"><?php _e('Mark as spam') ?></option>
                                    <option value="despam_all"><?php _e('Unmark as spam') ?></option>
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

                $('#sSearch').bind('keypress', function(e) {
                    if(e.keyCode==13){
                        oTable.applyFilters();
                        return false;
                    }
                });
            </script>
            <div style="clear: both;"></div>

        </div> <!-- end of container -->

        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>