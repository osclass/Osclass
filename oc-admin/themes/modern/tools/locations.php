<?php
    /**
     * OSClass – software for creating and publishing online classified advertising platforms
     *
     * Copyright (C) 2010 OSCLASS
     *
     * This program is free software: you can redistribute it and/or modify it under the terms
     * of the GNU Affero General Public License as published by the Free Software Foundation,
     * either version 3 of the License, or (at your option) any later version.
     *
     * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
     * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
     * See the GNU Affero General Public License for more details.
     *
     * You should have received a copy of the GNU Affero General Public
     * License along with this program. If not, see <http://www.gnu.org/licenses/>.
     */
     
    $all        = Preference::newInstance()->findValueByName('location_todo') ;
    $worktodo   = LocationsTmp::newInstance()->count() ;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
        <script type="text/javascript">
            function reload() {
                window.location = '<?php echo osc_admin_base_url(true).'?page=tools&action=locations'; ?>' ;
            }
            
            function ajax_() {
                $.ajax({
                    type: "POST",
                    url: '<?php echo osc_admin_base_url(true)?>?page=ajax&action=location_stats',
                    dataType: 'json',
                    success: function(data) {
                        if(data.status=='done') {
                        }else{
                            var pending = data.pending;
                            var all = <?php echo $all;?>;
                            var percent = parseInt( ((all-pending)*100) / all );
                            $('span#percent').html(percent);
                            ajax_();
                        }
                    }
                });
            }
            
            $(document).ready(function(){
                if(<?php echo $worktodo;?>> 0) {
                    ajax_();
                }
            });
        </script>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <div id="content">
            <?php osc_current_admin_theme_path( 'include/backoffice_menu.php' ) ; ?>
            <!-- right container -->
            <div class="right">
                <div class="header_title">
                    <h1 class="items"><?php _e('Location stats') ; ?></h1>
                </div>
                <?php osc_show_flash_message('admin') ; ?>
                <div id="locations_stats_form" style="border: 1px solid #ccc; background: #eee; ">
                    <div style="padding: 20px;">
                        <?php if($worktodo > 0) { ?>
                        <p>
                            <span id="percent">0</span> % <?php _e("Complete"); ?>
                        </p>
                        <br/>
                        <?php } ?>
                        <p>
                            <?php _e('You can recalculate your locations stats. This is useful if you upgrade from versions below OSClass 2.4'); ?>.
                        </p>
                        <br/>
                        <form action="<?php echo osc_admin_base_url(true); ?>" method="post">
                            <input type="hidden" name="action" value="locations_post" />
                            <input type="hidden" name="page" value="tools" />

                            <input id="button_save" type="submit" value="<?php _e('Calculate locations stats'); ?>" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>