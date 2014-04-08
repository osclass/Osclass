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

    $items        = __get("items");
    $max          = __get("max");
    $reports      = __get("reports");
    $max_views    = __get("max_views");
    $latest_items = __get("latest_items");
    
    $alerts       = __get("alerts");
    $max_alerts   = __get("max_alerts");
    $subscribers  = __get("subscribers");
    $max_subs     = __get("max_subs");

    $type         = Params::getParam('type_stat');

    switch($type){
        case 'week':
            $type_stat = __('Last 10 weeks');
            break;
        case 'month':
            $type_stat = __('Last 10 months');
            break;
        default:
            $type_stat = __('Last 10 days');
    }

    osc_add_filter('render-wrapper','render_offset');
    function render_offset(){
        return 'row-offset';
    }

    function addHelp() {
        echo '<p>' . __('Quickly find out how many new listings have been published on your site and how many visits each of the listings gets.') . '</p>';
    }
    osc_add_hook('help_box','addHelp');

    osc_add_hook('admin_page_header','customPageHeader');
    function customPageHeader(){ ?>
        <h1><?php _e('Statistics'); ?>
            <a href="#" class="btn ico ico-32 ico-help float-right"></a>
        </h1>
    <?php
    }

    function customPageTitle($string) {
        return sprintf(__('Listing Statistics &raquo; %s'), $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');

    function customHead() {
        $items        = __get("items");
        $max          = __get("max");
        $reports      = __get("reports");
        $max_views    = __get("max_views");
        $latest_items = __get("latest_items");
        
        $alerts       = __get("alerts");
        $max_alerts   = __get("max_alerts");
        $subscribers  = __get("subscribers");
        $max_subs     = __get("max_subs");

?>
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>
        <?php if( count($items) > 0 ) { ?>
        <script type="text/javascript">
            // Load the Visualization API and the piechart package.
            google.load('visualization', '1', {'packages':['corechart']});

            // Set a callback to run when the Google Visualization API is loaded.
            google.setOnLoadCallback(drawChart);

            // Callback that creates and populates a data table,
            // instantiates the pie chart, passes in the data and
            // draws it.
            function drawChart() {
                /* ITEMS */
                var data = new google.visualization.DataTable();
                var data2 = new google.visualization.DataTable();
                data.addColumn('string', '<?php echo osc_esc_js(__('Date')); ?>');
                data.addColumn('number', '<?php echo osc_esc_js(__('Items')); ?>');
                data2.addColumn('string', '<?php echo osc_esc_js(__('Date')); ?>');
                data2.addColumn('number', '<?php echo osc_esc_js(__('Views')); ?>');
                
                /* ALERTS */
                var data3 = new google.visualization.DataTable();
                var data4 = new google.visualization.DataTable();
                data3.addColumn('string', '<?php echo osc_esc_js(__('Date')); ?>');
                data3.addColumn('number', '<?php echo osc_esc_js(__('Alerts')); ?>');
                data4.addColumn('string', '<?php echo osc_esc_js(__('Date')); ?>');
                data4.addColumn('number', '<?php echo osc_esc_js(__('Subscribers')); ?>');
                
                <?php /*ITEMS */
                $k = 0;
                echo "data.addRows(" . count($items) . ");";
                foreach($items as $date => $num) {
                    echo "data.setValue(" . $k . ', 0, "' . $date . '");';
                    echo "data.setValue(" . $k . ", 1, " . $num . ");";
                    $k++;
                }
                $k = 0;
                echo "data2.addRows(" . count($reports) . ");";
                foreach($reports as $date => $data) {
                    echo "data2.setValue(" . $k . ', 0, "' . $date . '");';
                    echo "data2.setValue(" . $k . ", 1, " . $data['views'] . ");";
                    $k++;
                }

                /* ALERTS */
                $k = 0;
                echo "data3.addRows(" . count($alerts) . ");";
                foreach($alerts as $date => $num) {
                    echo "data3.setValue(" . $k . ', 0, "' . $date . '");';
                    echo "data3.setValue(" . $k . ", 1, " . $num . ");";
                    $k++;
                }
                $k = 0;
                echo "data4.addRows(" . count($subscribers) . ");";
                foreach($subscribers as $date => $num) {
                    echo "data4.setValue(" . $k . ', 0, "' . $date . '");';
                    echo "data4.setValue(" . $k . ", 1, " . $num . ");";
                    $k++;
                }
                ?>

                // Instantiate and draw our chart, passing in some options.
                var chart = new google.visualization.AreaChart(document.getElementById('placeholder'));
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
                            height:"80%"
                        }
                    });

                var chart = new google.visualization.AreaChart(document.getElementById('placeholder_total'));
                chart.draw(data2, {
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
                            height:"80%"
                        }
                    });

                var chart = new google.visualization.AreaChart(document.getElementById('placeholder_alerts'));
                chart.draw(data3, {
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
                            height:"80%"
                        }
                    });

                var chart = new google.visualization.AreaChart(document.getElementById('placeholder_subscribers'));
                chart.draw(data4, {
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
                            height:"80%"
                        }
                    });

            }
        </script>
