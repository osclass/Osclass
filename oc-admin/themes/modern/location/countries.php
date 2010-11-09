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
    $(document).ready(function(){
        $("#i_country").autocomplete({
            source: function( text, add ) {
                $.ajax({
                    "url": "http://geo.osclass.org/geo.services.php?callback=?&action=country&max=5",
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
<div id="content">
    <div id="separator"></div>
    <?php include_once $absolute_path . '/include/backoffice_menu.php'; ?>
    <div id="right_column">
        <div id="content_header" class="content_header">
            <div style="float: left;">
                <img src="<?php echo $current_theme; ?>/images/back_office/settings-icon.png" alt="" title=""/>
            </div>
            <div id="content_header_arrow">&raquo; <?php _e('Locations'); ?></div>
            <div style="clear: both;"></div>
        </div>

        <div id="content_separator"></div>
        <?php osc_showFlashMessages() ; ?>
        <!-- settings form -->
        <div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
            <div style="padding: 20px;">
                <div style="float: left; width: 50%;">
                    <fieldset>
                        <legend><?php _e('Countries'); ?></legend>
                        <form name="countries_form" id="countries_form" action="location.php" method="POST" enctype="multipart/form-data" >
                            <input type="hidden" name="action" value="countries_edit" />
                            <ul>
                            <?php foreach($aCountries as $country) { ?>
                                <li><input name="country[<?php echo $country['pk_c_code'];?>][en_US]" id="<?php echo $country['pk_c_code'];?>" type="text" value="<?php echo $country['s_name']; ?>" /> <a href="location.php?action=country_delete&id=<?php echo $country['pk_c_code']; ?>" ><button><?php _e('Delete'); ?></button></a></li>
                            <?php } ?>
                            </ul>
                            <button type="submit"><?php _e('Edit');?></button>
                        </form>
                    </fieldset>
                </div>

                <div style="float: left; width: 50%;">
                    <fieldset>
                        <legend><?php _e('Add new Country'); ?></legend>
                        <form name="country_form" id="country_form" action="location.php" method="POST" enctype="multipart/form-data" >
                            <input type="hidden" name="action" value="countries_add" />
                            <input type="text" id="i_country" name="i_country" value=""/>
                            <br/>
                            <button type="submit" ><?php _e('Add new'); ?></button>
                        </form>
                    </fieldset>
                </div>

                <div style="clear: both;"></div>										
            </div>
        </div>
        <!--
        <div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
            <div style="padding: 20px;">
                <div style="float: left; width: 100%;">
                    <fieldset style="border: 1px solid #ff0000;">
                    <legend><?php _e('Warning'); ?></legend>
                        <p>
                            <?php _e('Deleting countries may end in errors. Some of those countries could be attached to some actual items.') ; ?>
                        </p>
                    </fieldset>
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>
        -->
    </div>
</div>