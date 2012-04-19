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

    if( !function_exists('add_close_button_fm_admin') ) {
        function add_close_button_fm_admin($message) {
            return '<a href="#" class="close">×</a><p>' . $message . '</p>' ;
        }

        osc_add_filter('flash_message_text', 'add_close_button_fm_admin') ;
    }
?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php _e('OSClass Admin Panel'); ?></title>

<link href="<?php echo osc_current_admin_theme_styles_url('jquery-ui.css') ; ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo osc_current_admin_theme_styles_url('backoffice.css') ; ?>" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.js') ; ?>"></script>
<script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery-ui.js') ; ?>"></script>
<script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.cookie.js') ; ?>"></script>
<script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('jquery.json.js') ; ?>"></script>
<!-- global js -->
<script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('global.js') ; ?>"></script>
<!-- /global js -->

<script type="text/javascript">
    var fileDefaultText = '<?php echo osc_esc_js( __('No file selected', 'modern') ) ; ?>';
    var fileBtnText     = '<?php echo osc_esc_js( __('Choose File', 'modern') ) ; ?>';
</script>