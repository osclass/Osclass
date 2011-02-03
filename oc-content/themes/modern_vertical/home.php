<?php
$latestItems = Item::newInstance()->listLatest(10);

// for sites that has only one parent category and NO children categories e.g. vertical
if(count($categories) == 1) { 
	$children = Category::newInstance()->isParentOf($categories[0]['pk_i_id']);
	if (!$children) {
		$ads = Item::newInstance()->findByCategoryID($categories[0]['pk_i_id']);
		
		$items_info = array();
		$items_final = array();
		foreach($ads as $a) {
			$item = Item::newInstance()->findByPrimaryKey($a['pk_i_id']);
			$country = Country::newInstance()->findByCode($item['fk_c_country_code']);
			
			// unsetting info that we don't need.
			unset(
				$item['country_id'], 
				$country['id'],
				$country['iso2'],
				$country['iso3']
			);
			
			$items_final = array_merge((array)$item, (array)$country); //XXX: not the best solution
			array_push($items_info, $items_final);
		}					
	} else {
		$ads = array();
	}
}

?>
<script>
	$(document).ready(function() {
		// XXX: this is very dirty!
		// this is for "publish your item" correct url 
		var $cat_id = $('#cat_id').html();
		if($cat_id != null) {
			$('#search_post_yours a').attr('href', 'item.php?action=post&catId=' + $cat_id);			
		} 
	});
</script>


<div>
<?php if(!isset($latestItems) || is_null($latestItems)): ?>
<div id="home_header" class="no_items"><div><?php _e('No Latest Items'); ?></div></div>
<?php else: ?>
<div id="home_header"><div><?php _e('Latest Items'); ?></div></div>
<?php foreach($latestItems as $a): ?>

<div style="display: none;" id="cat_id"><?php echo $a['category_id'] ?></div>

<div id="home_item">
		<div id="home_item_title">
                    <a href="<?php osc_createItemURL($a, true); ?>"><?php echo $a['s_title'] ?></a>
		</div>
		<div id="home_item_loc">
			<?php 
			if($a['s_city']) echo $a['s_city'] . ', ';
			if($a['s_region']) echo $a['s_region'] . ', ';
			if($a['s_contact_name']) echo $a['s_contact_name'] . '. ';
			if($a['s_address']) echo '(' . $a['s_address'] .')';
			?>
		</div>
		<div id="home_item_cn">
			<strong><?php _e('Contact'); ?></strong>:
			<?php 
			if($a['s_contact_name']) {
			?><a href="<?php echo WEB_PATH; ?>/item.php?action=contact&amp;id=<?php echo $a['pk_i_id']; ?>"><?php echo $a['s_contact_name']; ?></a>	
			<?php
			} 
			?>
		</div>
		<div id="home_item_desc">
			<?php if($a['s_description']) echo strip_tags($a['s_description'], '<br />'); ?>
		</div>
</div>
		<!-- <div id="search_result_item">
		    <?php if($item_info['price'] != "0.000"): // XXX: !!! ?>
			<div id="search_result_price">
				<?php _e('Price'); ?>: <?php echo $item_info['price']; ?>
 			</div>
			<?php endif; ?>
			<div id="search_result_title"><a href="<?php osc_createItemURL($a, true); ?>"><?php echo $item_info['title']; ?></a></div>
			<div class="clear"></div>
		</div>
        <div id="search_result_desc">
			<div style="padding: 10px;">
				<?php 				
				$desc = strip_tags($item_info['description']);
				if(strlen($desc) == NULL) { 
					_e("no description.");
				} else {
					echo $desc;
				}
				?>
			</div>
        </div>
		-->	
<?php endforeach; ?>
<?php endif; ?>
</div>
<div style="margin-top: 10px;">&nbsp;</div>
