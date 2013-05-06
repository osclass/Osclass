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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
    <head>
        <?php osc_current_web_theme_path('head.php'); ?>
        <meta name="robots" content="noindex, nofollow" />
        <meta name="googlebot" content="noindex, nofollow" />
    </head>
    <body>
        <?php osc_current_web_theme_path('header.php'); ?>
        <div class="content user_account">
            <h1>
                <strong><?php _e('User account manager', 'modern'); ?></strong>
            </h1>
            <div id="sidebar">
                <?php echo osc_private_user_menu(); ?>
            </div>
            <div id="main">
                <h2><?php echo sprintf(__('Listings from %s', 'modern') ,osc_logged_user_name()); ?></h2>
                <?php if(osc_count_items() == 0) { ?>
                    <h3><?php _e('No listings have been added yet', 'modern'); ?></h3>
                <?php } else { ?>
                    <?php while(osc_has_items()) { ?>
                        <div class="userItem" >
                            <div>
                                <a href="<?php echo osc_item_url(); ?>"><?php echo osc_item_title(); ?></a>
                            </div>
                            <div class="userItemData" >
                            <?php _e('Publication date', 'modern'); ?>: <?php echo osc_format_date(osc_item_pub_date()); ?><br />
                                    <?php if( osc_price_enabled_at_items() && osc_item_category_price_enabled() ) { _e('Price', 'modern'); ?>: <?php echo osc_format_price(osc_item_price()); } ?>
                                    <p class="options">
                                        <strong><a href="<?php echo osc_item_url(); ?>"><?php _e('View listing', 'modern'); ?></a></strong>
                                        <span>|</span>
                                        <a href="<?php echo osc_item_edit_url(); ?>"><?php _e('Edit', 'modern'); ?></a>
                                        <?php if(osc_item_is_inactive()) {?>
                                        <span>|</span>
                                        <a href="<?php echo osc_item_activate_url();?>" ><?php _e('Activate', 'modern'); ?></a>
                                        <?php } ?>
                                    </p>
                                    <br />
                            </div>
                        </div>
                        <br />
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
        <?php osc_current_web_theme_path('footer.php'); ?>
    </body>
</html>
