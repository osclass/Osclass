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
     * Helper Menu Admin
     * @package OSClass
     * @subpackage Helpers
     * @author OSClass
     */
    
    /**
     * Draws menu with sections and subsections
     * @param type $aMenu 
     *
     * 
     * 
     */
    function osc_draw_admin_menu() 
    {
        $something_selected = false;
        
        $adminMenu = AdminMenu::newInstance() ;
        $aMenu = $adminMenu->get_array_menu() ;
        
        $url_actual = '?'.$_SERVER['QUERY_STRING'];
        
        $sMenu = '<!-- menu -->'.PHP_EOL ;
        $sMenu .= '<div class="left" id="left-side">'.PHP_EOL ;
        $sMenu .= '<ul class="oscmenu">'.PHP_EOL ;
        foreach($aMenu as $key => $value) {
            
            $aMenu_actions = array();
            $url = str_replace(osc_admin_base_url(true), '', $value[1] ) ;
            $url = str_replace(osc_admin_base_url(), '', $value[1] ) ;
            array_push($aMenu_actions, $url);
            
            $sSubmenu = "";
            if( array_key_exists('sub', $value) ) {
                // submenu
                $aSubmenu = $value['sub'] ;
                if($aSubmenu) {
                    $sSubmenu .= "<ul>".PHP_EOL;
                    foreach($aSubmenu as $aSub) {
                        // hardcoded
                        $url = str_replace(osc_admin_base_url(true), '', $aSub[1] ) ;
                        array_push($aMenu_actions, $url);
                        
                        $sSubmenu .= '<li><a id="'.$aSub[2].'" href="'.$aSub[1].'">'.$aSub[0].'</a></li>'.PHP_EOL ;
                    }
                    $sSubmenu .= "</ul>".PHP_EOL;
                }
            }
            
            $class = '';
            if(in_array($url_actual , $aMenu_actions)) {
                $class = 'current-menu-item';
                $something_selected = true;
            }
            
            $sMenu .= '<li id="menu_'.$value[2].'" class="'.$class.'">'.PHP_EOL ;
            $sMenu .= '<h3><a id="'.$value[2].'" href="'.$value[1].'">'.$value[0].'</a></h3>'.PHP_EOL ;
            $sMenu .= $sSubmenu;
        }
        $class = '';
        if(!$something_selected) $class = 'current-menu-item';
        $sMenu .= '<li id="menu_personal" class="'.$class.'">'.PHP_EOL ;
        
        // Remove hook admin_menu when osclass 4.0 be released
        // hack, compatibility with menu plugins.
        ob_start(); 
        osc_run_hook('admin_menu') ;
        $plugins_out = ob_get_contents();
        ob_end_clean();
        // -----------------------------------------------------
        
        $sMenu .= $plugins_out.PHP_EOL;
        $sMenu .= '</li>'.PHP_EOL ;
        $sMenu .= '</ul></div>'.PHP_EOL ;
        $sMenu .= '<!-- menu end -->'.PHP_EOL ;
        echo $sMenu;
    }
    
    /**
     * Add menu with id $id_menu, and $array information
     * 
     * @param type $array
     * @param type $id_menu 
     */
    function osc_add_admin_menu_page( $menu_title, $url, $menu_id, $icon_url = null, $capability = null , $position = null )
    {
        AdminMenu::newInstance()->add_menu($menu_title, $url, $menu_id, $icon_url = null, $capability, $position);
    }
    
    /**
     * Remove menu section with id $id_menu
     * @param type $id_menu 
     */
    function osc_remove_admin_menu_page($id_menu)
    {
        AdminMenu::newInstance()->remove_menu( $id_menu ) ;
    }
    
    /**
     * Add submenu under menu id $id_menu, with $array information
     * @param type $array
     * @param type $id_menu 
     */
    function osc_add_admin_submenu_page( $menu_id, $submenu_title, $url, $submenu_id, $capability = null, $icon_url = null )
    {
        AdminMenu::newInstance()->add_submenu( $menu_id, $submenu_title, $url, $submenu_id, $capability, $icon_url ) ;
    }
    
    /**
     * Remove submenu with id $id_submenu under menu id $id_menu
     * 
     * @param type $id_menu
     * @param type $id_submenu 
     */
    function osc_remove_admin_submenu_page( $id_menu, $id_submenu )
    {
        AdminMenu::newInstance()->remove_submenu( $id_menu, $id_submenu ) ;
    }
    
    /**
     * Add submenu into items menu page
     */
    function osc_admin_menu_items( $submenu_title, $url, $submenu_id, $capability = null, $icon_url = null )
    {
        AdminMenu::newInstance()->add_menu_items( $submenu_title, $url, $submenu_id, $capability, $icon_url) ;
    }
    
    /**
     * Add submenu into items menu page
     */
    function osc_admin_menu_categories( $submenu_title, $url, $submenu_id, $capability = null, $icon_url = null )
    {
        AdminMenu::newInstance()->add_menu_categories( $submenu_title, $url, $submenu_id, $capability, $icon_url) ;
    }
    
    /**
     * Add submenu into items menu page
     */
    function osc_admin_menu_pages( $submenu_title, $url, $submenu_id, $capability = null, $icon_url= null)
    {
        AdminMenu::newInstance()->add_menu_pages( $submenu_title, $url, $submenu_id, $capability, $icon_url) ;
    }
    
    /**
     * Add submenu into items menu page
     */
    function osc_admin_menu_appearance( $submenu_title, $url, $submenu_id, $capability = null, $icon_url = null )
    {
        AdminMenu::newInstance()->add_menu_appearance( $submenu_title, $url, $submenu_id, $capability, $icon_url) ;
    }
    
    /**
     * Add submenu into items menu page
     */
    function osc_admin_menu_plugins( $submenu_title, $url, $submenu_id, $capability = null, $icon_url = null )
    {
        AdminMenu::newInstance()->add_menu_plugins( $submenu_title, $url, $submenu_id, $capability, $icon_url) ;
    }
    
    /**
     * Add submenu into items menu page
     */
    function osc_admin_menu_settings( $submenu_title, $url, $submenu_id, $capability = null, $icon_url = null )
    {
        AdminMenu::newInstance()->add_menu_settings( $submenu_title, $url, $submenu_id, $capability, $icon_url) ;
    }
    
    /**
     * Add submenu into items menu page
     */
    function osc_admin_menu_tools( $submenu_title, $url, $submenu_id,$capability = null, $icon_url = null )
    {
        AdminMenu::newInstance()->add_menu_tools( $submenu_title, $url, $submenu_id, $capability, $icon_url) ;
    }
    
    /**
     * Add submenu into items menu page
     */
    function osc_admin_menu_users( $submenu_title, $url, $submenu_id, $capability = null, $icon_url = null )
    {
        AdminMenu::newInstance()->add_menu_users( $submenu_title, $url, $submenu_id, $capability, $icon_url) ;
    }
    
    /**
     * Add submenu into items menu page
     */
    function osc_admin_menu_stats( $submenu_title, $url, $submenu_id, $capability = null, $icon_url = null )
    {
        AdminMenu::newInstance()->add_menu_stats( $submenu_title, $url, $submenu_id, $capability, $icon_url) ;
    }
?>
