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

<!-- menu -->
<div id="left_column"> 
    <div style="padding-top: 9px;">
        <div style="float: left; padding-left: 5px; padding-top: 5px;">
            <img src="<?php echo osc_current_admin_theme_url('images/home_icon.gif') ; ?>" alt="" title="" />
        </div>
        <div style="float: left; padding-top: 5px; padding-left: 5px;">&raquo; <a href="<?php echo osc_admin_base_url(); ?>"><?php _e('Dashboard'); ?></a></div>
        <div style="clear: both;"></div>
        <div style="border-top: 1px solid #ccc; width: 99%;">&nbsp;</div>
    </div>

    <div id="menu">
        <h3>
            <a href="#"><?php _e('Items'); ?></a>
        </h3>
        <ul>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=items">&raquo; <?php _e('Manage items'); ?></a>
            </li>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=items&action=post">&raquo; <?php _e('Add new item'); ?></a>
            </li>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=comments">&raquo; <?php _e('Comments'); ?></a>
            </li>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=media">&raquo; <?php _e('Manage media'); ?></a>
            </li>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=items&action=settings">&raquo; <?php _e('Settings'); ?></a>
            </li>
        </ul>

        <h3>
            <a href="#"><?php _e('Categories'); ?></a>
        </h3>
        <ul>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=categories">&raquo; <?php _e('Manage categories'); ?></a>
            </li>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=categories&action=settings">&raquo; <?php _e('Settings'); ?></a>
            </li>
        </ul>

        <h3>
            <a href="#"><?php _e('Pages'); ?></a>
        </h3>
        <ul>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=pages">&raquo; <?php _e('Manage pages'); ?></a>
            </li>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=pages&action=add">&raquo; <?php _e('Create page'); ?></a>
            </li>
        </ul>
        <h3>
            <a href="#"><?php _e('Emails & Alerts'); ?></a>
        </h3>
        <ul>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=emails">&raquo; <?php _e('Manage emails & alerts'); ?></a>
            </li>
        </ul>
        <h3>
            <a href="#"><?php _e('Custom Fields'); ?></a>
        </h3>
        <ul>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=cfields">&raquo; <?php _e('Manage custom fields'); ?></a>
            </li>
        </ul>

        &nbsp;

        <h3>
            <a href="#"><?php _e('Appearance'); ?></a>
        </h3>
        <ul>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=appearance">&raquo; <?php _e('Manage themes'); ?></a>
            </li>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=appearance&action=add">&raquo; <?php _e('Add a new theme'); ?></a>
            </li>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=appearance&action=widgets">&raquo; <?php _e('Add or remove widgets'); ?></a>
            </li>
        </ul>

        <h3>
            <a href="#"><?php _e('Plugins'); ?></a>
        </h3>
        <ul>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=plugins">&raquo; <?php _e('Manage plugins'); ?></a>
            </li>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=plugins&action=add">&raquo; <?php _e('Add new plugin'); ?></a>
            </li>
        </ul>

        <h3>
            <a href="#"><?php _e('Languages'); ?></a>
        </h3>
        <ul>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=languages">&raquo; <?php _e('Manage languages'); ?></a>
            </li>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=languages&action=add">&raquo; <?php _e('Add a language'); ?></a>
            </li>
        </ul>

        <h3>
            <a href="#"><?php _e('General settings'); ?></a>
        </h3>
        <ul>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=settings">&raquo; <?php _e('General settings'); ?></a>
            </li>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=settings&action=comments">&raquo; <?php _e('Comments'); ?></a>
            </li>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=settings&action=contact">&raquo; <?php _e('Contact'); ?></a>
            </li>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=settings&action=locations">&raquo; <?php _e('Locations'); ?></a>
            </li>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=settings&action=permalinks">&raquo; <?php _e('Permalinks'); ?></a>
            </li>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=settings&action=spamNbots">&raquo; <?php _e('Spam and bots'); ?></a>
            </li>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=settings&action=currencies">&raquo; <?php _e('Currencies'); ?></a>
            </li>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=settings&action=mailserver">&raquo; <?php _e('Mail Server'); ?></a>
            </li>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=settings&action=media">&raquo; <?php _e('Media'); ?></a>
            </li>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=settings&action=cron">&raquo; <?php _e('Cron system'); ?></a>
            </li>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=settings&action=latestsearches">&raquo; <?php _e('Last Searches'); ?></a>
            </li>
        </ul>

        <h3>
            <a href="#"><?php _e('Tools'); ?></a>
        </h3>
        <ul>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=tools&action=import">&raquo; <?php _e('Import data'); ?></a>
            </li>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=tools&action=backup">&raquo; <?php _e('Backup data'); ?></a>
            </li>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=tools&action=upgrade">&raquo; <?php _e('Upgrade OSClass'); ?></a>
            </li>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=tools&action=images">&raquo; <?php _e('Regenerate thumbnails'); ?></a>
            </li>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=tools&action=maintenance">&raquo; <?php _e('Maintenance mode'); ?></a>
            </li>
        </ul>

        &nbsp;

        <?php osc_run_hook('admin_menu'); ?>

        &nbsp;

        <h3>
            <a href="#"><?php _e('Users'); ?></a>
        </h3>
        <ul>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=users">&raquo; <?php _e('Manage users'); ?></a>
            </li>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=users&action=create">&raquo; <?php _e('Add new user'); ?></a>
            </li>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=users&action=settings">&raquo; <?php _e('Settings'); ?></a>
            </li>
        </ul>

        <h3>
            <a href="#"><?php _e('Administrators'); ?></a>
        </h3>
        <ul>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=admins">&raquo; <?php _e('List administrators'); ?></a>
            </li>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=admins&action=add">&raquo; <?php _e('Add new administrator'); ?></a>
            </li>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=admins&action=edit">&raquo; <?php _e('Edit Your Profile'); ?></a>
            </li>
        </ul>

        <h3>
            <a href="#"><?php _e('Statistics'); ?></a>
        </h3>
        <ul>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=stats&action=users">&raquo; <?php _e('Users'); ?></a>
            </li>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=stats&action=items">&raquo; <?php _e('Items'); ?></a>
            </li>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=stats&action=comments">&raquo; <?php _e('Comments'); ?></a>
            </li>
            <li>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=stats&action=reports">&raquo; <?php _e('Reports'); ?></a>
            </li>
        </ul>

    </div>
</div>