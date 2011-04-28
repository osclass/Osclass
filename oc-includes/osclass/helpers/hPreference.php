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

    function osc_time_cookie() {
        return ( 31536000 ) ; // one year in seconds
    }

    function osc_comments_enabled() {
        return (getBoolPreference('enabled_comments')) ;
    }

    function osc_users_enabled() {
        return (getBoolPreference('enabled_users')) ;
    }

    function osc_user_registration_enabled() {
        return (getBoolPreference('enabled_user_registration')) ;
    }

    function osc_user_validation_enabled() {
        return (getBoolPreference('enabled_user_validation')) ;
    }

    function osc_logged_user_item_validation() {
        return (getBoolPreference('logged_user_item_validation')) ;
    }

    function osc_moderate_comments() {
        return (getPreference('moderate_comments')) ;
    }

    function osc_notify_new_comment() {
        return (getBoolPreference('notify_new_comment')) ;
    }

    function osc_rewrite_enabled() {
        return (getBoolPreference('rewriteEnabled')) ;
    }

    function osc_mod_rewrite_loaded() {
        return (getBoolPreference('mod_rewrite_loaded')) ;
    }

    function osc_keep_original_image() {
        return (getBoolPreference('keep_original_image')) ;
    }

    function osc_auto_cron() {
        return (getBoolPreference('auto_cron')) ;
    }

    //osc_enabled_recaptcha_items
    function osc_recaptcha_items_enabled() {
        return (getBoolPreference('enabled_recaptcha_items')) ;
    }

    function osc_items_wait_time() {
        return (getPreference('items_wait_time'));
    }
    
    function osc_moderate_items() {
        return (getPreference('moderate_items')) ;
    }
    
    function osc_reg_user_post() {
        return (getBoolPreference('reg_user_post')) ;
    }

    function osc_price_enabled_at_items() {
        return (getBoolPreference('enableField#f_price@items')) ;
    }

    function osc_images_enabled_at_items() {
        return (getBoolPreference('enableField#images@items')) ;
    }

    function osc_notify_contact_friends() {
        return(getBoolPreference('notify_contact_friends')) ;
    }

    function osc_notify_contact_item() {
        return(getBoolPreference('notify_contact_item')) ;
    }

    function osc_item_attachment() {
        return(getBoolPreference('item_attachment')) ;
    }

    function osc_contact_attachment() {
        return(getBoolPreference('contact_attachment')) ;
    }

    function osc_notify_new_item() {
        return(getBoolPreference('notify_new_item')) ;
    }

    function osc_mailserver_auth() {
        return(getBoolPreference('mailserver_auth')) ;
    }
    

    //OTHER FUNCTIONS TO GET INFORMATION OF PREFERENCES
    function osc_rewrite_rules() {
        return (getPreference('rewrite_rules')) ;
    }

    function osc_max_size_kb() {
        return (getPreference('maxSizeKb')) ;
    }

    function osc_allowed_extension() {
        return (getPreference('allowedExt')) ;
    }

    function osc_thumbnail_dimensions() {
        return (getPreference('dimThumbnail')) ;
    }

    function osc_preview_dimensions() {
        return (getPreference('dimPreview')) ;
    }

    function osc_normal_dimensions() {
        return (getPreference('dimNormal')) ;
    }

    function osc_last_version_check() {
        return (getPreference('last_version_check')) ;
    }

    function osc_version() {
        return (getPreference('version')) ;
    }

    function osc_page_title() {
        return (getPreference('pageTitle')) ;
    }

    function osc_language() {
        return(getPreference('language')) ;
    }

    function osc_admin_language() {
        return(getPreference('admin_language')) ;
    }

    function osc_theme() {
        return(getPreference('theme')) ;
    }

    function osc_admin_theme() {
        return(getPreference('admin_theme')) ;
    }

    function osc_page_description() {
        return(getPreference('pageDesc')) ;
    }

    function osc_contact_email() {
        return(getPreference('contactEmail')) ;
    }
    
    function osc_date_format() {
        return(getPreference('dateFormat')) ;
    }

    function osc_time_format() {
        return(getPreference('timeFormat')) ;
    }

    function osc_week_starts_at() {
        return(getPreference('weekStart')) ;
    }

    function osc_num_rss_items() {
        return(getPreference('num_rss_items')) ;
    }

    function osc_currency() {
        return(getPreference('currency')) ;
    }

    function osc_akismet_key() {
        return(getPreference('akismetKey')) ;
    }

    function osc_recaptcha_private_key() {
        return(getPreference('recaptchaPrivKey')) ;
    }

    function osc_recaptcha_public_key() {
        return(getPreference('recaptchaPubKey')) ;
    }

    function osc_mailserver_type() {
        return(getPreference('mailserver_type')) ;
    }

    function osc_mailserver_host() {
        return(getPreference('mailserver_host')) ;
    }

    function osc_mailserver_port() {
        return(getPreference('mailserver_port')) ;
    }

    function osc_mailserver_username() {
        return(getPreference('mailserver_username')) ;
    }

    function osc_mailserver_password() {
        return(getPreference('mailserver_password')) ;
    }

    function osc_mailserver_ssl() {
        return(getPreference('mailserver_ssl')) ;
    }

    function osc_active_plugins() {
        return(getPreference('active_plugins')) ;
    }

    function osc_default_order_field_at_search() {
        return(getPreference('defaultOrderField@search')) ;
    }

    function osc_default_order_type_at_search() {
        return(getPreference('defaultOrderType@search')) ;
    }
    
    function osc_default_show_as_at_search() {
        return(getPreference('defaultShowAs@search')) ;
    }

    function osc_max_results_per_page_at_search() {
        return(getPreference('maxResultsPerPage@search')) ;
    }

    function osc_default_results_per_page_at_search() {
        return(getPreference('defaultResultsPerPage@search')) ;
    }

    function osc_max_latest_items() {
        return(getPreference('maxLatestItems@home')) ;
    }

    function osc_save_latest_searches() {
        return(getBoolPreference('save_latest_searches')) ;
    }

    function osc_purge_latest_searches() {
        return(getPreference('purge_latest_searches')) ;
    }

    function osc_item_spam_delay() {
        return 60; // need to be changed
    }
    
    function osc_comment_spam_delay() {
        return 60; // need to be changed
    }
    
    function osc_selectable_parent_categories() {
        return(getPreference('selectable_parent_categories')) ;
    }

    function osc_get_preference($key, $section = 'osclass') {
        return getPreference($key, $section);
    }

    function osc_set_preference($key, $value = '', $section = 'osclass', $type = 'STRING') {
        return Preference::newInstance()->replace($key, $value, $section, $type);
    }

    function osc_delete_preference($value = '', $section = 'osclass') {
        return Preference::newInstance()->delete(array('s_name' => $value, 's_section' => $section));
    }

    function osc_reset_preferences() {
        return Preference::newInstance()->toArray();
    }
    

    //PRIVATE FUNCTION (if there was a class :P)
    function getBoolPreference($key) {
        $_P = Preference::newInstance() ;

        if($_P->get($key)) {
            return true ;
        } else {
            return false ;
        }
    }

    //PRIVATE FUNCTION FOR GETTING NO BOOLEAN INFORMATION (if there was a class :P)
    function getPreference($key, $section = 'osclass') {
        $_P = Preference::newInstance() ;
        return($_P->get($key, $section)) ;
    }
?>
