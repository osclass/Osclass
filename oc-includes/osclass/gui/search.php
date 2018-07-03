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
    if( osc_count_items() == 0 || stripos($_SERVER['REQUEST_URI'], 'search') ) {
        osc_add_hook('header','bender_nofollow_construct');
    } else {
        osc_add_hook('header','bender_follow_construct');
    }

    bender_add_body_class('search');
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
    osc_add_hook('footer','autocompleteCity');
    function autocompleteCity(){ ?>
    <script type="text/javascript">
    $(function() {
                    function log( message ) {
                        $( "<div/>" ).text( message ).prependTo( "#log" );
                        $( "#log" ).attr( "scrollTop", 0 );
                    }

                    $( "#sCity" ).autocomplete({
                        source: "<?php echo osc_base_url(true); ?>?page=ajax&action=location",
                        minLength: 2,
                        select: function( event, ui ) {
                            $("#sRegion").attr("value", ui.item.region);
                            log( ui.item ?
                                "<?php echo osc_esc_html( __('Selected', 'bender') ); ?>: " + ui.item.value + " aka " + ui.item.id :
                                "<?php echo osc_esc_html( __('Nothing selected, input was', 'bender') ); ?> " + this.value );
                        }
                    });
                });
    </script>
    <?php
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
                printf(__('%1$d - %2$d of %3$d listings', 'bender'), $search_number['from'], $search_number['to'], $search_number['of']);
            ?></span>
            <div class="actions">
              <a href="#" data-bclass-toggle="display-filters" class="resp-toggle show-filters-btn"><?php _e('Show filters','bender'); ?></a>
              <span class="doublebutton <?php echo $buttonClass; ?>">
                   <a href="<?php echo osc_esc_html(osc_update_search_url(array('sShowAs'=> 'list'))); ?>" class="list-button" data-class-toggle="listing-grid" data-destination="#listing-card-list"><span><?php _e('List','bender'); ?></span></a>
                   <a href="<?php echo osc_esc_html(osc_update_search_url(array('sShowAs'=> 'gallery'))); ?>" class="grid-button" data-class-toggle="listing-grid" data-destination="#listing-card-list"><span><?php _e('Grid','bender'); ?></span></a>
              </span>
            <!--     START sort by       -->
            <span class="see_by">
              <span><?php _e('Sort by', 'bender'); ?>:</span>
              <?php
              $orders = osc_list_orders();
              $current = '';
              foreach($orders as $label => $params) {
                  $orderType = ($params['iOrderType'] == 'asc') ? '0' : '1';
                  if(osc_search_order() == $params['sOrder'] && osc_search_order_type() == $orderType) {
                      $current = $label;
                  }
              }
              ?>
              <label><?php echo $current; ?><b class="arrow-envelope"><b class="arrow-down"></b></b></label>
              <?php $i = 0; ?>
              <ul>
                  <?php
                  foreach($orders as $label => $params) {
                      $orderType = ($params['iOrderType'] == 'asc') ? '0' : '1'; ?>
                      <?php if(osc_search_order() == $params['sOrder'] && osc_search_order_type() == $orderType) { ?>
                          <li><a class="current" href="<?php echo osc_esc_html(osc_update_search_url($params)); ?>"><?php echo $label; ?></a></li>
                      <?php } else { ?>
                          <li><a href="<?php echo osc_esc_html(osc_update_search_url($params)); ?>"><?php echo $label; ?></a></li>
                      <?php } ?>
                      <?php $i++; ?>
                  <?php } ?>
                </ul>
            </span>
            <!--     END sort by       -->
            </div>

            <?php } ?>
          </div>
     </div>
        <?php
            $i = 0;
            osc_get_premiums();
            if(osc_count_premiums() > 0) {
            echo '<h5>'.__('Premium listings','bender').'</h5>';
            View::newInstance()->_exportVariableToView("listType", 'premiums');
            View::newInstance()->_exportVariableToView("listClass",$listClass.' premium-list');
            osc_current_web_theme_path('loop.php');
            echo '<div style="clear:both;"></div><br/>';
            }
        ?>
     <?php if(osc_count_items() > 0) {
        echo '<h5>'.__('Listings','bender').'</h5>';
        View::newInstance()->_exportVariableToView("listType", 'items');
        View::newInstance()->_exportVariableToView("listClass",$listClass);
        osc_current_web_theme_path('loop.php');
    ?>

     <div class="clear"></div>
      <?php
      if(osc_rewrite_enabled()){
      $footerLinks = osc_search_footer_links();
      if(count($footerLinks)>0) {
      ?>
      <div id="related-searches">
        <h5><?php _e('Other searches that may interest you','bender'); ?></h5>
        <ul class="footer-links">
          <?php foreach($footerLinks as $f) { View::newInstance()->_exportVariableToView('footer_link', $f); ?>
          <?php if($f['total'] < 3) continue; ?>
            <li><a href="<?php echo osc_footer_link_url(); ?>"><?php echo osc_footer_link_title(); ?></a></li>
          <?php } ?>
        </ul>
      </div>
      <?php }
      } ?>
     <div class="paginate" >
          <?php echo osc_search_pagination(); ?>
     </div>
     <?php } ?>
<?php osc_current_web_theme_path('footer.php') ; ?>