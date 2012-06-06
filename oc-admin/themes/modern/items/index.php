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

    $users      = __get('users') ;
    $stat       = __get('stat') ;
    $categories = __get('categories') ;
    $countries  = __get('countries') ;
    $regions    = __get('regions') ;
    $cities     = __get('cities') ;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()) ; ?>">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
        <link href="<?php echo osc_current_admin_theme_styles_url('datatables.css') ; ?>" rel="stylesheet" type="text/css" />
        <!-- datatables js -->
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.dataTables.js') ; ?>"></script>
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('datatables.pagination.js') ; ?>"></script>
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('datatables.extend.js') ; ?>"></script>
        <script type="text/javascript">
            $(function() {
                oTable = $('#datatables_list').dataTable({
                    "sAjaxSource": "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=items<?php if( Params::getParam('catId') != '' ) { ?>&catId=<?php echo Params::getParam('catId') ; } ?>",
                    "fnServerParams": function ( aoData ) {
                        if( $('input[name="sSearch"]').val() ) {
                            aoData.push({
                                "name": "sSearch",
                                "value": $('input[name="sSearch"]').val()
                            }) ;
                        }
                        if( $('input[name="pk_i_id"]').val() ) {
                            aoData.push({
                                "name": "fCol_itemIdValue",
                                "value": $('input[name="pk_i_id"]').val()
                            }) ;
                        }
                        if( $('select[name="userId"]').val() ) {
                            aoData.push({
                                "name": "fCol_userIdValue",
                                "value": $('select[name="userId"]').val()
                            }) ;
                        }
                        if( $('select[name="countryId"]').val() ) {

                            aoData.push({
                                "name": "fCol_countryId",
                                "value": $('select[name="countryId"]').val()
                            }) ;
                        }
                        if( $('input[name="country"]').val() ) {
                            aoData.push({
                                "name": "fCol_country",
                                "value": $('input[name="country"]').val()
                            }) ;
                        }
                        if( $('select[name="regionId"]').val() ) {
                            aoData.push({
                                "name": "fCol_regionId",
                                "value": $('select[name="regionId"]').val()
                            }) ;
                        }
                        if( $('input[name="region"]').val() ) {
                            aoData.push({
                                "name": "fCol_region",
                                "value": $('input[name="region"]').val()
                            }) ;
                        }
                        if( $('select[name="cityId"]').val() ) {
                            aoData.push({
                                "name": "fCol_cityId",
                                "value": $('select[name="cityId"]').val()
                            }) ;
                        }
                        if( $('input[name="city"]').val() ) {
                            aoData.push({
                                "name": "fCol_city",
                                "value": $('input[name="city"]').val()
                            }) ;
                        }
                        if( $('select[name="catId"]').val() ) {
                            aoData.push({
                                "name": "fCol_catId",
                                "value": $('select[name="catId"]').val()
                            }) ;
                        }
                        // status filters
                        if( $('select[name="b_premium"]').val() ) {
                            aoData.push({
                                "name": "fCol_bPremium",
                                "value": $('select[name="b_premium"]').val()
                            }) ;
                        }
                        if( $('select[name="b_active"]').val() ) {
                            aoData.push({
                                "name": "fCol_bActive",
                                "value": $('select[name="b_active"]').val()
                            }) ;
                        }
                        if( $('select[name="b_enabled"]').val() ) {
                            aoData.push({
                                "name": "fCol_bEnabled",
                                "value": $('select[name="b_enabled"]').val()
                            }) ;
                        }
                        if( $('select[name="b_spam"]').val() ) {
                            aoData.push({
                                "name": "fCol_bSpam",
                                "value": $('select[name="b_spam"]').val()
                            }) ;
                        }
                    },
                    "iDisplayLength": 25,
                    "sDom": "<'row-action'<'row'<'span6 length-menu'l><'span6 filter'>fr>>t<'row'<'span6 info-results'i><'span6 paginate'p>>",
                    "sPaginationType": "bootstrap",
                    "bLengthChange": false,
                    "bProcessing": true,
                    "bServerSide":true,
                    "bPaginate": true,
                    "bFilter": false,
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
                        "sSearch": "<?php echo osc_esc_html( __('Search by name') ) ; ?>",
                        "sZeroRecords": "<?php echo osc_esc_html( __('No matching records found') ) ; ?>"
                    },
                    "aoColumns": [
                        {
                            "sTitle": '<input id="check_all" type="checkbox" />',
                            "sWidth": "10px",
                            "bSearchable": false,
                            "bSortable": false
                        },
                        {
                            "sTitle": "<?php echo osc_esc_html( __('Title') ) ; ?>",
                            "sWidth": "25%",
                            "bSortable": false
                        },
                        {
                            "sTitle": "<?php echo osc_esc_html( __('User') ) ; ?>",
                            "sWidth": "80px",
                            "bSortable": false
                        },
                        {
                            "sTitle": "<?php echo osc_esc_html( __('Category') ) ; ?>",
                            "bSortable": false
                        },
                        {
                            "sTitle": "<?php echo osc_esc_html( __('Country') ) ; ?>",
                            "bSortable": false
                        },
                        {
                            "sTitle": "<?php echo osc_esc_html( __('Region') ) ; ?>",
                            "bSortable": false
                        },
                        {
                            "sTitle": "<?php echo osc_esc_html( __('City') ) ; ?>",
                            "bSortable": false
                        },
                        {
                            "sTitle": "<?php echo osc_esc_html( __('Date') ) ; ?>",
                            "sWidth": "125px",
                            "bSearchable": false,
                            "bSortable": true,
                            "defaultSortable" : true
                        }
                    ],
                    "aaSorting": [[7,'desc']],
                    "fnDrawCallback": function() {
                        $('input[name="id[]"]').each(function() {
                            if( $(this).attr("blocked") == '1' ) {
                                if( $(this).attr("active") == '1' ) {
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

                $('#datatables_list tr').live('mouseover', function(event) {
                    $('.datatable_wrapper', this).show();
                });

                $('#datatables_list tr').live('mouseleave', function(event) {
                    $('.datatable_wrapper', this).hide();
                });

                $('.length-menu').append( $("#bulk_actions") ) ;
                $('.filter').append( $("#add_item_button") ) ;

                $('input[name="apply-filters"]').bind('click', function() {
                    oTable.fnDraw() ;
                });
                $('input[name="findById"]').bind('click', function() {
                    oTable.fnDraw() ;
                });

                $('.show-filters').bind('click', function() {
                    if( $(this).attr('data-showed') == 'true' ) {
                        $(this).html('+ <?php _e('Show filters') ; ?>') ;
                        $(this).attr('data-showed', 'false') ;
                    } else {
                        $(this).html('- <?php _e('Hide filters') ; ?>') ;
                        $(this).attr('data-showed', 'true') ;
                    }
                    $('.items-filters').toggle() ;
                }) ;
            }) ;
        </script>
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('datatables.post_init.js') ; ?>"></script>
        <!-- /datatables js -->
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.validate.min.js') ; ?>"></script>
        <?php ItemForm::location_javascript_new('admin') ; ?>
        <script type="text/javascript">
            // autocomplete users
            $(document).ready(function(){
                $('#user').attr( "autocomplete", "off" );
                $('#user').live('keyup.autocomplete', function(){
                    $('#userId').val('');
                    $( this ).autocomplete({
                        source: "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=userajax&term="+$('#user').val(),
                        minLength: 0,
                        select: function( event, ui ) {
                            if(ui.item.id=='') 
                                return false;
                            $('#userId').val(ui.item.id);
                        }
                    });
                });
            });
            
            function delete_alert() {
                if($("#bulk_actions option:selected").attr("value")=='delete_all') {
                    return confirm('<?php echo osc_esc_js(__("This action can not be undone, are you sure you want to continue?")); ?>')
                }
                return true;
            }
            
        </script>
        <style>
            .ui-autocomplete-loading {
                background: white url("<?php echo osc_current_admin_theme_url('images/loading.gif'); ?>") right center no-repeat;
            }
        </style>

    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <!-- container -->
        <div id="content">
            <?php osc_current_admin_theme_path( 'include/backoffice_menu.php' ) ; ?>
            <!-- right container -->
            <div class="right">
                <div class="header_title">
                    <h1 class="items"><?php _e('Manage listings') ; ?></h1>
                </div>
                <?php osc_show_flash_message('admin') ; ?>
                <!-- items filters -->
                <h3 class="show-filters" data-showed="false">+ <?php _e('Show filters') ; ?></h3>
                <div class="items-filters" style="display: none;">
                    <div class="input-line">
                        <label><?php _e('Search') ; ?></label>
                        <div class="input">
                            <input type="text" class="xlarge" name="sSearch" value="" />
                        </div>
                    </div>
                    <div class="input-line">
                        <label><?php _e('Listing user name') ; ?></label>
                        <div class="input">
                            <input id="user" name="user" type="text" value="" />
                            <input id="userId" name="userId" type="hidden" value="" />
                        </div>
                    </div>
                    <div class="input-line">
                        <label><?php _e('Country') ; ?></label>
                        <div class="input">
                            <?php ItemForm::country_text(); ?>
                        </div>
                    </div>
                    <div class="input-line">
                        <label><?php _e('Region') ; ?></label>
                        <div class="input">
                            <?php ItemForm::region_text(); ?>
                        </div>
                    </div>
                    <div class="input-line">
                        <label><?php _e('City') ; ?></label>
                        <div class="input">
                            <?php ItemForm::city_text(); ?>
                        </div>
                    </div>
                    <div class="input-line">
                        <label><?php _e('Category') ; ?></label>
                        <div class="input">
                            <?php ItemForm::category_select($categories, null, null, true) ; ?>
                        </div>
                    </div>
                    
                    <strong><?php _e('Status') ?></strong>
                    
                    <div class="input-line">
                        <label><?php _e('Premium') ; ?></label>
                        <div class="input">
                            <select id="b_premium" name="b_premium">
                                <option value=""><?php _e('ALL'); ?></option>
                                <option value="1"><?php _e('ON'); ?></option>
                                <option value="0"><?php _e('OFF'); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="input-line">
                        <label><?php _e('Active') ; ?></label>
                        <div class="input">
                            <select id="b_active" name="b_active">
                                <option value=""><?php _e('ALL'); ?></option>
                                <option value="1"><?php _e('ON'); ?></option>
                                <option value="0"><?php _e('OFF'); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="input-line">
                        <label><?php _e('Enabled') ; ?></label>
                        <div class="input">
                            <select id="b_enabled" name="b_enabled">
                                <option value=""><?php _e('ALL'); ?></option>
                                <option value="1"><?php _e('ON'); ?></option>
                                <option value="0"><?php _e('OFF'); ?></option>
                            </select>    
                        </div>
                    </div>
                    <div class="input-line">
                        <label><?php _e('Spam') ; ?></label>
                        <div class="input">
                            <select id="b_spam" name="b_spam">
                                <option value=""><?php _e('ALL'); ?></option>
                                <option value="1"><?php _e('ON'); ?></option>
                                <option value="0"><?php _e('OFF'); ?></option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="actions">
                        <input type="button" name="apply-filters" value="<?php echo osc_esc_html( __('Apply filters') ) ; ?>" />
                    </div>
                </div>
                <!-- /items filters -->
                <!-- datatables items -->
                <form class="items datatables" id="datatablesForm" action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
                    <input type="hidden" name="page" value="items" />
                    <input type="hidden" name="action" value="bulk_actions" />
                    <div id="bulk_actions">
                        <label>
                            <select id="bulk_actions" name="bulk_actions" class="display">
                                <option value=""><?php _e('Bulk actions') ; ?></option>
                                <option value="delete_all"><?php _e('Delete') ; ?></option>
                                <option value="activate_all"><?php _e('Activate') ; ?></option>
                                <option value="deactivate_all"><?php _e('Deactivate') ; ?></option>
                                <option value="disable_all"><?php _e('Block') ; ?></option>
                                <option value="enable_all"><?php _e('Unblock') ; ?></option>
                                <option value="premium_all"><?php _e('Mark as premium') ; ?></option>
                                <option value="depremium_all"><?php _e('Unmark as premium') ; ?></option>
                                <option value="spam_all"><?php _e('Mark as spam') ; ?></option>
                                <option value="despam_all"><?php _e('Unmark as spam') ; ?></option>
                            </select> <input type="submit" id="bulk_apply" onclick="javascript:return delete_alert();" class="btn" value="<?php echo osc_esc_html( __('Apply') ) ; ?>" />
                        </label>
                    </div>
                    <div id="add_item_button">
                        <input type="text" class="medium" name="pk_i_id" value="" />
                        <input type="button" name="findById" value="<?php echo osc_esc_html( __('Find by listing id') ) ; ?>" />
                        <a href="<?php echo osc_admin_base_url(true) ; ?>?page=items&amp;action=post" class="btn" id="button_open"><?php _e('Add listing') ; ?></a>
                    </div>
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="datatables_list"></table>
                </form>
                <!-- /datatables items -->
            </div>
            <!-- /right container -->
        </div>
        <!-- /container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>