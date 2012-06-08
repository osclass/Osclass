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

    function customPageHeader(){ ?>
        <h1><?php _e('Manage listing') ; ?>
            <a href="#" class="btn ico ico-32 ico-engine float-right"></a>
            <a href="#" class="btn ico ico-32 ico-help float-right"></a>
            <a href="<?php echo osc_admin_base_url(true) . '?page=items&action=post' ; ?>" class="btn btn-green ico ico-32 ico-add-white float-right"><?php _e('Add'); ?></a>
	</h1>
<?php
    }
    osc_add_hook('admin_page_header','customPageHeader');
    //customize Head
    function customHead() { ?>
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.validate.min.js') ; ?>"></script>
        <?php ItemForm::location_javascript_new('admin') ; ?>
        <script type="text/javascript">
            // autocomplete users
            $(document).ready(function(){

                $('#filter-select').change( function () {
                    var option = $(this).find('option:selected').attr('value') ;
                    // clean values
                    $('#fPattern,#fUser,#fItemId').attr('value', '');
                    if(option == 'oPattern') {
                        $('#fPattern').removeClass('hide');
                        $('#fUser, #fItemId').addClass('hide');
                    } else if(option == 'oUser'){
                        $('#fUser').removeClass('hide');
                        $('#fPattern, #fItemId').addClass('hide');
                    } else {   
                        $('#fItemId').removeClass('hide');
                        $('#fPattern, #fUser').addClass('hide');
                    }
                });
                
                $('input[name="user"]').attr( "autocomplete", "off" );
                $('#user,#fUser').autocomplete({
                    source: "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=userajax"+$('input[name="user"]').val(), // &term=
                    minLength: 0,
                    select: function( event, ui ) {
                        if(ui.item.id=='') 
                            return false;
                        $('#userId').val(ui.item.id);
                        $('#fUserId').val(ui.item.id);
                    },
                    search: function() {
                        $('#userId').val('');
                        $('#fUserId').val('');
                    }
                });

                // show filters
                /*var filters = $('#show-filters-div').hide();
                $('#show-filters-button').click(function(){
                    filters.toggle();
                });*/

                $('#btn-display-filters').click(function(){
                    $('#display-filters').dialog({modal:true,width:'700px',title:'<?php echo osc_esc_js( __('Filters') ) ; ?>'});
                    return false;
                });
                
                // check_all bulkactions
                $("#check_all").change(function(){
                    var isChecked = $(this+':checked').length;
                    $('.col-bulkactions input').each( function() {
                        if( isChecked == 1 ) {
                            this.checked = true;
                        } else {
                            this.checked = false;
                        }
                    });
                });
            });
           
            
        </script>
        <style>
            .ui-autocomplete-loading {
                display: block;
                background: white url("<?php echo osc_current_admin_theme_url('images/loading.gif'); ?>") right center no-repeat;
            }
            .hide {
                display: none !important;
            }
            table.table thead .sorting_desc {
                background: url("<?php echo osc_current_admin_theme_url('images/sort_desc.png'); ?>") no-repeat scroll right center transparent;
            }
            table.table thead .sorting_asc {
                background: url("<?php echo osc_current_admin_theme_url('images/sort_asc.png'); ?>") no-repeat scroll right center transparent;
            }
        </style>
        <?php
    }
    osc_add_hook('admin_header','customHead');
    
    $users      = __get('users') ;
    $stat       = __get('stat') ;
    $categories = __get('categories') ;
    $countries  = __get('countries') ;
    $regions    = __get('regions') ;
    $cities     = __get('cities') ;
    $withFilters= __get('withFilters') ;

    $iDisplayLength = __get('iDisplayLength');
    
    $aData      = __get('aItems') ;
    
    $url_date   = __get('url_date') ;
    
    $sort       = Params::getParam('sort');
    $direction  = Params::getParam('direction');
?>
<?php osc_current_admin_theme_path( 'parts/header.php' ) ; ?>

<div id="help-box">
    <a href="#" class="btn ico ico-20 ico-close">x</a>
    <h3>What does a red highlight mean?</h3>
    <p>This is where I would provide help to the user on how everything in my admin panel works. Formatted HTML works fine in here too.
    Red highlight means that the listing has been marked as spam.</p>