<?php }
    }
    osc_add_hook('admin_header', 'customHead', 10);
?>
<?php osc_current_admin_theme_path( 'parts/header.php' ); ?>
<div class="grid-system" id="stats-page">
    <div class="grid-row grid-50 no-bottom-margin">
        <div class="row-wrapper">
            <h2 class="render-title"><?php _e('Listing Statistics'); ?></h2>
        </div>
    </div>
    <div class="grid-row grid-50 no-bottom-margin">
        <div class="row-wrapper">
            <a id="monthly" class="btn float-right <?php if($type=='month') echo 'btn-green';?>" href="<?php echo osc_admin_base_url(true); ?>?page=stats&amp;action=items&amp;type_stat=month"><?php _e('Last 10 months'); ?></a>
            <a id="weekly"  class="btn float-right <?php if($type=='week') echo 'btn-green';?>" href="<?php echo osc_admin_base_url(true); ?>?page=stats&amp;action=items&amp;type_stat=week"><?php _e('Last 10 weeks'); ?></a>
            <a id="daily"   class="btn float-right <?php if($type==''||$type=='day') echo 'btn-green';?>" href="<?php echo osc_admin_base_url(true); ?>?page=stats&amp;action=items&amp;type_stat=day"><?php _e('Last 10 days'); ?></a>
        </div>
    </div>
    <div class="grid-row grid-50 clear">
        <div class="row-wrapper">
            <div class="widget-box">
                <div class="widget-box-title">
                    <h3><?php _e('New listing'); ?></h3>
                </div>
                <div class="widget-box-content">
                    <b class="stats-title"><?php _e('Number of new listings'); ?></b>
                    <div id="placeholder" class="graph-placeholder" style="height:150px">
                        <?php if( count($items) == 0 ) {
                            _e("There're no statistics yet");
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="grid-row grid-50">
        <div class="row-wrapper">
            <div class="widget-box">
                <div class="widget-box-title">
                    <h3><?php _e('Listings\' views'); ?></h3>
                </div>
                <div class="widget-box-content">
                    <b class="stats-title"><?php _e("Total number of listings' views"); ?></b>
                    <div id="placeholder_total" class="graph-placeholder" style="height:150px">
                        <?php if( count($reports) == 0 ) {
                            _e("There're no statistics yet");
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
    <div class="grid-row grid-50 clear">
        <div class="row-wrapper">
            <div class="widget-box">
                <div class="widget-box-title">
                    <h3><?php _e('New alerts'); ?></h3>
                </div>
                <div class="widget-box-content">
                    <b class="stats-title"><?php _e('Number of new alerts'); ?></b>
                    <div id="placeholder_alerts" class="graph-placeholder" style="height:150px">
                        <?php if( count($alerts) == 0 ) {
                            _e("There're no statistics yet");
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="grid-row grid-50">
        <div class="row-wrapper">
            <div class="widget-box">
                <div class="widget-box-title">
                    <h3><?php _e('New subscribers'); ?></h3>
                </div>
                <div class="widget-box-content">
                    <b class="stats-title"><?php _e("Number of new subscribers"); ?></b>
                    <div id="placeholder_subscribers" class="graph-placeholder" style="height:150px">
                        <?php if( count($subscribers) == 0 ) {
                            _e("There're no statistics yet");
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>