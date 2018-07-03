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
        echo '<p>' . __('Manually upload Osclass plugins in .zip format. If you prefer, you can manually upload the decompressed plugin to <em>oc-content/plugins</em>.') . '</p>';
    }
    osc_add_hook('help_box','addHelp');

    osc_add_hook('admin_page_header','customPageHeader');
    function customPageHeader(){ ?>
        <h1><?php _e('Plugins'); ?>
            <a href="#" class="btn ico ico-32 ico-help float-right"></a>
        </h1>
<?php
    }

    function customPageTitle($string) {
        return sprintf(__('Add plugin &raquo; %s'), $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');

    osc_current_admin_theme_path('parts/header.php'); ?>
<div class="appearance">
    <h2 class="render-title"><?php _e('Add plugin'); ?></h2>
    <div id="upload-plugins">
        <div class="form-horizontal">
        <?php if( is_writable( osc_plugins_path() ) ) { ?>
            <div class="flashmessage flashmessage-info flashmessage-inline" style="display:block;">
                <p class="info"><?php printf( __('Download more plugins at %s'), '<a href="'.osc_admin_base_url(true) . '?page=market&action=plugins">Market</a>'); ?></p>
            </div>
            <form class="separate-top" action="<?php echo osc_admin_base_url(true); ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add_post" />
                <input type="hidden" name="page" value="plugins" />

                <div class="form-row">
                    <div class="form-label"><?php _e('Plugin package (.zip)'); ?></div>
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
                <p><?php _e('Cannot install new plugin'); ?></p>
            </div>
            <p class="text">
                <?php _e('The plugin folder is not writable on your server so you cannot upload plugins from the administration panel. Please make the folder writable and try again.'); ?>
            </p>
            <p class="text">
                <?php _e('To make the directory writable under UNIX execute this command from the shell:'); ?>
            </p>
            <pre>chmod 0755 <?php echo osc_plugins_path(); ?></pre>
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
</div>
<?php osc_current_admin_theme_path('parts/footer.php'); ?>