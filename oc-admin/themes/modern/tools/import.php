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

    function render_offset(){
        return 'row-offset';
    }
    osc_add_hook('admin_page_header','customPageHeader');

    function addHelp() {
        /* xgettext:no-php-format */
        echo '<p>' . __("Upload registers from other Osclass installations or upload new geographic information to your site. <strong>Be careful</strong>: don’t use this option if you're not 100% sure what you're doing.") . '</p>';
    }
    osc_add_hook('help_box','addHelp');

    function customPageHeader(){ ?>
        <h1><?php _e('Tools'); ?>
            <a href="#" class="btn ico ico-32 ico-help float-right"></a>
        </h1>
    <?php
    }

    function customPageTitle($string) {
        return sprintf(__('Import &raquo; %s'), $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');

    osc_current_admin_theme_path( 'parts/header.php' ); ?>
    <!-- settings form -->
                    <div id="backup-settings">
                        <h2 class="render-title"><?php _e('Import'); ?></h2>
                        <form id="backup_form" name="backup_form" action="<?php echo osc_admin_base_url(true); ?>" enctype="multipart/form-data" method="post">
                            <input type="hidden" name="page" value="tools" />
                            <input type="hidden" name="action" value="import_post" />
                            <fieldset>
                            <div class="form-horizontal">
                            <div class="form-row">
                                <div class="form-label"><?php _e('File (.sql)'); ?></div>
                                <div class="form-controls">
                                    <input type="file" name="sql" id="sql" />
                                </div>
                            </div>
                            <div class="form-actions">
                                <input type="submit" value="<?php echo osc_esc_html( __('Import data') ); ?>" class="btn btn-submit" />
                            </div>
                        </div>
                        </fieldset>
                    </form>
                </div>
                <!-- /settings form -->
<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>