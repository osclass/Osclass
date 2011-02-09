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

<script>
	$(function() {
		// Here we include specific jQuery, jQuery UI and Datatables functions.
		$("#button_cancel").click(function() {
			if(confirm('<?php _e('Are you sure you want to cancel?'); ?>')) {
				setTimeout ("window.location = 'appearance.php';", 100) ;
			}			
		});
	});
</script>

<div id="content">

    <div id="separator"></div>

    <?php include_once osc_current_admin_theme_path() . 'include/backoffice_menu.php' ; ?>

    <div id="right_column">
        <?php /* this is header for right side. */ ?>
        <div id="content_header" class="content_header">
            <div style="float: left;"><img src="<?php echo  osc_current_admin_theme_url() ; ?>images/back_office/themes-icon.png" /></div>
            <div id="content_header_arrow">&raquo; <?php _e('Appearance') ; ?></div>
            <a href="?action=add" id="button_open"><?php _e('Add a new theme') ; ?></a>
            <div style="clear: both;"></div>
        </div>
        
        <?php osc_show_flash_message() ; ?>

        <!-- list themes -->
        <div id="content_separator"></div>
        <div id="list_themes_div" style="border: 1px solid #ccc; background: #eee;">
            <div style="padding: 20px;">

                <div id="current_theme"><?php _e('Current theme'); ?></div>
                <div id="current_theme_pic">
                    <img src="<?php echo osc_base_url() ; ?>/oc-content/themes/<?php echo osc_theme() ; ?>/screenshot.png" style="width: 280px;" />
                </div>
                <div id="current_theme_info">
                    <strong><?php echo $info['name']; ?> <?php echo $info['version']; ?>. <?php _e('Author'); ?> <a target="_blank" href="<?php echo $info['author_url']; ?>"><?php echo $info['author_name']; ?></a></strong>
                </div>
                <div id="current_theme_desc"><?php echo $info['description']; ?></div>

                <div id="content_separator"></div>
                <div id="current_theme"><?php _e('Available themes'); ?></div>

                <?php $i = 1; $colnums = 2; $c = 1; ?>

                <div>
                    <?php foreach($themes as $theme) { ?>
                        <?php if( $theme != osc_theme() ) { ?>
                            <?php $info = osc_loadThemeInfo($theme); ?>
                            <center>
                                <div style="width: 49%; float: left; padding-top: 10px; padding-bottom: 20px; <?php if($c == 1) { ?> border-right: 1px solid #ccc;<?php } ?>">
                                    <div id="available_theme_info">
                                        <strong><?php echo $info['name']; ?> <?php echo $info['version']; ?> by <a href="<?php echo $info['author_url']; ?>"><?php echo $info['author_name']; ?></a></strong>
                                    </div>
                                    <div id="available_theme_actions">
                                        <a href="appearance.php?action=activate&amp;theme=<?php echo $theme ; ?>"><?php _e('Activate') ; ?></a> |
                                        <a target="_blank" href="<?php echo osc_base_url() ; ?>?action=testTheme&theme=<?php echo $theme ; ?>"><?php _e('Preview') ; ?></a> |
                                        <a id="theme_delete" onclick="javascript:return confirm('<?php _e('This action can not be undone. Are you sure you want to continue?'); ?>');" href="appearance.php?action=delete&amp;theme=<?php echo $theme ; ?>"><?php _e('Delete') ; ?></a>
                                    </div>
                                    <div id="available_theme_pic"><img src="<?php echo osc_base_url() ; ?>/oc-content/themes/<?php echo $theme ; ?>/screenshot.png" style="width: 280px;" /></div>
                                    <div id="available_theme_desc"><?php echo $info['description'] ; ?></div>
                                    <div style="clear: both;"></div>
                                </div>
                            </center>
                            <?php $c == $colnums ? $c = 1 : $c++; ?>
                        <?php } ?>
                    <?php } ?>

                    <?php if ($i == $colnums) echo '<div style="clear:both;"></div>'; $i = 1; if ($i != $colnums) $i++; ?>
                    <div style="clear:both;"></div>
                </div>
            </div>
        </div>
        <!-- end of list themes -->
        <div style="clear: both;"></div>
    </div> <!-- end of right column -->