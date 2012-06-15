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

    $numUsers            = __get('numUsers');
    $numAdmins           = __get('numAdmins');
    $numItems            = __get('numItems');
    $numItemsSpam        = __get('numItemsSpam');
    $numItemsBlock       = __get('numItemsBlock');
    $numItemsInactive    = __get('numItemsInactive');
    $numItemsPerCategory = __get('numItemsPerCategory');
    $newsList            = __get('newsList');
    $comments            = __get('comments');

    osc_add_filter('render-wrapper','render_offset');
    function render_offset() {
        return 'row-offset';
    }

    osc_add_hook('admin_page_header','customPageHeader');
    function customPageHeader() { ?>
        <h1><?php _e('Dashboard') ; ?></h1>
    <?php
    }

    function customPageTitle($string) {
        return sprintf(__('Dashboard &raquo; %s'), $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');

    function customHead() {
    $items        = __get("items") ;
    $reports      = __get("reports") ;
    $max          = __get("max") ;
        ?>
     <script type="text/javascript" src="https://www.google.com/jsapi"></script>
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
                data.addColumn('string', '<?php _e('Date') ; ?>');
                data.addColumn('number', '<?php _e('Items') ; ?>');
                data.addColumn({type:'boolean',role:'certainty'});
                <?php $k = 0 ;
                echo "data.addRows(" . count($items) . ");" ;
                foreach($items as $date => $num) {
                    echo "data.setValue(" . $k . ', 0, "' . $date . '");';
                    echo "data.setValue(" . $k . ", 1, " . $num . ");";
                    $k++ ;
                }
                $k = 0 ;
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
                            height:"88%"
                        }
                    });
            }
        </script>
<?php
    }
    osc_add_hook('admin_header', 'customHead');

    osc_current_admin_theme_path( 'parts/header.php' ); ?>
<div id="dashboard">
<div class="grid-system">
    <div class="grid-row grid-first-row grid-50">
        <div class="row-wrapper">
            <div class="widget-box">
                <div class="widget-box-title"><h3><?php _e('Listings by category') ; ?></h3></div>
                <div class="widget-box-content">
                    <?php
                    $countEvent = 1;
                    if( !empty($numItemsPerCategory) ) { ?>
                    <table class="table" cellpadding="0" cellspacing="0">
                        <tbody>
                        <?php foreach($numItemsPerCategory as $c) { ?>
                            <tr<?php if($countEvent%2 == 0){ echo ' class="even"';} if($countEvent == 1){ echo ' class="table-first-row"';} ?>>
                                <td><a href="<?php echo osc_admin_base_url(true); ?>?page=items&amp;catId=<?php echo $c['pk_i_id'] ; ?>"><?php echo $c['s_name'] ; ?></a></td>
                                <td><?php echo $c['i_num_items'] . "&nbsp;" . ( ( $c['i_num_items'] == 1 ) ? __('Listing') : __('Listings') ); ?></td>
                            </tr>
                            <?php foreach($c['categories'] as $subc) {?>
                                <tr<?php if($countEvent%2 == 0){ echo 'class="even"';} ?>>
                                    <td class="children-cat"><a href="<?php echo osc_admin_base_url(true); ?>?page=items&amp;catId=<?php echo $subc['pk_i_id'];?>"><?php echo $subc['s_name'] ; ?></a></td>
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
                        <?php _e("There aren't any uploaded listing yet") ; ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="grid-row grid-50">
        <div class="row-wrapper">
            <div class="widget-box">
                <div class="widget-box-title"><h3><?php _e('Statistics'); ?> <select class="widget-box-selector select-box-big input-medium"><option><?php _e('New listings'); ?></option><option>New comments</option></select></h3></div>
                <div class="widget-box-content">
                    <b class="stats-title"><?php _e('Number of new listings'); ?></b>
                    <div id="placeholder" class="graph-placeholder"></div>
                    <a href="#" class="btn"><?php _e('Go to the statistics page'); ?></a>
                </div>
            </div>
        </div>
    </div>
    <div class="grid-row grid-first-row grid-50">
        <div class="row-wrapper">
            <div class="widget-box">
                <div class="widget-box-title"><h3><?php _e('Latest comments') ; ?></h3></div>
                <div class="widget-box-content">
                    <?php if (count($comments) > 0) { ?>
                    <ul class="list-latests">
                        <?php foreach($comments as $c) { ?>
                        <li>
                            <strong><?php echo $c['s_author_name'] ; ?></strong> <?php _e('Commented on listing') ; ?> <em><a title="<?php echo $c['s_body'] ; ?>" target='_blank' href='<?php echo osc_base_url(true) . '?page=item&amp;id=' . $c['fk_i_item_id'] ; ?>' id='dt_link'><?php echo $c['s_title'] ; ?></a></em>
                        </li>
                        <?php } ?>
                    </ul>
                    <?php } else { ?>
                        <?php _e("There aren't any comments yet") ; ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="grid-row grid-50">
        <div class="row-wrapper">
            <div class="widget-box">
                <div class="widget-box-title"><h3><?php _e('Latest news from OSClass') ; ?></h3></div>
                <div class="widget-box-content">
                    <?php if( is_array($newsList) ) { ?>
                        <ul class="list-latests">
                        <?php foreach ($newsList as $list) { ?>
                        <?php $new = ( strtotime($list['pubDate']) > strtotime('-1 week') ? true : false ) ; ?>
                            <li>
                                <a href="<?php echo $list['link'] ; ?>" target="_blank"><?php echo $list['title'] ; ?></a>
                                <?php if( $new ) { ?>
                                    <span style="color:red; font-size:10px; font-weight:bold;"><?php _e('new') ; ?></span>
                                <?php } ?>
                            </li>
                        <?php } ?>
                        </ul>
                    <?php } else { ?>
                        <?php _e('Unable to fetch news from OSClass. Please try again later') ; ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
</div>
<?php osc_current_admin_theme_path( 'parts/footer.php' ) ; ?>