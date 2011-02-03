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

$subCats = Category::newInstance()->findSubcategories($category);
?>

<div>
<span class="categoryHeader" ><?php echo $category['s_name']; ?></span> <a href="<?php echo osc_createItemPostURL($category); ?>" class="categoryLink" ><?php _e('Publish your item'); ?></a>
</div>

<div class="categoryList">
<?php foreach($subCats as $cat): ?>
	<a title="<?php echo $cat['s_description']; ?>" style="padding-right: 10px;" href="<?php osc_createCategoryURL($cat, true); ?>"><?php echo $cat['s_name']; ?></a>
<?php endforeach; ?>
</div>

<?php if(count($ads) == 0) { ?>
	<p><?php _e('There are no results in this category yet.'); ?></p>
<?php } else { ?>
	<?php foreach($ads as $i): ?>
	<div class="itemInCategory" >
	<?php if(osc_itemHasThumbnail($i)): ?>
		<a href="<?php osc_createItemURL($i, true); ?>"><img src="<?php echo osc_itemThumbnail($i); ?>" style="border: 0px; float: left;" /></a>
	<?php endif; ?>
	<div class="itemInCategoryData" >
		<p><a href="<?php osc_createItemURL($i, true); ?>" style="color: blue;"><?php echo $i['s_title']; ?></a></p>
		<p><?php echo strip_tags($i['s_description']); ?></p>
	</div>
	<div class="itemInCategoryPrice" ><?php echo osc_formatPrice($i); ?></div>
	<div style="clear: both;"></div>
	</div>
	<?php endforeach; ?>
<?php } ?>