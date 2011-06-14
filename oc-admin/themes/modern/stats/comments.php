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

    $comments = __get("comments");
    $max = __get("max");
    $latest_comments = __get("latest_comments");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <div id="update_version" style="display:none;"></div>
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>
		<div id="content">
			<div id="separator"></div>	
			<?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>
		    <div id="right_column">
			    <div id="content_header" class="content_header">
					<div style="float: left;">
                        <img src="<?php echo osc_current_admin_theme_url('images/settings-icon.png') ; ?>" alt="" title="" />
                    </div>
					<div id="content_header_arrow">&raquo; <?php _e('Item'); ?></div>
					<div style="clear: both;"></div>
				</div>
				<div id="content_separator"></div>
				<?php osc_show_flash_message('admin'); ?>

                <div>
                    <div style="padding: 20px;">
                        <p>
                            <a href="<?php echo osc_admin_base_url(true); ?>?page=stats&action=comments&type_stat=day"><?php _e('Last 10 days'); ?></a>
                            <a href="<?php echo osc_admin_base_url(true); ?>?page=stats&action=comments&type_stat=week"><?php _e('Last 10 weeks'); ?></a>
                            <a href="<?php echo osc_admin_base_url(true); ?>?page=stats&action=comments&type_stat=month"><?php _e('Last 10 months'); ?></a>
                        </p>
                    </div>
                </div>

                <div id="placeholder" style="width:600px;height:300px;margin:0 auto;padding-bottom: 45px;">
                    <?php if(count($comments)==0) {
                        _e('There\'re no statistics yet');
                    }
                    ?>
                </div>


                <br/>

                <div>
                    <h3><?php _e('Latest comments on the web'); ?></h3>
                    <?php if(count($latest_comments)>0) {?>
                    <table border="0">
                        <tr>
                            <th>ID</th>
                            <th><?php _e('Title'); ?></th>
                            <th><?php _e('Author'); ?></th>
                            <th><?php _e('Comment'); ?></th>
                        </tr>
                        <?php foreach($latest_comments as $c) { ?>
                        <tr>
                            <td><a href="<?php echo osc_admin_base_url(true); ?>?page=comments&action=comment_edit&amp;id=<?php echo $c['pk_i_id']; ?>"><?php echo $c['pk_i_id']; ?></a></td>
                            <td><a href="<?php echo osc_admin_base_url(true); ?>?page=comments&action=comment_edit&amp;id=<?php echo $c['pk_i_id']; ?>"><?php echo $c['s_title']; ?></a></td>
                            <td><a href="<?php echo osc_admin_base_url(true); ?>?page=comments&action=comment_edit&amp;id=<?php echo $c['pk_i_id']; ?>"><?php echo $c['s_author_name'] . " - " . $c['s_author_email']; ?></a></td>
                            <td><a href="<?php echo osc_admin_base_url(true); ?>?page=comments&action=comment_edit&amp;id=<?php echo $c['pk_i_id']; ?>"><?php echo $c['s_body']; ?></a></td>
                        </tr>
                        <?php }; ?>
                    </table>
                    <?php } else { ?>
                        <p><?php _e('There\'re no statistics yet'); ?>.</p>
                    <?php }; ?>
                </div>
                <br/>
			</div> <!-- end of right column -->
            <div style="clear: both;"></div>
        </div> <!-- end of container -->

        <?php if(count($comments)>0) { ?>
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
                data.addColumn('string', '<?php _e('Date'); ?>');
                data.addColumn('number', '<?php _e('Comments'); ?>');
                <?php $k = 0;
                echo "data.addRows(".count($comments).");";
                foreach($comments as $date => $num) {
                    echo "data.setValue(".$k.", 0, \"".$date."\");";
                    echo "data.setValue(".$k.", 1, ".$num.");";
                    $k++;
                };
                ?>

                // Instantiate and draw our chart, passing in some options.
                var chart = new google.visualization.LineChart(document.getElementById('placeholder'));
                chart.draw(data, {width: 600, height: 300, vAxis: {maxValue: <?php echo ceil($max*1.1);?>}});
            }
            </script>
        <?php } ?>
        
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>				