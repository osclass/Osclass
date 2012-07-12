<h2 class="render-title"><?php _e('Google Analytics', 'google_analytics'); ?></h2>
<form action="<?php echo osc_admin_render_plugin_url('google_analytics/admin.php'); ?>" method="post">
    <input type="hidden" name="option" value="stepone" />
    <fieldset>
        <div class="form-horizontal">
            <div class="form-row">
                <div class="form-label"><?php _e('Tracking ID', 'google_analytics') ?></div>
                <div class="form-controls"><input type="text" class="xlarge" name="webid" value="<?php echo osc_esc_html( osc_google_analytics_id() ); ?>"></div>
            </div>
            <div class="form-actions">
                <input type="submit" value="Save changes" class="btn btn-submit">
            </div>
        </div>
    </fieldset>
</form>