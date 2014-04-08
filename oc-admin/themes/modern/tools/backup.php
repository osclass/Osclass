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

    //customize Head
    function customHead(){
        ?>
        <script type="text/javascript">
            function submitForm(frm, type) {
                frm.action.value = 'backup-' + type;
                frm.submit();
            }
        </script>
        <?php
    }
    osc_add_hook('admin_header','customHead', 10);

    function render_offset(){
        return 'row-offset';
    }

    function addHelp() {
        echo '<p>' . __("Save a backup of all of your site's information: listings, users and configuration. You can save a backup on your server or on your computer.") . '</p>';
    }
    osc_add_hook('help_box','addHelp');

    osc_add_hook('admin_page_header','customPageHeader');
    function customPageHeader(){ ?>
        <h1><?php _e('Tools'); ?>
            <a href="#" class="btn ico ico-32 ico-help float-right"></a>
        </h1>
    <?php
    }

    function customPageTitle($string) {
        return sprintf(__('Backup &raquo; %s'), $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');

    osc_current_admin_theme_path( 'parts/header.php' ); ?>
<div id="backup-setting">
    <!-- settings form -->
                    <div id="backup-settings">
                        <h2 class="render-title"><?php _e('Backup'); ?></h2>
                        <form id="backup_form" name="backup_form" action="<?php echo osc_admin_base_url(true); ?>" method="post">
                            <input type="hidden" name="page" value="tools" />
                            <input type="hidden" name="action" value="" />
                            <fieldset>
                            <div class="form-horizontal">
                            <div class="form-row">
                                <div class="form-label"><?php _e('Backup folder'); ?></div>
                                <div class="form-controls">
                                    <input type="text" class="input-large" name="bck_dir" value="<?php echo osc_esc_html(osc_base_path()); ?>" />
                                    <div class="help-box">
                                        <?php _e("<strong>WARNING</strong>: If you don't specify a backup folder, the backup files will be created in the root of your Osclass installation."); ?>
                                        <br />
                                        <?php _e("This is the folder in which your backups will be created. We recommend that you choose a non-public path."); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <input type="button" id="backup_sql" onclick="javascript:submitForm(this.form, 'sql');" value="<?php echo osc_esc_html( __('Backup SQL (store on server)') ); ?>" class="btn btn-submit" />
                                <input type="button" id="backup_sql_file" onclick="javascript:submitForm(this.form, 'sql_file');" value="<?php echo osc_esc_html( __('Backup SQL (download file)') ); ?>" class="btn btn-submit" />
                                <input type="button" id="backup_zip" onclick="javascript:submitForm(this.form, 'zip');" value="<?php echo osc_esc_html( __('Backup files (store on server)') ); ?>" class="btn btn-submit" />
                            </div>
                        </div>
                        </fieldset>
                    </form>
                </div>
                <!-- /settings form -->
</div>
<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>