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

    $aLocale = __get('aLocale') ;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()) ; ?>">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.validate.min.js') ; ?>"></script>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <?php LanguageForm::js_validation(); ?>
        <!-- container -->
        <div id="content">
            <?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>
            <!-- right container -->
            <div class="right">
                <div class="header_title">
                    <h1 class="languages"><?php _e('Edit language') ; ?></h1>
                </div>
                <?php osc_show_flash_message('admin') ; ?>
                <!-- language form -->
                <div class="languages">
                    <ul id="error_list" style="display: none;"></ul>
                    <form name="language_form" action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
                        <input type="hidden" name="page" value="languages" />
                        <input type="hidden" name="action" value="edit_post" />
                        <?php LanguageForm::primary_input_hidden($aLocale) ; ?>
                        <fieldset>
                            <div class="input-line">
                                <label><?php _e('Name') ; ?></label>
                                <div class="input medium">
                                    <?php LanguageForm::name_input_text($aLocale) ; ?>
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('Short name') ; ?></label>
                                <div class="input medium">
                                    <?php LanguageForm::short_name_input_text($aLocale) ; ?>
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('Description') ; ?></label>
                                <div class="input large">
                                    <?php LanguageForm::description_input_text($aLocale) ; ?>
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('Currency format') ; ?></label>
                                <div class="input large">
                                    <?php LanguageForm::currency_format_input_text($aLocale) ; ?>
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('Number of decimals') ; ?></label>
                                <div class="input micro">
                                    <?php LanguageForm::num_dec_input_text($aLocale) ; ?>
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('Decimal point') ; ?></label>
                                <div class="input micro">
                                    <?php LanguageForm::dec_point_input_text($aLocale) ; ?>
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('Thousands separator') ; ?></label>
                                <div class="input micro">
                                    <?php LanguageForm::thousands_sep_input_text($aLocale) ; ?>
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('Date format') ; ?></label>
                                <div class="input small">
                                    <?php LanguageForm::date_format_input_text($aLocale) ; ?>
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('Stopwords') ; ?></label>
                                <div class="input">
                                    <?php LanguageForm::description_textarea($aLocale) ; ?>
                                </div>
                            </div>
                            <div class="input-line">
                                <div class="input">
                                    <label class="checkbox">
                                        <?php LanguageForm::enabled_input_checkbox($aLocale); ?> <?php _e('Enabled for the public website') ; ?>
                                    </label>
                                    <label class="checkbox">
                                        <?php LanguageForm::enabled_bo_input_checkbox($aLocale); ?> <?php _e('Enabled for the backoffice (oc-admin)'); ?>
                                    </label>
                                </div>
                            </div>
                            <div class="actions">
                                <input type="submit" value="<?php echo osc_esc_html( __('Save changes') ) ; ?>" />
                            </div>
                        </fieldset>
                    </form>
                </div>
                <!-- /language form -->
            </div>
            <!-- /right container -->
        </div>
        <!-- /container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>
