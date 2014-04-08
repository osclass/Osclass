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
     $category = __get("category");
     if(!isset($category['pk_i_id']) ) {
         $category['pk_i_id'] = null;
     }

?>
<div id="sidebar">
<?php osc_alert_form(); ?>
<div class="filters">
    <form action="<?php echo osc_base_url(true); ?>" method="get" class="nocsrf">
        <input type="hidden" name="page" value="search"/>
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
    <fieldset>
        <div class="row ">
            <h3><?php _e('Refine category', 'bender') ; ?></h3>
            <?php bender_sidebar_category_search($category['pk_i_id']); ?>
        </div>
    </fieldset>
</div>
</div>