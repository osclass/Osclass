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
    function customPageHeader(){ ?>
        <h1><?php _e('Settings'); ?></h1>
<?php
    }

    function customPageTitle($string) {
        return sprintf(__('Add language &raquo; %s'), $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');

    osc_current_admin_theme_path('parts/header.php'); ?>
<div class="appearance">
    <h2 class="render-title"><?php _e('Add language'); ?></h2>
    <div id="upload-language">
        <div class="form-horizontal">
        <?php if( is_writable( osc_translations_path() ) ) { ?>
            <div class="flashmessage flashmessage-info flashmessage-inline" style="display:block;">
                <p class="info"><?php printf( __('Download more languages at %s'), '<a href="'.osc_admin_base_url(true) . '?page=market&action=languages">Market</a>'); ?></p>
            </div>
            <form class="separate-top" action="<?php echo osc_admin_base_url(true); ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add_post" />
                <input type="hidden" name="page" value="languages" />

                <div class="form-row">
                    <div class="form-label"> <?php _e('Language package (.zip)'); ?></div>
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
                <p><?php _e("Can't install a new language"); ?></p>
            </div>
            <p class="text">
                <?php _e("The translations folder is not writable on your server so you can't upload translations from the administration panel. Please make the translation folder writable and try again."); ?>
            </p>
            <p class="text">
                <?php _e('To make the directory writable under UNIX execute this command from the shell:'); ?>
            </p>
            <pre>chmod a+w <?php echo osc_translations_path(); ?></pre>
        <?php } ?>
        </div>
    </div>
</div>
<?php osc_current_admin_theme_path('parts/footer.php'); ?>