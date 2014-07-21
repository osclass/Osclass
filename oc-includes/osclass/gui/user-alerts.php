<?php
    /*
     *      Osclass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2014 OSCLASS
     *
     *       This program is free software: you can redistribute it and/or
     *     modify it under the terms of the GNU Affero General Public License
     *     as published by the Free Software Foundation, either version 3 of
     *            the License, or (at your option) any later version.
     *
     *     This program is distributed in the hope that it will be useful, but
     *         WITHOUT ANY WARRANTY; without even the implied warranty of
     *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *             GNU Affero General Public License for more details.
     *
     *      You should have received a copy of the GNU Affero General Public
     * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
     */

    // meta tag robots
    osc_add_hook('header','bender_nofollow_construct');

    bender_add_body_class('user user-profile');
    osc_add_hook('before-main','sidebar');
    function sidebar(){
        osc_current_web_theme_path('user-sidebar.php');
    }
    osc_add_filter('meta_title_filter','custom_meta_title');
    function custom_meta_title($data){
        return __('Alerts', 'bender');;
    }
    osc_current_web_theme_path('header.php') ;
    $osc_user = osc_user();
?>
<h1><?php _e('Alerts', 'bender'); ?></h1>
<?php if(osc_count_alerts() == 0) { ?>
    <h3><?php _e('You do not have any alerts yet', 'bender'); ?>.</h3>
<?php } else { ?>
    <?php
    $i = 1;
    while(osc_has_alerts()) { ?>
        <div class="userItem" >
            <div class="title-has-actions">
                <h3><?php _e('Alert', 'bender'); ?> <?php echo $i; ?></h3> <a onclick="javascript:return confirm('<?php echo osc_esc_js(__('This action can\'t be undone. Are you sure you want to continue?', 'benderw')); ?>');" href="<?php echo osc_user_unsubscribe_alert_url(); ?>"><?php _e('Delete this alert', 'bender'); ?></a><div class="clear"></div></div>
            <div>
            <?php osc_current_web_theme_path('loop.php') ; ?>
            <?php if(osc_count_items() == 0) { ?>
                    <br />
                    0 <?php _e('Listings', 'bender'); ?>
            <?php } ?>
            </div>
        </div>
        <br />
    <?php
    $i++;
    }
    ?>
<?php  } ?>
<?php osc_current_web_theme_path('footer.php') ; ?>