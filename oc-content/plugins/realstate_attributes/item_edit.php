<h3><?php _e('Realestate attributes' ); ?></h3>
<div>
    <table>
        <tr>
            <td><label for="property_type"><?php _e('Type'); ?></label></td>
            <td>
                <select name="property_type" id="property_type">
                    <option value="FOR RENT" <?php if($detail['e_status']=='FOR RENT') { echo "selected";};?>><?php _e('For rent'); ?></option>
                    <option value="FOR SALE" <?php if($detail['e_status']=='FOR SALE') { echo "selected";};?>><?php _e('For sale'); ?></option>
                </select>
            </td>
        </tr>
        <tr>
<?php
        $locales = Locale::newInstance()->listAllEnabled();
        if(count($locales)==1) {
?>
            <p>
                <label><?php _e('Property type'); ?></label><br />
                <select name="p_type" id="p_type">
                <?php foreach($p_type[$locales[0]['pk_c_code']] as $k => $v) { ?>
                    <option value="<?php echo  $k; ?>" <?php if($k==$detail['fk_i_property_type_id']) { echo 'selected';};?>><?php echo  @$v;?></option>
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
                            <option value="<?php echo  $k; ?>" <?php if($k==$detail['fk_i_property_type_id']) { echo 'selected';};?>><?php echo  @$v;?></option>
                        <?php }; ?>
                        </select>
                    </p>
                </div>
            <?php }; ?>
            </div>
        <?php }; ?>
        </tr>
        <tr>
            <td><label for="numRooms"><?php _e('Num. of rooms'); ?></label></td>
            <td>
                <select name="numRooms" id="numRooms">
                <?php foreach(range(0, 15) as $n) { ?>
                    <option value="<?php echo $n; ?>" <?php if($n==$detail['i_num_rooms']) { echo "selected";};?>><?php echo $n; ?></option>
                <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><label for="numBathrooms"><?php _e('Num. of bathrooms'); ?></label></td>
            <td>
                <select name="numBathrooms" id="numBathrooms">
                <?php foreach(range(0, 15) as $n): ?>
                    <option value="<?php echo $n; ?>" <?php if($n==$detail['i_num_bathrooms']) { echo "selected";};?>><?php echo $n; ?></option>
                <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><label for="status"><?php _e('Status'); ?></label></td>
            <td>
                <select name="status" id="status">
                    <option value="NEW CONSTRUCTION" <?php if($detail['e_status']=='NEW CONSTRUCTION') { echo "selected";};?>><?php _e('New construction'); ?></option>
                    <option value="TO RENOVATE" <?php if($detail['e_status']=='TO RENOVATE') { echo "selected";};?>><?php _e('To renovate'); ?></option>
                    <option value="GOOD CONDITION" <?php if($detail['e_status']=='GOOD CONDITION') { echo "selected";};?>><?php _e('Good condition'); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <td><label for="squareMeters"><?php _e('Square meters'); ?></label></td>
            <td><input type="text" name="squareMeters" id="squareMeters" value="<?php echo  $detail['s_square_meters'];?>" size="4" maxlength="4" /></td>
        </tr>
        <tr>
            <td><label for="year"><?php _e('Construction Year'); ?></label></td>
            <td><input type="text" name="year" id="year" value="<?php echo  $detail['i_year'];?>" size="4" maxlength="4" /></td>
        </tr>
        <tr>
            <td><label for="squareMetersTotal"><?php _e('Square meters (total)'); ?></label></td>
            <td><input type="text" name="squareMetersTotal" id="squareMetersTotal" value="<?php echo  $detail['i_plot_area'];?>" size="4" maxlength="6" /></td>
        </tr>
        <tr>
            <td><label for="numFloors"><?php _e('Num. of floors'); ?></label></td>
            <td>
                <select name="numFloors" id="numFloors">
                <?php foreach(range(0, 15) as $n) { ?>
                    <option value="<?php echo $n; ?>" <?php if($n==$detail['i_num_floors']) { echo "selected";};?>><?php echo $n; ?></option>
		<?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><label for="numGarages"><?php _e('Num. of garages (place for a car = one garage)'); ?></label></td>
            <td>
                <select name="numGarages" id="numGarages">
                <?php foreach(range(0, 15) as $n) { ?>
                    <option value="<?php echo $n; ?>" <?php if($n==$detail['i_num_garages']) { echo "selected";};?>><?php echo $n; ?></option>
                <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><label for="condition"><?php _e('Condition'); ?></label></td>
            <td><input type="text" name="condition" id="condition" value="<?php echo  $detail['s_condition'];?>" /></td>
        </tr>
        <tr>
            <td><label for="agency"><?php _e('Agency'); ?></label></td>
            <td><input type="text" name="agency" id="agency" value="<?php echo  $detail['s_agency'];?>" /></td>
        </tr>
        <tr>
            <td><label for="floorNumber"><?php _e('Floor Number'); ?></label></td>
            <td><input type="text" name="floorNumber" id="floorNumber" value="<?php echo  $detail['i_floor_number'];?>" /></td>
        </tr>
        <tr>
            <td><?php _e('Other characteristics'); ?></td>
            <td>
                <input type="checkbox" name="heating" id="heating" value="1" <?php if($detail['b_heating']==1) {echo 'checked="yes"';};?>/> <label for="heating"><?php _e('Heating'); ?></label><br />
                <input type="checkbox" name="airCondition" id="airCondition" value="1" <?php if($detail['b_air_condition']==1) {echo 'checked="yes"';};?>/> <label for="airCondition"><?php _e('Air condition'); ?></label><br />
                <input type="checkbox" name="elevator" id="elevator" value="1" <?php if($detail['b_elevator']==1) {echo 'checked="yes"';};?>/> <label for="elevator"><?php _e('Elevator'); ?></label><br />
                <input type="checkbox" name="terrace" id="terrace" value="1" <?php if($detail['b_terrace']==1) {echo 'checked="yes"';};?>/> <label for="terrace"><?php _e('Terrace'); ?></label><br />
                <input type="checkbox" name="parking" id="parking" value="1" <?php if($detail['b_parking']==1) {echo 'checked="yes"';};?>/> <label for="parking"><?php _e('Parking'); ?></label><br />
                <input type="checkbox" name="furnished" id="furnished" value="1" <?php if($detail['b_furnished']==1) {echo 'checked="yes"';};?>/> <label for="furnished"><?php _e('Furnished'); ?></label><br />
                <input type="checkbox" name="new" id="new" value="1" <?php if($detail['b_new']==1) {echo 'checked="yes"';};?>/> <label for="new"><?php _e('New'); ?></label><br />
                <input type="checkbox" name="by_owner" id="by_owner" value="1" <?php if($detail['b_by_owner']==1) {echo 'checked="yes"';};?>/> <label for="by_owner"><?php _e('By owner'); ?></label><br />
            </td>
        </tr>
        <?php $locales = Locale::newInstance()->listAllEnabled();
        if(count($locales)==1) { ?>
            <p>
                <label for="transport"><?php _e('Transport'); ?></label><br />
                <input type="text" name="<?php echo $locales[0]['pk_c_code']; ?>#transport" id="transport" style="width: 100%;" value="<?php echo  $detail['locale'][$locales[0]['pk_c_code']]['s_transport']; ?>" />
            </p>
            <p>
                <label for="zone"><?php _e('Zone'); ?></label><br />
                <input type="text" name="<?php echo $locales[0]['pk_c_code']; ?>#zone" id="zone" style="width: 100%;" value="<?php echo  $detail['locale'][$locales[0]['pk_c_code']]['s_zone']; ?>"/>
            </p>
        <?php } else { ?>
            <div class="tabber">
            <?php foreach($locales as $locale) {?>
                <div class="tabbertab">
                    <h2><?php echo $locale['s_name']; ?></h2>
                    <p>
                        <label for="transport"><?php _e('Transport'); ?></label><br />
                        <input type="text" name="<?php echo $locale['pk_c_code']; ?>#transport" id="transport" style="width: 100%;" value="<?php echo  @$detail['locale'][$locale['pk_c_code']]['s_transport']; ?>" />
                    </p>
                    <p>
                        <label for="zone"><?php _e('Zone'); ?></label><br />
                        <input type="text" name="<?php echo $locale['pk_c_code']; ?>#zone" id="zone" style="width: 100%;" value="<?php echo  @$detail['locale'][$locale['pk_c_code']]['s_zone']; ?>"/>
                    </p>
                </div>
            <?php }; ?>
            </div>
        <?php }; ?>
    </table>
</div>