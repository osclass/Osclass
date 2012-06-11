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

    //customize Head
    function customHead(){ ?>
        <script type="text/javascript">
            $(document).ready(function() {
            }) ;
        </script>
        <?php
    }
    osc_add_hook('admin_header','customHead');
    
    osc_add_hook('admin_page_header','customPageHeader');
    function customPageHeader(){ ?>
        <h1><?php _e('Users Settings') ; ?></h1>
    <?php
    }
?>
<?php osc_current_admin_theme_path( 'parts/header.php' ) ; ?>

<!-- settings form -->
    <h2 class="render-title"><?php _e('Users Settings') ; ?></h2>
    <form action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
        <input type="hidden" name="page" value="users" />
        <input type="hidden" name="action" value="settings_post" />
        <fieldset>
            <div class="form-horizontal">
                <div class="form-row">
                    <div class="form-label"> <?php _e('Settings') ; ?></div>
                    <div class="form-controls">
                        <label id="enabled_users" class="form-label-checkbox">
                            <input type="checkbox" name="enabled_users" <?php echo ( osc_users_enabled() ? 'checked="checked"' : '' ) ; ?> value="1" />
                            <?php _e('Users enabled') ; ?>
                        </label>
                    </div>
                    <div class="form-controls separate-top-medium">
                        <label id="enabled_user_registration">
                            <input type="checkbox" name="enabled_user_registration" <?php echo ( osc_user_registration_enabled() ? 'checked="checked"' : '' ) ; ?> value="1" />
                            <?php _e('Anyone can register') ; ?>
                        </label>
                    </div>
                    <div class="form-controls separate-top-medium">
                        <label id="enabled_user_validation">
                            <input type="checkbox" name="enabled_user_validation" <?php echo ( osc_user_validation_enabled() ? 'checked="checked"' : '' ) ; ?> value="1" />
                            <?php _e('Users need to validate their account') ; ?>
                        </label>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label"> <?php _e('Admin notifications') ; ?></div>
                    <div class="form-controls">
                        <label id="notify_new_user" class="form-label-checkbox">
                            <input type="checkbox" name="notify_new_user" <?php echo ( osc_notify_new_user() ? 'checked="checked"' : '' ) ; ?> value="1" />
                            <?php _e('When a new user is registered') ; ?>
                        </label>
                    </div>
                </div>
                <div class="form-actions">
                    <input type="submit" value="<?php echo osc_esc_html( __('Save changes') ) ; ?>" class="btn btn-submit" />
                </div>
            </div>
        </fieldset>
    </form>
<!-- /settings form -->
<?php osc_current_admin_theme_path( 'parts/footer.php' ) ; ?>                