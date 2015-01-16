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
        echo '<p>' . __('Manually add Osclass themes in .zip format. If you prefer, you can manually upload the decompressed theme to <em>oc-content/themes</em>.') . '</p>';
    }
    osc_add_hook('help_box','addHelp');

    osc_add_hook('admin_page_header','customPageHeader');
    function customPageHeader(){ ?>
        <h1><?php _e('Appearance'); ?>
            <a href="#" class="btn ico ico-32 ico-help float-right"></a>
        </h1>
<?php
    }

    function customPageTitle($string) {
        return sprintf(__('Add theme &raquo; %s'), $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');

    osc_current_admin_theme_path('parts/header.php'); ?>
    <!-- themes list -->
    <div class="appearance">
        <h2 class="render-title"><?php _e('Add new theme'); ?></h2>
            <div id="upload-themes" class="ui-osc-tabs-panel">
                <div class="form-horizontal">
                <?php if( is_writable( osc_themes_path() ) ) { ?>
                    <div class="flashmessage flashmessage-info flashmessage-inline" style="display: block;">
                        <p class="info"><?php printf( __('Download more themes at %s'), '<a href="'.osc_admin_base_url(true) . '?page=market&action=themes">Market</a>'); ?></p>
                    </div>
                    <form class="separate-top" action="<?php echo osc_admin_base_url(true); ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="add_post" />
                        <input type="hidden" name="page" value="appearance" />
                        <div class="form-row">
                            <div class="form-label"><?php _e('Theme package (.zip)'); ?></div>
                            <div class="form-controls">
                                <div class="form-label-checkbox"><input type="file" name="package" id="package" /></div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <input type="submit" value="<?php echo osc_esc_html( __('Upload') ); ?>" class="btn btn-submit" />
                        </div>
                    </form>
                <?php } else { ?>
                    <div class="flashmessage flashmessage-error">
                        <a class="btn ico btn-mini ico-close" href="#">×</a>
                        <p><?php _e("Can't install a new theme"); ?></p>
                    </div>
                    <p class="text">
                        <?php _e("The theme folder is not writable on your server so you can't upload themes from the administration panel. Please make the theme folder writable and try again."); ?>
                    </p>
                    <p class="text">
                        <?php _e('To make the directory writable under UNIX execute this command from the shell:'); ?>
                    </p>
                    <pre>chmod a+w <?php echo osc_themes_path(); ?></pre>
                <?php } ?>
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
        <script>
        $(function() {
            $("#market_cancel").on("click", function(){
                $(".ui-dialog-content").dialog("close");
                return false;
            });

            $("#market_install").on("click", function(){
                $(".ui-dialog-content").dialog("close");
                //$(".ui-dialog-content").dialog({title:'Downloading...'}).html('Please wait until the download is completed');
                $('<div id="downloading"><div class="osc-modal-content">Please wait until the download is completed</div></div>').dialog({title:'Installing...',modal:true});
                $.getJSON(
                "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=market",
                {"code" : $("#market_code").attr("value")},
                function(data){
                    $("#downloading .osc-modal-content").html(data.message);
                    setTimeout(function(){
                      $(".ui-dialog-content").dialog("close");
                  },1000);
                });
                return false;
            });

            $.getJSON(
                "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=local_market",
                {"section" : "themes"},
                function(data){
                    $("#market_themes").html(" ");
                    if(data!=null && data.themes!=null) {
                        for(var i=0;i<data.themes.length;i++) {
                            var description = $(data.themes[i].s_description).text();
                            dots = '';
                            if(description.length > 80){
                                dots = '...';
                            }
                            var imgsrc = '<?php echo osc_current_admin_theme("img/marketblank.jpg"); ?>';
                            if(data.themes[i].s_image!=null) {
                                imgsrc = data.themes[i].s_image;
                            }
                            $("#market_themes").append('<div class="theme">'
                                +'<div class="theme-stage">'
                                    +'<img src="'+imgsrc+'" title="'+data.themes[i].s_title+'" alt="'+data.themes[i].s_title+'" />'
                                    +'<div class="theme-actions">'
                                        +'<a href="#'+data.themes[i].s_slug+'" class="btn btn-mini btn-green market-popup"><?php echo osc_esc_js(__('Install')); ?></a>'
                                        +'<a target="_blank" href="'+data.themes[i].s_preview+'" class="btn btn-mini btn-blue"><?php echo osc_esc_js(__('Preview')); ?></a>'
                                    +'</div>'
                                +'</div>'
                                +'<div class="theme-info">'
                                    +'<h3>'+data.themes[i].s_title+' '+data.themes[i].s_version+' <?php _e('by'); ?> <a target="_blank" href="">'+data.themes[i].s_contact_name+'</a></h3>'
                                +'</div>'
                                +'<div class="theme-description">'
                                    +description.substring(0,80)+dots
                                +'</div>'
                            +'</div>');
                        }
                    }
                    $("#market_themes").append('<div class="clear"></div>');
                }
            );
        });

        $('.market-popup').on('click',function(){
            $.getJSON(
                "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=check_market",
                {"code" : $(this).attr('href').replace('#','')},
                function(data){
                    if(data!=null) {
                        $("#market_thumb").attr('src',data.s_thumbnail);
                        $("#market_code").attr("value", data.s_slug);
                        $("#market_name").html(data.s_title);
                        $("#market_version").html(data.s_version);
                        $("#market_author").html(data.s_contact_name);
                        $("#market_url").attr('href',data.s_source_file);

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
    </div>
    <!-- /themes list -->
<?php osc_current_admin_theme_path('parts/footer.php'); ?>