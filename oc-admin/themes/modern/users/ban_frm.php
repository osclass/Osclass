<?php if ( ! defined('OC_ADMIN')) exit('Direct access is not allowed.');
    /**
     * Osclass – software for creating and publishing online classified advertising platforms
     *
     * Copyright (C) 2012 OSCLASS
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

    osc_enqueue_script('jquery-validate');

    $rule      = __get('rule');

    function customFrmText(){
        $rule = __get('rule');
        $return = array();

        if( isset($rule['pk_i_id']) ) {
            $return['edit']       = true;
            $return['title']      = __('Edit rule');
            $return['action_frm'] = 'edit_ban_rule_post';
            $return['btn_text']   = __('Update rule');
        } else {
            $return['edit']       = false;
            $return['title']      = __('Add new ban rule');
            $return['action_frm'] = 'create_ban_rule_post';
            $return['btn_text']   = __('Add new ban rule');
        }
        return $return;
    }
    function customPageHeader(){ ?>
        <h1><?php _e('Ban rules'); ?></h1>
<?php
    }
    osc_add_hook('admin_page_header','customPageHeader');

    function customPageTitle($string) {
        $aux = customFrmText();
        return sprintf('%s &raquo; %s', $aux['title'], $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');

    //customize Head
    function customHead() {
    }
    osc_add_hook('admin_header','customHead', 10);

    $aux    = customFrmText();
?>
<?php osc_current_admin_theme_path('parts/header.php'); ?>
<h2 class="render-title"><?php echo $aux['title']; ?></h2>
    <div class="settings-user">
        <ul id="error_list"></ul>
        <form name="register" action="<?php echo osc_admin_base_url(true); ?>" method="post">
            <input type="hidden" name="page" value="users" />
            <input type="hidden" name="action" value="<?php echo $aux['action_frm']; ?>" />
            <h3 class="render-title"><?php _e('Contact info'); ?></h3>
            <?php BanRuleForm::primary_input_hidden($rule); ?>
            <fieldset>
            <div class="form-horizontal">
                <div class="form-row">
                    <div class="form-label"><?php _e('Ban name / Reason'); ?></div>
                    <div class="form-controls">
                        <?php BanRuleForm::name_text($rule); ?>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label"><?php _e('IP rule'); ?></div>
                    <div class="form-controls">
                        <?php BanRuleForm::ip_text($rule); ?>
                        <span class="help-box"><?php _e('(e.g. 192.168.10-20.*)'); ?></span>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label"><?php _e('E-mail rule'); ?></div>
                    <div class="form-controls">
                        <?php BanRuleForm::email_text($rule); ?>
                        <span class="help-box"><?php _e('(e.g. *@badsite.com)'); ?></span>
                    </div>
                </div>
                <div class="clear"></div>
                <div class="form-actions">
                    <input type="submit" value="<?php echo osc_esc_html($aux['btn_text']); ?>" class="btn btn-submit" />
                </div>
            </div>
            </fieldset>
        </form>
    </div>
<?php osc_current_admin_theme_path('parts/footer.php'); ?>