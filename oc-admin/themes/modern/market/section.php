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

    $title      = __get('title');
    $section    = __get('section');
    $array      = __get('array');
    $pagination = __get('pagination');

    $sort       = __get('sort');

    $order_download = __get('order_download');
    $order_updated  = __get('order_updated');

    $sort_download  = __get('sort_download');
    $sort_updated   = __get('sort_updated');

?>
<div class="grid-market">
    <h2 class="section-title"><?php echo $title[$section]; ?>, <?php echo $array['total'].' '.$section; ?> <?php _e('and counting'); ?>
    <span style="<?php if($sort=='downloads'){ echo "font-weight: bold;";}?>" class="<?php echo ($order_download=='desc'?'sorting_desc':'sorting_asc') ?>"><a id="sort_download" href="<?php echo $sort_download; ?>"><?php _e('Downloads'); ?> </a></span>  <span style="<?php if($sort=='updated'){ echo "font-weight: bold;";}?>" class="<?php echo ($order_updated=='desc'?'sorting_desc':'sorting_asc') ?>"><a id="sort_updated" href="<?php echo $sort_updated; ?>"><?php _e('Last updates'); ?> </a></span>
    </h2>
    <?php
    // if there are data to be shown
    if(isset($array[$section]) ) {
        $colors = gradienColors();
        foreach($array[$section] as $item) {
            drawMarketItem($item,$colors[array_rand($colors)]);
        }
        echo '<div class="clear"></div><div class="has-pagination">'.$pagination.'</div>';
    } else {
    ?>
    <div>
        <p class="flashmessage flashmessage-inline flashmessage-error"><?php _e('Cannot get information form market.osclass.org, sorry for the inconvenience'); ?></p>
    </div>
    <?php } ?>
</div>
<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>
