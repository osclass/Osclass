<style type="text/css">
    #slider { margin-right:10px; margin-left:10px;};
</style>

<script type="text/javascript">
    $(function() {
        $("#floor-range").slider({
            range: true,
            min: 1,
            max: 15,
            values: [1, 15],
            slide: function(event, ui) {
                $("#numFloor").val(ui.values[0] + ' - ' + ui.values[1]);
            }
        });
        $("#numFloor").val($("#floor-range").slider("values", 0) + ' - ' + $("#floor-range").slider("values", 1));
        $("#room-range").slider({
            range: true,
            min: 1,
            max: 10,
            values: [1, 10],
            slide: function(event, ui) {
                $("#rooms").val(ui.values[0] + ' - ' + ui.values[1]);
            }
        });
        $("#rooms").val($("#room-range").slider("values", 0) + ' - ' + $("#room-range").slider("values", 1));
        $("#bathroom-range").slider({
            range: true,
            min: 1,
            max: 5,
            values: [1, 5],
            slide: function(event, ui) {
                $("#bathrooms").val(ui.values[0] + ' - ' + ui.values[1]);
            }
        });
        $("#bathrooms").val($("#bathroom-range").slider("values", 0) + ' - ' + $("#bathroom-range").slider("values", 1));
        $("#garage-range").slider({
            range: true,
            min: 1,
            max: 5,
            values: [1, 5],
            slide: function(event, ui) {
                $("#garages").val(ui.values[0] + ' - ' + ui.values[1]);
            }
        });
        $("#garages").val($("#garage-range").slider("values", 0) + ' - ' + $("#garage-range").slider("values", 1));
        $("#year-range").slider({
            range: true,
            min: 1900,
            max: 2011,
            values: [1900, 2011],
            slide: function(event, ui) {
                $("#year").val(ui.values[0] + ' - ' + ui.values[1]);
            }
        });
        $("#year").val($("#year-range").slider("values", 0) + ' - ' + $("#year-range").slider("values", 1));
        $("#sq-range").slider({
            range: true,
            min: 5,
            max: 500,
            values: [5, 500],
            slide: function(event, ui) {
                $("#sq").val(ui.values[0] + ' - ' + ui.values[1]);
            }
        });
        $("#sq").val($("#sq-range").slider("values", 0) + ' - ' + $("#sq-range").slider("values", 1));
    });
</script>
<div>
    <table>
        <tr>
            <td><label for="property_type"><?php _e('Type'); ?></label></td>
            <td>
                <select name="property_type" id="property_type">
                    <option value="FOR RENT"><?php _e('For rent'); ?></option>
                    <option value="FOR SALE"><?php _e('For sale'); ?></option>
                </select>
            </td>
        </tr>
        <tr>
<?php
        $locales = Locale::newInstance()->listAllEnabled();
        if(count($locales)==1) {
            $locale = $locales[0];
?>
            <p>
                <label><?php _e('Property type'); ?></label><br />
                <select name="p_type" id="p_type">
                <?php foreach($p_type[$locale['pk_c_code']] as $k => $v) { ?>
                    <option value="<?php echo  $k; ?>"><?php echo  @$v;?></option>
                <?php }; ?>
                </select>
            </p>
        <?php } else { ?>
            <div class="tabber">
                <?php foreach($locales as $locale) {?>
                <div class="tabbertab">
                    <h2><?php echo $locale['s_name']; ?></h2>
                    <p>
                        <label><?php _e('Property type'); ?></label><br />
                        <select name="p_type" id="p_type">
                        <?php foreach($p_type[$locale['pk_c_code']] as $k => $v) { ?>
                            <option value="<?php echo  $k; ?>"><?php echo @$v;?></option>
                        <?php }; ?>
                        </select>
                    </p>
                </div>
                <?php }; ?>
            </div>
        <?php }; ?>
        </tr>
        <tr>
            <p>
                <label for="numFloor"><?php _e('Num. Floors Range'); ?></label>
                <input type="text" id="numFloor" name="numFloor" style="border:0; color:#f6931f; font-weight:bold;" readonly/>
            </p>
            <div id="slider" >
                <div id="floor-range"></div>
            </div>
        </tr>
        <tr>
            <p>
                <label for="rooms"><?php _e('Rooms Range'); ?></label>
                <input type="text" id="rooms" name="rooms" style="border:0; color:#f6931f; font-weight:bold;" readonly/>
            </p>
            <div id="slider" >
                <div id="room-range"></div>
            </div>
        </tr>
        <tr>
            <p>
                <label for="bathrooms"><?php _e('Bathrooms Range'); ?></label>
                <input type="text" id="bathrooms" name="bathrooms" style="border:0; color:#f6931f; font-weight:bold;" readonly/>
            </p>
            <div id="slider" >
                <div id="bathroom-range"></div>
            </div>
        </tr>
        <tr>
            <p>
                <label for="garages"><?php _e('Garages Range'); ?></label>
                <input type="text" id="garages" name="garages" style="border:0; color:#f6931f; font-weight:bold;" readonly/>
            </p>
            <div id="slider" >
                <div id="garage-range"></div>
            </div>
        </tr>
        <tr>
            <p>
                <label for="year"><?php _e('Construction year Range'); ?></label>
                <input type="text" id="year" name="year" style="border:0; color:#f6931f; font-weight:bold;" readonly/>
            </p>
            <div id="slider" >
                <div id="year-range"></div>
            </div>
        </tr>
        <tr>
            <p>
                <label for="sq"><?php _e('Square Meters Range'); ?></label>
                <input type="text" name="sq" id="sq" style="border:0; color:#f6931f; font-weight:bold;" readonly/>
            </p>
            <div id="slider" >
                <div id="sq-range"></div>
            </div>
        </tr>
        <tr>
            <td><?php _e('Other characteristics'); ?></td>
            <td>
                <input type="checkbox" name="heating" id="heating" value="1" /> <label for="heating"><?php _e('Heating'); ?></label><br />
                <input type="checkbox" name="airCondition" id="airCondition" value="1" /> <label for="airCondition"><?php _e('Air condition'); ?></label><br />
                <input type="checkbox" name="elevator" id="elevator" value="1" /> <label for="elevator"><?php _e('Elevator'); ?></label><br />
                <input type="checkbox" name="terrace" id="terrace" value="1" /> <label for="terrace"><?php _e('Terrace'); ?></label><br />
                <input type="checkbox" name="parking" id="parking" value="1" /> <label for="parking"><?php _e('Parking'); ?></label><br />
                <input type="checkbox" name="furnished" id="furnished" value="1" /> <label for="furnished"><?php _e('Furnished'); ?></label><br />
                <input type="checkbox" name="new" id="new" value="1" /> <label for="new"><?php _e('New'); ?></label><br />
                <input type="checkbox" name="by_owner" id="by_owner" value="1" /> <label for="by_owner"><?php _e('By owner'); ?></label><br />
            </td>
        </tr>
    </table>
</div>

