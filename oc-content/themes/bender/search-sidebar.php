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
     $category = __get("category");

?>
<div id="sidebar">
<?php osc_alert_form(); ?>
<div class="filters">
    <form action="<?php echo osc_base_url(true); ?>" method="get" onSubmit="return doSearch()" class="nocsrf">
        <input type="hidden" name="page" value="search" />
        <input type="hidden" name="sOrder" value="<?php echo osc_search_order(); ?>" />
        <input type="hidden" name="iOrderType" value="<?php $allowedTypesForSorting = Search::getAllowedTypesForSorting() ; echo $allowedTypesForSorting[osc_search_order_type()]; ?>" />
        <?php foreach(osc_search_user() as $userId) { ?>
        <input type="hidden" name="sUser[]" value="<?php echo $userId; ?>"/>
        <?php } ?>
        <fieldset class="first">
            <h3><?php _e('Your search', 'bender'); ?></h3>
            <div class="row">
                <input class="input-text" type="text" name="sPattern"  id="query" value="<?php echo osc_esc_html(osc_search_pattern()); ?>" />
            </div>
        </fieldset>
        <fieldset>
            <h3><?php _e('City', 'bender'); ?></h3>
            <div class="row">
                <input class="input-text" type="text" id="sCity" name="sCity" value="<?php echo osc_esc_html(osc_search_city()); ?>" />
            </div>
        </fieldset>
        <?php if( osc_images_enabled_at_items() ) { ?>
        <fieldset>
            <h3><?php _e('Show only', 'bender') ; ?></h3>
            <div class="row">
                <input type="checkbox" name="bPic" id="withPicture" value="1" <?php echo (osc_search_has_pic() ? 'checked' : ''); ?> />
                <label for="withPicture"><?php _e('listings with pictures', 'bender') ; ?></label>
            </div>
        </fieldset>
        <?php } ?>
        <?php if( osc_price_enabled_at_items() ) { ?>
        <fieldset>
            <div class="row price-slice">
                <h3><?php _e('Price', 'bender') ; ?></h3>
                <span><?php _e('Min', 'bender') ; ?>.</span>
                <input class="input-text" type="text" id="priceMin" name="sPriceMin" value="<?php echo osc_esc_html(osc_search_price_min()); ?>" size="6" maxlength="6" />
                <span><?php _e('Max', 'bender') ; ?>.</span>
                <input class="input-text" type="text" id="priceMax" name="sPriceMax" value="<?php echo osc_esc_html(osc_search_price_max()); ?>" size="6" maxlength="6" />
            </div>
        </fieldset>
        <?php } ?>
        <div class="plugin-hooks">
            <?php
            if(osc_search_category_id()) {
                osc_run_hook('search_form', osc_search_category_id()) ;
            } else {
                osc_run_hook('search_form') ;
            }
            ?>
        </div>
        <?php
        $aCategories = osc_search_category();
        foreach($aCategories as $cat_id) { ?>
            <input type="hidden" name="sCategory[]" value="<?php echo osc_esc_html($cat_id); ?>"/>
        <?php } ?>
        <div class="actions">
            <button type="submit"><?php _e('Apply', 'bender') ; ?></button>
        </div>
    </form>
    <?php  osc_get_non_empty_categories(); ?>
    <fieldset>
        <?php  if ( osc_count_categories() ) { ?>
        <div class="row ">
            <h3><?php _e('Refine category', 'bender') ; ?></h3>
            <ul class="category">
                <?php // RESET CATEGORIES IF WE USED THEN IN THE HEADER ?>
                <?php osc_goto_first_category() ; ?>
                <?php while(osc_has_categories()) { ?>
                    <?php $parentSelected=false; if (in_array(osc_category_id(), osc_search_category()) || in_array(osc_category_slug()."/", osc_search_category()) || in_array(osc_category_slug(), osc_search_category()) || count(osc_search_category())==0 || (isset($category['fk_i_parent_id']) && $category['fk_i_parent_id'] == osc_category_id())) { $parentSelected=true;} ?>
                    <li class="parent  <?php if($parentSelected && Params::getParam('sCategory') != ''){ echo 'show-sub'; } ?>">
                        <a id="cat_<?php echo osc_esc_html(osc_category_id());?>" href="<?php echo osc_esc_html(osc_update_search_url(array('sCategory'=> osc_category_id()))); ?>"><?php echo osc_category_name(); ?></a>
                        <?php if(osc_count_subcategories() > 0) { ?>
                            <ul class="sub">
                                <?php while(osc_has_subcategories()) { ?>
                                    <li>
                                        <a id="cat_<?php echo osc_esc_html(osc_category_id());?>" href="<?php echo osc_esc_html(osc_update_search_url(array('sCategory'=> osc_esc_html(osc_category_id())))); ?>"><?php if(isset($category['pk_i_id']) && $category['pk_i_id'] == osc_category_id()){ echo '<strong>'.osc_category_name().'</strong>'; } else { echo osc_category_name(); } ?></a>
                                    </li>
                                <?php } ?>
                            </ul>
                        <?php } ?>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <?php } ?>
    </fieldset>
</div>
</div>