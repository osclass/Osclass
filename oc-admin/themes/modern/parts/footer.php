        	</div></div><div class="clear"></div></div><!-- #grid-system -->
        	</div><!-- #content-page -->
            <div class="clear"></div>
            <div id="footer-wrapper">
                <div id="footer">
                    <div class="float-left">
                        <?php printf(__('Thank you for using <a href="%s" target="_blank">OSClass</a>'), 'http://osclass.org/'); ?> -
                        <a title="<?php _e('Documentation'); ?>" href="http://doc.osclass.org/" target="_blank"><?php _e('Documentation'); ?></a> &middot;
                        <a title="<?php _e('Forums'); ?>" href="http://forums.osclass.org/" target="_blank"><?php _e('Forums'); ?></a>
                    </div>
                    <div class="float-right">
                        <strong>OSClass <?php echo preg_replace('|.0$|', '', OSCLASS_VERSION); ?></strong>
                    </div>
                    <a id="ninja" href="" class="ico ico-48 ico-dash-white"></a>
                    <div class="clear"></div>
                    <?php osc_run_hook('admin_footer') ; ?>
                </div>
            </div>
    	</div><!-- #content-render -->
    </div><!-- #content -->
    <form id="donate-form" name="_xclick" action="https://www.paypal.com/in/cgi-bin/webscr" method="post" target="_blank">
       <input type="hidden" name="cmd" value="_donations">
       <input type="hidden" name="business" value="info@osclass.org">
       <input type="hidden" name="item_name" value="OSClass project">
       <input type="hidden" name="return" value="<?php echo osc_admin_base_url(); ?>">
       <input type="hidden" name="currency_code" value="USD">
       <input type="hidden" name="lc" value="US" />
    </form>
    <!-- javascript
    ================================================== -->
    <script>
    var $ninja = $('#ninja');

    $ninja.click(function(){
        jQuery('#donate-form').submit();
        return false;
    });
    </script>
    <!-- Placed at the end of the document so the pages load faster -->
    <?php
    /* 
    Only if is iOS
    <script>
    $('a[href!="#"]').click(function(){
    	    window.open($(this).attr('href'));
    	    return false;
    });
    </script>
    */ ?>
  </body>
</html>