<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

/*
 * Copyright 2014 Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

    class CAdminSettingsPermalinks extends AdminSecBaseModel
    {
        function __construct()
        {
            parent::__construct();
        }

        //Business Layer...
        function doModel()
        {
            switch($this->action) {
                case('permalinks'):
                    // calling the permalinks view
                    $htaccess = Params::getParam('htaccess_status');
                    $file     = Params::getParam('file_status');

                    $this->_exportVariableToView('htaccess', $htaccess);
                    $this->_exportVariableToView('file', $file);

                    $this->doView('settings/permalinks.php');
                break;
                case('permalinks_post'):
                    // updating permalinks option
                    osc_csrf_check();
                    $htaccess_file  = osc_base_path() . '.htaccess';
                    $rewriteEnabled = (Params::getParam('rewrite_enabled') ? true : false);

                    $rewrite_base = REL_WEB_URL;
                    $htaccess     = <<<HTACCESS
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase {$rewrite_base}
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . {$rewrite_base}index.php [L]
</IfModule>
HTACCESS;

                    if( $rewriteEnabled ) {
                        osc_set_preference('rewriteEnabled', '1');;

                        // 1. OK (ok)
                        // 2. OK no apache module detected (warning)
                        // 3. No se puede crear + apache
                        // 4. No se puede crear + no apache
                        // 5. .htaccess exists, no overwrite
                        $status = 3;
                        if( file_exists($htaccess_file) ) {
                            $status = 5;
                        } else {
                            if( is_writable(osc_base_path()) && file_put_contents($htaccess_file, $htaccess) ) {
                                $status = 1;
                            }
                        }

                        if( !@apache_mod_loaded('mod_rewrite') ) {
                            $status++;
                        }

                        $errors = 0;
                        $item_url = substr(str_replace('//', '/', Params::getParam('rewrite_item_url').'/'), 0, -1);
                        if(!osc_validate_text($item_url)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_item_url', $item_url);;
                        }
                        $page_url = substr(str_replace('//', '/', Params::getParam('rewrite_page_url').'/'), 0, -1);
                        if(!osc_validate_text($page_url)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_page_url', $page_url);;
                        }
                        $cat_url = substr(str_replace('//', '/', Params::getParam('rewrite_cat_url').'/'), 0, -1);
                        // DEPRECATED: backward compatibility, remove in 3.4
                        $cat_url = str_replace('{CATEGORY_SLUG}', '{CATEGORY_NAME}', $cat_url);
                        if(!osc_validate_text($cat_url)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_cat_url', $cat_url);;
                        }
                        $search_url = substr(str_replace('//', '/', Params::getParam('rewrite_search_url').'/'), 0, -1);
                        if(!osc_validate_text($search_url)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_search_url', $search_url);;
                        }

                        if(!osc_validate_text(Params::getParam('rewrite_search_country'))) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_search_country', Params::getParam('rewrite_search_country'));;
                        }
                        if(!osc_validate_text(Params::getParam('rewrite_search_region'))) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_search_region', Params::getParam('rewrite_search_region'));;
                        }
                        if(!osc_validate_text(Params::getParam('rewrite_search_city'))) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_search_city', Params::getParam('rewrite_search_city'));;
                        }
                        if(!osc_validate_text(Params::getParam('rewrite_search_city_area'))) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_search_city_area', Params::getParam('rewrite_search_city_area'));;
                        }
                        if(!osc_validate_text(Params::getParam('rewrite_search_category'))) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_search_category', Params::getParam('rewrite_search_category'));;
                        }
                        if(!osc_validate_text(Params::getParam('rewrite_search_user'))) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_search_user', Params::getParam('rewrite_search_user'));;
                        }
                        if(!osc_validate_text(Params::getParam('rewrite_search_pattern'))) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_search_pattern', Params::getParam('rewrite_search_pattern'));;
                        }

                        $rewrite_contact = substr(str_replace('//', '/', Params::getParam('rewrite_contact').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_contact)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_contact', $rewrite_contact);;
                        }
                        $rewrite_feed = substr(str_replace('//', '/', Params::getParam('rewrite_feed').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_feed)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_feed', $rewrite_feed);;
                        }
                        $rewrite_language = substr(str_replace('//', '/', Params::getParam('rewrite_language').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_language)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_language', $rewrite_language);;
                        }
                        $rewrite_item_mark = substr(str_replace('//', '/', Params::getParam('rewrite_item_mark').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_item_mark)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_item_mark', $rewrite_item_mark);;
                        }
                        $rewrite_item_send_friend = substr(str_replace('//', '/', Params::getParam('rewrite_item_send_friend').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_item_send_friend)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_item_send_friend', $rewrite_item_send_friend);;
                        }
                        $rewrite_item_contact = substr(str_replace('//', '/', Params::getParam('rewrite_item_contact').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_item_contact)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_item_contact', $rewrite_item_contact);;
                        }
                        $rewrite_item_new = substr(str_replace('//', '/', Params::getParam('rewrite_item_new').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_item_new)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_item_new', $rewrite_item_new);;
                        }
                        $rewrite_item_activate = substr(str_replace('//', '/', Params::getParam('rewrite_item_activate').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_item_activate)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_item_activate', $rewrite_item_activate);;
                        }
                        $rewrite_item_edit = substr(str_replace('//', '/', Params::getParam('rewrite_item_edit').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_item_edit)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_item_edit', $rewrite_item_edit);;
                        }
                        $rewrite_item_delete = substr(str_replace('//', '/', Params::getParam('rewrite_item_delete').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_item_delete)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_item_delete', $rewrite_item_delete);;
                        }
                        $rewrite_item_resource_delete = substr(str_replace('//', '/', Params::getParam('rewrite_item_resource_delete').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_item_resource_delete)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_item_resource_delete', $rewrite_item_resource_delete);;
                        }
                        $rewrite_user_login = substr(str_replace('//', '/', Params::getParam('rewrite_user_login').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_user_login)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_user_login', $rewrite_user_login);;
                        }
                        $rewrite_user_dashboard = substr(str_replace('//', '/', Params::getParam('rewrite_user_dashboard').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_user_dashboard)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_user_dashboard', $rewrite_user_dashboard);;
                        }
                        $rewrite_user_logout = substr(str_replace('//', '/', Params::getParam('rewrite_user_logout').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_user_logout)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_user_logout', $rewrite_user_logout);;
                        }
                        $rewrite_user_register = substr(str_replace('//', '/', Params::getParam('rewrite_user_register').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_user_register)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_user_register', $rewrite_user_register);;
                        }
                        $rewrite_user_activate = substr(str_replace('//', '/', Params::getParam('rewrite_user_activate').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_user_activate)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_user_activate', $rewrite_user_activate);;
                        }
                        $rewrite_user_activate_alert = substr(str_replace('//', '/', Params::getParam('rewrite_user_activate_alert').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_user_activate_alert)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_user_activate_alert', $rewrite_user_activate_alert);;
                        }
                        $rewrite_user_profile = substr(str_replace('//', '/', Params::getParam('rewrite_user_profile').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_user_profile)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_user_profile', $rewrite_user_profile);;
                        }
                        $rewrite_user_items = substr(str_replace('//', '/', Params::getParam('rewrite_user_items').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_user_items)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_user_items', $rewrite_user_items);;
                        }
                        $rewrite_user_alerts = substr(str_replace('//', '/', Params::getParam('rewrite_user_alerts').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_user_alerts)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_user_alerts', $rewrite_user_alerts);;
                        }
                        $rewrite_user_recover = substr(str_replace('//', '/', Params::getParam('rewrite_user_recover').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_user_recover)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_user_recover', $rewrite_user_recover);;
                        }
                        $rewrite_user_forgot = substr(str_replace('//', '/', Params::getParam('rewrite_user_forgot').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_user_forgot)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_user_forgot', $rewrite_user_forgot);;
                        }
                        $rewrite_user_change_password = substr(str_replace('//', '/', Params::getParam('rewrite_user_change_password').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_user_change_password)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_user_change_password', $rewrite_user_change_password);;
                        }
                        $rewrite_user_change_email = substr(str_replace('//', '/', Params::getParam('rewrite_user_change_email').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_user_change_email)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_user_change_email', $rewrite_user_change_email);;
                        }
                        $rewrite_user_change_username = substr(str_replace('//', '/', Params::getParam('rewrite_user_change_username').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_user_change_username)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_user_change_username', $rewrite_user_change_username);
                        }
                        $rewrite_user_change_email_confirm = substr(str_replace('//', '/', Params::getParam('rewrite_user_change_email_confirm').'/'), 0, -1);
                        if(!osc_validate_text($rewrite_user_change_email_confirm)) {
                            $errors += 1;
                        } else {
                            osc_set_preference('rewrite_user_change_email_confirm', $rewrite_user_change_email_confirm);
                        }

                        osc_reset_preferences();

                        $rewrite = Rewrite::newInstance();
                        osc_run_hook("before_rewrite_rules", array(&$rewrite));
                        $rewrite->clearRules();

                        /*****************************
                         ********* Add rules *********
                         *****************************/

                        // Contact rules
                        $rewrite->addRule('^'.osc_get_preference('rewrite_contact').'/?$', 'index.php?page=contact');

                        // Feed rules
                        $rewrite->addRule('^'.osc_get_preference('rewrite_feed').'/?$', 'index.php?page=search&sFeed=rss');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_feed').'/(.+)/?$', 'index.php?page=search&sFeed=$1');

                        // Language rules
                        $rewrite->addRule('^'.osc_get_preference('rewrite_language').'/(.*?)/?$', 'index.php?page=language&locale=$1');

                        // Search rules
                        $rewrite->addRule('^'.$search_url.'$', 'index.php?page=search');
                        $rewrite->addRule('^'.$search_url.'/(.*)$', 'index.php?page=search&sParams=$1');

                        // Item rules
                        $rewrite->addRule('^'.osc_get_preference('rewrite_item_mark').'/(.*?)/([0-9]+)/?$', 'index.php?page=item&action=mark&as=$1&id=$2');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_item_send_friend').'/([0-9]+)/?$', 'index.php?page=item&action=send_friend&id=$1');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_item_contact').'/([0-9]+)/?$', 'index.php?page=item&action=contact&id=$1');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_item_new').'/?$', 'index.php?page=item&action=item_add');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_item_new').'/([0-9]+)/?$', 'index.php?page=item&action=item_add&catId=$1');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_item_activate').'/([0-9]+)/(.*?)/?$', 'index.php?page=item&action=activate&id=$1&secret=$2');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_item_edit').'/([0-9]+)/(.*?)/?$', 'index.php?page=item&action=item_edit&id=$1&secret=$2');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_item_delete').'/([0-9]+)/(.*?)/?$', 'index.php?page=item&action=item_delete&id=$1&secret=$2');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_item_resource_delete').'/([0-9]+)/([0-9]+)/([0-9A-Za-z]+)/?(.*?)/?$', 'index.php?page=item&action=deleteResource&id=$1&item=$2&code=$3&secret=$4');

                        // Item rules
                        $id_pos = stripos($item_url, '{ITEM_ID}');
                        $title_pos = stripos($item_url, '{ITEM_TITLE}');
                        $cat_pos = stripos($item_url, '{CATEGORIES');
                        $param_pos = 1;
                        if($title_pos!==false && $id_pos>$title_pos) {
                            $param_pos++;
                        }
                        if($cat_pos!==false && $id_pos>$cat_pos) {
                            $param_pos++;
                        }
                        $comments_pos = 1;
                        if($id_pos!==false) { $comments_pos++; }
                        if($title_pos!==false) { $comments_pos++; }
                        if($cat_pos!==false) { $comments_pos++; }
                        $rewrite->addRule('^([a-z]{2})_([A-Z]{2})/'. str_replace('{ITEM_CITY}', '.*', str_replace('{CATEGORIES}', '.*', str_replace('{ITEM_TITLE}', '.*', str_replace('{ITEM_ID}', '([0-9]+)', $item_url.'\?comments-page=([0-9al]*)')))).'$', 'index.php?page=item&id=$3&lang=$1_$2&comments-page=$4');
                        $rewrite->addRule('^'. str_replace('{ITEM_CITY}', '.*', str_replace('{CATEGORIES}', '.*', str_replace('{ITEM_TITLE}', '.*', str_replace('{ITEM_ID}', '([0-9]+)', $item_url.'\?comments-page=([0-9al]*)')))).'$', 'index.php?page=item&id=$1&comments-page=$2');
                        $rewrite->addRule('^([a-z]{2})_([A-Z]{2})/'. str_replace('{ITEM_CITY}', '.*', str_replace('{CATEGORIES}', '.*', str_replace('{ITEM_TITLE}', '.*', str_replace('{ITEM_ID}', '([0-9]+)', $item_url)))).'$', 'index.php?page=item&id=$3&lang=$1_$2');
                        $rewrite->addRule('^'. str_replace('{ITEM_CITY}', '.*', str_replace('{CATEGORIES}', '.*', str_replace('{ITEM_TITLE}', '.*', str_replace('{ITEM_ID}', '([0-9]+)', $item_url)))).'$', 'index.php?page=item&id=$1');

                        // User rules
                        $rewrite->addRule('^'.osc_get_preference('rewrite_user_login').'/?$', 'index.php?page=login');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_user_dashboard').'/?$', 'index.php?page=user&action=dashboard');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_user_logout').'/?$', 'index.php?page=main&action=logout');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_user_register').'/?$', 'index.php?page=register&action=register');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_user_activate').'/([0-9]+)/(.*?)/?$', 'index.php?page=register&action=validate&id=$1&code=$2');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_user_activate_alert').'/([0-9]+)/([a-zA-Z0-9]+)/(.+)$', 'index.php?page=user&action=activate_alert&id=$1&email=$3&secret=$2');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_user_profile').'/?$', 'index.php?page=user&action=profile');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_user_profile').'/([0-9]+)/?$', 'index.php?page=user&action=pub_profile&id=$1');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_user_profile').'/(.+)/?$', 'index.php?page=user&action=pub_profile&username=$1');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_user_items').'/?$', 'index.php?page=user&action=items');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_user_alerts').'/?$', 'index.php?page=user&action=alerts');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_user_recover').'/?$', 'index.php?page=login&action=recover');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_user_forgot').'/([0-9]+)/(.*)/?$', 'index.php?page=login&action=forgot&userId=$1&code=$2');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_user_change_password').'/?$', 'index.php?page=user&action=change_password');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_user_change_email').'/?$', 'index.php?page=user&action=change_email');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_user_change_username').'/?$', 'index.php?page=user&action=change_username');
                        $rewrite->addRule('^'.osc_get_preference('rewrite_user_change_email_confirm').'/([0-9]+)/(.*?)/?$', 'index.php?page=user&action=change_email_confirm&userId=$1&code=$2');

                        // Page rules
                        $pos_pID   = stripos($page_url, '{PAGE_ID}');
                        $pos_pSlug = stripos($page_url, '{PAGE_SLUG}');
                        $pID_pos   = 1;
                        $pSlug_pos = 1;
                        if( is_numeric($pos_pID) && is_numeric($pos_pSlug) ) {
                            // set the order of the parameters
                            if($pos_pID > $pos_pSlug) {
                                $pID_pos++;
                            } else {
                                $pSlug_pos++;
                            }

                            $rewrite->addRule('^' . str_replace('{PAGE_SLUG}', '([\p{L}\p{N}_\-,]+)', str_replace('{PAGE_ID}', '([0-9]+)', $page_url)) . '/?$', 'index.php?page=page&id=$' . $pID_pos . "&slug=$" . $pSlug_pos);
                            $rewrite->addRule('^([a-z]{2})_([A-Z]{2})/' . str_replace('{PAGE_SLUG}', '([\p{L}\p{N}_\-,]+)', str_replace('{PAGE_ID}', '([0-9]+)', $page_url)) . '/?$', 'index.php?page=page&lang=$1_$2&id=$' . ($pID_pos + 2) . '&slug=$' . ($pSlug_pos + 2) );
                        } else if( is_numeric($pos_pID) ) {
                            $rewrite->addRule('^' .  str_replace('{PAGE_ID}', '([0-9]+)', $page_url) . '/?$', 'index.php?page=page&id=$1');
                            $rewrite->addRule('^([a-z]{2})_([A-Z]{2})/' . str_replace('{PAGE_ID}', '([0-9]+)', $page_url) . '/?$', 'index.php?page=page&lang=$1_$2&id=$3' );
                        } else {
                            $rewrite->addRule('^' . str_replace('{PAGE_SLUG}', '([\p{L}\p{N}_\-,]+)', $page_url) . '/?$', 'index.php?page=page&slug=$1');
                            $rewrite->addRule('^([a-z]{2})_([A-Z]{2})/' . str_replace('{PAGE_SLUG}', '([\p{L}\p{N}_\-,]+)', $page_url) . '/?$', 'index.php?page=page&lang=$1_$2&slug=$3' );
                        }

                        // Clean archive files
                        $rewrite->addRule('^(.+?)\.php(.*)$', '$1.php$2');

                        // Category rules
                        $id_pos = stripos($item_url, '{CATEGORY_ID}');
                        $title_pos = stripos($item_url, '{CATEGORY_NAME}');
                        $cat_pos = stripos($item_url, '{CATEGORIES');
                        $param_pos = 1;
                        if($title_pos!==false && $id_pos>$title_pos) {
                            $param_pos++;
                        }
                        if($cat_pos!==false && $id_pos>$cat_pos) {
                            $param_pos++;
                        }
                        $rewrite->addRule('^'.str_replace('{CATEGORIES}', '(.+)', str_replace('{CATEGORY_NAME}', '([^/]+)', str_replace('{CATEGORY_ID}', '([0-9]+)', $cat_url))).'/([0-9]+)$', 'index.php?page=search&sCategory=$'.$param_pos.'&iPage=$'.($param_pos+1));
                        $rewrite->addRule('^'.str_replace('{CATEGORIES}', '(.+)', str_replace('{CATEGORY_NAME}', '([^/]+)', str_replace('{CATEGORY_ID}', '([0-9]+)', $cat_url))).'/?$', 'index.php?page=search&sCategory=$'.$param_pos);

                        $rewrite->addRule('^(.+)/([0-9]+)$', 'index.php?page=search&iPage=$2');
                        $rewrite->addRule('^(.+)$', 'index.php?page=search');

                        osc_run_hook("after_rewrite_rules", array(&$rewrite));

                        //Write rule to DB
                        $rewrite->setRules();

                        osc_set_preference('seo_url_search_prefix', rtrim(Params::getParam('seo_url_search_prefix'), '/'));

                        $msg_error = '<br/>'._m('All fields are required.')." ".sprintf(_mn('One field was not updated', '%s fields were not updated', $errors), $errors);
                        switch($status) {
                            case 1:
                                $msg  = _m("Permalinks structure updated");
                                if($errors>0) {
                                    $msg .= $msg_error;
                                    osc_add_flash_warning_message($msg, 'admin');
                                } else {
                                    osc_add_flash_ok_message($msg, 'admin');
                                }
                            break;
                            case 2:
                                $msg  = _m("Permalinks structure updated.");
                                $msg .= " ";
                                $msg .= _m("However, we can't check if Apache module <b>mod_rewrite</b> is loaded. If you experience some problems with the URLs, you should deactivate <em>Friendly URLs</em>");
                                if($errors>0) {
                                    $msg .= $msg_error;
                                }
                                osc_add_flash_warning_message($msg, 'admin');
                            break;
                            case 3:
                                $msg  = _m("File <b>.htaccess</b> couldn't be filled out with the right content.");
                                $msg .= " ";
                                $msg .= _m("Here's the content you have to add to the <b>.htaccess</b> file. If you can't create the file, please deactivate the <em>Friendly URLs</em> option.");
                                $msg .= "</p><pre>" . htmlentities($htaccess, ENT_COMPAT, "UTF-8") . '</pre><p>';
                                if($errors>0) {
                                    $msg .= $msg_error;
                                }
                                osc_add_flash_error_message($msg, 'admin');
                            break;
                            case 4:
                                $msg  = _m("File <b>.htaccess</b> couldn't be filled out with the right content.");
                                $msg .= " ";
                                $msg .= _m("Here's the content you have to add to the <b>.htaccess</b> file. If you can't create the file or experience some problems with the URLs, please deactivate the <em>Friendly URLs</em> option.");
                                $msg .= "</p><pre>" . htmlentities($htaccess, ENT_COMPAT, "UTF-8") . '</pre><p>';
                                if($errors>0) {
                                    $msg .= $msg_error;
                                }
                                osc_add_flash_error_message($msg, 'admin');
                            break;
                            case 5:
                                $warning = false;
                                if( file_exists($htaccess_file) ) {
                                    $htaccess_content = file_get_contents($htaccess_file);
                                    if($htaccess_content!=$htaccess) {
                                        $msg  = _m("File <b>.htaccess</b> already exists and was not modified.");
                                        $msg .= " ";
                                        $msg .= _m("Here's the content you have to add to the <b>.htaccess</b> file. If you can't modify the file or experience some problems with the URLs, please deactivate the <em>Friendly URLs</em> option.");
                                        $msg .= "</p><pre>" . htmlentities($htaccess, ENT_COMPAT, "UTF-8") . '</pre><p>';
                                        $warning = true;
                                    } else {
                                        $msg  = _m("Permalinks structure updated");
                                    }
                                }
                                if($errors>0) {
                                    $msg .= $msg_error;
                                }
                                if($errors>0 || $warning) {
                                    osc_add_flash_warning_message($msg, 'admin');
                                } else {
                                    osc_add_flash_ok_message($msg, 'admin');
                                }
                            break;
                        }
                    } else {
                        osc_set_preference('rewriteEnabled', 0);
                        osc_set_preference('mod_rewrite_loaded', 0);

                        $deleted = true;
                        if( file_exists($htaccess_file) ) {
                            $htaccess_content = file_get_contents($htaccess_file);
                            if($htaccess_content==$htaccess) {
                                $deleted = @unlink($htaccess_file);
                                $same_content = true;
                            } else {
                                $deleted = false;
                                $same_content = false;
                            }
                        }
                        if($deleted) {
                            osc_add_flash_ok_message(_m('Friendly URLs successfully deactivated'), 'admin');
                        } else {
                            if($same_content) {
                                osc_add_flash_warning_message(_m('Friendly URLs deactivated, but .htaccess file could not be deleted. Please, remove it manually'), 'admin');
                            } else {
                                osc_add_flash_warning_message(_m('Friendly URLs deactivated, but .htaccess file was modified outside Osclass and was not deleted'), 'admin');
                            }
                        }
                    }

                    $this->redirectTo( osc_admin_base_url(true) . '?page=settings&action=permalinks' );
                break;
            }
        }
    }

    // EOF: ./oc-admin/controller/settings/permalinks.php