<?php
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
    function addHelp() {
        echo '<p>' . __('Browse and download available Osclass plugins, from a constantly-updated selection. After downloading a plugin, you have to install it and configure it to get it up and running.') . '</p>';
    }
    osc_add_hook('help_box','addHelp');
    osc_current_admin_theme_path('market/header.php');

    $count      = __get('count');
    $aPlugins   = __get('aPlugins');
    $aThemes    = __get('aThemes');
    $aLanguages = __get('aLanguages');

    $colors = array_merge(gradienColors(),array_merge(gradienColors(),gradienColors()));
?>
<div class="grid-market">
    <h2 class="section-title"><?php _e('Recommended plugins for You'); ?><a href="<?php echo osc_admin_base_url(true).'?page=market&action=plugins'; ?>"><?php echo sprintf(__('View all (%s)'), $count['pluginsTotal']); ?></a></h2>
    <?php
    foreach($aPlugins as $item){
        drawMarketItem($item,$colors[array_rand($colors)]);
    }
    if(count($aPlugins)==0) {
    ?>
    <p class="flashmessage flashmessage-inline flashmessage-error"><?php _e('The connection with the Osclass market has failed. Try it later.'); ?></p>
    <?php
    }
    ?>
</div>
<div class="grid-market">
    <h2 class="section-title"><?php _e('Recommended themes for You'); ?> <a href="<?php echo osc_admin_base_url(true).'?page=market&action=themes'; ?>"><?php echo sprintf(__('View all (%s)'), $count['themesTotal']); ?></a></h2>
    <?php
    foreach($aThemes as $item){
        drawMarketItem($item,$colors[array_rand($colors)]);
    }
    if(count($aThemes)==0) {
    ?>
    <p class="flashmessage flashmessage-inline flashmessage-error"><?php _e('The connection with the Osclass market has failed. Try it later.'); ?></p>
    <?php
    }
    ?>
</div>
<?php if(count($aLanguages)>0) { ?>
<div class="grid-market">
    <h2 class="section-title"><?php _e('Languages'); ?> <a href="<?php echo osc_admin_base_url(true).'?page=market&action=languages'; ?>"><?php echo sprintf(__('View all (%s)'), $count['languagesTotal']); ?></a></h2>
    <?php
    foreach($aLanguages as $item){
        drawMarketItem($item,$colors[array_rand($colors)]);
    } ?>
</div>
<?php } ?>
<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>