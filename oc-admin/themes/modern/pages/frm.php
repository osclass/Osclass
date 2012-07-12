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

    $page    = __get('page');
    $locales = OSCLocale::newInstance()->listAllEnabled();

    function customFrmText($return = 'title') {
        $page = __get('page');
        $text = array();
        if( isset($page['pk_i_id']) ) {
            $text['edit']       = true;
            $text['title']      = __('Edit page');
            $text['action_frm'] = 'edit_post';
            $text['btn_text']   = __('Save changes');
        } else {
            $text['edit']       = false;
            $text['title']      = __('Add page');
            $text['action_frm'] = 'add_post';
            $text['btn_text']   = __('Add page');
        }

        return $text[$return];
    }

    function customPageHeader() { ?>
        <h1><?php _e('Pages'); ?></h1>
<?php
    }
    osc_add_hook('admin_page_header','customPageHeader');

    function customPageTitle($string) {
        return sprintf('%s &raquo; %s', customFrmText('title'), $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');

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
                plugins : "color",
                entity_encoding : "raw",
                theme_advanced_buttons1_add : "forecolorpicker,fontsizeselect",
                theme_advanced_disable : "styleselect,anchor,image"
            });
        </script>
        <?php
    }
    osc_add_hook('admin_header','customHead');

    osc_current_admin_theme_path('parts/header.php'); ?>
<h2 class="render-title"><?php echo customFrmText('title'); ?></h2>
<div id="item-form">
    <?php printLocaleTabs(); ?>
     <form action="<?php echo osc_admin_base_url(true); ?>" method="post">
        <input type="hidden" name="page" value="pages" />
        <input type="hidden" name="action" value="<?php echo customFrmText('action_frm'); ?>" />
        <?php PageForm::primary_input_hidden($page); ?>
        <div id="left-side">
            <?php printLocaleTitlePage($locales, $page); ?>
            <div>
                <label><?php _e('Internal name'); ?></label>
                <?php PageForm::internal_name_input_text($page); ?>
                <div class="flashmessage flashmessage-warning flashmessage-inline">
                    <p><?php _e('Used to quickly identify this page'); ?></p>
                </div>
                <span class="help"></span>
            </div>
            <div class="input-description-wide">
                <?php printLocaleDescriptionPage($locales, $page); ?>
            </div>
        </div>
        <div class="clear"></div>
        <div class="form-actions">
            <?php if( customFrmText('edit') ) { ?>
            <a href="javascript:history.go(-1)" class="btn"><?php _e('Cancel'); ?></a>
            <?php } ?>
            <input type="submit" value="<?php echo osc_esc_html(customFrmText('btn_text')); ?>" class="btn btn-submit" />
        </div>
    </form>
</div>
<?php osc_current_admin_theme_path('parts/footer.php'); ?>