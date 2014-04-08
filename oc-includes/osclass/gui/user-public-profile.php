<?php
/*
 * Copyright 2014 Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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
    <?php echo osc_user_info(); ?>
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