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
?>
<!-- footer -->
<div id="footer">
    <?php osc_run_hook('admin_footer') ; ?>
    <div id="footer_left">
        <?php _e('Thank you for using'); ?> <a href="http://osclass.org/" target="_blank">OSClass</a> -
        <a title="<?php _e('Documentation'); ?>" href="http://doc.osclass.org/" target="_blank"><?php _e('Documentation') ; ?></a> &middot;
        <a title="<?php _e('Forums'); ?>" href="http://forums.osclass.org/" target="_blank"><?php _e('Forums') ; ?></a> &middot;
        <a title="<?php _e('Feedback') ; ?>" href="http://admin.osclass.org/feedback.php" target="_blank"><?php _e('Feedback') ; ?></a>
    </div>
    <div id="footer_right">OSClass <?php echo OSCLASS_VERSION ; ?></div>
    <div class="clear"></div>
</div>
<!-- /footer -->
<script>
    $(document).ready(function() {

         function checkSize(){
            var $right = $('#content .right');
            var $left = $('#content .left');
            if(!$right.data('height')){
                $right.data('height',$right[0].offsetHeight);
            }
            /**/
            if($left[0].offsetHeight > $right.data('height')){
                $right.animate({'min-height':$left[0].offsetHeight});
            }
        }

        //load current views
        var menus = {};
        jQuery('.oscmenu > li').each(function(){
            menus[$(this).attr('id')] = false;
        });
        //load current views
        var menuStatus = $.cookie.get('menuStatus',true);
        for (var i in menuStatus){
            if(menuStatus[i] == true){
                menus[i] = true;
                jQuery('#'+i+' ul').show();
            }
        }
        checkSize();
        jQuery.cookie.set('menuStatus',menus,{json: true});

        jQuery('.oscmenu h3 a[href="#"]').click(function(){
            var menuStatus = $.cookie.get('menuStatus',true);
            if(jQuery(this).parent().next().is(':visible')){
                menuStatus[$(this).parents('li').attr('id')] = false;
                jQuery(this).parent().next().slideUp('normal',function(){
                    checkSize();
                });
            } else {
                menuStatus[$(this).parents('li').attr('id')] = true;
                jQuery(this).parent().next().slideDown('normal',function(){
                    checkSize();
                });
            }
            jQuery.cookie.set('menuStatus',menuStatus,{json: true});

            return false;
        });
    });
</script>