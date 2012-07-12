<?php
    /**
     * OSClass – software for creating and publishing online classified advertising platforms
     *
     * Copyright (C) 2010 OSCLASS
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

    $items        = __get("items") ;
    $max          = __get("max") ;
    $reports      = __get("reports") ;
    $max_views    = __get("max_views") ;
    $latest_items = __get("latest_items") ;
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
        <h1><?php _e('Statistics') ; ?>
            <a href="#" class="btn ico ico-32 ico-help float-right"></a>
        </h1>
    <?php
    }

    function customPageTitle($string) {
        return sprintf(__('Listing Statistics &raquo; %s'), $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');

    function customHead() {
        $items        = __get("items") ;
        $max          = __get("max") ;
        $reports      = __get("reports") ;
        $max_views    = __get("max_views") ;
        $latest_items = __get("latest_items") ;
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
                var data = new google.visualization.DataTable();
                var data2 = new google.visualization.DataTable();
                data.addColumn('string', '<?php echo osc_esc_js(__('Date')); ?>');
                data.addColumn('number', '<?php echo osc_esc_js(__('Items')); ?>');
                data2.addColumn('string', '<?php echo osc_esc_js(__('Date')); ?>');
                data2.addColumn('number', '<?php echo osc_esc_js(__('Views')); ?>');
                <?php $k = 0 ;
                echo "data.addRows(" . count($items) . ");" ;
                foreach($items as $date => $num) {
                    echo "data.setValue(" . $k . ', 0, "' . $date . '");';
                    echo "data.setValue(" . $k . ", 1, " . $num . ");";
                    $k++ ;
                }
                $k = 0 ;
                echo "data2.addRows(" . count($reports) . ");" ;
                foreach($reports as $date => $data) {
                    echo "data2.setValue(" . $k . ', 0, "' . $date . '");' ;
                    echo "data2.setValue(" . $k . ", 1, " . $data['views'] . ");" ;
                    $k++ ;
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
            }
        </script>
<?php }
    }
    osc_add_hook('admin_header', 'customHead');
?>
<?php osc_current_admin_theme_path( 'parts/header.php' ) ; ?>
<div class="grid-system" id="stats-page">
    <div class="grid-row grid-50 no-bottom-margin">
        <div class="row-wrapper">
            <h2 class="render-title"><?php _e('Listing Statistics'); ?></h2>
        </div>
    </div>
    <div class="grid-row grid-50 no-bottom-margin">
        <div class="row-wrapper">
            <a id="monthly" class="btn float-right <?php if($type=='month') echo 'btn-green';?>" href="<?php echo osc_admin_base_url(true); ?>?page=stats&amp;action=items&amp;type_stat=month"><?php _e('Last 10 months') ; ?></a>
            <a id="weekly"  class="btn float-right <?php if($type=='week') echo 'btn-green';?>" href="<?php echo osc_admin_base_url(true); ?>?page=stats&amp;action=items&amp;type_stat=week"><?php _e('Last 10 weeks') ; ?></a>
            <a id="daily"   class="btn float-right <?php if($type==''||$type=='day') echo 'btn-green';?>" href="<?php echo osc_admin_base_url(true); ?>?page=stats&amp;action=items&amp;type_stat=day"><?php _e('Last 10 days') ; ?></a>
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
                            _e("There're no statistics yet") ;
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
                            _e("There're no statistics yet") ;
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="grid-row grid-first-row grid-100">
        <div class="row-wrapper">
            <div class="widget-box">
                <div class="widget-box-title"><h3><?php _e('Latest listings on the web') ; ?></h3></div>
                <div class="widget-box-content">
                    <?php if( count($latest_items) > 0 ) { ?>
                    <table class="table" cellpadding="0" cellspacing="0">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th class="col-title"><?php _e('Title') ; ?></th>
                            <th><?php _e('Author') ; ?></th>
                            <th><?php _e('Status') ; ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($latest_items as $i) { ?>
                        <tr>
                            <td><a href="<?php echo osc_admin_base_url(true); ?>?page=items&amp;action=item_edit&amp;id=<?php echo $i['pk_i_id']; ?>"><?php echo $i['pk_i_id']; ?></a></td>
                            <td><a href="<?php echo osc_admin_base_url(true); ?>?page=items&amp;action=item_edit&amp;id=<?php echo $i['pk_i_id']; ?>"><?php echo $i['s_title']; ?></a></td>
                            <td><a href="<?php echo osc_admin_base_url(true); ?>?page=items&amp;action=item_edit&amp;id=<?php echo $i['pk_i_id']; ?>"><?php echo $i['s_contact_email']; ?></a></td>
                            <td><a href="<?php echo osc_admin_base_url(true); ?>?page=items&amp;action=item_edit&amp;id=<?php echo $i['pk_i_id']; ?>"><?php echo ($i['b_active'] == 1) ? __('Active') : __('Inactive') ; ?></a></td>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <?php } else { ?>
                        <p><?php _e("There are no statistics yet") ; ?></p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
<?php osc_current_admin_theme_path( 'parts/footer.php' ) ; ?>