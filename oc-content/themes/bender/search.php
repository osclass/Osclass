<?php
    /*
     *      Osclass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2013 OSCLASS
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
    if( osc_count_items() == 0 || stripos($_SERVER['REQUEST_URI'], 'search') ) {
        osc_add_hook('header','bender_nofollow_construct');
    } else {
        osc_add_hook('header','bender_follow_construct');
    }

    bender_add_boddy_class('search');
    $listClass = '';
    $buttonClass = '';
    if(osc_search_show_as() == 'gallery'){
          $listClass = 'listing-grid';
          $buttonClass = 'active';
    }
    osc_add_hook('before-main','sidebar');
    function sidebar(){
        osc_current_web_theme_path('search-sidebar.php');
    }
?>
<?php osc_current_web_theme_path('header.php') ; ?>
     <div class="list-header">
        <div class="resp-wrapper">
            <?php osc_run_hook('search_ads_listing_top'); ?>
            <h1><?php echo search_title(); ?></h1>

            <?php if(osc_count_items() == 0) { ?>
                <p class="empty" ><?php printf(__('There are no results matching "%s"', 'bender'), osc_search_pattern()) ; ?></p>
            <?php } else { ?>
            <span class="counter-search"><?php
                $search_number = bender_search_number();
                printf('%1$d - %2$d of %3$d listings', $search_number['from'], $search_number['to'], $search_number['of']);
            ?></span>
            <div class="actions">
              <a href="#" data-bclass-toggle="display-filters" class="resp-toogle show-filters-btn"><?php _e('Show filters','bender'); ?></a>
              <span class="doublebutton <?php echo $buttonClass; ?>">
                   <a href="<?php echo osc_update_search_url(array('sShowAs'=> 'list')); ?>" class="list-button" data-class-toggle="listing-grid" data-destination="#listing-card-list"><span><?php _e('List','bender'); ?></span></a>
                   <a href="<?php echo osc_update_search_url(array('sShowAs'=> 'gallery')); ?>" class="grid-button" data-class-toggle="listing-grid" data-destination="#listing-card-list"><span><?php _e('Grid','bender'); ?></span></a>
              </span>
            </div>
<!--     START sort by       -->
              <p class="see_by">
                <?php _e('Sort by', 'bender'); ?>:
                <?php $i = 0; ?>
                <?php $orders = osc_list_orders();
                foreach($orders as $label => $params) {
                    $orderType = ($params['iOrderType'] == 'asc') ? '0' : '1'; ?>
                    <?php if(osc_search_order() == $params['sOrder'] && osc_search_order_type() == $orderType) { ?>
                        <a class="current" href="<?php echo osc_esc_html(osc_update_search_url($params)); ?>"><?php echo $label; ?></a>
                    <?php } else { ?>
                        <a href="<?php echo osc_esc_html(osc_update_search_url($params)); ?>"><?php echo $label; ?></a>
                    <?php } ?>
                    <?php if ($i != count($orders)-1) { ?>
                        <span>|</span>
                    <?php } ?>
                    <?php $i++; ?>
                <?php } ?>
            </p>
<!--     END sort by       -->
            <?php } ?>
          </div>
     </div>
     <?php if(osc_count_items() > 0) { ?>
     <ul class="listing-card-list <?php echo $listClass; ?>" id="listing-card-list">
          <?php
          $i = 0;
          while(osc_has_items()) { $i++; ?>
                 <?php
                 $class = false;
                 if($i%4 == 0){
                    $class = 'last';
                 }
                 bender_draw_item($class); ?>
          <?php } ?>
     </ul>
     <div class="clear"></div>
      <?php
      if(osc_rewrite_enabled()){
      $footerLinks = osc_search_footer_links(); ?>
      <div id="related-searches">
        <h5><?php _e('Other searches that may interest you','bender'); ?></h5>
        <ul class="footer-links">
          <?php foreach($footerLinks as $f) { View::newInstance()->_exportVariableToView('footer_link', $f); ?>
          <?php if($f['total'] < 3) continue; ?>
            <li><a href="<?php echo osc_footer_link_url(); ?>"><?php echo osc_footer_link_title(); ?></a></li>
          <?php } ?>
        </ul>
      </div>
      <?php } ?>
     <div class="paginate" >
          <?php echo osc_search_pagination(); ?>
     </div>
     <?php } ?>
<?php osc_current_web_theme_path('footer.php') ; ?>