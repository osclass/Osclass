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
    function customPageHeader(){ ?>
        <h1><?php _e('Tools'); ?></h1>
    <?php
    }

    function customPageTitle($string) {
        return sprintf(__('Category stats &raquo; %s'), $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');

    osc_current_admin_theme_path( 'parts/header.php' ); ?>
<div id="backup-setting">
    <!-- settings form -->
    <div id="backup-settings">
        <h2 class="render-title"><?php _e('Category stats'); ?></h2>
        <p>
            <?php _e('You can recalculate stats by category, useful if the stats seem to be incorrect.'); ?>.
        </p>
        <form id="backup_form" name="backup_form" action="<?php echo osc_admin_base_url(true); ?>" method="post">
            <input type="hidden" name="page" value="tools" />
            <input type="hidden" name="action" value="category_post" />
            <fieldset>
                <div class="form-horizontal">
                    <div class="form-actions">
                        <input type="submit" id="backup_save" value="<?php echo osc_esc_html( __('Calculate category stats')); ?>" class="btn btn-submit" />
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
    <!-- /settings form -->
</div>
<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>