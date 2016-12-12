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

    $title      = __get('title');
    $section    = __get('section');
    $array      = __get('array');
    $pagination = __get('pagination');

    $sort       = __get('sort');

    $url_premium = __get('url_premium');
    $url_all = __get('url_all');

    $aFeatured = __get('aFeatured');
    $colors = array_merge(gradienColors(),array_merge(gradienColors(),gradienColors()));

    $categories     = __get('market_categories');
    $categories     = isset($categories[$section])?$categories[$section]:array();


if($section=='plugins' || $section=='themes') { ?>
<div class="grid-market">

    <h2 class="section-title"><?php _e('Featured'); ?></h2>

        <?php foreach($aFeatured as $item){
            drawMarketItem($item,$colors[array_rand($colors)]);
        }
        if(count($aFeatured)==0) { ?>
            <p class="flashmessage flashmessage-inline flashmessage-error"><?php _e('The connection with the Osclass market has failed. Try it later.'); ?></p>
        <?php } ?>
</div>
<?php } ?>

<div class="grid-market">
    <h2 class="section-title"><?php echo $title[$section]; ?>, <?php echo $array['total'].' '.$section; ?> <?php _e('and counting'); ?>

    <?php if($section=='plugins' || $section=='themes') { echo $sort;?>
        <a class="btn btn-mini btn-filter <?php if($sort=='premium'){ echo "btn-blue";}?>" id="sort_premium" href="<?php echo $url_premium; ?>"><?php _e('Premium'); ?></a>
        <a class="btn btn-mini btn-filter <?php if($sort=='all'){ echo "btn-blue";}?>" id="sort_all" href="<?php echo $url_all; ?>"><?php _e('All'); ?></a>
    <?php } ?>

    <?php if($section=='plugins' || $section=='themes') { ?>
        <span class="wrapper_market_categories">
            <select id="market_categories">
                <option value="<?php echo $categories['value'] ?>" <?php if(Params::getParam('sCategory')==$categories['value']) {echo 'selected="selected"'; }; ?>><?php echo $categories['label']; ?></option>
                <?php foreach($categories['categories'] as $c) { ?>
                    <option value="<?php echo $c['value'] ?>" <?php if(Params::getParam('sCategory')==$c['value']) {echo 'selected="selected"'; }; ?>>&nbsp;&nbsp;<?php echo $c['label']; ?></option>
                <?php }; ?>
            </select>
        </span>
    <?php }; ?>
    </h2>
    <?php

    // if there are data to be shown
    if(isset($array[$section]) ) {
        if(isset($array['total']) && (int)$array['total']>0) {
            foreach ($array[$section] as $item) {
                drawMarketItem($item, $colors[array_rand($colors)]);
            }
            echo '<div class="clear"></div><div class="has-pagination">' . $pagination . '</div>';
        } else { ?>
            <div>
                <p class="flashmessage flashmessage-inline flashmessage-error"><?php printf(__('There are no %s that matches your search'), $section); ?></p>
            </div>
        <?php }
    } else {
    ?>
    <div>
        <p class="flashmessage flashmessage-inline flashmessage-error"><?php _e('Cannot get information from market.osclass.org, sorry for the inconvenience'); ?></p>
    </div>
    <?php } ?>
</div>
<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>
