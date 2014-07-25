<?php if ( ! defined('OC_ADMIN')) exit('Direct access is not allowed.');
/*
 * Copyright 2014 Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

    function addHelp() {
        echo '<p>' . __("Add, edit or delete the language in which your Osclass is displayed, both the part that's viewable by users and the admin panel.") . '</p>';
    }
    osc_add_hook('help_box','addHelp');

    function customPageHeader(){ ?>
        <h1><?php _e('Settings'); ?>
            <a href="#" class="btn ico ico-32 ico-help float-right"></a>
            <a href="<?php echo osc_admin_base_url(true); ?>?page=languages&amp;action=add" class="btn btn-green ico ico-32 ico-add-white float-right" ><?php _e('Add language'); ?></a>
        </h1>
<?php
    }
    osc_add_hook('admin_page_header','customPageHeader');

    function customPageTitle($string) {
        return sprintf(__('Languages &raquo; %s'), $string);
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
                $("#dialog-language-delete").dialog({
                    autoOpen: false,
                    modal: true,
                    title: '<?php echo osc_esc_js( __('Delete language') ); ?>'
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
                // dialog bulk actions function
                $("#datatablesForm").submit(function() {
                    if( $("#bulk_actions option:selected").val() == "" ) {
                        return false;
                    }

                    if( $("#datatablesForm").attr('data-dialog-open') == "true" ) {
                        return true;
                    }

                    $("#dialog-bulk-actions .form-row").html($("#bulk_actions option:selected").attr('data-dialog-content'));
                    $("#bulk-actions-submit").html($("#bulk_actions option:selected").text());
                    $("#datatablesForm").attr('data-dialog-open', 'true');
                    $("#dialog-bulk-actions").dialog('open');
                    return false;
                });
            });

            // dialog delete function
            function delete_dialog(item_id) {
                $("#dialog-language-delete input[name='id[]']").attr('value', item_id);
                $("#dialog-language-delete").dialog('open');
                return false;
            }
        </script>
        <?php
    }
    osc_add_hook('admin_header','customHead', 10);

    $iDisplayLength = __get('iDisplayLength');
    $aData          = __get('aLanguages');

    osc_current_admin_theme_path( 'parts/header.php' );
?>
<h2 class="render-title"><?php _e('Manage Languages'); ?> <a href="<?php echo osc_admin_base_url(true); ?>?page=languages&amp;action=add" class="btn btn-mini"><?php _e('Add new'); ?></a></h2>
<div class="relative">
    <div id="language-toolbar" class="table-toolbar">
        <div class="float-right">

        </div>
    </div>
    <form class="" id="datatablesForm" action="<?php echo osc_admin_base_url(true); ?>" method="post" data-dialog-open="false">
        <input type="hidden" name="page" value="languages" />
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
                        <th class="col-bulkactions"><input id="check_all" type="checkbox" /></th>
                        <th><?php _e('Name'); ?></th>
                        <th><?php _e('Short name'); ?></th>
                        <th><?php _e('Description'); ?></th>
                        <th><?php _e('Enabled (website)'); ?></th>
                        <th><?php _e('Enabled (oc-admin)'); ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php if(count($aData['aaData'])>0) { ?>
                <?php foreach( $aData['aaData'] as $array) { ?>
                    <tr>
                    <?php foreach($array as $key => $value) { ?>
                        <?php if( $key==0 ) { ?>
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
                    <td colspan="6" class="text-center">
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
<?php
    osc_show_pagination_admin($aData);
?>
<form id="dialog-language-delete" method="get" action="<?php echo osc_admin_base_url(true); ?>" class="has-form-actions hide">
    <input type="hidden" name="page" value="languages" />
    <input type="hidden" name="action" value="delete" />
    <input type="hidden" name="id[]" value="" />
    <div class="form-horizontal">
        <div class="form-row">
            <?php _e('Are you sure you want to delete this language?'); ?>
        </div>
        <div class="form-actions">
            <div class="wrapper">
            <a class="btn" href="javascript:void(0);" onclick="$('#dialog-language-delete').dialog('close');"><?php _e('Cancel'); ?></a>
            <input id="language-delete-submit" type="submit" value="<?php echo osc_esc_html( __('Delete') ); ?>" class="btn btn-red" />
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

<div id="market_installer" class="has-form-actions hide">
    <form action="" method="post">
        <input type="hidden" name="market_code" id="market_code" value="" />
        <div class="osc-modal-content-market">
            <img src="" id="market_thumb" class="float-left"/>
            <table class="table" cellpadding="0" cellspacing="0">
                <tbody>
                    <tr class="table-first-row">
                        <td><?php _e('Name'); ?></td>
                        <td><span id="market_name"><?php _e("Loading data"); ?></span></td>
                    </tr>
                    <tr class="even">
                        <td><?php _e('Version'); ?></td>
                        <td><span id="market_version"><?php _e("Loading data"); ?></span></td>
                    </tr>
                    <tr>
                        <td><?php _e('Author'); ?></td>
                        <td><span id="market_author"><?php _e("Loading data"); ?></span></td>
                    </tr>
                    <tr class="even">
                        <td><?php _e('URL'); ?></td>
                        <td><span id="market_url_span"><a id="market_url" href="#"><?php _e("Download manually"); ?></a></span></td>
                    </tr>
                </tbody>
            </table>
            <div class="clear"></div>
        </div>
        <div class="form-actions">
            <div class="wrapper">
                <button id="market_cancel" class="btn btn-red" ><?php _e('Cancel'); ?></button>
                <button id="market_install" class="btn btn-submit" ><?php _e('Continue install'); ?></button>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(function() {
        $("#market_cancel").on("click", function(){
            $(".ui-dialog-content").dialog("close");
            return false;
        });

        $("#market_install").on("click", function(){
            $(".ui-dialog-content").dialog("close");
            $('<div id="downloading"><div class="osc-modal-content"><?php echo osc_esc_js(__('Please wait until the download is completed')); ?></div></div>').dialog({title:'<?php echo osc_esc_js(__('Downloading')); ?>...',modal:true});
            $.getJSON(
            "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=market&<?php echo osc_csrf_token_url(); ?>",
            {"code" : $("#market_code").attr("value"), "section" : 'languages'},
            function(data){
                var content = data.message;
                if(data.error == 0) { // no errors
                    content += '<h3><?php echo osc_esc_js(__('The theme has been downloaded correctly, proceed to activate or preview it.')); ?></h3>';
                    content += "<p>";
                    content += '<a class="btn btn-mini btn-green" href="<?php echo osc_admin_base_url(true); ?>?page=languages&marketError='+data.error+'&slug='+data.data['s_update_url']+'"><?php echo osc_esc_js(__('Ok')); ?></a>';
                    content += '<a class="btn btn-mini" href="javascript:location.reload(true)"><?php echo osc_esc_js(__('Close')); ?></a>';
                    content += "</p>";
                } else {
                    content += '<a class="btn btn-mini" href="javascript:location.reload(true)"><?php echo osc_esc_js(__('Close')); ?></a>';
                }
                $("#downloading .osc-modal-content").html(content);
            });
            return false;
        });
    });

    $('.btn-market-popup').on('click',function(){
        $.getJSON(
            "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=check_market",
            {"code" : $(this).attr('href').replace('#',''), 'section' : 'languages'},
            function(data){
                if(data!=null) {
                    $("#market_thumb").attr('src',data.s_thumbnail);
                    $("#market_code").attr("value", data.s_update_url);
                    $("#market_name").html(data.s_title);
                    $("#market_version").html(data.s_version);
                    $("#market_author").html(data.s_contact_name);
                    $("#market_url").attr('href',data.s_source_file);
                    $('#market_install').html("<?php echo osc_esc_js( __('Update') ); ?>");

                    $('#market_installer').dialog({
                        modal:true,
                        title: '<?php echo osc_esc_js( __('Osclass Market') ); ?>',
                        width:485
                    });
                }
            }
        );

        return false;
    });
</script>

<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>