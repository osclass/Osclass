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

    //customize Head
    function customHead() { ?>
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery-ui.js') ; ?>"></script>
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

    $aData = __get('aItems') ;

?>
<?php osc_current_admin_theme_path( 'parts/header.php' ) ; ?>
<div id="content-head">
	<h1>Listing
		<a hreg="#" class="btn ico ico-engine float-right"></a>
		<a hreg="#" class="btn ico ico-help float-right"></a>
		<a hreg="#" class="btn btn-green ico ico-add-white float-right">Add</a>
	</h1>
</div>
<div id="help-box">
	<a href="#" class="btn ico btn-mini ico-close">x</a>
	<h3>What does a red highlight mean?</h3>
<p>This is where I would provide help to the user on how everything in my admin panel works. Formatted HTML works fine in here too.
Red highlight means that the listing has been marked as spam.</p>
</div>
<div id="content-page">
<!-- -->
<h2 class="reder-title">Manage listings</h2>
<form method="get" action="<?php echo osc_admin_base_url(true); ?>">
    <input type="hidden" name="page" value="items" />
    <input type="hidden" name="iSortCol_0" value="7" />
    <input type="hidden" name="sSortDir_0" value="0" />

    
<!--    <input type="hidden" name="" value="" />
    <input type="hidden" name="" value="" />-->
    <div class="table-hast-actions_">
        <div class="form-row">
            <div class="form-label">
                <?php _e('Listing user name') ; ?>
            </div>
            <div class="form-controls">
                <input class="input-small" type="text" name="sSearch" id="sSearch" value="<?php echo osc_esc_html(Params::getParam('sSearch')); ?>" />
            </div>
        </div>
        <div class="form-row">
            <div class="form-label"></div>
            <div class="form-controls">
            </div>
        </div>
        <div class="form-row">
            <div class="form-label"></div>
            <div class="form-controls">
            </div>
        </div>
        <div class="form-row">
            <div class="form-label"></div>
            <div class="form-controls">
            </div>
        </div>
        <div class="form-row">
            <div class="form-label"></div>
            <div class="form-controls">
            </div>
        </div>
                <input type="text" class="input-small" name="default_results_per_page" value="<?php echo osc_esc_html(osc_default_results_per_page_at_search()); ?>" />
        <p>
            pattern 
        </p>
        <p>
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
        </p>
        <input type="submit" value="<?php echo osc_esc_html( __('Apply filters') ) ; ?>" class="btn btn-submit" />
    </div>
</form>
<div class="table-hast-actions">
<table class="table" cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<th class="col-bulkactions"><input type="checkbox"/></th>
			<th>Title</th>
			<th>User</th>
			<th>Category</th>
			<th>Country</th>
			<th>Region</th>
			<th>City</th>
			<th>Date</th>
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
<div id="table-row-actions">    

</div> <!-- used for table actions -->
<?php 
    $pageActual = 0 ;
    if( Params::getParam('iPage') != '' ) {
        $pageActual = Params::getParam('iPage') ;
    }
    
    $urlActual = osc_admin_base_url(true).'?'.$_SERVER['QUERY_STRING'];
    $urlActual = preg_replace('/&iPage=(\d)+/', '', $urlActual) ;
    
    $params = array('total'    => ceil($aData['iTotalDisplayRecords']/25)
                   ,'selected' => $pageActual
                   ,'url'      => $urlActual.'&iPage={PAGE}'
                   ,'sides'    => 9
        );
    $pagination = new Pagination($params);
    $aux = $pagination->doPagination();
    
    echo $aux;
?>
<!-- -->
</div>
</div>
<?php osc_current_admin_theme_path( 'parts/footer.php' ) ; ?>