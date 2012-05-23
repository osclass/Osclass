<?php 
// draw admin menu 
osc_draw_admin_menu();
?>
<!--<div id="sidebar">
<ul class="oscmenu">
<li id="menu_dash" class="current"><h3><a id="dash" href="http://olar.com.br/oc-admin/"><div class="ico ico-48 ico-dashboard"></div>Dashboard</a></h3></li>
<li id="menu_items">
<h3><a id="items" href="#"><div class="ico ico-48 ico-listing"></div>Listing</a></h3>
<ul>
<li><a id="items_manage" href="http://olar.com.br/oc-admin/index.php?page=items">Manage Listings</a></li>
<li><a id="items_new" href="http://olar.com.br/oc-admin/index.php?page=items&action=post">Add new</a></li>
<li><a id="items_comments" href="http://olar.com.br/oc-admin/index.php?page=comments">Comments</a></li>
<li><a id="items_media" href="http://olar.com.br/oc-admin/index.php?page=media">Manage media</a></li>
<li><a id="items_cfields" href="http://olar.com.br/oc-admin/index.php?page=cfields">Manage custom fields</a></li>
<li><a id="items_settings" href="http://olar.com.br/oc-admin/index.php?page=items&action=settings">Settings</a></li>
<span class="arrow"></span>
</ul>
</li>
<li id="menu_categories">
<h3><a id="categories" href="#"><div class="ico ico-48 ico-categories"></div>Categories</a></h3>
<ul>
<li><a id="categories_manage" href="http://olar.com.br/oc-admin/index.php?page=categories">Manage categories</a></li>
<li><a id="categories_settings" href="http://olar.com.br/oc-admin/index.php?page=categories&action=settings">Settings</a></li>
<span class="arrow"></span>
</ul>
</li>
<li id="menu_pages">
<h3><a id="pages" href="#"><div class="ico ico-48 ico-pages"></div>Pages</a></h3>
<ul>
<li><a id="pages_manage" href="http://olar.com.br/oc-admin/index.php?page=pages">Manage pages</a></li>
<li><a id="pages_new" href="http://olar.com.br/oc-admin/index.php?page=pages&action=add">Add new</a></li>
<span class="arrow"></span>
</ul>
</li>
<li id="menu_appearance">
<h3><a id="appearance" href="#"><div class="ico ico-48 ico-appearance"></div>Appearance</a></h3>
<ul>
<li><a id="appearance_manage" href="http://olar.com.br/oc-admin/index.php?page=appearance">Manage themes</a></li>
<li><a id="appearance_new" href="http://olar.com.br/oc-admin/index.php?page=appearance&action=add">Add new theme</a></li>
<li><a id="appearance_widgets" href="http://olar.com.br/oc-admin/index.php?page=appearance&action=widgets">Manage widgets</a></li>
<span class="arrow"></span>
</ul>
</li>
<li id="menu_plugins">
<h3><a id="plugins" href="#"><div class="ico ico-48 ico-plugins"></div>Plugins</a></h3>
<ul>
<li><a id="plugins_manage" href="http://olar.com.br/oc-admin/index.php?page=plugins">Manage plugins</a></li>
<li><a id="plugins_new" href="http://olar.com.br/oc-admin/index.php?page=plugins&action=add">Add new plugin</a></li>
<span class="arrow"></span>
</ul>
</li>
<li id="menu_settings">
<h3><a id="settings" href="#"><div class="ico ico-48 ico-settings"></div>Settings</a></h3>
<ul>
<li><a id="settings_general" href="http://olar.com.br/oc-admin/index.php?page=settings">General</a></li>
<li><a id="settings_comments" href="http://olar.com.br/oc-admin/index.php?page=settings&action=comments">Comments</a></li>
<li><a id="settings_locations" href="http://olar.com.br/oc-admin/index.php?page=settings&action=locations">Locations</a></li>
<li><a id="settings_emails_manage" href="http://olar.com.br/oc-admin/index.php?page=emails">E-mail templates</a></li>
<li><a id="settings_language" href="http://olar.com.br/oc-admin/index.php?page=languages">Manage languages</a></li>
<li><a id="settings_language_new" href="http://olar.com.br/oc-admin/index.php?page=languages&action=add">Add a language</a></li>
<li><a id="settings_permalinks" href="http://olar.com.br/oc-admin/index.php?page=settings&action=permalinks">Permalinks</a></li>
<li><a id="settings_spambots" href="http://olar.com.br/oc-admin/index.php?page=settings&action=spamNbots">Spam and bots</a></li>
<li><a id="settings_currencies" href="http://olar.com.br/oc-admin/index.php?page=settings&action=currencies">Currencies</a></li>
<li><a id="settings_mailserver" href="http://olar.com.br/oc-admin/index.php?page=settings&action=mailserver">Mail server</a></li>
<li><a id="settings_media" href="http://olar.com.br/oc-admin/index.php?page=settings&action=media">Media</a></li>
<li><a id="settings_searches" href="http://olar.com.br/oc-admin/index.php?page=settings&action=latestsearches">Last searches</a></li>
<span class="arrow"></span>
</ul>
</li>
<li id="menu_tools">
<h3><a id="tools" href="#"><div class="ico ico-48 ico-tools"></div>Tools</a></h3>
<ul>
<li><a id="tools_import" href="http://olar.com.br/oc-admin/index.php?page=tools&action=import">Import data</a></li>
<li><a id="tools_backup" href="http://olar.com.br/oc-admin/index.php?page=tools&action=backup">Backup data</a></li>
<li><a id="tools_upgrade" href="http://olar.com.br/oc-admin/index.php?page=tools&action=upgrade">Upgrade OSClass</a></li>
<li><a id="tools_location" href="http://olar.com.br/oc-admin/index.php?page=tools&action=locations">Location stats</a></li>
<li><a id="tools_category" href="http://olar.com.br/oc-admin/index.php?page=tools&action=category">Category stats</a></li>
<li><a id="tools_maintenance" href="http://olar.com.br/oc-admin/index.php?page=tools&action=maintenance">Maintenance mode</a></li>
<span class="arrow"></span>
</ul>
</li>
<li id="menu_users">
<h3><a id="users" href="#"><div class="ico ico-48 ico-users"></div>Users</a></h3>
<ul>
<li><a id="users_administrators_manage" href="http://olar.com.br/oc-admin/index.php?page=admins">Manage administrators</a></li>
<li><a id="users_administrators_new" href="http://olar.com.br/oc-admin/index.php?page=admins&action=add">Add new administrator</a></li>
<li><a id="users_manage" href="http://olar.com.br/oc-admin/index.php?page=users">Manage users</a></li>
<li><a id="users_new" href="http://olar.com.br/oc-admin/index.php?page=users&action=create">Add new user</a></li>
<li><a id="users_settings" href="http://olar.com.br/oc-admin/index.php?page=users&action=settings">User settings</a></li>
<li><a id="users_administrators_profile" href="http://olar.com.br/oc-admin/index.php?page=admins&action=edit">Your Profile</a></li>
<span class="arrow"></span>
</ul>
</li>
<li id="menu_stats">
<h3><a id="stats" href="#"><div class="ico ico-48 ico-statistics"></div>Statistics</a></h3>
<ul>
<li><a id="stats_users" href="http://olar.com.br/oc-admin/index.php?page=stats&action=users">Users</a></li>
<li><a id="stats_items" href="http://olar.com.br/oc-admin/index.php?page=stats&action=items">Listings</a></li>
<li><a id="stats_comments" href="http://olar.com.br/oc-admin/index.php?page=stats&action=comments">Comments</a></li>
<li><a id="stats_reports" href="http://olar.com.br/oc-admin/index.php?page=stats&action=reports">Reports</a></li>
<span class="arrow"></span>
</ul>
</li>
</ul>
<div id="show-more">
	<h3><a id="stats" href="#"><div class="ico ico-48 ico-more"></div>Show more</a></h3>
	<ul id="hidden-menus">
	</ul>
</div>
</div>-->