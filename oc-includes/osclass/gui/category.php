
<div id="home_header"><div><?php _e('Category'); ?> &#149; <?php echo $category['s_name']; ?></div></div>
<?php if(count($ads) == 0): ?>
	<p><?php _e('There are no results in this category yet.'); ?></p>
<?php else: ?>
<?php foreach($ads as $a): ?>
<div style="display: none;" id="cat_id"><?php echo $a['category_id'] ?></div>
<script>
	$(document).ready(function() {
		// XXX: this is very dirty!
		// this is for "publish your item" correct url 
		var $cat_id = $('#cat_id').html();
		$('#search_post_yours a').attr('href', 'item.php?action=post&catId=' + $cat_id);
	});
</script>
		<div id="search_result_item">
		    <?php if(is_null($a['price'])): ?>
			<div id="search_result_price">
				<?php _e('Price'); ?>: <?php echo $a['price']; ?>
 			</div>
			<?php endif; ?>
			<div id="search_result_title"><a href="<?php echo osc_item_url($a) ; ?>"><?php echo $a['s_title']; ?></a></div>
			<div class="clear"></div>
		</div>
        <div id="search_result_desc">
			<div style="padding: 10px;">
				<?php 				
				$desc = strip_tags($a['description']);
				if(strlen($desc) == NULL) { // XXX: dirty workaround, description must be null if it was empty
					_e("no description.");
				} else {
					echo $desc;
				}
				?>
			</div> asSAsadsadsadas
        </div>

<?php endforeach; ?>
<?php endif; ?>
<div style="margin-top: 10px;">&nbsp;</div>
