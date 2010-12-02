<script src="<?php echo WEB_PATH;?>/oc-includes/js/tabber-minimized.js"></script>
<link type="text/css" href="<?php echo WEB_PATH;?>/oc-includes/css/tabs.css" media="screen" rel="stylesheet" />
<div style="width: 100%; border: 0px solid black; margin-top: 20px;">
	<!-- item header + contact button -->
	<div id="user_item">
		<div id="item_title"><?php echo $item['s_title']; ?></div>
		<div id="item_contact_seller"><a href="<?php echo WEB_PATH; ?>/item.php?action=contact&amp;id=<?php echo $item['pk_i_id']; ?>"><?php echo __('Contact seller'); ?></a></div>
                <div id="item_send_friend"><a href="<?php echo WEB_PATH; ?>/item.php?action=send_friend&amp;id=<?php echo $item['pk_i_id']; ?>"><?php echo __('Send to a friend'); ?></a></div>
		<div class="clear"></div>
	</div>
	
	<!-- item description body -->
	<div id="item_desc_body">
		
		<!-- images - left column -->
		<div id="item_images">
			<?php if(count($resources)): ?>
				<!-- ><h3><?php echo __('Images'); ?></h3> -->

				<?php foreach($resources as $r): ?>
					<img src="<?php echo WEB_PATH; ?>/oc-content/uploads/<?php echo $r['pk_i_id']; ?>" style="width: 400px; border: 1px solid #ccc;" /><br />
				<?php endforeach; ?>
			<?php else: ?>
				<div style="width: 400px; height: 200px; border: 1px solid #ccc; padding-top: 180px; text-align: center;"><?php __('no pictures'); ?></div>
			<?php endif; ?>
		</div>
		
		<!-- price and description -->
		<div id="item_desc">
			<!-- price  and options -->
			<?php /* echo __('Price:'); */ ?><div id="item_price"> <?php echo osc_formatPrice($item); ?></div>
			<div class="clear"></div>
			<div id="item_actions">
				<?php echo __('Mark as '); ?> 
				<a id="item_spam" href="<?php echo WEB_PATH; ?>/item.php?action=mark&amp;as=spam&amp;id=<?php echo $item['pk_i_id']; ?>"><?php echo __('spam'); ?></a>,
				<a id="item_bad_category" href="<?php echo WEB_PATH; ?>/item.php?action=mark&amp;as=badcat&amp;id=<?php echo $item['pk_i_id']; ?>"><?php echo __('bad category'); ?></a>,
				<a id="item_repeated" href="<?php echo WEB_PATH; ?>/item.php?action=mark&amp;as=repeated&amp;id=<?php echo $item['pk_i_id']; ?>"><?php echo __('repeated'); ?></a>,
				<a id="item_expired" href="<?php echo WEB_PATH; ?>/item.php?action=mark&amp;as=expired&amp;id=<?php echo $item['pk_i_id']; ?>"><?php echo __('expired'); ?></a>,
				<a id="item_offensive" href="<?php echo WEB_PATH; ?>/item.php?action=mark&amp;as=offensive&amp;id=<?php echo $item['pk_i_id']; ?>"><?php echo __('offensive'); ?></a>.
			</div>
			<!-- desc -->
			<div style="-moz-border-radius: 4px; border: 1px solid white; background-color: #eee; padding: 20px; margin-top: 10px; font-family: Tahoma; font-size: 14px;">
<?php $locales = Locale::newInstance()->listAllEnabled();
if(count($locales)==1) {
$locale = $locales[0];
?>
<p>
<?php echo  @$item['locale'][$locale['pk_c_code']]['s_description']; ?>
</p>

<?php } else { ?>
				<div class="tabber">
<?php foreach($locales as $locale) {?>
<div class="tabbertab">
<h2><?php echo $locale['s_name']; ?></h2>

<p>
<?php echo  @$item['locale'][$locale['pk_c_code']]['s_description']; ?>
</p>
</div>
<?php }; ?>
</div>
<?php }; ?>


			</div>
			
			<!-- plugins -->
			<?php
			osc_runHook('item_detail', $item);
			?>

			<?php osc_runHook('location'); ?>			
		</div>
		<div class="clear"></div>
	</div>
</div>

<form action="<?php echo WEB_PATH; ?>/item.php" method="post">
<input type="hidden" name="action" value="add_comment" />
<input type="hidden" name="id" value="<?php echo $item['pk_i_id']; ?>" />

<div style="border: 1px solid #ccc; -moz-border-radius: 3px; margin-bottom: 20px;">
	<div style="border-bottom: 1px solid orange; font-weight: bold; padding: 5px;"><?php echo __('Comments'); ?></div>

        <?php if(osc_comments_enabled()) : ?>

	<div style="font-size: small; padding: 5px;">
	<?php if(isset($comments) && count($comments)): ?>
	<?php foreach($comments as $c): ?>
	<div><p><i><?php echo $c['s_title']; ?></i> by <i><?php echo $c['s_author_name']; ?></i>:</p><p><?php echo  $c['s_body'] ?></p><hr></div>
	<?php endforeach; ?>
	<?php else: ?>
	<?php echo __('Be the first to comment on this item!'); ?>
	<?php endif; ?>
	</div>
	<div style="border-top: 1px solid #ccc; padding: 15px; background-image: url('<?php echo osc_themeResource('images/pattern.gif'); ?>'); font-size: small;">
	<p><?php echo __('Leave your comment (spam and offensive messages will be removed)'); ?></p>

	<p>
	<label for="authorName"><?php echo __('Your name:'); ?></label> <input type="text" name="authorName" id="authorName" /><br />
	<label for="authorEmail"><?php echo __('Your email:'); ?></label> <input type="text" name="authorEmail" id="authorEmail" /><br />
	</p>

	<p>
	<label for="title"><?php echo __('Title:'); ?></label><br /><input type="text" name="title" id="title" /><br />
	<label for="body"><?php echo __('Comment:'); ?></label><br /><textarea name="body" id="body" rows="3" cols="60"></textarea>
	</p>

	<p><input type="submit" value="<?php echo __('Send comment'); ?>" /></p>
	</div>

        <?php else: ?>
        <div style="font-size: small; padding: 5px;">
        <?php echo __('Comments are not enabled.'); ?>
        </div>
        <?php endif; ?>
</div>

</form>

<div style="border: 1px solid #ccc; -moz-border-radius: 3px; margin-bottom: 20px;">
	<div style="border-bottom: 1px solid green; font-weight: bold; padding: 5px;"><?php echo __('Helpful information'); ?></div>
	<div style="border-top: 1px solid #ccc; padding: 5px; background-image: url('<?php echo osc_themeResource('images/pattern.gif'); ?>'); font-size: small;">
	<ul>
		<li><?php echo __('Avoid scams by dealing locally or paying with PayPal.'); ?></li>
		<li><?php echo __('Never pay with Western Union, Moneygram or other anonymous payment services.'); ?></li>
		<li><?php echo __("Don't buy or sell outside of your country. Don't accept cashier cheques from outside your country."); ?></li>
		<li><?php echo __('This site is never involved in any transaction, and does not handle payments, shipping, guarantee transactions, provide escrow services, or offer "buyer protection" or "seller certification".'); ?></li>
	</ul>
	</div>
</div>
