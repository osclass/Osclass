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

            <div class="content user_account">

                <h1><strong><?php _e('User account manager') ; ?></strong></h1>

                <div id="sidebar">

                    <?php echo osc_private_user_menu() ; ?>

                </div>

                <div id="main">

                    <h2><?php _e('Your alerts') ; ?></h2>

                    <?php if(osc_count_alerts() == 0) { ?>
                        <h3><?php _e('You do not have any alerts yet.'); ?></h3>
                    <?php } else { ?>
                        <?php while(osc_has_alerts()) { ?>
                            <div class="userItem" >
                                <div><?php _e('Alert'); ?> | <a onclick="javascript:return confirm('<?php _e('This action can not be undone. Are you sure you want to continue?'); ?>');" href="<?php echo osc_user_unsubscribe_alert_url() ; ?>"><?php _e('Delete this alert') ; ?></a></div>

                                <div style="width: 75%; padding-left: 100px;" >
                                <?php while(osc_has_items()) { ?>
                                    <div class="userItem" >
                                        <div><a href="<?php echo osc_item_url(); ?>"><?php echo osc_item_title() ; ?></a></div>

                                        <div class="userItemData" >
                                        <?php _e('Publication date') ; ?>: <?php echo osc_format_date(osc_item_pub_date()) ; ?><br />
                                        <?php _e('Price') ; ?>: <?php echo osc_format_price(osc_item_price()) ; ?>
                                        </div>
                                    </div>
                                    <br />
                                <?php } ?>
                                </div>
                            </div>
                            <br />
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>

            <?php osc_current_web_theme_path('footer.php') ; ?>

        </div>

        <?php osc_show_flash_message() ; ?>

    </body>

</html>
