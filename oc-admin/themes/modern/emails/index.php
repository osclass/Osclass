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
        echo '<p>' . __("Modify the emails your site's users receive when they join your site, when someone shows interest in their ad, to recover their password... <strong>Be careful</strong>: don't modify any of the words that appear within brackets.") . '</p>';
    }
    osc_add_hook('help_box','addHelp');

    function customPageHeader(){ ?>
        <h1><?php _e('Settings'); ?>
            <a href="#" class="btn ico ico-32 ico-help float-right"></a>
        </h1>
<?php
    }
    osc_add_hook('admin_page_header','customPageHeader');

    function customPageTitle($string) {
        return sprintf(__('Email templates &raquo; %s'), $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');

    $aData = __get('aEmails');

    osc_current_admin_theme_path( 'parts/header.php' ); ?>
<h2 class="render-title"><?php _e('Emails templates'); ?></h2>
<div class="table-contains-actions">
    <table class="table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th class="col-name"><?php _e('Name'); ?></th>
                <th class="col-title"><?php _e('Title'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php if(count($aData['aaData'])>0) { ?>
        <?php foreach( $aData['aaData'] as $array) { ?>
            <tr>
            <?php foreach($array as $key => $value) { ?>
                <td>
                <?php echo $value; ?>
                </td>
            <?php } ?>
            </tr>
        <?php } ?>
        <?php } else { ?>
        <tr>
            <td colspan="6" class="text-center">
            <p><?php _e('No data available in table'); ?></p>
            </td>
        </tr>
        <?php } ?>
        </tbody>
    </table>
    <div id="table-row-actions"></div> <!-- used for table actions -->
</div>
<?php
    osc_show_pagination_admin($aData);
?>
<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>