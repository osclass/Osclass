<h3><?php _e('Realestate attributes') ; ?></h3>
<table class="tabla_atributos_realstate">
    <tr>
	<td>
            <label><?php _e('Type'); ?></label>
        </td>
	<td>
            <?php echo $detail['e_type']; ?>
        </td>
    </tr>
    <tr>
<?php
    $locales = Locale::newInstance()->listAllEnabled();
    if(count($locales)==1) {
?>
        <td>
            <label><?php _e('Property type'); ?></label>
	</td>
        <td>
            <?php echo  $detail['locale'][$locales[0]['pk_c_code']]['s_name']; ?>
        </td>
    <?php } else { ?>
        <div class="tabber">
            <?php foreach($locales as $locale) {?>
            <div class="tabbertab">
                <h2><?php echo $locale['s_name']; ?></h2>
                <p>
                    <label><?php _e('Property type'); ?></label><br />
                    <?php echo @$detail['locale'][$locale['pk_c_code']]['s_name']; ?>
                </p>
            </div>
            <?php }; ?>
        </div>
    <?php }; ?>
    </tr>
    <tr>
        <td><label><?php _e('Num. Rooms'); ?></label></td>
        <td><?php echo $detail['i_num_rooms']; ?></td>
    </tr>
    <tr>
        <td><label><?php _e('Num. Bathrooms'); ?></label></td>
        <td><?php echo $detail['i_num_bathrooms']; ?></td>
    </tr>
    <tr>
        <td><label><?php _e('Status'); ?></label></td>
        <td><?php echo $detail['e_status']; ?></td>
    </tr>
    <tr>
        <td><label><?php _e('Square Meters'); ?></label></td>
        <td><?php echo $detail['s_square_meters']; ?></td>
    </tr>
    <tr>
        <td><label><?php _e('Square Meters (total)'); ?></label></td>
        <td><?php echo $detail['i_plot_area']; ?></td>
    </tr>
    <tr>
        <td><label><?php _e('Num. Floors'); ?></label></td>
        <td><?php echo $detail['i_num_floors']; ?></td>
    </tr>
    <tr>
        <td><label><?php _e('Construction Year'); ?></label></td>
        <td><?php echo $detail['i_year']; ?></td>
    </tr>
    <tr>
        <td><label><?php _e('Heating'); ?>: </label></td><td><?php echo $detail['b_heating'] ? '<strong>'.__('Yes').'</strong>' : __('No'); ?></td>
    </tr>
    <tr>
        <td><label><?php _e('Air Condition'); ?>: </label></td><td><?php echo $detail['b_air_condition'] ? '<strong>'.__('Yes').'</strong>' : __('No'); ?></td>
    </tr>
    <tr>
        <td><label><?php _e('Elevator'); ?>: </label></td><td><?php echo $detail['b_elevator'] ? '<strong>'.__('Yes').'</strong>' : __('No'); ?></td>
    </tr>
    <tr>
        <td><label><?php _e('Terrace'); ?>: </label></td><td><?php echo $detail['b_terrace'] ? '<strong>'.__('Yes').'</strong>' : __('No'); ?></td>
    </tr>
    <tr>
        <td><label><?php _e('Parking'); ?>: </label></td><td><?php echo $detail['b_parking'] ? '<strong>'.__('Yes').'</strong>' : __('No'); ?></td>
    </tr>
    <tr>
        <td><label><?php _e('Furnished'); ?>: </label></td><td><?php echo $detail['b_furnished'] ? '<strong>'.__('Yes').'</strong>' : __('No'); ?></td>
    </tr>
    <tr>
        <td><label><?php _e('New'); ?>: </label></td><td><?php echo $detail['b_new'] ? '<strong>'.__('Yes').'</strong>' : __('No'); ?></td>
    </tr>
    <tr>
        <td><label><?php _e('By Owner'); ?>: </label></td><td><?php echo $detail['b_by_owner'] ? '<strong>'.__('Yes').'</strong>' : __('No'); ?></td>
    </tr>
    <tr>
        <td><label><?php _e('Condition'); ?></label></td>
        <td><?php echo $detail['s_condition']; ?></td>
    </tr>
    <tr>
        <td><label><?php _e('Agency'); ?></label></td>
        <td><?php echo $detail['s_agency']; ?></td>
    </tr>
    <tr>
        <td><label><?php _e('Floor Number'); ?></label></td>
        <td><?php echo $detail['i_floor_number']; ?></td>
    </tr>
</table>
<?php
    $locales = Locale::newInstance()->listAllEnabled();
    if(count($locales)==1) {
?>
        <p class="otrosAtributos">
            <label><?php _e('Transport'); ?></label><br />
            <?php echo @$detail['locale'][$locales[0]['pk_c_code']]['s_transport']; ?>
        </p>
        <p class="otrosAtributos">
            <label><?php _e('Zone'); ?></label><br />
            <?php echo  @$detail['locale'][$locales[0]['pk_c_code']]['s_zone'] ?>
        </p>
<?php } else { ?>
        <div class="tabber">
        <?php foreach($locales as $locale) {?>
            <div class="tabbertab">
                <h2><?php echo $locale['s_name']; ?></h2>
                <p>
                    <label><?php _e('Transport'); ?></label><br />
                    <?php echo @$detail['locale'][$locale['pk_c_code']]['s_transport']; ?>
                </p>
                <p>
                    <label><?php _e('Zone'); ?></label><br />
                    <?php echo @$detail['locale'][$locale['pk_c_code']]['s_zone']; ?>
                </p>
            </div>
        <?php }; ?>
        </div>
<?php }; ?>