<?php
    /*
     *      Osclass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2014 OSCLASS
     *
     *       This program is free software: you can redistribute it and/or
     *     modify it under the terms of the GNU Affero General Public License
     *     as published by the Free Software Foundation, either version 3 of
     *            the License, or (at your option) any later version.
     *
     *     This program is distributed in the hope that it will be useful, but
     *         WITHOUT ANY WARRANTY; without even the implied warranty of
     *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *             GNU Affero General Public License for more details.
     *
     *      You should have received a copy of the GNU Affero General Public
     * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
     */
?>

<?php if ( (!defined('ABS_PATH')) ) exit('ABS_PATH is not loaded. Direct access is not allowed.'); ?>
<?php if ( !OC_ADMIN ) exit('User access is not allowed.'); ?>
<?php if( (!defined('MULTISITE') || MULTISITE==0)&& !osc_get_preference('footer_link', 'bender') && !osc_get_preference('donation', 'bender') ) { ?>
<form name="_xclick" action="https://www.paypal.com/in/cgi-bin/webscr" method="post" class="nocsrf">
    <input type="hidden" name="cmd" value="_donations">
    <input type="hidden" name="rm" value="2">
    <input type="hidden" name="business" value="info@osclass.org">
    <input type="hidden" name="item_name" value="Osclass project">
    <input type="hidden" name="return" value="http://osclass.org/paypal/">
    <input type="hidden" name="currency_code" value="USD">
    <input type="hidden" name="lc" value="US" />
    <input type="hidden" name="custom" value="<?php echo osc_admin_render_theme_url('oc-content/themes/bender/admin/settings.php'); ?>&donation=successful&source=bender">
    <div id="flashmessage" class="flashmessage flashmessage-inline flashmessage-warning" style="color: #505050; display: block; ">
        <p><?php _e('I would like to contribute to the development of Osclass with a donation of', 'bender'); ?> <select name="amount" class="select-box-medium">
            <option value="50">50$</option>
            <option value="25">25$</option>
            <option value="10" selected>10$</option>
            <option value="5">5$</option>
            <option value=""><?php _e('Custom', 'bender'); ?></option>
        </select><input type="submit" class="btn btn-mini" name="submit" value="<?php echo osc_esc_html(__('Donate', 'bender')); ?>"></p>
    </div>
</form>
<?php } ?>
<h2 class="render-title <?php echo (osc_get_preference('footer_link', 'bender') ? '' : 'separate-top'); ?>"><?php _e('Theme settings', 'bender'); ?></h2>
<form action="<?php echo osc_admin_render_theme_url('oc-content/themes/bender/admin/settings.php'); ?>" method="post" class="nocsrf">
    <input type="hidden" name="action_specific" value="settings" />
    <fieldset>
        <div class="form-horizontal">
            <div class="form-row">
                <div class="form-label"><?php _e('Search placeholder', 'bender'); ?></div>
                <div class="form-controls"><input type="text" class="xlarge" name="keyword_placeholder" value="<?php echo osc_esc_html( osc_get_preference('keyword_placeholder', 'bender') ); ?>"></div>
            </div>
            <div class="form-row">
                <div class="form-label"><?php _e('Show lists as:', 'bender'); ?></div>
                <div class="form-controls">
                    <select name="defaultShowAs@all">
                        <option value="gallery" <?php if(bender_default_show_as() == 'gallery'){ echo 'selected="selected"' ; } ?>><?php _e('Gallery','bender'); ?></option>
                        <option value="list" <?php if(bender_default_show_as() == 'list'){ echo 'selected="selected"' ; } ?>><?php _e('List','bender'); ?></option>
                    </select>
                </div>
            </div>
            <?php if(!defined('MULTISITE') || MULTISITE==0) { ?>
            <div class="form-row">
                <div class="form-label"><?php _e('Footer link', 'bender'); ?></div>
                <div class="form-controls">
                    <div class="form-label-checkbox"><input type="checkbox" name="footer_link" value="1" <?php echo (osc_get_preference('footer_link', 'bender') ? 'checked' : ''); ?> > <?php _e('I want to help Osclass by linking to <a href="http://osclass.org/" target="_blank">osclass.org</a> from my site with the following text:', 'bender'); ?></div>
                    <span class="help-box"><?php _e('This website is proudly using the <a title="Osclass web" href="http://osclass.org/">classifieds scripts</a> software <strong>Osclass</strong>', 'bender'); ?></span>
                </div>
            </div>
            <?php } ?>
        </div>
    </fieldset>

    <h2 class="render-title"><?php _e('Location input', 'bender'); ?></h2>
    <fieldset>
        <div class="form-horizontal">
            <div class="form-row">
                <div class="form-label"><?php _e('Show location input as:', 'bender'); ?></div>
                <div class="form-controls">
                    <select name="defaultLocationShowAs">
                        <option value="dropdown" <?php if(bender_default_location_show_as() == 'dropdown'){ echo 'selected="selected"' ; } ?>><?php _e('Dropdown','bender'); ?></option>
                        <option value="autocomplete" <?php if(bender_default_location_show_as() == 'autocomplete'){ echo 'selected="selected"' ; } ?>><?php _e('Autocomplete','bender'); ?></option>
                    </select>
                </div>
            </div>
        </div>
    </fieldset>

    <h2 class="render-title"><?php _e('Ads management', 'bender'); ?></h2>
    <div class="form-row">
        <div class="form-label"></div>
        <div class="form-controls">
            <p><?php _e('In this section you can configure your site to display ads and start generating revenue.', 'bender'); ?><br/><?php _e('If you are using an online advertising platform, such as Google Adsense, copy and paste here the provided code for ads.', 'bender'); ?></p>
        </div>
    </div>
    <fieldset>
        <div class="form-horizontal">
            <div class="form-row">
                <div class="form-label"><?php _e('Header 728x90', 'bender'); ?></div>
                <div class="form-controls">
                    <textarea style="height: 115px; width: 500px;"name="header-728x90"><?php echo osc_esc_html( osc_get_preference('header-728x90', 'bender') ); ?></textarea>
                    <br/><br/>
                    <div class="help-box"><?php _e('This ad will be shown at the top of your website, next to the site title and above the search results. Note that the size of the ad has to be 728x90 pixels.', 'bender'); ?></div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label"><?php _e('Homepage 728x90', 'bender'); ?></div>
                <div class="form-controls">
                    <textarea style="height: 115px; width: 500px;" name="homepage-728x90"><?php echo osc_esc_html( osc_get_preference('homepage-728x90', 'bender') ); ?></textarea>
                    <br/><br/>
                    <div class="help-box"><?php _e('This ad will be shown on the main site of your website. It will appear both at the top and bottom of your site homepage. Note that the size of the ad has to be 728x90 pixels.', 'bender'); ?></div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label"><?php _e('Search results 728x90 (top of the page)', 'bender'); ?></div>
                <div class="form-controls">
                    <textarea style="height: 115px; width: 500px;" name="search-results-top-728x90"><?php echo osc_esc_html( osc_get_preference('search-results-top-728x90', 'bender') ); ?></textarea>
                    <br/><br/>
                    <div class="help-box"><?php _e('This ad will be shown on top of the search results of your site. Note that the size of the ad has to be 728x90 pixels.', 'bender'); ?></div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label"><?php _e('Search results 728x90 (middle of the page)', 'bender'); ?></div>
                <div class="form-controls">
                    <textarea style="height: 115px; width: 500px;" name="search-results-middle-728x90"><?php echo osc_esc_html( osc_get_preference('search-results-middle-728x90', 'bender') ); ?></textarea>
                    <br/><br/>
                    <div class="help-box"><?php _e('This ad will be shown among the search results of your site. Note that the size of the ad has to be 728x90 pixels.', 'bender'); ?></div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label"><?php _e('Sidebar 300x250', 'bender'); ?></div>
                <div class="form-controls">
                    <textarea style="height: 115px; width: 500px;" name="sidebar-300x250"><?php echo osc_esc_html( osc_get_preference('sidebar-300x250', 'bender') ); ?></textarea>
                    <br/><br/>
                    <div class="help-box"><?php _e('This ad will be shown at the right sidebar of your website, on the product detail page. Note that the size of the ad has to be 300x350 pixels.', 'bender'); ?></div>
                </div>
            </div>
            <div class="form-actions">
                <input type="submit" value="<?php _e('Save changes', 'bender'); ?>" class="btn btn-submit">
            </div>
        </div>
    </fieldset>
</form>
