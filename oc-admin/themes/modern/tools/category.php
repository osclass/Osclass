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