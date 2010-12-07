<?php
$catId = osc_pageInfo('category');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title><?php echo osc_pageInfo('pageTitle'); ?></title>
        <meta name="generator" content="OSClass <?php echo OSCLASS_VERSION; ?>" />
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

        function check_search_field(form) {
            var query = form.elements['pattern'].value;
            if (query.length < 2) {
                document.getElementById('search-error').setAttribute('style', '')
                return false;
            } else {
                return true;
            }
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
                        <div id="header_title">
                            <a href="<?php echo WEB_PATH; ?>/">
                            <?php echo $preferences['pageTitle']; ?>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- right -->
                <div id="header_right">
                    <div id="header_user_menu">
                        <?php if( osc_isUserLoggedIn() ) { ?>
                        <?php echo __('Hello') . ' ' . osc_userInfo('s_name') . "!"; ?>
                        <?php _e('Manage from here your'); ?>
                        <a href="<?php echo osc_createUserAccountURL(); ?>"><?php _e('account'); ?></a> |
                        <a href="<?php echo osc_createLogoutURL(); ?>"><?php _e('Logout'); ?></a>
                        <?php } else { ?>
                        <a href="<?php echo osc_createRegisterURL(); ?>"><?php _e('Register a free account'); ?></a> <?php _e('or'); ?> <a id="login_open" href="<?php echo osc_createLoginURL(); ?>"><?php _e('login'); ?></a>
                        <?php } ?>
                    </div>
                    <div id="header_lang_menu">
                        <div id="clang"></div>
                        <div id="langs">
                            <?php $i = 0; foreach($locales as $locale) { ?>
                                <div id="lang_container">
                                    <a id="<?php echo $locale['pk_c_code'] ?>" href="<?php echo WEB_PATH; ?>/index.php?action=setlanguage&value=<?php echo $locale['pk_c_code']; ?>"><?php echo $locale['s_name']; ?></a>
                                </div>
                            <?php } ?>
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
                    <div style="padding-top: 8px;">
                        <form name="search_engine" action="search.php" method="GET" onsubmit="return check_search_field(this);">
                            <input class="search_input" type="text" name="pattern" id="searchPattern" />
                            <button class="search_button" value="<?php _e('Search'); ?>"><?php _e('Search'); ?></button>
                            <span id="search-error" style="display: none;">
                                <strong><?php _e('Your search must be at least two characters long'); ?></strong>
                            </span>
                        </form>
                    </div>
                </div>

                <div id="search_right">
                    <div id="search_post_yours"><a href="<?php echo osc_createItemPostURL($catId); ?>"><?php _e('Publish your item'); ?></a></div>
                </div>
            </div>
        </div>

        <div id="content" align="left">
        <?php osc_showWidgets('header'); ?>
