<?php
    $javaScripts = array(
        '/oc-includes/js/tiny_mce/tiny_mce.js'
        ,'/oc-includes/js/jquery-1.4.2.js'
        ,'/oc-includes/js/jquery-ui-1.8.5.js'
    );
    if(isset($headerConf['javaScripts'])) {
        $javaScripts = array_merge($javaScripts, $headerConf['javaScripts']) ;
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <meta name="description" content="<?php echo osc_page_info('pageTitle') ; ?>" />
        <meta name="keywords" content="<?php echo osc_page_info('pageTitle') ; ?>" />
        <title><?php echo osc_page_info('pageTitle') ; ?></title>
        <link rel="icon" href="<?php echo osc_base_url() ; ?>favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="<?php echo osc_base_url() ; ?>favicon.ico" type="image/x-icon" />
        <link rel="alternate" type="application/rss+xml" href="<?php echo osc_create_url('feed') ; ?>" title="<?php _e('Latest items added') ; ?>" />
        <?php foreach($javaScripts as $javaScript) { ?>
            <script type="text/javascript" src="<?php echo osc_base_url() . $javaScript ; ?>"></script>
        <?php } ?>
        <meta name="generator" content="OSClass <?php echo OSCLASS_VERSION ; ?>" />
    </head>
    <body>

        <?php
            osc_run_hooks('header') ;
            osc_show_flash_messages() ;
            $locales = Locale::newInstance()->listAllEnabled();
        ?>

        <div id="header" class="header">
            <div class="headerTitle">
                <a href="<?php echo osc_base_url() ; ?>/" class="headerTitleLink" ><?php echo osc_page_title() ; ?></a>
            </div>
            <div class="headerLocales" >
                <select style="padding: 5px;" onchange="javascript:document.location='<?php echo osc_base_url() ; ?>/index.php?action=setlanguage&value=' + this.value">
                    <?php $i = 0 ; ?>
                    <?php foreach($locales as $locale) { ?>
                        <option value="<?php echo $locale['pk_c_code'] ; ?>" <?php ($locale['pk_c_code'] == $GLOBALS['locale']) ? 'selected="selected"' : '' ; ?>><?php echo $locale['s_name'] ; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div style="clear: both;"></div>
        </div>

        <div class="headerOptions" >
            <?php if(osc_isUserLoggedIn()) { ?>
                <?php printf(__('Hello %s!'), osc_userInfo('s_name')) ; ?>
                <?php _e('Manage from here your'); ?>
                <a  href="<?php echo osc_createUserItemsURL(); ?>"><?php _e('items') ; ?></a>
                <?php _e('and '); ?>
                <a  href="<?php echo osc_createProfileURL(); ?>"><?php _e('profile') ; ?></a>.<br />
                <a  href="<?php echo osc_createLogoutURL(); ?>"><?php _e('Logout') ; ?></a>
            <?php } else { ?>
                <a  href="<?php echo osc_createRegisterURL() ; ?>"><?php _e('Register a free account') ; ?></a> <?php _e('or') ; ?> <a  href="<?php echo osc_createLoginURL() ; ?>"><?php _e('login') ; ?></a>
            <?php } ?>
        </div>

        <?php osc_show_widgets('header') ; ?>