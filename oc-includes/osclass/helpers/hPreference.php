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

    /**
    * Helper Preferences
    * @package OSClass
    * @subpackage Helpers
    * @author OSClass
    */

    /**
     * Return cookie's life
     *
     * @return int 
     */
    function osc_time_cookie() {
        return ( 31536000 ) ; // one year in seconds
    }

    /**
     * Return if comments are enabled or not
     *
     * @return boolean 
     */
    function osc_comments_enabled() {
        return (getBoolPreference('enabled_comments')) ;
    }

    /**
     * Return comments per page
     *
     * @return int 
     */
    function osc_comments_per_page() {
        return (getPreference('comments_per_page')) ;
    }

    /**
     * Return if users are enabled or not
     *
     * @return boolean 
     */
    function osc_users_enabled() {
        return (getBoolPreference('enabled_users')) ;
    }

    /**
     * Return if user registration is enabled
     *
     * @return boolean 
     */
    function osc_user_registration_enabled() {
        return (getBoolPreference('enabled_user_registration')) ;
    }

    /**
     * Return is user validation is enabled or not
     *
     * @return boolean 
     */
    function osc_user_validation_enabled() {
        return (getBoolPreference('enabled_user_validation')) ;
    }

    /**
     * Return if validation for logged users is required or not
     *
     * @return boolean 
     */
    function osc_logged_user_item_validation() {
        return (getBoolPreference('logged_user_item_validation')) ;
    }

    /**
     * Return how many comments should be posted before auto-moderation
     *
     * @return int 
     */
    function osc_moderate_comments() {
        return (getPreference('moderate_comments')) ;
    }

    /**
     * Return if notification of new comments is enabled or not
     *
     * @return boolean 
     */
    function osc_notify_new_comment() {
        return (getBoolPreference('notify_new_comment')) ;
    }

    /**
     * Return if nice urls are enabled or not
     *
     * @return boolean 
     */
    function osc_rewrite_enabled() {
        return (getBoolPreference('rewriteEnabled')) ;
    }

    /**
     * Return if mod rewrite is loaded or not (if apache runs on cgi mode, mod rewrite will not be detected)
     *
     * @return boolean 
     */
    function osc_mod_rewrite_loaded() {
        return (getBoolPreference('mod_rewrite_loaded')) ;
    }

    /**
     * Return if original images should be kept
     *
     * @return boolean 
     */
    function osc_keep_original_image() {
        return (getBoolPreference('keep_original_image')) ;
    }

    /**
     * Return if autocron is enabled
     *
     * @return boolean 
     */
    function osc_auto_cron() {
        return (getBoolPreference('auto_cron')) ;
    }

    /**
     * Return if recaptcha for items is enabled or not
     *
     * @return boolean 
     */
    function osc_recaptcha_items_enabled() {
        return (getBoolPreference('enabled_recaptcha_items')) ;
    }

    /**
     * Return how many seconds should an user wait to post a second item (0 for no waiting)
     *
     * @return int
     */
    function osc_items_wait_time() {
        return (getPreference('items_wait_time'));
    }
    
    /**
     * Return how many items should be moderated to enable auto-moderation
     *
     * @return int 
     */
    function osc_moderate_items() {
        return (getPreference('moderate_items')) ;
    }
    
    /**
     * Return if only registered users can publish new items or anyone could
     *
     * @return boolean 
     */
    function osc_reg_user_post() {
        return (getBoolPreference('reg_user_post')) ;
    }

    /**
     * Return if the prices are o not enabled on the item's form
     *
     * @return boolean 
     */
    function osc_price_enabled_at_items() {
        return (getBoolPreference('enableField#f_price@items')) ;
    }

    /**
     * Return if images are o not enabled in item's form
     *
     * @return boolean 
     */
    function osc_images_enabled_at_items() {
        return (getBoolPreference('enableField#images@items')) ;
    }

    /**
     * Return how many images are allowed per item (o for unlimited)
     *
     * @return int 
     */
    function osc_max_images_per_item() {
        return (getPreference('numImages@items')) ;
    }

    /**
     * Return if notification are sent to admin when a send-a-friend message is sent
     *
     * @return boolean 
     */
    function osc_notify_contact_friends() {
        return(getBoolPreference('notify_contact_friends')) ;
    }

    /**
     * Return if notification are sent to admin when a contact message is sent
     *
     * @return boolean 
     */
    function osc_notify_contact_item() {
        return(getBoolPreference('notify_contact_item')) ;
    }

    /**
     * Return item attachment is enabled
     *
     * @return boolean
     */
    function osc_item_attachment() {
        return(getBoolPreference('item_attachment')) ;
    }

    /**
     * Return if contact attachment is enabled
     *
     * @return boolean
     */
    function osc_contact_attachment() {
        return(getBoolPreference('contact_attachment')) ;
    }

    /**
     * Return if notification are sent to admin with new item
     *
     * @return boolean
     */
    function osc_notify_new_item() {
        return(getBoolPreference('notify_new_item')) ;
    }

    /**
     * Return if the mailserver requires authetification
     *
     * @return boolean
     */
    function osc_mailserver_auth() {
        return(getBoolPreference('mailserver_auth')) ;
    }
    

    //OTHER FUNCTIONS TO GET INFORMATION OF PREFERENCES
    /**
     * Return the rewrite rules (generated via generate_rules.php at root folder)
     *
     * @return string
     */
    function osc_rewrite_rules() {
        return (getPreference('rewrite_rules')) ;
    }

    /**
     * Return max kb of uploads
     *
     * @return int
     */
    function osc_max_size_kb() {
        return (getPreference('maxSizeKb')) ;
    }

    /**
     * Return allowed extensions of uploads
     *
     * @return string
     */
    function osc_allowed_extension() {
        return (getPreference('allowedExt')) ;
    }

    /**
     * Return thumbnails' dimensions
     *
     * @return string
     */
    function osc_thumbnail_dimensions() {
        return (getPreference('dimThumbnail')) ;
    }

    /**
     * Return preview images' dimensions
     *
     * @return string
     */
    function osc_preview_dimensions() {
        return (getPreference('dimPreview')) ;
    }

    /**
     * Return normal size images' dimensions
     *
     * @return string
     */
    function osc_normal_dimensions() {
        return (getPreference('dimNormal')) ;
    }

    /**
     * Return when was the last version check
     *
     * @return string
     */
    function osc_last_version_check() {
        return (getPreference('last_version_check')) ;
    }

    /**
     * Return current version
     *
     * @return int
     */
    function osc_version() {
        return (getPreference('version')) ;
    }

    /**
     * Return website's title
     *
     * @return string
     */
    function osc_page_title() {
        return (getPreference('pageTitle')) ;
    }

    /**
     * Return website's default language
     *
     * @return string
     */
    function osc_language() {
        return(getPreference('language')) ;
    }

    /**
     * Return website's admin default language
     *
     * @return string
     */
    function osc_admin_language() {
        return(getPreference('admin_language')) ;
    }

    /**
     * Return current theme
     *
     * @return string
     */
    function osc_theme() {
        return(getPreference('theme')) ;
    }

    /**
     * Return current admin theme
     *
     * @return string
     */
    function osc_admin_theme() {
        return(getPreference('admin_theme')) ;
    }

    /**
     * Return website description
     *
     * @return string
     */
    function osc_page_description() {
        return(getPreference('pageDesc')) ;
    }

    /**
     * Return contact email
     *
     * @return string
     */
    function osc_contact_email() {
        return(getPreference('contactEmail')) ;
    }
    
    /**
     * Return date format
     *
     * @return string
     */
    function osc_date_format() {
        return(getPreference('dateFormat')) ;
    }

    /**
     * Return time format
     *
     * @return string
     */
    function osc_time_format() {
        return(getPreference('timeFormat')) ;
    }

    /**
     * Return week start day
     *
     * @return string
     */
    function osc_week_starts_at() {
        return(getPreference('weekStart')) ;
    }

    /**
     * Return number of items to display on RSS
     *
     * @return int
     */
    function osc_num_rss_items() {
        return(getPreference('num_rss_items')) ;
    }

    /**
     * Return default currency
     *
     * @return string
     */
    function osc_currency() {
        return(getPreference('currency')) ;
    }

    /**
     * Return akismet key
     *
     * @return string
     */
    function osc_akismet_key() {
        return(getPreference('akismetKey')) ;
    }

    /**
     * Return recaptcha private key
     *
     * @return string
     */
    function osc_recaptcha_private_key() {
        return(getPreference('recaptchaPrivKey')) ;
    }

    /**
     * Return recaptcha public key
     *
     * @return string
     */
    function osc_recaptcha_public_key() {
        return(getPreference('recaptchaPubKey')) ;
    }

    /**
     * Return mailserver's type
     *
     * @return string
     */
    function osc_mailserver_type() {
        return(getPreference('mailserver_type')) ;
    }

    /**
     * Return mailserver's host
     *
     * @return string
     */
    function osc_mailserver_host() {
        return(getPreference('mailserver_host')) ;
    }

    /**
     * Return mailserver's port
     *
     * @return int
     */
    function osc_mailserver_port() {
        return(getPreference('mailserver_port')) ;
    }

    /**
     * Return mailserver's username
     *
     * @return string
     */
    function osc_mailserver_username() {
        return(getPreference('mailserver_username')) ;
    }

    /**
     * Return mailserver's password
     *
     * @return string
     */
    function osc_mailserver_password() {
        return(getPreference('mailserver_password')) ;
    }

    /**
     * Return if use SSL on the mailserver
     *
     * @return boolean
     */
    function osc_mailserver_ssl() {
        return(getPreference('mailserver_ssl')) ;
    }

    /**
     * Return list of active plugins
     *
     * @return string
     */
    function osc_active_plugins() {
        return(getPreference('active_plugins')) ;
    }

    /**
     * Return default order field at search
     *
     * @return string
     */
    function osc_default_order_field_at_search() {
        return(getPreference('defaultOrderField@search')) ;
    }

    /**
     * Return default order type at search
     *
     * @return string
     */
    function osc_default_order_type_at_search() {
        return(getPreference('defaultOrderType@search')) ;
    }
    
    /**
     * Return default show as at search
     *
     * @return string
     */
    function osc_default_show_as_at_search() {
        return(getPreference('defaultShowAs@search')) ;
    }

    /**
     * Return max results per page at search
     *
     * @return int
     */
    function osc_max_results_per_page_at_search() {
        return(getPreference('maxResultsPerPage@search')) ;
    }

    /**
     * Return default results per page at search
     *
     * @return int
     */
    function osc_default_results_per_page_at_search() {
        return(getPreference('defaultResultsPerPage@search')) ;
    }

    /**
     * Return max latest items
     *
     * @return int
     */
    function osc_max_latest_items() {
        return(getPreference('maxLatestItems@home')) ;
    }

    /**
     * Return if save searches is enabled or not
     *
     * @return boolean
     */
    function osc_save_latest_searches() {
        return(getBoolPreference('save_latest_searches')) ;
    }

    function osc_purge_latest_searches() {
        return(getPreference('purge_latest_searches')) ;
    }

    /**
     * Return how many seconds between item post to not consider it SPAM
     *
     * @return int
     */
    function osc_item_spam_delay() {
        return 60; // need to be changed
    }
    
    /**
     * Return how many seconds between comment post to not consider it SPAM
     *
     * @return int
     */
    function osc_comment_spam_delay() {
        return 60; // need to be changed
    }
    
    /**
     * Return if parent categories are enabled or not
     *
     * @return boolean
     */
    function osc_selectable_parent_categories() {
        return(getPreference('selectable_parent_categories')) ;
    }

    /**
     * generic function to retrieve preferences
     *
     * @return string
     */
    function osc_get_preference($key, $section = 'osclass') {
        return getPreference($key, $section);
    }

    /**
     * generic function to insert/update preferences
     *
     * @return boolean
     */
    function osc_set_preference($key, $value = '', $section = 'osclass', $type = 'STRING') {
        return Preference::newInstance()->replace($key, $value, $section, $type);
    }

    /**
     * generic function to delete preferences
     *
     * @return boolean
     */
    function osc_delete_preference($value = '', $section = 'osclass') {
        return Preference::newInstance()->delete(array('s_name' => $value, 's_section' => $section));
    }

    /**
     * Reload preferences
     *
     * @return <array>
     */
    function osc_reset_preferences() {
        return Preference::newInstance()->toArray();
    }
    

    //PRIVATE FUNCTION (if there was a class :P)
    /**
     * Return preference
     *
     * @return boolean
     */
    function getBoolPreference($key) {
        $_P = Preference::newInstance() ;

        if($_P->get($key)) {
            return true ;
        } else {
            return false ;
        }
    }

    //PRIVATE FUNCTION FOR GETTING NO BOOLEAN INFORMATION (if there was a class :P)
    /**
     * Return preference
     *
     * @return string
     */
    function getPreference($key, $section = 'osclass') {
        $_P = Preference::newInstance() ;
        return($_P->get($key, $section)) ;
    }
?>
