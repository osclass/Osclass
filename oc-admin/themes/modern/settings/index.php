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
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.validate.min.js') ; ?>"></script>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <script type="text/javascript">
            $(document).ready(function(){
                // Code for form validation
                $("form[name=settings_form]").validate({
                    rules: {
                        pageTitle: {
                            required: true,
                            minlength: 1
                        },
                        contactEmail: {
                            required: true,
                            email: true
                        },
                        num_rss_items: {
                            required: true,
                            digits: true
                        },
                        max_latest_items_at_home: {
                            required: true,
                            digits: true
                        },
                        default_results_per_page: {
                            required: true,
                            digits: true
                        }
                    },
                    messages: {
                        pageTitle: {
                            required: "<?php _e("Page title: this field is required"); ?>.",
                            minlength: "<?php _e("Page title: this field is required"); ?>."
                        },
                        contactEmail: {
                            required: "<?php _e("Email: this field is required"); ?>.",
                            email: "<?php _e("Invalid email address"); ?>."
                        },
                        num_rss_items: {
                            required: "<?php _e("RSS shows: this field is required"); ?>.",
                            digits: "<?php _e("RSS shows: this field has to be numeric only"); ?>."
                        },
                        max_latest_items_at_home: {
                            required: "<?php _e("The latest items show: this field is required"); ?>.",
                            digits: "<?php _e("The latest items show: this field has to be numeric only"); ?>."
                        },
                        default_results_per_page: {
                            required: "<?php _e("The search page shows: this field is required"); ?>.",
                            digits: "<?php _e("The search page shows: this field has to be numeric only"); ?>."
                        }
                    },
                    wrapper: "li",
                    errorLabelContainer: "#error_list",
                    invalidHandler: function(form, validator) {
                        $('html,body').animate({ scrollTop: $('h1').offset().top }, { duration: 250, easing: 'swing'});
                    }
                });
            });
            
            function custom_date(date_format) {
                $.getJSON(
                    "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=date_format",
                    {"format" : date_format},
                    function(data){
                        if(data.str_formatted!='') {
                            $("#custom_date").html(' <?php _e('Preview'); ?>: ' + data.str_formatted)
                        } else {
                            $("#custom_date").html('');
                        }
                    }
                );
            }
            
            function custom_time(time_format) {
                $.getJSON(
                    "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=date_format",
                    {"format" : time_format},
                    function(data){
                        if(data.str_formatted!='') {
                            $("#custom_time").html(' <?php _e('Preview'); ?>: ' + data.str_formatted)
                        } else {
                            $("#custom_time").html('');
                        }
                    }
                );
            }
            
        </script>
        <!-- container -->
        <div id="content">
            <?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>
            <!-- right container -->
            <div class="right">
                <div class="header_title">
                    <h1 class="settings"><?php _e('General Settings') ; ?></h1>
                </div>
                <?php osc_show_flash_message('admin') ; ?>
                <!-- settings form -->
                <div class="settings general">
                    <ul id="error_list" style="display: none;"></ul>
                    <form name="settings_form" action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
                        <input type="hidden" name="page" value="settings" />
                        <input type="hidden" name="action" value="update" />
                        <fieldset>
                            <table class="table-backoffice-form">
                                <tr>
                                    <td class="labeled"><?php _e('Page title') ; ?></td>
                                    <td><input type="text" class="xlarge" name="pageTitle" value="<?php echo osc_esc_html( osc_page_title() ); ?>" /></td>
                                </tr>
                                <tr>
                                    <td><?php _e('Page description') ; ?></td>
                                    <td><input type="text" class="xlarge" name="pageDesc" value="<?php echo osc_esc_html( osc_page_description() ); ?>" /></td>
                                </tr>
                                <tr>
                                    <td><?php _e('Admin e-mail') ; ?></td>
                                    <td><input type="text" class="large" name="contactEmail" value="<?php echo osc_esc_html( osc_contact_email() ) ; ?>" /></td>
                                </tr>
                                <tr>
                                    <td><?php _e('Default language'); ?></td>
                                    <td>
                                        <select name="language">
                                            <?php foreach( $aLanguages as $lang ) { ?>
                                                <option value="<?php echo $lang['pk_c_code'] ; ?>" <?php echo ((osc_language() == $lang['pk_c_code']) ? 'selected="selected"' : '') ; ?>><?php echo $lang['s_name'] ; ?></option>
                                            <?php } ?>
                                        </select>
                                   </td>
                                </tr>
                                <tr>
                                    <td><?php _e('Default currency') ; ?></td>
                                    <td>
                                        <select name="currency" id="currency_admin">
                                            <?php foreach($aCurrencies as $currency) { ?>
                                                <option value="<?php echo osc_esc_html($currency['pk_c_code']); ?>" <?php echo ((osc_currency() == $currency['pk_c_code']) ? 'selected="selected"' : ''); ?>><?php echo $currency['pk_c_code'] ?></option>
                                            <?php } ?>
                                        </select>
                                   </td>
                                </tr>
                                <tr>
                                    <td><?php _e('Week starts on') ; ?></td>
                                    <td>
                                        <select name="weekStart" id="weekStart">
                                            <option value="0" <?php if(osc_week_starts_at() == '0') { ?>selected="selected"<?php } ?>><?php _e('Sunday') ; ?></option>
                                            <option value="1" <?php if(osc_week_starts_at() == '1') { ?>selected="selected"<?php } ?>><?php _e('Monday') ; ?></option>
                                            <option value="2" <?php if(osc_week_starts_at() == '2') { ?>selected="selected"<?php } ?>><?php _e('Tuesday') ; ?></option>
                                            <option value="3" <?php if(osc_week_starts_at() == '3') { ?>selected="selected"<?php } ?>><?php _e('Wednesday') ; ?></option>
                                            <option value="4" <?php if(osc_week_starts_at() == '4') { ?>selected="selected"<?php } ?>><?php _e('Thursday') ; ?></option>
                                            <option value="5" <?php if(osc_week_starts_at() == '5') { ?>selected="selected"<?php } ?>><?php _e('Friday') ; ?></option>
                                            <option value="6" <?php if(osc_week_starts_at() == '6') { ?>selected="selected"<?php } ?>><?php _e('Saturday') ; ?></option>
                                        </select>
                                   </td>
                                </tr>
                                <tr>
                                    <td><?php _e('Timezone') ; ?></td>
                                    <td>
                                    <?php require osc_lib_path() . 'osclass/timezones.php' ; ?>
                                        <select name="timezone" id="timezone">
                                            <?php $selected_tz = osc_timezone() ; ?>
                                            <option value="" selected="selected"><?php _e('Select a timezone...') ; ?></option>
                                            <?php foreach ($timezone as $tz) { ?>
                                            <option value="<?php echo $tz ; ?>" <?php if($selected_tz == $tz) { ?> selected="selected" <?php } ?>><?php echo $tz; ?></option>
                                            <?php } ?>
                                        </select>
                                   </td>
                                </tr>
                                <tr>
                                    <td><?php _e('Date format'); ?></td>
                                    <td>
                                        <?php
                                        $custom_checked = true ;
                                        foreach( $dateFormats as $df ) {
                                        $checked = false ;
                                        if( $df == osc_date_format() ) {
                                        $custom_checked = false ;
                                        $checked        = true ;
                                        } ?>
                                        <div>
                                            <input type="radio" name="df" id="<?php echo $df ; ?>" value="<?php echo $df ; ?>" <?php echo ( $checked ? 'checked="checked"' : '' ) ; ?> onclick="javascript:document.getElementById('dateFormat').value = '<?php echo $df ; ?>' ;"/>
                                            <?php echo date($df) ; ?>
                                        </div>
                                            <?php } ?>
                                        <div>
                                        <input type="radio" name="df" id="df_custom" value="df_custom" <?php echo ( $custom_checked ? 'checked="checked"' : '' ) ; ?> />
                                        <input type="text" class="small" <?php echo ( $custom_checked ? 'value="' . osc_esc_html( osc_date_format() ) . '"' : '' ) ; ?> onchange="javascript:document.getElementById('dateFormat').value = this.value ;" onkeyup="javascript:custom_date(this.value);"/><span id="custom_date"></span>
                                        </div>
                                        <div class="help-box"><a href="http://php.net/date" target="_blank"><?php _e('Documentation on date and time formatting') ; ?></a></div>
                                        <input type="hidden" name="dateFormat" id="dateFormat" value="<?php echo osc_date_format() ; ?>" />
                                   </td>
                                </tr>
                                <tr>
                                    <td><?php _e('Time format') ; ?></td>
                                    <td>
                                        <?php
                                        $custom_checked = true ;
                                        foreach( $timeFormats as $tf ) {
                                            $checked = false ;
                                            if( $tf == osc_time_format() ) {
                                            $custom_checked = false ;
                                            $checked        = true ;
                                            }
                                        ?>  <div>
                                                <input type="radio" name="tf" id="<?php echo $tf ; ?>" value="<?php echo $tf; ?>" <?php echo ( $checked ? 'checked="checked"' : '' ) ; ?> onclick="javascript:document.getElementById('timeFormat').value = '<?php echo $tf ; ?>' ;" />
                                                <?php echo date($tf) ; ?>
                                            </div>
                                        <?php } ?>
                                        <div>
                                        <input type="radio" name="tf" id="tf_custom" value="tf_custom" <?php echo ( $custom_checked ? 'checked="checked"' : '' ) ; ?> />
                                        <input type="text" class="small" <?php echo ( $custom_checked ? 'value="' . osc_esc_html( osc_time_format() ) . '"' : ''); ?> onchange="javascript:document.getElementById('timeFormat').value = this.value ;" onkeyup="javascript:custom_time(this.value);"/><span id="custom_time"></span>
                                        </div>
                                        <div class="help-box"><a href="http://php.net/date" target="_blank"><?php _e('Documentation on date and time formatting') ; ?></a></div>
                                        <input type="hidden" name="timeFormat" id="timeFormat" value="<?php echo osc_esc_html( osc_time_format() ) ; ?>" />
                                   </td>
                                </tr>
                                <tr>
                                    <td><?php _e('RSS shows') ; ?></td>
                                    <td>
                                        <input type="text" class="mini" name="num_rss_items" value="<?php echo osc_esc_html(osc_num_rss_items()); ?>" />
                                        <?php _e('items at most') ; ?>
                                   </td>
                                </tr>
                                <tr>
                                    <td><?php _e('The latest items show') ; ?></td>
                                    <td>
                                        <input type="text" class="mini" name="max_latest_items_at_home" value="<?php echo osc_esc_html(osc_max_latest_items_at_home()) ; ?>" />
                                        <?php _e('items at most') ; ?>
                                   </td>
                                </tr>
                                <tr>
                                    <td><?php _e('The search page shows') ; ?></td>
                                    <td>
                                        <input type="text" class="mini" name="default_results_per_page" value="<?php echo osc_esc_html(osc_default_results_per_page_at_search()); ?>" />
                                        <?php _e('items at most') ; ?>
                                   </td>
                                </tr>
                                <tr class="separate">
                                    <td colspan="2"><h2><?php _e('Contact Settings') ; ?></h2></td>
                                </tr>
                                <tr>
                                    <td class="labeled"><?php _e('Attachments') ; ?></td>
                                    <td>
                                        <input type="checkbox" <?php echo ( osc_contact_attachment() ? 'checked="true"' : '' ) ; ?> name="enabled_attachment" value="1" />
                                        <?php _e('Allow people to attach a file to the contact form') ; ?></td>
                                </tr>
                                <tr class="separate">
                                    <td colspan="2"><h2><?php _e('Contact Settings') ; ?></h2></td>
                                </tr>
                                <tr>
                                    <td class="labeled"><?php _e('Automatic cron process') ; ?></td>
                                    <td>
                                        <input type="checkbox" <?php echo ( osc_auto_cron() ? 'checked="true"' : '' ) ; ?> name="auto_cron" />
                                        <?php printf(__('Allow OSClass to run a built-in <a href="%s" target="_blank">cron</a> automatically without setting crontab'), 'http://en.wikipedia.org/wiki/Cron' ) ; ?>
                                        <span class="help-box"><?php _e('It is <b>recommended</b> to have this option enabled, because some features require it.') ; ?></span>
                                    </td>
                                </tr>
                                <tr class="separate">
                                    <td></td>
                                    <td>
                                        <input type="submit" value="<?php echo osc_esc_html( __('Save changes') ) ; ?>" />
                                    </td>
                                </tr>
                            </table>
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
