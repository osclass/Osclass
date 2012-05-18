<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

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

    class AdminMenu
    {
        private static $instance ;
        private $aMenu ;
        public $aPages_action;

        public function __construct()
        {
            $this->aMenu         = array() ;
            $this->aPages_action = array() ; 
        }

        public static function newInstance()
        {
            if(!self::$instance instanceof self) {
                self::$instance = new self ;
            }
            return self::$instance ;
        }

        /**
         * Create 
         * syntax menu -> array( $)
         */
        public function init()
        {
            $this->aPages_action['dash'] = array();
            $this->add_menu( __('Dashboard'), osc_admin_base_url(), 'dash' );

            $this->aPages_action['items'] = array('items', 'comments', 'media', 'cfields') ;
            $this->add_menu( __('Listing'), '#', 'items' ) ; // array('items', 'comments', 'media', 'cfields')
            $this->add_submenu( 'items', __('Manage Listings'), osc_admin_base_url(true)."?page=items", 'items_manage') ;
            $this->add_submenu( 'items', __('Add new'), osc_admin_base_url(true)."?page=items&action=post", 'items_new') ;
            $this->add_submenu( 'items', __('Comments'), osc_admin_base_url(true)."?page=comments", 'items_comments') ;
            $this->add_submenu( 'items', __('Manage media'), osc_admin_base_url(true)."?page=media", 'items_media') ;
            $this->add_submenu( 'items', __('Manage custom fields'), osc_admin_base_url(true)."?page=cfields", 'items_cfields') ;
            $this->add_submenu( 'items', __('Settings'), osc_admin_base_url(true)."?page=items&action=settings", 'items_settings') ;
            
            $this->aPages_action['categories'] = array('categories') ;
            $this->add_menu( __('Categories'), '#', 'categories' ) ; //  array('categories')
            $this->add_submenu( 'categories', __('Manage categories'), osc_admin_base_url(true) .'?page=categories', 'categories_manage') ;
            $this->add_submenu( 'categories', __('Settings'), osc_admin_base_url(true) .'?page=categories&action=settings', 'categories_settings') ;

            $this->aPages_action['pages'] = array('pages') ;
            $this->add_menu( __('Pages'), '#', 'pages' ) ; // array('pages')
            $this->add_submenu( 'pages', __('Manage pages'), osc_admin_base_url(true) .'?page=pages', 'pages_manage') ;
            $this->add_submenu( 'pages',  __('Add new'), osc_admin_base_url(true) .'?page=pages&action=add', 'pages_new') ;
            
            $this->aPages_action['appearance'] = array('appearance') ;
            $this->add_menu( __('Appearance'), '#', 'appearance') ; // array('appearance') ;
            $this->add_submenu( 'appearance', __('Manage themes'), osc_admin_base_url(true) .'?page=appearance', 'appearance_manage') ;
            $this->add_submenu( 'appearance', __('Add new theme'), osc_admin_base_url(true) .'?page=appearance&action=add', 'appearance_new') ;
            $this->add_submenu( 'appearance', __('Manage widgets'), osc_admin_base_url(true) .'?page=appearance&action=widgets', 'appearance_widgets') ;
            
            $this->aPages_action['plugins'] = array('plugins') ;
            $this->add_menu(__('Plugins'), '#', 'plugins') ;  // array('plugins')
            $this->add_submenu( 'plugins', __('Manage plugins'), osc_admin_base_url(true) .'?page=plugins', 'plugins_manage') ;
            $this->add_submenu( 'plugins', __('Add new plugin'), osc_admin_base_url(true) .'?page=plugins&action=add', 'plugins_new') ;
            
            $this->aPages_action['settings'] = array('settings', 'languages', 'emails') ;
            $this->add_menu(__('Settings'), '#', 'settings') ; // array('settings', 'language', 'emails')
            $this->add_submenu( 'settings', __('General'), osc_admin_base_url(true) .'?page=settings', 'settings_general') ;
            $this->add_submenu( 'settings', __('Comments'), osc_admin_base_url(true) .'?page=settings&action=comments', 'settings_comments') ;
            $this->add_submenu( 'settings', __('Locations'), osc_admin_base_url(true) .'?page=settings&action=locations', 'settings_locations') ;
            $this->add_submenu( 'settings', __('E-mail templates'), osc_admin_base_url(true) .'?page=emails', 'settings_emails_manage') ;
            $this->add_submenu( 'settings', __('Manage languages'), osc_admin_base_url(true) .'?page=languages', 'settings_language') ;
            $this->add_submenu( 'settings', __('Add a language'), osc_admin_base_url(true) .'?page=languages&action=add', 'settings_language_new') ;
            $this->add_submenu( 'settings', __('Permalinks'), osc_admin_base_url(true) .'?page=settings&action=permalinks', 'settings_permalinks') ;
            $this->add_submenu( 'settings', __('Spam and bots'), osc_admin_base_url(true) .'?page=settings&action=spamNbots', 'settings_spambots') ;
            $this->add_submenu( 'settings', __('Currencies'), osc_admin_base_url(true) .'?page=settings&action=currencies', 'settings_currencies') ;
            $this->add_submenu( 'settings', __('Mail server'), osc_admin_base_url(true) .'?page=settings&action=mailserver', 'settings_mailserver') ;
            $this->add_submenu( 'settings', __('Media'), osc_admin_base_url(true) .'?page=settings&action=media', 'settings_media') ;
            $this->add_submenu( 'settings', __('Last searches'), osc_admin_base_url(true) .'?page=settings&action=latestsearches', 'settings_searches') ;
            
            $this->aPages_action['tools'] = array('tools');
            $this->add_menu( __('Tools'), '#', 'tools' ) ; //  array('tools')
            $this->add_submenu( 'tools', __('Import data'), osc_admin_base_url(true) .'?page=tools&action=import', 'tools_import') ;
            $this->add_submenu( 'tools', __('Backup data'), osc_admin_base_url(true) .'?page=tools&action=backup', 'tools_backup') ;
            $this->add_submenu( 'tools', __('Upgrade OSClass'), osc_admin_base_url(true) .'?page=tools&action=upgrade', 'tools_upgrade') ;
            $this->add_submenu( 'tools', __('Location stats'), osc_admin_base_url(true) .'?page=tools&action=locations', 'tools_location') ;
            $this->add_submenu( 'tools', __('Category stats'), osc_admin_base_url(true) .'?page=tools&action=category', 'tools_category') ;
            $this->add_submenu( 'tools', __('Maintenance mode'), osc_admin_base_url(true) .'?page=tools&action=maintenance', 'tools_maintenance') ;

            $this->aPages_action['users'] = array('users', 'admins');
            $this->add_menu( __('Users'), '#', 'users' ) ; // array('admins', 'users')
            $this->add_submenu( 'users', __('Manage administrators'), osc_admin_base_url(true) .'?page=admins', 'users_administrators_manage') ;
            $this->add_submenu( 'users', __('Add new administrator'), osc_admin_base_url(true) .'?page=admins&action=add', 'users_administrators_new') ;
            $this->add_submenu( 'users', __('Manage users'), osc_admin_base_url(true) .'?page=users', 'users_manage') ;
            $this->add_submenu( 'users', __('Add new user'), osc_admin_base_url(true) .'?page=users&action=create', 'users_new') ;
            $this->add_submenu( 'users', __('User settings'), osc_admin_base_url(true) .'?page=users&action=settings', 'users_settings') ;
            $this->add_submenu( 'users', __('Your Profile'), osc_admin_base_url(true) .'?page=admins&action=edit', 'users_administrators_profile') ;
            
            $this->aPages_action['stats'] = array('stats');
            $this->add_menu( __('Statistics'), '#', 'stats' );
            $this->add_submenu( 'stats', __('Users'), osc_admin_base_url(true) .'?page=stats&action=users', 'stats_users' ) ;
            $this->add_submenu( 'stats', __('Listings'), osc_admin_base_url(true) .'?page=stats&action=items', 'stats_items') ;
            $this->add_submenu( 'stats', __('Comments'), osc_admin_base_url(true) .'?page=stats&action=comments', 'stats_comments') ;
            $this->add_submenu( 'stats', __('Reports'), osc_admin_base_url(true) .'?page=stats&action=reports', 'stats_reports') ;
            
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
        public function add_menu($menu_title, $url, $menu_id, $icon_url = null, $capability = null , $position = null )
        {
            $array = array(
                $menu_title,
                $url,
                $menu_id,
                $icon_url,
                $capability,
                $position
            );
            $this->aMenu[$menu_id] = $array ;
        }
        
        /**
         * Remove menu and submenus under menu with id $id_menu
         * 
         * @param type $id_menu 
         */
        public function remove_menu( $menu_id ) 
        {
            unset( $this->aMenu[$menu_id] ) ;
        }
        
        /**
         * Add submenu under menu id $menu_id
         *
         * @param type $menu_id
         * @param type $submenu_title
         * @param type $url
         * @param type $id_submenu
         * @param type $capability
         * @param type $icon_url 
         */
        public function add_submenu( $menu_id, $submenu_title, $url, $submenu_id, $capability = null, $icon_url = null )
        {
            $array = array(
                $submenu_title,
                $url,
                $submenu_id,
                $menu_id,
                $capability,
                $icon_url
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
            unset( $this->aMenu[$menu_id]['sub'][$submenu_id] ) ;
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
        public function add_menu_items( $submenu_title, $url, $submenu_id, $capability = null, $icon_url = null  ) 
        {
            $aSubMenu= $this->add_submenu('items', $submenu_title, $url, $submenu_id, $capability, $icon_url) ;
        }
        
        public function add_menu_categories( $submenu_title, $url, $submenu_id, $capability = null, $icon_url = null  ) 
        {
            $aSubMenu= $this->add_submenu('categories', $submenu_title, $url, $submenu_id, $capability, $icon_url) ;
        }
        
        public function add_menu_pages( $submenu_title, $url, $submenu_id, $capability = null, $icon_url = null  ) 
        {
            $aSubMenu= $this->add_submenu('pages', $submenu_title, $url, $submenu_id, $capability, $icon_url) ;
        }
        
        public function add_menu_appearance( $submenu_title, $url, $submenu_id, $capability = null, $icon_url = null  ) 
        {
            $aSubMenu= $this->add_submenu('appearance', $submenu_title, $url, $submenu_id, $capability, $icon_url) ;
        }
        
        public function add_menu_plugins( $submenu_title, $url, $submenu_id, $capability = null, $icon_url = null  ) 
        {
            $aSubMenu= $this->add_submenu('plugins', $submenu_title, $url, $submenu_id, $capability, $icon_url) ;
        }
        
        public function add_menu_settings( $submenu_title, $url, $submenu_id, $capability = null, $icon_url = null  ) 
        {
            $aSubMenu= $this->add_submenu('settings', $submenu_title, $url, $submenu_id, $capability, $icon_url) ;
        }
        
        public function add_menu_tools( $submenu_title, $url, $submenu_id, $capability = null, $icon_url = null  ) 
        {
            $aSubMenu= $this->add_submenu('tools', $submenu_title, $url, $submenu_id, $capability, $icon_url) ;
        }
        
        public function add_menu_users( $submenu_title, $url, $submenu_id, $capability = null, $icon_url = null  ) 
        {
            $aSubMenu= $this->add_submenu('users', $submenu_title, $url, $submenu_id, $capability, $icon_url) ;
        }
        
        public function add_menu_stats( $submenu_title, $url, $submenu_id, $capability = null, $icon_url = null  ) 
        {
            $aSubMenu= $this->add_submenu('stats', $submenu_title, $url, $submenu_id, $capability, $icon_url) ;
        }
    }

?>