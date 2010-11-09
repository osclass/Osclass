<script type="text/javascript">
    $(document).ready(function(){

        $("#make").change(function(){
            var make_id = $(this).val();
            var url = '<?php echo WEB_PATH . "/oc-content/plugins/cars_attributes/ajax.php?makeId="; ?>' + make_id;
            var result = '';
            var model_id = '';
            <?php
                if(isset($detail['model'])) {
            ?>
                    model_id = $detail['model'];
            <?php
                }
            ?>
            if(make_id != '') {
                $("#model").attr('disabled',false);
                $.ajax({
                    type: "POST",
                    url: url,
                    dataType: 'json',
                    success: function(data){
                        var length = data.length;
                        if(length > 0) {
                            result += '<option value=""><?php echo __("Select a model..."); ?></option>';
                            for(key in data) {
                                if(data[key].pk_i_id==model_id) {
                                    result += '<option value="' + data[key].pk_i_id + '" selected>' + data[key].s_name + '</option>';
                                } else {
                                    result += '<option value="' + data[key].pk_i_id + '">' + data[key].s_name + '</option>';
                                }
                            }
                        } else {
                            result += '<option value=""><?php echo __('No results') ?></option>';
                        }
                        $("#model").html(result);
                    }
                 });
             } else {
                $("#model").attr('disabled',true);
             }
        });
        
    });



</script>
<h3><?php _e('Cars attributes') ; ?></h3>

<table>
<tr>
	<td><label><?php echo __('Make'); ?></label></td>
	<td>
    <select name="make" id="make" >
        <option value=""><?php echo  __('Select a make'); ?></option>
		<?php foreach($make as $a): ?>
			<option value="<?php echo $a['pk_i_id']; ?>" <?php if($detail['fk_i_make_id']==$a['pk_i_id']) { echo 'selected';};?>><?php echo $a['s_name']; ?></option>
		<?php endforeach; ?>
	</select>
    </td>
</tr>
<tr>
	<td><label><?php echo __('Model'); ?></label></td>
	<td>
    <select name="model" id="model">
    <?php foreach($model as $a): ?>
        <option value="<?php echo $a['pk_i_id']; ?>"><?php echo $a['s_name']; ?></option>
    <?php endforeach; ?>
	</select>
    </td>
</tr>
<tr>
<?php $locales = Locale::newInstance()->listAllEnabled();
if(count($locales)==1) {
$locale = $locales[0]; ?>
    <p>
<label><?php echo __('Car type'); ?></label><br />
<select name="car_type" id="car_type">
<?php foreach($car_type[$locale['pk_c_code']] as $k => $v) { ?>
<option value="<?php echo  $k; ?>" <?php if($detail['fk_vehicle_type_id']==$k) { echo 'selected';};?>><?php echo  @$v;?></option>
<?php }; ?>
</select>
</p>

<?php } else { ?>
<div class="tabber">
<?php foreach($locales as $locale) {?>
<div class="tabbertab">
<h2><?php echo $locale['s_name']; ?></h2>

<p>
<label><?php echo __('Car type'); ?></label><br />
<select name="car_type" id="car_type">
<?php foreach($car_type[$locale['pk_c_code']] as $k => $v) { ?>
<option value="<?php echo  $k; ?>" <?php if($detail['fk_vehicle_type_id']==$k) { echo 'selected';};?>><?php echo  @$v;?></option>
<?php }; ?>
</select>
</p>

</div>
<?php }; ?>
</div>
<?php }; ?>
</tr>
<tr>
	<td><label><?php echo __('Year'); ?></label></td>
	<td><input type="text" name="year" id="year" value="<?php echo  $detail['i_year']; ?>"  size=4/></td>
</tr>
<tr>
	<td><label><?php echo __('Doors'); ?></label></td>
	<td>
    <select name="doors" id="doors">
		<?php foreach(range(3, 5) as $n): ?>
			<option value="<?php echo $n; ?>" <?php if($detail['i_doors']==$n) { echo 'selected';};?>><?php echo $n; ?></option>
		<?php endforeach; ?>
	</select>
    </td>
</tr>
<tr>
	<td><label><?php echo __('Seats'); ?></label></td>
	<td>
    <select name="seats" id="seats">
		<?php foreach(range(1, 17) as $n): ?>
			<option value="<?php echo $n; ?>" <?php if($detail['i_seats']==$n) { echo 'selected';};?>><?php echo $n; ?></option>
		<?php endforeach; ?>
	</select>
    </td>
</tr>
<tr>
	<td><label><?php echo __('Mileage'); ?></div>
</label></td>
	<td><input type="text" name="mileage" id="mileage" value="<?php echo  $detail['i_mileage']; ?>" /></td>
