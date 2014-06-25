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
    osc_add_hook('header','bender_nofollow_construct');

    bender_add_body_class('user user-items');
    osc_add_hook('before-main','sidebar');
    function sidebar(){
        osc_current_web_theme_path('user-sidebar.php');
    }
    osc_current_web_theme_path('header.php') ;

    $listClass = '';
    $buttonClass = '';
    if(Params::getParam('ShowAs') == 'gallery'){
        $listClass = 'listing-grid';
        $buttonClass = 'active';
    }
?>
<div class="list-header">
    <?php osc_run_hook('search_ads_listing_top'); ?>
    <h1><?php _e('My listings', 'bender'); ?></h1>
    <?php if(osc_count_items() == 0) { ?>
        <p class="empty" ><?php _e('No listings have been added yet', 'bender'); ?></p>
    <?php } else { ?>
        <div class="actions">
            <span class="doublebutton <?php echo $buttonClass; ?>">
                <a href="<?php echo osc_user_list_items_url(); ?>?ShowAs=list" class="list-button" data-class-toggle="listing-grid" data-destination="#listing-card-list"><span>Lista</span></a>
                <a href="<?php echo osc_user_list_items_url(); ?>?ShowAs=gallery" class="grid-button" data-class-toggle="listing-grid" data-destination="#listing-card-list"><span>Grid</span></a>
            </span>
        </div>
    </div>
    <?php
        View::newInstance()->_exportVariableToView("listClass",$listClass);
        View::newInstance()->_exportVariableToView("listAdmin", true);
        osc_current_web_theme_path('loop.php');
    ?>
    <div class="clear"></div>
    <?php
    if(osc_rewrite_enabled()){
        $footerLinks = osc_search_footer_links();
    ?>
        <ul class="footer-links">
            <?php foreach($footerLinks as $f) { View::newInstance()->_exportVariableToView('footer_link', $f); ?>
                <?php if($f['total'] < 3) continue; ?>
                <li><a href="<?php echo osc_footer_link_url(); ?>"><?php echo osc_footer_link_title(); ?></a></li>
            <?php } ?>
        </ul>
    <?php } ?>
    <div class="paginate" >
        <?php echo osc_search_pagination(); ?>
    </div>
<?php } ?>
<?php osc_current_web_theme_path('footer.php') ; ?>
