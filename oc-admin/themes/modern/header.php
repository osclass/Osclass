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
<div id="header">
    <div id="logo"><?php _e('OSClass'); ?></div>
    <div id="arrow">&raquo;</div>
    <div id="hostname"><?php echo osc_page_title() ; ?></div>
    <em id="visit_site"><a title="<?php _e('Visit website'); ?>" href="<?php echo osc_base_url() ; ?>" target="_blank"><?php _e('Visit website') ; ?></a><!-- &crarr; --></em>
    <div id="user_links"><?php _e('Hi') ; ?>, <a title="<?php _e('Your profile') ; ?>" href="<?php echo osc_admin_base_url(true) ; ?>?page=admins&action=edit"><?php echo osc_logged_admin_username() ; ?>!</a> | <a title="<?php _e('Log Out'); ?>" href="index.php?action=logout"><?php _e('Log Out'); ?></a></div>
    <?php osc_run_hook('admin_header') ; ?>
</div>
<div id="static"> <a style="text-decoration:none;" target="_blank" href="http://admin.osclass.org/feedback.php"><img style="border:0px;" alt="<?php _e('Feedback'); ?>" src="<?php echo  osc_current_admin_theme_url('images/feedback_button_new.gif') ; ?>"></a> </div>