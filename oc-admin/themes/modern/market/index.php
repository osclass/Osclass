<?php
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
        echo '<p>' . __('Browse and download available Osclass plugins, from a constantly-updated selection. After downloading a plugin, you have to install it and configure it to get it up and running.') . '</p>';
    }
    osc_add_hook('help_box','addHelp');
    osc_current_admin_theme_path('market/header.php');

    $count      = __get('count');
    $aPlugins   = __get('aPlugins');
    $aThemes    = __get('aThemes');
    $aLanguages = __get('aLanguages');

    $colors = array_merge(gradienColors(),array_merge(gradienColors(),gradienColors()));

    $categories     = __get('market_categories');
?>
<div class="grid-market">

</div>
<div class="grid-market">
    <h2 class="section-title"><?php _e('Recommended plugins for You'); ?><a href="<?php echo osc_admin_base_url(true).'?page=market&action=plugins'; ?>"><?php echo sprintf(__('View all (%s)'), $count['pluginsTotal']); ?></a>


        <span class="wrapper_market_categories">
            <select id="market_categories">
                    <option section-data="" value="" ><?php _e('Select a category'); ?></option>
                <?php foreach($categories as $k => $section) { ?>
                    <option section-data="<?php echo $k; ?>" value="<?php echo $section['value'] ?>" ><?php echo $section['label']; ?></option>
                    <?php foreach($section['categories'] as $c) { ?>
                        <option section-data="<?php echo $k; ?>" value="<?php echo $c['value'] ?>" >&nbsp;&nbsp;<?php echo $c['label']; ?></option>
                    <?php }; ?>
                <?php }; ?>
            </select>
        </span>


    </h2>
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