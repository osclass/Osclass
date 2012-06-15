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

    function customPageHeader() { ?>
        <h1><?php _e('Pages'); ?>
            <a href="#" class="btn ico ico-32 ico-help float-right"></a>
            <a href="<?php echo osc_admin_base_url(true); ?>?page=pages&amp;action=add" class="btn btn-green ico ico-32 ico-add-white float-right"><?php _e('Create page'); ?></a>
        </h1>
<?php
    }
    osc_add_hook('admin_page_header','customPageHeader');

    function customPageTitle($string) {
        return sprintf(__('Pages &raquo; %s'), $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');

    //customize Head
    function customHead() { ?>
        <script type="text/javascript">
            function order_up(id) {
                $('#datatables_list_processing').show() ;
                $.ajax({
                    url: "<?php echo osc_admin_base_url(true)?>?page=ajax&action=order_pages&id="+id+"&order=up",
                    success: function(res) {
                        // TODO improve
                        window.location.reload( true );
                    },
                    error: function(){
                        // alert error
                        // TODO
                    }
                });
            }

            function order_down(id) {
                $('#datatables_list_processing').show();
                $.ajax({
                    url: "<?php echo osc_admin_base_url(true)?>?page=ajax&action=order_pages&id="+id+"&order=down",
                    success: function(res){
                        // TODO improve
                        window.location.reload( true );
                    },
                    error: function(){
                        // alert error
                        // TODO
                    }
                });
            }

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

    $aData = __get('aPages');

    osc_current_admin_theme_path( 'parts/header.php' ); ?>
<h2 class="render-title"><?php _e('Manage pages'); ?></h2>
<div class="relative">
    <div id="pages-toolbar" class="table-toolbar">
    </div>
    <form class="" id="datatablesForm" action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
        <input type="hidden" name="page" value="pages" />
        <div id="bulk-actions">
            <label>
                <select id="action" name="bulk_actions" class="select-box-extra">
                    <option value=""><?php _e('Bulk actions'); ?></option>
                    <option value="delete"><?php _e('Delete'); ?></option>
                    <?php $onclick_bulkactions= 'onclick="javascript:return confirm(\'' . osc_esc_js( __('You are doing bulk actions. Are you sure you want to continue?') ) . '\')"'; ?>
                </select> <input type="submit" <?php echo $onclick_bulkactions; ?> id="bulk_apply" class="btn" value="<?php echo osc_esc_html( __('Apply') ); ?>" />
            </label>
        </div>
        <div class="table-contains-actions">
            <table class="table" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th class="col-bulkactions"><input id="check_all" type="checkbox" /></th>
                        <th><?php _e('Internal name'); ?></th>
                        <th class="col-title"><?php _e('Title'); ?></th>
                        <th class="col-order"><?php _e('Order'); ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php if( count($aData['aaData']) > 0 ) { ?>
                <?php foreach( $aData['aaData'] as $array) { ?>
                    <tr>
                    <?php foreach($array as $key => $value) { ?>
                        <?php if( $key == 0 ) { ?>
                        <td class="col-bulkactions">
                        <?php } else { ?>
                        <td>
                        <?php } ?>
                        <?php echo $value; ?>
                        </td>
                    <?php } ?>
                    </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                    <td colspan="4" class="text-center">
                    <p><?php _e('No data available in table'); ?></p>
                    </td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
            <div id="table-row-actions"></div> <!-- used for table actions -->
        </div>
    </form>
</div>
<div class="has-pagination">
<?php     
    $pageActual = Params::getParam('iPage') ;
    $urlActual  = osc_admin_base_url(true).'?'.$_SERVER['QUERY_STRING'];
    $urlActual  = preg_replace('/&iPage=(\d)+/', '', $urlActual) ;
    $pageTotal  = ceil($aData['iTotalDisplayRecords']/$aData['iDisplayLength']);
    $params     = array(
        'total'    => $pageTotal,
        'selected' => $pageActual - 1,
        'url'      => $urlActual . '&iPage={PAGE}',
        'sides'    => 5
    );
    $pagination = new Pagination($params);
    $aux = $pagination->doPagination();

    if( $pageTotal > 1) {
        echo $aux;
    }
?>
</div>
<?php osc_current_admin_theme_path( 'parts/footer.php' ) ; ?>