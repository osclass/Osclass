<?php
    /*
     *      Osclass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2014 OSCLASS
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
<div id="sidebar">
    <?php if(!osc_is_web_user_logged_in() || osc_logged_user_id()!=osc_item_user_id()) { ?>
        <form action="<?php echo osc_base_url(true); ?>" method="post" name="mask_as_form" id="mask_as_form">
            <input type="hidden" name="id" value="<?php echo osc_item_id(); ?>" />
            <input type="hidden" name="as" value="spam" />
            <input type="hidden" name="action" value="mark" />
            <input type="hidden" name="page" value="item" />
            <select name="as" id="as" class="mark_as">
                    <option><?php _e("Mark as...", 'bender'); ?></option>
                    <option value="spam"><?php _e("Mark as spam", 'bender'); ?></option>
                    <option value="badcat"><?php _e("Mark as misclassified", 'bender'); ?></option>
                    <option value="repeated"><?php _e("Mark as duplicated", 'bender'); ?></option>
                    <option value="expired"><?php _e("Mark as expired", 'bender'); ?></option>
                    <option value="offensive"><?php _e("Mark as offensive", 'bender'); ?></option>
            </select>
        </form>
    <?php } ?>

    <?php if( osc_get_preference('sidebar-300x250', 'bender') != '') {?>
    <!-- sidebar ad 350x250 -->
    <div class="ads_300">
        <?php echo osc_get_preference('sidebar-300x250', 'bender'); ?>
    </div>
    <!-- /sidebar ad 350x250 -->
    <?php } ?>

    <div id="contact" class="widget-box form-container form-vertical">
        <h2><?php _e("Contact publisher", 'bender'); ?></h2>
        <?php if( osc_item_is_expired () ) { ?>
            <p>
                <?php _e("The listing is expired. You can't contact the publisher.", 'bender'); ?>
            </p>
        <?php } else if( ( osc_logged_user_id() == osc_item_user_id() ) && osc_logged_user_id() != 0 ) { ?>
            <p>
                <?php _e("It's your own listing, you can't contact the publisher.", 'bender'); ?>
            </p>
        <?php } else if( osc_reg_user_can_contact() && !osc_is_web_user_logged_in() ) { ?>
            <p>
                <?php _e("You must log in or register a new account in order to contact the advertiser", 'bender'); ?>
            </p>
            <p class="contact_button">
                <strong><a href="<?php echo osc_user_login_url(); ?>"><?php _e('Login', 'bender'); ?></a></strong>
                <strong><a href="<?php echo osc_register_account_url(); ?>"><?php _e('Register for a free account', 'bender'); ?></a></strong>
            </p>
        <?php } else { ?>
            <?php if( osc_item_user_id() != null ) { ?>
                <p class="name"><?php _e('Name', 'bender') ?>: <a href="<?php echo osc_user_public_profile_url( osc_item_user_id() ); ?>" ><?php echo osc_item_contact_name(); ?></a></p>
            <?php } else { ?>
                <p class="name"><?php printf(__('Name: %s', 'bender'), osc_item_contact_name()); ?></p>
            <?php } ?>
            <?php if( osc_item_show_email() ) { ?>
                <p class="email"><?php printf(__('E-mail: %s', 'bender'), osc_item_contact_email()); ?></p>
            <?php } ?>
            <?php if ( osc_user_phone() != '' ) { ?>
                <p class="phone"><?php printf(__("Phone: %s", 'bender'), osc_user_phone()); ?></p>
            <?php } ?>
            <ul id="error_list"></ul>
            <form action="<?php echo osc_base_url(true); ?>" method="post" name="contact_form" id="contact_form" <?php if(osc_item_attachment()) { echo 'enctype="multipart/form-data"'; };?> >
                <?php osc_prepare_user_info(); ?>
                 <input type="hidden" name="action" value="contact_post" />
                    <input type="hidden" name="page" value="item" />
                    <input type="hidden" name="id" value="<?php echo osc_item_id(); ?>" />
                <div class="control-group">
                    <label class="control-label" for="yourName"><?php _e('Your name', 'bender'); ?>:</label>
                    <div class="controls"><?php ContactForm::your_name(); ?></div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="yourEmail"><?php _e('Your e-mail address', 'bender'); ?>:</label>
                    <div class="controls"><?php ContactForm::your_email(); ?></div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="phoneNumber"><?php _e('Phone number', 'bender'); ?> (<?php _e('optional', 'bender'); ?>):</label>
                    <div class="controls"><?php ContactForm::your_phone_number(); ?></div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="message"><?php _e('Message', 'bender'); ?>:</label>
                    <div class="controls textarea"><?php ContactForm::your_message(); ?></div>
                </div>

                <?php if(osc_item_attachment()) { ?>
                    <div class="control-group">
                        <label class="control-label" for="attachment"><?php _e('Attachment', 'bender'); ?>:</label>
                        <div class="controls"><?php ContactForm::your_attachment(); ?></div>
                    </div>
                <?php }; ?>

                <div class="control-group">
                    <div class="controls">
                        <?php osc_run_hook('item_contact_form', osc_item_id()); ?>
                        <?php if( osc_recaptcha_public_key() ) { ?>
                        <script type="text/javascript">
                            var RecaptchaOptions = {
                                theme : 'custom',
                                custom_theme_widget: 'recaptcha_widget'
                            };
                        </script>
                        <style type="text/css">
                          div#recaptcha_widget, div#recaptcha_image > img { width:240px; margin-top: 5px; }
                          div#recaptcha_image { margin-bottom: 15px; }
                        </style>
                        <div id="recaptcha_widget">
                            <div id="recaptcha_image"><img /></div>
                            <span class="recaptcha_only_if_image"><?php _e('Enter the words above','bender'); ?>:</span>
                            <input type="text" id="recaptcha_response_field" name="recaptcha_response_field" />
                            <div><a href="javascript:Recaptcha.showhelp()"><?php _e('Help', 'bender'); ?></a></div>
                        </div>
                        <?php } ?>
                        <?php osc_show_recaptcha(); ?>
                        <button type="submit" class="ui-button ui-button-middle ui-button-main"><?php _e("Send", 'bender');?></button>
                    </div>
                </div>
            </form>
            <?php ContactForm::js_validation(); ?>
        <?php } ?>
    </div>
</div><!-- /sidebar -->
