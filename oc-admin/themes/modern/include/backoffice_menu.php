<?php
    /**
     * OSClass – software for creating and publishing online classified advertising platforms
     *
     * Copyright (C) 2010 OSCLASS
     *
     * This program is free software: you can redistribute it and/or modify it under the terms
     * of the GNU Affero General Public License as published by the Free Software Foundation,
     * either version 3 of the License, or (at your option) any later version.
     *
     * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
     * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
     * See the GNU Affero General Public License for more details.
     *
     * You should have received a copy of the GNU Affero General Public
     * License along with this program. If not, see <http://www.gnu.org/licenses/>.
     */
?>
<?php
$menuActive = array(
    'items' => false,
    'categories'=>false,
    'pages'=>false,
    'emails'=>false,
    'customfields'=>false,
    'appearance'=>false,
    'plugins'=>false,
    'languages'=>false,
    'settings'=>false,
    'tools'=>false,
    'users'=>false,
    'administrators'=>false,
    'stats'=>false,
    'xtramenu'=>false
    );
if(in_array(Params::getParam('page'),array('items','comments','media'))){
    $menuActive['items'] = 'current-menu-item';
}
if(in_array(Params::getParam('page'),array('categories'))){
    $menuActive['categories'] = 'current-menu-item';
}
if(in_array(Params::getParam('page'),array('pages'))){
    $menuActive['pages'] = 'current-menu-item';
}
if(in_array(Params::getParam('page'),array('emails'))){
    $menuActive['emails'] = 'current-menu-item';
}
if(in_array(Params::getParam('page'),array('cfields'))){
    $menuActive['customfields'] = 'current-menu-item';
}
if(in_array(Params::getParam('page'),array('appearance'))){
    $menuActive['appearance'] = 'current-menu-item';
}
if(in_array(Params::getParam('page'),array('plugins')) && Params::getParam('action') != 'render'){
    $menuActive['plugins'] = 'current-menu-item';
}
if(in_array(Params::getParam('page'),array('languages'))){
    $menuActive['languages'] = 'current-menu-item';
}
if(in_array(Params::getParam('page'),array('settings'))){
    $menuActive['settings'] = 'current-menu-item';
}
if(in_array(Params::getParam('page'),array('tools'))){
    $menuActive['tools'] = 'current-menu-item';
}
if(in_array(Params::getParam('page'),array('users'))){
    $menuActive['users'] = 'current-menu-item';
}
if(in_array(Params::getParam('page'),array('admins'))){
    $menuActive['administrators'] = 'current-menu-item';
}
if(in_array(Params::getParam('page'),array('stats'))){
    $menuActive['stats'] = 'current-menu-item';
}
if(in_array(Params::getParam('page'),array('plugins')) && Params::getParam('action') == 'render'){
    $menuActive['xtramenu'] = 'current-menu-item';
}
?>
<!-- menu -->
<div class="left" id="left-side">

    <ul class="oscmenu">
    <li id="menu_dash">
        <h3><a href="<?php echo osc_admin_base_url() ; ?>"><?php _e('Dashboard') ; ?></a></h3>
    </li>
    <li id="menu_items" class="<?php echo $menuActive['items']; ?>">
        <h3><a id="items" href="#"><?php _e('Items') ; ?></a></h3>
        <ul>
            <li><a id="items_manage" href="<?php echo osc_admin_base_url(true) ; ?>?page=items"><?php _e('Manage items') ; ?></a></li>
            <li><a id="items_new" href="<?php echo osc_admin_base_url(true) ; ?>?page=items&action=post"><?php _e('Add new') ; ?></a></li>
            <li><a id="items_comments" href="<?php echo osc_admin_base_url(true) ; ?>?page=comments"><?php _e('Comments') ; ?></a></li>
            <li><a id="items_media" href="<?php echo osc_admin_base_url(true) ; ?>?page=media"><?php _e('Manage media') ; ?></a></li>
            <li><a id="items_settings" href="<?php echo osc_admin_base_url(true) ; ?>?page=items&action=settings"><?php _e('Settings') ; ?></a></li>
        </ul>
    </li>
    <li id="menu_categories" class="<?php echo $menuActive['categories']; ?>">
        <h3><a id="categories" href="#"><?php _e('Categories') ; ?></a></h3>
        <ul>
            <li><a id="categories_manage" href="<?php echo osc_admin_base_url(true) ; ?>?page=categories"><?php _e('Manage categories') ; ?></a></li>
            <li><a id="categories_settings" href="<?php echo osc_admin_base_url(true) ; ?>?page=categories&action=settings"><?php _e('Settings') ; ?></a></li>
        </ul>
    </li>
    <li id="menu_pages" class="<?php echo $menuActive['pages']; ?>">
        <h3><a id="categories" href="#"><?php _e('Pages') ; ?></a></h3>
        <ul>
            <li><a id="pages_manage" href="<?php echo osc_admin_base_url(true) ; ?>?page=pages"><?php _e('Manage pages') ; ?></a></li>
            <li><a id="pages_new" href="<?php echo osc_admin_base_url(true) ; ?>?page=pages&action=add"><?php _e('Add new') ; ?></a></li>
        </ul>
    </li>
    <li id="menu_emails" class="<?php echo $menuActive['emails']; ?>">
        <h3><a id="emails" href="#"><?php _e('Emails & Alerts') ; ?></a></h3>
        <ul>
            <li><a id="emails_manage" href="<?php echo osc_admin_base_url(true) ; ?>?page=emails"><?php _e('Manage emails & alerts') ; ?></a></li>
        </ul>
    </li>
    <li id="menu_customfields" class="<?php echo $menuActive['customfields']; ?>">
        <h3><a id="categories" href="#"><?php _e('Custom Fields') ; ?></a></h3>
        <ul>
            <li><a id="fields_manage" href="<?php echo osc_admin_base_url(true) ; ?>?page=cfields"><?php _e('Manage custom fields') ; ?></a></li>
        </ul>
    </li>
    <li id="menu_appearance" class="<?php echo $menuActive['appearance']; ?>">
        <h3><a id="appearance" href="#"><?php _e('Appearance') ; ?></a></h3>
        <ul>
            <li><a id="appearance_manage" href="<?php echo osc_admin_base_url(true) ; ?>?page=appearance"><?php _e('Manage themes') ; ?></a></li>
            <li><a id="appearance_new" href="<?php echo osc_admin_base_url(true) ; ?>?page=appearance&action=add"><?php _e('Add new theme') ; ?></a></li>
            <li><a id="appearance_widgets" href="<?php echo osc_admin_base_url(true) ; ?>?page=appearance&action=widgets"><?php _e('Manage widgets') ; ?></a></li>
        </ul>
    </li>

    <li id="menu_plugins" class="<?php echo $menuActive['plugins']; ?>">
        <h3><a id="plugins" href="#"><?php _e('Plugins') ; ?></a></h3>
        <ul>
            <li><a id="plugins_manage" href="<?php echo osc_admin_base_url(true) ; ?>?page=plugins"><?php _e('Manage plugins') ; ?></a></li>
            <li><a id="plugins_new" href="<?php echo osc_admin_base_url(true) ; ?>?page=plugins&action=add"><?php _e('Add new plugin') ; ?></a></li>
        </ul>
    </li>

    <li id="menu_languages" class="<?php echo $menuActive['languages']; ?>">
        <h3><a id="languages" href="#"><?php _e('Languages') ; ?></a></h3>
        <ul>
            <li><a id="language_manage" href="<?php echo osc_admin_base_url(true) ; ?>?page=languages"><?php _e('Manage languages') ; ?></a></li>
            <li><a id="languages_new" href="<?php echo osc_admin_base_url(true) ; ?>?page=languages&action=add"><?php _e('Add a language') ; ?></a></li>
        </ul>
    </li>
    <li id="menu_settings" class="<?php echo $menuActive['settings']; ?>">
        <h3><a id="settings" href="#"><?php _e('Settings') ; ?></a></h3>
        <ul>
            <li><a id="settings_general" href="<?php echo osc_admin_base_url(true) ; ?>?page=settings"><?php _e('General') ; ?></a></li>
            <li><a id="settings_comments" href="<?php echo osc_admin_base_url(true) ; ?>?page=settings&action=comments"><?php _e('Comments') ; ?></a></li>
            <li><a id="settings_locations" href="<?php echo osc_admin_base_url(true) ; ?>?page=settings&action=locations"><?php _e('Locations') ; ?></a></li>
            <li><a id="settings_permalinks" href="<?php echo osc_admin_base_url(true) ; ?>?page=settings&action=permalinks"><?php _e('Permalinks') ; ?></a></li>
            <li><a id="settings_spambots" href="<?php echo osc_admin_base_url(true) ; ?>?page=settings&action=spamNbots"><?php _e('Spam and bots') ; ?></a></li>
            <li><a id="settings_currencies" href="<?php echo osc_admin_base_url(true) ; ?>?page=settings&action=currencies"><?php _e('Currencies') ; ?></a></li>
            <li><a id="settings_mailserver" href="<?php echo osc_admin_base_url(true) ; ?>?page=settings&action=mailserver"><?php _e('Mail server') ; ?></a></li>
            <li><a id="settings_media" href="<?php echo osc_admin_base_url(true) ; ?>?page=settings&action=media"><?php _e('Media') ; ?></a></li>
            <li><a id="settings_searches" href="<?php echo osc_admin_base_url(true) ; ?>?page=settings&action=latestsearches"><?php _e('Last searches') ; ?></a></li>
        </ul>
    </li>
    <li id="menu_tools" class="<?php echo $menuActive['tools']; ?>">
        <h3><a id="tools" href="#"><?php _e('Tools') ; ?></a></h3>
        <ul>
            <li><a id="tools_import" href="<?php echo osc_admin_base_url(true) ; ?>?page=tools&action=import"><?php _e('Import data') ; ?></a></li>
            <li><a id="tools_backup" href="<?php echo osc_admin_base_url(true) ; ?>?page=tools&action=backup"><?php _e('Backup data') ; ?></a></li>
            <li><a id="tools_upgrade" href="<?php echo osc_admin_base_url(true) ; ?>?page=tools&action=upgrade"><?php _e('Upgrade OSClass') ; ?></a></li>
            <li><a id="tools_location" href="<?php echo osc_admin_base_url(true); ?>?page=tools&action=locations"><?php _e('Location stats') ; ?></a></li>
            <li><a id="tools_category" href="<?php echo osc_admin_base_url(true); ?>?page=tools&action=category"><?php _e('Category stats') ; ?></a></li>
            <li><a id="tools_maintenance" href="<?php echo osc_admin_base_url(true); ?>?page=tools&action=maintenance"><?php _e('Maintenance mode') ; ?></a></li>
        </ul>
    </li>

    <li id="menu_users" class="<?php echo $menuActive['users']; ?>">
        <h3><a  id="users" href="#"><?php _e('Users') ; ?></a></h3>
        <ul>
            <li><a id="users_manage" href="<?php echo osc_admin_base_url(true); ?>?page=users"><?php _e('Manage users') ; ?></a></li>
            <li><a id="users_new" href="<?php echo osc_admin_base_url(true); ?>?page=users&action=create"><?php _e('Add new') ; ?></a></li>
            <li><a id="users_settings" href="<?php echo osc_admin_base_url(true); ?>?page=users&action=settings"><?php _e('Settings') ; ?></a></li>
        </ul>
    </li>
    <li id="menu_administrators" class="<?php echo $menuActive['administrators']; ?>">
        <h3><a id="administrators" href="#"><?php _e('Administrators') ; ?></a></h3>
        <ul>
            <li><a id="administrators_manage" href="<?php echo osc_admin_base_url(true) ; ?>?page=admins"><?php _e('Manage administrators') ; ?></a></li>
            <li><a id="administrators_new" href="<?php echo osc_admin_base_url(true) ; ?>?page=admins&action=add"><?php _e('Add new') ; ?></a></li>
            <li><a id="administrators_profile" href="<?php echo osc_admin_base_url(true) ; ?>?page=admins&action=edit"><?php _e('Your Profile') ; ?></a></li>
        </ul>
    </li>

    <li id="menu_stats" class="<?php echo $menuActive['stats']; ?>">
        <h3><a id="stats" href="#"><?php _e('Statistics') ; ?></a></h3>
        <ul>
            <li><a id="stats_users" href="<?php echo osc_admin_base_url(true) ; ?>?page=stats&action=users"><?php _e('Users') ; ?></a></li>
            <li><a id="stats_items" href="<?php echo osc_admin_base_url(true) ; ?>?page=stats&action=items"><?php _e('Items') ; ?></a></li>
            <li><a id="stats_comments" href="<?php echo osc_admin_base_url(true) ; ?>?page=stats&action=comments"><?php _e('Comments') ; ?></a></li>
            <li><a id="stats_reports" href="<?php echo osc_admin_base_url(true) ; ?>?page=stats&action=reports"><?php _e('Reports') ; ?></a></li>
        </ul>
    </li>
    <li id="menu_personal" class="<?php echo $menuActive['xtramenu']; ?>">
    <?php osc_run_hook('admin_menu') ; ?>
    </li>
    </ul>
    
</div>
<!-- /menu -->
