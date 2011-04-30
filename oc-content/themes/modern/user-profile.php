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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <?php osc_current_web_theme_path('head.php') ; ?>
        <meta name="robots" content="noindex, nofollow" />
        <meta name="googlebot" content="noindex, nofollow" />
    </head>
    <body>
        <div class="container">
            <?php osc_current_web_theme_path('header.php') ; ?>
            <div class="content user_account">
                <h1>
                    <strong><?php _e('User account manager', 'modern') ; ?></strong>
                </h1>
                <div id="sidebar">
                    <?php echo osc_private_user_menu() ; ?>
                </div>
                <div id="main" class="modify_profile">
                    <h2><?php _e('Update your profile', 'modern') ; ?></h2>
                    <?php UserForm::location_javascript(); ?>
                    <form action="<?php echo osc_base_url(true) ; ?>" method="post">
                        <input type="hidden" name="page" value="user" />
                        <input type="hidden" name="action" value="profile_post" />
                        <fieldset>
                            <div class="row">
                                <label for="name"><?php _e('Name', 'modern') ; ?></label>
                                <?php UserForm::name_text(osc_user()) ; ?>
                            </div>
                            <div class="row">
                                <label for="email"><?php _e('E-mail', 'modern') ; ?></label>
                                <span class="update">
                                    <?php echo osc_user_email() ; ?><br />
                                    <a href="<?php echo osc_change_user_email_url() ; ?>"><?php _e('Modify e-mail', 'modern') ; ?></a> <a href="<?php echo osc_change_user_password_url() ; ?>" ><?php _e('Modify password', 'modern') ; ?></a>
                                </span>
                            </div>
                            <div class="row">
                                <label for="user_type"><?php _e('User type', 'modern') ; ?></label>
                                <?php UserForm::is_company_select(osc_user()) ; ?>
                            </div>
                            <div class="row">
                                <label for="phoneMobile"><?php _e('Cell phone', 'modern') ; ?></label>
                                <?php UserForm::mobile_text(osc_user()) ; ?>
                            </div>
                            <div class="row">
                                <label for="phoneLand"><?php _e('Phone', 'modern') ; ?></label>
                                <?php UserForm::phone_land_text(osc_user()) ; ?>
                            </div>
                            <div class="row">
                                <label for="country"><?php _e('Country', 'modern') ; ?> *</label>
                                <?php UserForm::country_select(osc_get_countries(), osc_user()) ; ?>
                            </div>
                            <div class="row">
                                <label for="region"><?php _e('Region', 'modern') ; ?> *</label>
                                <?php UserForm::region_select(osc_get_regions(), osc_user()) ; ?>
                            </div>
                            <div class="row">
                                <label for="city"><?php _e('City', 'modern') ; ?> *</label>
                                <?php UserForm::city_select(osc_get_cities(), osc_user()) ; ?>
                            </div>
                            <div class="row">
                                <label for="city_area"><?php _e('City area', 'modern') ; ?></label>
                                <?php UserForm::city_area_text(osc_user()) ; ?>
                            </div>
                            <div class="row">
                                <label for="address"><?php _e('Address', 'modern') ; ?></label>
                                <?php UserForm::address_text(osc_user()) ; ?>
                            </div>
                            <div class="row">
                                <label for="webSite"><?php _e('Website', 'modern') ; ?></label>
                                <?php UserForm::website_text(osc_user()) ; ?>
                            </div>
                            <div class="row">
                                <button type="submit"><?php _e('Update', 'modern') ; ?></button>
                            </div>
                            <?php osc_run_hook('user_form') ; ?>
                        </fieldset>
                    </form>
                </div>
            </div>
            <?php osc_current_web_theme_path('footer.php') ; ?>
        </div>
        <?php osc_show_flash_message() ; ?>
        <?php osc_run_hook('footer'); ?>
    </body>
</html>