</tr>
<tr>
	<td><label><?php echo __('Engine size (cc)'); ?></label></td>
	<td><input type="text" name="engine_size" id="engine_size" value="<?php echo  $detail['i_engine_size']; ?>" /></td>
</tr>
<tr>
	<td><label><?php echo __('Num. Airbags'); ?></label></td>
	<td>
    <select name="num_airbags" id="num_airbags">
		<?php foreach(range(0, 8) as $n): ?>
			<option value="<?php echo $n; ?>" <?php if($detail['i_num_airbags']==$n) { echo 'selected';};?>><?php echo $n; ?></option>
		<?php endforeach; ?>
	</select>
    </td>
</tr>
<tr>
	<td><label><?php echo __('Transmission'); ?></label></td>
	<td><select name="transmission" id="transmission">
        <option value="MANUAL"<?php if($detail['e_transmission']=='MANUAL') { echo 'selected';};?>><?php echo  __('Manual');?></option>
        <option value="AUTO"<?php if($detail['e_transmission']=='AUTO') { echo 'selected';};?>><?php echo  __('Auto');?></option>
    </select></td>
</tr>
<tr>
	<td><label><?php echo __('Fuel'); ?></label></td>
	<td><select name="fuel" id="fuel">
        <option value="DIESEL" <?php if($detail['e_fuel']=='DIESEL') { echo 'selected';};?>><?php echo  __('Diesel');?></option>
        <option value="GASOLINE" <?php if($detail['e_fuel']=='GASOLINE') { echo 'selected';};?>><?php echo  __('Gasoline');?></option>
        <option value="ELECTRIC-HIBRID" <?php if($detail['e_fuel']=='ELECTRIC-HIBRID') { echo 'selected';};?>><?php echo  __('Electric-hibrid');?></option>
        <option value="OTHER" <?php if($detail['e_fuel']=='OTHER') { echo 'selected';};?>><?php echo  __('Other');?></option>
    </select></td>
</tr>
<tr>
	<td><label><?php echo __('Seller'); ?></label></td>
	<td><select name="seller" id="seller">
        <option value="DEALER"<?php if($detail['e_seller']=='DEALER') { echo 'selected';};?>><?php echo  __('Dealer');?></option>
        <option value="OWNER"<?php if($detail['e_seller']=='OWNER') { echo 'selected';};?>><?php echo  __('Owner');?></option>
    </select></td>
</tr>
<tr>
	<td>
	<input type="checkbox" name="warranty" id="warranty" value="1" <?php if($detail['b_warranty']==1) {echo 'checked="yes"';};?>/> <label><?php echo __('Warranty'); ?></label><br />
	<input type="checkbox" name="new" id="new" value="1" <?php if($detail['b_new']==1) {echo 'checked="yes"';};?>/> <label><?php echo __('New'); ?></label><br />
	</td>
</tr>
<tr>
	<td><label><?php echo __('Power'); ?></label></td>
	<td><input type="text" name="power" id="power" value="<?php echo  $detail['i_power']; ?>" /><select name="power_unit" id="power_unit">
        <option value="KW" <?php if($detail['e_power_unit']=='KW') { echo 'selected';};?>><?php echo  __('Kw');?></option>
        <option value="CV" <?php if($detail['e_power_unit']=='CV') { echo 'selected';};?>><?php echo  __('Cv');?></option>
        <option value="CH" <?php if($detail['e_power_unit']=='CH') { echo 'selected';};?>><?php echo  __('Ch');?></option>
        <option value="KM" <?php if($detail['e_power_unit']=='KM') { echo 'selected';};?>><?php echo  __('Km');?></option>
        <option value="HP" <?php if($detail['e_power_unit']=='HP') { echo 'selected';};?>><?php echo  __('Hp');?></option>
        <option value="PS" <?php if($detail['e_power_unit']=='PS') { echo 'selected';};?>><?php echo  __('Ps');?></option>
        <option value="PK" <?php if($detail['e_power_unit']=='PK') { echo 'selected';};?>><?php echo  __('Pk');?></option>
        <option value="CP" <?php if($detail['e_power_unit']=='CP') { echo 'selected';};?>><?php echo  __('Cp');?></option>
    </select></td>
</tr>
<tr>
	<td><label><?php echo __('Gears'); ?></label></td>
	<td> <select name="gears" id="gears">
		<?php foreach(range(1, 8) as $n): ?>
			<option value="<?php echo $n; ?>" <?php if($detail['i_gears']==$n) { echo 'selected';};?>><?php echo $n; ?></option>
		<?php endforeach; ?>
	</select>
    </td>
</tr>
</table>

