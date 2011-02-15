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
<?php
    $item = $this->_get('item') ;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <?php $this->osc_print_head() ; ?>
    </head>
    <body>
        <div class="container">
            <?php $this->osc_print_header() ; ?>
            
            <div class="content item">
                <div id="contact" class="inner">
                    <h2><?php _e('Contact seller'); ?></h2>

                    <form action="<?php echo osc_base_url(); ?>" method="post" onsubmit="return contact();">
                        <fieldset>
                            <label><?php _e('To (seller)'); ?>: <?php echo $item['s_contact_name']; ?></label><br/>
                            <label><?php _e('Item'); ?>: <a href="<?php echo osc_item_url($item); ?>"><?php echo $item['s_title']; ?></a></label><br/>
                            <label for="yourName"><?php _e('Your name'); ?></label> <?php ContactForm::your_name(); ?><br/>
                            <label for="yourEmail"><?php _e('Your email address'); ?></label> <?php ContactForm::your_email(); ?><br />
                            <label for="phoneNumber"><?php _e('Phone number'); ?></label><?php ContactForm::your_phone_number(); ?><br/>
                            <label for="message"><?php _e('Message'); ?></label> <?php ContactForm::your_message(); ?><br />
                            <button type="submit"><?php _e('Send message') ?></button>
                            <input type="hidden" name="action" value="send_friend_post" />
                            <input type="hidden" name="page" value="item" />
                            <input type="hidden" name="id" value="<?php echo $item['pk_i_id']; ?>" />
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>  
        <?php $this->osc_print_footer() ; ?>
    </body>
</html>