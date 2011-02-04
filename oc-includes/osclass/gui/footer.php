<div style="clear: both;"></div>
<?php
$mPages = new Page();
$pages  = $mPages->listAll(false);
$numPages = count($pages);
?>
<?php osc_show_widgets('footer') ; ?>

<div class="footerPages" >
<?php for($i = 0; $i < $numPages; $i++) { $page = $pages[$i]; ?>
	<a title="<?php echo $page['s_title'] ; ?>" href="<?php osc_createPageURL($page, true); ?>"><?php echo $page['s_title']; ?></a> -
<?php } ?>

<a title="<?php _e('Contact form') ; ?>" href="<?php echo osc_base_url() ; ?>/contact.html"><?php _e('Contact form') ; ?></a>

</div>

<div id="footer" class="footer" >
	This web is proudly using the <a title="OSClass' web" href="http://osclass.org">open source classifieds</a> software <strong>OSClass</strong>.
</div>

<?php osc_run_hooks('footer'); ?>

</body>
</html>

