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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()) ; ?>">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
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
                data.addColumn('string', '<?php _e('Date') ; ?>');
                data.addColumn('number', '<?php _e('Items') ; ?>');
                data2.addColumn('string', '<?php _e('Date') ; ?>');
                data2.addColumn('number', '<?php _e('Views') ; ?>');
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
                var chart = new google.visualization.LineChart(document.getElementById('placeholder'));
                chart.draw(data, {width: 400, height: 300, vAxis: {maxValue: <?php echo ceil($max * 1.1) ; ?>}});

                var chart2 = new google.visualization.ColumnChart(document.getElementById('placeholder_total'));
                chart2.draw(data2, {width: 400, height: 300, vAxis: {maxValue: <?php echo ceil($max_views * 1.1) ;?>}});
            }
        </script>
        <?php } ?>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <!-- container -->
        <div id="content">
            <?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>
            <!-- right container -->
            <div class="right">
                <div class="header_title">
                    <h1 class="settings"><?php _e('Listings Statistics') ; ?></h1>
                </div>
                <?php osc_show_flash_message('admin') ; ?>
                <!-- items statistics -->
                <div class="statistics">
                <div class="actions-header">
                    <a href="<?php echo osc_admin_base_url(true); ?>?page=stats&amp;action=items&amp;type_stat=day"><?php _e('Last 10 days') ; ?></a>
                    <a href="<?php echo osc_admin_base_url(true); ?>?page=stats&amp;action=items&amp;type_stat=week"><?php _e('Last 10 weeks') ; ?></a>
                    <a href="<?php echo osc_admin_base_url(true); ?>?page=stats&amp;action=items&amp;type_stat=month"><?php _e('Last 10 months') ; ?></a>
                </div>
                    <div class="sortable_div">
                        <div class="float50per">
                        <div class="latest-items ui-dialog ui-corner-all">
                            <h3 class="ui-dialog-titlebar"><?php _e('New listing'); ?></h3>
                            <div class="ui-state-body">
                                <div id="placeholder" style="width:400px;height:300px;margin:0; margin:0 auto; padding-bottom: 45px;">
                                    <?php if( count($items) == 0 ) {
                                        _e("There're no statistics yet") ;
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="float50per">
                        <div class="latest-items ui-dialog ui-corner-all">
                            <h3 class="ui-dialog-titlebar"><?php _e("Total number of listings' views") ; ?></h3>
                            <div class="ui-state-body">
                                <div id="placeholder_total" style="width:400px;height:300px;margin:0; margin:0 auto; padding-bottom: 45px;">
                                    <?php if( count($reports) == 0 ) {
                                        _e("There're no statistics yet") ;
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        </div>


                        <div class="float50per">
                        <div class="latest-items ui-dialog ui-corner-all">
                            <h3 class="ui-dialog-titlebar"><?php _e('Latest listings on the web') ; ?></h3>
                            <div class="ui-state-body">
                                <?php if( count($latest_items) > 0 ) { ?>
                                <table border="0">
                                    <tr>
                                        <th>ID</th>
                                        <th><?php _e('Title') ; ?></th>
                                        <th><?php _e('Author') ; ?></th>
                                        <th><?php _e('Status') ; ?></th>
                                    </tr>
                                    <?php foreach($latest_items as $i) { ?>
                                    <tr>
                                        <td><a href="<?php echo osc_admin_base_url(true); ?>?page=items&amp;action=item_edit&amp;id=<?php echo $i['pk_i_id']; ?>"><?php echo $i['pk_i_id']; ?></a></td>
                                        <td><a href="<?php echo osc_admin_base_url(true); ?>?page=items&amp;action=item_edit&amp;id=<?php echo $i['pk_i_id']; ?>"><?php echo $i['s_title']; ?></a></td>
                                        <td><a href="<?php echo osc_admin_base_url(true); ?>?page=items&amp;action=item_edit&amp;id=<?php echo $i['pk_i_id']; ?>"><?php echo $i['s_contact_email']; ?></a></td>
                                        <td><a href="<?php echo osc_admin_base_url(true); ?>?page=items&amp;action=item_edit&amp;id=<?php echo $i['pk_i_id']; ?>"><?php echo ($i['b_active'] == 1) ? __('Active') : __('Inactive') ; ?></a></td>
                                    </tr>
                                    <?php } ?>
                                </table>
                                <?php } else { ?>
                                    <p><?php _e("There're no statistics yet") ; ?></p>
                                <?php } ?>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /items statistics -->
                <div class="clear"></div>
            </div>
            <!-- /right container -->
        </div>
        <!-- /container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>