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
</div><!-- content -->
<?php osc_run_hook('after-main'); ?>
</div>
<div id="responsive-trigger"></div>
<!-- footer -->
<div class="clear"></div>
<div id="footer">
    <div class="wrapper">
        <ul class="resp-toogle">
            <?php if( osc_users_enabled() ) { ?>
            <?php if( osc_is_web_user_logged_in() ) { ?>
                <li>
                    <?php echo sprintf(__('Hi %s', 'bender'), osc_logged_user_name() . '!'); ?>  &middot;
                    <strong><a href="<?php echo osc_user_dashboard_url(); ?>"><?php _e('My account', 'bender'); ?></a></strong> &middot;
                    <a href="<?php echo osc_user_logout_url(); ?>"><?php _e('Logout', 'bender'); ?></a>
                </li>
            <?php } else { ?>
                <li><a href="<?php echo osc_user_login_url(); ?>"><?php _e('Login', 'bender'); ?></a></li>
                <?php if(osc_user_registration_enabled()) { ?>
                    <li>
                        <a href="<?php echo osc_register_account_url(); ?>"><?php _e('Register for a free account', 'bender'); ?></a>
                    </li>
                <?php } ?>
            <?php } ?>
            <?php } ?>
            <?php if( osc_users_enabled() || ( !osc_users_enabled() && !osc_reg_user_post() )) { ?>
            <li class="publish">
                <a href="<?php echo osc_item_post_url_in_category(); ?>"><?php _e("Publish your ad for free", 'bender');?></a>
            </li>
            <?php } ?>
        </ul>
        <ul>
        <?php
        osc_reset_static_pages();
        while( osc_has_static_pages() ) { ?>
            <li>
                <a href="<?php echo osc_static_page_url(); ?>"><?php echo osc_static_page_title(); ?></a>
            </li>
        <?php
        }
        ?>
            <li>
                <a href="<?php echo osc_contact_url(); ?>"><?php _e('Contact', 'bender'); ?></a>
            </li>
        </ul>
        <?php
        if( osc_get_preference('footer_link', 'bender_theme') !== '0') {
            echo '<div>This website is proudly using the <a title="Osclass web" href="http://osclass.org/">classifieds scripts</a> software <strong>Osclass</strong></div>';
        }
        ?>
        <?php if ( osc_count_web_enabled_locales() > 1) { ?>
            <?php osc_goto_first_locale(); ?>
            <strong><?php _e('Language:', 'bender'); ?></strong>
            <?php $i = 0;  ?>
            <?php while ( osc_has_web_enabled_locales() ) { ?>
            <span><a id="<?php echo osc_locale_code(); ?>" href="<?php echo osc_change_language_url ( osc_locale_code() ); ?>"><?php echo osc_locale_name(); ?></a></span><?php if( $i == 0 ) { echo " &middot; "; } ?>
                <?php $i++; ?>
            <?php } ?>
        <?php } ?>
    </div>
</div>
<?php osc_run_hook('footer'); ?>
</body></html>
