<script src="<?php echo osc_base_url() ; ?>/oc-includes/js/tabber-minimized.js"></script>
<link type="text/css" href="<?php echo osc_base_url() ; ?>/oc-includes/css/tabs.css" media="screen" rel="stylesheet" />
<script type="text/javascript">
    $(document).ready(function(){
        $("#countryId").change(function(){
            var pk_c_code = $(this).val();
            var url = '<?php echo osc_base_url() . "/oc-includes/osclass/ajax/region.php?countryId="; ?>' + pk_c_code;
            var result = '';
            
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                success: function(data){
                    var length = data.length
                    if(length > 0) {
                    	result += '<option value=""><?php _e("Select a region..."); ?></option>';
                        for(key in data) {
                            result += '<option value="' + data[key].pk_i_id + '">' + data[key].s_name + '</option>';
                        }
                    } else {
                        result += '<option value="<?php _e('none'); ?>"><?php _e('No results') ?></option>';
                    }
                    $("#regionId").html(result);
    
                    var region_id = $("#regionId option:first").val();
                    var url = '<?php echo osc_base_url() . "/oc-includes/osclass/ajax/city.php?regionId="; ?>' + region_id;
                    var result = '';
    
                    $.ajax({
                        type: "POST",
                        url: url,
                        dataType: 'json',
                        success: function(data){
                            var length = data.length
                            if(length > 0) {
                            	result += '<option value=""><?php _e("Select a city..."); ?></option>';
                                for(key in data) {
                                    result += '<option value="' + data[key].pk_i_id + '">' + data[key].s_name + '</option>';
                                }
                            } else {
                                result += '<option value="<?php _e('none'); ?>"><?php _e('No results') ?></option>';
                            }
                            $("#cityId").html(result);
                        }
                    });
                }
             });
        });


        $("#regionId").change(function(){
            var pk_c_code = $(this).val();
            var url = '<?php echo osc_base_url() . "/oc-includes/osclass/ajax/city.php?regionId="; ?>' + pk_c_code;
            var result = '';
    
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                success: function(data){
                    var length = data.length
                    if(length > 0) {
                    	result += '<option value=""><?php _e("Select a city..."); ?></option>';
                        for(key in data) {
                            result += '<option value="' + data[key].pk_i_id + '">' + data[key].s_name + '</option>';
                        }
                    } else {
                        result += '<option value="<?php _e('none'); ?>"><?php _e('No results') ?></option>';
                    }
                    $("#cityId").html(result);
                }
             });
        });
    });

</script>
<script type="text/javascript">
function regionChange(data) {
    if(data.value!="") {
        document.getElementById('cityId').disabled = false;
    } else {
        document.getElementById('cityId').disabled = true;
    }
}

function checkForm() {

    if(document.getElementById('regionId').value=="") { alert("<?php echo  __('You have to select a region.');?>"); return false;}
    if(document.getElementById('cityId').value=="") { alert("<?php echo  __('You have to select a city.');?>"); return false;}

    return true;
}
</script>
<div id="home_header"><div><?php _e('Post your item'); ?></div></div>

