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
?>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php _e('OSClass Admin Panel'); ?></title>

<link href="<?php echo osc_current_admin_theme_styles_url('backoffice.css') ; ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo osc_current_admin_theme_styles_url('jquery-ui.css') ; ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo osc_current_admin_theme_styles_url('demo_table.css') ; ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo osc_current_admin_theme_styles_url('admins_list_layout.css') ; ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo osc_current_admin_theme_styles_url('new_item_layout.css') ; ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo osc_current_admin_theme_styles_url('item_list_layout.css') ; ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo osc_current_admin_theme_styles_url('tabs.css') ; ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo osc_current_admin_theme_styles_url('appearance_layout.css') ; ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo osc_current_admin_theme_styles_url('languages_layout.css') ; ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo osc_current_admin_theme_styles_url('settings_layout.css') ; ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo osc_current_admin_theme_styles_url('location_layout.css') ; ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo osc_current_admin_theme_styles_url('cat_list_layout.css') ; ?>" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.js') ; ?>"></script>
<script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery-ui.js') ; ?>"></script>
<script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.cookie.js') ; ?>"></script>
<script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.json.js') ; ?>"></script>
<script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.uniform.js') ; ?>"></script>
<script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('osclass_datatables.js') ; ?>"></script>
<script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('tabber-minimized.js') ; ?>"></script>
<script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('tiny_mce/tiny_mce.js') ; ?>"></script>
<script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.validate.min.js') ; ?>"></script>
<script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.dataTables.min.js') ; ?>"></script>

<script type="text/javascript">
    $(function() {
        $("#menu").accordion({
            active: false,
            collapsible: true,
            navigation: true,
            autoHeight: false,
            icons: { 'header': 'ui-icon-plus', 'headerSelected': 'ui-icon-minus' }
        });

        if (jQuery.browser.msie && jQuery.browser.version.substr(0,1)<7) {
            jQuery('#accordion *').css('zoom', '1');
        }

        if($('.Header')) $('.Header').hide(); //XXX: remove it.
        if($('.FlashMessage')) $('.FlashMessage').animate({opacity: 1.0}, 5000).fadeOut();
        $("#static").hover(function(){ $(this).css('margin-right', '2px') }, function(){ $(this).css('margin-right','-2px') } );
    });
</script>

<?php
    $lastCheck = (int)osc_last_version_check() ;
    $hourInSecs = 24 * 3600 ;
?>
<?php if ( (time() - $lastCheck) > $hourInSecs ) { ?>
    <script type="text/javascript">
        $(function() {
            var version = <?php echo osc_version() ; ?> ;

            $.getJSON("http://osclass.org/latest_version.php?callback=?", function(data) {
                var update = document.getElementById('update_version') ;
                if(data.version > version) {
                    var text = '<?php printf(__('OSClass %s is available!'), '\' + data.s_name + \'') ; ?> <a href="index.php?page=tools&action=upgrade"><?php _e('Please upgrade now') ; ?></a>' ;
                    update.innerHTML = text ;
                    update.setAttribute('style', '') ;
                }
            });
        });
    </script>
<?php } ?>

<script>
    var fileDefaultText = '<?php _e('No file selected','modern'); ?>';
    var fileBtnText     = '<?php _e('Choose File','modern'); ?>';
</script>