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
<div class="left">
    <div class="dashboard">
        <p>
            <a href="<?php echo osc_admin_base_url() ; ?>"><?php _e('Dashboard') ; ?></a>
        </p>
    </div>

    <div class="menu">
        <h3>
            <a href="#"><?php _e('Items') ; ?></a>
        </h3>
        <ul>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true) ; ?>?page=items"><?php _e('Manage items') ; ?></a>
            </li>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true) ; ?>?page=items&action=post"><?php _e('Add new') ; ?></a>
            </li>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true) ; ?>?page=comments"><?php _e('Comments') ; ?></a>
            </li>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true) ; ?>?page=media"><?php _e('Manage media') ; ?></a>
            </li>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true) ; ?>?page=items&action=settings"><?php _e('Settings') ; ?></a>
            </li>
        </ul>

        <h3>
            <a href="#"><?php _e('Categories') ; ?></a>
        </h3>
        <ul>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true) ; ?>?page=categories"><?php _e('Manage categories') ; ?></a>
            </li>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true) ; ?>?page=categories&action=settings"><?php _e('Settings') ; ?></a>
            </li>
        </ul>

        <h3>
            <a href="#"><?php _e('Pages') ; ?></a>
        </h3>
        <ul>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true) ; ?>?page=pages"><?php _e('Manage pages') ; ?></a>
            </li>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true) ; ?>?page=pages&action=add"><?php _e('Add new') ; ?></a>
            </li>
        </ul>
        <h3>
            <a href="#"><?php _e('Emails & Alerts') ; ?></a>
        </h3>
        <ul>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true) ; ?>?page=emails"><?php _e('Manage emails & alerts') ; ?></a>
            </li>
        </ul>
        <h3>
            <a href="#"><?php _e('Custom Fields') ; ?></a>
        </h3>
        <ul>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true) ; ?>?page=cfields"><?php _e('Manage custom fields') ; ?></a>
            </li>
        </ul>

        &nbsp;

        <h3>
            <a href="#"><?php _e('Appearance') ; ?></a>
        </h3>
        <ul>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true) ; ?>?page=appearance"><?php _e('Manage themes') ; ?></a>
            </li>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true) ; ?>?page=appearance&action=add"><?php _e('Add new theme') ; ?></a>
            </li>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true) ; ?>?page=appearance&action=widgets"><?php _e('Manage widgets') ; ?></a>
            </li>
        </ul>

        <h3>
            <a href="#"><?php _e('Plugins') ; ?></a>
        </h3>
        <ul>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true) ; ?>?page=plugins"><?php _e('Manage plugins') ; ?></a>
            </li>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true) ; ?>?page=plugins&action=add"><?php _e('Add new plugin') ; ?></a>
            </li>
        </ul>

        <h3>
            <a href="#"><?php _e('Languages') ; ?></a>
        </h3>
        <ul>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true) ; ?>?page=languages"><?php _e('Manage languages') ; ?></a>
            </li>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true) ; ?>?page=languages&action=add"><?php _e('Add a language') ; ?></a>
            </li>
        </ul>

        <h3>
            <a href="#"><?php _e('Settings') ; ?></a>
        </h3>
        <ul>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true) ; ?>?page=settings"><?php _e('General') ; ?></a>
            </li>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true) ; ?>?page=settings&action=comments"><?php _e('Comments') ; ?></a>
            </li>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true) ; ?>?page=settings&action=locations"><?php _e('Locations') ; ?></a>
            </li>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true) ; ?>?page=settings&action=permalinks"><?php _e('Permalinks') ; ?></a>
            </li>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true) ; ?>?page=settings&action=spamNbots"><?php _e('Spam and bots') ; ?></a>
            </li>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true) ; ?>?page=settings&action=currencies"><?php _e('Currencies') ; ?></a>
            </li>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true) ; ?>?page=settings&action=mailserver"><?php _e('Mail server') ; ?></a>
            </li>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true) ; ?>?page=settings&action=media"><?php _e('Media') ; ?></a>
            </li>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true) ; ?>?page=settings&action=latestsearches"><?php _e('Last searches') ; ?></a>
            </li>
        </ul>

        <h3>
            <a href="#"><?php _e('Tools') ; ?></a>
        </h3>
        <ul>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true) ; ?>?page=tools&action=import"><?php _e('Import data') ; ?></a>
            </li>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true) ; ?>?page=tools&action=backup"><?php _e('Backup data') ; ?></a>
            </li>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true) ; ?>?page=tools&action=upgrade"><?php _e('Upgrade OSClass') ; ?></a>
            </li>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true); ?>?page=tools&action=locations"><?php _e('Location stats') ; ?></a>
            </li>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true); ?>?page=tools&action=maintenance"><?php _e('Maintenance mode') ; ?></a>
            </li>
        </ul>

        &nbsp;

        <?php osc_run_hook('admin_menu') ; ?>

        &nbsp;

        <h3>
            <a href="#"><?php _e('Users') ; ?></a>
        </h3>
        <ul>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true); ?>?page=users"><?php _e('Manage users') ; ?></a>
            </li>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true); ?>?page=users&action=create"><?php _e('Add new') ; ?></a>
            </li>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true); ?>?page=users&action=settings"><?php _e('Settings') ; ?></a>
            </li>
        </ul>

        <h3>
            <a href="#"><?php _e('Administrators') ; ?></a>
        </h3>
        <ul>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true) ; ?>?page=admins"><?php _e('Manage administrators') ; ?></a>
            </li>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true) ; ?>?page=admins&action=add"><?php _e('Add new') ; ?></a>
            </li>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true) ; ?>?page=admins&action=edit"><?php _e('Your Profile') ; ?></a>
            </li>
        </ul>

        <h3>
            <a href="#"><?php _e('Statistics') ; ?></a>
        </h3>
        <ul>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true) ; ?>?page=stats&action=users"><?php _e('Users') ; ?></a>
            </li>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true) ; ?>?page=stats&action=items"><?php _e('Items') ; ?></a>
            </li>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true) ; ?>?page=stats&action=comments"><?php _e('Comments') ; ?></a>
            </li>
            <li>
                &raquo; <a href="<?php echo osc_admin_base_url(true) ; ?>?page=stats&action=reports"><?php _e('Reports') ; ?></a>
            </li>
        </ul>
    </div>
</div>
<!-- /menu -->
