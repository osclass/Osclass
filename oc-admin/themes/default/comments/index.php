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
        <h1><?php _e('Manage Comments') ; ?>
            <a href="#" class="btn ico ico-32 ico-help float-right"></a>
	</h1>
<?php
    }
    osc_add_hook('admin_page_header','customPageHeader');
    //customize Head
    function customHead() { ?>
        <script type="text/javascript">
            // autocomplete users
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
    
    $aData  = __get('aComments');
?>
<?php osc_current_admin_theme_path( 'parts/header.php' ) ; ?>

<div id="help-box">
    <a href="#" class="btn ico ico-20 ico-close">x</a>
    <h3>What does a red highlight mean?</h3>
    <p>This is where I would provide help to the user on how everything in my admin panel works. Formatted HTML works fine in here too.
    Red highlight means that the listing has been marked as spam.</p>
</div>

<h2 class="render-title"><?php _e('Manage reported listings') ; ?></h2>
<div style="position:relative;">
    <div id="listing-toolbar">
        <div class="float-right">
            
        </div>
    </div>
    
    <form class="" id="datatablesForm" action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
        <input type="hidden" name="page" value="comments" />
        <input type="hidden" name="action" value="bulk_actions" />
        <div id="bulk-actions">
            <label>
                <select id="bulk_actions" name="bulk_actions" class="select-box-extra">
                    <option value=""><?php _e('Bulk actions') ; ?></option>
                    <option value="delete_all"><?php _e('Delete') ; ?></option>
                    <option value="activate_all"><?php _e('Activate') ; ?></option>
                    <option value="deactivate_all"><?php _e('Deactivate') ; ?></option>
                    <option value="enable_all"><?php _e('Block') ; ?></option>
                    <option value="disable_all"><?php _e('Unblock') ; ?></option>
                    <?php $onclick_bulkactions= 'onclick="javascript:return confirm(\'' . osc_esc_js( __('You are doing bulk actions. Are you sure you want to continue?') ) . '\')"' ; ?>
                </select> <input type="submit" <?php echo $onclick_bulkactions; ?> id="bulk_apply" class="btn" value="<?php echo osc_esc_html( __('Apply') ) ; ?>" />
            </label>
        </div>
        <div class="table-hast-actions">
            <table class="table" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th class="col-bulkactions"><input id="check_all" type="checkbox" /></th>
                        <th><?php _e('Author') ; ?></th>
                        <th><?php _e('Comment') ; ?></th>
                        <th><?php _e('Date') ; ?></th>
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
                        <td colspan="4" style="text-align: center;">
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
    $pageTotal = ceil($aData['iTotalDisplayRecords']/$aData['iDisplayLength']);
    
    $pageActual = Params::getParam('iPage') ;
    $urlActual = osc_admin_base_url(true).'?'.$_SERVER['QUERY_STRING'];
    $urlActual = preg_replace('/&iPage=(\d+)?/', '', $urlActual) ;
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