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

    $aCountries = __get('aCountries');
    //customize Head
    function customHead(){
        ?>
       <script type="text/javascript">
            var base_url    = '<?php echo osc_admin_base_url(); ?>';
            var s_close     = '<?php echo osc_esc_js(_e('Close')); ?>';
            var s_view_more = '<?php echo osc_esc_js(_e('View more')); ?>';
            var addText = '<?php echo osc_esc_js(_e('Add')); ?>';
            var cancelText = '<?php echo osc_esc_js(_e('Cancel')); ?>';
            var editText = '<?php echo osc_esc_js(_e('Edit')); ?>';
            var editNewCountryText = '<?php echo osc_esc_js(__('Edit country')) ; ?>';
            var addNewCountryText = '<?php echo osc_esc_js(__('Add new country')) ; ?>';
            var editNewRegionText = '<?php echo osc_esc_js(__('Edit region')) ; ?>';
            var addNewRegionText = '<?php echo osc_esc_js(__('Add new region')) ; ?>';
            var editNewCityText = '<?php echo osc_esc_js(__('Edit city')) ; ?>';
            var addNewCityText = '<?php echo osc_esc_js(__('Add new city')) ; ?>';
        </script>
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('location.js') ; ?>"></script>
        <?php
    }
    osc_add_hook('admin_header','customHead');

    osc_add_hook('admin_page_header','customPageHeader');
    function customPageHeader(){ ?>
        <h1><?php _e('Settings') ; ?></h1>
<?php
    }

    function customPageTitle($string) {
        return __('Locations');
    }
    osc_add_filter('admin_title', 'customPageTitle');
    osc_current_admin_theme_path('parts/header.php') ; ?>
        <!-- container -->
    <h1 class="render-title"><?php _e('Locations') ; ?></h1>
                <?php osc_show_flash_message('admin') ; ?>
                <!-- settings form -->
                <div id="settings_form" class="locations locations_box well border-radius">
                    <!-- Country -->
                    <div style="float:left; width: 33%;  margin-left:10px">
                        <div style="border-bottom: 1px dashed black; padding: 4px 4px 0px; width: 90%;" >
                            <div style="float:left;">
                                <h3>
                                    <?php _e('Countries'); ?>
                                </h3>
                            </div>
                            <div style="float:right;">
                                <a id="b_new_country" href="javascript:void(0);"><?php _e('Add new'); ?></a>
                            </div>
                            <div style="clear:both;"></div>
                        </div>
                        <div id="l_countries" style="padding: 10px 0;">
                            <?php foreach( $aCountries as $country ) { ?>
                            <div style="padding: 4px; width: 90%;">
                                <div style="float:left;">
                                    <div>
                                        <a id="country_delete" class="close" onclick="javascript:return confirm('<?php echo osc_esc_js(__('This action can not be undone. Items with this location associated will be deleted. Are you sure you want to continue?')); ?>');" href="<?php echo osc_admin_base_url(true); ?>?page=settings&action=locations&type=delete_country&id=<?php echo urlencode($country['pk_c_code']) ; ?>">
                                            <img src="<?php echo osc_admin_base_url() ; ?>images/close.png" alt="<?php _e('Close'); ?>" title="<?php _e('Close'); ?>" />
                                        </a>
                                        <a id="country_edit" class="edit" href="javascript:void(0);" style="padding-right: 15px;" onclick="edit_countries($(this));" data="<?php echo osc_esc_html($country['s_name']);?>" code="<?php echo $country['pk_c_code'];?>"><?php echo $country['s_name'] ; ?></a>
                                    </div>
                                </div>
                                <div style="float:right">
                                    <a href="javascript:void(0)" onclick="show_region('<?php echo $country['pk_c_code']; ?>', '<?php echo addslashes($country['s_name']) ; ?>')"><?php _e('View more'); ?> &raquo;</a>
                                </div>
                            </div>
                            <div style="clear:both;"></div>
                            <?php } ?>
                        </div>
                        <div id="i_countries">
                        </div>
                    </div>
                    <!-- End country -->
                    <!-- Region -->
                    <div style="float:left; width: 33%;">
                        <div style="border-bottom: 1px dashed black; padding: 4px 4px 0px; width: 90%;" >
                            <div style="float:left;">
                                <h3>
                                    <?php _e('Regions'); ?>
                                </h3>
                            </div>
                            <div style="float:right;">
                                <a id="b_new_region" href="javascript:void(0);" style="display: none;"><?php _e('Add new'); ?></a>
                            </div>
                            <div style="clear:both;"></div>
                        </div>
                        <div id="i_regions" style="padding: 10px 0;">

                        </div>
                    </div>
                    <!-- End region -->
                    <!-- City -->
                    <div style="float:left; width: 32%; ">
                        <div style="border-bottom: 1px dashed black; padding: 4px 4px 0px; width: 90%;" >
                            <div style="float:left;">
                                <h3>
                                    <?php _e('Cities'); ?>
                                </h3>
                            </div>
                            <div style="float:right;">
                                <a id="b_new_city" href="javascript:void(0);" style="display:none;"><?php _e('Add new'); ?></a>
                            </div>
                            <div style="clear:both;"></div>
                        </div>
                        <div id="i_cities"  style="padding: 10px 0;">

                        </div>
                    </div>
                    <!-- End city -->
                    <div style="clear:both;"></div>
                </div>
                <!-- /settings form -->


                <!-- Form add country -->
        <div id="d_add_country" class="lightbox_country location" style="display:none;">
            <div style="padding: 14px;">
                <form action="<?php echo osc_admin_base_url(true); ?>" method="post" accept-charset="utf-8" id="d_add_country_form">
                    <div><small id="c_code_error" style="display: none;"><?php _e('Country code should have two characters'); ?></small></div>
                    <input type="hidden" name="page" value="settings" />
                    <input type="hidden" name="action" value="locations" />
                    <input type="hidden" name="type" value="add_country" />
                    <input type="hidden" name="c_manual" value="1" />
                    <label><?php _e('Country code'); ?>: </label><br />
                    <input type="text" id="c_country" name="c_country" value="" /><br />
                    <p>
                        <label><?php _e('Country'); ?>: </label><br />
                        <input type="text" id="country" name="country" value="" />
                    </p>
                </form>
            </div>
        </div>
        <!-- End form add country -->
        <!-- Form edit country -->
        <div id="d_edit_country" class="lightbox_country location" style="display:none;">
            <div style="padding: 14px;">
                <form action="<?php echo osc_admin_base_url(true); ?>" method="post" accept-charset="utf-8" id="d_edit_country_form">
                    <input type="hidden" name="page" value="settings" />
                    <input type="hidden" name="action" value="locations" />
                    <input type="hidden" name="type" value="edit_country" />
                    <input type="hidden" name="country_code" value="" />
                    <p>
                        <label><?php _e('Country'); ?>: </label><br />
                        <input type="text" id="e_country" name="e_country" value="" />
                    </p>
                </form>
            </div>
        </div>

        <!-- End form edit country -->
        <!-- Form add region -->
        <div id="d_add_region" class="lightbox_country location" style="display:none;">
            <div style="padding: 14px;">
                <form action="<?php echo osc_admin_base_url(true); ?>" method="post" accept-charset="utf-8" id="d_add_region_form">
                    <input type="hidden" name="page" value="settings" />
                    <input type="hidden" name="action" value="locations" />
                    <input type="hidden" name="type" value="add_region" />
                    <input type="hidden" name="country_c_parent" value="" />
                    <input type="hidden" name="country_parent" value="" />
                    <input type="hidden" name="r_manual" value="1" />
                    <table>
                        <tr>
                            <td><?php _e('Region'); ?>: </td>
                            <td><input type="text" id="region" name="region" value="" /></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>

        <!-- End form add region -->
        <!-- Form edit region -->
        <div id="d_edit_region" class="lightbox_country location" style="display:none;">
            <div style="padding: 14px;">
                <form action="<?php echo osc_admin_base_url(true); ?>" method="post" accept-charset="utf-8" id="d_edit_region_form">
                    <input type="hidden" name="page" value="settings" />
                    <input type="hidden" name="action" value="locations" />
                    <input type="hidden" name="type" value="edit_region" />
                    <input type="hidden" name="region_id" value="" />
                    <table>
                        <tr>
                            <td><?php _e('Region'); ?>: </td>
                            <td><input type="text" id="e_region" name="e_region" value="" /></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>

        <!-- End form edit region -->
        <!-- Form edit city -->
        <div id="d_add_city" class="lightbox_country location" style="display:none;">
            <div style="padding: 14px;">
                <form action="<?php echo osc_admin_base_url(true); ?>" method="post" accept-charset="utf-8" id="d_add_city_form">
                    <input type="hidden" name="page" value="settings" />
                    <input type="hidden" name="action" value="locations" />
                    <input type="hidden" name="type" value="add_city" />
                    <input type="hidden" name="country_c_parent" value="" />
                    <input type="hidden" name="country_parent" value="" />
                    <input type="hidden" name="region_parent" value="" />
                    <input type="hidden" name="ci_manual" value="1" />
                    <table>
                        <tr>
                            <td><?php _e('City'); ?>: </td>
                            <td><input type="text" id="city" name="city" value="" /></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>

        <!-- End form add city -->
        <!-- Form edit city -->
        <div id="d_edit_city" class="lightbox_country location" style="display:none;">
            <div style="padding: 14px;">
                <form action="<?php echo osc_admin_base_url(true); ?>" method="post" accept-charset="utf-8" id="d_edit_city_form">
                    <input type="hidden" name="page" value="settings" />
                    <input type="hidden" name="action" value="locations" />
                    <input type="hidden" name="type" value="edit_city" />
                    <input type="hidden" name="city_id" value="" />
                    <table>
                        <tr>
                            <td><?php _e('City'); ?>: </td>
                            <td><input type="text" id="e_city" name="e_city" value="" /></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        
        <!-- End form edit city -->
                </div>
            </div>
            <!-- /right container -->
        <?php if(Params::getParam('country')!='' && Params::getParam('country_code')!='') { ?>
            <script type="text/javascript">
                <?php if(Params::getParam('country')!='' && Params::getParam('country_code')!='') { ?>
                    show_region('<?php echo Params::getParam('country_code'); ?>', '<?php echo addslashes(Params::getParam('country')); ?>');
                    function hook_load_cities() {
                    <?php if(Params::getParam('region')!='') { ?>
                        show_city(<?php echo Params::getParam('region'); ?>);
                    <?php }; ?>
                    };
                <?php }; ?>
            </script>
        <?php }; ?>
        <!-- /container -->
        <?php osc_current_admin_theme_path('parts/footer.php') ; ?>