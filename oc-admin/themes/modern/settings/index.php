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

    $dateFormats = array('F j, Y', 'Y/m/d', 'm/d/Y', 'd/m/Y') ;
    $timeFormats = array('g:i a', 'g:i A', 'H:i') ;

    $aLanguages  = __get('aLanguages') ;
    $aCurrencies = __get('aCurrencies') ;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()) ; ?>">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <!-- container -->
        <div id="content">
            <?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>
            <!-- right container -->
            <div class="right">
                <div class="header_title">
                    <h1 class="settings"><?php _e('General Settings') ; ?></h1>
                </div>
                <?php osc_show_admin_flash_messages() ; ?>
                <!-- settings form -->
                <div class="settings general">
                    <form action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
                        <input type="hidden" name="page" value="settings" />
                        <input type="hidden" name="action" value="update" />
                        <fieldset>
                            <div class="input-line">
                                <label><?php _e('Page title') ; ?></label>
                                <div class="input">
                                    <input type="text" class="xlarge" name="pageTitle" value="<?php echo osc_esc_html( osc_page_title() ); ?>" />
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('Page description') ; ?></label>
                                <div class="input">
                                    <input type="text" class="xlarge" name="pageDesc" value="<?php echo osc_esc_html( osc_page_description() ); ?>" />
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('Admin e-mail') ; ?></label>
                                <div class="input">
                                    <input type="text" class="large" name="contactEmail" value="<?php echo osc_esc_html( osc_contact_email() ) ; ?>" />
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('Default language'); ?></label>
                                <div class="input">
                                    <select name="language">
                                        <?php foreach( $aLanguages as $lang ) { ?>
                                            <option value="<?php echo $lang['pk_c_code'] ; ?>" <?php echo ((osc_language() == $lang['pk_c_code']) ? 'selected="selected"' : '') ; ?>><?php echo $lang['s_name'] ; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('Default currency') ; ?></label>
                                <div class="input">
                                    <select name="currency" id="currency_admin">
                                        <?php foreach($aCurrencies as $currency) { ?>
                                            <option value="<?php echo osc_esc_html($currency['pk_c_code']); ?>" <?php echo ((osc_currency() == $currency['pk_c_code']) ? 'selected="selected"' : ''); ?>><?php echo $currency['pk_c_code'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('Week starts on') ; ?></label>
                                <div class="input">
                                    <select name="weekStart" id="weekStart">
                                        <option value="0" <?php if(osc_week_starts_at() == '0') { ?>selected="selected"<?php } ?>><?php _e('Sunday') ; ?></option>
                                        <option value="1" <?php if(osc_week_starts_at() == '1') { ?>selected="selected"<?php } ?>><?php _e('Monday') ; ?></option>
                                        <option value="2" <?php if(osc_week_starts_at() == '2') { ?>selected="selected"<?php } ?>><?php _e('Tuesday') ; ?></option>
                                        <option value="3" <?php if(osc_week_starts_at() == '3') { ?>selected="selected"<?php } ?>><?php _e('Wednesday') ; ?></option>
                                        <option value="4" <?php if(osc_week_starts_at() == '4') { ?>selected="selected"<?php } ?>><?php _e('Thursday') ; ?></option>
                                        <option value="5" <?php if(osc_week_starts_at() == '5') { ?>selected="selected"<?php } ?>><?php _e('Friday') ; ?></option>
                                        <option value="6" <?php if(osc_week_starts_at() == '6') { ?>selected="selected"<?php } ?>><?php _e('Saturday') ; ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('Timezone') ; ?></label>
                                <div class="input">
                                    <?php require osc_lib_path() . 'osclass/timezones.php' ; ?>
                                    <select name="timezone" id="timezone">
                                        <?php $selected_tz = osc_timezone() ; ?>
                                        <option value="" selected="selected"><?php _e('Select a timezone...') ; ?></option>
                                        <?php foreach ($timezone as $tz) { ?>
                                        <option value="<?php echo $tz ; ?>" <?php if($selected_tz == $tz) { ?> selected="selected" <?php } ?>><?php echo $tz; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('Date format'); ?></label>
                                <div class="input">
                                    <?php
                                        $custom_checked = true ;
                                        foreach( $dateFormats as $df ) {
                                            $checked = false ;
                                            if( $df == osc_date_format() ) {
                                                $custom_checked = false ;
                                                $checked        = true ;
                                            } ?>
                                            <label class="radio">
                                                <input type="radio" name="df" id="<?php echo $df ; ?>" value="<?php echo $df ; ?>" <?php echo ( $checked ? 'checked="checked"' : '' ) ; ?> onclick="javascript:document.getElementById('dateFormat').value = '<?php echo $df ; ?>' ;"/>
                                                <?php echo date($df) ; ?>
                                            </label>
                                    <?php } ?>
                                    <label class="radio">
                                        <input type="radio" name="df" id="df_custom" value="df_custom" <?php echo ( $custom_checked ? 'checked="checked"' : '' ) ; ?> />
                                        <input type="text" class="small" <?php echo ( $custom_checked ? 'value="' . osc_esc_html( osc_date_format() ) . '"' : '' ) ; ?> onkeyup="javascript:document.getElementById('dateFormat').value = this.value;"/>
                                    </label>
                                    <p class="help"><a href="http://php.net/date"><?php _e('Documentation on date and time formatting') ; ?></a></p>
                                    <input type="hidden" name="dateFormat" id="dateFormat" value="<?php echo osc_date_format() ; ?>" />
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('Time format') ; ?></label>
                                <div class="input">
                                    <?php
                                        $custom_checked = true ;
                                        foreach( $timeFormats as $tf ) {
                                            $checked = false ;
                                            if( $tf == osc_time_format() ) {
                                                $custom_checked = false ;
                                                $checked        = true ;
                                            } ?>
                                            <label class="radio">
                                                <input type="radio" name="tf" id="<?php echo $tf ; ?>" value="<?php echo $tf; ?>" <?php echo ( $checked ? 'checked="checked"' : '' ) ; ?> onclick="javascript:document.getElementById('timeFormat').value = '<?php echo $tf ; ?>' ;" />
                                                <?php echo date($tf) ; ?>
                                            </label>
                                    <?php } ?>
                                    <label class="radio">
                                        <input type="radio" name="tf" id="tf_custom" value="tf_custom" <?php echo ( $custom_checked ? 'checked="checked"' : '' ) ; ?> />
                                        <input type="text" class="small" <?php echo ( $custom_checked ? 'value="' . osc_esc_html( osc_time_format() ) . '"' : ''); ?> onkeyup="javascript:document.getElementById('timeFormat').value = this.value;"/>
                                    </label>
                                    <p class="help"><a href="http://php.net/date"><?php _e('Documentation on date and time formatting') ; ?></a></p>
                                    <input type="hidden" name="timeFormat" id="timeFormat" value="<?php echo osc_esc_html( osc_time_format() ) ; ?>" />
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('RSS shows') ; ?></label>
                                <div class="input">
                                    <input type="text" class="mini" name="num_rss_items" value="<?php echo osc_esc_html(osc_num_rss_items()); ?>" />
                                    <p class="inline"><?php _e('items at most') ; ?></p>
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('The search page shows') ; ?></label>
                                <div class="input">
                                    <input type="text" class="mini" name="default_results_per_page" value="<?php echo osc_esc_html(osc_default_results_per_page_at_search()); ?>" />
                                    <p class="inline"><?php _e('items at most') ; ?></p>
                                </div>
                            </div>
                            <div class="actions">
                                <input type="submit" value="<?php osc_esc_html( _e('Save changes') ) ; ?>" />
                            </div>
                        </fieldset>
                    </form>
                </div>
                <!-- /settings form -->
            </div>
            <!-- /right container -->
        </div>
        <!-- /container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>