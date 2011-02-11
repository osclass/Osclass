<div class="content add_item">
    <h1><strong><?php _e('Post your item'); ?></strong></h1>

	<form action="item.php" method="post" enctype="multipart/form-data" onSubmit="return checkForm()">
	<fieldset>
    	<input type="hidden" name="action" value="post_item" />
    	<input type="hidden" name="catId" value="<?php echo  $_GET['catId']; ?>" />

    	<div class="left_column">
    	    <div class="box general_info">
        		<h2><?php _e('General Information'); ?></h2>
                <div class="row">
                    <label for="catId"><?php _e('Category'); ?></label>
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
        			<div class="row">
        			    <label for="title"><?php _e('Title'); ?></label>
        				<input type="text" name="<?php echo @$locale['pk_c_code']; ?>#title" id="title" />
    			    </div>
    			    <div class="row">
        				<label for="description"><?php _e('Description'); ?></label>
        				<textarea name="<?php echo @$locale['pk_c_code']; ?>#description" id="description"></textarea>
        			</div>
               <?php } else {?>
        		    <div class="tabber">
            			<?php foreach($locales as $locale) { ?>
                			<div class="tabbertab">
                    			<h2><?php echo $locale['s_name']; ?></h2>
                				<div class="row">
                				    <label for="title"><?php _e('Title'); ?></label>
                    				<input type="text" name="<?php echo @$locale['pk_c_code']; ?>#title" id="title" />
                    			</div>
                    			<div class="row">
                    				<label for="description"><?php _e('Description'); ?></label>
                    				<textarea name="<?php echo @$locale['pk_c_code']; ?>#description" id="description"></textarea>
                    			</div>
                			</div>
            			<?php } ?>
            		</div>
        		<?php } ?>
    		
                <div class="row price">
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
            </div>
		
    		<div class="box photos">
    			<h2><?php _e('Photos'); ?></h2>
    			<div id="photos">
    				<div class="row">
    					<input type="file" name="photos[]" /> (<?php _e('optional'); ?>)
    				</div>
    			</div>
    			<a href="#" onclick="addNewPhoto(); return false;"><?php _e('Add new photo'); ?></a>
    		</div>
    	</div>
	
    	<div class="right_column">
    	    <div class="box location">
                <h2><?php _e('Item Location'); ?></h2>
                <?php if (count($countries) > 1) { ?>
                    <div class="row">                        
                        <label for="countryId"><?php _e('Country'); ?></label>
                        <select name="countryId" id="countryId">
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
                    <div class="row">
                        <?php $regions = Region::newInstance()->getByCountry($countries[0]['pk_c_code']); ?>
                        <?php if(count($regions) > 0) { ?>
                            <label for="regionId"><?php _e('Region'); ?></label>
                            <select name="regionId" id="regionId" onchange="regionChange(this)" >
                                <option value=""><?php _e("Select a region..."); ?></option>
                                <?php foreach($regions as $r): ?>
                                    <option value="<?php echo $r['pk_i_id']; ?>"><?php echo $r['s_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php } else { ?>
                            <label for="region"><?php _e('Region'); ?></label>
                            <input type="text" name="region" id="region" />
                        <?php } ?>
                     </div>
                <?php } elseif (count($regions) == 1) { ?>
                    <input type="hidden" name="regionId" id="regionId" value="<?php echo $regions[0]['pk_i_id']; ?>"/>
                <?php } ?>
        
                <?php $cities = City::newInstance()->listWhere("fk_i_region_id = %d" ,$regions[0]['pk_i_id']); ?>
                <div class="row">
                    <?php if(count($cities) > 0) { ?>
                        <label for="cityId"><?php _e('City'); ?></label>
                        <select name="cityId" id="cityId" disabled="disabled">
                            <option value=""><?php _e("Select a city..."); ?></option>
                            <?php foreach($cities as $c) { ?>
                                <option value="<?php echo $c['pk_i_id']; ?>"><?php echo $c['s_name']; ?></option>
                            <?php } ?>
                        </select>
                    <?php } else { ?>
                        <label for="city"><?php _e('City'); ?></label>
                        <input type="text" name="city" id="city" />
                    <?php } ?>
                </div>

                <div class="row">
                    <label for="city"><?php _e('City Area'); ?></label>
                    <input type="text" name="cityArea" id="cityArea" />
                </div>
                
                <div class="row">
                    <label for="address"><?php _e('Address'); ?></label>
                    <input type="text" name="address" id="address" />
                </div>
            </div>

            <?php if(!osc_isUserLoggedIn()) { ?>
                <div class="box seller_info">
                    <h2><?php _e('Seller information'); ?></h2>
                    <div class="row">
                        <label for="contactName"><?php _e('Name'); ?></label>
                        <input type="text" name="contactName" id="contactName" />
                    </div>
                    <div class="row">
                        <label for="contactEmail"><?php _e('E-mail'); ?></label>
                        <input type="text" name="contactEmail" id="contactEmail" />
                    </div>
                    <div class="row">
                        <input type="checkbox" name="showEmail" id="showEmail" value="1" checked="checked" /> 
                        <label for="showEmail"><?php _e('Show email publically within the item page'); ?></label>
                    </div>
                </div>
            <?php }; ?>

            <?php
                if(isset($_GET['catId'])) {
                    osc_runHook('item_form', $_GET['catId']);
                } else {
                    osc_runHook('item_form');
                }
            ?>
    	</div>

        <button type="submit"><?php _e('Publish'); ?></button>
    </fieldset>    
    </form>
</div>

<script src="<?php echo WEB_PATH;?>/oc-includes/js/tabber-minimized.js"></script>
<link type="text/css" href="<?php echo WEB_PATH;?>/oc-includes/css/tabs.css" media="screen" rel="stylesheet" />
<script type="text/javascript">
    $(document).ready(function(){
        $("#countryId").change(function(){
            var pk_c_code = $(this).val();
            var url = '<?php echo osc_base_url(true)."?page=ajax&action=regions&countryId="; ?>' + pk_c_code;
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
                    $("#uniform-regionId").removeClass("disabled");
    
                    var region_id = $("#regionId option:first").val();
                    var url = '<?php echo osc_base_url(true)."?page=ajax&action=city&regionId="; ?>' + region_id;
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
            var url = '<?php echo WEB_PATH . "/oc-includes/osclass/ajax/city.php?regionId="; ?>' + pk_c_code;
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
                    $("#uniform-cityId").removeClass("disabled");
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
	a.setAttribute('href', '#');
	a.setAttribute('divid', id);
	a.onclick = function() { re(this.getAttribute('divid')); return false; }
	a.appendChild(document.createTextNode('<?php _e('Remove'); ?>'));

	var d = ce('div');
	d.setAttribute('id', id);
	d.setAttribute('class', "row");
	d.appendChild(i);
	d.appendChild(a);

	gebi('photos').appendChild(d);
}
</script>
