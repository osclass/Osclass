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

    osc_add_hook('admin_page_header','customPageHeader');
    function customPageHeader() { ?>
        <h1><?php echo osc_apply_filter('custom_appearance_title', __('Appearance')); ?></h1>
    <?php
    }

    function customPageTitle($string) {
        return sprintf(__('Appearance &raquo; %s'), $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');

    osc_current_admin_theme_path( 'parts/header.php' ); ?>
<!-- theme files -->
<div class="theme-files">
    <?php
        if( file_exists(__get('file')) ) {
            require_once __get('file');
        }
    ?>
</div>
<!-- /theme files -->
<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>