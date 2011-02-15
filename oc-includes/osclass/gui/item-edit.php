<script src="<?php echo WEB_PATH;?>/oc-includes/js/tabber-minimized.js"></script>
<link type="text/css" href="<?php echo WEB_PATH;?>/oc-includes/css/tabs.css" media="screen" rel="stylesheet" />
<?php ItemForm::location_javascript(); ?>

<div class="content add_item">
    <h1><strong><?php _e('Update your item'); ?></strong></h1>

	<form action="user.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<input type="hidden" name="action" value="item_edit_post" />
		<input type="hidden" name="id" value="<?php echo $item['pk_i_id'];?>" />
		<input type="hidden" name="secret" value="<?php echo $item['s_secret'];?>" />
		<input type="hidden" name="fk_location_id" value="<?php echo $item['fk_i_user_location_id'];?>" />

		<div class="left_column">
            <div class="box general_info">
                <h2><?php _e('General Information'); ?></h2>
                <div class="row">
                    <label><?php _e('Category'); ?></label>
                    <?php ItemForm::category_select($categories, $item); ?>
                </div>
                <div class="row">
                    <?php ItemForm::multilanguage_title_description($locales, $item); ?>
                </div>
                <div class="row price">
                    <label><?php _e('Price'); ?></label>
                    <?php ItemForm::price_input_text($item); ?>
                    <?php ItemForm::currency_select($currencies,$item); ?>
                </div>
            </div>

            <div class="box photos">
                <?php ItemForm::photos_javascript($item); ?>
                <h2><?php _e('Photos'); ?></h2>
                <?php ItemForm::photos($resources); ?>
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
                <h2><?php _e('Location'); ?></h2>
                <div class="row">
                    <label><?php _e('Country'); ?></label>
                    <?php ItemForm::country_select($countries, $item) ; ?>
                </div>
                <div class="row">
                    <label><?php _e('Region'); ?></label>
                    <?php ItemForm::region_select($regions, $item) ; ?>
                </div>
                <div class="row">
                    <label><?php _e('City'); ?></label>
                    <?php ItemForm::city_select($cities, $item) ; ?>
                </div>
                <div class="row">
                    <label><?php _e('City area'); ?></label>
                    <?php ItemForm::city_area_text($item) ; ?>
                </div>
                <div class="row">
                    <label><?php _e('Address'); ?></label>
                    <?php ItemForm::address_text($item) ; ?>
                </div>
            </div>

			<?php
                osc_run_hook('item_edit', $item) ;
			?>
		</div>
		
        <button class="itemFormButton" type="submit"><?php _e('Update'); ?></button>
        <a href="javascript:history.back(-1)" class="go_back"><?php _e('Cancel'); ?></a>
	</fieldset>
	</form>
</div>