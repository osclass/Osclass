<script src="<?php echo WEB_PATH;?>/oc-includes/js/tabber-minimized.js"></script>
<link type="text/css" href="<?php echo WEB_PATH;?>/oc-includes/css/tabs.css" media="screen" rel="stylesheet" />
<?php ItemForm::location_javascript(); ?>
<?php if(!isset($_GET['catId'])) { $_GET['catId'] = -1; }; ?>
<div id="home_header"><div><?php _e('Post your item'); ?></div></div>
    <div align="center">
	<div id="add_item_form">
		<form action="item.php" method="post" enctype="multipart/form-data" onSubmit="return checkForm()">
		<input type="hidden" name="action" value="post_item" />
                
		<!-- left -->
		<div class="left column">
                    <h2>
                        <?php _e('General Information'); ?>
                    </h2>

                    <?php ItemForm::category_select($categories, $item['fk_i_category_id'] = $_GET['catId']); ?>

                    <?php ItemForm::multilanguage_title_description($locales); ?>
                    
                    <div>
                        <h2><?php _e('Price'); ?></h2>
                        <?php ItemForm::price_input_text(); ?>
                        <?php ItemForm::currency_select($currencies); ?>
                    </div>

                    <div>
                        <?php ItemForm::photos_javascript(); ?>
                        <h2><?php _e('Photos'); ?></h2>
                        <div id="photos">
                            <input type="file" name="photos[]" /> (<?php _e('optional'); ?>)
                        </div>
                        <a href="#" onclick="addNewPhoto(); return false;"><?php _e('Add new photo'); ?></a>
                    </div>
		</div>
		
		<!-- right -->
		<div class="right column">
                    <div class="location-post">
                        <!-- location info -->
                        <h2><?php _e('Location'); ?></h2>
                        <dl>
                            <dt><?php _e('Country'); ?></dt>
                            <dd><?php ItemForm::country_select($countries) ; ?></dd>
                            <dt><?php _e('Region'); ?></dt>
                            <dd><?php ItemForm::region_select($regions) ; ?></dd>
                            <dt><?php _e('City'); ?></dt>
                            <dd><?php ItemForm::city_select($cities) ; ?></dd>
                            <dt><?php _e('City area'); ?></dt>
                            <dd><?php ItemForm::city_area_text() ; ?></dd>
                            <dt><?php _e('Address'); ?></dt>
                            <dd><?php ItemForm::address_text() ; ?></dd>
                        </dl>
                    </div>
                    <!-- seller info -->
                    <?php if(!osc_isUserLoggedIn()) { ?>
                    <div class="contact-post">
                        <h2><?php _e('Seller information'); ?></h2>
                        <dl>
                            <dt><?php _e('Name'); ?></dt>
                            <dd><?php ItemForm::contact_name_text() ; ?></dd>
                            <dt><?php _e('E-mail'); ?></dt>
                            <dd>
                                <?php ItemForm::contact_email_text() ; ?>
                                <br/>
                                <?php ItemForm::show_email_checkbox() ; ?>
                            </dd>
                        </dl>
                    </div>
                    <?php }; ?>
                    <?php ItemForm::plugin_post_item($categories); ?>
		</div>
		<div class="clear"></div>
		<div align="center" style="margin-top: 30px; padding: 20px; background-color: #eee;">
                    <button style="background-color: orange; color: white;" type="submit"><?php _e('Publish'); ?></button>
		</div>
                </form>
	</div>
</div>
