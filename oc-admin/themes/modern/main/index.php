<?php
/*
 *      OSCLass – software for creating and publishing online classified
 *                           advertising platforms
 *
 *                        Copyright (C) 2010 OSCLASS
 *
 *       This program is free software: you can redistribute it and/or
 *     modify it under the terms of the GNU Affero General Public License
 *     as published by the Free Software Foundation, either version 3 of
 *            the License, or (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful, but
 *         WITHOUT ANY WARRANTY; without even the implied warranty of
 *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *             GNU Affero General Public License for more details.
 *
 *      You should have received a copy of the GNU Affero General Public
 * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
?>

<script>
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
		if ($.cookie.get("osc_admin_main") == '' || $.cookie.get("osc_admin_main") == null) { // create cookies if admin is a first timer...
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
			
			<?php include_once $absolute_path . '/include/backoffice_menu.php'; ?>
<script>
	
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
					<div style="float: left;"><img src="<?php echo  $current_theme;?>/images/back_office/home.png" /></div>
					<div id="content_header_arrow">&raquo; <?php echo __('Dashboard'); ?></div>
					<div id="button_open"><?php echo osc_lowerCase( __('Settings') ); ?></div>
					<div style="clear: both;"></div>
				</div>
				<?php osc_showFlashMessages(); ?>
				
				<!-- settings div -->
				<div id="main_div">
					<form id="checkboxes">
						<div style="margin-bottom: 8px; font-weight: bold;"><?php echo __('Which of the following content do you want to see on your dashboard'); ?>:</div>
						<input id="cb_last_items" type="checkbox" />
						<label for="cb_last_items"><?php echo __('Last Items'); ?></label>
						&nbsp;&nbsp;&nbsp;
						<input id="cb_statistics" type="checkbox" />
						<label for="cb_statistics"><?php echo __('Statistics') ?></label>							
						&nbsp;&nbsp;&nbsp;
						<input id="cb_last_comments" type="checkbox" />
						<label for="cb_last_comments"><?php echo __('Last Comments') ?></label>							
						&nbsp;&nbsp;&nbsp;
						<input id="cb_last_news" type="checkbox" />
						<label for="cb_last_news"><?php echo __('Last News from OSClass') ?></label>							
					</form>
					<br />
					<a href="#" id="button_save"><?php echo osc_lowerCase( __('Save') ); ?></a><br />
				</div>

				<!-- sortable divs -->
				<div id="sortable_container"> <!-- sortable divs container -->
					<!-- left side -->
					<div id="sortable_left" class="sortable_div">

						<div id="last_items" class="ui-widget-content ui-corner-all">
							<h3 class="ui-state-default"><?php echo __('Items by Category'); ?></h3>
							<div id="last_items_body">
							<?php foreach($categories as $c) { ?>
								<?php $totalWithItems = 0 ; ?>
								<?php if (isset($numItemsPerCategory[$c['pk_i_id']])) { ?>
    								<a href="items.php?catId=<?php echo $c['pk_i_id']?>"><?php echo $c['s_name']; ?></a>
    								<?php echo "(" . $numItemsPerCategory[$c['pk_i_id']] . "&nbsp;" . ( ( $numItemsPerCategory[$c['pk_i_id']] == 1 ) ? osc_lowerCase('Item') : osc_lowerCase('Items') ) . ")" ;?>
									<br />
									<?php $totalWithItems++ ; ?>
								<?php } //end if ?>
							<?php } //end foreach ?>
							
							<?php if ($totalWithItems == 0) {
								echo __('There aren\'t any uploaded items yet');
							 } ?>
							</div>					
						</div>

						<div id="statistics" class="ui-widget-content ui-corner-all">
							<h3 class="ui-state-default"><?php echo __('Statistics'); ?></h3>
							<div id="statistics_body">
								<?php echo __('Number of items'); ?>: <?php echo $numItems; ?><br />
								<?php echo __('Number of public users'); ?>: <?php echo $numUsers; ?><br />
								<?php echo __('Number of administrators'); ?>: <?php echo $numAdmins; ?><br />
							</div>							
						</div>

					</div>

					<!-- right side -->
					<div id="sortable_right" class="sortable_div">

						<div id="last_comments" class="ui-widget-content ui-corner-all">
							<h3 class="ui-state-default"><?php echo __('Last Comments') ?></h3>
							<div id="statistics_body">
								<?php foreach($comments as $c): ?>
									<strong><?php echo $c['s_author_name']; ?></strong> <?php echo osc_lowerCase( __('Commented') ) ." ". osc_lowerCase( __('on') ) ." ". osc_lowerCase( __('Item') ); ?> <i><a title="<?php echo $c['s_body']; ?>" target='_blank' href='<?php echo WEB_PATH . '/item.php?id=' . $c['fk_i_item_id'] ?>' id='dt_link'><?php echo $c['s_title']; ?></a></i><br />
								<?php endforeach; ?>
							</div>
						</div>

						<div id="last_news" class="ui-widget-content ui-corner-all">
							<h3 class="ui-state-default"><?php echo __('Latest News from OSClass') ?></h3>
							<div id="last_news_body">
							<?php
								$xml = @osc_file_get_contents('http://osclass.org/feed');
								if($xml) {
	 								$xml = simplexml_load_string($xml);
									echo '<ul>';
									foreach($xml->channel->item as $item) {
										printf('<li><a href="%s">%s</a></li>', $item->link, $item->title);
									}
									echo '</ul>';
								} else {
									echo __('Unable to fetch news from') . ' OSClass. ' . __('Please try again later') . '.';
								}
							?>								
							</div>
						</div>
					</div>
					<div style="clear: both;"></div>
				</div> <!-- end of sortable divs -->
			</div> <!-- end of right column -->
