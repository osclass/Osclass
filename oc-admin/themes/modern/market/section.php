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
    switch (Params::getParam("action")) {
        case 'plugins':
            $section = 'plugins';
            break;

        case 'themes':
            $section = 'themes';
            break;
        case 'languages':
            $section = 'languages';
            break;

        default:
            $section = false;
            break;
    }
    $title = array(
        'plugins'    => __('Recommended plugins for You'),
        'themes'     => __('Recommended themes for You'),
        'languages'  => __('Languages for this version')
        );

    // page number
    $marketPage     = Params::getParam("mPage");
    $url_actual     = osc_admin_base_url(true) . '?page=market&action='.$section.'&mPage='.$marketPage;
    if($marketPage>=1) $marketPage-- ;

    // api
    $url            = osc_market_url($section)."page/".$marketPage.'/length/9/';
    // default sort
    $sort_actual    = '';
    $sort_download  = $url_actual.'&sort=downloads&order=desc';
    $sort_updated   = $url_actual.'&sort=updated&order=desc';

    // sorting options (default)
    $_order         = 'desc';
    $order_download = $_order;
    $order_updated  = $_order;

    $sort           = Params::getParam("sort");
    $order          = Params::getParam("order");

    if($sort=='') {
        $sort = 'updated';
    }
    if($order=='') {
        $order = $_order;
    }

    $aux = ($order=='desc')?'asc':'desc';

    switch ($sort) {
        case 'downloads':
            $sort_actual    = '&sort=downloads&order=';
            $sort_download  = $url_actual.$sort_actual.$aux;
            $sort_actual   .= $order;
            $order_download = $order;
            // market api call
            $url .= 'order/downloads/'.$order;
        break;
        case 'updated':
            $sort_actual    = '&sort=updated&order=';
            $sort_updated   = $url_actual.$sort_actual.$aux;
            $sort_actual   .= $order;
            $order_updated  = $order;
            // market api call
            $url .= 'order/updated/'.$order;
        break;
        default:
        break;
    }

?>
<div class="grid-market">
    <h2 class="section-title"><?php echo $title[$section]; ?>
    <span style="<?php if($sort=='downloads'){ echo "font-weight: bold;";}?>" class="<?php echo ($order_download=='desc'?'sorting_desc':'sorting_asc') ?>"><a href="<?php echo $sort_download; ?>"><?php _e('Downloads'); ?> </a></span>  <span style="<?php if($sort=='updated'){ echo "font-weight: bold;";}?>" class="<?php echo ($order_updated=='desc'?'sorting_desc':'sorting_asc') ?>"><a href="<?php echo $sort_updated; ?>"><?php _e('Last updates'); ?> </a></span>
    </h2>
    <?php
    // pageSize or length attribute is hardcoded
    $out    = osc_file_get_contents($url);
    $array  = json_decode($out, true);

    $pageActual = $array['page'];
    $totalPages = ceil( $array['total'] / $array['sizePage'] );
    $params     = array(
        'total'    => $totalPages,
        'selected' => $pageActual,
        'url'      => osc_admin_base_url(true).'?page=market'.'&amp;action='.$section.'&amp;mPage={PAGE}'.$sort_actual,
        'sides'    => 5
    );
    // set pagination
    $pagination = new Pagination($params);
    $aux = $pagination->doPagination();

    $colors = array_merge(gradienColors(),array_merge(gradienColors(),gradienColors()));
    $i = 0;
    foreach($array[$section] as $item) {
        drawMarketItem($item,$colors[$i]);
        $i++;
    }
    echo '<div class="clear"></div><div class="has-pagination">'.$aux.'</div>';
    ?>
</div>
<?php osc_current_admin_theme_path( 'parts/footer.php' ) ; ?>
