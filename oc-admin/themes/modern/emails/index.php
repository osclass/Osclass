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
<?php
    $emails = $this->_get("emails");
    $prefLocale = $this->_get("prefLocale");
    $last = end($emails); $last_id = $last['pk_i_id'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <?php $this->osc_print_head() ; ?>
    </head>
    <body>
        <?php $this->osc_print_header() ; ?>
        <div id="update_version" style="display:none;"></div>
        <div class="Header"><?php _e('Emails & Alerts'); ?></div>              
        <div id="content">
            <div id="separator"></div>

            <?php include_once osc_current_admin_theme_path() . 'include/backoffice_menu.php'; ?>

            <div id="right_column">
                <?php
                    /* this is header for right side. */
                ?>
                <div id="content_header" class="content_header">
                    <div style="float: left;"><img src="<?php echo osc_current_admin_theme_url() ; ?>images/pages-icon.png" alt="" title=""/></div>
                    <div id="content_header_arrow">&raquo; <?php _e('Emails & Alerts'); ?></div>
                    <div style="clear: both;"></div>
                </div>

                <div id="content_separator"></div>
                <?php osc_show_flash_message('admin') ; ?>

                <div class="dataTables_wrapper">
                    <table cellpadding="0" cellspacing="0" border="0" class="display" id="datatables_list">
                        <thead>
                            <tr>
                                <th style="width: 30%; " class="sorting"><?php _e('Name'); ?></th>
                                <th class="sorting" style="border-right-width: 1px; border-right-style: solid; border-right-color: rgb(170, 170, 170); "><?php _e('Description'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $odd = 1;
                                foreach($emails as $email) {
                                
                                if($odd==1) {
                                    $odd_even = "odd";
                                    $odd = 0;
                                } else {
                                    $odd_even = "even";
                                    $odd = 1;
                                }
                                $body = array();
                                if(isset($email['locale'][$prefLocale]) && !empty($email['locale'][$prefLocale]['s_title'])) {
                                    $body = $email['locale'][$prefLocale];
                                } else {
                                    $body = current($email['locale']);
                                }
                                $p_body = str_replace("'", "\'", trim(strip_tags($body['s_title']), "\x22\x27"));
                            ?>
                                <tr class="<?php echo $odd_even;?>">
                                    <td><?php echo $email['s_internal_name']; ?><div><a href="<?php echo osc_admin_base_url(true); ?>?page=emails&action=edit&amp;id=<?php echo  $email["pk_i_id"] ?>">Edit</a></div></td>
                                    <td><?php echo $p_body; ?></td>
                                </tr>                          
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div style="clear: both;"></div>
            </div> <!-- end of right column -->
            <div style="clear: both;"></div>
        </div> <!-- end of container -->
        <?php $this->osc_print_footer() ; ?>
    </body>
</html>				
