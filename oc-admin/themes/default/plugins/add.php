<?php
    /**
     * OSClass – software for creating and publishing online classified advertising platforms
     *
     * Copyright (C) 2010 OSCLASS
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
        <h1 class="dashboard"><?php _e('Plugins') ; ?></h1>
<?php
    }

    function customPageTitle($string) {
        return sprintf(__('Add plugin &raquo; %s'), $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');

    osc_current_admin_theme_path('parts/header.php') ; ?>
<div class="appearance">
    <h2 class="render-title"><?php _e('Add plugin') ; ?></h2>
    <div id="upload-language">
        <div class="form-horizontal">
        <?php if( is_writable( osc_plugins_path() ) ) { ?>
            <div class="flashmessage flashmessage-info">
                <p class="info"><?php printf( __('Download more plugins at %s'), '<a href="https://sourceforge.net/projects/osclass/files/Plugins/" target="_blank">Sourceforge</a>') ; ?></p>
            </div>
            <form action="<?php echo osc_admin_base_url(true) ; ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add_post" />
                <input type="hidden" name="page" value="plugins" />

                <div class="form-row">
                    <div class="form-label"><?php _e('Plugin package (.zip)') ; ?></div>
                    <div class="form-controls">
                        <div class="form-label-checkbox"><input type="file" name="package" id="package" /></div>
                    </div>
                </div>
                <div class="form-actions">
                    <input type="submit" value="<?php echo osc_esc_html( __('Upload') ) ; ?>" class="btn btn-submit" />
                </div>
            </form>
        <?php } else { ?>
            <div class="flashmessage flashmessage-error">
                <a class="btn ico btn-mini ico-close" href="#">×</a>
                <p><?php _e('Cannot install a new plugin') ; ?></p>
            </div>
            <p class="text">
                <?php _e('The plugin folder is not writable on your server and you cannot upload plugins from the administration panel. Please make the folder writable') ; ?>
            </p>
            <p class="text">
                <?php _e('To make the directory writable under UNIX execute this command from the shell:') ; ?>
            </p>
            <pre>chmod a+w <?php echo osc_plugins_path() ; ?></pre>
        <?php } ?>
        </div>
    </div>
</div>
<?php osc_current_admin_theme_path('parts/footer.php') ; ?>