</div>
<form method="get" action="<?php echo osc_admin_base_url(true); ?>" id="display-filters" style="display:none">
    <input type="hidden" name="page" value="items" />
    <input type="hidden" name="iDisplayLength" value="<?php echo $iDisplayLength;?>" />
    <div class="form-horizontal">
    <div class="grid-system">
        <div class="grid-row grid-50">
            <div class="row-wrapper">
                <div class="form-row">
                    <div class="form-label">
                        <?php _e('Pattern') ; ?>
                    </div>
                    <div class="form-controls">
                        <input type="text" name="sSearch" id="sSearch" value="<?php echo osc_esc_html(Params::getParam('sSearch')); ?>" />
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label">
                        <?php _e('Category') ; ?>
                    </div>
                    <div class="form-controls">
                        <?php ManageItemsForm::category_select($categories, null, null, true) ; ?>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-label">
                        <?php _e('Country') ; ?>
                    </div>
                    <div class="form-controls">
                        <?php ManageItemsForm::country_text(); ?>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label">
                        <?php _e('Region') ; ?>
                    </div>
                    <div class="form-controls">
                        <?php ManageItemsForm::region_text(); ?>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label">
                        <?php _e('City') ; ?>
                    </div>
                    <div class="form-controls">
                        <?php ManageItemsForm::city_text(); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid-row grid-50">
            <div class="row-wrapper">
                <div class="form-row">
                    <div class="form-label">
                        <?php _e('Listing user name') ; ?>
                    </div>
                    <div class="form-controls">
                        <input id="user" name="user" type="text" value="<?php echo osc_esc_html(Params::getParam('user')); ?>" />
                        <input id="userId" name="userId" type="hidden" value="<?php echo osc_esc_html(Params::getParam('userId')); ?>" />
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label">
                        <?php _e('Premium') ; ?>
                    </div>
                    <div class="form-controls">
                        <select id="b_premium" name="b_premium">
                            <option value="" <?php echo ( (Params::getParam('b_premium') == '') ? 'selected="selected"' : '' )?>><?php _e('ALL'); ?></option>
                            <option value="1" <?php echo ( (Params::getParam('b_premium') == '1') ? 'selected="selected"' : '' )?>><?php _e('ON'); ?></option>
                            <option value="0" <?php echo ( (Params::getParam('b_premium') == '0') ? 'selected="selected"' : '' )?>><?php _e('OFF'); ?></option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label">
                        <?php _e('Active') ; ?>
                    </div>
                    <div class="form-controls">
                        <select id="b_active" name="b_active">
                            <option value="" <?php echo ( (Params::getParam('b_active') == '') ? 'selected="selected"' : '' )?>><?php _e('ALL'); ?></option>
                            <option value="1" <?php echo ( (Params::getParam('b_active') == '1') ? 'selected="selected"' : '' )?>><?php _e('ON'); ?></option>
                            <option value="0" <?php echo ( (Params::getParam('b_active') == '0') ? 'selected="selected"' : '' )?>><?php _e('OFF'); ?></option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label">
                        <?php _e('Block') ; ?>
                    </div>
                    <div class="form-controls">
                        <select id="b_enabled" name="b_enabled">
                            <option value="" <?php echo ( (Params::getParam('b_enabled') == '') ? 'selected="selected"' : '' )?>><?php _e('ALL'); ?></option>
                            <option value="0" <?php echo ( (Params::getParam('b_enabled') == '0') ? 'selected="selected"' : '' )?>><?php _e('ON'); ?></option>
                            <option value="1" <?php echo ( (Params::getParam('b_enabled') == '1') ? 'selected="selected"' : '' )?>><?php _e('OFF'); ?></option>
                        </select>   
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label">
                        <?php _e('Spam') ; ?>
                    </div>
                    <div class="form-controls">
                        <select id="b_spam" name="b_spam">
                            <option value="" <?php echo ( (Params::getParam('b_spam') == '') ? 'selected="selected"' : '' )?>><?php _e('ALL'); ?></option>
                            <option value="1" <?php echo ( (Params::getParam('b_spam') == '1') ? 'selected="selected"' : '' )?>><?php _e('ON'); ?></option>
                            <option value="0" <?php echo ( (Params::getParam('b_spam') == '0') ? 'selected="selected"' : '' )?>><?php _e('OFF'); ?></option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid-row grid-100">
            <div class="row-wrapper">
                <div class="form-horizontal">
                    <div class="form-row">
                        <div class="form-actions">
                            <input id="show-filters" type="submit" value="<?php echo osc_esc_html( __('Apply filters') ) ; ?>" class="btn btn-submit float-right" />
                            <a class="btn float-right" href="<?php echo osc_admin_base_url(true).'?page=items'; ?>"><?php _e('Reset filters') ; ?></a>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    </div>
