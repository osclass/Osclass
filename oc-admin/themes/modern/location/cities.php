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

<?php defined('APP_PATH') or die(__('Invalid OSClass request.')); ?>

<?php
$dateFormats = array('F j, Y', 'Y/m/d', 'm/d/Y', 'd/m/Y');
$timeFormats = array('g:i a', 'g:i A', 'H:i');
?>
<script>
	$(function() {
		// Here we include specific jQuery, jQuery UI and Datatables functions.
	});
</script>
		<div id="content">
                    <div id="separator"></div>

			<?php include_once $absolute_path . '/include/backoffice_menu.php'; ?>

		    <div id="right_column">
				<div id="content_header" class="content_header">
					<div style="float: left;"><img src="<?php echo  $current_theme; ?>/images/back_office/settings-icon.png" /></div>
					<div id="content_header_arrow">&raquo; <?php echo __('Locations'); ?></div>
					<div style="clear: both;"></div>
				</div>
				
				<div id="content_separator"></div>
				<?php osc_showFlashMessages(); ?>
				<!-- settings form -->
				<div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
					<div style="padding: 20px;">

						<div style="float: left; width: 50%;">
                            <fieldset>
                            <legend><?php echo __('Cities'); ?></legend>
                            <form name="cities_form" id="cities_form" action="location.php" method="GET" enctype="multipart/form-data" >
                            <input type="hidden" name="action" value="cities_edit" />
                            <select name="countryId" id="countryId" onchange="location.href = 'location.php?action=cities&countryId=' + this.value" >
                                <option value=""><?php echo  __('Select a country'); ?></option>
                                <?php foreach($aCountries as $a): ?>
                                <option value="<?php echo $a['pk_c_code']; ?>" <?php if(isset($_REQUEST['countryId']) && $_REQUEST['countryId']!="" && $_REQUEST['countryId']==$a['pk_c_code']) { echo 'selected'; };?>><?php echo $a['s_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select name="regionId" id="regionId" onchange="location.href = 'location.php?action=cities<?php if(isset($_REQUEST['countryId'])) { echo '&countryId='.$_REQUEST['countryId']; }; ?>&regionId=' + this.value" >
                                <option value=""><?php echo  __('Select a region'); ?></option>
                                <?php foreach($aRegions as $a): ?>
                                <option value="<?php echo $a['pk_i_id']; ?>" <?php if(isset($_REQUEST['regionId']) && $_REQUEST['regionId']!="" && $_REQUEST['regionId']==$a['pk_i_id']) { echo 'selected'; };?>><?php echo $a['s_name']; ?></option>
                                <?php endforeach; ?>
                            </select>

                            <ul>
                            <?php
                                if(isset($_REQUEST['countryId'])) { $string = '&countryId='.$_REQUEST['countryId']; } else { $string=""; };
                                if(isset($_REQUEST['regionId'])) { $string .='&regionId='.$_REQUEST['regionId']; };
                                foreach($aCities as $city) {
                                    echo '<li><input name="city['.$city['pk_i_id'].']" id="'.$city['pk_i_id'].'" type="text" value="'.$city['s_name'].'" /> <a href="location.php?action=city_delete&id='.$city['pk_i_id'].$string.'" ><button>'.__('Delete').'</button></a> </li>';                                }
                            ?>
                            </ul>
                            <button type="submit"><?php echo  __('Edit');?></button>
                            </form>
                            </fieldset>
						</div>
						<div style="float: left; width: 50%;">
                            <fieldset>
                            <legend><?php echo __('Add new city'); ?></legend>
                            <!--<form name="cities_form" id="cities_form" action="location.php" method="GET" enctype="multipart/form-data" >
                            <input type="hidden" name="action" value="cities_add" />
                                <?php if(isset($_REQUEST['countryId']) && $_REQUEST['countryId']!='') { ?>
                                    <input type="hidden" name="countryId" value="<?php echo  $_REQUEST['countryId'];?>" />
                                    <?php if(isset($_REQUEST['regionId']) && $_REQUEST['regionId']!='') { ?>
                                        <input type="hidden" name="regionId" value="<?php echo  $_REQUEST['regionId'];?>" />
                                        <input name="city" id="city" value="" /><button type="submit" ><?php echo  __('Add new'); ?></button>
                                    <?php } else { ?>
                                        <label><?php echo __('Select a region first.');?></label>
                                    <?php }; ?>
                                <?php } else { ?>
                                    <label><?php echo __('Select a country first.');?></label>
                                <?php }; ?>
                            </form>-->
                            <?php _e('Disable for now'); ?>
                            </fieldset>
                        </div>

                        <div style="clear: both;"></div>
												
                        <!--<input id="button_save" type="submit" value="<?php echo __('Update'); ?>" />-->

                    </form>

                </div>
            </div>
            </div> <!-- end of right column -->
