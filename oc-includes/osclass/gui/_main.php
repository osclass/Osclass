

<?php
$latestItems = Item::newInstance()->listLatest(10);
?>
<br />

<div class="homeSearch" >
<script type="text/javascript">
function doSearch() { document.location = '<?php echo osc_base_url() ; ?>/search.php?pattern=' + encodeURIComponent(document.getElementById('searchPattern').value); }
</script>
<input onkeyup="if(event.keyCode == 13) doSearch();" type="text" name="pattern" id="searchPattern" /> <input type="button" value="<?php _e('Search'); ?>" onclick="doSearch();" /> or <a href="<?php echo osc_item_post_url(); ?>"><?php _e('Publish your item'); ?></a>
</div>

<?php osc_show_widgets('categories') ; ?>

<div>

<div class="homeCategories" >
	<h2><?php _e('Categories'); ?></h2>

<?php foreach($categories as $c): ?>
	<div class="Category">
	<div class="CategoryHead"><a href="<?php osc_search_category_url($c, true); ?>"><?php echo $c['s_name']; ?></a> (<?php printf(__('%d items'), CategoryStats::newInstance()->getNumItems($c)); ?>)</div>
		<div>
		<?php foreach($c['categories'] as $sc) { ?>
			<a href="<?php osc_search_category_url($sc, true); ?>"><?php echo $sc['s_name']; ?></a><br />
		<?php } ?>
		</div>
	</div>
<?php endforeach; ?>
	<div style="clear: both;"></div>
</div>

<div class="homeLatestItems" >
	<h3><?php _e('Latest published items'); ?></h3>

	<?php foreach($latestItems as $item): ?>
		<a href="<?php osc_create_item_url($item, true); ?>"><?php echo $item['s_title']; ?></a> <span class="homeLastestItemsList" >(<?php echo osc_formatDate($item); ?>)</span><br />
	<?php endforeach; ?>
</div>

<div style="clear: both;"></div>

</div>