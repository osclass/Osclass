<?php

/*
 *      OSCLass – software for creating and publishing online classified
 *                           advertising platforms
 *
 *                        Copyright (C) 2010 OSCLASS
 *
 *       This program is free software: you can redistribute it and/or
 *     modify it under the terms of the GNU Affero General Public License
 *     as published by the Free Software Foundation, either version 3 of
 *            the License, or (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful, but
 *         WITHOUT ANY WARRANTY; without even the implied warranty of
 *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *             GNU Affero General Public License for more details.
 *
 *      You should have received a copy of the GNU Affero General Public
 * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
?>

<?php defined('ABS_PATH') or die(__('Invalid OSClass request.')); ?>

<script>
	$(function() {
		// Remove border-bottom from last tr :)
		$('#registry tbody tr:last td').each(function() {
			$(this).css('border', '0');
		});
	});
</script>
<div id="content">
    <div id="separator"></div>

    <?php include_once $absolute_path . '/include/backoffice_menu.php' ; ?>

    <div id="right_column">
        <div id="content_header" class="content_header">
            <div style="float: left;"><img src="<?php echo  $current_theme; ?>/images/back_office/settings-icon.png" /></div>
            <div id="content_header_arrow">&raquo; <?php _e('Registry') ; ?></div>
            <div style="clear: both;"></div>
        </div>

        <div id="content_separator"></div>

        <?php osc_showFlashMessages(); ?>

        <!-- settings form -->
        <div id="settings_form" style="border: 1px solid #ccc; background: #eee;">
            <div style="padding: 20px;">

                <div><?php _e('The registry is a place within OSClass where all the preferences from plugins, themes and more are saved. These values should be modified by the plugins or themes, this is just an informational table.'); ?></div>
                <br />

                <table id="registry" cellpadding="2" cellspacing="2">
                    <thead>
                        <tr >
                            <th><?php _e('Section') ; ?></th>
                            <th><?php _e('Name') ; ?></th>
                            <th><?php _e('Value') ; ?></th>
                            <th><?php _e('Type') ; ?></th>
                        </tr>
                    </thead>
                    <?php foreach($preferencesTable as $pref) { ?>
                        <tr>
                            <td style="font-weight: bold;"><?php echo $pref['s_section'] ; ?></td>
                            <td><?php echo $pref['s_name'] ; ?></td>
                            <td><?php echo $pref['s_value'] ; ?></td>
                            <td><?php echo $pref['e_type'] ; ?></td>
                        </tr>
                    <?php } ?>
                    <tfoot>
                        <tr>
                            <th><?php _e('Section') ; ?></th>
                            <th><?php _e('Name') ; ?></th>
                            <th><?php _e('Value') ; ?></th>
                            <th><?php _e('Type') ; ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>