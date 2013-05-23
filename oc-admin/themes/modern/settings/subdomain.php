<?php if ( ! defined('OC_ADMIN')) exit('Direct access is not allowed.');
    /**
     * Osclass – software for creating and publishing online classified advertising platforms
     *
     * Copyright (C) 2012 OSCLASS
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

    $subdomains = __get('subdomains');
    function addHelp() {
        //TODO COMPLETE THIS HELP WITH MODIFICATIONS TO .htaccess FILE AND SERVER CONFIGURATION
        echo '<p>' . __('Add or delete subdomains. Keep in mind that some subdomains could be incompatible among them and your website will not work properly.') . '</p>';
    }
    osc_add_hook('help_box','addHelp');

    function customPageHeader(){ ?>
        <h1><?php _e('Users'); ?>
            <a href="<?php echo osc_admin_base_url(true) . '?page=users&action=settings'; ?>" class="btn ico ico-32 ico-engine float-right"></a>
            <a href="#" class="btn ico ico-32 ico-help float-right"></a>
        </h1>
<?php
    }
    osc_add_hook('admin_page_header','customPageHeader');

    function customPageTitle($string) {
        return sprintf(__('Manage subdomains &raquo; %s'), $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');

    //customize Head
    function customHead() { ?>
        <script type="text/javascript">
            $(document).ready(function(){
                // check_all bulkactions
                $("#check_all").change(function(){
                    var isChecked = $(this).prop("checked");
                    $('.col-bulkactions input').each( function() {
                        if( isChecked == 1 ) {
                            this.checked = true;
                        } else {
                            this.checked = false;
                        }
                    });
                });

                // dialog delete
                $("#dialog-subdomain-add").dialog({
                    autoOpen: false,
                    modal: true
                });

                // dialog bulk actions
                $("#dialog-bulk-actions").dialog({
                    autoOpen: false,
                    modal: true
                });
                $("#bulk-actions-submit").click(function() {
                    $("#datatablesForm").submit();
                });
                $("#bulk-actions-cancel").click(function() {
                    $("#datatablesForm").attr('data-dialog-open', 'false');
                    $('#dialog-bulk-actions').dialog('close');
                });
            });
        </script>
        <?php
    }
    osc_add_hook('admin_header','customHead', 10);

?>
<?php osc_current_admin_theme_path( 'parts/header.php' ); ?>
<h2 class="render-title"><?php _e('Manage subdomains'); ?> <a href="javascript:void(0);" onclick="$('#dialog-subdomain-add').dialog('open');" class="btn btn-mini"><?php _e('Add new'); ?></a></h2>
<div class="relative">
    <div id="users-toolbar" class="table-toolbar">
        <div class="float-right">
        </div>
    </div>
    <form class="" id="datatablesForm" action="<?php echo osc_admin_base_url(true); ?>" method="post">
        <input type="hidden" name="page" value="subdomain" />

        <div id="bulk-actions">
            <label>
                <?php osc_print_bulk_actions('bulk_actions', 'action', __get('bulk_options'), 'select-box-extra'); ?>
                <input type="submit" id="bulk_apply" class="btn" value="<?php echo osc_esc_html( __('Apply') ); ?>" />
            </label>
        </div>
        <div class="table-contains-actions">
            <table class="table" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th class="col-bulk"></th>
                        <th class="col-subdomain"><?php _e('Subdomain'); ?></th>
                        <th class="col-route"><?php _e('Route'); ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php if( count($subdomains) > 0 ) { ?>
                    <?php foreach($subdomains as $sd) { ?>
                        <tr>
                            <td class="col-bulk"><?php echo '#'; ?></td>
                            <td class="col-subdomain"><?php echo $sd['s_subdomain']; ?></td>
                            <td class="col-route"><?php echo $sd['s_route']; ?></td>
                        </tr>
                    <?php }; ?>
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
<form id="dialog-subdomain-add" method="get" action="<?php echo osc_admin_base_url(true); ?>" class="has-form-actions hide" title="<?php echo osc_esc_html(__('Add subdomain')); ?>">
    <input type="hidden" name="page" value="settings" />
    <input type="hidden" name="action" value="subdomain_add" />
    <div class="form-horizontal">
        <div class="form-row">
            <label><?php _e('Subdomain'); ?> <input type="text" name="s_subdomain" id="s_subdomain" /></label><br/>
            <select name="e_type" id="e_type" >
                <option value=""><?php _e('Select an option'); ?></option>
                <option value="category"><?php _e('Category'); ?></option>
                <option value="country"><?php _e('Country'); ?></option>
                <option value="region"><?php _e('Region'); ?></option>
                <option value="city"><?php _e('City'); ?></option>
                <option value="user"><?php _e('User'); ?></option>
            </select><br/>
            <label><?php _e('Route'); ?> <input type="text" name="s_route" id="s_route" /></label><br/>
        </div>
        <div class="form-actions">
            <div class="wrapper">
            <a class="btn" href="javascript:void(0);" onclick="$('#dialog-subdomain-add').dialog('close');"><?php _e('Cancel'); ?></a>
            <input id="subdomain-add-submit" type="submit" value="<?php echo osc_esc_html( __('Add') ); ?>" class="btn btn-red" />
            </div>
        </div>
    </div>
</form>
<div id="dialog-bulk-actions" title="<?php _e('Bulk actions'); ?>" class="has-form-actions hide">
    <div class="form-horizontal">
        <div class="form-row"></div>
        <div class="form-actions">
            <div class="wrapper">
                <a id="bulk-actions-cancel" class="btn" href="javascript:void(0);"><?php _e('Cancel'); ?></a>
                <a id="bulk-actions-submit" href="javascript:void(0);" class="btn btn-red" ><?php echo osc_esc_html( __('Delete') ); ?></a>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>
<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>