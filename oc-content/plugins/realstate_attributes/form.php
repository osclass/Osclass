<h3><?php _e('Realestate attributes') ; ?></h3>
<div class="caja_contenedor">
	<div class="caja_contenido">
	
		<div class="bloque">
			<label for="property_type"><?php _e('Type'); ?></label>
			<select name="property_type" id="property_type">
				<option value="FOR RENT"><?php _e('For rent'); ?></option>
				<option value="FOR SALE"><?php _e('For sale'); ?></option>
			</select>
		</div>
<?php
            $locales = Locale::newInstance()->listAllEnabled();
            if(count($locales)==1) {
?>
		<p class="bloque">
			<label><?php _e('Property type'); ?></label>
			<select name="p_type" id="p_type">
			<?php foreach($p_type[$locales[0]['pk_c_code']] as $k => $v) { ?>
				<option value="<?php echo @$k; ?>"><?php echo @$v;?></option>
			<?php }; ?>
			</select>
		</p>
		
<?php } else { ?>
        
		<div class="tabber bloque">
			<?php foreach($locales as $locale) {?>
			<div class="tabbertab">
				<h2><?php echo $locale['s_name']; ?></h2>
				<p class="bloque">
					<label><?php _e('Property type'); ?></label><br />
					<select name="p_type" id="p_type">
					<?php foreach($p_type[$locale['pk_c_code']] as $k => $v) { ?>
						<option value="<?php echo @$k; ?>"><?php echo @$v;?></option>
					<?php }; ?>
					</select>
				</p>
			</div>
			<?php }; ?>
		</div>
	<?php }; ?>


			
		<p class="bloque">
			<label for="numRooms"><?php _e('Num. of rooms'); ?></label>
			<select name="numRooms" id="numRooms">
			<?php foreach(range(0, 15) as $n) { ?>
				<option value="<?php echo $n; ?>"><?php echo $n; ?></option>
			<?php } ?>
			</select>

            <label for="numBathrooms"><?php _e('Num. of bathrooms'); ?></label>
			<select name="numBathrooms" id="numBathrooms">
			<?php foreach(range(0, 15) as $n) { ?>
				<option value="<?php echo $n; ?>"><?php echo $n; ?></option>
			<?php } ?>
			</select>

			<label for="status"><?php _e('Status'); ?></label>

			<select name="status" id="status">
				<option value="NEW CONSTRUCTION"><?php _e('New construction'); ?></option>
				<option value="TO RENOVATE"><?php _e('To renovate'); ?></option>
				<option value="GOOD CONDITION"><?php _e('Good condition'); ?></option>
			</select>



			<label for="squareMeters"><?php _e('Square meters'); ?></label>
			<input type="text" name="squareMeters" id="squareMeters" value="" size="4" maxlength="4" />


			<label for="year"><?php _e('Construction Year'); ?></label>
			<input type="text" name="year" id="year" value="" size="4" maxlength="4" />


			<label for="squareMetersTotal"><?php _e('Square meters (total)'); ?></label>
			<input type="text" name="squareMetersTotal" id="squareMetersTotal" value="" size="4" maxlength="6" />


			<label for="numFloors"><?php _e('Num. of floors'); ?></label>

			<select name="numFloors" id="numFloors">
			<?php foreach(range(0, 15) as $n) { ?>
				<option value="<?php echo $n; ?>"><?php echo $n; ?></option>
			<?php } ?>
			</select>



			<label for="numGarages"><?php _e('Num. of garages (place for a car = one garage)'); ?></label>

			<select name="numGarages" id="numGarages">
				<?php foreach(range(0, 15) as $n) { ?>
				<option value="<?php echo $n; ?>"><?php echo $n; ?></option>
				<?php } ?>
			</select>

			<label for="condition"><?php _e('Condition'); ?></label>
			<input type="text" name="condition" id="condition" value="" />


			<label for="agency"><?php _e('Agency'); ?></label>
			<input type="text" name="agency" id="agency" value="" />


			<label for="floorNumber"><?php _e('Floor Number'); ?></label>
			<input type="text" name="floorNumber" id="floorNumber" value="" />

		</p>
		<p class="bloque otros">
			<span class="subtitulo_region"><?php _e('Other characteristics'); ?></span>

			<input type="checkbox" name="heating" id="heating" value="1" /> <label for="heating"><?php _e('Heating'); ?></label><br />
			<input type="checkbox" name="airCondition" id="airCondition" value="1" /> <label for="airCondition"><?php _e('Air condition'); ?></label><br />
			<input type="checkbox" name="elevator" id="elevator" value="1" /> <label for="elevator"><?php _e('Elevator'); ?></label><br />
			<input type="checkbox" name="terrace" id="terrace" value="1" /> <label for="terrace"><?php _e('Terrace'); ?></label><br />
			<input type="checkbox" name="parking" id="parking" value="1" /> <label for="parking"><?php _e('Parking'); ?></label><br />
			<input type="checkbox" name="furnished" id="furnished" value="1" /> <label for="furnished"><?php _e('Furnished'); ?></label><br />
			<input type="checkbox" name="new" id="new" value="1" /> <label for="new"><?php _e('New'); ?></label><br />
			<input type="checkbox" name="by_owner" id="by_owner" value="1" /> <label for="by_owner"><?php _e('By owner'); ?></label><br />
		</p>
		
		<?php
			$locales = Locale::newInstance()->listAllEnabled();
			if(count($locales)==1) {
		?>
			<p class="bloque">
				<label for="transport"><?php _e('Transport'); ?></label><br />
				<input type="text" name="<?php echo @$locales[0]['pk_c_code']; ?>#transport" id="transport" style="width: 100%;" />
		
				<label for="zone"><?php _e('Zone'); ?></label><br />
				<input type="text" name="<?php echo @$locales[0]['pk_c_code']; ?>#zone" id="zone" style="width: 100%;" />
			</p>
		<?php } else { ?>
			<div class="tabber bloque">
			<?php foreach($locales as $locale) {?>
				<div class="tabbertab">
					<h2><?php echo $locale['s_name']; ?></h2>
					<p>
						<label for="transport"><?php _e('Transport'); ?></label><br />
						<input type="text" name="<?php echo @$locale['pk_c_code']; ?>#transport" id="transport" style="width: 100%;" />
					</p>
					<p>
						<label for="zone"><?php _e('Zone'); ?></label><br />
						<input type="text" name="<?php echo @$locale['pk_c_code']; ?>#zone" id="zone" style="width: 100%;" />
					</p>
				</div>
			<?php }; ?>
			</div>
		<?php }; ?>
			<script type="text/javascript">
				tabberAutomatic();
			</script>
	</div> <!-- fin div caja_contenedor -->
</div> <!-- fin div caja_contenido -->
