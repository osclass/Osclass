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

    //customize Head
    function customHead(){
        echo '<script type="text/javascript" src="'.osc_current_admin_theme_js_url('jquery.validate.min.js').'"></script>';
        ?>
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
                    required: "<?php echo osc_esc_js(__("Page title: this field is required")); ?>.",
                    minlength: "<?php echo osc_esc_js(__("Page title: this field is required")); ?>."
                },
                contactEmail: {
                    required: "<?php echo osc_esc_js(__("Email: this field is required")); ?>.",
                    email: "<?php echo osc_esc_js(__("Invalid email address")); ?>."
                },
                num_rss_items: {
                    required: "<?php echo osc_esc_js(__("RSS shows: this field is required")); ?>.",
                    digits: "<?php echo osc_esc_js(__("RSS shows: this field has to be numeric only")); ?>."
                },
                max_latest_items_at_home: {
                    required: "<?php echo osc_esc_js(__("The latest listings show: this field is required")); ?>.",
                    digits: "<?php echo osc_esc_js(__("The latest listings show: this field has to be numeric only")); ?>."
                },
                default_results_per_page: {
                    required: "<?php echo osc_esc_js(__("The search page shows: this field is required")); ?>.",
                    digits: "<?php echo osc_esc_js(__("The search page shows: this field has to be numeric only")); ?>."
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
        <?php
    }
    osc_add_hook('admin_header','customHead');

    function render_offset(){
        return 'row-offset';
    }

    osc_add_hook('admin_page_header','customPageHeader');
    function customPageHeader(){ ?>
        <h1><?php _e('Settings'); ?></h1>
    <?php
    }

    function customPageTitle($string) {
        return sprintf(__('General Settings &raquo; %s'), $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');

    osc_current_admin_theme_path( 'parts/header.php' ) ; ?>
<div id="general-setting">
    <!-- settings form -->
    <div id="general-settings">
        <h2 class="render-title"><?php _e('General Settings') ; ?></h2>
            <ul id="error_list"></ul>
            <form name="settings_form" action="<?php echo osc_admin_base_url(true) ; ?>" method="post">
                <input type="hidden" name="page" value="settings" />
                <input type="hidden" name="action" value="update" />
                <fieldset>
                    <div class="form-horizontal">
                    <div class="form-row">
                        <div class="form-label"><?php _e('Page title') ; ?></div>
                        <div class="form-controls"><input type="text" class="xlarge" name="pageTitle" value="<?php echo osc_esc_html( osc_page_title() ); ?>" /></div>
                    </div>
                    <div class="form-row">
                        <div class="form-label"><?php _e('Page description') ; ?></div>
                        <div class="form-controls"><input type="text" class="xlarge" name="pageDesc" value="<?php echo osc_esc_html( osc_page_description() ); ?>" /></div></div>
                    <div class="form-row">
                        <div class="form-label"><?php _e('Admin e-mail') ; ?></div>
                        <div class="form-controls"><input type="text" class="large" name="contactEmail" value="<?php echo osc_esc_html( osc_contact_email() ) ; ?>" /></div></div>
                    <div class="form-row">
                        <div class="form-label"><?php _e('Default language'); ?></div>
                        <div class="form-controls">
                            <select name="language">
                            <?php foreach( $aLanguages as $lang ) { ?>
                            <option value="<?php echo $lang['pk_c_code'] ; ?>" <?php echo ((osc_language() == $lang['pk_c_code']) ? 'selected="selected"' : '') ; ?>><?php echo $lang['s_name'] ; ?></option>
                            <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label"><?php _e('Default currency') ; ?></div>
                        <div class="form-controls">
                            <select name="currency" id="currency_admin">
                            <?php foreach($aCurrencies as $currency) { ?>
                            <option value="<?php echo osc_esc_html($currency['pk_c_code']); ?>" <?php echo ((osc_currency() == $currency['pk_c_code']) ? 'selected="selected"' : ''); ?>><?php echo $currency['pk_c_code'] ?></option>
                            <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label"><?php _e('Week starts on') ; ?></div>
                        <div class="form-controls">
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
                    <div class="form-row">
                        <div class="form-label"><?php _e('Timezone') ; ?></div>
                        <div class="form-controls">
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
                    <div class="form-row">
                        <div class="form-label"><?php _e('Date & time format'); ?></div>
                        <div class="form-controls">
                            <table class="table-small">
                                <tr>
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
                                            <input type="radio" name="df" id="<?php echo $df ; ?>" value="<?php echo $df ; ?>" <?php echo ( $checked ? 'checked="checked"' : '' ) ; ?> onclick="javascript:document.getElementById('dateFormat').value = '<?php echo $df ; ?>' ;" />
                                            <?php echo date($df) ; ?>
                                        </div>
                                        <?php } ?>
                                            <input type="radio" name="df" id="df_custom" value="df_custom" <?php echo ( $custom_checked ? 'checked="checked"' : '' ) ; ?> />
                                            <input type="text" name="df_custom_text" id="df_custom_text" class="input-medium" <?php echo ( $custom_checked ? 'value="' . osc_esc_html( osc_date_format() ) . '"' : '' ) ; ?> onchange="javascript:document.getElementById('dateFormat').value = this.value ;" onkeyup="javascript:custom_date(this.value);" /><span id="custom_date"></span>
                                            <input type="hidden" name="dateFormat" id="dateFormat" value="<?php echo osc_date_format() ; ?>" />
                                    </td>
                                    <td>
                                        <?php
                                        $custom_checked = true ;
                                        foreach( $timeFormats as $tf ) {
                                        $checked = false ;
                                        if( $tf == osc_time_format() ) {
                                        $custom_checked = false ;
                                        $checked        = true ;
                                        }
                                        ?>
                                        <div>
                                            <input type="radio" name="tf" id="<?php echo $tf ; ?>" value="<?php echo $tf; ?>" <?php echo ( $checked ? 'checked="checked"' : '' ) ; ?> onclick="javascript:document.getElementById('timeFormat').value = '<?php echo $tf ; ?>' ;" />
                                            <?php echo date($tf) ; ?>
                                        </div>
                                        <?php } ?>
                                        <input type="radio" name="tf" id="tf_custom" value="tf_custom" <?php echo ( $custom_checked ? 'checked="checked"' : '' ) ; ?> />
                                        <input type="text" class="input-medium" <?php echo ( $custom_checked ? 'value="' . osc_esc_html( osc_time_format() ) . '"' : ''); ?> onchange="javascript:document.getElementById('timeFormat').value = this.value ;" onkeyup="javascript:custom_time(this.value);" /><span id="custom_time"></span>
                                        <input type="hidden" name="timeFormat" id="timeFormat" value="<?php echo osc_esc_html( osc_time_format() ) ; ?>" />
                                    </td>
                                </tr>
                            </table>
                            <div class="help-box"><a href="http://php.net/date" target="_blank"><?php _e('Documentation on date and time formatting') ; ?></a></div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label"><?php _e('RSS shows') ; ?></div>
                        <div class="form-controls">
                            <input type="text" class="input-small" name="num_rss_items" value="<?php echo osc_esc_html(osc_num_rss_items()); ?>" />
                            <?php _e('listings at most') ; ?>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label"><?php _e('The latest listings show') ; ?></div>
                        <div class="form-controls">
                            <input type="text" class="input-small" name="max_latest_items_at_home" value="<?php echo osc_esc_html(osc_max_latest_items_at_home()) ; ?>" />
                            <?php _e('Listings at most') ; ?>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label"><?php _e('The search page shows') ; ?></div>
                        <div class="form-controls">
                            <input type="text" class="input-small" name="default_results_per_page" value="<?php echo osc_esc_html(osc_default_results_per_page_at_search()); ?>" />
                            <?php _e('listings at most') ; ?>
                        </div>
                    </div>
                    <h2 class="render-title separate-top"><?php _e('Categories Settings') ; ?></h2>
                    <div class="form-row">
                        <div class="form-label"><?php _e('Parent categories'); ?></div>
                        <div class="form-controls">
                            <div class="form-label-checkbox">
                                <label>
                                    <input type="checkbox" <?php echo ( osc_selectable_parent_categories() ? 'checked="checked"' : '' ) ; ?> name="selectable_parent_categories" value="1" />
                            <?php _e('Allow users to select as a category when inserting or editing a listing a parent category') ; ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <h2 class="render-title separate-top"><?php _e('Contact Settings') ; ?></h2>
                    <div class="form-row">
                        <div class="form-label"><?php _e('Attachments') ; ?></div>
                        <div class="form-controls">
                            <div class="form-label-checkbox">
                                <label>
                                    <input type="checkbox" <?php echo ( osc_contact_attachment() ? 'checked="checked"' : '' ) ; ?> name="enabled_attachment" value="1" />
                            <?php _e('Allow people to attach a file to the contact form') ; ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <h2 class="render-title separate-top"><?php _e('Cron Settings') ; ?></h2>
                    <div class="form-row">
                        <div class="form-label"><?php _e('Automatic cron process') ; ?></div>
                        <div class="form-controls">
                            <div class="form-label-checkbox">
                                <label>
                                    <input type="checkbox" <?php echo ( osc_auto_cron() ? 'checked="checked"' : '' ) ; ?> name="auto_cron" />
                            <?php printf(__('Allow OSClass to run a built-in <a href="%s" target="_blank">cron</a> automatically without setting crontab'), 'http://en.wikipedia.org/wiki/Cron' ) ; ?>
                                </label>
                            </div>
                            <span class="help-box"><?php _e('It is <b>recommended</b> to have this option enabled, because some features require it.') ; ?></span>
                        </div>
                    </div>
                    <h2 class="render-title separate-top"><?php _e('Check plugin & theme updates') ; ?></h2>
                    <div class="form-row">
                        <div class="form-label"><a class="btn" href="<?php echo osc_admin_base_url(true); ?>?page=settings&action=check_updates"><?php _e('Check updates');?></a></div>
                        <div class="form-controls">
                            <?php _e('Check for plugin or theme updates. Updates are checked once a day.') ; ?>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="form-actions">
                        <input type="submit" id="save_changes" value="<?php echo osc_esc_html( __('Save changes') ) ; ?>" class="btn btn-submit" />
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
    <!-- /settings form -->
</div>
<?php osc_current_admin_theme_path( 'parts/footer.php' ) ; ?>                