<?php if ( ! defined('OC_ADMIN')) exit('Direct access is not allowed.'); ?>
<!DOCTYPE html>
<html lang="<?php echo substr(osc_current_admin_locale(), 0, 2); ?>">
<head>
    <meta charset="utf-8">
    <title><?php echo osc_apply_filter('admin_title', osc_page_title() . ' - Osclass'); ?></title>
    <meta name="title" content="<?php echo osc_apply_filter('admin_title', osc_page_title() . ' - Osclass'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="content-language" content="<?php echo osc_current_admin_locale(); ?>" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <script type="text/javascript">
        var osc = window.osc || {};
<?php
    /* TODO: enqueue js lang strings */
    $lang = array(
        'nochange_expiration' => __('No change expiration'),
        'without_expiration' => __('Without expiration'),
        'expiration_day' => __('1 day'),
        'expiration_days' => __('%d days'),
        'select_category' => __('Select category'),
        'no_subcategory' => __('No subcategory'),
        'select_subcategory' => __('Select subcategory')
    );
    $locales = osc_get_locales();
    $codes   = array();
    foreach($locales as $locale) {
        $codes[] = '\''. osc_esc_js($locale['pk_c_code']) . '\'';
    }
?>
        osc.locales = {};
        osc.locales._default = '<?php echo osc_language(); ?>';
        osc.locales.current = '<?php echo osc_current_admin_locale(); ?>';
        osc.locales.codes   = new Array(<?php echo join(',', $codes); ?>);
        osc.locales.string  = '[name*="' + osc.locales.codes.join('"],[name*="') + '"],.' + osc.locales.codes.join(',.');
        osc.langs = <?php echo json_encode($lang); ?>;
    </script>
    <?php osc_run_hook('admin_header'); ?>
</head>
<body class="<?php echo implode(' ',osc_apply_filter('admin_body_class', array())); ?>">
    <?php AdminToolbar::newInstance()->render(); ?>
    </div>
    <div id="content">
        <?php osc_draw_admin_menu(); ?>
        <div id="content-render">
            <div id="content-head">
                <?php osc_run_hook('admin_page_header'); ?>
            </div>
            <div id="help-box">
                <a href="#" class="btn ico ico-20 ico-close">x</a>
                <?php osc_run_hook('help_box'); ?>
            </div>
            <?php osc_show_flash_message('admin'); ?>
            <div class="jsMessage flashmessage flashmessage-info hide">
                <a class="btn ico btn-mini ico-close">×</a>
                <p></p>
            </div>
            <div id="content-page">
                <div class="grid-system">
                    <div class="grid-row grid-first-row grid-100">
                        <div class="row-wrapper <?php echo osc_apply_filter('render-wrapper', ''); ?>">
