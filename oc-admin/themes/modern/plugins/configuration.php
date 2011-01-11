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
<script type="text/javascript">

	function checkAll (frm, check) {
		var aa = document.getElementById(frm);
		for (var i = 0 ; i < aa.elements.length ; i++) {
			aa.elements[i].checked = check;
		}
	}

	function checkCat(id, check) {
		var lay = document.getElementById("cat" + id);
		inp = lay.getElementsByTagName("input");
		for (var i = 0, maxI = inp.length ; i < maxI; ++i) {
			if(inp[i].type == "checkbox") {
				inp[i].checked = check;
			}
		}
	}

</script>
	<div id="content">
		<div id="separator"></div>	
		<?php include_once $absolute_path . '/include/backoffice_menu.php'; ?>
		
	    <div id="right_column">

			<div id="content_header" class="content_header">
				<div style="float: left;"><img src="<?php echo  $current_theme; ?>/images/back_office/plugins-icon.png" /></div>
				<div id="content_header_arrow">&raquo; <?php echo __('Plugins'); ?></div>
				<a href="?action=add" id="button_open"><?php echo osc_lowerCase(__('Add a new plugin')); ?></a>
				<div style="clear: both;"></div>
			</div>
			<?php osc_showFlashMessages(); ?>

			<div id="content_separator"></div>
			<div id="TableToolsToolbar">
			
			</div>
			
			<div>

			<?php 
			$categories = Category::newInstance()->toTreeAll();
			$dao_pluginCategory = new PluginCategory() ;
			//#DEV.CONQUER: we only need 'fk_i_category_id'
			$selected = $dao_pluginCategory->listSelected($plugin_data['short_name']) ;
			$numCols = 1;
			$catsPerCol = round(count($categories)/$numCols);

			?>
			<form id="frm3" action="plugins.php" method="post">
			<input type="hidden" name="action" value="configure_post" />
			<input type="hidden" name="plugin" value="<?php echo $plugin_data['filename']; ?>" />
			<input type="hidden" name="plugin_short_name" value="<?php echo $plugin_data['short_name']; ?>" />

			<p>
			<?php echo "<b>".$plugin_data['plugin_name']."</b>,<br/>".$plugin_data['description']; ?>
			<br/>
			<?php echo __('Select the categories you want to apply those attributes:'); ?>
			</p>
			<p>
			<table>
			<tr style="vertical-align: top;">
			<td style="font-weight: bold;" colspan="<?php echo $numCols; ?>">
			<label for="categories">Presets categories</label><br />
			<a style="font-size: x-small; color: gray;" href="#" onclick="checkAll('frm3', true); return false;">Check all</a> - <a style="font-size: x-small; color: gray;" href="#" onclick="checkAll('frm3', false); return false;">Uncheck all</a>
			</td>
			<td>
			    <?php CategoryForm::plugin_categories($categories, $selected); ?>
			</td>
			<?php /* for ($j = 0 ; $j < $numCols ; $j++) {?>
				<td>
				<?php for ($i = $catsPerCol*$j ; $i < $catsPerCol*($j+1) ; $i++) {?>
					<?php if (is_array($categories[$i])) { ?>
					<br /><input type="checkbox" name="categories[]" value="<?php echo $categories[$i]['pk_i_id']; ?>" style="float:left;" onclick="javascript:checkCat('<?php echo $categories[$i]['pk_i_id'];?>', this.checked);" <?php if(in_array($categories[$i]['pk_i_id'], $selected)) {echo 'checked'; }; ?>><span style="font-size:25px"><?php echo $categories[$i]['s_name']; ?></span></input><br />
					<div id="cat<?php echo $categories[$i]['pk_i_id'];?>">
					<?php foreach($categories[$i]['categories'] as $sc): ?>
						&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="categories[]" value="<?php echo $sc['pk_i_id']; ?>" <?php if(in_array($categories[$i]['pk_i_id'], $selected)) {echo 'checked'; }; ?>><?php echo $sc['s_name']; ?></input><br />
					<?php endforeach; ?>
					</div>
					<?php } ?>
				<?php } ?>
				</td>
			<?php } //FIRST FOR's END 
			    */
			    ?>
			</tr>
			</table>
			</p>

			<p>
			<input class="Button" type="button" onclick="window.history.go(-1);" value="<?php echo __('Cancel'); ?>" />
			<input class="Button" type="submit" value="<?php echo __('Save'); ?>" />
			</p>

			</form>

			</div>
			<br />
			<div style="clear: both;"></div>

		</div> <!-- end of right column -->
