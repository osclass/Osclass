<?php
$subCats = Category::newInstance()->findSubcategories($category);
?>
<div id="home_header"><div><?php _e('Category'); ?> &#149; <?php echo $category['s_name']; ?></div></div>

<?php if(count($ads) == 0) { ?>
    <p><?php _e('There are no results in this category yet.'); ?></p>
<?php } else { ?>
    <?php foreach($ads as $a) { ?>
        <div id="search_result_item">
            <div id="search_result_price">
                <?php _e('Price'); ?>: <?php echo osc_formatPrice($a); ?>
            </div>
            <div id="search_result_title"><a href="<?php echo osc_createItemURL($a); ?>"><?php echo $a['s_title']; ?></a></div>
            <div class="clear"></div>
        </div>
        <div id="search_result_desc">
            <div style="padding: 10px;">
                <?php echo strip_tags($a['s_description']); ?>
            </div>
        </div>
    <?php } ?>
<?php } ?>
<div style="margin-top: 10px;">&nbsp;</div>
