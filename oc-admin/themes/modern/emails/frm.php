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

    function customPageHeader(){ ?>
        <h1><?php _e('Settings'); ?></h1>
<?php
    }
    osc_add_hook('admin_page_header','customPageHeader');
    //customize Head
    function customHead() { ?>
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('tiny_mce/tiny_mce.js') ; ?>"></script>
        <script type="text/javascript">
            tinyMCE.init({
                mode : "textareas",
                theme : "advanced",
                skin: "cirkuit",
                width: "100%",
                height: "340px",
                theme_advanced_buttons3 : "",
                theme_advanced_toolbar_align : "left",
                theme_advanced_toolbar_location : "top",
                plugins : "media",
                entity_encoding : "raw",
                theme_advanced_buttons1_add : "media",
                theme_advanced_disable : "styleselect"
            }) ;
        </script>
        <?php
    }
    osc_add_hook('admin_header','customHead');

    function customPageTitle($string) {
        return sprintf(__('Edit email template &raquo; %s'), $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');

    $email   = __get("email");
    $locales = OSCLocale::newInstance()->listAllEnabled();

    osc_current_admin_theme_path('parts/header.php'); ?>
<h2 class="render-title"><?php _e('Edit email template'); ?></h2>
<div id="item-form">
    <?php printLocaleTabs(); ?>
     <form action="<?php echo osc_admin_base_url(true); ?>" method="post">
        <input type="hidden" name="page" value="emails" />
        <input type="hidden" name="action" value="edit_post" />
        <?php PageForm::primary_input_hidden($email); ?>
        <div id="left-side">
            <?php printLocaleTitlePage($locales, $email); ?>
            <div>
                <label><?php _e('Internal name') ; ?></label>
                <?php PageForm::internal_name_input_text($email) ; ?>
                <div class="flashmessage flashmessage-warning flashmessage-inline">
                    <p><?php _e('Used to identify the email template') ; ?></p>
                </div>
            </div>
            <div class="input-description-wide">
                <?php printLocaleDescriptionPage($locales, $email); ?>
            </div>
        </div>
        <div class="clear"></div>
        <div class="form-actions">
            <input type="submit" value="<?php echo osc_esc_html(__('Save changes')); ?>" class="btn btn-submit" />
        </div>
    </form>
</div>
<?php osc_current_admin_theme_path('parts/footer.php') ; ?>