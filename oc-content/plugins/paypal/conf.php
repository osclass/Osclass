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

    if(Params::getParam('plugin_action')=='done') {
        osc_set_preference('default_premium_cost', Params::getParam("default_premium_cost") ? Params::getParam("default_premium_cost") : '1.0', 'paypal', 'STRING');
        osc_set_preference('allow_premium', Params::getParam("allow_premium") ? Params::getParam("allow_premium") : '0', 'paypal', 'BOOLEAN');
        osc_set_preference('default_publish_cost', Params::getParam("default_premium_cost") ? Params::getParam("default_publish_cost") : '1.0', 'paypal', 'STRING');
        osc_set_preference('pay_per_post', Params::getParam("pay_per_post") ? Params::getParam("pay_per_post") : '0', 'paypal', 'BOOLEAN');
        osc_set_preference('premium_days', Params::getParam("premium_days") ? Params::getParam("premium_days") : '7', 'paypal', 'INTEGER');
        osc_set_preference('currency', Params::getParam("currency") ? Params::getParam("currency") : 'USD', 'paypal', 'STRING');
        osc_set_preference('api_username', Params::getParam("api_username"), 'paypal', 'STRING');
        osc_set_preference('api_password', Params::getParam("api_password"), 'paypal', 'STRING');
        osc_set_preference('api_signature', Params::getParam("api_signature"), 'paypal', 'STRING');
        echo '<div style="text-align:center; font-size:22px; background-color:#00bb00;"><p>' . __('Congratulations. The plugin is now configured', 'paypal') . '.</p></div>' ;
        osc_reset_preferences();
    }
?>
<div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
    <div style="padding: 20px;">
        <div style="float: left; width: 100%;">
            <fieldset>
                <legend><?php _e('Paypal Options', 'paypal'); ?></legend>
                <form name="paypal_form" id="paypal_form" action="<?php echo osc_admin_base_url(true); ?>" method="GET" enctype="multipart/form-data" >
                    <div style="float: left; width: 50%;">
                    <input type="hidden" name="page" value="plugins" />
                    <input type="hidden" name="action" value="renderplugin" />
                    <input type="hidden" name="file" value="<?php echo osc_plugin_folder(__FILE__); ?>conf.php" />
                    <input type="hidden" name="plugin_action" value="done" />
                        <label><?php _e('API username', 'paypal'); ?></label><input type="text" name="api_username" id="api_username" value="<?php echo osc_get_preference('api_username', 'paypal'); ?>" />
                        <br/>
                        <label><?php _e('API password', 'paypal'); ?></label><input type="password" name="api_password" id="api_password" value="<?php echo osc_get_preference('api_password', 'paypal'); ?>" />
                        <br/>
                        <label><?php _e('API signature', 'paypal'); ?></label><input type="text" name="api_signature" id="api_signature" value="<?php echo osc_get_preference('api_signature', 'paypal'); ?>" />
                        <br/>
                    </div>
                    <div style="float: left; width: 50%;">
                        <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" <?php echo (osc_get_preference('allow_premium', 'paypal') ? 'checked="true"' : ''); ?> name="allow_premium" id="allow_premium" value="1" />
                        <label for="allow_premium"><?php _e('Allow premium ads', 'paypal'); ?></label>
                        <br/>
                        <label><?php _e('Default premium cost', 'paypal'); ?></label><input type="text" name="default_premium_cost" id="default_premium_cost" value="<?php echo osc_get_preference('default_premium_cost', 'paypal'); ?>" />
                        <br/>
                        <label><?php _e('Premium days', 'paypal'); ?></label><input type="text" name="premium_days" id="premium_days" value="<?php echo osc_get_preference('premium_days', 'paypal'); ?>" />
                        <br/>
                        <input style="height: 20px; padding-left: 4px;padding-top: 4px;" type="checkbox" <?php echo (osc_get_preference('pay_per_post', 'paypal') ? 'checked="true"' : ''); ?> name="pay_per_post" id="pay_per_post" value="1" />
                        <label for="pay_per_post"><?php _e('Pay per post ads', 'paypal'); ?></label>
                        <br/>
                        <label><?php _e('Default publish cost', 'paypal'); ?></label><input type="text" name="default_publish_cost" id="default_publish_cost" value="<?php echo osc_get_preference('default_publish_cost', 'paypal'); ?>" />
                        <br/>
                        <label><?php _e('Currency (3-character code)', 'paypal'); ?></label><input type="text" name="currency" id="currency" value="<?php echo osc_get_preference('currency', 'paypal'); ?>" />
                        <br/>
                        <button type="submit" style="float: right;"><?php _e('Update', 'paypal');?></button>
                    </div>
                </form>
            </fieldset>
        </div>
        <div style="clear:both;">
        <div style="float: left; width: 100%;">
            <fieldset>
                <legend><?php _e('Help', 'paypal'); ?></legend>
                <h3><?php _e('Setting up your Paypal account', 'paypal'); ?></h3>
                <p>
                    <?php _e('Before being able to use Paypal plugin, you need to set up some configuration at your Paypal account', 'paypal'); ?>.
                    <br/>
                    <?php _e('Your Paypal account has to be set as Business or Premier, you could change that at Your Profile, under My Settings', 'paypal'); ?>.
                    <br/>
                    <?php _e('You need Paypal API credentials', 'paypal'); ?>.
                    <br/>
                    <?php _e('You need to tell Paypal where is your IPN file', 'paypal'); ?>
                </p>
                <h3><?php _e('Setting up your IPN', 'paypal'); ?></h3>
                <p>
                    <?php _e('Click Profile on the My Account tab', 'paypal'); ?>.
                    <br/>
                    <?php _e('Click Instant Payment Notification Preferences in the Selling Preferences column', 'paypal'); ?>.
                    <br/>
                    <?php _e("Click Choose IPN Settings to specify your listener’s URL and activate the listener (usually is http://www.yourdomain.com/oc-content/plugins/paypal/notify_url.php)", 'paypal'); ?>.
                </p>
                <h3><?php _e('How to obtain API credentials', 'paypal'); ?></h3>
                <p>
                    <?php _e('In order to use the Paypal plugin you will need Paypal API credentials, you could obtain them for free following theses steps', 'paypal'); ?>:
                    <br/>
                    <?php _e('Verify your account status. Go to your PayPal Profile under My Settings and verify that your Account Type is Premier or Business, or upgrade your account', "paypal"); ?>.
                    <br/>
                    <?php _e('Verify your API settings. Click on My Selling Tools. Click Selling Online and verify your API access. Click Update to view or set up your API signature and credentials', 'paypal'); ?>.
                </p>
            </fieldset>
        </div>
        <div style="clear: both;"></div>										
    </div>
</div>