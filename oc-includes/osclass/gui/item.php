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
                <?php include("inc.search.php") ; ?>
                <strong class="publish_button"><a href="<?php echo osc_item_post_url_in_category() ; ?>"><?php _e("Publish your ad for free") ; ?></a></strong>
            </div>

            <div class="content item">
                <div id="item_head">
                    <div class="inner">
                        <h1><span class="price"><?php echo osc_item_formated_price() ; ?></span> <strong><?php echo osc_item_title() ; ?></strong></h1>

                        <p id="report">
                            <strong><?php _e('Mark as') ; ?></strong>
                            <span>
                                <a id="item_spam" href="<?php echo osc_item_link_spam() ; ?>"><?php _e('spam') ; ?></a>
                                <a id="item_bad_category" href="<?php echo osc_item_link_bad_category() ; ?>"><?php _e('misclassified') ; ?></a>
                                <a id="item_repeated" href="<?php echo osc_item_link_repeated() ; ?>"><?php _e('duplicated') ; ?></a>
                                <a id="item_expired" href="<?php echo osc_item_link_expired() ; ?>"><?php _e('expired') ; ?></a>
                                <a id="item_offensive" href="<?php echo osc_item_link_offensive() ; ?>"><?php _e('offensive') ; ?></a>
                            </span>
                        </p>
                    </div>
                </div>

                <div id="main">

                    <div id="type_dates">
                        <strong><?php echo osc_item_category() ; ?></strong>
                        <em class="publish"><?php echo date("d/m/Y", strtotime(osc_item_pub_date())) ; ?></em>
                        <em class="update"><?php echo date("d/m/Y", strtotime(osc_item_mod_date())) ; ?></em>
                    </div>

                    <ul id="item_location">
                        <?php if ( osc_item_country() != "" ) { ?><li><?php _e("Country:") ; ?> <strong><?php echo osc_item_country() ; ?></strong></li><?php } ?>
                        <?php if ( osc_item_region() != "" ) { ?><li><?php _e("Region:") ; ?> <strong><?php echo osc_item_region() ; ?></strong></li><?php } ?>
                        <?php if ( osc_item_city() != "" ) { ?><li><?php _e("City:") ; ?> <strong><?php echo osc_item_city() ; ?></strong></li><?php } ?>
                        <?php if ( osc_item_city_area() != "" ) { ?><li><?php _e("City area:") ; ?> <strong><?php echo osc_item_city_area() ; ?></strong></li><?php } ?>
                        <?php if ( osc_item_address() != "" ) { ?><li><?php _e("Address:") ; ?> <strong><?php echo osc_item_address() ; ?></strong></li><?php } ?>
                    </ul>

                    <div id="description">
                        <p><?php echo  osc_item_description() ; ?></p>
                        <p class="contact_button">
                            <strong><a href="#contact"><?php _e('Contact seller') ; ?></a></strong>

                            <strong><a href="<?php echo osc_item_send_friend_url();?>"><?php _e('Share') ; ?></a></strong>
                        </p>
                    </div>

                    <!-- plugins -->
                    <?php osc_run_hook('item_detail', osc_item() ) ; ?>
                    <?php osc_run_hook('location') ; ?>


                    <?php if( osc_comments_enabled() ) { ?>
                        <div id="comments">
                            <h2><?php _e('Comments'); ?></h2>
                            <?php if( osc_count_item_comments() >= 1 ) { ?>
                                <div class="comments_list">
                                    <?php while ( osc_has_item_comments() ) { ?>
                                        <div class="comment">
                                            <h3><strong><?php echo osc_comment_title() ; ?></strong> <em><?php _e("by") ; ?> <?php echo osc_comment_author_name() ; ?>:</em></h3>
                                            <p><?php echo osc_comment_author_email() ; ?></p>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                            <form action="<?php echo osc_base_url(true) ; ?>" method="post">
                            <fieldset>
                                <h3><?php _e('Leave your comment (spam and offensive messages will be removed)') ; ?></h3>
                                <input type="hidden" name="action" value="add_comment" />
                                <input type="hidden" name="page" value="item" />
                                <input type="hidden" name="id" value="<?php echo osc_item_id() ; ?>" />
                                <label for="authorName"><?php _e('Your name') ; ?>:</label> <input type="text" name="authorName" id="authorName" /><br />
                                <label for="authorEmail"><?php _e('Your e-mail') ; ?>:</label> <input type="text" name="authorEmail" id="authorEmail" /><br />
                                <label for="title"><?php _e('Title') ; ?>:</label><br /><input type="text" name="title" id="title" /><br />
                                <label for="body"><?php _e('Comment') ; ?>:</label><br /><textarea name="body" id="body" rows="5" cols="40"></textarea><br />
                                <button type="submit"><?php _e('Send') ; ?></button>
                            </fieldset>
                            </form>
                        </div>
                    <?php } ?>

                    <div id="useful_info">
                        <h2><?php _e('Useful information') ; ?></h2>
                        <ul>
                            <li><?php _e('Avoid scams by acting locally or paying with PayPal'); ?></li>
                            <li><?php _e('Never pay with Western Union, Moneygram or other anonymous payment services'); ?></li>
                            <li><?php _e('Don\'t buy or sell outside of your country. Don\'t accept cashier cheques from outside your country'); ?></li>
                            <li><?php _e('This site is never involved in any transaction, and does not handle payments, shipping, guarantee transactions, provide escrow services, or offer "buyer protection" or "seller certification"') ; ?></li>
                        </ul>
                    </div>
                </div>

                <div id="sidebar">
                    <div id="photos">
                        <?php while ( osc_has_item_resources() ) { ?>
                            <img src="<?php echo osc_resource_url() ; ?>" width="350" />
                        <?php } ?>

                    </div>

                    <div id="contact">
                        <h2><?php _e("Contact publisher") ; ?></h2>
                        <form action="<?php echo osc_base_url(true) ; ?>?page=item" method="post" onsubmit="return validate_contact();">

                            <?php osc_prepare_user_info() ; ?>

                            <fieldset>
                                <h3><?php echo osc_user_name() ; ?></h3>
                                <?php if ( osc_user_phone() != '' ) { ?>
                                    <p class="phone"><?php _e("Tel.: ") ; ?> <?php echo osc_user_phone() ; ?></p>
                                <?php } ?>
                                <label for="yourName"><?php _e('Your name (optional)') ; ?>:</label> <?php ContactForm::your_name(); ?>
                                <label for="yourEmail"><?php _e('Your e-mail address') ; ?>:</label> <?php ContactForm::your_email(); ?>
                                <label for="phoneNumber"><?php _e('Phone number') ; ?>:</label> <?php ContactForm::your_phone_number(); ?>
                                <label for="message"><?php _e('Message') ; ?>:</label> <?php ContactForm::your_message(); ?>
                                <input type="hidden" name="action" value="contact_post" />
                                <input type="hidden" name="id" value="<?php echo osc_item_id() ; ?>" />
                                <button type="submit"><?php _e('Send') ; ?></button>
                            </fieldset>
                        </form>
                    </div>

                    <script type="text/javascript">
                        function validate_contact() {
                            email = $("#yourEmail");

                            var pattern=/^([a-zA-Z0-9_\.-])+@([a-zA-Z0-9_\.-])+\.([a-zA-Z])+([a-zA-Z])+/;
                            var num_error = 0;

                            if(!pattern.test(email.value)){
                                email.css('border', '1px solid red');
                                num_error = num_error + 1;
                            }

                            if(message.val().length < 1) {
                                message.css('border', '1px solid red');
                                num_error = num_error + 1;
                            }

                            if(num_error > 0) {
                                return false;
                            }

                            return true;
                        }
                    </script>

                    <!--
                    VER QUE HACEMOS CON ESTO    
                    <div id="item_contact_seller"><a href="<?php echo WEB_PATH; ?>/item.php?action=contact&amp;id=<?php echo osc_item_id() ; ?>"><?php _e('Contact seller'); ?></a></div>
                    <div id="item_send_friend"><a href="<?php echo WEB_PATH; ?>/item.php?action=send_friend&amp;id=<?php echo osc_item_id() ; ?>"><?php _e('Send to a friend'); ?></a></div>
                    -->
                </div>
                
            </div>

            <?php osc_current_web_theme_path('footer.php') ; ?>

        </div>

        <?php osc_show_flash_message() ; ?>
        
    </body>
</html>
