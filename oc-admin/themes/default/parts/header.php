<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php _e('OSClass Admin Panel'); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- styles
        ================================================== -->
        <link href="<?php echo osc_current_admin_theme_styles_url('main.css'); ?>" rel="stylesheet">
        <!-- favicons
        ================================================== -->
        <link rel="shortcut icon" href="images/favicon-48.png">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/favicon-144.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/favicon-114.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/favicon-72.png">
        <link rel="apple-touch-icon-precomposed" href="../assets/ico/images/favicon-57.png">
    </head>

<body>
        <?php AdminToolbar::newInstance()->render() ;?>
        <?php osc_run_hook('admin_header') ; ?>
    </div>

    <div id="content">
        <?php osc_current_admin_theme_path( 'parts/sidebar.php' ) ; ?>
        <div id="content-render">