<?php
    /**
     * OSClass – software for creating and publishing online classified advertising platforms
     *
     * Copyright (C) 2010 OSCLASS
     *
     * This program is free software: you can redistribute it and/or modify it under the terms
     * of the GNU Affero General Public License as published by the Free Software Foundation,
     * either version 3 of the License, or (at your option) any later version.
     *
     * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
     * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
     * See the GNU Affero General Public License for more details.
     *
     * You should have received a copy of the GNU Affero General Public
     * License along with this program. If not, see <http://www.gnu.org/licenses/>.
     */

    $new_item = __get('new_item') ;

    if( $new_item ) {
        $title  = __('New item') ;
        $button = __('Add new item') ;
    } else {
        $title  = __('Edit item') ;
        $button = __('Update item') ;
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
        <script type="text/javascript">
            document.write('<style type="text/css"> .tabber{ display:none; } </style>') ;
            $(document).ready(function(){
                $("#userId").bind('change', function() {
                    if($(this).val() == '') {
                        $("#contact_info").show() ;
                    } else {
                        $("#contact_info").hide() ;
                    }
                }) ;

                if( $("#userId").val() == '') {
                    $("#contact_info").show() ;
                } else {
                    $("#contact_info").hide() ;
                }
            }) ;
        </script>
        <?php ItemForm::location_javascript_new('admin') ; ?>
        <?php if( osc_images_enabled_at_items() ) ItemForm::photos_javascript() ; ?>
        <link href="<?php echo osc_current_admin_theme_styles_url('tabs.css') ; ?>" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('tabber-minimized.js') ; ?>"></script>
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.validate.min.js') ; ?>"></script>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <!-- container -->
        <div id="content">
            <?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>
            <!-- right container -->
            <div class="right">
                <div class="header_title">
                    <h1 class="items"><?php echo $title ; ?></h1>
                </div>
                <?php osc_show_flash_message('admin') ; ?>
                <!-- item form -->
                <div class="items">
                    <ul id="error_list" style="display: none;"></ul>
                    <form action="<?php echo osc_admin_base_url(true) ; ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="page" value="items" />
                        <?php if( $new_item ) { ?>
                            <input type="hidden" name="action" value="post_item" />
                        <?php } else { ?>
                            <input type="hidden" name="action" value="item_edit_post" />
                            <input type="hidden" name="id" value="<?php echo osc_item_id() ; ?>" />
                            <input type="hidden" name="secret" value="<?php echo osc_item_secret() ; ?>" />
                        <?php } ?>
                        <fieldset>
                            <h3><?php _e('User') ; ?></h3>
                            <div class="input-line">
                                <label><?php _e('Item posted by') ; ?></label>
                                <div class="input">
                                    <?php ItemForm::user_select(null, null, __('Non-registered user')) ; ?>
                                </div>
                            </div>
                            <div id="contact_info">
                                <div class="input-line">
                                    <label><?php _e('Name') ; ?></label>
                                    <div class="input large">
                                        <?php ItemForm::contact_name_text() ; ?>
                                    </div>
                                </div>
                                <div class="input-line">
                                    <label><?php _e('E-mail') ; ?></label>
                                    <div class="input large">
                                        <?php ItemForm::contact_email_text(); ?>
                                    </div>
                                </div>
                            </div>
                            <h3><?php _e('General information') ; ?></h3>
                            <div class="input-line">
                                <label><?php _e('Category') ; ?></label>
                                <div class="input">
                                    <?php ItemForm::category_select() ; ?>
                                </div>
                            </div>
                            <div class="input-line">
                                <label></label>
                                <div class="input xxlarge">
                                    <?php ItemForm::multilanguage_title_description( osc_get_locales() ) ; ?>
                                </div>
                            </div>
                            <?php if(osc_price_enabled_at_items()) { ?>
                                <div class="input-line">
                                    <label><?php _e('Price') ; ?></label>
                                    <div class="input">
                                        <?php ItemForm::price_input_text() ; ?>
                                        <?php ItemForm::currency_select() ; ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if( osc_images_enabled_at_items() ) { ?>
                                <div class="photos input-line">
                                    <label><?php _e('Photos') ; ?></label>
                                    <?php ItemForm::photos() ; ?>
                                    <div class="input">
                                        <div id="photos">
                                            <?php if( osc_max_images_per_item() == 0 || ( osc_max_images_per_item() != 0 && osc_count_item_resources() < osc_max_images_per_item() ) ) { ?>
                                            <div>
                                                <input type="file" name="photos[]" /> (<?php _e('optional') ; ?>)
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <p><a style="font-size: small;" href="#" onclick="addNewPhoto(); return false;"><?php _e('Add new photo') ; ?></a></p>
                                    </div>
                                </div>
                            <?php } ?>
                            <h3><?php _e('Location') ; ?></h3>
                            <div class="input-line">
                                <label><?php _e('Country') ; ?></label>
                                <div class="input">
                                    <?php ItemForm::country_select() ; ?>
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('Region') ; ?></label>
                                <div class="input large">
                                    <?php ItemForm::region_text() ; ?>
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('City') ; ?></label>
                                <div class="input large">
                                    <?php ItemForm::city_text() ; ?>
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('City area') ; ?></label>
                                <div class="input large">
                                    <?php ItemForm::city_area_text() ; ?>
                                </div>
                            </div>
                            <div class="input-line">
                                <label><?php _e('Address') ; ?></label>
                                <div class="input large">
                                    <?php ItemForm::address_text() ; ?>
                                </div>
                            </div>
                            <?php if( $new_item ) {
                                    ItemForm::plugin_post_item() ;
                                } else {
                                    ItemForm::plugin_edit_item() ;
                                }
                            ?>
                            <div class="actions">
                                <input type="submit" name="submit" value="<?php echo $button ; ?>">
                            </div>
                        </fieldset>
                    </form>
                    <div class="clear"></div>
                </div>
                <!-- /item form -->
            </div>
            <!-- /right container -->
        </div>
        <!-- /container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>