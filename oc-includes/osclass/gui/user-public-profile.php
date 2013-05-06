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

    $address = '';
    if(osc_user_address()!='') {
        if(osc_user_city_area()!='') {
            $address = osc_user_address().", ".osc_user_city_area();
        } else {
            $address = osc_user_address();
        }
    } else {
        $address = osc_user_city_area();
    }
    $location_array = array();
    if(trim(osc_user_city()." ".osc_user_zip())!='') {
        $location_array[] = trim(osc_user_city()." ".osc_user_zip());
    }
    if(osc_user_region()!='') {
        $location_array[] = osc_user_region();
    }
    if(osc_user_country()!='') {
        $location_array[] = osc_user_country();
    }
    $location = implode(", ", $location_array);
    unset($location_array);

    osc_enqueue_script('jquery-validate');
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
        <div class="content item user_public_profile">
            <div id="item_head">
                <div class="inner">
                    <h1><?php echo sprintf(__('%s\'s profile', 'modern'), osc_user_name()); ?></h1>
                </div>
            </div>
            <div id="main">
                <br />
                <div id="description">
                <h2><?php _e('Profile', 'modern'); ?></h2>
                    <ul id="user_data">
                        <li><?php _e('Full name', 'modern'); ?>: <?php echo osc_user_name(); ?></li>
                        <li><?php _e('Address', 'modern'); ?>: <?php echo $address; ?></li>
                        <li><?php _e('Location', 'modern'); ?>: <?php echo $location; ?></li>
                        <li><?php _e('Website', 'modern'); ?>: <?php echo osc_user_website(); ?></li>
                        <li><?php _e('User Description', 'modern'); ?>: <?php echo osc_user_info(); ?></li>
                    </ul>
                </div>
                <div id="description" class="latest_ads">
                    <h2><?php _e('Latest listings', 'modern'); ?></h2>
                    <table border="0" cellspacing="0">
                        <tbody>
                            <?php $class = "even"; ?>
                            <?php while(osc_has_items()) { ?>
                                <tr class="<?php echo $class; ?>" >
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
                    <?php if(osc_search_total_pages() > osc_max_results_per_page_at_search() ) { ?>
                    <p class="see_more_link"><a href="<?php echo osc_base_url(true).'&page=search&sUser[]='.osc_user_id(); ?>"><strong>See all offers »</strong></a></p>
                    <?php } ?>
                </div>
            </div>
            <div id="sidebar">
                <?php if(osc_logged_user_id()!=  osc_user_id()) { ?>
                <?php     if(osc_reg_user_can_contact() && osc_is_web_user_logged_in() || !osc_reg_user_can_contact() ) { ?>
                <div id="contact">
                    <h2><?php _e("Contact publisher", 'modern'); ?></h2>
                    <ul id="error_list"></ul>
                    <?php ContactForm::js_validation(); ?>
                    <form action="<?php echo osc_base_url(true); ?>" method="post" name="contact_form" id="contact_form">
                        <input type="hidden" name="action" value="contact_post" />
                        <input type="hidden" name="page" value="user" />
                        <input type="hidden" name="id" value="<?php echo osc_user_id();?>" />
                        <?php osc_prepare_user_info(); ?>
                        <fieldset>
                            <label for="yourName"><?php _e('Your name', 'modern'); ?>:</label> <?php ContactForm::your_name(); ?>
                            <label for="yourEmail"><?php _e('Your e-mail address', 'modern'); ?>:</label> <?php ContactForm::your_email(); ?>
                            <label for="phoneNumber"><?php _e('Phone number', 'modern'); ?> (<?php _e('optional', 'modern'); ?>):</label> <?php ContactForm::your_phone_number(); ?>
                            <label for="message"><?php _e('Message', 'modern'); ?>:</label> <?php ContactForm::your_message(); ?>
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
                            <button type="submit"><?php _e('Send', 'modern'); ?></button>
                        </fieldset>
                    </form>
                </div>
                <?php     } ?>
                <?php } ?>
            </div>
        </div>
        <?php osc_current_web_theme_path('footer.php'); ?>
    </body>
</html>
