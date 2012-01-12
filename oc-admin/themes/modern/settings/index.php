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

    $dateFormats = array('F j, Y', 'Y/m/d', 'm/d/Y', 'd/m/Y');
    $timeFormats = array('g:i a', 'g:i A', 'H:i');

    $aLanguages  = __get('aLanguages');
    $aCurrencies = __get('aCurrencies');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
        <script type="text/javascript">

            function validateInt(field,default_value) {
                var regExpr = /^\d*$/;
                if (!regExpr.test(field.value)) {
                  // Case of error
                  field.value = default_value;
                }
            }

        </script>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <div id="update_version" style="display:none;"></div>
        <div id="content">
            <div id="separator"></div>
            <?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>
            <div id="right_column">
                <div id="content_header" class="content_header">
                    <div style="float: left;">
                        <img src="<?php echo osc_current_admin_theme_url('images/settings-icon.png') ; ?>" alt="" title=""/>
                    </div>
                    <div id="content_header_arrow">&raquo; <?php _e('General settings') ; ?></div>
                    <div style="clear: both;"></div>
                </div>
                <div id="content_separator"></div>
                <?php osc_show_flash_message('admin'); ?>
                <!-- settings form -->
                <div id="settings_form" style="border: 1px solid #ccc; background: #eee;">
                    <div style="padding: 20px;">
                        <form action="<?php echo osc_admin_base_url(true); ?>" method="post">
                            <input type="hidden" name="page" value="settings" />
                            <input type="hidden" name="action" value="update" />
                            <div style="float: left; width: 50%;">
                                <fieldset>
                                    <legend><?php _e('Page title'); ?></legend>
                                    <input style="width: 95%; height: 20px; padding-left: 4px;" type="text" name="pageTitle" id="pageTitle" value="<?php echo osc_esc_html( osc_page_title() ); ?>" />
                                </fieldset>
                            </div>

                            <div style="float: left; width: 50%;">
                                <fieldset>
                                    <legend><?php _e('Page description'); ?></legend>
                                    <input style="width: 95%; height: 20px; padding-left: 4px;" type="text" name="pageDesc" id="pageDesc" value="<?php echo osc_esc_html(osc_page_description() ); ?>" />
                                </fieldset>
                            </div>

                            <div style="clear: both;"></div>

                            <div style="float: left; width: 50%;">
                                <fieldset>
                                    <legend><?php _e('Admin e-mail'); ?></legend>
                                    <input style="width: 95%; height: 20px; padding-left: 4px;" type="text" name="contactEmail" id="contactEmail" value="<?php echo osc_esc_html(osc_contact_email() ); ?>" />
                                </fieldset>
                            </div>

                            <div style="float: left; width: 50%;">
                                <fieldset>
                                    <legend><?php _e('Default language'); ?></legend>
                                    <select name="language" id="language">
                                        <?php foreach($aLanguages as $lang) { ?>
                                            <?php $sLanguage = osc_language(); ?>
                                            <option value="<?php echo $lang['pk_c_code'] ; ?>" <?php echo ((osc_language() == $lang['pk_c_code']) ? 'selected="selected"' : ''); ?>><?php echo $lang['s_name'] ; ?></option>
                                        <?php } ?>
                                    </select>
                                </fieldset>
                            </div>

                            <div style="clear: both;"></div>

                            <div style="float: left; width: 50%;">
                                <fieldset>
                                    <legend><?php _e('Date format'); ?></legend>
                                    <div style="font-size: small; margin: 0px;">
                                        <?php
                                            $custom_checked = true;
                                            foreach($dateFormats as $df) {
                                                $checked = false;
                                                if($df == osc_date_format()) {
                                                    $custom_checked = false;
                                                    $checked = true ;
                                                } ?>
                                                <input type="radio" name="df" id="<?php echo $df ; ?>" value="<?php echo $df ; ?>" <?php echo (($checked) ? 'checked="checked"' : ''); ?> onclick="javascript:document.getElementById('dateFormat').value = '<?php echo $df ; ?>' ;"/>
                                                <label for="<?php echo $df; ?>"><?php echo date($df); ?></label><br />
                                        <?php } ?>

                                        <input type="radio" name="df" id="df_custom" value="df_custom" <?php echo (($custom_checked) ? 'checked="checked"' : ''); ?> />
                                        <label for="df_custom"><?php _e('Custom') ; ?>:</label> <input type="text" <?php echo (($custom_checked) ? 'value="' . osc_esc_html(osc_date_format()) . '"' : ''); ?> onkeyup="javascript:document.getElementById('dateFormat').value = this.value;"/>
                                        <input type="hidden" name="dateFormat" id="dateFormat" value="<?php echo osc_date_format(); ?>" />
                                    </div>
                                </fieldset>
                            </div>

                            <div style="float: left; width: 50%;">
                                <fieldset>
                                    <legend><?php _e('Default currency'); ?></legend>
                                    <select name="currency" id="currency_admin">
                                        <?php $currentCurrency = osc_currency(); ?>
                                        <?php foreach($aCurrencies as $currency) { ?>
                                            <option value="<?php echo osc_esc_html($currency['pk_c_code']); ?>" <?php echo (($currentCurrency == $currency['pk_c_code']) ? 'selected="selected"' : ''); ?>><?php echo $currency['pk_c_code'] ?></option>
                                        <?php } ?>
                                    </select>
                                </fieldset>

                                <fieldset>
                                    <legend><?php _e('Week starts on'); ?></legend>
                                    <select name="weekStart" id="weekStart">
                                        <option value="0" <?php if(osc_week_starts_at() == '0') { ?>selected="selected"<?php } ?>><?php _e('Sunday'); ?></option>
                                        <option value="1" <?php if(osc_week_starts_at() == '1') { ?>selected="selected"<?php } ?>><?php _e('Monday') ; ?></option>
                                        <option value="2" <?php if(osc_week_starts_at() == '2') { ?>selected="selected"<?php } ?>><?php _e('Tuesday') ; ?></option>
                                        <option value="3" <?php if(osc_week_starts_at() == '3') { ?>selected="selected"<?php } ?>><?php _e('Wednesday') ; ?></option>
                                        <option value="4" <?php if(osc_week_starts_at() == '4') { ?>selected="selected"<?php } ?>><?php _e('Thursday') ; ?></option>
                                        <option value="5" <?php if(osc_week_starts_at() == '5') { ?>selected="selected"<?php } ?>><?php _e('Friday') ; ?></option>
                                        <option value="6" <?php if(osc_week_starts_at() == '6') { ?>selected="selected"<?php } ?>><?php _e('Saturday') ; ?></option>
                                    </select>
                                </fieldset>

                                <fieldset>
                                    <legend><?php _e('Timezone'); ?></legend>
                                    <?php require osc_lib_path() . 'osclass/timezones.php' ; ?>
                                    <select name="timezone" id="timezone">
                                        <?php $selected_tz = osc_timezone() ; ?>
                                        <option value="" selected="selected"><?php _e('Select a timezone...') ; ?></option>
                                        <?php foreach ($timezone as $tz) { ?>
                                        <option value="<?php echo $tz ; ?>" <?php if($selected_tz == $tz) { ?> selected="selected" <?php } ?>><?php echo $tz; ?></option>
                                        <?php } ?>
                                    </select>
                                </fieldset>
                            </div>

                            <div style="clear: both;"></div>

                            <div style="float: left; width: 50%;">
                                <fieldset>
                                    <legend><?php _e('Time format') ; ?></legend>
                                    <div style="font-size: small; margin: 0px;">
                                        <?php
                                            $custom_checked = true;
                                            foreach($timeFormats as $tf) {
                                                $checked = false;
                                                if($tf == osc_time_format()) {
                                                    $custom_checked = false;
                                                    $checked = true;
                                                } ?>
                                                <input type="radio" name="tf" id="<?php echo $tf ; ?>" value="<?php echo $tf; ?>" <?php echo (($checked) ? 'checked="checked"' : ''); ?> onclick="javascript:document.getElementById('timeFormat').value = '<?php echo $tf ; ?>' ;" />
                                                <label for="<?php echo $tf; ?>"><?php echo date($tf) ; ?></label>
                                                <br />
                                            <?php } ?>
                                        <input type="radio" name="tf" id="tf_custom" value="tf_custom" <?php echo (($custom_checked) ? 'checked="checked"' : ''); ?> />
                                        <label for="tf_custom"><?php _e('Custom') ; ?>:</label> <input type="text" <?php echo (($custom_checked) ? 'value="' . osc_esc_html(osc_time_format()) . '"' : ''); ?> onkeyup="javascript:document.getElementById('timeFormat').value = this.value;"/>
                                        <input type="hidden" name="timeFormat" id="timeFormat" value="<?php echo osc_esc_html(osc_time_format()); ?>" />
                                    </div>
                                </fieldset>
                            </div>

                            <div style="float: left; width: 50%;">
                                <fieldset>
                                    <legend><?php _e('Number of items in the RSS') ; ?></legend>
                                    <input type="text" id="num_rss_items" name="num_rss_items" value="<?php echo osc_esc_html(osc_num_rss_items()); ?>" onblur='validateInt(this,<?php echo osc_num_rss_items(); ?>)'/>
                                </fieldset>
                            </div>

                            <div style="float: left; width: 50%;">
                                <fieldset>
                                    <legend><?php _e('Number of recent items displayed at home') ; ?></legend>
                                    <input type="text" name="max_latest_items_at_home" id="max_latest_items_at_home" value="<?php echo osc_esc_html(osc_max_latest_items_at_home()); ?>" onblur='validateInt(this,<?php echo osc_max_latest_items_at_home(); ?>)'/>
                                </fieldset>
                            </div>

                            <div style="float: left; width: 50%;">
                                <fieldset>
                                    <legend><?php _e('Number of item displayed in search results') ; ?></legend>
                                    <input type="text" name="default_results_per_page" id="default_results_per_page" value="<?php echo osc_esc_html(osc_default_results_per_page_at_search()); ?>" onblur='validateInt(this,<?php echo osc_default_results_per_page_at_search(); ?>)'/>
                                </fieldset>
                            </div>

                            <div style="clear: both;"></div>

                            <input id="button_save" type="submit" value="<?php osc_esc_html(_e('Update') ); ?>" />
                        </form>
                    </div>
                </div>
            </div> <!-- end of right column -->
        </div><!-- end of container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>