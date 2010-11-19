<?php
$catId = osc_pageInfo('category');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title><?php echo osc_pageInfo('pageTitle'); ?></title>
        <meta name="generator" content="<?php echo OSCLASS_VERSION; ?>" />
	<link href="<?php echo osc_themeResource('style.css'); ?>" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="<?php echo WEB_PATH; ?>/oc-includes/js/tiny_mce/tiny_mce.js"></script>
	<script src="<?php echo WEB_PATH; ?>/oc-includes/js/jquery-1.4.2.js" type="text/javascript"></script>
	<script src="<?php echo WEB_PATH; ?>/oc-includes/js/jquery-ui-1.8.5.js" type="text/javascript"></script>
	<script>
        var locale = "<?php if(isset($_SESSION['locale']) && $_SESSION['locale'] != NULL) echo $_SESSION['locale']; ?>";
		
        function setCurrentLang() {
            if (typeof locale == "undefined" || locale.length < 1) {
                $('#clang').html('English');
                return false;
            }
            $('#clang').html( $('#langs').find('a#' + locale +'').html());
        }

        $(function() {
            setCurrentLang();
            $('#clang').click(function() {
                $('#langs').slideToggle('fast', function(){
                    //
                });
            });
            $("#FlashMessage").effect('highlight',2000);
            $('#search_post_yours').mouseover(function() {
                $(this).css('background-color', '#CA0002');
            });
            $('#search_post_yours').mouseout(function() {
                $(this).css('background-color', '#F38527');
            });
        });
    </script>
    <?php osc_runHook('header'); ?>
</head>
<body>

<?php
osc_showFlashMessages();
$locales = Locale::newInstance()->listAllEnabled();
?>
    <div id="container" align="center">
        <div id="header" align="center">
            <div id="header_content">
                <!-- title, langs, search,register, usermenu, login -->
                    <!-- left -->
                    <div id="header_left">
                        <div id="header_title_shadow">
                            <div id="header_title"><a href="<?php echo WEB_PATH; ?>/"><?php echo osc_pageInfo('pageTitle'); ?></a></div>
                        </div>
                    </div>

                    <!-- right -->
                    <div id="header_right">
                        <div id="header_user_menu">
                            <?php if(osc_isUserLoggedIn()): ?>
                            <?php _e('Hello ' . osc_userInfo('s_name') . "!") ; ?>
                            <?php _e('Manage from here your'); ?>
                            <a href="<?php echo WEB_PATH; ?>/user.php?action=items"><?php _e('items'); ?></a>
                            <?php _e('and'); ?>
                            <a href="<?php echo WEB_PATH; ?>/user.php?action=profile"><?php _e('profile'); ?></a> |
                            <a href="<?php echo WEB_PATH; ?>/user.php?action=logout"><?php _e('Logout'); ?></a>
                            <?php else: ?>
                            <a href="<?php echo WEB_PATH; ?>/user.php?action=register"><?php _e('Register a free account'); ?></a> or <a id="login_open" href="<?php echo WEB_PATH; ?>/user.php?action=login"><?php _e('login'); ?></a>
                            <?php endif; ?>
                        </div>
                        <div id="header_lang_menu">
                            <div id="clang"></div>
                            <div id="langs">
                                <?php $i = 0; foreach($locales as $locale): ?>
                                    <div id="lang_container">
                                        <a id="<?php echo $locale['pk_c_code'] ?>" href="<?php echo WEB_PATH; ?>/index.php?action=setlanguage&value=<?php echo $locale['pk_c_code']; ?>"><?php echo $locale['s_name']; ?></a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div style="clear:both;"></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div style="clear: both;"></div>
                </div>
            </div>

            <div id="search" align="center">
                <div id="search_content">
                    <div id="search_left">
                        <script type="text/javascript">
                            function doSearch() { document.location = '<?php echo WEB_PATH; ?>/search.php?pattern=' + encodeURIComponent(document.getElementById('searchPattern').value); }
                        </script>

                        <div style="padding-top: 8px;">
                            <input class="search_input" onkeyup="if(event.keyCode == 13) doSearch();" type="text" name="pattern" id="searchPattern" />
                            <button class="search_button" value="<?php _e('Search'); ?>" onclick="doSearch();"><?php _e('Search'); ?></button>
                        </div>
                    </div>

                    <div id="search_right">
                        <div id="search_post_yours"><a href="<?php echo osc_createItemPostURL($catId); ?>"><?php _e('Publish your item'); ?></a></div>
                    </div>
                </div>
            </div>

            <div id="content" align="left">
            <?php osc_showWidgets('header'); ?>
