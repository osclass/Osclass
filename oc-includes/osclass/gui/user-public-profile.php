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
    osc_add_hook('header','bender_follow_construct');

    $address = '';
    if(osc_user_address()!='') {
        if(osc_user_city_area()!='') {
            $address = osc_user_address().", ".osc_user_city_area();
        } else {
            $address = osc_user_address();
        }
    } else {
        $address = osc_user_city_area();
    }
    $location_array = array();
    if(trim(osc_user_city()." ".osc_user_zip())!='') {
        $location_array[] = trim(osc_user_city()." ".osc_user_zip());
    }
    if(osc_user_region()!='') {
        $location_array[] = osc_user_region();
    }
    if(osc_user_country()!='') {
        $location_array[] = osc_user_country();
    }
    $location = implode(", ", $location_array);
    unset($location_array);

    osc_enqueue_script('jquery-validate');

    bender_add_body_class('user-public-profile');
    osc_add_hook('after-main','sidebar');
    function sidebar(){
        osc_current_web_theme_path('user-public-sidebar.php');
    }

    osc_current_web_theme_path('header.php');
?>
<div id="item-content">
    <div class="user-card">
        <img src="http://www.gravatar.com/avatar/<?php echo md5( strtolower( trim( osc_user_email() ) ) ); ?>?s=120&d=<?php echo osc_current_web_theme_url('images/user_default.gif') ; ?>" />
        <ul id="user_data">
            <li class="name"><?php echo osc_user_name(); ?></li>
            <?php if( osc_user_website() !== '' ) { ?>
            <li class="website"><a href="<?php echo osc_user_website(); ?>"><?php echo osc_user_website(); ?></a></li>
            <?php } ?>
            <?php if( $address !== '' ) { ?>
            <li class="adress"><?php printf(__('<strong>Address:</strong> %1$s'), $address); ?></li>
            <?php } ?>
            <?php if( $location !== '' ) { ?>
            <li class="location"><?php printf(__('<strong>Location:</strong> %1$s'), $location); ?></li>
            <?php } ?>
        </ul>
    </div>
    <?php if( osc_user_info() !== '' ) { ?>
    <h2><?php _e('User description', 'bender'); ?></h2>
    <?php } ?>
    <?php echo nl2br(osc_user_info()); ?>
    <?php if( osc_count_items() > 0 ) { ?>
    <div class="similar_ads">
        <h2><?php _e('Latest listings', 'bender'); ?></h2>
        <?php osc_current_web_theme_path('loop.php'); ?>
        <div class="paginate"><?php echo osc_pagination_items(); ?></div>
        <div class="clear"></div>
    </div>
    <?php } ?>
</div>
<?php osc_current_web_theme_path('footer.php') ; ?>