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
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title><?php echo osc_page_title() ; ?> &raquo; <?php _e('Log in') ; ?></title>
        <script type="text/javascript" src="<?php echo osc_admin_base_url() ; ?>themes/modern/js/jquery.min.js"></script>
        <link type="text/css" href="style/backoffice_login.css" media="screen" rel="stylesheet" />
    </head>
    <body class="login">
        <div id="login">
            <h1>
                <a href="http://osclass.org/" title="OSClass">
                    <img src="images/osclass-logo.gif" border="0" title="" alt="" />
                </a>
            </h1>
            <?php osc_show_flash_message('admin') ; ?>
            <form name="loginform" id="loginform" action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
                <input type="hidden" name="page" value="login" />
                <input type="hidden" name="action" value="login_post" />
                <p>
                    <label>
                        <span><?php _e('Username') ; ?></span>
                        <input type="text" name="user" id="user_login" class="input" value="<?php if( defined('DEMO') ){ echo 'admin' ; } ?>" size="20" tabindex="10" />
                    </label>
                </p>
                <p>
                    <label>
                        <span><?php _e('Password') ; ?></span>
                        <input type="password" name="password" id="user_pass" class="input" value="<?php if( defined('DEMO') ) { echo 'admin' ; }?>" size="20" tabindex="20" />
                    </label>
                </p>
                <?php $locales = osc_all_enabled_locales_for_admin() ; ?>
                <?php if(count($locales) > 1) { ?>
                    <p>
                        <select name="locale" id="user_language">
                        <?php foreach($locales as $locale) { ?>
                            <option value="<?php echo $locale ['pk_c_code'] ; ?>" <?php if(osc_admin_language() == $locale['pk_c_code']) echo 'selected="selected"' ; ?>><?php echo $locale['s_short_name'] ; ?></option>
                        <?php } ?>
                        </select>
                    </p>
                <?php } else {?>
                    <input type="hidden" name="locale" value="<?php echo $locales[0]["pk_c_code"] ; ?>" />
                <?php } ?>
                <p class="forgetmenot">
                    <label>
                        <input name="remember" type="checkbox" id="remember" value="1" tabindex="90" /> <?php _e('Remember me') ; ?>
                    </label>
                        <a href="<?php echo osc_admin_base_url(true); ?>?page=login&amp;action=recover" title="<?php echo osc_esc_html( __('Forgot your password?')) ; ?>" class="forgot"><?php _e('Forgot your password?') ; ?></a>
                </p>
                <p class="submit">
                    <input type="submit" name="submit" id="submit" value="<?php echo osc_esc_html( __('Log in')) ; ?>" tabindex="100" />
                </p>
            </form>
            
        </div>
        <p id="backtoblog"><a href="<?php echo osc_base_url() ; ?>" title="<?php echo osc_esc_html( sprintf( __('Back to %s'), osc_page_title() )) ; ?>">&larr; <?php printf( __('Back to %s'), osc_page_title() ) ; ?></a></p>
        <script type="text/javascript">
            $(function(){
                function placeholder(input_form) {
                    input_form.each(function(){
                        $(this).focus(function(){
                            $(this).prev().hide();
                        }).blur(function(){
                            if($(this).val() == '') {
                                $(this).prev().show();
                            }
                        }).prev().click(function(){
                            $(this).hide().next().focus();
                        });
                        if($(this).val() != ''){
                            $(this).prev().hide();
                        }
                    });
                }

                placeholder($('#user_login, #user_pass')) ;
                setTimeout(function() {
                    placeholder($('#user_login, #user_pass'));
                }, '500') ;

                $(".ico-close").click(function(){
                    $(this).parent().hide();
                });
            });
        </script>
    </body>
</html>