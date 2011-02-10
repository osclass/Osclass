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
<meta name="generator" content="OSClass <?php echo OSCLASS_VERSION ; ?>" />
<title><?php echo osc_page_title() ; ?></title>
<?php foreach ($this->get_css() as $css) { ?>
    <link href="<?php echo $css ; ?>" rel="stylesheet" type="text/css" />
<?php } ?>

<?php foreach ($this->get_js() as $js) { ?>
    <script type="text/javascript" src="<?php echo $js ; ?>"></script>
<?php } ?>
<?php osc_run_hook('header') ; ?>