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

    $plugins        = __get('plugins') ;
    $active_plugins = osc_get_plugins() ;
?>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()) ; ?>">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
        <link href="<?php echo osc_current_admin_theme_styles_url('demo_table.css') ; ?>" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.dataTables.js') ; ?>"></script>
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('datatables.pagination.js') ; ?>"></script>
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('datatables.extend.js') ; ?>"></script>
        <script type="text/javascript">
            $(function() {
                oTable = $('#datatables_list').dataTable({
                    "bAutoWidth": false,
                    "aaData": [
                        <?php foreach($plugins as $p){ ?>
                        <?php $p_info = osc_plugin_get_info($p); ?>
                        <?php osc_plugin_is_installed($p) ? $installed = 1 : $installed = 0; ?>
                        <?php osc_plugin_is_enabled($p) ? $enabled = 1 : $enabled = 0; ?>
                            [
                                "<input type='hidden' name='installed' value='<?php echo $installed ?>' enabled='<?php echo $enabled ?>' />" +
                                "<?php echo addcslashes($p_info['plugin_name'], '"'); ?>&nbsp;<div><?php if(osc_check_update(@$p_info['plugin_update_uri'], @$p_info['version'])) { ?><a href='<?php echo osc_admin_base_url(true);?>?page=universe&code=<?php echo htmlentities($p_info['plugin_update_uri']); ?>'><?php _e("There's a new version available to update"); ?></a><?php }; ?></div>",
                                "<?php echo addcslashes($p_info['description'], '"'); ?>",
                                "<?php if(isset($active_plugins[$p.'_configure'])) { ?><a href='<?php echo osc_admin_base_url(true);?>?page=plugins&action=admin&amp;plugin=<?php echo $p_info['filename']; ?>'><?php _e('Configure'); ?></a><?php }; ?>",
                                "<?php if($installed) { if($enabled) { ?><a href='<?php echo osc_admin_base_url(true);?>?page=plugins&action=disable&amp;plugin=<?php echo $p_info['filename']; ?>'><?php _e('Disable'); ?></a><?php } else { ?><a href='<?php echo osc_admin_base_url(true);?>?page=plugins&action=enable&amp;plugin=<?php echo $p_info['filename']; ?>'><?php _e('Enable'); ?></a><?php }; };?>",
                                "<?php if($installed) { ?><a onclick=\"javascript:return confirm('<?php _e('This action can not be undone. Uninstalling plugins may result in a permanent lost of data. Are you sure you want to continue?'); ?>')\" href='<?php echo osc_admin_base_url(true);?>?page=plugins&action=uninstall&amp;plugin=<?php echo $p_info['filename']; ?>'><?php _e('Uninstall'); ?></a><?php } else { ?><a href='<?php echo osc_admin_base_url(true);?>?page=plugins&action=install&amp;plugin=<?php echo $p_info['filename']; ?>'><?php _e('Install'); ?></a><?php }; ?>"
                            ] <?php echo $p != end($plugins) ? ',' : ''; ?>
                        <?php } ?>
                    ],
                    "aoColumns": [
                        {
                            "sTitle": "<?php echo osc_esc_html( __('Name') ) ; ?>"
                        },
                        {
                            "sTitle": "<?php echo osc_esc_html( __('Description') ) ; ?>"
                        },
                        {
                            "sTitle": "",
                            "sWidth": "65px"
                        },
                        {
                            "sTitle": "",
                            "sWidth": "65px"
                        },
                        {
                            "sTitle": "",
                            "sWidth": "65px"
                        }
                    ],
                    "fnDrawCallback": function() {
                        $('input:hidden[name="installed"]').each(function() {
                            $(this).parent().parent().children().css('background', 'none') ;
                            if( $(this).val() == '1' ) {
                                if( $(this).attr("enabled") == 1 ) {
                                    $(this).parent().parent().css('background-color', '#EDFFDF') ;
                                } else {
                                    $(this).parent().parent().css('background-color', '#FFFFDF') ;
                                }
                            } else {
                                $(this).parent().parent().css('background-color', '#FFF0DF') ;
                            }
                        }) ;
                    }
                });

                $('.filter').append( $("#add_plugin_button") ) ;
            });
        </script>
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('datatables.post_init.js') ; ?>"></script>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <!-- container -->
        <div id="content">
            <?php osc_current_admin_theme_path( 'include/backoffice_menu.php' ) ; ?>
            <!-- right container -->
            <div class="right">
                <div class="header_title">
                    <h1 class="plugins"><?php _e('Plugins') ; ?></h1>
                </div>
                <?php osc_show_admin_flash_messages() ; ?>
                <!-- datatables plugins -->
                <div id="add_plugin_button">
                    <a href="<?php echo osc_admin_base_url(true); ?>?page=plugins&amp;action=add" class="btn" id="button_open"><?php _e('Add new plugin') ; ?></a>
                </div>
                <div class="datatables">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="datatables_list"></table>
                </div>
                <!-- /datatables plugins -->
            </div>
            <!-- /right container -->
        </div>
        <!-- /container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>