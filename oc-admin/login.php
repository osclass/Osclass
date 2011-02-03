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

<?php
    $preferences = Preference::newInstance()->toArray();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title><?php echo __('OSClass Admin Panel Login') ?></title>
        <script type="text/javascript" src="<?php echo ABS_WEB_URL; ?>oc-includes/js/jquery.js"></script>
        <link type="text/css" href="style/backoffice_login.css" media="screen" rel="stylesheet" />
    </head>
    <body class="login">
        <div id="login">
            <h1><a href="<?php echo WEB_PATH; ?>" title="OSClass"><img src="images/osclass-logo.png" border="0"></a></h1>
            <?php osc_showFlashMessages(); ?>
            <form name="loginform" id="loginform" action="index.php" method="post">
                <input type="hidden" name="action" value="login_post" />
                <p>
                    <label>
                        <?php echo __('Username'); ?><br />
                        <input type="text" name="userName" id="user_login" class="input" value="" size="20" tabindex="10" />
                    </label>
                </p>
                <p>
                    <label>
                        <?php echo __('Password'); ?><br />
                        <input type="password" name="password" id="user_pass" class="input" value="" size="20" tabindex="20" />
                    </label>
                </p>
                <?php if(count($locales) > 1) {?>
                    <p>
                        <label><?php echo __('Language'); ?><br />
                            <select name="locale" id="user_language">
                                                            <option value="en_US" selected="selected">English</option>
                                                            <option>------------</option>
                                <?php foreach ($locales as $locale): ?>
                                    <option value="<?php echo $locale ['pk_c_code']; ?>" <?php if ($preferences['admin_language'] == $locale['pk_c_code'])
                                        echo 'selected="selected"'; ?>><?php echo $locale['s_short_name']; ?></option>
                                        <?php endforeach; ?>
                            </select>
                        </label>
                    </p>
                <?php } ?>
                <p>
                    <label><?php echo __('Theme'); ?><br />
                        <select name="theme" id="user_theme" disabled="disabled">
                            <option value="modern">Modern</option>
                        </select>
                    </label>
                </p>
                <p class="forgetmenot">
                    <label>
                        <input name="rememberMe" type="checkbox" id="rememberMe" value="forever" tabindex="90" /> <?php echo __('Remember me'); ?>
                    </label>
                </p>
                <p class="submit">
                    <input type="submit" name="submit" id="submit" value="<?php echo __('Log in'); ?>" tabindex="100" />
                </p>
            </form>

            <p id="nav">
                <a href="index.php?action=recover" title="<?php echo __('Lost your password?'); ?>"><?php echo __('Lost your password?'); ?></a>
            </p>
        </div>
        <p id="backtoblog"><a href="<?php echo WEB_PATH; ?>/" title="<?php echo __('Have you lost?') ?>">&larr; <?php echo __('Back to'); ?> <?php echo $preferences['pageTitle']; ?></a></p>
        <script type="text/javascript">
            try{
                document.getElementById('user_login').focus();
            }catch(e){}
        </script>

    </body>
</html>
