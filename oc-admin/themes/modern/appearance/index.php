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

    osc_enqueue_script('jquery-validate');

    //getting variables for this view
    $themes = __get("themes");
    $info   = WebThemes::newInstance()->loadThemeInfo(osc_theme());

    //customize Head
    function customHead() { ?>
        <script type="text/javascript">
            $(document).ready(function() {
                // dialog delete
                $("#dialog-delete-theme").dialog({
                    autoOpen: false,
                    modal: true,
                    title: '<?php echo osc_esc_js( __('Delete theme') ); ?>'
                });
            });

            // dialog delete function
            function delete_dialog(theme) {
                $("#dialog-delete-theme input[name='webtheme']").attr('value', theme);
                $("#dialog-delete-theme").dialog('open');
                return false;
            }
        </script>
        <?php
    }
    osc_add_hook('admin_header','customHead', 10);

    function addHelp() {
        echo '<p>' . sprintf(__("Change your site's look and feel by activating a theme among those available. You can download new themes from the <a href=\"%s\">market</a>. <strong>Be careful</strong>: if your theme has been customized, you'll lose all changes if you change to a new theme."), osc_admin_base_url(true) . '?page=market&action=themes') . '</p>';
    }
    osc_add_hook('help_box','addHelp');

    osc_add_hook('admin_page_header','customPageHeader');
    function customPageHeader(){ ?>
        <h1><?php _e('Appearance'); ?>
            <a href="#" class="btn ico ico-32 ico-help float-right"></a>
            <a href="<?php echo osc_admin_base_url(true); ?>?page=appearance&amp;action=add" class="btn btn-green ico ico-32 ico-add-white float-right"><?php _e('Add theme'); ?></a>
        </h1>
    <?php
    }

    function customPageTitle($string) {
        return sprintf(__('Appearance &raquo; %s'), $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');

    osc_current_admin_theme_path( 'parts/header.php' ); ?>
<div id="appearance-page">
    <!-- themes list -->
    <div class="appearance">
        <div id="tabs" class="ui-osc-tabs ui-tabs-right">
            <ul>
                <li><a href="#market" onclick="window.location = '<?php echo osc_admin_base_url(true) . '?page=market&action=themes'; ?>'; return false; "><?php _e('Market'); ?></a></li>
                <li><a href="#available-themes"><?php _e('Available themes'); ?></a></li>
            </ul>
            <div id="available-themes" class="ui-osc-tabs-panel">
                <h2 class="render-title"><?php _e('Current theme'); ?> <a href="<?php echo osc_admin_base_url(true); ?>?page=appearance&amp;action=add" class="btn btn-mini"><?php _e('Add new'); ?></a></h2>
                <div class="current-theme">
                    <div class="theme">
                        <img src="<?php echo osc_base_url(); ?>/oc-content/themes/<?php echo osc_theme(); ?>/screenshot.png" title="<?php echo $info['name']; ?>" alt="<?php echo $info['name']; ?>" />
                        <div class="theme-info">
                            <h3><?php echo $info['name']; ?> <?php echo $info['version']; ?> <?php _e('by'); ?> <a target="_blank" href="<?php echo $info['author_url']; ?>"><?php echo $info['author_name']; ?></a></h3>
                        </div>
                        <div class="theme-description">
                            <?php echo $info['description']; ?>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
                <h2 class="render-title"><?php _e('Available themes'); ?></h2>
                <div class="available-theme">
                    <?php $aThemesToUpdate = json_decode( osc_get_preference('themes_to_update') );
                    $bThemesToUpdate = (is_array($aThemesToUpdate))?true:false;
                    $csrf_token = osc_csrf_token_url();
                    foreach($themes as $theme) { ?>
                    <?php
                            if( $theme == osc_theme() ) {
                                continue;
                            }
                            $info = WebThemes::newInstance()->loadThemeInfo($theme);
                    ?>
                    <div class="theme">
                        <div class="theme-stage">
                            <img src="<?php echo osc_base_url(); ?>/oc-content/themes/<?php echo $theme; ?>/screenshot.png" title="<?php echo $info['name']; ?>" alt="<?php echo $info['name']; ?>" />
                            <div class="theme-actions">
                                <a href="<?php echo osc_admin_base_url(true); ?>?page=appearance&amp;action=activate&amp;theme=<?php echo $theme; ?>&amp;<?php echo $csrf_token; ?>" class="btn btn-mini btn-green"><?php _e('Activate'); ?></a>
                                <a target="_blank" href="<?php echo osc_base_url(true); ?>?theme=<?php echo $theme; ?>" class="btn btn-mini btn-blue"><?php _e('Preview'); ?></a>
                                <a onclick="return delete_dialog('<?php echo $theme; ?>');" href="<?php echo osc_admin_base_url(true); ?>?page=appearance&amp;action=delete&amp;webtheme=<?php echo $theme; ?>&amp;<?php echo $csrf_token; ?>" class="btn btn-mini float-right delete"><?php _e('Delete'); ?></a>
                                <?php
                                if($bThemesToUpdate) {
                                    if(in_array($theme,$aThemesToUpdate )){
                                    ?>
                                    <a href='#<?php echo htmlentities(@$info['theme_update_uri']); ?>' class="btn btn-mini btn-orange market-popup"><?php _e("Update"); ?></a>
                                <?php };
                                }; ?>
                            </div>
                        </div>
                        <div class="theme-info">
                            <h3><?php echo $info['name']; ?> <?php echo $info['version']; ?> <?php _e('by'); ?> <a target="_blank" href="<?php echo $info['author_url']; ?>"><?php echo $info['author_name']; ?></a></h3>
                        </div>
                        <div class="theme-description">
                            <?php echo $info['description']; ?>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="clear"></div>
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
        </div>
    </div>
    <!-- /themes list -->
</div>
<form id="dialog-delete-theme" method="get" action="<?php echo osc_admin_base_url(true); ?>" class="has-form-actions hide">
    <input type="hidden" name="page" value="appearance" />
    <input type="hidden" name="action" value="delete" />
    <input type="hidden" name="webtheme" value="" />
    <div class="form-horizontal">
        <div class="form-row">
            <?php _e('This action can not be undone. Are you sure you want to delete the theme?'); ?>
        </div>
        <div class="form-actions">
            <div class="wrapper">
            <a class="btn" href="javascript:void(0);" onclick="$('#dialog-delete-theme').dialog('close');"><?php _e('Cancel'); ?></a>
            <input id="delete-theme-submit" type="submit" value="<?php echo osc_esc_html( __('Uninstall') ); ?>" class="btn btn-red" />
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
    $(function() {
        $( "#tabs" ).tabs({ active: -1 });

        $("#market_cancel").on("click", function(){
            $(".ui-dialog-content").dialog("close");
            return false;
        });

        $("#market_install").on("click", function(){
            $(".ui-dialog-content").dialog("close");
            $('<div id="downloading"><div class="osc-modal-content"><?php echo osc_esc_js(__('Please wait until the download is completed')); ?></div></div>').dialog({title:'<?php echo osc_esc_js(__('Downloading')); ?>...',modal:true});
            $.getJSON(
            "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=market&<?php echo osc_csrf_token_url(); ?>",
            {"code" : $("#market_code").attr("value"), "section" : 'themes'},
            function(data){
                var content = data.message;
                if(data.error == 0) { // no errors
                    content += '<h3><?php echo osc_esc_js(__('The theme has been downloaded correctly, proceed to activate or preview it.')); ?></h3>';
                    content += "<p>";
                    content += '<a class="btn btn-mini btn-green" href="<?php echo osc_admin_base_url(true); ?>?page=appearance&marketError='+data.error+'&slug='+data.data['s_update_url']+'"><?php echo osc_esc_js(__('Ok')); ?></a>';
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

    $('.market-popup').on('click',function(){
        $.getJSON(
            "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=check_market",
            {"code" : $(this).attr('href').replace('#',''), 'section' : 'themes'},
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