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
        <h1><?php _e('Settings') ; ?>
            <a href="#" class="btn ico ico-32 ico-help float-right"></a>
            <a href="<?php echo osc_admin_base_url(true).'?page=settings&action=currencies&type=add'; ?>" class="btn btn-green ico ico-32 ico-add-white float-right"><?php _e('Add'); ?></a>
	   </h1>
<?php
    }
    osc_add_hook('admin_page_header','customPageHeader');

    function customPageTitle($string) {
        return sprintf(__('Currencies &raquo; %s'), $string);
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
   
    $aCurrencies = __get('aCurrencies') ;

    $aData = array() ;
    foreach($aCurrencies as $currency) {
        $row = array() ;
        $row[] = '<input type="checkbox" name="code[]" value="' . osc_esc_html($currency['pk_c_code']) . '" />' ;

        $options   = array() ;
        $options[] = '<a onclick="javascript:return confirm(\'' . osc_esc_js( __("This action can't be undone. Are you sure you want to continue?") ) . '\');" href="' . osc_admin_base_url(true) . '?page=settings&amp;action=currencies&amp;type=delete&amp;code=' . $currency['pk_c_code'] . '">' . __('Delete') . '</a>' ;
        $options[] = '<a href="' . osc_admin_base_url(true) . '?page=settings&amp;action=currencies&amp;type=edit&amp;code=' . $currency['pk_c_code'] . '">' . __('Edit') . '</a>' ;

        $row[] = $currency['pk_c_code'] . ' (' . implode(' &middot; ', $options) . ')' ;
        $row[] = $currency['s_name'] ;
        $row[] = $currency['s_description'] ;
        $aData[] = $row ;
    }

    osc_current_admin_theme_path( 'parts/header.php' ) ; ?>
<h2 class="render-title"><?php _e('Currencies') ; ?></h2>
<div style="position:relative;">
    <div id="listing-toolbar"> <!-- FERNANDO add class currencies-toolbar-->
        <div class="float-right">
            
        </div>
    </div>
    
    <form class="" id="datatablesForm" action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
        <input type="hidden" name="page" value="settings" />
        <input type="hidden" name="action" value="currencies" />
        <input type="hidden" name="type" value="delete" />
        <div id="bulk-actions">
            <label>
                <select id="action" name="bulk_actions" class="select-box-extra">
                    <option value=""><?php _e('Bulk actions') ; ?></option>
                    <option value="delete_all"><?php _e('Delete') ; ?></option>
                    <?php $onclick_bulkactions= 'onclick="javascript:return confirm(\'' . osc_esc_js( __('You are doing bulk actions. Are you sure you want to continue?') ) . '\')"' ; ?>
                </select> <input type="submit" <?php echo $onclick_bulkactions; ?> id="bulk_apply" class="btn" value="<?php echo osc_esc_html( __('Apply') ) ; ?>" />
            </label>
        </div>
        <table class="table" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th class="col-bulkactions"><input id="check_all" type="checkbox" /></th>
                    <th><?php _e('Code') ; ?></th>
                    <th><?php _e('Name') ; ?></th>
                    <th><?php _e('Description') ; ?></th>   
                </tr>
            </thead>
            <tbody>
            <?php foreach( $aData as $array) : ?>
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
    </form>
</div>    
<?php osc_current_admin_theme_path( 'parts/footer.php' ) ; ?>