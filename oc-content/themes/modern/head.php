<?php
    /*
     *      OSCLass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2010 OSCLASS
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

<meta http-equiv="Content-type" content="text/html; charset=utf-8" />

<title><?php echo meta_title() ; ?></title>
<meta name="title" content="<?php echo meta_title() ; ?>" />
<meta name="description" content="<?php echo meta_description() ; ?>" />
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="Expires" content="Fri, Jan 01 1970 00:00:00 GMT" />

<link href="<?php echo osc_current_web_theme_url('combine.php?type=css&files=style.css,tabs.css') ; ?>" rel="stylesheet" type="text/css" />

<script>
    var fileDefaultText = '<?php _e('No file selected', 'modern') ; ?>';
    var fileBtnText     = '<?php _e('Choose File', 'modern') ; ?>';
</script>

<script type="text/javascript" src="<?php echo osc_current_web_theme_url('combine.php?type=js&files=js/jquery.js,js/jquery-ui.js,js/jquery.uniform.js,js/global.js,js/tabber-minimized.js') ; ?>"></script>

<?php osc_run_hook('header') ; ?>