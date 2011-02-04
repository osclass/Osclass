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

<?php defined('ABS_PATH') or die(__('Invalid OSClass request.')); ?>

<?php
    $dateFormats = array('F j, Y', 'Y/m/d', 'm/d/Y', 'd/m/Y') ;
    $timeFormats = array('g:i a', 'g:i A', 'H:i') ;
?>

<div id="content">
    <div id="separator"></div>

    <?php include_once $absolute_path . '/include/backoffice_menu.php'; ?>

    <div id="right_column">
        <div id="content_header" class="content_header">
            <div style="float: left;"><img src="<?php echo $current_theme ; ?>/images/back_office/settings-icon.png" /></div>
            <div id="content_header_arrow">&raquo; <?php _e('General settings') ; ?></div>
            <div style="clear: both;"></div>
        </div>
        
        <div id="content_separator"></div>
        
        <?php osc_showFlashMessages() ; ?>
        
        <!-- settings form -->
        <div id="settings_form" style="border: 1px solid #ccc; background: #eee;">
            <div style="padding: 20px;">
                <form action="settings.php" method="post">
                    <input type="hidden" name="action" value="update" />
                    <div style="float: left; width: 50%;">
                        <fieldset>
                            <legend><?php _e('Page title'); ?></legend>
                            <input style="width: 95%; height: 20px; padding-left: 4px;" type="text" name="pageTitle" id="pageTitle" value="<?php echo osc_page_title() ; ?>" />
                        </fieldset>
                    </div>

                    <div style="float: left; width: 50%;">
                        <fieldset>
                            <legend><?php _e('Page description'); ?></legend>
                            <input style="width: 95%; height: 20px; padding-left: 4px;" type="text" name="pageDesc" id="pageDesc" value="<?php echo osc_page_description() ; ?>" />
                        </fieldset>
                    </div>

                    <div style="clear: both;"></div>

                    <div style="float: left; width: 50%;">
                        <fieldset>
                            <legend><?php _e('Administrator E-mail'); ?></legend>
                            <input style="width: 95%; height: 20px; padding-left: 4px;" type="text" name="contactEmail" id="contactEmail" value="<?php echo osc_contact_email() ; ?>" />
                        </fieldset>
                    </div>

                    <div style="float: left; width: 50%;">
                        <fieldset>
                            <legend><?php _e('Default language'); ?></legend>
                            <select name="language" id="language">
                                <?php foreach($languages as $lang) { ?>
                                    <?php $sLanguage = osc_language(); ?>
                                    <option value="<?php echo $lang['pk_c_code'] ; ?>" <?php echo (osc_language() == $lang['pk_c_code']) ? 'selected="selected"' : '' ; ?>><?php echo $lang['s_name'] ; ?></option>
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
                                    $checked = false;
                                    foreach($dateFormats as $df) {
                                        if($df == osc_date_format()) {
                                            $checked = true ;
                                        } ?>
                                        <input type="radio" name="df" id="<?php echo $df ; ?>" value="<?php echo $df ; ?>" <?php echo ($checked) ? 'checked="checked"' : '' ; ?> onclick="javascript:document.getElementById('dateFormat').value = '<?php echo $df ; ?>' ;"/>
                                        <label for="<?php echo $df; ?>"><?php echo date($df); ?></label><br />
                                <?php } ?>

                                <input type="radio" name="df" id="df_custom" value="-" <?php echo (!$checked) ? 'checked="checked"' : '' ; ?> />
                                <label for="df_custom"><?php _e('Custom') ; ?>:</label> <input type="text" name="dateFormat" id="dateFormat" value="<?php echo osc_date_format() ; ?>" />
                            </div>
                        </fieldset>
                    </div>

                    <div style="float: left; width: 50%;">
                        <fieldset>
                            <legend><?php _e('Default currency'); ?></legend>
                            <select name="currency" id="weekStart">
                                <?php foreach($aCurrencies as $currency) { ?>
                                    <option value="<?php echo $currency['pk_c_code'] ?>" <?php if( osc_currency() == $currency['pk_c_code'] ) { ?>selected="selected"<?php } ?>><?php echo $currency['pk_c_code'] ?></option>
                                <?php } ?>
                            </select>
                        </fieldset>
                        
                        <fieldset>
                            <legend><?php _e('Week starts on'); ?></legend>
                            <select name="weekStart" id="weekStart">
                                <option value="0" selected="selected"><?php _e('Sunday'); ?></option>
                                <option value="1" <?php if(osc_week_starts_at() == '1') { ?>selected="selected"<?php } ?>><?php _e('Monday') ; ?></option>
                                <option value="2" <?php if(osc_week_starts_at() == '2') { ?>selected="selected"<?php } ?>><?php _e('Tuesday') ; ?></option>
                                <option value="3" <?php if(osc_week_starts_at() == '3') { ?>selected="selected"<?php } ?>><?php _e('Wednesday') ; ?></option>
                                <option value="4" <?php if(osc_week_starts_at() == '4') { ?>selected="selected"<?php } ?>><?php _e('Thursday') ; ?></option>
                                <option value="5" <?php if(osc_week_starts_at() == '5') { ?>selected="selected"<?php } ?>><?php _e('Friday') ; ?></option>
                                <option value="6" <?php if(osc_week_starts_at() == '6') { ?>selected="selected"<?php } ?>><?php _e('Saturday') ; ?></option>
                            </select>
                        </fieldset>
                    </div>

                    <div style="clear: both;"></div>

                    <div style="float: left; width: 50%;">
                        <fieldset>
                            <legend><?php _e('Time format') ; ?></legend>
                            <label for="timeFormat"><?php _e('Time format') ; ?></label><br />
                            <div style="font-size: small; margin: 0px;">
                                <?php
                                    $checked = false;
                                    foreach($timeFormats as $tf) {
                                        if($tf == osc_time_format()) {
                                            $checked = true;
                                        } ?>
                                        <input type="radio" name="tf" id="<?php echo $tf ; ?>" value="<?php echo $tf; ?>" <?php echo ($checked) ? 'checked="checked"' : '' ; ?> onclick="javascript:document.getElementById('timeFormat').value = '<?php echo $tf ; ?>' ;" />
                                        <label for="<?php echo $tf; ?>"><?php echo date($tf) ; ?></label>
                                        <br />
                                    <?php } ?>
                                <input type="radio" name="tf" id="tf_custom" value="-" <?php echo (!$checked) ? 'checked="checked"' : '' ; ?> />
                                <label for="tf_custom"><?php _e('Custom') ; ?>:</label> <input type="text" name="timeFormat" id="timeFormat" value="<?php echo osc_time_format() ; ?>" />
                            </div>
                        </fieldset>
                    </div>

                    <div style="float: left; width: 50%;">
                        <fieldset>
                            <legend><?php _e('Number of items in the RSS') ; ?></legend>
                            <select name="num_rss_items" id="num_rss_items">
                                <option value="10" <?php echo (osc_num_rss_items() == '10') ? 'selected="selected"' : '' ; ?>>10</option>
                                <option value="25" <?php echo (osc_num_rss_items() == '25') ? 'selected="selected"' : '' ; ?>>25</option>
                                <option value="50" <?php echo (osc_num_rss_items() == '50') ? 'selected="selected"' : '' ; ?>>50</option>
                                <option value="75" <?php echo (osc_num_rss_items() == '75') ? 'selected="selected"' : '' ; ?>>75</option>
                                <option value="100" <?php echo (osc_num_rss_items() == '100') ? 'selected="selected"' : '' ; ?>>100</option>
                                <option value="150" <?php echo (osc_num_rss_items() == '150') ? 'selected="selected"' : '' ; ?>>150</option>
                                <option value="200" <?php echo (osc_num_rss_items() == '200') ? 'selected="selected"' : '' ; ?>>200</option>
                            </select>
                        </fieldset>
                    </div>

                    <div style="clear: both;"></div>

                    <input id="button_save" type="submit" value="<?php _e('Update') ; ?>" />
                </form>

            </div>
        </div>
    </div> <!-- end of right column -->
</div>