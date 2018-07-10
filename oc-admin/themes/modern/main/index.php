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

    $numItemsPerCategory = __get('numItemsPerCategory');
    $numItems            = __get('numItems');
    $numUsers            = __get('numUsers');


    $aFeatured            = __get('aFeatured');

    osc_enqueue_script('fancybox');
    osc_enqueue_style('fancybox', osc_assets_url('js/fancybox/jquery.fancybox.css'));
    osc_register_script('market-js', osc_current_admin_theme_js_url('market.js'), array('jquery', 'jquery-ui'));
    osc_enqueue_script('market-js');

    osc_add_hook('admin_header','add_market_jsvariables');

    osc_add_filter('render-wrapper','render_offset');
    function render_offset() {
        return 'row-offset';
    }

    osc_add_filter('admin_body_class','addBodyClass');
    if(!function_exists('addBodyClass')){
        function addBodyClass($array){
            $array[] = 'dashboard';
            return $array;
        }
}

    osc_add_hook('admin_page_header','customPageHeader');
    function customPageHeader() { ?>
        <h1><?php _e('Dashboard'); ?></h1>
    <?php
    }

    function customPageTitle($string) {
        return sprintf(__('Dashboard &raquo; %s'), $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');

    function customHead() {
        $items = __get('item_stats');
        $users = __get('user_stats');
        ?>
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>
        <script type="text/javascript">
            google.load('visualization', '1', {'packages':['corechart']});
            google.setOnLoadCallback(drawChartListing);
            google.setOnLoadCallback(drawChartUser);

            function drawChartListing() {
                var data = new google.visualization.DataTable();
                data.addColumn('string', '<?php _e('Date'); ?>');
                data.addColumn('number', '<?php _e('Listings'); ?>');
                data.addColumn({type:'boolean',role:'certainty'});
                <?php $k = 0;
                echo "data.addRows(" . count($items) . ");";
                foreach($items as $date => $num) {
                    echo "data.setValue(" . $k . ', 0, "' . $date . '");';
                    echo "data.setValue(" . $k . ", 1, " . $num . ");";
                    $k++;
                }
                $k = 0;
                ?>

                // Instantiate and draw our chart, passing in some options.
                var chart = new google.visualization.AreaChart(document.getElementById('placeholder-listing'));
                chart.draw(data, {
                    colors:['#058dc7','#e6f4fa'],
                        areaOpacity: 0.1,
                        lineWidth:3,
                        hAxis: {
                        gridlines:{
                            color: '#333',
                            count: 3
                        },
                        viewWindow:'explicit',
                        showTextEvery: 2,
                        slantedText: false,
                        textStyle:{
                            color: '#058dc7',
                            fontSize: 10
                        }
                        },
                        vAxis: {
                            gridlines:{
                                color: '#DDD',
                                count: 4,
                                style: 'dooted'
                            },
                            viewWindow:'explicit',
                            baselineColor:'#bababa'

                        },
                        pointSize: 6,
                        legend: 'none',
                        chartArea:{
                            left:10,
                            top:10,
                            width:"95%",
                            height:"88%"
                        }
                    });
            }

            function drawChartUser() {
                var data = new google.visualization.DataTable();
                data.addColumn('string', '<?php _e('Date'); ?>');
                data.addColumn('number', '<?php _e('Users'); ?>');
                data.addColumn({type:'boolean',role:'certainty'});
                <?php $k = 0;
                echo "data.addRows(" . count($users) . ");";
                foreach($users as $date => $num) {
                    echo "data.setValue(" . $k . ', 0, "' . $date . '");';
                    echo "data.setValue(" . $k . ", 1, " . $num . ");";
                    $k++;
                }
                $k = 0;
                ?>

                // Instantiate and draw our chart, passing in some options.
                var chart = new google.visualization.AreaChart(document.getElementById('placeholder-user'));
                chart.draw(data, {
                    colors:['#058dc7','#e6f4fa'],
                    areaOpacity: 0.1,
                    lineWidth:3,
                    hAxis: {
                    gridlines:{
                        color: '#333',
                        count: 3
                    },
                    viewWindow:'explicit',
                    showTextEvery: 2,
                    slantedText: false,
                    textStyle:{
                        color: '#058dc7',
                        fontSize: 10
                    }
                    },
                    vAxis: {
                        gridlines:{
                            color: '#DDD',
                            count: 4,
                            style: 'dooted'
                        },
                        viewWindow:'explicit',
                        baselineColor:'#bababa'

                    },
                    pointSize: 6,
                    legend: 'none',
                    chartArea:{
                        left:10,
                        top:10,
                        width:"95%",
                        height:"88%"
                    }
                });
            }

            $(document).ready(function() {
                $("#widget-box-stats-select").bind('change', function () {
                    if( $(this).val() == 'users' ) {
                        $('#widget-box-stats-listings').css('visibility', 'hidden');
                        $('#widget-box-stats-users').css('visibility', 'visible');
                    } else {
                        $('#widget-box-stats-users').css('visibility', 'hidden');
                        $('#widget-box-stats-listings').css('visibility', 'visible');
                    }
                });
            });
        </script>
<?php
    }
    osc_add_hook('admin_header', 'customHead', 10);

    osc_current_admin_theme_path( 'parts/header.php' ); ?>
<div id="dashboard">
<div class="grid-system">
    <div class="grid-row grid-first-row grid-50">
        <div class="row-wrapper">
            <div class="widget-box">
                <div class="widget-box-title"><h3><?php _e('Listings by category'); ?></h3></div>
                <div class="widget-box-content">
                    <?php
                    $countEvent = 1;
                    if( !empty($numItemsPerCategory) ) { ?>
                    <table class="table" cellpadding="0" cellspacing="0">
                        <tbody>
                        <?php
                        $even = false;
                        foreach($numItemsPerCategory as $c) {?>
                            <tr<?php if($even == true){ $even = false; echo ' class="even"'; } else { $even = true; } if($countEvent == 1){ echo ' class="table-first-row"';} ?>>
                                <td><a href="<?php echo osc_admin_base_url(true); ?>?page=items&amp;catId=<?php echo $c['pk_i_id']; ?>"><?php echo $c['s_name']; ?></a></td>
                                <td><?php echo $c['i_num_items'] . "&nbsp;" . ( ( $c['i_num_items'] == 1 ) ? __('Listing') : __('Listings') ); ?></td>
                            </tr>
                            <?php foreach($c['categories'] as $subc) {?>
                                <tr<?php if($even == true){ $even = false; echo ' class="even"'; } else { $even = true; } ?>>
                                    <td class="children-cat"><a href="<?php echo osc_admin_base_url(true); ?>?page=items&amp;catId=<?php echo $subc['pk_i_id'];?>"><?php echo $subc['s_name']; ?></a></td>
                                    <td><?php echo $subc['i_num_items'] . " " . ( ( $subc['i_num_items'] == 1 ) ? __('Listing') : __('Listings') ); ?></td>
                                </tr>
                            <?php
                            $countEvent++;
                            }
                            ?>
                        <?php
                        $countEvent++;
                        }
                        ?>
                        </tbody>
                    </table>
                    <?php } else { ?>
                        <?php _e("There aren't any uploaded listing yet"); ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="grid-row grid-50">
        <div class="row-wrapper">
            <div class="widget-box">
                <div class="widget-box-title"><h3><?php _e('Statistics'); ?> <select id="widget-box-stats-select" class="widget-box-selector select-box-big input-medium"><option value="listing"><?php _e('New listings'); ?></option><option value="users"><?php _e('New users'); ?></option></select></h3></div>
                <div class="widget-box-content widget-box-content-stats" style="overflow-y: visible;">
                    <div id="widget-box-stats-listings" class="widget-box-stats">
                        <b class="stats-title"><?php _e('New listings'); ?></b>
                        <div class="stats-detail"><?php printf(__('Total number of listings: %s'), $numItems); ?></div>
                        <div id="placeholder-listing" class="graph-placeholder"></div>
                        <a href="<?php echo osc_admin_base_url(true); ?>?page=stats&amp;action=items" class="btn"><?php _e('Listing statistics'); ?></a>
                    </div>
                    <div id="widget-box-stats-users" class="widget-box-stats" style="visibility: hidden;">
                        <b class="stats-title"><?php _e('New users'); ?></b>
                        <div class="stats-detail"><?php printf(__('Total number of users: %s'), $numUsers); ?></div>
                        <div id="placeholder-user" class="graph-placeholder"></div>
                        <a href="<?php echo osc_admin_base_url(true); ?>?page=stats&amp;action=users" class="btn"><?php _e('User statistics'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .mk-item {
            width: 90%;
            margin:5%;
            margin-top: 3%;
        }
        .mk-item-plugin {
            height: 225px;
        }
        .mk-item .mk-info {
            width:auto;
        }
        .mk-item-plugin .banner ,
        .mk-item-theme .banner {
            width: 90%;
            height: 155px;
            border-radius: 5px 5px 0 0;
            -webkit-border-radius: 5px 5px 0 0;
            -moz-border-radius: 5px 5px 0 0;
        }

        .mk-item-plugin .mk-info {
            height: 40px;
            padding: 170px 15px 15px;
        }

    </style>
    <div class="grid-row grid-first-row grid-50">
        <div class="row-wrapper">
            <div class="widget-box  widget-box-project">
                <div class="widget-box-title"><h3><?php _e('Featured products'); ?></h3></div>
                <div class="widget-box-content widget-box-content-no-wrapp">
                    <?php foreach($aFeatured['themes'] as $p) {
                        drawMarketItem($p);
                    } ?>
                    <?php foreach($aFeatured['plugins'] as $p) {
                        drawMarketItem($p);
                    } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="grid-row grid-50">
        <div class="row-wrapper">
            <div class="widget-box">
                <div class="widget-box-title"><h3><?php _e('Market'); ?></h3></div>
                <div class="widget-box-content widget-box-content-no-wrapp">
                    <div id="banner_market"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
</div>
<script type="text/javascript">
    $(function(){
        $.getJSON(
            '<?php echo osc_admin_base_url(true); ?>?page=ajax&action=dashboardbox_market',
            function(data){
                if(data.error===0) {
                    $('<a href="'+oscEscapeHTML(data.url)+'" target="_blank"><div style="height: 100%; width: 100%; background: url('+oscEscapeHTML(data.banner)+') no-repeat;"></div></a>').insertAfter('#banner_market');
                }else {
                    $('<p style="text-align:center; padding-top:15px;"><?php _e('Has been a problem loading the contents, sorry for the inconvenience'); ?></p>').insertAfter('#banner_market');
                }
            });
        });
</script>

<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>
