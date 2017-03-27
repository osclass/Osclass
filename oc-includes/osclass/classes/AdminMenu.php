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

    /**
     * AdminMenu class
     *
     * @since 3.0
     * @package Osclass
     * @subpackage classes
     * @author Osclass
     */
    class AdminMenu
    {
        private static $instance;
        private $aMenu;

        public function __construct()
        {
            $this->aMenu = array();
        }

        public static function newInstance()
        {
            if(!self::$instance instanceof self) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        /**
         *  Initialize menu representation.
         */
        public function init()
        {
            $this->add_menu( __('Dashboard'), osc_admin_base_url(), 'dash', 'moderator');

            $this->add_menu( __('Market'), osc_admin_base_url(true) .'?page=market', 'market', 'administrator');
            $this->add_submenu( 'market', __('Themes'), osc_admin_base_url(true) .'?page=market&action=themes', 'market_view_themes', 'administrator');
            $this->add_submenu( 'market', __('Plugins'), osc_admin_base_url(true).'?page=market&action=plugins', 'market_view_plugins', 'administrator');
            $this->add_submenu( 'market', __('Languages'), osc_admin_base_url(true).'?page=market&action=languages', 'market_view_languages', 'administrator');

            $this->add_menu( __('Listings'), osc_admin_base_url(true).'?page=items', 'items', 'moderator');
            $this->add_submenu( 'items', __('Manage listings'), osc_admin_base_url(true).'?page=items', 'items_manage', 'moderator');
            $this->add_submenu( 'items', __('Reported listings'), osc_admin_base_url(true).'?page=items&action=items_reported', 'items_reported', 'moderator');
            $this->add_submenu( 'items', __('Manage media'), osc_admin_base_url(true).'?page=media', 'items_media', 'moderator');
            $this->add_submenu( 'items', __('Comments'), osc_admin_base_url(true).'?page=comments', 'items_comments', 'moderator');
            $this->add_submenu( 'items', __('Custom fields'), osc_admin_base_url(true).'?page=cfields', 'items_cfields', 'administrator');
            $this->add_submenu( 'items', __('Settings'), osc_admin_base_url(true).'?page=items&action=settings', 'items_settings', 'administrator');

            $this->add_menu( __('Appearance'), osc_admin_base_url(true) .'?page=appearance', 'appearance', 'administrator');
            $this->add_submenu( 'appearance', __('Market'), osc_admin_base_url(true).'?page=market&action=themes', 'appearance_market', 'administrator');
            $this->add_submenu( 'appearance', __('Manage themes'), osc_admin_base_url(true) .'?page=appearance', 'appearance_manage', 'administrator');
            $this->add_submenu( 'appearance', __('Manage widgets'), osc_admin_base_url(true) .'?page=appearance&action=widgets', 'appearance_widgets', 'administrator');

            $this->add_menu(__('Plugins'), osc_admin_base_url(true) .'?page=plugins', 'plugins', 'administrator');
            $this->add_submenu( 'plugins', __('Manage plugins'), osc_admin_base_url(true) .'?page=plugins', 'plugins_manage', 'administrator');
            $this->add_submenu( 'plugins', __('Market'), osc_admin_base_url(true).'?page=market&action=plugins', 'plugins_market', 'administrator');

            $this->add_menu( __('Statistics'), osc_admin_base_url(true) .'?page=stats&action=items', 'stats', 'moderator' );
            $this->add_submenu( 'stats', __('Listings'), osc_admin_base_url(true) .'?page=stats&action=items', 'stats_items', 'moderator');
            $this->add_submenu( 'stats', __('Reports'), osc_admin_base_url(true) .'?page=stats&action=reports', 'stats_reports', 'moderator');
            $this->add_submenu( 'stats', __('Users'), osc_admin_base_url(true) .'?page=stats&action=users', 'stats_users', 'moderator');
            $this->add_submenu( 'stats', __('Comments'), osc_admin_base_url(true) .'?page=stats&action=comments', 'stats_comments', 'moderator');

            $this->add_menu(__('Settings'), osc_admin_base_url(true) .'?page=settings', 'settings', 'administrator');
            $this->add_submenu( 'settings', __('General'), osc_admin_base_url(true) .'?page=settings', 'settings_general', 'administrator');
            $this->add_submenu( 'settings',__('Categories'), osc_admin_base_url(true) .'?page=categories', 'settings_categories', 'administrator');
            $this->add_submenu( 'settings', __('Comments'), osc_admin_base_url(true) .'?page=settings&action=comments', 'settings_comments', 'administrator');
            $this->add_submenu( 'settings', __('Locations'), osc_admin_base_url(true) .'?page=settings&action=locations', 'settings_locations', 'administrator');
            $this->add_submenu( 'settings', __('Email templates'), osc_admin_base_url(true) .'?page=emails', 'settings_emails_manage', 'administrator');
            $this->add_submenu( 'settings', __('Languages'), osc_admin_base_url(true) .'?page=languages', 'settings_language', 'administrator');
            $this->add_submenu( 'settings', __('Permalinks'), osc_admin_base_url(true) .'?page=settings&action=permalinks', 'settings_permalinks', 'administrator');
            $this->add_submenu( 'settings', __('Spam and bots'), osc_admin_base_url(true) .'?page=settings&action=spamNbots', 'settings_spambots', 'administrator');
            $this->add_submenu( 'settings', __('Currencies'), osc_admin_base_url(true) .'?page=settings&action=currencies', 'settings_currencies', 'administrator');
            $this->add_submenu( 'settings', __('Mail server'), osc_admin_base_url(true) .'?page=settings&action=mailserver', 'settings_mailserver', 'administrator');
            $this->add_submenu( 'settings', __('Media'), osc_admin_base_url(true) .'?page=settings&action=media', 'settings_media', 'administrator');
            $this->add_submenu( 'settings', __('Latest searches'), osc_admin_base_url(true) .'?page=settings&action=latestsearches', 'settings_searches', 'administrator');
            $this->add_submenu( 'settings', __('Advanced'), osc_admin_base_url(true) .'?page=settings&action=advanced', 'settings_advanced', 'administrator');

            $this->add_menu( __('Pages'), osc_admin_base_url(true) .'?page=pages', 'pages', 'administrator' );

            $this->add_menu( __('Users'), osc_admin_base_url(true) .'?page=users', 'users', 'moderator');
            $this->add_submenu( 'users', __('Users'), osc_admin_base_url(true) .'?page=users', 'users_manage', 'administrator');
            $this->add_submenu( 'users', __('User Settings'), osc_admin_base_url(true) .'?page=users&action=settings', 'users_settings', 'administrator');
            $this->add_submenu( 'users', __('Administrators'), osc_admin_base_url(true) .'?page=admins', 'users_administrators_manage', 'administrator');
            $this->add_submenu( 'users', __('Your Profile'), osc_admin_base_url(true) .'?page=admins&action=edit', 'users_administrators_profile', 'moderator');
            $this->add_submenu( 'users', __('Alerts'), osc_admin_base_url(true) .'?page=users&action=alerts', 'users_alerts', 'administrator');
            $this->add_submenu( 'users', __('Ban rules'), osc_admin_base_url(true) .'?page=users&action=ban', 'users_ban', 'administrator');

            $this->add_menu( __('Tools'), osc_admin_base_url(true) .'?page=tools&action=import', 'tools', 'administrator');
            $this->add_submenu( 'tools', __('Import data'), osc_admin_base_url(true) .'?page=tools&action=import', 'tools_import', 'administrator');
            $this->add_submenu( 'tools', __('Backup data'), osc_admin_base_url(true) .'?page=tools&action=backup', 'tools_backup', 'administrator');
            $this->add_submenu( 'tools', __('Upgrade Osclass'), osc_admin_base_url(true) .'?page=tools&action=upgrade', 'tools_upgrade', 'administrator');
            $this->add_submenu( 'tools', __('Location stats'), osc_admin_base_url(true) .'?page=tools&action=locations', 'tools_location', 'administrator');
            $this->add_submenu( 'tools', __('Category stats'), osc_admin_base_url(true) .'?page=tools&action=category', 'tools_category', 'administrator');
            $this->add_submenu( 'tools', __('Maintenance mode'), osc_admin_base_url(true) .'?page=tools&action=maintenance', 'tools_maintenance', 'administrator');
            osc_run_hook('admin_menu_init');
        }

        /**
         * Add menu entry
         *
         * @param type $menu_title
         * @param type $url
         * @param type $menu_id
         * @param type $icon_url   (unused)
         * @param type $capability (unused)
         * @param type $position   (unused)
         */
        public function add_menu($menu_title, $url, $menu_id, $capability = null ,$icon_url = null, $position = null )
        {
            $array = array(
                $menu_title,
                $url,
                $menu_id,
                $capability,
                $icon_url,
                $position
            );
            $this->aMenu[$menu_id] = $array;
        }

        /**
         * Remove menu and submenus under menu with id $id_menu
         *
         * @param type $id_menu
         */
        public function remove_menu( $menu_id )
        {
            unset( $this->aMenu[$menu_id] );
        }

        /**
         * Add submenu under menu id $menu_id
         *
         * @param type $menu_id
         * @param type $submenu_title
         * @param type $url
         * @param type $id_submenu
         * @param type $capability
         */
        public function add_submenu( $menu_id, $submenu_title, $url, $submenu_id, $capability = null)
        {
            $array = array(
                $submenu_title,
                $url,
                $submenu_id,
                $menu_id,
                $capability
            );
            $this->aMenu[$menu_id]['sub'][$submenu_id] = $array;
        }

        /**
         * Remove submenu with id $id_submenu under menu id $id_menu
         *
         * @param type $id_menu
         * @param type $id_submenu
         */
        public function remove_submenu( $menu_id, $submenu_id )
        {
            unset( $this->aMenu[$menu_id]['sub'][$submenu_id] );
        }

        /**
         * Add submenu under menu id $menu_id
         *
         * @param type $menu_id
         * @param type $submenu_title
         * @param type $id_submenu
         * @param type $capability
         * @since 3.1
         */
        public function add_submenu_divider( $menu_id, $submenu_title, $submenu_id, $capability = null)
        {
            $array = array(
                $submenu_title,
                "divider_" . $submenu_id,
                $menu_id,
                $capability
            );
            $this->aMenu[$menu_id]['sub']["divider_" . $submenu_id] = $array;
        }

        /**
         * Remove submenu with id $id_submenu under menu id $id_menu
         *
         * @param type $id_menu
         * @param type $id_submenu
         * @since 3.1
         */
        public function remove_submenu_divider( $menu_id, $submenu_id )
        {
            unset( $this->aMenu[$menu_id]['sub']["divider_" . $submenu_id] );
        }

        /**
         * Return menu as array
         *
         * @return type
         */
        public function get_array_menu()
        {
            return $this->aMenu;
        }

        // common functions
        public function add_menu_items( $submenu_title, $url, $submenu_id, $capability = null, $icon_url = null )
        {
            $this->add_submenu('items', $submenu_title, $url, $submenu_id, $capability, $icon_url);
        }

        public function add_menu_categories( $submenu_title, $url, $submenu_id, $capability = null, $icon_url = null )
        {
            $this->add_submenu('categories', $submenu_title, $url, $submenu_id, $capability, $icon_url);
        }

        public function add_menu_pages( $submenu_title, $url, $submenu_id, $capability = null, $icon_url = null )
        {
            $this->add_submenu('pages', $submenu_title, $url, $submenu_id, $capability, $icon_url);
        }

        public function add_menu_appearance( $submenu_title, $url, $submenu_id, $capability = null, $icon_url = null )
        {
            $this->add_submenu('appearance', $submenu_title, $url, $submenu_id, $capability, $icon_url);
        }

        public function add_menu_plugins( $submenu_title, $url, $submenu_id, $capability = null, $icon_url = null )
        {
            $this->add_submenu('plugins', $submenu_title, $url, $submenu_id, $capability, $icon_url);
        }

        public function add_menu_settings( $submenu_title, $url, $submenu_id, $capability = null, $icon_url = null )
        {
            $this->add_submenu('settings', $submenu_title, $url, $submenu_id, $capability, $icon_url);
        }

        public function add_menu_tools( $submenu_title, $url, $submenu_id, $capability = null, $icon_url = null )
        {
            $this->add_submenu('tools', $submenu_title, $url, $submenu_id, $capability, $icon_url);
        }

        public function add_menu_users( $submenu_title, $url, $submenu_id, $capability = null, $icon_url = null )
        {
            $this->add_submenu('users', $submenu_title, $url, $submenu_id, $capability, $icon_url);
        }

        public function add_menu_stats( $submenu_title, $url, $submenu_id, $capability = null, $icon_url = null )
        {
            $this->add_submenu('stats', $submenu_title, $url, $submenu_id, $capability, $icon_url);
        }

        /*
         * Empty the menu
         */
        public function clear_menu( )
        {
            $this->aMenu = array();
        }
    }

?>
