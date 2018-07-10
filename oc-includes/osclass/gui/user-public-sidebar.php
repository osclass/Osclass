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
    <?php if(osc_logged_user_id() !=  osc_user_id()) { ?>
    <?php     if(osc_reg_user_can_contact() && osc_is_web_user_logged_in() || !osc_reg_user_can_contact() ) { ?>
        <div id="contact" class="widget-box form-container form-vertical">
            <h2><?php _e("Contact", 'bender'); ?></h2>
                <ul id="error_list"></ul>
                <form action="<?php echo osc_base_url(true); ?>" method="post" name="contact_form" id="contact_form">
                    <input type="hidden" name="action" value="contact_post" />
                    <input type="hidden" name="page" value="user" />
                    <input type="hidden" name="id" value="<?php echo osc_user_id();?>" />
                    <div class="control-group">
                        <label class="control-label" for="yourName"><?php _e('Your name', 'bender'); ?>:</label>
                        <div class="controls"><?php ContactForm::your_name(); ?></div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="yourEmail"><?php _e('Your email address', 'bender'); ?>:</label>
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

                    <div class="control-group">
                        <div class="controls">
                            <?php osc_run_hook('item_contact_form', osc_item_id()); ?>
                            <?php osc_show_recaptcha(); ?>
                            <button type="submit" class="ui-button ui-button-middle ui-button-main"><?php _e("Send", 'bender');?></button>
                        </div>
                    </div>
                </form>
                <?php ContactForm::js_validation(); ?>
        </div>
        <?php
        }
    }
    ?>

</div><!-- /sidebar -->