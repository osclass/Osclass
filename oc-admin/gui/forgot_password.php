<?php
/*
 * Copyright 2014 Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
?>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="robots" content="noindex, nofollow, noarchive"/>
        <meta name="googlebot" content="noindex, nofollow, noarchive"/>
        <title><?php echo osc_page_title(); ?> &raquo; <?php _e('Change your password'); ?></title>
        <script type="text/javascript" src="<?php echo osc_assets_url('js/jquery.min.js'); ?>"></script>
        <link type="text/css" href="style/backoffice_login.css" media="screen" rel="stylesheet"/>
        <?php osc_run_hook('admin_login_header'); ?>
    </head>
    <body class="forgot">
        <div id="login">
            <h1>
                <a href="<?php echo View::newInstance()->_get('login_admin_url'); ?>" title="<?php echo View::newInstance()->_get('login_admin_title'); ?>">
                    <img src="<?php echo View::newInstance()->_get('login_admin_image'); ?>" border="0" title="<?php echo View::newInstance()->_get('login_admin_title'); ?>" alt="<?php echo View::newInstance()->_get('login_admin_title'); ?>"/>
                </a>
            </h1>
            <?php osc_show_flash_message('admin'); ?>
            <div class="flashmessage">
                <?php _e('Type your new password'); ?>.
            </div>
            <form action="<?php echo osc_admin_base_url(true); ?>" method="post" >
                <input type="hidden" name="page" value="login"/>
                <input type="hidden" name="action" value="forgot_post"/>
                <input type="hidden" name="adminId" value="<?php echo Params::getParam('adminId', true); ?>"/>
                <input type="hidden" name="code" value="<?php echo Params::getParam('code', true); ?>"/>
                    <p>
                        <label for="new_password">
                            <span><?php _e('New password'); ?></span>
                            <input id="new_password" type="password" name="new_password" value="" autocomplete="off"/>
                        </label>
                    </p>
                    <p>
                        <label for="new_password2">
                            <span><?php _e('Repeat new password'); ?></span>
                            <input id="new_password2" type="password" name="new_password2" value="" autocomplete="off"/>
                        </label>
                    </p>
                    <p class="submit">
                        <input type="submit" name="submit" id="submit" value="<?php echo osc_esc_html( __('Change password')); ?>"/>
                    </p>
            </form>
            <p id="nav">
                <a title="<?php _e('Log in'); ?>" href="<?php echo osc_admin_base_url(); ?>"><?php _e('Log in'); ?></a>
            </p>
        </div>
        <p id="backtoblog"><a href="<?php echo osc_base_url(); ?>" title="<?php printf( __('Back to %s'), osc_page_title() ); ?>">&larr; <?php printf( __('Back to %s'), osc_page_title() ); ?></a></p>
        <script type="text/javascript">
            $(document).ready(function(){
                $('#new_password, #new_password2').focus(function(){
                        $(this).prev().hide();
                }).blur(function(){
                    if($(this).val() == '') {
                        $(this).prev().show();
                    }
                }).prev().click(function(){
                    $(this).hide();
                });

                $(".ico-close").click(function(){
                    $(this).parent().hide();
                });

                $("#new_password").focus();
            });
        </script>
    </body>
</html>
