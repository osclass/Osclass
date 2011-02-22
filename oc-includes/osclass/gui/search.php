<?php
    /*
     *      OSCLass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2010 OSCLASS
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <?php osc_current_web_theme_path('head.php') ; ?>
    </head>
    <body>
        <div class="container">
            <?php osc_current_web_theme_path('header.php') ; ?>
            <div id="form_publish">
                <strong class="publish_button margin-bottom"><a href="<?php echo osc_item_post_url( osc_category() ) ; ?>"><?php _e("Publish your ad for free", 'gui');?></a></strong>
            </div>
            <div class="content list">
                <div id="main">
                    <div class="ad_list">
                        <div id="list_head">
                            <div class="inner">
                                <h1>
                                    <strong><?php _e('Search results', 'gui') ; ?></strong>
                                </h1>
                                <p class="see_by">
                                    <?php _e('Sort by', 'gui'); ?>:
                                    <?php $i = 0 ; ?>
                                    <?php $orders = osc_list_orders();
                                    foreach($orders as $label => $params) { ?>
                                        <?php if(osc_search_order() == $params['sOrder'] && osc_search_order_type() == $params['iOrderType']) { ?>
                                            <a class="current" href="<?php echo osc_update_search_url($params) ; ?>"><?php echo $label; ?></a>
                                        <?php } else { ?>
                                            <a href="<?php echo osc_update_search_url($params) ; ?>"><?php echo $label; ?></a>
                                        <?php } ?>
                                        <?php if ($i != count($orders)-1) { ?>
                                            <span>|</span>
                                        <?php } ?>
                                        <?php $i++ ; ?>
                                    <?php } ?>
                                </p>
                            </div>
                        </div>
                        <?php if(osc_count_items() == 0) { ?>
                            <p class="empty" ><?php printf(__('There are no results matching "%s"', 'gui'), osc_search_pattern()) ; ?></p>
                        <?php } else { ?>
                            <?php require(osc_search_show_as() == 'list' ? 'search_list.php' : 'search_gallery.php') ; ?>
                        <?php } ?>
                        <div class="paginate" >
                        <?php for($i = 0 ; $i < osc_search_total_pages() ; $i++) {
                            if($i == osc_search_page()) {
                                printf('<a class="searchPaginationSelected" href="%s">%d</a>', osc_update_search_url(array('iPage' => $i)), ($i + 1));
                            } else {
                                printf('<a class="searchPaginationNonSelected" href="%s">%d</a>', osc_update_search_url(array('iPage' => $i)), ($i + 1));
                            }
                        } ?>
                        </div>
                    </div>
                </div>
                <div id="sidebar">
                    <div class="filters">
                        <form action="<?php echo osc_base_url(true); ?>" method="get">
                            <input type="hidden" name="page" value="search" />
                            <fieldset class="box location">
                                <h3><strong><?php _e('Your search', 'offerum'); ?></strong></h3>
                                <div class="row one_input">
                                    <input type="text" name="sPattern"  id="query" value="<?php echo osc_search_pattern() ; ?>" />
                                </div>
                                <h3><strong><?php _e('Location', 'gui') ; ?></strong></h3>
                                <div class="row one_input">
                                    <h6><?php _e('City', 'gui'); ?></h6>
                                    <input type="text" id="sCity" name="sCity" value="<?php echo osc_search_city() ; ?>" />
                                </div>
                            </fieldset>

                            <fieldset class="box show_only">
                                <h3><strong><?php _e('Show only', 'gui') ; ?></strong></h3>
                                <div class="row checkboxes">
                                    <ul>
                                        <li>
                                            <input type="checkbox" name="bPic" id="withPicture" value="1" <?php echo (osc_search_has_pic() ? 'checked' : ''); ?> />
                                            <label for="withPicture"><?php _e('Show only items with pictures', 'gui') ; ?></label>
                                        </li>
                                    </ul>
                                </div>

                                <div class="row two_input">
                                    <h6><?php _e('Price', 'gui') ; ?></h6>
                                    <?php _e('Min', 'gui') ; ?>.
                                    <input type="text" id="priceMin" name="sPriceMin" value="<?php echo osc_search_price_min() ; ?>" size="6" maxlength="6" />
                                    <?php _e('Max', 'gui') ; ?>.
                                    <input type="text" id="priceMax" name="sPriceMax" value="<?php echo osc_search_price_max() ; ?>" size="6" maxlength="6" />
                                </div>

                                <?php  if ( osc_count_categories() ) { ?>
                                    <div class="row checkboxes">
                                        <h6><?php _e('Category', 'gui') ; ?></h6>
                                        <ul>
                                            <?php // RESET CATEGORIES IF WE USED THEN IN THE HEADER ?>
                                            <?php osc_goto_first_category() ; ?>
                                            <?php while(osc_has_categories()) { ?>
                                                <li>
                                                    <?php if(in_array(osc_category_id(), osc_search_category())) { ?>
                                                        <input onchange="updateFilter();" type="checkbox" name="sCategory[]" checked="checked" value="<?php echo osc_category_id(); ?>" /> <label for="cat<?php echo osc_category_id(); ?>"><strong><?php echo osc_category_name(); ?></strong></label>
                                                    <?php } else { ?>
                                                        <input onchange="updateFilter();" type="checkbox" name="sCategory[]" value="<?php echo osc_category_id(); ?>" /> <label for="cat<?php echo osc_category_id(); ?>"><strong><?php echo osc_category_name(); ?></strong></label>
                                                    <?php } ?>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                <?php } ?>
                            </fieldset>

                            <?php
                                if(osc_search_category() != '') {
                                    osc_run_hook('search_form', osc_search_category()) ;
                                } else {
                                    osc_run_hook('search_form') ;
                                }
                            ?>

                            <button type="submit"><?php _e('Apply', 'gui') ; ?></button>
                        </form>
                        <?php osc_alert_form() ; ?>
                    </div>
                </div>
                <script type="text/javascript">
                    $(function() {
                        function log( message ) {
                            $( "<div/>" ).text( message ).prependTo( "#log" );
                            $( "#log" ).attr( "scrollTop", 0 );
                        }

                        $( "#city" ).autocomplete({
                            source: "<?php echo osc_base_url(true); ?>?page=ajax&action=location",
                            minLength: 2,
                            select: function( event, ui ) {
                                log( ui.item ?
                                    "<?php _e('Selected', 'gui'); ?>: " + ui.item.value + " aka " + ui.item.id :
                                    "<?php _e('Nothing selected, input was', 'gui'); ?> " + this.value );
                            }
                        });
                    });
                </script>
            </div>
            <?php osc_current_web_theme_path('footer.php') ; ?>
        </div>
        <?php osc_show_flash_message() ; ?>
    </body>
</html>