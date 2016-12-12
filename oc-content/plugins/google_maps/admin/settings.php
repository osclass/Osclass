<?php
if (Params::getParam('plugin_action') == 'done') {
    osc_set_preference('maps_key', Params::getParam('maps_key'), 'google_maps');
    osc_set_preference('include_maps_js', (Params::getParam('include_maps_js') ? '1' : '0'), 'google_maps');
    // HACK : This will make possible use of the flash messages ;)
    ob_get_clean();
    osc_add_flash_ok_message(__('Settings updated correctly', 'google_maps'), 'admin');

    osc_redirect_to( osc_route_admin_url('google_maps_settings') );
}
?>

<form action="<?php echo osc_route_admin_url('google_maps_settings') ?>" method="post" class="nocsrf">
    <input type="hidden" name="plugin_action" value="done" />
    <h2 class="render-title"><?php _e('Javascript', 'google_maps'); ?></h2>
    <div class="form-horizontal">
        <div class="form-label"><?php _e('Load Google Maps javascript library.', 'google_maps'); ?></div>
        <div class="form-controls">
            <div class="form-label-checkbox">
                <label><input type="checkbox" <?php echo ( (osc_get_preference('include_maps_js', 'google_maps') != '0') ? 'checked="checked"' : ''); ?> name="include_maps_js" value="1" /> <?php _e('load/unload', 'google_maps'); ?></label>
                <br>
                <p><span class="help-box"><?php _e('Some plugins already load the Google Maps library and can be loaded twice or more. Disable this option if you don\'t want to load google maps library.', 'google_maps'); ?></span></p>
            </div>
        </div>
    </div>
    <div class="form-horizontal">
        <div class="form-label"><?php _e('Google Maps API key.', 'google_maps'); ?></div>
        <div class="form-controls">
            <div class="form-label-checkbox">
                <input type="text" value="<?php echo osc_esc_html(osc_get_preference('maps_key', 'google_maps')); ?>" name="maps_key"/>
                <br>
                <p><span class="help-box"><?php _e('Create an API key as per instructions here: <a href="https://developers.google.com/maps/documentation/javascript/get-api-key">Get api key</a>', 'google_maps'); ?></span></p>
            </div>
        </div>
    </div>
    <div class="form-actions">
        <input type="submit" value="<?php _e('Save changes', 'google_maps'); ?>" class="btn btn-submit">
    </div>
</form>