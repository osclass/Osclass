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

    $new_item = __get('new_item');
    function customText($return = 'title'){
        $new_item = __get('new_item') ;
        $text = array();
        if( $new_item ) {
            $text['title']    = __('Listing');
            $text['subtitle'] = __('Add listing');
            $text['button']   = __('Add listing');
        } else {
            $text['title']    = __('Listing');
            $text['subtitle'] = __('Edit listing');
            $text['button']   = __('Update listing');
        }
        return $text[$return];
    }

    function customPageHeader() { ?>
        <h1><?php echo customText('title') ; ?></h1>
<?php
    }
    osc_add_hook('admin_page_header','customPageHeader');

    function customPageTitle($string) {
        return sprintf('%s &raquo; %s', customText('subtitle'), $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');

    //customize Head
    function customHead() { ?>
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.validate.min.js') ; ?>"></script>
        <script type="text/javascript">
            document.write('<style type="text/css"> .tabber{ display:none; } </style>') ;
            $(document).ready(function(){
                // -----
//                $("#userId").bind('change', function() {
//                    if($(this).val() == '') {
//                        $("#contact_info").show() ;
//                    } else {
//                        $("#contact_info").hide() ;
//                    }
//                }) ;
//
//                if( $("#userId").val() == '') {
//                    $("#contact_info").show() ;
//                } else {
//                    $("#contact_info").hide() ;
//                }
                // -----
                $('input[name="user"]').attr( "autocomplete", "off" );
                $('#user,#fUser').autocomplete({
                    source: "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=userajax",
                    minLength: 0,
                    select: function( event, ui ) {
                        if(ui.item.id=='') {
                            $("#contact_info").show() ;
                            return false;
                        }
                        $('#userId').val(ui.item.id);
                        $('#fUserId').val(ui.item.id);
                        $("#contact_info").hide() ;
                    }
//                    ,search: function() {
//                        $('#userId').val('');
//                        $('#fUserId').val('');
//                    }
                });
                // ----

                <?php if(osc_locale_thousands_sep()!='' || osc_locale_dec_point() != '') { ?>
                $("#price").blur(function(event) {
                    var price = $("#price").attr("value");
                    <?php if(osc_locale_thousands_sep()!='') { ?>
                    while(price.indexOf('<?php echo osc_esc_js(osc_locale_thousands_sep());  ?>')!=-1) {
                        price = price.replace('<?php echo osc_esc_js(osc_locale_thousands_sep());  ?>', '');
                    }
                    <?php }; ?>
                    <?php if(osc_locale_dec_point()!='') { ?>
                    var tmp = price.split('<?php echo osc_esc_js(osc_locale_dec_point())?>');
                    if(tmp.length>2) {
                        price = tmp[0]+'<?php echo osc_esc_js(osc_locale_dec_point())?>'+tmp[1];
                    }
                    <?php }; ?>
                    $("#price").attr("value", price);
                });
                <?php } ?>
            });
        </script>
        <?php ItemForm::location_javascript_new('admin') ; ?>
        <?php if( osc_images_enabled_at_items() ) ItemForm::photos_javascript() ; ?>
        <?php
    }
    osc_add_hook('admin_header','customHead');

    $new_item   = __get('new_item') ;
    $actions    = __get('actions') ;

    osc_add_filter('render-wrapper','render_offset');
    function render_offset(){
        return 'row-offset';
    }
osc_current_admin_theme_path( 'parts/header.php' ) ; ?>
<div id="pretty-form">
<div class="grid-row no-bottom-margin">
    <div class="row-wrapper">
        <h2 class="render-title"><?php echo customText('subtitle') ; ?></h2>
    </div>
</div>
<div class="grid-row no-bottom-margin float-right">
    <div class="row-wrapper">
        <?php if( !$new_item ) { ?>
        <ul id="item-action-list">
            <?php foreach($actions as $aux) { ?>
            <li>
                <?php echo $aux; ?>
            </li>
            <?php } ?>
        </ul>
        <div class="clear"></div>
        <?php } ?>
    </div>
</div>
<div class="grid-row grid-100">
    <div class="row-wrapper">
        <div id="item-form">
                <ul id="error_list"></ul>
                <?php printLocaleTabs(); ?>
                <form action="<?php echo osc_admin_base_url(true) ; ?>" method="post" enctype="multipart/form-data" name="item">
                    <input type="hidden" name="page" value="items" />
                    <?php if( $new_item ) { ?>
                        <input type="hidden" name="action" value="post_item" />
                    <?php } else { ?>
                        <input type="hidden" name="action" value="item_edit_post" />
                        <input type="hidden" name="id" value="<?php echo osc_item_id() ; ?>" />
                        <input type="hidden" name="secret" value="<?php echo osc_item_secret() ; ?>" />
                    <?php } ?>
                    <?php /********************************* */ ?>

                    <?php /********************************* */ ?>
                    <div id="left-side">
                        <?php printLocaleTitle(osc_get_locales()); ?>
                        <div>
                            <label><?php _e('Category') ; ?></label>
                            <?php ItemForm::category_select() ; ?>
                        </div>
                        <div class="input-description-wide">
                            <?php printLocaleDescription(osc_get_locales()); ?>
                        </div>
                        <?php if(osc_price_enabled_at_items()) { ?>
                            <div>
                                <label><?php _e('Price') ; ?></label>
                                <?php ItemForm::price_input_text() ; ?>
                                <span class="input-currency"><?php ItemForm::currency_select() ; ?></span>
                            </div>
                        <?php } ?>

                        <?php if( osc_images_enabled_at_items() ) { ?>
                            <label><?php _e('Photos') ; ?></label>
                            <?php ItemForm::photos() ; ?>
                            <div id="photos">
                                <?php if( osc_max_images_per_item() == 0 || ( osc_max_images_per_item() != 0 && osc_count_item_resources() < osc_max_images_per_item() ) ) { ?>
                                <div>
                                    <input type="file" name="photos[]" /> (<?php _e('optional') ; ?>)
                                </div>
                                <?php } ?>
                            </div>
                            <p><a href="#" onclick="addNewPhoto(); return false;"><?php _e('Add new photo') ; ?></a></p>
                        <?php } ?>
                        <?php if( $new_item ) {
                                ItemForm::plugin_post_item() ;
                            } else {
                                ItemForm::plugin_edit_item() ;
                            }
                        ?>
                    </div>
                    <div id="right-side">
                        <div class="well ui-rounded-corners">
                            <h3 class="label">User</h3>
                            <?php //ItemForm::user_select(null, null, __('Non-registered user')) ; ?>
<!--                         input autocomplete   -->
<!--                            <input id="fUser" name="user" type="text" class="fUser input-text input-actions" value="<?php echo osc_esc_html(Params::getParam('user')); ?>" />
                            <input id="fUserId" name="userId" type="hidden" value="<?php echo osc_esc_html(Params::getParam('userId')); ?>" />-->
                            <div id="contact_info">
                                <div class="input-has-placeholder input-separate-top">
                                    <label><?php _e('Name') ; ?></label>
                                    <?php ItemForm::contact_name_text() ; ?>
                                </div>
                                <div class="input-has-placeholder input-separate-top">
                                    <label><?php _e('E-mail') ; ?></label>
                                    <?php ItemForm::contact_email_text() ; ?>
                                </div>
                            </div>
                        </div>

                        <div class="well ui-rounded-corners input-separate-top">
                            <h3 class="label">Location</h3>
                            <?php ItemForm::country_select() ; ?>
                            <div class="input-has-placeholder input-separate-top">
                                <label><?php _e('Region') ; ?></label>
                                <?php ItemForm::region_text() ; ?>
                            </div>
                            <div class="input-has-placeholder input-separate-top">
                                <label><?php _e('City') ; ?></label>
                                <?php ItemForm::city_text() ; ?>
                            </div>
                            <div class="input-has-placeholder input-separate-top">
                                <label><?php _e('City area') ; ?></label>
                                <?php ItemForm::city_area_text() ; ?>
                            </div>
                            <div class="input-has-placeholder input-separate-top">
                                <label><?php _e('Address') ; ?></label>
                                <?php ItemForm::address_text() ; ?>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="form-actions">
                        <?php if( !$new_item ) { ?>
                        <a href="javascript:history.go(-1)" class="btn"><?php _e('Cancel'); ?></a>
                        <?php } ?>
                        <input type="submit" value="<?php echo osc_esc_html(customText('button')); ?>" class="btn btn-submit" />
                    </div>
                    </form>
        </div>
    </div>
</div>
</div>