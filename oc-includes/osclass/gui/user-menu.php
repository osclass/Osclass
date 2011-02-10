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

<div class="content user_account">
        
    <h1><strong><?php _e('User account manager'); ?></strong></h1>

    <div id="sidebar">
        <ul class="user_menu" >
            <li><a href="<?php echo osc_createUserAccountURL() ; ?>" ><?php _e('Menu'); ?></a></li>
            <li><a href="<?php echo osc_createProfileURL() ; ?>" ><?php _e('Manage your profile'); ?></a></li>
            <li><a href="<?php echo osc_createUserItemsURL() ; ?>" ><?php _e('Manage your items'); ?></a></li>
            <li><a href="<?php echo osc_createUserAlertsURL() ; ?>" ><?php _e('Manage your alerts'); ?></a></li>
            <li><a href="<?php echo osc_user_logout_url() ; ?>" ><?php _e('Log-out'); ?></a></li>
        </ul>
    </div>
    
    <div id="main">
