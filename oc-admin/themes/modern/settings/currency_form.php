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

    //customize Head
    function customHead() { ?>
        <script type="text/javascript">
            $(document).ready(function(){
                // Code for form validation
                $("form[name=currency_form]").validate({
                    rules: {
                        pk_c_code: {
                            required: true,
                            minlength: 3,
                            maxlength: 3
                        },
                        s_name: {
                            required: true,
                            minlength: 1
                        }
                    },
                    messages: {
                        pk_c_code: {
                            required: '<?php echo osc_esc_js( __('Currency code: this field is required')); ?>.',
                            minlength: '<?php echo osc_esc_js( __('Currency code: this field is required')); ?>.',
                            maxlength: '<?php echo osc_esc_js( __('Currency code: this field is required')); ?>.'
                        },
                        s_name: {
                            required: '<?php echo osc_esc_js( __('Name: this field is required')); ?>.',
                            minlength: '<?php echo osc_esc_js( __('Name: this field is required')); ?>.'
                        }
                    },
                    wrapper: "li",
                    errorLabelContainer: "#error_list",
                    invalidHandler: function(form, validator) {
                        $('html,body').animate({ scrollTop: $('h1').offset().top }, { duration: 250, easing: 'swing'});
                    },
                    submitHandler: function(form){
                        $('button[type=submit], input[type=submit]').attr('disabled', 'disabled');
                        form.submit();
                    }
                });
            });
        </script>
        <?php
    }
    osc_add_hook('admin_header','customHead', 10);

    osc_add_hook('admin_page_header','customPageHeader');
    function customPageHeader(){ ?>
        <h1><?php _e('Settings'); ?>
            <a href="#" class="btn ico ico-32 ico-help float-right"></a>
            <a href="<?php echo osc_admin_base_url(true).'?page=settings&action=currencies&type=add'; ?>" class="btn btn-green ico ico-32 ico-add-white float-right"><?php _e('Add'); ?></a>
       </h1>
    <?php
    }

    $typeForm = __get('typeForm');
    function customText($return = 'title') {
        $typeForm = __get('typeForm');
        $text     = array();
        switch( $typeForm ) {
            case('add_post'):
                $text['title']  = __('Add currency');
                $text['button'] = __('Add currency');
            break;
            case('edit_post'):
                $text['title']  = __('Edit currency');
                $text['button'] = __('Update currency');
            break;
        }

        return $text[$return];
    }

    function customPageTitle($string) {
        return sprintf('%s &raquo; %s', customText('title'), $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');

    $aCurrency = View::newInstance()->_get('aCurrency');

    osc_current_admin_theme_path( 'parts/header.php' ); ?>
<div id="add-currency-settings">
    <h2 class="render-title"><?php echo customText('title'); ?></h2>
    <ul id="error_list"></ul>
    <form name="currency_form" action="<?php echo osc_admin_base_url(true); ?>" method="post">
        <input type="hidden" name="page" value="settings" />
        <input type="hidden" name="action" value="currencies" />
        <input type="hidden" name="type" value="<?php echo $typeForm; ?>" />
        <?php if( $typeForm == 'edit_post' ) { ?>
        <input type="hidden" name="pk_c_code" value="<?php echo osc_esc_html($aCurrency['pk_c_code']); ?>" />
        <?php } ?>
        <fieldset>
            <div class="form-horizontal">
                <div class="form-row">
                    <div class="form-label"><?php _e('Currency Code'); ?></div>
                    <div class="form-controls">
                        <input type="text" class="input-small" name="pk_c_code" value="<?php echo osc_esc_html($aCurrency['pk_c_code']); ?>" <?php if( $typeForm == 'edit_post' ) echo 'disabled="disabled"'; ?> />
                        <span class="help-box"><?php printf(__('Must be a three-character code according to the <a href="%s" target="_blank">ISO 4217</a>'), 'http://en.wikipedia.org/wiki/ISO_4217'); ?></span>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label"><?php _e('Currency symbol'); ?></div>
                    <div class="form-controls">
                        <input type="text" class="input-small" name="s_description" value="<?php echo osc_esc_html($aCurrency['s_description']); ?>" />
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label"><?php _e('Name'); ?></div>
                    <div class="form-controls">
                        <input type="text" name="s_name" value="<?php echo osc_esc_html($aCurrency['s_name']); ?>" />
                    </div>
                </div>
                <div class="form-actions">
                    <?php if( $typeForm == 'edit_post' ) { ?>
                    <input class="btn btn-red" type="button" value="<?php echo osc_esc_html( __('Cancel')); ?>" onclick="location.href='<?php echo osc_admin_base_url(true); ?>?page=settings&amp;action=currencies'">
                    <?php } ?>
                    <input type="submit" value="<?php echo osc_esc_html(customText('button')); ?>" class="btn btn-submit" />
                </div>
            </div>
        </fieldset>
    </form>
</div>
<!-- /settings form -->
<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>