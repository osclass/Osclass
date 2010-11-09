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
<script type="text/javascript">
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
            <div id="content_header_arrow">&raquo; <?php _e('Locations'); ?></div>
            <div style="clear: both;"></div>
        </div>
				
        <div id="content_separator"></div>
        <?php osc_showFlashMessages(); ?>
        <!-- settings form -->
        <div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
            <div style="padding: 20px;">
                <div style="float: left; width: 50%;">
                    <fieldset>
                        <legend><?php _e('Regions'); ?></legend>
                        <form name="regions_form" id="regions_form" action="location.php" method="GET" enctype="multipart/form-data" >
                            <input type="hidden" name="action" value="regions_edit" />
                            <select name="countryId" id="countryId" onchange="location.href = 'location.php?action=regions&countryId=' + this.value" >
                                <option value=""><?php _e('Select a country'); ?></option>
                                <?php foreach($aCountries as $a) {?>
                                <option value="<?php echo $a['pk_c_code']; ?>" <?php if(isset($_REQUEST['countryId']) && $_REQUEST['countryId']!="" && $_REQUEST['countryId']==$a['pk_c_code']) { echo 'selected'; };?>><?php echo $a['s_name']; ?></option>
                                <?php } ?>
                            </select>
                            <ul>
                            <?php
                                if(isset($_REQUEST['countryId'])) { $string = '&countryId='.$_REQUEST['countryId']; } else { $string=""; };
                                foreach($aRegions as $region) {
                                    echo '<li><input name="region['.$region['pk_i_id'].']" id="'.$region['pk_i_id'].'" type="text" value="'.$region['s_name'].'" /> <a href="location.php?action=region_delete&id='.$region['pk_i_id'].$string.'" ><button>'.__('Delete').'</button></a> </li>';
                                }
                            ?>
                            </ul>
                            <button type="submit"><?php _e('Edit');?></button>
                        </form>
                    </fieldset>
                </div>

                <div style="float: left; width: 50%;">
                    <fieldset>
                        <legend><?php _e('Add new region'); ?></legend>
                        <form name="regions_form" id="regions_form" action="location.php" method="POST" enctype="multipart/form-data" >
                            <input type="hidden" name="action" value="regions_add" />
                            <?php if(isset($_REQUEST['countryId']) && $_REQUEST['countryId']!='') { ?>
                            <script type="text/javascript">
                                $(document).ready(function(){
                                    $("#region").autocomplete({
                                        source: function( text, add ) {
                                            $.ajax({
                                                <?php $c = $mCountries->findByCode($_REQUEST['countryId']) ; ?>
                                                "url": 'http://geo.osclass.org/geo.services.php?callback=?&action=region&max=5&country=<?php echo $c["s_name"] ; ?>',
                                                "dataType": "jsonp",
                                                "data": text,
                                                success: function( json ) {
                                                    var suggestions = [];
                                                    if( json.length > 0 ) {
                                                        $.each(json, function(i, val){
                                                            suggestions.push(val.name);
                                                        });
                                                    } else {
                                                        suggestions.push('No matches found');
                                                    }
                                                    add(suggestions);
                                                }
                                            });
                                        }
                                    });
                                });
                            </script>
                            <input type="hidden" name="countryId" value="<?php echo  $_REQUEST['countryId'];?>" />
                            <input name="region" id="region" value="" /><button type="submit" ><?php _e('Add new'); ?></button>
                            <?php } else { ?>
                            <label><?php _e('Select a country first.');?></label>
                            <?php }; ?>
                        </form>
                    </fieldset>
                </div>

                <div style="clear: both;"></div>
												
                <!--<input id="button_save" type="submit" value="<?php _e('Update'); ?>" />-->
            </div>
        </div>
    </div> <!-- end of right column -->
</div>