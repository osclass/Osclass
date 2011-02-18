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
        <?php $this->osc_print_head() ; ?>
    </head>
    <body>

        <div class="container">

            <?php $this->osc_print_header() ; ?>

            <div class="content user_account">

                <h1><strong><?php _e('User account manager') ; ?></strong></h1>

                <div id="sidebar">

                    <?php echo osc_private_user_menu() ; ?>

                </div>

                <div id="main">
                <h2><?php _e('Your items'); ?> <a href="<?php echo osc_item_post_url($catId) ; ?>">+ <?php _e('Post a new item'); ?></a></h2>

                <?php if(osc_count_items() == 0): ?>
                    <h3><?php _e('You do not have any items yet.'); ?></h3>
                <?php else: ?>
                    <?php while(osc_has_items()) { ?>
                            <div class="item" >
                                    <h3><a href="<?php echo osc_item_url(); ?>"><?php echo osc_item_title(); ?></a></h3>
                                    <p>
                                    <?php _e('Publication date') ; ?>: <?php echo osc_format_date(osc_item_pub_date()) ; ?><br />
                                    <?php _e('Price') ; ?>: <?php echo osc_format_price(osc_item_price()); ?>
                                    </p>

                                    <p class="options">
                                        <strong><a href="<?php osc_base_url(true);?>?page=user&action=editItem&amp;id=<?php echo $i['pk_i_id']; ?>&amp;secret=<?php echo $i['s_secret']; ?>"><?php _e('Edit'); ?></a></strong>
                                        <span>|</span>
                                        <a class="delete" onclick="javascript:return confirm('<?php _e('This action can not be undone. Are you sure you want to continue?'); ?>')" href="user.php?action=deleteItem&amp;id=<?php echo $i['pk_i_id']; ?>&amp;secret=<?php echo $i['s_secret']; ?>"><?php _e('Delete'); ?></a>
                                    </p>
                            </div>
                    <?php }; ?>
                <?php endif; ?>
                </div>

                <?php $this->osc_print_footer() ; ?>

        </div>

        <?php osc_show_flash_message() ; ?>

    </body>

</html>
