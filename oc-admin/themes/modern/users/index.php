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
        <h1><?php _e('Manage users') ; ?>
		<a href="#" class="btn ico ico-32 ico-engine float-right"></a>
		<a href="#" class="btn ico ico-32 ico-help float-right"></a>
                <a href="<?php echo osc_admin_base_url(true) . '?page=users&action=create' ; ?>" class="btn btn-green ico ico-32 ico-add-white float-right"><?php _e('Add'); ?></a>
	</h1>
<?php
    }
    osc_add_hook('admin_page_header','customPageHeader');

    function customPageTitle($string) {
        return sprintf(__('Manage users &raquo; %s'), $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');

    //customize Head
    function customHead() { ?>
        <script type="text/javascript">
            $(document).ready(function(){
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
        <?php
    }
    osc_add_hook('admin_header','customHead');
   
    $iDisplayLength = __get('iDisplayLength');
    $aData          = __get('aUsers'); 
?>
<?php osc_current_admin_theme_path( 'parts/header.php' ) ; ?> 
<div class="relative">
    <div id="users-toolbar" class="table-toolbar">
    </div>
    <form class="" id="datatablesForm" action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
        <input type="hidden" name="page" value="users" />
        
        <div id="bulk-actions">
            <label>
                <select name="action" id="action" class="select-box-extra">
                    <option value=""><?php _e('Bulk Actions') ; ?></option>
                    <option value="activate"><?php _e('Activate') ; ?></option>
                    <option value="deactivate"><?php _e('Deactivate') ; ?></option>
                    <option value="enable"><?php _e('Unblock') ; ?></option>
                    <option value="disable"><?php _e('Block') ; ?></option>
                    <option value="delete"><?php _e('Delete') ; ?></option>
                    <?php if( osc_user_validation_enabled() ) { ?>
                        <option value="resend_activation"><?php _e('Resend activation') ; ?></option>
                    <?php }; ?>
                    <?php $onclick_bulkactions= 'onclick="javascript:return confirm(\'' . osc_esc_js( __('You are doing bulk actions. Are you sure you want to continue?') ) . '\')"' ; ?>
                </select> <input type="submit" <?php echo $onclick_bulkactions; ?> id="bulk_apply" class="btn" value="<?php echo osc_esc_html( __('Apply') ) ; ?>" />
            </label>
        </div>
        <div class="table-contains-actions">
            <table class="table" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th class="col-bulkactions"><input id="check_all" type="checkbox" /></th>
                        <th><?php _e('E-mail') ; ?></th>
                        <th><?php _e('Name') ; ?></th>
                        <th class="col-date"><?php _e('Date') ; ?></th>
                        <th><?php _e('Update Date') ; ?></th>
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
<div class="showing-results">
    <?php echo osc_pagination_showing((Params::getParam('iPage')-1)*$aData['iDisplayLength']+1, ((Params::getParam('iPage')-1)*$aData['iDisplayLength'])+count($aData['aaData']), $aData['iTotalDisplayRecords']); ?>
</div>
<?php 
    osc_show_pagination_admin($aData);
?> 
<?php osc_current_admin_theme_path( 'parts/footer.php' ) ; ?>