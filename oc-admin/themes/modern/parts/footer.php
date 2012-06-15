        	</div></div></div><!-- #grid-system -->
        	</div><!-- #content-page -->
            <div class="clear"></div>
            <div id="footer">
                <div class="float-left">
                    <?php printf(__('Thank you for using <a href="%s" target="_blank">OSClass</a>'), 'http://osclass.org/'); ?> -
                    <a title="<?php _e('Documentation'); ?>" href="http://doc.osclass.org/" target="_blank"><?php _e('Documentation'); ?></a> &middot;
                    <a title="<?php _e('Forums'); ?>" href="http://forums.osclass.org/" target="_blank"><?php _e('Forums'); ?></a>
                </div>
                <div class="float-right">
                    <strong>OSClass <?php echo OSCLASS_VERSION ; ?></strong>
                </div>
                <div class="clear"></div>
                <?php osc_run_hook('admin_footer') ; ?>
            </div>
    	</div><!-- #content-render -->
    </div><!-- #content -->
    <!-- javascript
    ================================================== -->
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