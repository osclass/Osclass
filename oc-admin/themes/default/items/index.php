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
            <a hreg="#" class="btn ico ico-32 ico-engine float-right"></a>
            <a hreg="#" class="btn ico ico-32 ico-help float-right"></a>
            <a hreg="<?php echo osc_admin_base_url(true) . '?page=items&action=post' ; ?>" class="btn btn-green ico ico-32 ico-add-white float-right"><?php _e('Add'); ?></a>
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
                $('#user').attr( "autocomplete", "off" );
                
                $('#user').autocomplete({
                    source: "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=userajax&term="+$('#user').val(),
                    minLength: 0,
                    select: function( event, ui ) {
                        if(ui.item.id=='') 
                            return false;
                        $('#userId').val(ui.item.id);
                    },
                    search: function() {
                        $('#userId').val('');
                        return false;
                    },
                    focus: function (event, ui) {
                        return false;
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
            });
           
            
        </script>
        <style>
            .ui-autocomplete-loading {
                display: block;
                background: white url("<?php echo osc_current_admin_theme_url('images/loading.gif'); ?>") right center no-repeat;
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

    $iDisplayLength = __get('iDisplayLength');
    
    $aData      = __get('aItems') ;

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
    <input type="hidden" name="iSortCol_0" value="7" />
    <input type="hidden" name="sSortDir_0" value="0" />
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
                            <option value="1" <?php echo ( (Params::getParam('b_enabled') == '1') ? 'selected="selected"' : '' )?>><?php _e('ON'); ?></option>
                            <option value="0" <?php echo ( (Params::getParam('b_enabled') == '0') ? 'selected="selected"' : '' )?>><?php _e('OFF'); ?></option>
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
            <a href="#" class="btn" id="btn-display-filters"><?php _e('Show filters') ; ?></a>
            <select class="select-box-extra select-box-input">
                <option value=""><?php _e('User') ; ?></option>
                <option value=""><?php _e('Pattern') ; ?></option>
            </select>
            <input type="text" name="sPattern" class="input-text input-actions input-has-select"/>
            <input type="submit" class="btn submit-right" value="<?php echo osc_esc_html( __('Find') ) ; ?>">
        </div>
    </div>
    
    <form class="items datatables" id="datatablesForm" action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
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
                </select> <input type="submit" id="bulk_apply" class="btn" value="<?php echo osc_esc_html( __('Apply') ) ; ?>" />
            </label>
        </div>
        <div class="table-hast-actions">
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
                        <th><?php _e('Date') ; ?></th>
                    </tr>
                </thead>
                <tbody>
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
                </tbody>
            </table>
            <div id="table-row-actions"></div> <!-- used for table actions -->
        </div>
    </form>
</div>
<?php 
    $pageActual = 0 ;
    if( Params::getParam('iPage') != '' ) {
        $pageActual = Params::getParam('iPage') ;
    }
    
    $urlActual = osc_admin_base_url(true).'?'.$_SERVER['QUERY_STRING'];
    $urlActual = preg_replace('/&iPage=(\d)+/', '', $urlActual) ;
    
    $params = array('total'    => ceil($aData['iTotalDisplayRecords']/$aData['iDisplayLength'])
                   ,'selected' => $pageActual
                   ,'url'      => $urlActual.'&iPage={PAGE}'
                   ,'sides'    => 9
        );
    $pagination = new Pagination($params);
    $aux = $pagination->doPagination();
    
    echo $aux;
?>
    
<?php osc_current_admin_theme_path( 'parts/footer.php' ) ; ?>