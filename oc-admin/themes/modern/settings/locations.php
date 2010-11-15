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
    function check_form_country() {
        var error = 0;
        if ( $("#c_country").val().length != 2 ) {
            $("#c_country").css('border','1px solid red') ;
            error = error + 1;
        }
        if ( $("#country").val().length < 2 ) {
            $("#country").css('border','1px solid red') ;
            error = error + 1;
        }
        if(error > 0) {
            $('#c_code_error').css('display','block');
            return false;
        }
        return true;
    }

    function edit_countries(element) {
        var d_country = $('#d_edit_country');

        d_country.css('display','block');
        $('#fade').css('display','block');

        $("input[name=country_old]").val(element.html());
        $("input[name=e_country]").val(element.html());

        return false;
    }

    function edit_region(element, id) {
        var d_region = $('#d_edit_region');

        d_region.css('display','block');
        $('#fade').css('display','block');

        $("input[name=region_id]").val(id);
        $("input[name=e_region]").val(element.html());

        return false;
    }

    function edit_city(element, id) {
        var d_city = $('#d_edit_city');

        d_city.css('display','block');
        $('#fade').css('display','block');

        $("input[name=city_id]").val(id);
        $("input[name=e_city]").val(element.html());

        return false;
    }

    function show_region(c_code, s_country) {
        $.ajax({
            "url": "<?php echo ABS_WEB_URL; ?>/oc-includes/osclass/ajax/region.php?countryId=" + c_code,
            "dataType": 'json',
            success: function( json ) {
                var div_regions = $("#i_regions").html('');
                $('#i_cities').html('');
                $.each(json, function(i, val){
                    var clear = $('<div>').css('clear','both');
                    var container = $('<div>').css('padding','4px').css('width','90%');
                    var s_country = $('<div>').css('float','left');
                    var more_region = $('<div>').css('float','right');
                    var link = $('<a>');

                    s_country.append('<a href="javascript:void(0);" class="edit" onclick="edit_region($(this), ' + val.pk_i_id + ');" style="padding-right: 15px;">' + val.s_name + '</a>');
                    link.attr('href', 'javascript:void(0)').attr('onclick','show_city(' + val.pk_i_id + ')');
                    link.append('<?php _e('View more'); ?>  &raquo;');
                    more_region.append(link);
                    container.append(s_country).append(more_region);
                    div_regions.append(container);
                    div_regions.append(clear);
                });
            }
        });
        
        $('input[name=country_c_parent]').val(c_code);
        $('input[name=country_parent]').val(s_country);
        $('#b_new_region').css('display','block');
        $('#b_new_city').css('display','none');
        return false;
    }

    function show_city(id_region) {
        $.ajax({
            "url": "<?php echo ABS_WEB_URL; ?>/oc-includes/osclass/ajax/city.php?regionId=" + id_region,
            "dataType": 'json',
            success: function( json ) {
                var div_regions = $("#i_cities").html('');
                $.each(json, function(i, val){
                    var clear = $('<div>').css('clear','both');
                    var container = $('<div>').css('padding','4px').css('width','90%');
                    var s_country = $('<div>').css('float','left');

                    s_country.append('<a href="javascript:void(0);" class="edit" onclick="edit_city($(this), ' + val.pk_i_id + ');" style="padding-right: 15px;">' + val.s_name + '</a>');
                    container.append(s_country);
                    div_regions.append(container);
                    div_regions.append(clear);
                });
            }
        });
        $('#b_new_city').css('display','block');
        $('input[name=region_parent]').val(id_region);
        return false;
    }

    $(document).ready(function(){
        $("#c_country").focus(function(){
            $('#c_code_error').css('display','none');
           $(this).css('border','');
        });

        $("#country").focus(function(){
           $(this).css('border','');
        });

        $("#country").keyup(function(){
            if($('#country').val().length == 0) {
               $('input[name=c_manual]').val('1');
            }
        });

        var countries ;
        $("#country").autocomplete({
            source: function( text, add ) {
                $.ajax({
                    "url": "http://geo.osclass.org/geo.services.php?callback=?&action=country&max=5",
                    "dataType": "jsonp",
                    "data": text,
                    success: function( json ) {
                        var suggestions = [];
                        if( json.length > 0 ) {
                            countries = new Array();
                            $.each(json, function(i, val){
                                suggestions.push(val.name);
                                countries[val.name] = val.code;
                                $('input[name=c_manual]').val('0');
                            });
                        } else {
                            countries = new Array();
                            suggestions.push(text.term);
                            $('input[name=c_manual]').val('1');
                        }
                        add(suggestions);
                    }
                });
            },

            select: function(e, ui) {
                if ( typeof countries[ui.item.value] !== "undefined" && countries[ui.item.value]) {
                    $("#c_country").val(countries[ui.item.value]);
                } else {
                    $("#c_country").val('');
                }
            },

            selectFirst: true
        });

        var regions ;
        $("#region").autocomplete({
            source: function( text, add ) {
                $.ajax({
                    "url": 'http://geo.osclass.org/geo.services.php?callback=?&action=region&max=5&country=' + $('input[name=country_parent]').val(),
                    "dataType": "jsonp",
                    "data": text,
                    success: function( json ) {
                        var suggestions = [];
                        if( json.length > 0 ) {
                            regions = new Array();
                            $.each(json, function(i, val){
                                suggestions.push(val.name);
                                regions[val.name] = val.code;
                                $('input[name=r_manual]').val('0');
                            });
                        } else {
                            regions = new Array();
                            suggestions.push(text.term);
                            $('input[name=r_manual]').val('1');
                        }
                        add(suggestions);
                    }
                });
            },

            selectFirst: true
        });

        var cities ;
        $("#city").autocomplete({
            source: function( text, add ) {
                $.ajax({
                    "url": 'http://geo.osclass.org/geo.services.php?callback=?&action=city&max=5&country=' + $('input[name=country_parent]').val(),
                    "dataType": "jsonp",
                    "data": text,
                    success: function( json ) {
                        var suggestions = [];
                        if( json.length > 0 ) {
                            cities = new Array();
                            $.each(json, function(i, val){
                                suggestions.push(val.name);
                                cities[val.name] = val.code;
                                $('input[name=ci_manual]').val('0');
                            });
                        } else {
                            cities = new Array();
                            suggestions.push(text.term);
                            $('input[name=ci_manual]').val('1');
                        }
                        add(suggestions);
                    }
                });
            },

            selectFirst: true
        });

        $("#b_new_country").click(function(){
            $('#d_add_country').css('display','block') ;
            $('#fade').css('display','block') ;
        });
        $("#b_new_region").click(function(){
            $('#d_add_region').css('display','block') ;
            $('#fade').css('display','block') ;
        });
        $("#b_new_city").click(function(){
            $('#d_add_city').css('display','block') ;
            $('#fade').css('display','block') ;
        });
    });
