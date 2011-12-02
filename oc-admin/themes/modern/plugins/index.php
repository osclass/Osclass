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

    $plugins        = __get("plugins");
    $last           = end($plugins); $last_id = $last['int_name'];
    $active_plugins = osc_get_plugins();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php'); ?>
        <div id="update_version" style="display:none;"></div>
        <script type="text/javascript">
            $(function() {
                sSearchName = "<?php _e('Search'); ?>...";
                oTable = $('#datatables_list').dataTable({
                    "bAutoWidth": false,
                    "aaData": [
                        <?php foreach($plugins as $p){ ?>
                        <?php $p_info = osc_plugin_get_info($p); ?>
                        <?php osc_plugin_is_installed($p) ? $installed = 1 : $installed = 0; ?>
                        <?php osc_plugin_is_enabled($p) ? $enabled = 1 : $enabled = 0; ?>
                            [
                                "<input type='hidden' name='installed' value='<?php echo $installed ?>' enabled='<?php echo $enabled ?>' />" +
                                "<?php echo addcslashes($p_info['plugin_name'], '"'); ?>&nbsp;<div id='datatables_quick_edit'><?php if(osc_plugin_check_update($p_info['filename'])) { ?><a href='<?php echo osc_admin_base_url(true);?>?page=upgrade-plugin&plugin=<?php echo $p_info['filename']; ?>'><?php _e('There\'s a new version. You should update!'); ?></a><?php }; ?></div>",
                                "<?php echo addcslashes($p_info['description'], '"'); ?>",
                                "<?php if(isset($active_plugins[$p.'_configure'])) { ?><a href='<?php echo osc_admin_base_url(true);?>?page=plugins&action=admin&amp;plugin=<?php echo $p_info['filename']; ?>'><?php _e('Configure'); ?></a><?php }; ?>",
                                "<?php if($installed) { if($enabled) { ?><a href='<?php echo osc_admin_base_url(true);?>?page=plugins&action=disable&amp;plugin=<?php echo $p_info['filename']; ?>'><?php _e('Disable'); ?></a><?php } else { ?><a href='<?php echo osc_admin_base_url(true);?>?page=plugins&action=enable&amp;plugin=<?php echo $p_info['filename']; ?>'><?php _e('Enable'); ?></a><?php }; };?>",
                                "<?php if($installed) { ?><a onclick=\"javascript:return confirm('<?php _e('This action can not be undone. Uninstalling plugins may result in a permanent lost of data. Are you sure you want to continue?'); ?>')\" href='<?php echo osc_admin_base_url(true);?>?page=plugins&action=uninstall&amp;plugin=<?php echo $p_info['filename']; ?>'><?php _e('Uninstall'); ?></a><?php } else { ?><a href='<?php echo osc_admin_base_url(true);?>?page=plugins&action=install&amp;plugin=<?php echo $p_info['filename']; ?>'><?php _e('Install'); ?></a><?php }; ?>"
                            ] <?php echo $p != end($plugins) ? ',' : ''; ?>
                        <?php } ?>
                    ],
                    "aoColumns": [
                        {
                            "sTitle": "<?php _e('Name'); ?>",
                            "sWidth": "auto"
                        },
                        {
                            "sTitle": "<?php _e('Description'); ?>"
                        },
                        {
                            "sTitle": "",
                            "sClass": "center",
                            "sWidth": "65px"
                        },
                        {
                            "sTitle": "",
                            "sClass": "center",
                            "sWidth": "65px"
                        },
                        {
                            "sTitle": "",
                            "sClass": "center",
                            "sWidth": "65px"
                        }
                    ],
                    "aaSorting": [[4,'desc'], [3,'asc']],
                    "bPaginate": false,
                    "bFilter": false,
                    "bInfo": false,
                    "fnDrawCallback": function() {
                        $('input:hidden[name=installed]').each(function() {
                            $(this).parent().parent().children().css('background', 'none');
                            if ($(this).val() == '1') {
                                if($(this).attr("enabled")==1) {
                                    $(this).parent().parent().css('background-color', '#EDFFDF');
                                } else {
                                    $(this).parent().parent().css('background-color', '#FFFFDF');
                                }
                            } else {
                                $(this).parent().parent().css('background-color', '#FFF0DF');
                            }
                        });
                    }
                });
            });
        </script>
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_url('js/datatables.post_init.js') ; ?>"></script>
        <div id="content">
            <div id="separator"></div>
            <?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>
            <div id="right_column">
                <div id="content_header" class="content_header">
                    <div style="float: left;">
                        <img src="<?php echo  osc_current_admin_theme_url('images/plugins-icon.png') ; ?>" title="" alt=""/>
                    </div>
                    <div id="content_header_arrow">&raquo; <?php _e('Plugins'); ?></div>
                    <a href="<?php echo osc_admin_base_url(true) ; ?>?page=plugins&action=add" id="button_open"><?php _e('Add a new plugin') ; ?></a>
                    <div style="clear: both;"></div>
                </div>
                <?php osc_show_flash_message('admin') ; ?>
                <table cellpadding="0" cellspacing="0" border="0" class="display" id="datatables_list" style="border-bottom: 1px solid #AAAAAA; border-left: 1px solid #AAAAAA; border-right: 1px solid #AAAAAA;"></table>
                <div style="clear: both;"></div>
            </div> <!-- end of right column -->
            <div style="clear: both;"></div>
        </div> <!-- end of container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>