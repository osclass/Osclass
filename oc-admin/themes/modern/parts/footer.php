<?php if ( ! defined('OC_ADMIN')) exit('Direct access is not allowed.'); ?>
            </div></div><div class="clear"></div></div><!-- #grid-system -->
            </div><!-- #content-page -->
            <div class="clear"></div>
            <div id="footer-wrapper">
                <div id="footer">
                    <?php osc_run_hook('admin_content_footer'); ?>
                </div>
            </div>
        </div><!-- #content-render -->
    </div><!-- #content -->
    <?php osc_run_hook('admin_footer'); ?>
    </body>
</html>