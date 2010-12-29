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
<div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
<div style="padding: 20px;">
<div>
<fieldset>
<legend><h1><?php _e("Breadcrumbs Help");?></h1></legend>

<h2><?php _e("What is Banner Management Plugin?");?></h2>
<?php _e("Banner Management allows you to manage different banners in your OSClass installation. Publish different banners inside your site in a few steps.");?>

<h2><?php _e("How does Banner Management work?");?></h2>
<?php _e("In Banner Management we have two entities, campaigns and banners. Think of a banner as an image, a flash movie or a text link, or what ever you want. Think of a campaign as a place holder for one or several banners, for example a list of images on your sidebar. Banners are 'inside' campaigns. This scheme allows you to manage your banners without having to change the whole HTML code each time, let's say you have a '5 images(banners) list' (campaign). Then, you may want to change one of the images(banner) for a Flash movie, you simply edit that banner to include a Flash movie, and that's all. If for example you want to reduce the list from 5 to 4 banners, you only have to modify your campaign.");?>


<h2><?php _e("How to use Banner Management?");?></h2>
<h3><?php _e("Create a banner first");?></h3>
<?php _e("Go to Banner Management menu in admin panel and select 'Manage banners' option. On the left side you have a list of your created banners. On the right side you have a form to create a new one. Enter a name to indentify the banner and use the editor to create your banner. You could use the rich-text editor or edit directly the HTML code of your banner.");?>

<br />
<br />

<h3><?php _e("Create a campaign");?></h3>
<?php _e("Go to Banner Management menu in admin panel and select 'Manage banners' option. On the left side you hace a drop-down list with your created campaigns. On the right side, you have a form to create a new one. Enter a name to indentify the campaign and use the editor to create it. You could use the rich-text editor or edit directly the HTML code of your campaign.");?>

<br />
<br />

<h3><?php _e("Insert a banner inside your campaign");?></h3>
<?php _e("Use the option in the campaign menu to add/remove some banners to/from your campaign. Each banner could be in several campaigns and each campaign could have severals banners.");?>

<br />
<br />

<h3><?php _e("Edit the HTML code of your campaign");?></h3>
<?php _e("To insert a banner on your campaign is as easy as put '[banner]' (without quotes) on the HTML of your campaign. The [banner] text will be replaced with the HTML code of your banner. You could add a specific banner adding the following text '[banner#banner_name]' (without quotes) where 'banner_name' is the name of your banner.");?>

<br />
<br />

<h3><?php _e("Edit the HTML code of the theme");?></h3>
<?php _e("You should edit the HTML code of the theme and include &lt;?php bm_show_campaign('campaign_name'); ?&gt; to call to 'campaign_name' campaign and show it on the website. You could use the bm_show_campaign as many time as you want.");?>


</fieldset>
</div></div></div>
