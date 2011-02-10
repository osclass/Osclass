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

 <div id="header">
    <a id="logo" href="<?php echo osc_base_url() ; ?>"><strong><?php echo osc_page_title() ; ?></strong></a>
    <div id="user_menu">
        <ul>
            <?php if(osc_is_user_logged_in()) { ?>
            <li class="first logged">
                <?php _e('Hello ' . osc_userInfo('s_name') . '!') ; ?>  &middot;
                <?php // _e('Manage from here your'); ?>
                <strong><a href="<?php echo osc_user_account_url() ; ?>"><?php _e('My account'); ?></a></strong> &middot;
                <a href="<?php echo osc_user_logout_url() ; ?>"><?php _e('Logout'); ?></a>
            </li>
            <?php } else { ?>
            <li class="first">
                <a id="login_open" href="<?php echo osc_login_url(); ?>"><?php _e('Login'); ?></a>  &middot;
                <a href="<?php echo osc_createRegisterURL(); ?>"><?php _e('Register a free account'); ?></a>
                <form id="login" action="user.php" method="post">
                    <fieldset>
                        <input type="hidden" name="action" value="login_post" />
                        <label for="userName"><?php _e('User name'); ?></label>
                        <input type="text" name="userName" id="userName" />
                        <label for="password"><?php _e('Password'); ?></label>
                        <input type="password" name="password" id="password" />
                        <button type="submit"><?php _e('Login'); ?></button>
                    </fieldset>
                </form>
            </li>
            <?php } ?>
            <li class="last with_sub">
                <strong><?php _e("Language") ?></strong>
                <ul>
                    <?php $locales = Locale::newInstance()->listAllEnabled(); ?>
                    <?php $i = 0; foreach($locales as $locale): ?>
                        <li <?php if($i==0) { echo "class='first'"; } ?>><a id="<?php echo  $locale['pk_c_code'] ?>" href="<?php echo WEB_PATH; ?>/index.php?action=setlanguage&value=<?php echo $locale['pk_c_code']; ?>"><?php echo $locale['s_name']; ?></a></li>
                    <?php $i++; endforeach; ?>
                </ul>
            </li>
        </ul>
    </div>
</div>

<?php osc_showWidgets('header') ; ?>