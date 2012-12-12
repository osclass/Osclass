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

        default:
            $section = false;
            break;
    }
    $title = array(
        'plugins' => __('Recommended plugins for You'),
        'themes'  => __('Recommended themes for You')
        );
?>
<div class="grid-market">
    <h2 class="section-title"><?php echo $title[$section]; ?></h2>
    <?php

    $marketPage = Params::getParam("mPage");
                    if($marketPage>=1) $marketPage-- ;

    $out    = osc_file_get_contents(osc_market_url($section)."page/".$marketPage);
    $array  = json_decode($out, true);


    $pageActual = $array['page'];
    $totalPages = ceil( $array['total'] / $array['sizePage'] );
    $params     = array(
        'total'    => $totalPages,
        'selected' => $pageActual,
        'url'      => osc_admin_base_url(true).'?page=market'.'&amp;action='.$section.'&amp;mPage={PAGE}',
        'sides'    => 5
    );
    // set pagination
    $pagination = new Pagination($params);
    $aux = $pagination->doPagination();

    foreach($array[$section] as $item){
        drawMarketItem($item);
        $i++;
    }
    echo '<div class="clear"></div><div class="has-pagination">'.$aux.'</div>';
    ?>
</div>
<?php osc_current_admin_theme_path( 'parts/footer.php' ) ; ?>
