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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()) ; ?>">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.validate.min.js') ; ?>"></script>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <script type="text/javascript">
            $(document).ready(function(){
                // Code for form validation
                $("form[name=searches_form]").validate({
                    rules: {
                        custom_queries: {
                            required: true,
                            digits: true
                        }
                    },
                    messages: {
                        custom_queries: {
                            required: "<?php _e("Custom number: this field is required"); ?>.",
                            digits: "<?php _e("Custom number: this field has to be numeric only"); ?>."
                        }
                    },
                    wrapper: "li",
                        errorLabelContainer: "#error_list",
                        invalidHandler: function(form, validator) {
                            $('html,body').animate({ scrollTop: $('h1').offset().top }, { duration: 250, easing: 'swing'});
                        }
                });
            }) ;
        </script>
        <!-- container -->
        <div id="content">
            <?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>
            <!-- right container -->
            <div class="right">
                <div class="header_title">
                    <h1 class="settings"><?php _e('Last searches Settings') ; ?></h1>
                </div>
                <?php osc_show_admin_flash_messages() ; ?>
                <!-- latest searches form -->
                <div class="settings latest-searches">
                    <ul id="error_list"></ul>
                    <form name="searches_form" action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
                        <input type="hidden" name="page" value="settings" />
                        <input type="hidden" name="action" value="latestsearches_post" />
                        <fieldset>
                            <div class="input-line">
                                <label><?php _e('Latest searches') ; ?></label>
                                <div class="input">
                                    <label class="checkbox">
                                        <input type="checkbox" <?php echo ( osc_save_latest_searches() ) ? 'checked="true"' : '' ; ?> name="save_latest_searches" />
                                        <p class="inline"><?php _e('Save the last user searches') ; ?></p>
                                        <p class="help"><?php _e('It may be useful to know what queries users do.') ?></p>
                                    </label>
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('How long are stored the queries') ; ?></label>
                                <div class="input">
                                    <label class="radio">
                                        <input type="radio" name="purge_searches" value="hour" <?php echo ( ( osc_purge_latest_searches() == 'hour' ) ? 'checked="checked"' : '' ) ; ?> onclick="javascript:document.getElementById('customPurge').value = 'hour' ;" />
                                        <?php _e('One hour') ; ?>
                                    </label>
                                    <label class="radio">
                                        <input type="radio" name="purge_searches" value="day" <?php echo ( ( osc_purge_latest_searches() == 'day' ) ? 'checked="checked"' : '' ) ; ?> onclick="javascript:document.getElementById('customPurge').value = 'day' ;" />
                                        <?php _e('One day') ; ?>
                                    </label>
                                    <label class="radio">
                                        <input type="radio" name="purge_searches" value="week" <?php echo ( ( osc_purge_latest_searches() == 'week' ) ? 'checked="checked"' : '' ) ; ?> onclick="javascript:document.getElementById('customPurge').value = 'week' ;" />
                                        <?php _e('One week') ; ?>
                                    </label>
                                    <label class="radio">
                                        <input type="radio" name="purge_searches" value="forever" <?php echo ( ( osc_purge_latest_searches() == 'forever' ) ? 'checked="checked"' : '' ) ; ?> onclick="javascript:document.getElementById('customPurge').value = 'forever' ;" />
                                        <?php _e('Forever') ; ?>
                                    </label>
                                    <label class="radio">
                                        <input type="radio" name="purge_searches" value="1000" <?php echo ( ( osc_purge_latest_searches() == '1000' ) ? 'checked="checked"' : '' ) ; ?> onclick="javascript:document.getElementById('customPurge').value = '1000' ;" />
                                        <?php _e('Store 1000 queries') ; ?>
                                    </label>
                                    <label class="radio">
                                        <input type="radio" name="purge_searches" id="purge_searches" value="custom" <?php echo ( !in_array( osc_purge_latest_searches(), array('hour', 'day', 'week', 'forever', '1000') ) ? 'checked="checked"' : '' ) ; ?> />
                                        <?php printf( __('Store %s queries'), '<input name="custom_queries" type="text" class="small" ' . ( !in_array( osc_purge_latest_searches(), array('hour', 'day', 'week', 'forever', '1000') ) ? 'value="' . osc_esc_html( osc_purge_latest_searches() ) . '"' : '') . ' onkeyup="javascript:document.getElementById(\'customPurge\').value = this.value;"/>' ) ; ?>
                                        <p class="help">
                                            <?php _e("This feature can generate a lot of data. It's recommended to purge this data periodically.") ; ?>
                                        </p>
                                    </label>
                                    <input type="hidden" id="customPurge" name="customPurge" value="<?php echo osc_esc_html( osc_purge_latest_searches() ) ; ?>" />
                                </div>
                            </div>
                            <div class="actions">
                                <input type="submit" value="<?php echo osc_esc_html( __('Save changes') ) ; ?>" />
                            </div>
                        </fieldset>
                    </form>
                </div>
                <!-- /latest searches form -->
            </div>
            <!-- /right container -->
        </div>
        <!-- /container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>
