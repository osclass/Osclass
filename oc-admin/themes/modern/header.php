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
<!-- header -->
<div id="header">
    <div id="site">
        <a title="<?php _e('Visit website') ; ?>" href="<?php echo osc_base_url() ; ?>" target="_blank"><?php echo osc_page_title() ; ?></a>
    </div>
    <div class="user">
        <p><?php _e('Hi') ; ?>, <a title="<?php _e('Your profile') ; ?>" href="<?php echo osc_admin_base_url(true) ; ?>?page=admins&action=edit"><?php echo osc_logged_admin_username() ; ?>!</a> &nbsp;<a title="<?php _e('Dashboard') ; ?>" href="<?php echo osc_admin_base_url(false) ; ?>" class="splt-btn dashboard"><span><?php _e('Dashboar') ; ?></span></a><a title="<?php _e('Log Out') ; ?>" href="<?php echo osc_admin_base_url(true) ; ?>?action=logout" class="splt-btn logout"><span><?php _e('Sign out') ; ?></span></a></p>
    </div>
    <?php osc_run_hook('admin_header') ; ?>
</div>
<?php if ( ($json = osc_update_core_json()) != '' ) { ?>
<?php $json = json_decode($json) ; ?>
<div id="update_core">
    <?php printf(__('OSClass %s is available!'), $json->s_name) ; ?> <a href="<?php echo osc_admin_base_url(true) ; ?>?page=tools&action=upgrade"><?php _e('Please upgrade now') ; ?></a>
</div>
<?php } ?>
<!-- /header -->