</script>
<style type="text/css">
    .lightbox_country {
        display: none;
        position: absolute;
        top: 50%;
        left: 50%;
        width: 300px;
        height: 192px;
        border: 4px solid grey;
        margin: -100px 0 0 -180px;
        background-color: white;
        z-index:1002;
        overflow: auto;
    }
    .location h4 {
        margin: 0;
        padding:5px 10px;
        font-size: 18px;
        color: white;
        background: #333333;
    }
    .black_overlay{
        display: none;
        position: absolute;
        top: 0%;
        left: 0%;
        width: 100%;
        height: 100%;
        background-color: black;
        z-index:1001;
        -moz-opacity: 0.78;
        -webkit-opacity: 0.78;
        -khtml-opacity: 0.78;
        opacity:.78;
        filter: alpha(opacity=80);
    }

    a.edit {
        text-decoration: none;
    }

    a.edit:hover{
        background: url('<?php echo ABS_WEB_URL; ?>/oc-admin/images/edit.png') no-repeat right;
    }
</style>
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
        <div id="settings_form" style="border: 1px solid #ccc; background: #eee; min-height: 200px; ">
            <!-- Country -->
            <div style="float:left; width: 33%; ">
                <div style="border-bottom: 1px dashed black; padding: 4px 4px 0px; width: 90%;" >
                    <div style="float:left;">
                        <h3 style="">
                            <?php _e('Countries'); ?>
                        </h3>
                    </div>
                    <div style="float:right;">
                        <a id="b_new_country" href="javascript:void(0);"><?php _e('Add new'); ?></a>
                    </div>
                    <div style="clear:both;"></div>
                </div>
                <div id="l_countries" style="padding: 10px 0;">
                    <?php foreach( $aCountries as $country ) { ?>
                    <div style="padding: 4px; width: 90%;">
                        <div style="float:left;">
                            <div>
                                <a class="edit" href="javascript:void(0);" style="padding-right: 15px;" onclick="edit_countries($(this));"><?php echo $country['s_name']; ?></a>
                            </div>
                        </div>
                        <div style="float:right"><a href="javascript:void(0)" onclick="show_region('<?php echo $country['pk_c_code']; ?>', '<?php echo $country['s_name']; ?>')"><?php _e('View more'); ?> &raquo;</a></div>
                    </div>
                    <div style="clear:both;"></div>
                    <?php } ?>
                </div>
                <div id="i_countries">
                </div>
            </div>
            <!-- End country -->
            <!-- Region -->
            <div style="float:left; width: 33%; ">
                <div style="border-bottom: 1px dashed black; padding: 4px 4px 0px; width: 90%;" >
                    <div style="float:left;">
                        <h3 style="">
                            <?php _e('Regions'); ?>
                        </h3>
                    </div>
                    <div style="float:right;">
                        <a id="b_new_region" href="javascript:void(0);" style="display: none;"><?php _e('Add new'); ?></a>
                    </div>
                    <div style="clear:both;"></div>
                </div>
                <div id="i_regions" style="padding: 10px 0;">
                    
                </div>
            </div>
            <!-- End region -->
            <!-- City -->
            <div style="float:left; width: 33%; ">
                <div style="border-bottom: 1px dashed black; padding: 4px 4px 0px; width: 90%;" >
                    <div style="float:left;">
                        <h3 style="">
                            <?php _e('Cities'); ?>
                        </h3>
                    </div>
                    <div style="float:right;">
                        <a id="b_new_city" href="javascript:void(0);" style="display:none;"><?php _e('Add new'); ?></a>
                    </div>
                    <div style="clear:both;"></div>
                </div>
                <div id="i_cities"  style="padding: 10px 0;">
                    
                </div>
            </div>
            <!-- End city -->
            <div style="clear:both;"></div>
        </div>
    </div>
