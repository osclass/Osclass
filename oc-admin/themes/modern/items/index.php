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
                        if( $('input[name="userName"]').val() ) {
                            aoData.push({
                                "name": "fCol_userIdValue",
                                "value": $('input[name="userName"]').val()
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
                        if( $('input[name="regionId"]').val() ) {
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
                    },
                    "iDisplayLength": "25",
                    "sDom": "<'row'<'span6 length-menu'l><'span6 filter'>fr>t<'row'<'span6 info-results'i><'span6 paginate'p>>",
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
                            "sTitle": "",
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
                            "sTitle": "<?php echo osc_esc_html( __('County') ) ; ?>",
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
                        },
                        {
                            "sTitle": "Spam",
                            "bSortable": false,
                            "bVisible": false
                        },
                        {
                            "sTitle": "Repeated",
                            "bSortable": false,
                            "bVisible": false
                        },
                        {
                            "sTitle": "Bad",
                            "bSortable": false,
                            "bVisible": false
                        },
                        {
                            "sTitle": "Offensive",
                            "bSortable": false,
                            "bVisible": false
                        },
                        {
                            "sTitle": "Expired",
                            "bSortable": false,
                            "bVisible": false
                        }
                    ],
                    "aaSorting": [[7,'desc']]
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
                }) ;

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
        <?php ItemForm::location_javascript('admin') ; ?>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <!-- container -->
        <div id="content">
            <?php osc_current_admin_theme_path( 'include/backoffice_menu.php' ) ; ?>
            <!-- right container -->
            <div class="right">
                <div class="header_title">
                    <h1 class="items"><?php _e('Manage Items') ; ?></h1>
                </div>
                <?php osc_show_admin_flash_messages() ; ?>
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
                        <label><?php _e('Item ID') ; ?></label>
                        <div class="input">
                            <input type="text" class="small" name="pk_i_id" value="" />
                        </div>
                    </div>
                    <div class="input-line">
                        <label><?php _e('Item user') ; ?></label>
                        <div class="input">
                            <input type="text" class="medium" name="userName" value="" />
                        </div>
                    </div>
                    <div class="input-line">
                        <label><?php _e('Country') ; ?></label>
                        <div class="input">
                            <?php ItemForm::country_select($countries, array('countryId' => '') ) ; ?>
                        </div>
                    </div>
                    <div class="input-line">
                        <label><?php _e('Region') ; ?></label>
                        <div class="input">
                            <?php ItemForm::region_select($regions, null) ; ?>
                        </div>
                    </div>
                    <div class="input-line">
                        <label><?php _e('City') ; ?></label>
                        <div class="input">
                            <?php ItemForm::city_select($cities, null) ; ?>
                        </div>
                    </div>
                    <div class="input-line">
                        <label><?php _e('Category') ; ?></label>
                        <div class="input">
                            <?php ItemForm::category_select($categories, null, null, true) ; ?>
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
                                <option value="enable_all"><?php _e('Block') ; ?></option>
                                <option value="disable_all"><?php _e('Unblock') ; ?></option>
                                <option value="premium_all"><?php _e('Mark as premium') ; ?></option>
                                <option value="depremium_all"><?php _e('Unmark as premium') ; ?></option>
                                <option value="spam_all"><?php _e('Mark as spam') ; ?></option>
                                <option value="despam_all"><?php _e('Unmark as spam') ; ?></option>
                            </select> <input type="submit" id="bulk_apply" class="btn" value="<?php echo osc_esc_html( __('Apply') ) ; ?>">
                        </label>
                    </div>
                    <div id="add_item_button">
                        <a href="<?php echo osc_admin_base_url(true) ; ?>?page=items&amp;action=post" class="btn" id="button_open"><?php _e('Add item') ; ?></a>
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