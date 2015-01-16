<?php
/*
 * Copyright 2014 Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
?>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />

    <title><?php echo meta_title(); ?></title>
    <meta name="title" content="<?php echo osc_esc_html(meta_title()); ?>" />
<?php if( meta_description() != '' ) { ?>
    <meta name="description" content="<?php echo osc_esc_html(meta_description()); ?>" />
<?php } ?>
<?php if( function_exists('meta_keywords') ) { ?>
    <?php if( meta_keywords() != '' ) { ?>
        <meta name="keywords" content="<?php echo osc_esc_html(meta_keywords()); ?>" />
    <?php } ?>
<?php } ?>
<?php if( osc_get_canonical() != '' ) { ?>
    <link rel="canonical" href="<?php echo osc_get_canonical(); ?>"/>
<?php } ?>
    <meta http-equiv="Cache-Control" content="no-cache" />
    <meta http-equiv="Expires" content="Fri, Jan 01 1970 00:00:00 GMT" />

    <script type="text/javascript">
        var fileDefaultText = '<?php echo osc_esc_js( __('No file selected', 'modern') ); ?>';
        var fileBtnText     = '<?php echo osc_esc_js( __('Choose File', 'modern') ); ?>';
    </script>

<?php
osc_enqueue_style('style', osc_current_web_theme_url('style.css'));
osc_enqueue_style('tabs', osc_current_web_theme_url('tabs.css'));
osc_enqueue_style('jquery-ui-datepicker', osc_assets_url('css/jquery-ui/jquery-ui.css'));

osc_register_script('jquery-uniform', osc_current_web_theme_js_url('jquery.uniform.js'), 'jquery');
osc_register_script('global', osc_current_web_theme_js_url('global.js'));

osc_enqueue_script('jquery');
osc_enqueue_script('jquery-ui');
osc_enqueue_script('jquery-uniform');
osc_enqueue_script('tabber');
osc_enqueue_script('global');

osc_run_hook('header');

FieldForm::i18n_datePicker();

?>