</form>
<h2 class="render-title"><?php _e('Manage listing') ; ?></h2>
<div style="position:relative;">
    <div id="listing-toolbar">
        <div class="float-right">
            <form method="get" action="<?php echo osc_admin_base_url(true); ?>" id="shortcut-filters">
                <input type="hidden" name="page" value="items" />
                <input type="hidden" name="iDisplayLength" value="<?php echo $iDisplayLength;?>" />
                <?php if($withFilters) { ?>
                <a class="btn" href="<?php echo osc_admin_base_url(true).'?page=items'; ?>"><?php _e('Reset filters') ; ?></a>
                <?php } ?>
                <a href="#" class="btn <?php if($withFilters) { echo 'btn-red'; } ?>" id="btn-display-filters"><?php _e('Show filters') ; ?></a>

                <?php $opt = "oPattern"; if(Params::getParam('shortcut-filter') != '') { $opt = Params::getParam('shortcut-filter'); } ?>
                <?php $classPattern = 'hide'; $classUser = 'hide'; $classItemId = 'hide'; ?>
                <?php if($opt == 'oUser') { $classUser = ''; } ?>
                <?php if($opt == 'oPattern') { $classPattern = ''; } ?>
                <?php if($opt == 'oItemId') { $classItemId = ''; } ?>
                <select id="filter-select" name="shortcut-filter" class="select-box-extra select-box-input">
                    <option value="oPattern" <?php if($opt == 'oPattern'){ echo 'selected="selected"'; } ?>><?php _e('Pattern') ; ?></option>
                    <option value="oUser" <?php if($opt == 'oUser'){ echo 'selected="selected"'; } ?>><?php _e('User') ; ?></option>
                    <option value="oItemId" <?php if($opt == 'oItemId'){ echo 'selected="selected"'; } ?>><?php _e('Item id') ; ?></option>
                </select><input 
                    id="fPattern" type="text" name="sSearch"
                    value="<?php echo osc_esc_html(Params::getParam('sSearch')); ?>" 
                    class="input-text input-actions input-has-select <?php echo $classPattern; ?>"/><input 
                    id="fUser" name="user" type="text" 
                    class="fUser input-text input-actions input-has-select <?php echo $classUser; ?>" 
                    value="<?php echo osc_esc_html(Params::getParam('user')); ?>" /><input 
                    id="fUserId" name="userId" type="hidden" 
                    value="<?php echo osc_esc_html(Params::getParam('userId')); ?>" /><input 
                    id="fItemId" type="text" name="itemId" 
                    value="<?php echo osc_esc_html(Params::getParam('itemId')); ?>" 
                    class="input-text input-actions input-has-select <?php echo $classItemId; ?>"/>

                <input type="submit" class="btn submit-right" value="<?php echo osc_esc_html( __('Find') ) ; ?>">
            </form>
        </div>
    </div>
    
    <form class="" id="datatablesForm" action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
        <input type="hidden" name="page" value="items" />
        <input type="hidden" name="action" value="bulk_actions" />
        
        <div id="bulk-actions">
            <label>
                <select id="bulk_actions" name="bulk_actions" class="select-box-extra">
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
                    <?php $onclick_bulkactions= 'onclick="javascript:return confirm(\'' . osc_esc_js( __('You are doing bulk actions. Are you sure you want to continue?') ) . '\')"' ; ?>
                </select> <input type="submit" <?php echo $onclick_bulkactions; ?> id="bulk_apply" class="btn" value="<?php echo osc_esc_html( __('Apply') ) ; ?>" />
            </label>
        </div>
        <div class="table-contains-actions">
            <table class="table" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th class="col-bulkactions"><input id="check_all" type="checkbox" /></th>
                        <th><?php _e('Title') ; ?></th>
                        <th><?php _e('User') ; ?></th>
                        <th><?php _e('Category') ; ?></th>
                        <th><?php _e('Country') ; ?></th>
                        <th><?php _e('Region') ; ?></th>
                        <th><?php _e('City') ; ?></th>
                        <th class="<?php if($sort=='date'){ if($direction=='desc'){ echo 'sorting_desc'; } else { echo 'sorting_asc'; } } ?>">
                            <a href="<?php echo $url_date; ?>"><?php _e('Date') ; ?></a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                <?php if(count($aData['aaData'])>0) : ?>
                <?php foreach( $aData['aaData'] as $array) : ?>
                    <tr>
                    <?php foreach($array as $key => $value) : ?>
                        <?php if( $key==0 ): ?>
                        <td class="col-bulkactions">
                        <?php else : ?>
                        <td>
                        <?php endif ; ?>
                        <?php echo $value; ?>
                        </td>
                    <?php endforeach; ?>
                    </tr>
                <?php endforeach;?>
                <?php else : ?>
                    <tr>
                        <td colspan="8" style="text-align: center;">
                        <p><?php _e('No data available in table') ; ?></p>
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
            <div id="table-row-actions"></div> <!-- used for table actions -->
        </div>
    </form>
</div>
<div class="has-pagination">
<?php 
    
    $pageActual = Params::getParam('iPage') ;
    $urlActual = osc_admin_base_url(true).'?'.$_SERVER['QUERY_STRING'];
    $urlActual = preg_replace('/&iPage=(\d+)?/', '', $urlActual) ;
    $pageTotal = ceil($aData['iTotalDisplayRecords']/$aData['iDisplayLength']);
    $params = array('total'    => $pageTotal
                   ,'selected' => $pageActual-1
                   ,'url'      => $urlActual.'&iPage={PAGE}'
                   ,'sides'    => 5
        );
    $pagination = new Pagination($params);
    $aux = $pagination->doPagination();
    
    echo $aux;
?>
</div>
    
<?php osc_current_admin_theme_path( 'parts/footer.php' ) ; ?>