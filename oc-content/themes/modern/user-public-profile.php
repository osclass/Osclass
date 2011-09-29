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
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
    <head>
        <?php osc_current_web_theme_path('head.php') ; ?>
    </head>
    <body>
        <div class="container">
            <?php osc_current_web_theme_path('header.php') ; ?>
            <div class="content item">
                <div id="item_head">
                    <div class="inner">
                        <h1><?php echo sprintf(__('%s\'s profile', 'modern'), osc_user_name()); ?></h1>
                    </div>
                </div>
                <div id="main">
                    <ul id="user_data">
                        <li><?php _e('Full name'); ?>: <?php echo osc_user_name(); ?></li>
                        <li><?php _e('Address'); ?>: <?php echo $address; ?></li>
                        <li><?php _e('Location'); ?>: <?php echo $location; ?></li>
                        <li><?php _e('Website'); ?>: <?php echo osc_user_website(); ?></li>
                    </ul>
                    <div id="description">
                    </div>
                </div>
                <div id="sidebar">
                    <?php if(osc_logged_user_id()!=  osc_user_id()) { ?>
                    <?php     if(osc_reg_user_can_contact() && osc_is_web_user_logged_in() || !osc_reg_user_can_contact() ) { ?>
                    <div id="contact">
                        <h2><?php _e("Contact publisher", 'modern') ; ?></h2>
                        <p class="name"><?php _e('Name', 'modern') ?>: <?php echo osc_user_name(); ?></p>
                        <?php if(osc_item_show_email()) { ?>
                        <p class="email"><?php _e('E-mail', 'modern'); ?>: <?php echo osc_user_email(); ?></p>
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
                                <input type="hidden" name="page" value="user" />
                                <input type="hidden" name="id" value="<?php echo osc_user_id();?>" />
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
                    </div>
                    <?php     } ?>
                    <?php } ?>
                </div>
            </div>
            <?php osc_current_web_theme_path('footer.php') ; ?>
        </div>
        <?php osc_show_flash_message() ; ?>
        <?php osc_run_hook('footer'); ?>
    </body>
</html>