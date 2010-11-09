<script type="text/javascript">
    $(document).ready(function(){

        $("#make").change(function(){
            var make_id = $(this).val();
            var url = '<?php echo WEB_PATH . "/oc-content/plugins/cars_attributes/ajax.php?makeId="; ?>' + make_id;
            var result = '';
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
                                result += '<option value="' + data[key].pk_i_id + '">' + data[key].s_name + '</option>';
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

        $("#model").attr('disabled',true);
        
    });

alert(checkFuncs);
    checkFuncs.push(function() {
        if(document.getElementById('make').value == "") {
            alert("You have to select a make.");
            return false;
        }

        if(document.getElementById('model').value == "") {
            alert("You have to select a model.");
            return false;
        }

        return true;
    })

</script>
<h3><?php _e('Cars attributes') ; ?></h3>

<table>
<tr>
	<td><label><?php _e('Make'); ?></label></td>
	<td>
    <select name="make" id="make" >
        <option value=""><?php  _e('Select a make'); ?></option>
		<?php foreach($make as $a): ?>
			<option value="<?php echo $a['pk_i_id']; ?>"><?php echo $a['s_name']; ?></option>
		<?php endforeach; ?>
	</select>
    </td>
</tr>
<tr>
	<td><label><?php _e('Model'); ?></label></td>
	<td>
    <select name="model" id="model">
	</select>
    </td>
</tr>
<tr>
<?php $locales = Locale::newInstance()->listAllEnabled();
if(count($locales)==1) {
$locale = $locales[0];?>
<p>
<label><?php _e('Car type'); ?></label><br />
<select name="car_type" id="car_type">
<?php foreach($car_type[$locale['pk_c_code']] as $k => $v) { ?>
<option value="<?php echo  $k; ?>"><?php echo  $v;?></option>
<?php }; ?>
</select>
</p>
<?php } else { ?>
<div class="tabber">
<?php foreach($locales as $locale) {?>
<div class="tabbertab">
<h2><?php echo $locale['s_name']; ?></h2>

<p>
<label><?php _e('Car type'); ?></label><br />
<select name="car_type" id="car_type">
<?php foreach($car_type[$locale['pk_c_code']] as $k => $v) { ?>
<option value="<?php echo  $k; ?>"><?php echo  $v;?></option>
<?php }; ?>
</select>
</p>

</div>
<?php }; ?>
</div>
<?php }; ?>
</tr>
<tr>
	<td><label><?php _e('Year'); ?></label></td>
	<td><input type="text" name="year" id="year" value=""  size=4/></td>
</tr>
<tr>
	<td><label><?php _e('Doors'); ?></label></td>
	<td>
    <select name="doors" id="doors">
		<?php foreach(range(3, 5) as $n): ?>
			<option value="<?php echo $n; ?>"><?php echo $n; ?></option>
		<?php endforeach; ?>
	</select>
    </td>
</tr>
<tr>
	<td><label><?php _e('Seats'); ?></label></td>
	<td>
    <select name="seats" id="seats">
		<?php foreach(range(1, 17) as $n): ?>
			<option value="<?php echo $n; ?>"><?php echo $n; ?></option>
		<?php endforeach; ?>
	</select>
    </td>
</tr>
<tr>
	<td><label><?php _e('Mileage'); ?></div>
</label></td>
	<td><input type="text" name="mileage" id="mileage" value="" /></td>
</tr>
<tr>
	<td><label><?php _e('Engine size (cc)'); ?></label></td>
	<td><input type="text" name="engine_size" id="engine_size" value="" /></td>
</tr>
<tr>
	<td><label><?php _e('Num. Airbags'); ?></label></td>
	<td>
    <select name="num_airbags" id="num_airbags">
		<?php foreach(range(0, 8) as $n): ?>
			<option value="<?php echo $n; ?>"><?php echo $n; ?></option>
		<?php endforeach; ?>
	</select>
    </td>
</tr>
<tr>
	<td><label><?php _e('Transmission'); ?></label></td>
	<td><select name="transmission" id="transmission">
        <option value="MANUAL"><?php _e('Manual');?></option>
        <option value="AUTO"><?php _e('Auto');?></option>
    </select></td>
</tr>
<tr>
	<td><label><?php _e('Fuel'); ?></label></td>
	<td><select name="fuel" id="fuel">
        <option value="DIESEL"><?php _e('Diesel');?></option>
        <option value="GASOLINE"><?php _e('Gasoline');?></option>
        <option value="ELECTRIC-HIBRID"><?php _e('Electric-hibrid');?></option>
        <option value="OTHER"><?php _e('Other');?></option>
    </select></td>
</tr>
<tr>
	<td><label><?php _e('Seller'); ?></label></td>
	<td><select name="seller" id="seller">
        <option value="DEALER"><?php _e('Dealer');?></option>
        <option value="OWNER"><?php _e('Owner');?></option>
    </select></td>
</tr>
<tr>
	<td>
	<input type="checkbox" name="warranty" id="warranty" value="1" /> <label><?php _e('Warranty'); ?></label><br />
	<input type="checkbox" name="new" id="new" value="1" /> <label><?php _e('New'); ?></label><br />
	</td>
</tr>
<tr>
	<td><label><?php _e('Power'); ?></label></td>
	<td><input type="text" name="power" id="power" value="" /><select name="power_unit" id="power_unit">
        <option value="KW"><?php _e('Kw');?></option>
        <option value="CV"><?php _e('Cv');?></option>
        <option value="CH"><?php _e('Ch');?></option>
        <option value="KM"><?php _e('Km');?></option>
        <option value="HP"><?php _e('Hp');?></option>
        <option value="PS"><?php _e('Ps');?></option>
        <option value="PK"><?php _e('Pk');?></option>
        <option value="CP"><?php _e('Cp');?></option>
    </select></td>
</tr>
<tr>
	<td><label><?php _e('Gears'); ?></label></td>
	<td> <select name="gears" id="gears">
		<?php foreach(range(1, 8) as $n): ?>
			<option value="<?php echo $n; ?>"><?php echo $n; ?></option>
		<?php endforeach; ?>
	</select>
    </td>
</tr>
</table>

<script type="text/javascript">
    tabberAutomatic();
</script>
