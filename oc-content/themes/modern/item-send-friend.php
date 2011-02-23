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
            <div class="content item">
                <div id="contact" class="inner">
                    <h2><?php _e('Send to a friend', 'gui'); ?></h2>
                    <?php SendFriendForm::js_validation(); ?>
                    <form id="send-friend" name="send-friend" action="<?php echo osc_base_url(true); ?>" method="post">
                        <fieldset>
                            <input type="hidden" name="action" value="send_friend_post" />
                            <input type="hidden" name="page" value="item" />
                            <input type="hidden" name="id" value="<?php echo osc_item_id(); ?>" />
                            <label><?php _e('Item', 'gui'); ?>: <a href="<?php echo osc_item_url( ); ?>"><?php echo osc_item_title(); ?></a></label><br/>
                            <label for="yourName"><?php _e('Your name', 'gui'); ?></label> <?php SendFriendForm::your_name(); ?> <br/>
                            <label for="yourEmail"><?php _e('Your e-mail address', 'gui'); ?></label> <?php SendFriendForm::your_email(); ?> <br/>
                            <label for="friendName"><?php _e("Your friend's name", 'gui'); ?></label> <?php SendFriendForm::friend_name(); ?> <br/>
                            <label for="friendEmail"><?php _e("Your friend's e-mail address", 'gui'); ?></label> <?php SendFriendForm::friend_email(); ?> <br/>
                            <label for="message"><?php _e('Message', 'gui'); ?></label> <?php SendFriendForm::your_message(); ?> <br/>
                            <input onclick="return validate_form();" type="button" value="<?php _e('Send', 'gui'); ?>" />
                        </fieldset>
                    </form>
                </div>
            </div>
            <?php osc_current_web_theme_path('footer.php') ; ?>
        </div>
        <?php osc_show_flash_message() ; ?>
    </body>
</html>