<?php
/*
 * Copyright 2014 Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

    // meta tag robots
    osc_add_hook('header','bender_nofollow_construct');

    osc_enqueue_script('jquery-validate');
    bender_add_body_class('user user-profile');
    osc_add_hook('before-main','sidebar');
    function sidebar(){
        osc_current_web_theme_path('user-sidebar.php');
    }
    osc_add_filter('meta_title_filter','custom_meta_title');
    function custom_meta_title($data){
        return __('Change e-mail', 'bender');;
    }
    osc_current_web_theme_path('header.php') ;
    $osc_user = osc_user();
?>
<h1><?php _e('Change e-mail', 'bender'); ?></h1>
<div class="form-container form-horizontal">
    <div class="resp-wrapper">
        <ul id="error_list"></ul>
        <form action="<?php echo osc_base_url(true); ?>" method="post">
            <input type="hidden" name="page" value="user" />
            <input type="hidden" name="action" value="change_email_post" />
            <div class="control-group">
                <label for="email"><?php _e('Current e-mail', 'bender'); ?></label>
                <div class="controls">
                    <?php echo osc_logged_user_email(); ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="new_email"><?php _e('New e-mail', 'bender'); ?> *</label>
                <div class="controls">
                    <input type="text" name="new_email" id="new_email" value="" />
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <button type="submit" class="ui-button ui-button-middle ui-button-main"><?php _e("Update", 'bender');?></button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('form#change-email').validate({
            rules: {
                new_email: {
                    required: true,
                    email: true
                }
            },
            messages: {
                new_email: {
                    required: '?php echo osc_esc_js(__("Email: this field is required", "bender")); ?>.',
                    email: '<?php echo osc_esc_js(__("Invalid email address", "bender")); ?>.'
                }
            },
            errorLabelContainer: "#error_list",
            wrapper: "li",
            invalidHandler: function(form, validator) {
                $('html,body').animate({ scrollTop: $('h1').offset().top }, { duration: 250, easing: 'swing'});
            },
            submitHandler: function(form){
                $('button[type=submit], input[type=submit]').attr('disabled', 'disabled');
                form.submit();
            }
        });
    });
</script>
<?php osc_current_web_theme_path('footer.php') ; ?>