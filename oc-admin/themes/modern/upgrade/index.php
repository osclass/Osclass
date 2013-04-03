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
        <h1><?php _e('Tools'); ?></h1>
    <?php
    }

    function customPageTitle($string) {
        return __('Upgrade');
    }
    osc_add_filter('admin_title', 'customPageTitle');

    //customize Head
    function customHead(){ ?>
        <script type="text/javascript">
            $(document).ready(function(){
                if (typeof $.uniform != 'undefined') {
                    $('textarea, button,select, input:file').uniform();
                }

                <?php if(Params::getParam('confirm')=='true') {?>
                    $('#output').show();
                    $('#tohide').hide();

                    $.get('<?php echo osc_admin_base_url(true); ?>?page=upgrade&action=upgrade-funcs' , function(data) {
                        $('#loading_immage').hide();
                        $('#result').append(data+"<br/>");
                    });
                <?php } ?>
            });
        </script>
    <?php }

    osc_add_hook('admin_header','customHead', 10);

    osc_current_admin_theme_path( 'parts/header.php' ); ?>

    <div id="backup-settings">
        <h2 class="render-title"><?php _e('Upgrade'); ?></h2>
        <div id="result">
            <div id="output" style="display:none">
                <img id="loading_immage" src="<?php echo osc_current_admin_theme_url('images/loading.gif'); ?>" title="" alt="" />
                <?php _e('Upgrading your Osclass installation (this could take a while): ', 'admin'); ?>
            </div>
            <div id="tohide">
                <p>
                    <?php _e('You have uploaded a new version of Osclass, you need to upgrade Osclass for it to work correctly.'); ?>
                </p>
                <a class="btn" href="<?php echo osc_admin_base_url(true); ?>?page=upgrade&confirm=true"><?php _e('Upgrade now'); ?></a>
            </div>
        </div>
    </div>
<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>
