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
        <h1><?php _e('Manage Media') ; ?>
            <a href="#" class="btn ico ico-32 ico-engine float-right"></a>
            <a href="#" class="btn ico ico-32 ico-help float-right"></a>
	</h1>
<?php
    }
    osc_add_hook('admin_page_header','customPageHeader');

    function customPageTitle($string) {
        return sprintf(__('Media &raquo; %s'), $string);
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
    $aData          = __get('aMedia'); 

?>
<?php osc_current_admin_theme_path( 'parts/header.php' ) ; ?>
<div class="relative">
    <div id="media-toolbar" class="table-toolbar">
    </div>
    
    <form class="" id="datatablesForm" action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
        <input type="hidden" name="page" value="media" />
        
        <div id="bulk-actions">
            <label>
                <select id="action" name="bulk_actions" class="select-box-extra">
                    <option value=""><?php _e('Bulk actions'); ?></option>
                    <option value="delete_all"><?php _e('Delete') ?></option>
                    <?php $onclick_bulkactions= 'onclick="javascript:return confirm(\'' . osc_esc_js( __('You are doing bulk actions. Are you sure you want to continue?') ) . '\')"' ; ?>
                </select> <input type="submit" <?php echo $onclick_bulkactions; ?> id="bulk_apply" class="btn" value="<?php echo osc_esc_html( __('Apply') ) ; ?>" />
            </label>
        </div>
        <table class="table" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th class="col-bulkactions"><input id="check_all" type="checkbox" /></th>
                    <th><?php _e('E-mail') ; ?></th>
                    <th><?php _e('Name') ; ?></th>
                    <th><?php _e('Attached to') ; ?></th>
                    <th class="col-date"><?php _e('Date') ; ?></th>
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
                <td colspan="5" class="text-center">
                <p><?php _e('No data available in table') ; ?></p>
                </td>
            </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </form>
</div>
<div class="showing-results">
    <?php echo osc_pagination_showing((Params::getParam('iPage')-1)*$aData['iDisplayLength']+1, ((Params::getParam('iPage')-1)*$aData['iDisplayLength'])+count($aData['aaData']), $aData['iTotalDisplayRecords'], $aData['iTotalRecords']); ?>
</div>
<?php 
    osc_show_pagination_admin($aData);
    osc_current_admin_theme_path( 'parts/footer.php' );
?>