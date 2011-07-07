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

    //getting variables for this view
    $numUsers = __get("numUsers") ;
    $numAdmins = __get("numAdmins") ;
    $numItems = __get("numItems") ;
    $numItemsPerCategory = __get("numItemsPerCategory") ;
    $newsList = __get("newsList") ;
    $comments = __get("comments") ;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <div id="update_version" style="display:none;"></div>
        <script type="text/javascript">
            $.extend({
                initDashboard: function(args) {
                    $.isArray(args) ? true : false;
                    $.each(args, function(i, val) {
                        $("#" + val.substr(3)).show();
                        $("#" + val).attr('checked', 'checked');
                    });
                },
                setCookie: function(args) {
                    $.isArray(args) ? true : false;
                    $.cookie.set("osc_admin_main", args, {json: true});
                }
            });

            $(function() {
                if ($.cookie.get("osc_admin_main") == '' || $.cookie.get("osc_admin_main") == null) { 
                    var sections = ['cb_last_items', 'cb_statistics', 'cb_last_comments', 'cb_last_news'];
                    $.initDashboard(sections);
                    $.setCookie(sections);

                } else { // else read it and apply it!
                    var enabled_sections = $.cookie.get("osc_admin_main", true);
                    $.initDashboard(enabled_sections);
                    $.setCookie(enabled_sections);
                }

                // save settings
                $("#button_save").click(function() {
                    var sections = [];
                    $('#checkboxes input:checkbox:checked').each(function() {
                        sections.push($(this).attr('id'));
                    });

                    $.setCookie(sections);
                    $('#main_div').hide();
                });


                $('#button_open').click(function() {
                    $('#main_div').toggle();
                });

                $("#checkboxes input[type='checkbox']").click(function() {
                    var val = $(this).attr('id');
                    $("#" + val.substr(3)).toggle();
                });
            });
        </script>
        
		<div id="content">
			<div id="separator"></div>	
			
			<?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>

            <script type="text/javascript">

                // this must be loaded after backoffice menu is loaded.
                $(function() {
                    // other tweaks
                    $('#sortable_left').sortable({
                        connectWith: ["#sortable_right"], placeholder: 'widget-placeholder', containment: 'body'
                    });
                    $('#sortable_right').sortable({
                        connectWith: ["#sortable_left"], placeholder: 'widget-placeholder', containment: 'body'
                    });
                });
            </script>
            
			<div id="right_column">
			    <div id="content_header" class="content_header">
					<div style="float: left;">
                        <img src="<?php echo osc_current_admin_theme_url('images/home.png') ; ?>" title="" alt=""/>
                    </div>
					<div id="content_header_arrow">&raquo; <?php _e('Dashboard') ; ?></div>
					<div id="button_open"><?php _e('Settings') ; ?></div>
					<div style="clear: both;"></div>
				</div>
				<?php osc_show_flash_message('admin') ; ?>
				
				<!-- settings div -->
				<div id="main_div">
					<form id="checkboxes">
						<div style="margin-bottom: 8px; font-weight: bold;"><?php _e('Which of the following do you want to see on your dashboard') ; ?>:</div>
						<input id="cb_last_items" type="checkbox" />
						<label for="cb_last_items"><?php _e('Latest items') ; ?></label>
						&nbsp;&nbsp;&nbsp;
						<input id="cb_statistics" type="checkbox" />
						<label for="cb_statistics"><?php _e('Statistics') ; ?></label>
						&nbsp;&nbsp;&nbsp;
						<input id="cb_last_comments" type="checkbox" />
						<label for="cb_last_comments"><?php _e('Latest comments') ; ?></label>
						&nbsp;&nbsp;&nbsp;
						<input id="cb_last_news" type="checkbox" />
						<label for="cb_last_news"><?php _e('Latest news from OSClass') ; ?></label>
					</form>
					<br />
					<a href="#" id="button_save"><?php _e('Save') ; ?></a><br />
				</div>

				<!-- sortable divs -->
				<div id="sortable_container"> <!-- sortable divs container -->
					<!-- left side -->
					<div id="sortable_left" class="sortable_div">

						<div id="last_items" class="ui-widget-content ui-corner-all">
							<h3 class="ui-state-default"><?php _e('Items by category') ; ?></h3>
							<div id="last_items_body">
                                <?php if( !empty($numItemsPerCategory) ) {?>
                                    <?php foreach($numItemsPerCategory as $c) {?>
                                        <a href="<?php echo osc_admin_base_url(true); ?>?page=items&catId=<?php echo $c['pk_i_id'];?>"><?php echo $c['s_name'] ; ?></a>
                                        <?php echo "(" . $c['i_num_items'] . "&nbsp;" . ( ( $c['i_num_items'] == 1 ) ? __('Item') : __('Items') ) . ")" ; ?>
                                        <br />
                                        <?php foreach($c['categories'] as $subc) {?>
                                            <?php echo "&nbsp;&nbsp;"; ?>
                                            <a href="<?php echo osc_admin_base_url(true); ?>?page=items&catId=<?php echo $subc['pk_i_id'];?>"><?php echo $subc['s_name'] ; ?></a>
                                            <?php echo "(" . $subc['i_num_items'] . "&nbsp;" . ( ( $subc['i_num_items'] == 1 ) ? __('Item') : __('Items') ) . ")" ; ?>
                                            <br />
                                        <?php }?>
                                    <?php }?>
                                <?php }else {
                                    _e('There aren\'t any uploaded items yet');
                                } ?>
							</div>					
						</div>

						<div id="statistics" class="ui-widget-content ui-corner-all">
							<h3 class="ui-state-default"><?php _e('Statistics'); ?></h3>
							<div id="statistics_body">
								<?php _e('Number of items') ; ?>: <?php echo $numItems; ?><br />
								<?php _e('Number of public users') ; ?>: <?php echo $numUsers; ?><br />
								<?php _e('Number of administrators') ; ?>: <?php echo $numAdmins; ?><br />
							</div>							
						</div>
					</div>

					<!-- right side -->
					<div id="sortable_right" class="sortable_div">

						<div id="last_comments" class="ui-widget-content ui-corner-all">
							<h3 class="ui-state-default"><?php _e('Latest comments') ; ?></h3>
							<div id="statistics_body">
                                <?php if (count($comments) > 0) { ?>
                                    <?php foreach($comments as $c) { ?>
                                        <strong><?php echo $c['s_author_name'] ; ?></strong> <?php _e('Commented on item') ; ?> <i><a title="<?php echo $c['s_body'] ; ?>" target='_blank' href='<?php echo osc_base_url(true) . '?page=item&id=' . $c['fk_i_item_id'] ; ?>' id='dt_link'><?php echo $c['s_title'] ; ?></a></i><br />
                                    <?php } ?>
                                <?php } else {
                                    _e('There aren\'t any comments yet');
                                } ?>
							</div>
						</div>

						<div id="last_news" class="ui-widget-content ui-corner-all">
							<h3 class="ui-state-default"><?php _e('Latest news from OSClass') ; ?></h3>
							<div id="last_news_body">
							<?php
                                if(is_array($newsList)) {
                            ?>
                                    <ul>
                            <?php
                                    $total = 7 ;
                                    foreach ($newsList as $list) {
                                        $new = (strtotime($list['pubDate']) > strtotime('-1 week') ? true : false);
                            ?>
                                        <li>
                                            <a href="<?php echo $list['link'] ; ?>" target="_blank">
                                                <?php echo $list['title'] ; ?>
                                            </a>
                                            <?php if ($new) { ?>
                                            <span style="color:red;font-family: arial; font-size:10px;font-weight:bold;">
                                                <?php _e('new') ; ?>
                                            </span>
                                            <?php } ?>
                                        </li>
                                    <?php } ?>
                                </ul>
                                <?php } else {
                                    _e('Unable to fetch news from OSClass. Please try again later') ;
                                } ?>
							</div>
						</div>
					</div>
					<div style="clear: both;"></div>

                </div> <!-- end of sortable divs -->

            </div> <!-- end of right column -->
            
            <div style="clear: both;"></div>

        </div> <!-- end of container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>