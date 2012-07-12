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
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
    <head>
        <?php osc_current_web_theme_path('head.php') ; ?>
        <script type="text/javascript" src="<?php echo osc_current_web_theme_js_url('fancybox/jquery.fancybox.js') ; ?>"></script>
        <link href="<?php echo osc_current_web_theme_js_url('fancybox/jquery.fancybox.css') ; ?>" rel="stylesheet" type="text/css" />
        
        <script type="text/javascript">
            $(document).ready(function(){
                $("a[rel=image_group]").fancybox({
                    openEffect : 'none',
                    closeEffect : 'none',
                    nextEffect : 'fade',
                    prevEffect : 'fade',
                    loop : false,
                    helpers : {
                            title : {
                                    type : 'inside'
                            }
                    }
                });
            });
        </script>
        <meta name="robots" content="index, follow" />
        <meta name="googlebot" content="index, follow" />
        <script type="text/javascript" src="<?php echo osc_current_web_theme_js_url('jquery.validate.min.js') ; ?>"></script>
    </head>
    <body>
        <?php osc_current_web_theme_path('header.php') ; ?>
        <div class="content item">
            <div id="item_head">
                <div class="inner">
                    <h1><?php if( osc_price_enabled_at_items() ) { ?><span class="price"><?php echo osc_item_formated_price() ; ?></span> <?php } ?><strong><?php echo osc_item_title() . ' ' . osc_item_city(); ?></strong></h1>
                    <p id="report">
                        <strong><?php _e('Mark as', 'modern') ; ?></strong>
                        <span>
                            <a id="item_spam" href="<?php echo osc_item_link_spam() ; ?>" rel="nofollow"><?php _e('spam', 'modern') ; ?></a>
                            <a id="item_bad_category" href="<?php echo osc_item_link_bad_category() ; ?>" rel="nofollow"><?php _e('misclassified', 'modern') ; ?></a>
                            <a id="item_repeated" href="<?php echo osc_item_link_repeated() ; ?>" rel="nofollow"><?php _e('duplicated', 'modern') ; ?></a>
                            <a id="item_expired" href="<?php echo osc_item_link_expired() ; ?>" rel="nofollow"><?php _e('expired', 'modern') ; ?></a>
                            <a id="item_offensive" href="<?php echo osc_item_link_offensive() ; ?>" rel="nofollow"><?php _e('offensive', 'modern') ; ?></a>
                        </span>
                    </p>
                </div>
            </div>
            <div id="main">
                <div id="type_dates">
                    <strong><?php echo osc_item_category() ; ?></strong>
                    <em class="publish"><?php if ( osc_item_pub_date() != '' ) echo __('Published date', 'modern') . ': ' . osc_format_date( osc_item_pub_date() ) ; ?></em>
                    <em class="update"><?php if ( osc_item_mod_date() != '' ) echo __('Modified date', 'modern') . ': ' . osc_format_date( osc_item_mod_date() ) ; ?></em>
                </div>
                <ul id="item_location">
                    <?php if ( osc_item_country() != "" ) { ?><li><?php _e("Country", 'modern'); ?>: <strong><?php echo osc_item_country() ; ?></strong></li><?php } ?>
                    <?php if ( osc_item_region() != "" ) { ?><li><?php _e("Region", 'modern'); ?>: <strong><?php echo osc_item_region() ; ?></strong></li><?php } ?>
                    <?php if ( osc_item_city() != "" ) { ?><li><?php _e("City", 'modern'); ?>: <strong><?php echo osc_item_city() ; ?></strong></li><?php } ?>
                    <?php if ( osc_item_city_area() != "" ) { ?><li><?php _e("City area", 'modern'); ?>: <strong><?php echo osc_item_city_area() ; ?></strong></li><?php } ?>
                    <?php if ( osc_item_address() != "" ) { ?><li><?php _e("Address", 'modern') ; ?>: <strong><?php echo osc_item_address() ; ?></strong></li><?php } ?>
                </ul>
                <div id="description">
                    <p><?php echo osc_item_description() ; ?></p>
                    <div id="custom_fields">
                        <?php if( osc_count_item_meta() >= 1 ) { ?>
                            <br />
                            <div class="meta_list">
                                <?php while ( osc_has_item_meta() ) { ?>
                                    <?php if(osc_item_meta_value()!='') { ?>
                                        <div class="meta">
                                            <strong><?php echo osc_item_meta_name(); ?>:</strong> <?php echo osc_item_meta_value(); ?>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                    <?php osc_run_hook('item_detail', osc_item() ) ; ?>
                    <p class="contact_button">
                        <?php if( !osc_item_is_expired () ) { ?>
                        <?php if( !( ( osc_logged_user_id() == osc_item_user_id() ) && osc_logged_user_id() != 0 ) ) { ?>
                            <?php     if(osc_reg_user_can_contact() && osc_is_web_user_logged_in() || !osc_reg_user_can_contact() ) { ?>
                                <strong><a href="#contact"><?php _e('Contact seller', 'modern') ; ?></a></strong>
                            <?php     } ?>
                        <?php     } ?>
                        <?php } ?>
                        <strong class="share"><a href="<?php echo osc_item_send_friend_url() ; ?>" rel="nofollow"><?php _e('Share', 'modern') ; ?></a></strong>
                    </p>
                    <?php osc_run_hook('location') ; ?>
                </div>
                <!-- plugins -->
                <div id="useful_info">
                    <h2><?php _e('Useful information', 'modern') ; ?></h2>
                    <ul>
                        <li><?php _e('Avoid scams by acting locally or paying with PayPal', 'modern'); ?></li>
                        <li><?php _e('Never pay with Western Union, Moneygram or other anonymous payment services', 'modern'); ?></li>
                        <li><?php _e('Don\'t buy or sell outside of your country. Don\'t accept cashier cheques from outside your country', 'modern'); ?></li>
                        <li><?php _e('This site is never involved in any transaction, and does not handle payments, shipping, guarantee transactions, provide escrow services, or offer "buyer protection" or "seller certification"', 'modern') ; ?></li>
                    </ul>
                </div>
                <?php if( osc_comments_enabled() ) { ?>
                    <?php if( osc_reg_user_post_comments () && osc_is_web_user_logged_in() || !osc_reg_user_post_comments() ) { ?>
                    <div id="comments">
                        <h2><?php _e('Comments', 'modern'); ?></h2>
                        <ul id="comment_error_list"></ul>
                        <?php CommentForm::js_validation(); ?>
                        <?php if( osc_count_item_comments() >= 1 ) { ?>
                            <div class="comments_list">
                                <?php while ( osc_has_item_comments() ) { ?>
                                    <div class="comment">
                                        <h3><strong><?php echo osc_comment_title() ; ?></strong> <em><?php _e("by", 'modern') ; ?> <?php echo osc_comment_author_name() ; ?>:</em></h3>
                                        <p><?php echo osc_comment_body() ; ?> </p>
                                        <?php if ( osc_comment_user_id() && (osc_comment_user_id() == osc_logged_user_id()) ) { ?>
                                        <p>
                                            <a rel="nofollow" href="<?php echo osc_delete_comment_url(); ?>" title="<?php _e('Delete your comment', 'modern'); ?>"><?php _e('Delete', 'modern'); ?></a>
                                        </p>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                <div class="pagination">
                                    <?php echo osc_comments_pagination(); ?>
                                </div>
                            </div>
                        <?php } ?>
                        <form action="<?php echo osc_base_url(true) ; ?>" method="post" name="comment_form" id="comment_form">
                            <fieldset>
                                <h3><?php _e('Leave your comment (spam and offensive messages will be removed)', 'modern') ; ?></h3>
                                <input type="hidden" name="action" value="add_comment" />
                                <input type="hidden" name="page" value="item" />
                                <input type="hidden" name="id" value="<?php echo osc_item_id() ; ?>" />
                                <?php if(osc_is_web_user_logged_in()) { ?>
                                    <input type="hidden" name="authorName" value="<?php echo osc_esc_html( osc_logged_user_name() ); ?>" />
                                    <input type="hidden" name="authorEmail" value="<?php echo osc_logged_user_email();?>" />
                                <?php } else { ?>
                                    <label for="authorName"><?php _e('Your name', 'modern') ; ?>:</label> <?php CommentForm::author_input_text(); ?><br />
                                    <label for="authorEmail"><?php _e('Your e-mail', 'modern') ; ?>:</label> <?php CommentForm::email_input_text(); ?><br />
                                <?php }; ?>
                                <label for="title"><?php _e('Title', 'modern') ; ?>:</label><?php CommentForm::title_input_text(); ?><br />
                                <label for="body"><?php _e('Comment', 'modern') ; ?>:</label><?php CommentForm::body_input_textarea(); ?><br />
                                <button type="submit"><?php _e('Send', 'modern') ; ?></button>
                            </fieldset>
                        </form>
                    </div>
                    <?php } ?>
                <?php } ?>
            </div>
            <div id="sidebar">
                <?php if( osc_images_enabled_at_items() ) { ?>
                    <?php if( osc_count_item_resources() > 0 ) { ?>
                    <div id="photos">
                        <?php for ( $i = 0; osc_has_item_resources() ; $i++ ) { ?>
                        <a href="<?php echo osc_resource_url(); ?>" rel="image_group" title="<?php _e('Image', 'modern'); ?> <?php echo $i+1;?> / <?php echo osc_count_item_resources();?>">
                            <?php if( $i == 0 ) { ?>
                            <img src="<?php echo osc_resource_url(); ?>" width="315" alt="<?php echo osc_item_title(); ?>" title="<?php echo osc_item_title(); ?>" />
                            <?php } else { ?>
                                <img src="<?php echo osc_resource_thumbnail_url(); ?>" width="75" alt="<?php echo osc_item_title(); ?>" title="<?php echo osc_item_title(); ?>" />
                            <?php } ?>
                        </a>
                        <?php } ?>
                    </div>
                    <?php } ?>
                <?php } ?>
                <div id="contact">
                    <h2><?php _e("Contact publisher", 'modern') ; ?></h2>
                    <?php if( osc_item_is_expired () ) { ?>
                        <p>
                            <?php _e("The listing is expired. You can't contact the publisher.", 'modern') ; ?>
                        </p>
                    <?php } else if( ( osc_logged_user_id() == osc_item_user_id() ) && osc_logged_user_id() != 0 ) { ?>
                        <p>
                            <?php _e("It's your own listing, you can't contact the publisher.", 'modern') ; ?>
                        </p>
                    <?php } else if( osc_reg_user_can_contact() && !osc_is_web_user_logged_in() ) { ?>
                        <p>
                            <?php _e("You must log in or register a new account in order to contact the advertiser", 'modern') ; ?>
                        </p>
                        <p class="contact_button">
                            <strong><a href="<?php echo osc_user_login_url() ; ?>"><?php _e('Login', 'modern') ; ?></a></strong>
                            <strong><a href="<?php echo osc_register_account_url() ; ?>"><?php _e('Register for a free account', 'modern'); ?></a></strong>
                        </p>
                    <?php } else { ?>
                        <?php if( osc_item_user_id() != null ) { ?>
                            <p class="name"><?php _e('Name', 'modern') ?>: <a href="<?php echo osc_user_public_profile_url( osc_item_user_id() ); ?>" ><?php echo osc_item_contact_name(); ?></a></p>
                        <?php } else { ?>
                            <p class="name"><?php _e('Name', 'modern') ?>: <?php echo osc_item_contact_name(); ?></p>
                        <?php } ?>
                        <?php if( osc_item_show_email() ) { ?>
                            <p class="email"><?php _e('E-mail', 'modern'); ?>: <?php echo osc_item_contact_email(); ?></p>
                        <?php } ?>
                        <?php if ( osc_user_phone() != '' ) { ?>
                            <p class="phone"><?php _e("Tel", 'modern'); ?>.: <?php echo osc_user_phone() ; ?></p>
                        <?php } ?>
                        <ul id="error_list"></ul>
                        <?php ContactForm::js_validation(); ?>
                        <form action="<?php echo osc_base_url(true) ; ?>" method="post" name="contact_form" id="contact_form">
                            <?php osc_prepare_user_info() ; ?>
                            <fieldset>
                                <label for="yourName"><?php _e('Your name', 'modern') ; ?>:</label> <?php ContactForm::your_name(); ?>
                                <label for="yourEmail"><?php _e('Your e-mail address', 'modern') ; ?>:</label> <?php ContactForm::your_email(); ?>
                                <label for="phoneNumber"><?php _e('Phone number', 'modern') ; ?> (<?php _e('optional', 'modern'); ?>):</label> <?php ContactForm::your_phone_number(); ?>
                                <label for="message"><?php _e('Message', 'modern') ; ?>:</label> <?php ContactForm::your_message(); ?>
                                <input type="hidden" name="action" value="contact_post" />
                                <input type="hidden" name="page" value="item" />
                                <input type="hidden" name="id" value="<?php echo osc_item_id() ; ?>" />
                                <?php if( osc_recaptcha_public_key() ) { ?>
                                <script type="text/javascript">
                                    var RecaptchaOptions = {
                                        theme : 'custom',
                                        custom_theme_widget: 'recaptcha_widget'
                                    };
                                </script>
                                <style type="text/css"> div#recaptcha_widget, div#recaptcha_image > img { width:280px; } </style>
                                <div id="recaptcha_widget">
                                    <div id="recaptcha_image"><img /></div>
                                    <span class="recaptcha_only_if_image"><?php _e('Enter the words above','modern'); ?>:</span>
                                    <input type="text" id="recaptcha_response_field" name="recaptcha_response_field" />
                                    <div><a href="javascript:Recaptcha.showhelp()"><?php _e('Help', 'modern'); ?></a></div>
                                </div>
                                <?php } ?>
                                <?php osc_show_recaptcha(); ?>
                                <button type="submit"><?php _e('Send', 'modern') ; ?></button>
                            </fieldset>
                        </form>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php osc_current_web_theme_path('footer.php') ; ?>
    </body>
</html>