<div align="center">
	<div id="add_item_form" style="width: 900px; margin-bottom: 20px; padding: 10px; border: 1px solid #ddd;" align="left">
		<form action="item.php" method="post" enctype="multipart/form-data" onSubmit="return checkForm()">
		<input type="hidden" name="action" value="post_item" />
		<input type="hidden" name="catId" value="<?php echo  $_GET['catId']; ?>" />

		<!-- left -->
		<div style="float: left; width: 449px; padding: 10px; border-right: 0px solid #ddd;">
			<div id="home_header"><div style="font-size: 20px;"><?php _e('General Information'); ?></div></div>
            <div style="margin-top: 30px; padding: 20px; background-color: #eee;">
            <label for="catId"><?php _e('Category'); ?></label><br />
            <select name="catId" id="catId" style="width: 100%;">
            <?php foreach($categories as $c): ?>
                <option value="<?php echo $c['pk_i_id']; ?>" <?php if(isset($_GET['catId']) && $_GET['catId']==$c['pk_i_id']) { echo "selected"; } ?>><?php echo $c['s_name']; ?></option>
                <?php foreach($c['categories'] as $sc): ?>
                    <option style="padding-left: 20px;" value="<?php echo $sc['pk_i_id']; ?>" <?php if(isset($_GET['catId']) && $_GET['catId']==$sc['pk_i_id']) { echo "selected"; } ?>><?php echo $sc['s_name']; ?></option>
                <?php endforeach; ?>
            <?php endforeach; ?>
            </select>
            </div>

    			<?php $locales = Locale::newInstance()->listAllEnabled();
                if(count($locales)==1) {
                $locale = $locales[0]; ?>
                        <div style="margin-top: 30px; padding: 20px; background-color: #eee;">
            				<div style=""><label for="title"><?php _e('Title'); ?></label></div>
            				<input type="text" name="<?php echo @$locale['pk_c_code']; ?>#title" id="title" style="width: 100%; border: 1px solid #ccc;" />				
            			</div>	
            			
            			<div style="margin-top: 30px; padding: 20px; background-color: #eee;">
            				<label for="description"><?php _e('Description'); ?></label><br />
            				<textarea name="<?php echo @$locale['pk_c_code']; ?>#description" id="description" style="width: 100%;"></textarea>
            			</div>

               <?php } else {?>

			<div class="tabber">
    			<?php foreach($locales as $locale) { ?>
        			<div class="tabbertab">
            			<h2><?php echo $locale['s_name']; ?></h2>
            			<div style="margin-top: 30px; padding: 20px; background-color: #eee;">
            				<div style=""><label for="title"><?php _e('Title'); ?></label></div>
            				<input type="text" name="<?php echo @$locale['pk_c_code']; ?>#title" id="title" style="width: 100%; border: 1px solid #ccc;" />				
            			</div>	
            			
            			<div style="margin-top: 30px; padding: 20px; background-color: #eee;">
            				<label for="description"><?php _e('Description'); ?></label><br />
            				<textarea name="<?php echo @$locale['pk_c_code']; ?>#description" id="description" style="width: 100%;"></textarea>
            			</div>
        			</div>
    			<?php } ?>
			</div>
			<?php } ?>
			<div style="margin-top: 10px; padding: 20px; background-color: #eee; font-weight: bold;" align="center">
                <label for="price"><?php _e('Price'); ?></label>
                <input type="text" name="price" id="price" value="0" size="5" />
                <?php if(count($currencies) == 1): ?>
                    <?php echo $currencies[0]['s_description']; ?>
                <?php else: ?>
                    <select name="currency">
                    <?php foreach($currencies as $c): ?>
                    <option value="<?php echo $c['pk_c_code']; ?>"><?php echo $c['s_description']; ?></option>
                    <?php endforeach; ?>
                    </select>
                <?php endif; ?>
			</div>
			
			<div style="margin-top: 10px; padding: 20px; background-color: #eee;">
				<script type="text/javascript">
				var photoIndex = 0;
				function gebi(id) { return document.getElementById(id); }
				function ce(name) { return document.createElement(name); }
				function re(id) {
					var e = gebi(id);
					e.parentNode.removeChild(e);
				}
				function addNewPhoto() {
					var id = 'p-' + photoIndex++;

					var i = ce('input');
					i.setAttribute('type', 'file');
					i.setAttribute('name', 'photos[]');

					var a = ce('a');
					a.style.fontSize = 'x-small';
					a.setAttribute('href', '#');
					a.setAttribute('divid', id);
					a.onclick = function() { re(this.getAttribute('divid')); return false; }
					a.appendChild(document.createTextNode('<?php _e('Remove'); ?>'));

					var d = ce('div');
					d.setAttribute('id', id);
					d.setAttribute('style','padding: 4px;')

					d.appendChild(i);
					d.appendChild(a);

					gebi('photos').appendChild(d);
				}
				</script>

				<?php _e('Photos'); ?><br />
				<div id="photos">
					<div style="padding: 4px;">
						<input type="file" name="photos[]" /> (<?php _e('optional'); ?>)
					</div>
				</div>
				<a style="font-size: small;" href="#" onclick="addNewPhoto(); return false;"><?php _e('Add new photo'); ?></a>
			</div>
		</div>
		
		<!-- right -->
		<div style="float:left; width: 400px; padding: 10px; border-left: 1px solid #ddd;">
                    <!-- location info -->
                    <div id="home_header" style="margin-bottom: 30px;"><div style="font-size: 20px;"><?php _e('Item Location'); ?></div></div>

                    <?php if (count($countries) > 1) { ?>
                        <div style="margin-bottom: 10px; padding: 20px; background-color: #eee;">
                            <label for="countryId"><?php _e('Country'); ?></label><br />
                            <select name="countryId" id="countryId" style="width: 100%;">
                                <option value=""><?php _e("Select a country..."); ?></option>
                                <?php foreach($countries as $c): ?>
                                    <option value="<?php echo $c['pk_c_code']; ?>"><?php echo $c['s_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php } elseif (count($countries) == 1) { ?>
                        <input type="hidden" name="countryId" id="countryId" value="<?php echo $countries[0]['pk_c_code']?>"/>
                    <?php } ?>

                    <?php $regions = Region::newInstance()->getByCountry($countries[0]['pk_c_code']); ?>
                    <?php if(count($regions) > 1) { ?>
                        <div style="margin-bottom: 10px; padding: 20px; background-color: #eee;">
                            <?php $regions = Region::newInstance()->getByCountry($countries[0]['pk_c_code']); ?>
                            <?php if(count($regions) > 0) { ?>
                                <select name="regionId" id="regionId" style="width: 100%;" onchange="regionChange(this)" >
                                    <option value=""><?php _e("Select a region..."); ?></option>
                                    <?php foreach($regions as $r): ?>
                                        <option value="<?php echo $r['pk_i_id']; ?>"><?php echo $r['s_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php } else { ?>
                                <label for="region"><?php _e('Region'); ?></label><br />
                                <input type="text" name="region" id="region" style="width: 100%; border: 1px solid #ccc;" />
                            <?php } ?>
                        </div>
                    <?php } elseif (count($regions) == 1) { ?>
                        <input type="hidden" name="regionId" id="regionId" value="<?php echo $regions[0]['pk_i_id']; ?>"/>
                    <?php } ?>
                    
                    <div style="margin-bottom: 10px; padding: 20px; background-color: #eee;">
                        <?php $cities = City::newInstance()->listWhere("fk_i_region_id = %d" ,$regions[0]['pk_i_id']); ?>
                        <?php if(count($cities) > 0) { ?>
                            <select name="cityId" id="cityId" style="width: 100%;" disabled>
                                <option value=""><?php _e("Select a city..."); ?></option>
                                <?php foreach($cities as $c) { ?>
                                    <option value="<?php echo $c['pk_i_id']; ?>"><?php echo $c['s_name']; ?></option>
                                <?php } ?>
                            </select>
                        <?php } else { ?>
                            <label for="city"><?php _e('City'); ?></label><br />
                            <input type="text" name="city" id="city" style="width: 100%; border: 1px solid #ccc;" />
                        <?php } ?>
                    </div>

                    <div style="margin-bottom: 10px; padding: 20px; background-color: #eee;">
                        <label for="city"><?php _e('City Area'); ?></label><br />
                        <input type="text" name="cityArea" id="cityArea" style="width: 100%; border: 1px solid #ccc;" />
                    </div>
                    
                    <div style="margin-bottom: 10px; padding: 20px; background-color: #eee;">
                        <label for="address"><?php _e('Address'); ?></label><br />
                        <input type="text" name="address" id="address" style="width: 100%; border: 1px solid #ccc;" />
                    </div>

                    <!-- seller info -->
                    <?php if(!osc_isUserLoggedIn()) { ?>
                        <div id="home_header"><div style="padding-top: 10px; font-size: 20px;"><?php _e('Seller information'); ?></div></div>
    
                        <div style="margin-top: 30px; padding: 20px; background-color: #eee;">
                                <label for="contactName"><?php _e('Name'); ?></label><br />
                                <input type="text" name="contactName" id="contactName" style="width: 100%; border: 1px solid #ccc;" />
                        </div>
    
                        <div style="margin-top: 10px; padding: 20px; background-color: #eee;">
                                <label for="contactEmail"><?php _e('E-mail'); ?></label><br />
                                <input type="text" name="contactEmail" id="contactEmail" style="width: 100%; border: 1px solid #ccc;" /><br />
                                <input type="checkbox" name="showEmail" id="showEmail" value="1" checked="checked" /> <label style="font-size: small;" for="showEmail"><?php _e('Show email publically within the item page'); ?></label>
                        </div>
                    <?php }; ?>

                    <?php
                        if(isset($_GET['catId'])) {
                            osc_run_hook('item_form', $_GET['catId']) ;
                        } else {
                            osc_run_hook('item_form') ;
                        }
                    ?>
		</div>
		<div class="clear"></div>
		<div align="center" style="margin-top: 30px; padding: 20px; background-color: #eee;">
                    <button style="background-color: orange; color: white;" type="submit"><?php _e('Publish'); ?></button>
		</div>
                </form>
	</div>
</div>
