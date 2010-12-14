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
?>

<!-- menu -->
<div id="left_column"> 
    <div style="padding-top: 9px;">
        <div style="float: left; padding-left: 5px; padding-top: 5px;"><img src="<?php echo $current_theme ?>/images/back_office/home_icon.gif" /></div>
        <div style="float: left; padding-top: 5px; padding-left: 5px;">&raquo; <a href="main.php"><?php _e('Dashboard'); ?></a></div>
        <div style="clear: both;"></div>
        <div style="border-top: 1px solid #ccc; width: 99%;">&nbsp;</div>
    </div>

    <div id="menu">
        <h3>
            <a href="#"><?php _e('Items'); ?></a>
        </h3>
        <ul>
            <li>
                <a href="items.php">&raquo; <?php _e('Manage items'); ?></a>
            </li>
            <li><a href="items.php?action=post">&raquo; <?php _e('Add new item'); ?></a></li>
            <li>
                <a href="comments.php">&raquo; <?php _e('Comments'); ?></a>
            </li>
            <li>
                <a href="media.php">&raquo; <?php _e('Manage media'); ?></a>
            </li>
        </ul>

        <h3>
            <a href="#"><?php _e('Categories'); ?></a>
        </h3>
        <ul>
            <li>
                <a href="categories.php">&raquo; <?php _e('Manage categories'); ?></a>
            </li>
            <li>
                <a href="categories.php?action=add">&raquo; <?php _e('Add a new category'); ?></a>
            </li>
        </ul>

        <h3>
            <a href="#"><?php _e('Pages'); ?></a>
        </h3>
        <ul>
            <li>
                <a href="pages.php">&raquo; <?php _e('Manage pages'); ?></a>
            </li>
            <li>
                <a href="pages.php?action=add">&raquo; <?php _e('Create page'); ?></a>
            </li>
        </ul>

        &nbsp;

        <h3>
            <a href="#"><?php _e('Appearance'); ?></a>
        </h3>
        <ul>
            <li>
                <a href="appearance.php">&raquo; <?php _e('Manage themes'); ?></a>
            </li>
            <li>
                <a href="appearance.php?action=add">&raquo; <?php _e('Add a new theme'); ?></a>
            </li>
            <li>
                <a href="appearance.php?action=widgets">&raquo; <?php _e('Add or remove widgets'); ?></a>
            </li>
        </ul>

        <h3>
            <a href="#"><?php _e('Plugins'); ?></a>
        </h3>
        <ul>
            <li>
                <a href="plugins.php">&raquo; <?php _e('Manage plugins'); ?></a>
            </li>
            <li>
                <a href="plugins.php?action=add">&raquo; <?php _e('Add new plugin'); ?></a>
            </li>
        </ul>

        <h3>
            <a href="#"><?php _e('Languages'); ?></a>
        </h3>
        <ul>
            <li>
                <a href="languages.php">&raquo; <?php _e('Manage languages'); ?></a>
            </li>
            <li>
                <a href="languages.php?action=add">&raquo; <?php _e('Add a language'); ?></a>
            </li>
        </ul>

        <h3>
            <a href="#"><?php _e('General settings'); ?></a>
        </h3>
        <ul>
            <li>
                <a href="settings.php">&raquo; <?php _e('General settings'); ?></a>
            </li>
            <li>
                <a href="settings.php?action=items">&raquo; <?php _e('Items'); ?></a>
            </li>
            <li>
                <a href="settings.php?action=comments">&raquo; <?php _e('Comments'); ?></a>
            </li>
            <li>
                <a href="settings.php?action=users">&raquo; <?php _e('Users'); ?></a>
            </li>
            <li>
                <a href="settings.php?action=locations">&raquo; <?php _e('Locations'); ?></a>
            </li>
            <li>
                <a href="settings.php?action=permalinks">&raquo; <?php _e('Permalinks'); ?></a>
            </li>
            <li>
                <a href="settings.php?action=spamNbots">&raquo; <?php _e('Spam and bots'); ?></a>
            </li>
            <li>
                <a href="settings.php?action=currencies">&raquo; <?php _e('Currencies'); ?></a>
            </li>
            <li>
                <a href="settings.php?action=mailserver">&raquo; <?php _e('Mail Server'); ?></a>
            </li>
            <li>
                <a href="media.php?action=config">&raquo; <?php _e('Media'); ?></a>
            </li>
            <li>
                <a href="settings.php?action=cron">&raquo; <?php _e('Cron system'); ?></a>
            </li>
        </ul>

        <h3>
            <a href="#"><?php _e('Tools'); ?></a>
        </h3>
        <ul>
            <li>
                <a href="tools.php?action=import">&raquo; <?php _e('Import data'); ?></a>
            </li>
            <li>
                <a href="tools.php?action=backup">&raquo; <?php _e('Backup data'); ?></a>
            </li>
            <li>
                <a href="tools.php?action=upgrade">&raquo; <?php _e('Upgrade OSClass'); ?></a>
            </li>
        </ul>

        &nbsp;

        <?php osc_runHook('admin_menu'); ?>

        &nbsp;

        <h3>
            <a href="#"><?php _e('Users'); ?></a>
        </h3>
        <ul>
            <li>
                <a href="users.php">&raquo; <?php _e('Manage users'); ?></a>
            </li>
            <li>
                <a href="users.php?action=create">&raquo; <?php _e('Add new user'); ?></a>
            </li>
        </ul>

        <h3>
            <a href="#"><?php _e('Administrators'); ?></a>
        </h3>
        <ul>
            <li>
                <a href="admins.php">&raquo; <?php _e('List administrators') ; ?></a>
            </li>
            <li>
                <a href="admins.php?action=add">&raquo; <?php _e('Add new administrator'); ?></a>
            </li>
            <li>
                <a href="admins.php?action=edit">&raquo; <?php _e('Edit Your Profile'); ?></a>
            </li>
        </ul>
    </div>
</div>
