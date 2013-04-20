<?php
    /*
     *      Osclass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2012 OSCLASS
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

    osc_get_premiums();
    if(osc_count_premiums() > 0) {
?>
<table border="0" cellspacing="0">
     <tbody>
        <?php $class = "even"; ?>
        <?php while(osc_has_premiums()) { ?>
            <tr class="premium_<?php echo $class; ?>">
                <?php if( osc_images_enabled_at_items() ) { ?>
                 <td class="photo">
                     <?php if(osc_count_premium_resources()) { ?>
                        <a href="<?php echo osc_premium_url(); ?>"><img src="<?php echo osc_resource_thumbnail_url(); ?>" width="75" height="56" title="<?php echo osc_item_title(); ?>" alt="<?php echo osc_item_title(); ?>" /></a>
                    <?php } else { ?>
                        <img src="<?php echo osc_current_web_theme_url('images/no_photo.gif'); ?>" title="" alt="" />
                    <?php } ?>
                 </td>
                 <?php } ?>
                 <td class="text">
                     <h3>
                         <span style="float:left;"><a href="<?php echo osc_premium_url(); ?>"><?php echo osc_premium_title(); ?></a></span><span style="float:right;"><?php _e("Sponsored ad", "modern"); ?></span>
                     </h3>
                     <p style="clear: left;">
                         <strong><?php if( osc_price_enabled_at_items() && osc_item_category_price_enabled() ) { echo osc_premium_formated_price(); ?> - <?php } echo osc_premium_city(); ?> (<?php echo osc_premium_region(); ?>) - <?php echo osc_format_date(osc_premium_pub_date()); ?></strong>
                     </p>
                     <p><?php echo osc_highlight( strip_tags( osc_premium_description() ) ); ?></p>
                 </td>
             </tr>
            <?php $class = ($class == 'even') ? 'odd' : 'even'; ?>
        <?php } ?>
    </tbody>
</table>
<?php } ?>
<table border="0" cellspacing="0">
    <tbody>
        <?php $class = "even"; ?>
        <?php while(osc_has_items()) { ?>
            <tr class="<?php echo $class; ?>">
                <?php if( osc_images_enabled_at_items() ) { ?>
                 <td class="photo">
                     <?php if(osc_count_item_resources()) { ?>
                        <a href="<?php echo osc_item_url(); ?>"><img src="<?php echo osc_resource_thumbnail_url(); ?>" width="75" height="56" title="<?php echo osc_item_title(); ?>" alt="<?php echo osc_item_title(); ?>" /></a>
                    <?php } else { ?>
                        <img src="<?php echo osc_current_web_theme_url('images/no_photo.gif'); ?>" title="" alt="" />
                    <?php } ?>
                 </td>
                 <?php } ?>
                 <td class="text">
                     <h3>
                         <a href="<?php echo osc_item_url(); ?>"><?php echo osc_item_title(); ?></a>
                     </h3>
                     <p>
                         <strong><?php if( osc_price_enabled_at_items() && osc_item_category_price_enabled() ) { echo osc_item_formated_price(); ?> - <?php } echo osc_item_city(); ?> (<?php echo osc_item_region(); ?>) - <?php echo osc_format_date(osc_item_pub_date()); ?></strong>
                     </p>
                     <p><?php echo osc_highlight( strip_tags( osc_item_description() ) ); ?></p>
                 </td>
             </tr>
            <?php $class = ($class == 'even') ? 'odd' : 'even'; ?>
        <?php } ?>
    </tbody>
</table>