</div>
<!-- Form add country -->
<div id="d_add_country" class="lightbox_country location">
    <div>
        <h4><?php _e('Add new country') ; ?></h4>
    </div>
    <div style="padding: 14px;">
        <form action="settings.php?action=locations" method="POST" accept-charset="utf-8" onsubmit="return check_form_country();">
            <input type="hidden" name="action" value="locations" />
            <input type="hidden" name="type" value="add_country" />
            <input type="hidden" name="c_manual" value="1" />
            <table>
                <tr>
                    <td><?php _e('Country: '); ?></td>
                    <td><input type="text" id="country" name="country" value="" /></td>
                </tr>
                <tr>
                    <td><?php _e('Country code: '); ?></td>
                    <td><input type="text" id="c_country" name="c_country" value="" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td><small id="c_code_error" style="color: red; display: none;"><?php _e('Country code has two characters'); ?></small></td>
                </tr>
            </table>
            <div style="margin-top: 8px; text-align: right; ">
                <input type="button" value="<?php _e('Cancel'); ?>" onclick="$('#d_add_country').css('display','none');$('#fade').css('display','none');"/>
                <input type="submit" name="submit" value="<?php _e('Add'); ?>" />
            </div>
        </form>
    </div>
</div>
<!-- End form add country -->
<!-- Form edit country -->
<div id="d_edit_country" class="lightbox_country location" style="height: 140px;">
    <div>
        <h4><?php _e('Edit country') ; ?></h4>
    </div>
    <div style="padding: 14px;">
        <form action="settings.php?action=locations" method="POST" accept-charset="utf-8">
            <input type="hidden" name="action" value="locations" />
            <input type="hidden" name="type" value="edit_country" />
            <input type="hidden" name="country_old" value="" />
            <table>
                <tr>
                    <td><?php _e('Country: '); ?></td>
                    <td><input type="text" id="country" name="e_country" value="" /></td>
                </tr>
            </table>
            <div style="margin-top: 8px; text-align: right; ">
                <input type="button" value="<?php _e('Cancel'); ?>" onclick="$('#d_edit_country').css('display','none');$('#fade').css('display','none');"/>
                <input type="submit" name="submit" value="<?php _e('Edit'); ?>" />
            </div>
        </form>
    </div>
