<script src="<?php echo WEB_PATH;?>/oc-includes/js/tabber-minimized.js"></script>
<link type="text/css" href="<?php echo WEB_PATH;?>/oc-includes/css/tabs.css" media="screen" rel="stylesheet" />
<?php ItemForm::location_javascript(); ?>
<div id="home_header" class="sectionHeader"><?php _e('Update your item'); ?></div>
<div class="itemFormHolder">
	<div id="add_item_form" class="itemForm">
		<form action="<?php echo osc_createURL('user');?>" method="post" enctype="multipart/form-data">
		<input type="hidden" name="action" value="item_edit_post" />
		<input type="hidden" name="id" value="<?php echo $item['pk_i_id'];?>" />
		<input type="hidden" name="secret" value="<?php echo $item['s_secret'];?>" />
		<input type="hidden" name="fk_location_id" value="<?php echo $item['fk_i_user_location_id'];?>" />

		<!-- left -->
		<div class="itemFormLeft">

                    <div class="itemFormHeader"><?php _e('General Information'); ?></div>

                    <?php ItemForm::category_select($categories, $item); ?>

                    <?php ItemForm::multilanguage_title_description($locales, $item); ?>

                    <div class="itemFormContent">
                        <h2><?php _e('Price'); ?></h2>
                        <?php ItemForm::price_input_text($item); ?>
                        <?php ItemForm::currency_select($currencies,$item); ?>
                    </div>

                    <div class="itemFormContent">
                        <?php ItemForm::photos_javascript($item); ?>
                        <h2><?php _e('Photos'); ?></h2>
                        <?php ItemForm::photos($resources); ?>
                        <div id="photos">
                            <input type="file" name="photos[]" /> (<?php _e('optional'); ?>)
                        </div>
                        <a href="#" onclick="addNewPhoto(); return false;"><?php _e('Add new photo'); ?></a>
                    </div>
			
		</div>
		
		<!-- right -->
		<div class="itemFormRight">
			                    <div class="itemFormContent">
                        <!-- location info -->
                        <div class="itemFormHeader"><?php _e('Location'); ?></div>
                        <dl>
                            <dt><?php _e('Country'); ?></dt>
                            <dd><?php ItemForm::country_select($countries, $item) ; ?></dd>
                            <dt><?php _e('Region'); ?></dt>
                            <dd><?php ItemForm::region_select($regions, $item) ; ?></dd>
                            <dt><?php _e('City'); ?></dt>
                            <dd><?php ItemForm::city_select($cities, $item) ; ?></dd>
                            <dt><?php _e('City area'); ?></dt>
                            <dd><?php ItemForm::city_area_text($item) ; ?></dd>
                            <dt><?php _e('Address'); ?></dt>
                            <dd><?php ItemForm::address_text($item) ; ?></dd>
                        </dl>
                    </div>

			<?php
                            osc_runHook('item_edit', $item);
			?>
		</div>
		<div class="clear"></div>
		<div class="itemFormButtons">
                        <button class="itemFormButton" type="button" onclick="history.back()" ><?php _e('Cancel'); ?></button>
                        <button class="itemFormButton" type="submit"><?php _e('Update'); ?></button>
		</div>
		</form>
	</div>
</div>
