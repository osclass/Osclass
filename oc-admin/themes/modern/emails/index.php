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

    $emails     = __get("emails");
    $prefLocale = __get("prefLocale");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
        <script type="text/javascript">
            $(function() {
                sSearchName = "<?php _e('Search'); ?>...";
                oTable = $('#datatables_list').dataTable({
                    "bAutoWidth": false,
                    "aaData": [
                        <?php foreach($emails as $email){ ?>
                        <?php if(isset($email['locale'][$prefLocale]) && !empty($email['locale'][$prefLocale]['s_title'])) {
                                  $title = $email['locale'][$prefLocale];
                              } else {
                                  $title = current($email['locale']);
                              } ?>
                            [
                                "<?php echo $email['pk_i_id']; ?>",
                                '<?php echo addslashes(osc_esc_html($email['s_internal_name'])); ?><div><a href="<?php echo osc_admin_base_url(true); ?>?page=emails&action=edit&amp;id=<?php echo $email["pk_i_id"]; ?>"><?php _e("Edit"); ?></a></div>',
                                "<?php echo addslashes(osc_esc_html($title['s_title'])); ?>"
                            ] <?php echo $email != end($emails) ? ',' : ''; ?>
                        <?php } ?>
                    ],
                    "aoColumns": [
                        {
                            "sTitle": "id"
                        },
                        {
                            "sTitle": "<?php _e('Name'); ?>",
                            "sWidth": "150px"
                        },
                        {
                            "sTitle": "<?php _e('Title'); ?>",
                            "sWidth": "auto"
                        }
                    ],
                    "aoColumnDefs": [
                        {
                            "bVisible": false,
                            "aTargets": [ 0 ]
                        }
					],
                    "bPaginate": false,
                    "aaSorting": [[0, 'asc']],
                    "bFilter": false,
                    "bInfo": false
                });
            });
        </script>
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_url('js/datatables.post_init.js') ; ?>"></script>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <div id="update_version" style="display:none;"></div>
        <div id="content">
            <div id="separator"></div>
            <?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>
            <div id="right_column">
                <div id="content_header" class="content_header">
                    <div style="float: left;">
                        <img src="<?php echo osc_current_admin_theme_url('images/pages-icon.png') ; ?>" alt="" title=""/>
                    </div>
                    <div id="content_header_arrow">&raquo; <?php _e('Emails & alerts'); ?></div>
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