</div>
<!-- End form edit country -->
<!-- Form add region -->
<div id="d_add_region" class="lightbox_country location" style="height: 140px;">
    <div>
        <h4><?php _e('Add new region') ; ?></h4>
    </div>
    <div style="padding: 14px;">
        <form action="settings.php?action=locations" method="POST" accept-charset="utf-8">
            <input type="hidden" name="action" value="locations" />
            <input type="hidden" name="type" value="add_region" />
            <input type="hidden" name="country_c_parent" value="" />
            <input type="hidden" name="country_parent" value="" />
            <input type="hidden" name="r_manual" value="1" />
            <table>
                <tr>
                    <td><?php _e('Region: '); ?></td>
                    <td><input type="text" id="region" name="region" value="" /></td>
                </tr>
            </table>
            <div style="margin-top: 8px; text-align: right; ">
                <input type="button" value="<?php _e('Cancel'); ?>" onclick="$('#d_add_region').css('display','none');$('#fade').css('display','none');"/>
                <input type="submit" name="submit" value="<?php _e('Add'); ?>" />
            </div>
        </form>
    </div>
</div>
<!-- End form add region -->
<!-- Form edit region -->
<div id="d_edit_region" class="lightbox_country location" style="height: 140px;">
    <div>
        <h4><?php _e('Edit region') ; ?></h4>
    </div>
    <div style="padding: 14px;">
        <form action="settings.php?action=locations" method="POST" accept-charset="utf-8">
            <input type="hidden" name="action" value="locations" />
            <input type="hidden" name="type" value="edit_region" />
            <input type="hidden" name="region_id" value="" />
            <table>
                <tr>
                    <td><?php _e('Region: '); ?></td>
                    <td><input type="text" id="region" name="e_region" value="" /></td>
                </tr>
            </table>
            <div style="margin-top: 8px; text-align: right; ">
                <input type="button" value="<?php _e('Cancel'); ?>" onclick="$('#d_edit_region').css('display','none');$('#fade').css('display','none');"/>
                <input type="submit" name="submit" value="<?php _e('Edit'); ?>" />
            </div>
        </form>
    </div>
</div>
<!-- End form edit region -->
<!-- Form edit city -->
<div id="d_add_city" class="lightbox_country location" style="height: 140px;">
    <div>
        <h4><?php _e('Add new city') ; ?></h4>
    </div>
    <div style="padding: 14px;">
        <form action="settings.php?action=locations" method="POST" accept-charset="utf-8">
            <input type="hidden" name="action" value="locations" />
            <input type="hidden" name="type" value="add_city" />
            <input type="hidden" name="country_c_parent" value="" />
            <input type="hidden" name="country_parent" value="" />
            <input type="hidden" name="region_parent" value="" />
            <input type="hidden" name="ci_manual" value="1" />
            <table>
                <tr>
                    <td><?php _e('City: '); ?></td>
                    <td><input type="text" id="city" name="city" value="" /></td>
                </tr>
            </table>
            <div style="margin-top: 8px; text-align: right; ">
                <input type="button" value="<?php _e('Cancel'); ?>" onclick="$('#d_add_city').css('display','none');$('#fade').css('display','none');"/>
                <input type="submit" name="submit" value="<?php _e('Add'); ?>" />
            </div>
        </form>
    </div>
</div>
<!-- End form add city -->
<!-- Form edit city -->
<div id="d_edit_city" class="lightbox_country location" style="height: 140px;">
    <div>
        <h4><?php _e('Edit city') ; ?></h4>
    </div>
    <div style="padding: 14px;">
        <form action="settings.php?action=locations" method="POST" accept-charset="utf-8">
            <input type="hidden" name="action" value="locations" />
            <input type="hidden" name="type" value="edit_city" />
            <input type="hidden" name="city_id" value="" />
            <table>
                <tr>
                    <td><?php _e('City: '); ?></td>
                    <td><input type="text" id="region" name="e_city" value="" /></td>
                </tr>
            </table>
            <div style="margin-top: 8px; text-align: right; ">
                <input type="button" value="<?php _e('Cancel'); ?>" onclick="$('#d_edit_city').css('display','none');$('#fade').css('display','none');"/>
                <input type="submit" name="submit" value="<?php _e('Edit'); ?>" />
            </div>
        </form>
    </div>
</div>
<!-- End form edit city -->
<div id="fade" class="black_overlay"></div> 