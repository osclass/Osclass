<!DOCTYPE html>
<html lang="<?php echo substr(osc_current_admin_locale(), 0, 2); ?>">
    <head>
        <meta charset="utf-8">
        <title><?php echo osc_apply_filter('admin_title', osc_page_title() . ' - OSClass'); ?></title>
        <meta name="title" content="<?php echo osc_apply_filter('admin_title', osc_page_title() . ' - OSClass'); ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="content-language" content="<?php echo osc_current_admin_locale(); ?>" />
        <script src="<?php echo osc_current_admin_theme_js_url('jquery.min.js') ; ?>"></script>
        <script src="<?php echo osc_current_admin_theme_js_url('jquery-ui-1.8.20.min.js') ; ?>"></script>
        <script src="<?php echo osc_current_admin_theme_js_url('ui-osc.js') ; ?>"></script>
        <!-- styles
        ================================================== -->
        <link href="<?php echo osc_current_admin_theme_styles_url('jquery-ui/jquery-ui-1.8.20.custom.css'); ?>" rel="stylesheet">

        <link href="<?php echo osc_current_admin_theme_styles_url('main.css'); ?>" rel="stylesheet">
        <!-- favicons
        ================================================== -->
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <link rel="shortcut icon" href="<?php echo osc_current_admin_theme_url('images/favicon-48.png'); ?>">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo osc_current_admin_theme_url('images/favicon-144.png'); ?>">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo osc_current_admin_theme_url('images/favicon-114.png'); ?>">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo osc_current_admin_theme_url('images/favicon-72.png'); ?>">
        <link rel="apple-touch-icon-precomposed" href="<?php echo osc_current_admin_theme_url('images/favicon-57.png'); ?>">

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
            <?php osc_show_flash_message('admin') ; ?>
            <div class="jsMessage flashmessage flashmessage-info hide">
                <a class="btn ico btn-mini ico-close">×</a>
                <p></p>
            </div>
            <div id="content-page">
                <div class="grid-system">
                    <div class="grid-row grid-first-row grid-100">
                        <div class="row-wrapper <?php echo osc_apply_filter('render-wrapper',''); ?>">