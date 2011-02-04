<script>
    $(document).ready(function() {
        $('div.category_body').mouseover(function() {
            $(this).css('border-bottom', '1px solid #F38527');
        });

        $('div.category_body').mouseout(function() {
            $(this).css('border-bottom', '1px solid #eee');
        });

        $('div.category_header').mouseover(function() {
            $(this).css('background-color', '#eee');
        });

        $('div.category_header').mouseout(function() {
            $(this).css('background-color', 'white');
        });
    });
</script>

<div id="home_header">
    <div>
        <?php _e('Categories') ; ?>
    </div>
</div>

<?php osc_show_widgets('categories') ; ?> <?php /* XXX: must be moved in a better place */ ?>

<div>
<?php 

if (count($categories) >= 3) {
	$colnums = 3;
	$width = '310px';
} elseif(count($categories) == 2) {
	$colnums = 2;
	$width = '465px';
} else {
	$colnums = 1;
	$width = '930px';
}

/* sort category multi-dimensional array by amount of sub categories DESC */
/*foreach($categories as $k => $v) {
	$cat[$k] = $v['categories'];
}

array_multisort($cat, SORT_DESC, $categories);*/
/* end of sort trick */

$i = 1;
foreach($categories as $c) {
?>
    <div id="category" style="width: <?php echo $width ?>;">
        <div class="category_header"><a href="<?php osc_createCategoryURL($c, true); ?>"><?php echo $c['s_name']; ?></a> <span style="color: #ccc;">[<?php echo CategoryStats::newInstance()->getNumItems($c); ?>]</span></div>
        <div>
        <?php foreach($c['categories'] as $sc): ?>
            <div class="category_body"><a href="<?php osc_createCategoryURL($sc, true); ?>"><?php echo $sc['s_name']; ?></a> <span style="color: #ccc;">[<?php echo CategoryStats::newInstance()->getNumItems($sc); ?>]</span></div>
        <?php endforeach; ?>
        </div>
    </div>
    <?php if ($i == $colnums) { ?>
    <div style="clear:both;"></div>
    <?php
        $i = 1;
    } else {
        $i++;
    }
}
?>
<div style="clear:both;"></div>
</div>
